<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CoverPhotoUploadTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_an_authenticated_user_can_replace_their_cover_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('cover-photos/previous.jpg', 'previous cover');
        $user = User::factory()->create(['cover_photo' => 'cover-photos/previous.jpg']);

        $response = $this->from(route('profile'))
            ->actingAs($user)
            ->post(route('profile.cover.update'), [
                'cover' => UploadedFile::fake()->image('cover.jpg', 640, 360),
            ]);

        $response->assertRedirect(route('profile'));
        $user->refresh();

        $this->assertNotSame('cover-photos/previous.jpg', $user->cover_photo);
        Storage::disk('public')->assertExists($user->cover_photo);
        Storage::disk('public')->assertMissing('cover-photos/previous.jpg');
    }

    public function test_guests_cannot_upload_cover_photos(): void
    {
        $response = $this->post(route('profile.cover.update'));

        $response->assertRedirect(route('login'));
    }
}
