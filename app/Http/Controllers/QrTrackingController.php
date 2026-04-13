<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QrCode;

class QrTrackingController extends Controller
{
    public function track(Request $request, string $unique_code)
    {
        $qrCode = QrCode::where('unique_code', $unique_code)->firstOrFail();

        // Log the analytic scan if dynamic
        if ($qrCode->is_dynamic) {
            $location = \Stevebauman\Location\Facades\Location::get($request->ip());
            
            $qrCode->scans()->create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $this->guessDeviceType($request->userAgent()),
                'country' => $location ? $location->countryName : 'Unknown',
                'city' => $location ? $location->cityName : 'Unknown',
            ]);
        }

        if ($qrCode->type === 'bio_link') {
            return view('profile', compact('qrCode'));
        }

        if (!$qrCode->destination_url) {
            abort(404, 'Destination URL not set.');
        }

        return redirect()->away($qrCode->destination_url);
    }

    protected function guessDeviceType(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown';
        }

        $userAgent = strtolower($userAgent);
        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android') || str_contains($userAgent, 'iphone')) {
            return 'Mobile';
        } elseif (str_contains($userAgent, 'ipad') || str_contains($userAgent, 'tablet')) {
            return 'Tablet';
        }

        return 'Desktop';
    }
}
