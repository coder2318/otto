<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Lulu\LineItem;
use App\Data\Lulu\PrintableNormalization;
use App\Data\Lulu\PrintJobDetails;
use App\Data\Lulu\ShippingOption;
use App\Data\Story\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FontController;
use App\Http\Requests\Stories\ChapterOrderRequest;
use App\Http\Requests\Stories\OrderCostRequest;
use App\Http\Requests\Stories\StoreStoryRequest;
use App\Http\Requests\Stories\StoriesRequest;
use App\Http\Requests\Stories\UpdateStoryRequest;
use App\Http\Resources\BookCoverTemplateResource;
use App\Http\Resources\BookUserCoverTemplateResource;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\StoryTypeResource;
use App\Http\Resources\TimelineResource;
use App\Jobs\RegenerateBookCover;
use App\Jobs\RegenerateBookPreview;
use App\Models\BookCoverTemplate;
use App\Models\BookUserCoverTemplate;
use App\Models\Story;
use App\Models\StoryType;
use App\Models\User;
use App\Services\LuluService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Sokil\IsoCodes\Database\Countries\Country;
use Sokil\IsoCodes\Database\Subdivisions\Subdivision;
use Sokil\IsoCodes\IsoCodesFactory;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Story::class, 'story');
    }

    public function index(StoriesRequest $request)
    {
        return Inertia::render('Dashboard/Stories/Index', [
            'stories' => fn() => StoryResource::collection(
                $request->stories($request->user()->stories()->with('activeUserCoverTemplate'))
                    ->paginate(6)
                    ->appends($request->query())
            ),
            'fonts' => FontController::getFonts()
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Stories/Create', [
            'story_types' => fn() => StoryTypeResource::collection(
                StoryType::all(['id', 'name'])
            ),
        ]);
    }

    public function write(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Write', [
            'story' => fn() => StoryResource::make($story),
        ]);
    }

    public function show(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Show', [
            'story' => fn() => StoryResource::make(
                $story
                    ->load('activeUserCoverTemplate')
                    ->append(['pages', 'words', 'progress'])
            ),
            'fonts' => FontController::getFonts()
        ]);
    }

    public function store(StoreStoryRequest $request)
    {
        /** @var Story $story */
        $story = Story::create($request->validated() + [
            'status' => Status::PENDING,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('dashboard.stories.show', compact('story'))->with('message', 'Story created successfully!');
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        $story->update($request->validated());
        $hasCover = $request->validated('cover', false);

        if ($hasCover) {
            /** @var \App\Models\Media|null */
            $oldCover = $story->cover;
            $meta = $request->validated('meta', []);
            $saveAsNewUserTemplate = $request->validated('saveAsNewUserTemplate', false);

            $files = [];
            $parameters = [];

            foreach ($meta as $key => $value) {
                $value instanceof UploadedFile
                    ? $files[$key] = $value
                    : $parameters[$key] = $value;
            }

            /** @var \App\Models\Media */
            $cover = $story->addMediaFromRequest('cover')
                ->setName('book-cover-image')
                ->setFileName('book-cover-image'. $story->id . '.' . $request->file('cover')->extension())
                ->toMediaCollection('cover');

            $oldCover?->media()->update([
                'model_id' => $cover->id,
            ]);
            $oldCover?->delete();

            foreach ($files as $key => $value) {
                $cover->clearMediaCollection($key);
                $cover->addMedia($value)->toMediaCollection($key);
            }

            foreach ($parameters as $key => $value) {
                $cover->setCustomProperty($key, $value);
            }

            $cover->save();

            $story->load('activeUserCoverTemplate');
            $userCoverTemplate = $story->activeUserCoverTemplate;
            $saveAsUserTemplate = $meta['saveAsUserTemplate'] ?? false;

            $selectedUserTemplateId = $meta['user_template_id'] ?? null;
            $templateId = $meta['template_id'] ?? 1;
            $activeUserCoverTemplate = [];

            if (!$selectedUserTemplateId || $saveAsNewUserTemplate) {
                $oldUserTemplate = BookUserCoverTemplate::where('id', $selectedUserTemplateId)->first();

                $this->createOrUpdateUserTemplate($story, new BookUserCoverTemplate(), $parameters, $files, $templateId, $oldUserTemplate);
            } else {
                $userCoverTemplate = BookUserCoverTemplate::where('id', $selectedUserTemplateId)->first();
                if ($userCoverTemplate) {
                    $this->createOrUpdateUserTemplate($story, $userCoverTemplate, $parameters, $files, $templateId);
                } else {
                    return redirect()->back()->with('error', 'User cover template not found');
                }
            }

            dispatch(new RegenerateBookCover($story));
        }

        dispatch(new RegenerateBookPreview($story));

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'Story updated successfully!');
    }

    public function destroy(Story $story)
    {
        $story->delete();

        return redirect()->route('dashboard.stories.index')->with('message', 'Story deleted successfully!');
    }

    public function cover(Story $story, string $type = 'default', ?int $id = null)
    {
        $storyResource = StoryResource::make($story->append('pages')->load('activeUserCoverTemplate'));

        $template = [];
        $templateId = ($type == 'default' && $id) ? $id : ($story->activeUserCoverTemplate?->template_id ?? 1); // @phpstan-ignore-line

        if ($story->activeUserCoverTemplate && !$id) {
            $template = BookUserCoverTemplateResource::make($story->activeUserCoverTemplate);
            $type = 'user';
        } else if ($type === 'user' && $id) {
            $userCoverTemplate = BookUserCoverTemplate::with(['story', 'template'])->where('id', $id)->first();
            if ($userCoverTemplate) {
                $template = BookUserCoverTemplateResource::make($userCoverTemplate);
            }
        } else {
            $bookCoverTemplate = BookCoverTemplate::where('id', $templateId)->first();
            $template = BookCoverTemplateResource::make($bookCoverTemplate)
                ->additional(['activeUserCoverTemplate' => $story->activeUserCoverTemplate]);
        }

        return Inertia::render('Dashboard/Stories/Cover', [
            'story' => fn() => $storyResource,
            'templateData' => fn() => $template,
            'templateType' => $type,
            'templateId' => $id,
            'fonts' => FontController::getFonts()
        ]);
    }

    public function covers(Story $story)
    {
        $storyResource = StoryResource::make($story->append('pages')->load('activeUserCoverTemplate'));
        $activeUserCoverTemplate = $story->activeUserCoverTemplate;

        $userCoverTemplatesQuery = BookUserCoverTemplate::with(['template'])
            ->where('story_id', $story->id)
            ->when($activeUserCoverTemplate, function ($query) use ($activeUserCoverTemplate) {
                return $query->orderByRaw("FIELD(id, {$activeUserCoverTemplate->id}) DESC")
                    ->orderBy('created_at', 'desc');
            }, function ($query) {
                return $query->orderBy('created_at', 'desc');
            });

        $userCoverTemplates = $userCoverTemplatesQuery->paginate(10, ['*'], 'upage');

        $coverTemplates = BookCoverTemplate::paginate(10);
        $coverTemplates->map(function ($coverTemplate) use ($activeUserCoverTemplate) {
            $coverTemplate->activeUserCoverTemplate = $activeUserCoverTemplate;
        });

        return Inertia::render('Dashboard/Stories/Covers', [
            'story' => fn() => $storyResource,
            'covers' => fn() => BookCoverTemplateResource::collection($coverTemplates)->additional(['parameters' => $activeUserCoverTemplate?->parameters ?? []]),
            'userCovers' => fn() => BookUserCoverTemplateResource::collection($userCoverTemplates),
            'activeCoverId' => fn() => @$activeUserCoverTemplate?->id ?? null,
            'fonts' =>  FontController::getFonts(),
        ]);
    }

    private function createOrUpdateUserTemplate(
        Story $story,
        BookUserCoverTemplate $userTemplate,
        array $parameters,
        array $files,
        int $templateId,
        ?BookUserCoverTemplate $oldUserTemplate = null,
        ?bool $setActiveUserCoverTemplate = true
    ): BookUserCoverTemplate {
        $oldMediaList = null;
        $isNew = !@$userTemplate?->id;

        if ($oldUserTemplate) {
            $oldMediaList = $oldUserTemplate?->media;
        } else {
            $oldMediaList = $story->activeUserCoverTemplate?->media;
        }

        $userTemplate->fill([
            'parameters' => $parameters,
            'story_id' => $story->id,
            'template_id' => $templateId ?? $userTemplate->template_id
        ])->save();

        if ($isNew) {
            foreach (($oldMediaList ?? []) as $mediaItem) {
                $matchingFiles = [];

                foreach ($files as $key => $file) {
                    if ($key === $mediaItem->collection_name) {
                        $matchingFiles[] = $file;
                    }
                }

                if (empty($matchingFile)) {
                    try {
                        $userTemplate->addMediaFromDisk($mediaItem->getPath())
                            ->preservingOriginal()
                            ->toMediaCollection($mediaItem->collection_name);
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', $e->getMessage());
                    }
                }
            }
        }

        foreach ($files as $key => $value) {
            $userTemplate->clearMediaCollection($key);
            $userTemplate->addMedia($value)->toMediaCollection($key);
        }

        $userTemplate->save();

        if ($setActiveUserCoverTemplate) {
            $story->activeUserCoverTemplate()->associate($userTemplate);
            $story->save();
        }

        return $userTemplate;
    }

    public function coverDelete(Story $story, Request $request, ?int $id = null)
    {
        if ($id) {
            $story->load('activeUserCoverTemplate');
            if ($story->activeUserCoverTemplate?->id === $id) {
                $activeUserCoverTemplate = BookUserCoverTemplate::where('story_id', $story->id)
                    ->where('id', '!=', $id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                $story->activeUserCoverTemplate()->associate($activeUserCoverTemplate);
                $story->save();
            }
            BookUserCoverTemplate::where('id', $id)?->first()?->delete();
        }

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'User cover deleted successfully!');
    }

    public function edit(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Edit', [
            'story' => fn() => StoryResource::make($story),
            'chapters' => fn() => $story->chapters()
                ->orderBy('timeline_id', 'asc')
                ->orderBy('order', 'asc')
                ->where('status', Status::PUBLISHED)
                ->get([
                    'id',
                    'title',
                    'status',
                    'timeline_id',
                    'order',
                ])
                ->groupBy('timeline_id')
                ->map(fn($chapters) => ChapterResource::collection($chapters)),
            'timelines' => fn() => TimelineResource::collection(
                $story->storyType->timelines()->get(['id', 'title'])
            ),
            'fonts' => FontController::getFonts()
        ]);
    }

    public function saveContents(Story $story, ChapterOrderRequest $request)
    {
        DB::transaction(function () use ($story, $request) {
            foreach ($request->validated('timelines', []) as $data) {
                $story->update(['font' => $request->validated('font', config('story.default_font'))]);

                if (empty($data['chapters'])) {
                    continue;
                }

                $story->chapters()->whereIntegerInRaw('id', $data['chapters'])->update([
                    'timeline_id' => $data['id'],
                ]);

                foreach ($data['chapters'] as $order => $chapter) {
                    $story->chapters()->where('id', $chapter)->update(compact('order'));
                }
            }
        });

        dispatch(new RegenerateBookPreview($story));

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'Contents saved successfully!');
    }

    public function preview(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Preview', [
            'story' => fn() => StoryResource::make($story->load('book_preview')->load('book')),
            'chapters' => fn() => ChapterResource::collection(
                $story->chapters()->where('status', Status::PUBLISHED)
                    ->orderBy('timeline_id', 'asc')
                    ->orderBy('order', 'asc')
                    ->get(['id', 'title'])
            ),
        ]);
    }

    public function regenerate_status(Story $story)
    {
        $regenerateStatus = false;
        $regeneratePreviewStatus = false;

        $queuesDefault = Redis::lrange('queues:default', 0, -1);
        $queuesReserved = Redis::zrange('queues:default:reserved', 0, -1);
        $queues = array_merge($queuesDefault, $queuesReserved);

        foreach ($queues as $queueJson) {
            $queue = json_decode($queueJson);
            $firstTag = ($queue->tags[0] ?? '');

            if ($firstTag !== "App\Models\Story:{$story->id}") {
                continue;
            }

            if ($queue->displayName == 'App\Jobs\RegenerateBook') {
                $regenerateStatus = true;
            }

            if ($queue->displayName == 'App\Jobs\RegenerateBookPreview') {
                $regeneratePreviewStatus = true;
            }
        }

        return response()->json([
            'regenerate_status' => $regenerateStatus,
            'regenerate_preview_status' => $regeneratePreviewStatus,
        ]);
    }

    public function order(Story $story, IsoCodesFactory $iso, OrderCostRequest $request)
    {
        if (! $story->activeUserCoverTemplate) {
            return redirect()->route('dashboard.stories.cover', $story)->with('error', trans('Please create cover before ordering!'));
        }

        return Inertia::render('Dashboard/Stories/Order', [
            'story' => fn() => StoryResource::make($story->append('pages')),
            'coverTemplate' => fn() => BookUserCoverTemplateResource::make($story->activeUserCoverTemplate),
            'countries' => fn() => collect($iso->getCountries())->map(fn(Country $country) => [
                'name' => $country->getName(),
                'code' => $country->getAlpha2(),
            ]),
            'payment' => fn() => $request->user()->defaultPaymentMethod(),
            'states' => fn() => $request->has('country_code') ? collect($iso->getSubdivisions()->getAllByCountryCode($request->validated('country_code')))
                ->map(fn(Subdivision $subdivision) => [
                    'name' => $subdivision->getName(),
                    'code' => explode('-', $subdivision->getCode())[1],
                ])->values() : [],
            'fonts' => FontController::getFonts()
        ]);
    }

    public function orderCost(Story $story, LuluService $lulu, OrderCostRequest $request)
    {
        if ($story->pages < 32) {
            return redirect()->back()->with('error', trans('A book must have at least 32 pages!'));
        }

        $luluSettings = \App\Models\LuluPrintSettings::where('is_enabled', '=', 1)->firstOrFail();
        $pod_package_id = $luluSettings->getPackageId();

        $cost = rescue(fn() => $lulu->cost(
            LineItem::from([
                'page_count' => $story->pages,
                'pod_package_id' => $pod_package_id,
                'quantity' => $request->validated('quantity'),
            ]),
            $request->shippingAddress(),
            ShippingOption::from($request->validated('shipping_method')),
        ), fn($e) => Session::flash('error', $e->getMessage()));

        if ($cost) {
            Session::put("print-cost-{$story->id}", $cost);
        }

        return Inertia::render('Dashboard/Stories/Order', [
            'story' => fn() => StoryResource::make($story->load('activeUserCoverTemplate')->append('pages')),
            'price' => fn() => $cost,
        ]);
    }

    public function orderPurchase(Story $story, LuluService $lulu, OrderCostRequest $request)
    {
        if (! $story->book) {
            return redirect()->back()->with('error', trans('Book is being processed. Please wait!'));
        }

        if (! $story->book_cover) {
            return redirect()->back()->with('error', trans('Your book has no cover or it is being processed. Please wait!'));
        }

        abort_unless(Session::has("print-cost-{$story->id}"), 403);

        $cost = Session::get("print-cost-{$story->id}");

        /** @var User */
        $user = $request->user();

        if (! $user->can('free-books')) {
            try {
                $payment = $user->charge($cost * 100, $user->paymentMethods()->first()->id);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        $luluSettings = \App\Models\LuluPrintSettings::where('is_enabled', '=', 1)->firstOrFail();
        $pod_package_id = $luluSettings->getPackageId();

        $print = $lulu->print(
            config('mail.from.address'),
            LineItem::from([
                'printable_normalization' => PrintableNormalization::from([
                    'external_id' => isset($payment) ? $payment->external_id : null, // @phpstan-ignore-line
                    'pod_package_id' => $pod_package_id,
                    'cover' => ['source_url' => $story->book_cover->getTemporaryUrl(now()->addHour())], // @phpstan-ignore-line
                    'interior' => ['source_url' => $story->book->getTemporaryUrl(now()->addHour())], // @phpstan-ignore-line
                ]),
                'quantity' => $request->validated('quantity'),
                'title' => $story->title,
            ]),
            $request->shippingAddress(),
            ShippingOption::from($request->validated('shipping_method')),
        );

        $story->printJobs()->create([
            'lulu_id' => $print['id'],
            'details' => PrintJobDetails::from($print),
        ]);

        $story->update([
            'status' => Status::PUBLISHED,
        ]);

        Session::forget("print-cost-{$story->id}");

        return redirect()->back()->with('message', 'Print job created successfully!');
    }
}
