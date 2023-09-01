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
use App\Http\Resources\TimelineResource;
use App\Models\BookCoverTemplate;
use App\Models\Story;
use App\Models\Timeline;
use App\Models\User;
use App\Services\LuluService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;
use Sokil\IsoCodes\Database\Countries\Country;
use Sokil\IsoCodes\Database\Subdivisions\Subdivision;
use Sokil\IsoCodes\IsoCodesFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
            'timelines' => fn () => TimelineResource::collection(
                Timeline::all(['id', 'title'])
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
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    public function cover(Story $story, Request $request)
    {
        return Inertia::render('Dashboard/Stories/Cover', [
            'story' => fn () => StoryResource::make($story->append('pages')),
            'template' => fn () => BookCoverTemplateResource::make(
                BookCoverTemplate::when(
                    $tmpl = $request->query('template'),
                    fn ($query) => $query->where('id', $tmpl)
                )->firstOrFail()
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

        return redirect()->route('stories.show', compact('story'))->with('message', 'Story created successfully!');
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        $story->update($request->validated());

        if ($request->hasFile('cover')) {
            $story->cover?->delete();
            $story->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'Story updated successfully!');
    }

    public function destroy(Story $story)
    {
        DB::transaction(function () use ($story) {
            $story->cover?->delete();
            $story->delete();
        });

        return redirect()->route('stories.index')->with('message', 'Story deleted successfully!');
    }

    public function covers(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Covers', [
            'story' => fn () => StoryResource::make($story),
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
                ->orderBy('order', 'asc')
                ->where('status', Status::PUBLISHED)
                ->get([
                    'id', 'title', 'status', 'timeline_id', 'order',
                ])
                ->groupBy('timeline_id')
                ->map(fn ($chapters) => ChapterResource::collection($chapters)),
            'timelines' => fn () => TimelineResource::collection(
                Timeline::all(['id', 'title'])
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

        return $this->redirectBackOrRoute($request, compact('story'))->with('message', 'Contents saved successfully!');
    }

    public function preview(Story $story)
    {
        return Inertia::render('Dashboard/Stories/Preview', [
            'story' => fn () => StoryResource::make($story),
        ]);
    }

    public function book(Story $story)
    {
        $chapters = $story->chapters()
            ->where('status', Status::PUBLISHED)
            ->orderBy('timeline_id', 'asc')
            ->orderBy('order', 'asc')
            ->lazy();

        return Pdf::loadView('pdf.book', compact('story', 'chapters'))->stream('demo.pdf');
    }

    public function bookCover(Story $story)
    {
        /** @var Media */
        $cover = $story->cover;
        $image = Image::make($stream = $cover->stream());
        $cover = 'data:image/'.$cover->type.';base64,'.base64_encode(stream_get_contents($stream));

        return Pdf::setPaper([0, 0, $image->width(), $image->height()])->loadView('pdf.book-cover', compact('cover'))->stream();
    }

    public function order(Story $story, IsoCodesFactory $iso, OrderCostRequest $request)
    {
        return Inertia::render('Dashboard/Stories/Order', [
            'story' => fn () => StoryResource::make($story->load('cover')->append('pages')),
            'countries' => fn () => collect($iso->getCountries())->map(fn (Country $country) => [
                'name' => $country->getName(),
                'code' => $country->getAlpha2(),
            ]),
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

        $cost = rescue(fn () => $lulu->cost(
            LineItem::from([
                'page_count' => $story->pages,
                'pod_package_id' => '0600X0900FCSTDPB080CW444GXX',
                'quantity' => 1,
            ]),
            $request->shippingAddress(),
            ShippingOption::EXPRESS,
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
        abort_unless(Session::has("print-cost-{$story->id}"), 403);

        $cost = Session::get("print-cost-{$story->id}");

        /** @var User */
        $user = $request->user();

        try {
            $payment = $user->charge($cost * 100, $user->paymentMethods()->first()->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $print = $lulu->print(
            config('mail.from.address'),
            LineItem::from([
                'printable_normalization' => PrintableNormalization::from([
                    'external_id' => $payment->external_id,
                    'pod_package_id' => '0600X0900FCSTDPB080CW444GXX',
                    'cover' => [ 'source_url' => route('stories.book-cover', compact('story')) ],
                    'interior' => [ 'source_url' => route('stories.book', compact('story')) ],
                ]),
                'quantity' => 1,
                'title' => $story->title,
            ]),
            $request->shippingAddress(),
            ShippingOption::EXPRESS,
        );

        $story->printJobs()->create([
            'lulu_id' => $print['id'],
            'details' => $details = PrintJobDetails::from($print),
        ]);

        Session::forget("print-cost-{$story->id}");

        return redirect()->back()->with('message', 'Print job created successfully!');
    }
}
