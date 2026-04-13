<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QrCodeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_preview_qr_code(): void
    {
        $response = $this->postJson('/api/qrcodes/preview', [
            'name' => 'Test Preview',
            'type' => 'url',
            'destination_url' => 'https://example.com',
            'foreground_color' => '#ff0000',
            'background_color' => '#ffffff',
            'is_dynamic' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'message'
        ]);
        
        $this->assertStringStartsWith('data:image/png;base64,', $response->json('data'));
    }

    public function test_api_can_save_and_download_qr_code(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/qrcodes/download', [
            'name' => 'Test Save',
            'type' => 'url',
            'destination_url' => 'https://example.org',
            'foreground_color' => '#00ff00',
            'background_color' => '#000000',
            'is_dynamic' => false,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'qr_code' => ['id', 'unique_code'],
            'download_url'
        ]);

        $this->assertDatabaseHas('qr_codes', [
            'name' => 'Test Save',
            'destination_url' => 'https://example.org',
            'user_id' => $user->id,
        ]);
        
        $this->assertNotEmpty($response->json('download_url'));
    }
}
