<?php

namespace Tests\Feature\Http\Controllers\Guests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Guests\ChapterController
 */
class ChapterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function attachments_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->get(route('guests.chapters.attachments', [$chapter]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function delete_attachments_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->delete(route('guests.chapters.attachments.destroy', [$chapter, 'attachment' => $chapter->attachment]));

        $response->assertRedirect(route('guests.chapters.attachments', compact('chapter')));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->delete(route('guests.chapters.destroy', [$chapter]));

        $response->assertRedirect(route('guests.chapters.index'));
        $this->assertModelMissing($chapter);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->get(route('guests.chapters.edit', [$chapter]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function finish_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->get(route('guests.chapters.finish', [$chapter]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('guests.chapters.index'));

        $response->assertRedirect(route('guests.chapters.index', ['type' => $type === 'sent' ? 'received' : 'sent']));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_aborts_with_a_404(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        // TODO: perform additional setup to trigger `abort(404)`...

        $response = $this->get(route('guests.chapters.index'));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function invite_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $story = \App\Models\Story::factory()->create();
        $timelineQuestion = \App\Models\TimelineQuestion::factory()->create();
        $chapter = \App\Models\Chapter::factory()->create();
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->post(route('dashboard.guest.chapters.invite', [$story, 'question' => $chapter->question]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(back());

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function record_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->get(route('guests.chapters.record', [$chapter]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function resend_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->post(route('guests.chapters.resend', [$chapter]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(back());

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->get(route('guests.chapters.show', [$chapter]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function transcribe_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Guests\ChapterController::class,
            'transcribe',
            \App\Http\Requests\Chapters\TranscribeRequest::class
        );
    }

    /**
     * @test
     */
    public function transcribe_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->post(route('guests.chapters.attachments.transcribe', [$chapter]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route('guests.chapters.write', compact('chapter')));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Guests\ChapterController::class,
            'update',
            \App\Http\Requests\Chapters\UpdateChapterRequest::class
        );
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->put(route('guests.chapters.update', [$chapter]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(route($redirect, compact('chapter', 'story')));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function upload_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->get(route('guests.chapters.upload', [$chapter]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function write_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $chapter = \App\Models\Chapter::factory()->create();

        $response = $this->get(route('guests.chapters.write', [$chapter]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}
