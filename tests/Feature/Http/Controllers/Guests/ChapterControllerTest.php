<?php

namespace Tests\Feature\Http\Controllers\Guests;

use App\Models\Guest;
use App\Models\User;
use App\Notifications\GuestChapterInviteNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Guests\ChapterController
 */
class ChapterControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createGuestChapter(Guest $guest = null, User $user = null): \App\Models\Chapter
    {
        $user ??= $this->createUser();

        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        return \App\Models\Chapter::factory()->create([
            'guest_id' => $guest?->id,
            'story_id' => $story->id,
        ]);
    }

    /** @test */
    public function attachments_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);
        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.attachments', [$chapter]));

        $response->assertOk();
    }

    /** @test */
    public function edit_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.edit', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Chapters/Edit')
        );
    }

    /** @test */
    public function finish_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.finish', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Chapters/Finish')
        );
    }

    /** @test */
    public function index_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.index', ['type' => 'received']));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Chapters/Index')
        );

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.index'));

        $response->assertRedirect();
    }

    /** @test */
    public function index_aborts_with_a_404(): void
    {
        $guest = $this->createGuestUser(configured: true);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.index', ['type' => 'wrong-type']));

        $response->assertNotFound();
    }

    /** @test */
    public function invite_returns_an_ok_response(): void
    {
        Notification::fake();

        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);
        $timeline = \App\Models\Timeline::factory()->create();
        $question = \App\Models\TimelineQuestion::factory()->create([
            'timeline_id' => $timeline->id,
        ]);

        $response = $this->actingAs($user)->post(route('dashboard.guest.chapters.invite', compact('story', 'question')), $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
        ]);

        $this->assertDatabaseHas('guests', $data);
        $guest = Guest::firstWhere('email', $data['email']);
        $response->assertRedirect();
        Notification::assertSentTo($guest, GuestChapterInviteNotification::class);
    }

    /** @test */
    public function record_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.record', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Chapters/Record')
        );
    }

    /** @test */
    public function resend_returns_an_ok_response(): void
    {
        Notification::fake();

        $user = $this->createUser(configured: true, subscribed: true);
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest, $user);

        $response = $this->actingAs($user)->post(route('guests.chapters.resend', [$chapter]));

        $response->assertRedirect();

        Notification::assertSentTo($guest, GuestChapterInviteNotification::class);
    }

    /** @test */
    public function show_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.show', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Chapters/Show')
        );
    }

    /** @test */
    public function transcribe_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Guests\ChapterController::class,
            'transcribe',
            \App\Http\Requests\Chapters\TranscribeRequest::class
        );
    }

    /** @test */
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Guests\ChapterController::class,
            'update',
            \App\Http\Requests\Chapters\UpdateChapterRequest::class
        );
    }

    /** @test */
    public function update_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);

        $response = $this->actingAs($guest)->put(route('guests.chapters.update', [$chapter]), [
            'title' => 'New title',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('chapters', [
            'id' => $chapter->id,
            'title' => 'New title',
        ]);
    }

    /** @test */
    public function upload_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.upload', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Chapters/Upload')
        );
    }

    /** @test */
    public function write_returns_an_ok_response(): void
    {
        $guest = $this->createGuestUser(configured: true);
        $chapter = $this->createGuestChapter($guest);

        $response = $this->actingAs($guest, 'web-guest')->get(route('guests.chapters.write', [$chapter]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Chapters/Write')
        );
    }
}
