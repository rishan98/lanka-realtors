<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegisterAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_registration_stores_avatar(): void
    {
        Storage::fake('public');

        $response = $this->post('/register', [
            'name' => 'Test Agent',
            'email' => 'agent-avatar@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'agent',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect(route('register.pending'));

        $this->assertDatabaseHas('users', [
            'email' => 'agent-avatar@example.com',
            'role' => 'agent',
            'approval_status' => 'pending',
        ]);

        $user = \App\Models\User::where('email', 'agent-avatar@example.com')->first();

        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_owner_registration_is_approved_immediately(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Owner',
            'email' => 'owner@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'owner',
        ]);

        $response->assertRedirect(route('owner.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'owner@example.com',
            'role' => 'owner',
            'approval_status' => 'approved',
        ]);

        $this->assertAuthenticatedAs(\App\Models\User::where('email', 'owner@example.com')->first());
    }
}
