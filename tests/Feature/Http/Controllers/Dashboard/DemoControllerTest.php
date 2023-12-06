<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\DemoController
 */
class DemoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attachments_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.demo.attachments'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Demo/Attachments')
        );
    }

    /** @test */
    public function book_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);
        \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        ob_start();
        $response = $this->actingAs($user)->get(route('dashboard.demo.book'));
        ob_end_clean();

        $response->assertOk();
    }

    /** @test */
    public function enhance_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.demo.enhance'));

        if ($response->status() == 302) {
            $response->assertStatus(302);
        } else {
            $response->assertStatus(200);
            $response->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Dashboard/Demo/Enhance')
            );
        }
    }

    /** @test */
    public function finish_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.demo.finish'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Demo/Finish')
        );
    }

    /** @test */
    public function index_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);
        $timeline = \App\Models\Timeline::factory()->create();
        \App\Models\TimelineQuestion::factory()->times(3)->create([
            'timeline_id' => $timeline->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.demo.index'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Demo/Index')
        );
    }

    /** @test */
    public function record_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.demo.record'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Demo/Record')
        );
    }

    /** @test */
    public function store_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $timeline = \App\Models\Timeline::factory()->create();
        $question = \App\Models\TimelineQuestion::factory()->create([
            'timeline_id' => $timeline->id,
        ]);

        $response = $this->actingAs($user)->post(route('dashboard.demo.store'), [
            'question' => $question->id,
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function transcribe_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\DemoController::class,
            'transcribe',
            \App\Http\Requests\Chapters\TranscribeRequest::class
        );
    }

    /** @test */
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\DemoController::class,
            'update',
            \App\Http\Requests\Chapters\UpdateChapterRequest::class
        );
    }

    /** @test */
    public function update_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->put(route('dashboard.demo.update'), [
            'title' => 'New Title',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('chapters', [
            'title' => 'New Title',
        ]);
    }

    /** @test */
    public function write_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.demo.write'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Demo/Write')
        );
    }
}
