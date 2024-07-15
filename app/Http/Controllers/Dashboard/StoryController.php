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
use App\Http\Resources\ChapterResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\StoryTypeResource;
use App\Http\Resources\TimelineResource;
use App\Jobs\RegenerateBook;
use App\Jobs\RegenerateBookCover;
use App\Models\BookCoverTemplate;
use App\Models\Story;
use App\Models\StoryType;
use App\Models\User;
use App\Services\LuluService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
            'stories' => fn () => StoryResource::collection(
                $request->stories($request->user()->stories()->with('cover'))
                    ->paginate(6)
                    ->appends($request->query())
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Stories/Create', [
            'story_types' => fn () => StoryTypeResource::collection(
                StoryType::all(['id', 'name'])
            ),
        ]);
    }

    public function write(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Write', [
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    public function show(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Show', [
            'story' => fn () => StoryResource::make($story
                ->load('cover')
                ->append(['pages', 'words', 'progress'])
            ),
        ]);
    }

    public function cover(Story $story, Request $request)
    {
        return Inertia::render('Dashboard/Stories/Cover', [
            'story' => fn () => StoryResource::make($story->append('pages')->load('cover')),
            'template' => fn () => BookCoverTemplateResource::make(
                BookCoverTemplate::when(
                    $tmpl = $request->query('template', $story->cover?->getCustomProperty('template_id')), // @phpstan-ignore-line
                    fn ($query) => $query->where('id', $tmpl)
                )->orderBy('created_at')->firstOrFail()
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

        dispatch(new RegenerateBook($story));

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'Story updated successfully!');
    }

    public function destroy(Story $story)
    {
        $story->delete();

        return redirect()->route('dashboard.stories.index')->with('message', 'Story deleted successfully!');
    }

    public function covers(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Covers', [
            'story' => fn () => StoryResource::make($story->append('pages')->load('cover')),
            'covers' => fn () => BookCoverTemplateResource::collection(
                BookCoverTemplate::paginate(12)
            ),
        ]);
    }

    public function edit(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Edit', [
            'story' => fn () => StoryResource::make($story),
            'chapters' => fn () => $story->chapters()
                ->orderBy('timeline_id', 'asc')
                ->orderBy('order', 'asc')
                ->where('status', Status::PUBLISHED)
                ->get([
                    'id', 'title', 'status', 'timeline_id', 'order',
                ])
                ->groupBy('timeline_id')
                ->map(fn ($chapters) => ChapterResource::collection($chapters)),
            'timelines' => fn () => TimelineResource::collection(
                $story->storyType->timelines()->get(['id', 'title'])
            ),
        ]);
    }

    public function saveContents(Story $story, ChapterOrderRequest $request)
    {
        DB::transaction(function () use ($story, $request) {
            foreach ($request->validated('timelines', []) as $data) {
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

        dispatch(new RegenerateBook($story));

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'Contents saved successfully!');
    }

    public function preview(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Preview', [
            'story' => fn () => StoryResource::make($story->load('book')),
            'chapters' => fn () => ChapterResource::collection(
                $story->chapters()->where('status', Status::PUBLISHED)
                    ->orderBy('timeline_id', 'asc')
                    ->orderBy('order', 'asc')
                    ->get(['id', 'title'])
            ),
        ]);
    }

    public function regenerate_counter(Story $story)
    {
        return response()->json([
            'regenerate_counter' => $story->regenerate_counter,
        ]);
    }

    public function order(Story $story, IsoCodesFactory $iso, OrderCostRequest $request)
    {
        if (! $story->cover) {
            return redirect()->route('dashboard.stories.cover', $story)->with('error', trans('Please create cover before ordering!'));
        }

        return Inertia::render('Dashboard/Stories/Order', [
            'story' => fn () => StoryResource::make($story->load('cover')->append('pages')),
            'countries' => fn () => collect($iso->getCountries())->map(fn (Country $country) => [
                'name' => $country->getName(),
                'code' => $country->getAlpha2(),
            ]),
            'payment' => fn () => $request->user()->defaultPaymentMethod(),
            'states' => fn () => $request->has('country_code') ? collect($iso->getSubdivisions()->getAllByCountryCode($request->validated('country_code')))
                ->map(fn (Subdivision $subdivision) => [
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

        $cost = rescue(fn () => $lulu->cost(
            LineItem::from([
                'page_count' => $story->pages,
                'pod_package_id' => $pod_package_id,
                'quantity' => $request->validated('quantity'),
            ]),
            $request->shippingAddress(),
            ShippingOption::from($request->validated('shipping_method')),
        ), fn ($e) => Session::flash('error', $e->getMessage()));

        if ($cost) {
            Session::put("print-cost-{$story->id}", $cost);
        }

        return Inertia::render('Dashboard/Stories/Order', [
            'story' => fn () => StoryResource::make($story->load('cover')->append('pages')),
            'price' => fn () => $cost,
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
