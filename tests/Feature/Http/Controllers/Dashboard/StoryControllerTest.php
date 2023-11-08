<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\StoryController
 */
class StoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cover_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);
        $template = \App\Models\BookCoverTemplate::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard.stories.cover', compact('story', 'template')));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/Cover')
        );
    }

    /** @test */
    public function covers_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);
        \App\Models\BookCoverTemplate::factory(3)->create();

        $response = $this->actingAs($user)->get(route('dashboard.stories.covers', [$story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/Covers')
        );
    }

    /** @test */
    public function create_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);

        $response = $this->actingAs($user)->get(route('dashboard.stories.create'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/Create')
        );
    }

    /** @test */
    public function destroy_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('dashboard.stories.destroy', [$story]));

        $response->assertRedirect();
        $this->assertModelMissing($story);
    }

    /** @test */
    public function edit_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $storyType = \App\Models\StoryType::factory()->create();
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
            'story_type_id' => $storyType->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.stories.edit', [$story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/Edit')
        );
    }

    /** @test */
    public function index_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\StoryController::class,
            'index',
            \App\Http\Requests\Stories\StoriesRequest::class
        );
    }

    /** @test */
    public function index_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);

        $response = $this->actingAs($user)->get(route('dashboard.stories.index'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/Index')
        );
    }

    /** @test */
    public function order_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\StoryController::class,
            'order',
            \App\Http\Requests\Stories\OrderCostRequest::class
        );
    }

    /** @test */
    public function order_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        $story->addMedia(fake()->image())->toMediaCollection('cover');

        $response = $this->actingAs($user)->get(route('dashboard.stories.order', [$story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/Order')
        );
    }

    /** @test */
    public function ordercost_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\StoryController::class,
            'orderCost',
            \App\Http\Requests\Stories\OrderCostRequest::class
        );
    }

    /** @test */
    public function orderpurchase_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\StoryController::class,
            'orderPurchase',
            \App\Http\Requests\Stories\OrderCostRequest::class
        );
    }

    /** @test */
    public function preview_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.stories.preview', [$story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/Preview')
        );
    }

    /** @test */
    public function savecontents_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\StoryController::class,
            'saveContents',
            \App\Http\Requests\Stories\ChapterOrderRequest::class
        );
    }

    /** @test */
    public function show_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.stories.show', [$story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Stories/ShowV2')
        );
    }

    /** @test */
    public function store_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\StoryController::class,
            'store',
            \App\Http\Requests\Stories\StoreStoryRequest::class
        );
    }

    /** @test */
    public function store_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $storyType = \App\Models\StoryType::factory()->create();

        $response = $this->actingAs($user)->post(route('dashboard.stories.store'), [
            'title' => 'My Story',
            'story_type_id' => $storyType->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('stories', [
            'title' => 'My Story',
            'story_type_id' => $storyType->id,
        ]);
    }

    /** @test */
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\StoryController::class,
            'update',
            \App\Http\Requests\Stories\UpdateStoryRequest::class
        );
    }

    /** @test */
    public function update_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $storyType = \App\Models\StoryType::factory()->create();
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
            'story_type_id' => $storyType->id,
        ]);

        $response = $this->actingAs($user)->put(route('dashboard.stories.update', [$story]), [
            'title' => 'New title',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('stories', [
            'id' => $story->id,
            'title' => 'New title',
        ]);
    }
}
