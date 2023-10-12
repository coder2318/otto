<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\ChapterController
 */
class ChapterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function prepare()
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $storyType = \App\Models\StoryType::factory()->create();
        $timeline = \App\Models\Timeline::factory()->create([
            'story_type_id' => $storyType->id,
        ]);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
            'story_type_id' => $storyType->id,
        ]);
        $chapter = \App\Models\Chapter::factory()->create([
            'story_id' => $story->id,
        ]);

        return [$user, $story, $chapter, $timeline];
    }

    /** @test */
    public function attachments_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();
        $response = $this->actingAs($user)->get(route('dashboard.chapters.attachments', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Attachments')
        );
    }

    /** @test */
    public function create_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.stories.chapters.create', [$story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Create')
        );
    }

    /** @test */
    public function destroy_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->delete(route('dashboard.chapters.destroy', [$chapter]));

        $response->assertRedirect();

        $this->assertModelMissing($chapter);
    }

    /** @test */
    public function edit_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->get(route('dashboard.chapters.edit', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Edit')
        );
    }

    /** @test */
    public function enhance_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->get(route('dashboard.chapters.enhance', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Enhance')
        );
    }

    /** @test */
    public function finish_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->get(route('dashboard.chapters.finish', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Finish')
        );
    }

    /** @test */
    public function index_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\ChapterController::class,
            'index',
            \App\Http\Requests\Chapters\ChaptersRequest::class
        );
    }

    /** @test */
    public function index_returns_an_ok_response(): void
    {
        [$user, $story] = $this->prepare();

        $response = $this->actingAs($user)->get(route('dashboard.stories.chapters.index', [$story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Index')
        );
    }

    /** @test */
    public function record_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->get(route('dashboard.chapters.record', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Record')
        );
    }

    /** @test */
    public function show_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->get(route('dashboard.chapters.show', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Show')
        );
    }

    /** @test */
    public function store_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\ChapterController::class,
            'store',
            \App\Http\Requests\Chapters\StoreChapterRequest::class
        );
    }

    /** @test */
    public function store_returns_an_ok_response(): void
    {
        [$user, $story, , $timeline] = $this->prepare();

        $response = $this->actingAs($user)->post(route('dashboard.stories.chapters.store', [$story]), [
            'title' => 'foo',
            'timeline_id' => $timeline->id,
        ]);

        $response->assertRedirect();

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('chapters', [
            'title' => 'foo',
            'story_id' => $story->id,
            'timeline_id' => $timeline->id,
        ]);
    }

    /** @test */
    public function transcribe_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\ChapterController::class,
            'transcribe',
            \App\Http\Requests\Chapters\TranscribeRequest::class
        );
    }

    /** @test */
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\ChapterController::class,
            'update',
            \App\Http\Requests\Chapters\UpdateChapterRequest::class
        );
    }

    /** @test */
    public function update_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->put(route('dashboard.chapters.update', [$chapter]), [
            'title' => 'New Title',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('chapters', [
            'title' => 'New Title',
            'story_id' => $chapter->story_id,
            'id' => $chapter->id,
        ]);
    }

    /** @test */
    public function write_returns_an_ok_response(): void
    {
        [$user, , $chapter] = $this->prepare();

        $response = $this->actingAs($user)->get(route('dashboard.chapters.write', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Chapters/Write')
        );
    }
}
