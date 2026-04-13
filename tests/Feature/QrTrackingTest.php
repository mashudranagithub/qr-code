<?php

namespace Tests\Feature;

use App\Models\QrCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_tracking_logs_scan_and_redirects(): void
    {
        $user = User::factory()->create();
        $qrCode = QrCode::create([
            'user_id' => $user->id,
            'name' => 'Test QR',
            'type' => 'url',
            'destination_url' => 'https://google.com',
            'is_dynamic' => true,
            'unique_code' => 'test_code',
        ]);

        $response = $this->get('/t/test_code');

        $response->assertRedirect('https://google.com');
        
        $this->assertDatabaseHas('qr_scan_analytics', [
            'qr_code_id' => $qrCode->id,
        ]);
        
        $this->assertEquals(1, $qrCode->scans()->count());
    }
}
