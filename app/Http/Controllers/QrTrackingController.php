<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Support\Str;

class QrTrackingController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

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

        if ($qrCode->type === 'bio_link' || $qrCode->type === 'vcard') {
            return view('profile', compact('qrCode'));
        }

        if (!$qrCode->destination_url) {
            abort(404, 'Destination URL not set.');
        }

        return redirect()->away($qrCode->destination_url);
    }

    public function downloadVcf(string $unique_code)
    {
        $qrCode = QrCode::where('unique_code', $unique_code)->firstOrFail();
        
        if ($qrCode->type !== 'vcard') {
            abort(404);
        }

        $vCardContent = $this->qrCodeService->buildVCard($qrCode->content_data ?? []);
        $filename = Str::slug($qrCode->content_data['name'] ?? 'contact') . '.vcf';

        return response($vCardContent)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
