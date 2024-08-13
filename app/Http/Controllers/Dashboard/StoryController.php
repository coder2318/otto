<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Lulu\LineItem;
use App\Data\Lulu\PrintableNormalization;
use App\Data\Lulu\PrintJobDetails;
use App\Data\Lulu\ShippingOption;
use App\Data\Story\Status;
use App\Http\Controllers\Controller;
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
use App\Models\Setting;
use App\Services\LuluService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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
                $request->stories($request->user()->stories()->with('cover'))
                    ->paginate(6)
                    ->appends($request->query())
            ),
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
                    ->load('cover')
                    ->append(['pages', 'words', 'progress'])
            ),
        ]);
    }

    public function store(StoreStoryRequest $request)
    {
        /** @var Story $story */
        $story = Story::create($request->validated() + [
            'status' => Status::PENDING,
            'user_id' => $request->user()->id,
        ]);

        if ($request->hasFile('cover')) {
            $story->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return redirect()->route('dashboard.stories.show', compact('story'))->with('message', 'Story created successfully!');
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        $story->update($request->validated());

        if ($request->hasFile('cover')) {
            /** @var \App\Models\Media|null */
            $oldCover = $story->cover;

            $files = [];
            $parameters = [];

            foreach ($request->validated('meta', []) as $key => $value) {
                $value instanceof UploadedFile
                    ? $files[$key] = $value
                    : $parameters[$key] = $value;
            }

            /** @var \App\Models\Media */
            $cover = $story->addMediaFromRequest('cover')->toMediaCollection('cover');
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

    public function cover(Story $story, Request $request, string $type = 'default', ?int $id = null)
    {
        $storyResource = StoryResource::make($story->append('pages')->load('cover'));

        $templateId = ($type == 'default' && $id) ? $id : ($story->cover?->getCustomProperty('template_id') ?? 1); // @phpstan-ignore-line

        $bookCoverTemplate = BookCoverTemplate::where('id', $templateId)->orderBy('created_at')->first();
        $bookCoverTemplateResource = BookCoverTemplateResource::make($bookCoverTemplate);
        $bookCoverTemplateResource->story = $storyResource->resource;

        $bookUserCoverTemplateResource = [];
        if ($type == 'user' && $id) {
            $bookUserCoverTemplate = BookUserCoverTemplate::with(['story', 'template'])->where('id', $id)->first();
            $bookUserCoverTemplateResource = BookUserCoverTemplateResource::make($bookUserCoverTemplate);
        }

        try {
            $coverFonts = Setting::firstWhere('name', 'book_cover_font')?->value ?? null;
        } catch (\Exception) {
            $coverFonts = null;
        }

        return Inertia::render('Dashboard/Stories/Cover', [
            'story' => fn() => $storyResource,
            'template' => fn() => $bookCoverTemplateResource,
            'userTemplate' => fn() => $bookUserCoverTemplateResource,
            'templateType' => $type,
            'templateId' => $id,
            'coverFonts' =>  $coverFonts,
        ]);
    }

    public function covers(Story $story, Request $request)
    {
        $storyResource = StoryResource::make($story->append('pages')->load('cover'));
        $bookCoverTemplate = BookCoverTemplate::paginate(10);
        $bookCoverTemplate->map(function ($i) use ($storyResource) {
            $i->story = $storyResource->resource;
        });
        $bookUserCoverTemplate = BookUserCoverTemplate::with(['story', 'template'])->where('story_id', $story->id)->paginate(10, ['*'], 'upage');

        try {
            $coverFonts = Setting::firstWhere('name', 'book_cover_font')?->value ?? null;
        } catch (\Exception) {
            $coverFonts = null;
        }

        return Inertia::render('Dashboard/Stories/Covers', [
            'story' => fn() => $storyResource,
            'covers' => fn() => BookCoverTemplateResource::collection($bookCoverTemplate),
            'userCovers' => fn() => BookUserCoverTemplateResource::collection($bookUserCoverTemplate),
            'coverFonts' =>  $coverFonts,
        ]);
    }

    public function userCoverTemplate(Story $story, Request $request)
    {
        $parameters = request()->parameters;

        $templateId = $parameters['template_id'] ?? 1;
        foreach (['front', 'front_image', 'back', 'back_image', 'template_id'] as $v) {
            unset($parameters[$v]);
        }

        $template = new BookUserCoverTemplate();
        $template->parameters = $parameters;
        $template->story_id = $story->id;
        $template->template_id = $templateId;
        $template->save();

        $userTemplateId = $template->id;

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'User cover saved successfully!');
    }

    public function coverDelete(Story $story, Request $request, ?int $id = null)
    {
        if ($id) {
            BookUserCoverTemplate::where('id', $id)?->first()?->delete();
        }

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'User cover deleted successfully!');
    }

    public function coversImageBase64(Story $story)
    {
        $images = [
            'front' => null,
            'back' => null,
        ];
        $request = request()->json();

        $front_image = $request->get('front_image');
        $back_image = $request->get('back_image');
        $medias = $story?->cover?->media ?? [];

        foreach ($medias as $v) {
            if ($v->collection_name == 'front' || $v->collection_name == 'back') {
                $stream = $v->stream();
                if (is_resource($stream)) {
                    $file = stream_get_contents($stream);
                    fclose($stream);
                    $ext = \Symfony\Component\Mime\MimeTypes::getDefault()->getExtensions($v->mime_type)[0] ?? null;
                    $base64 = 'data:application/' . $ext . ';base64,' . base64_encode($file);
                    unset($file);

                    $images["{$v->collection_name}"] = $base64;
                }
            }
        }

        if (empty($images['front']) && ! empty($front_image)) {
            $stream = Storage::disk(config('media-library.private_disk_name'))->readStream($front_image);
            if (is_resource($stream)) {
                $file = stream_get_contents($stream);
                fclose($stream);
                $parts = explode('.', $front_image);
                $ext = array_pop($parts);
                $base64 = 'data:application/' . $ext . ';base64,' . base64_encode($file);
                unset($file);

                $images['front'] = $base64;
            }
        }
        if (empty($images['back']) && ! empty($back_image)) {
            $stream = Storage::disk(config('media-library.private_disk_name'))->readStream($back_image);
            if (is_resource($stream)) {
                $file = stream_get_contents($stream);
                fclose($stream);
                $parts = explode('.', $back_image);
                $ext = array_pop($parts);
                $base64 = 'data:application/' . $ext . ';base64,' . base64_encode($file);
                unset($file);

                $images['back'] = $base64;
            }
        }

        return response()->json($images);
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
            'fontList' => fn() => $this->getFonts()
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
        if (! $story->cover) {
            return redirect()->route('dashboard.stories.cover', $story)->with('error', trans('Please create cover before ordering!'));
        }

        return Inertia::render('Dashboard/Stories/Order', [
            'story' => fn() => StoryResource::make($story->load('cover')->append('pages')),
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
            'story' => fn() => StoryResource::make($story->load('cover')->append('pages')),
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

    public function getFonts(): JsonResponse
    {
        $fontPath = config('story.book_fonts_dir');
        $fonts = [];

        if (File::exists($fontPath)) {
            $directories = File::directories($fontPath);

            foreach ($directories as $directory) {
                $fontName = basename($directory);

                $formattedName = $this->formatFontName($fontName);

                $fonts[] = [
                    'name' => $formattedName,
                    'directory' => $fontName,
                ];
            }
        }

        $standartFonts = @config('pdf.standart_fonts') ?? [];

        foreach ($standartFonts as $standartFont) {
            $fonts[] = [
                'name' => $standartFont,
                'directory' => $standartFont,
            ];
        }

        return response()->json($fonts);
    }

    private function formatFontName(string $fontName): string
    {
        $formattedName = str_replace(['_', '-'], ' ', $fontName);

        $formattedName = ucwords($formattedName);

        return $formattedName;
    }
}
