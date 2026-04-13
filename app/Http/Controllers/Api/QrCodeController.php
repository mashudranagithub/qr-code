<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    protected QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Preview QR Code as Base64 format (no DB save, no physical file).
     */
    public function preview(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'type' => 'nullable|string',
            'destination_url' => 'nullable|string',
            'content_data' => 'nullable|array',
            'foreground_color' => 'nullable|string',
            'background_color' => 'nullable|string',
            'is_dynamic' => 'nullable|boolean'
        ]);

        // Create in-memory model for preview
        $qrCode = new QrCode([
            'name' => $request->name ?? 'Preview',
            'type' => $request->type ?? 'url',
            'destination_url' => $request->destination_url,
            'content_data' => $request->content_data,
            'foreground_color' => $request->foreground_color ?? '#000000',
            'background_color' => $request->background_color ?? '#ffffff',
            'is_dynamic' => $request->is_dynamic ?? true,
            'unique_code' => Str::random(8),
        ]);

        $base64 = $this->qrCodeService->getBase64Preview($qrCode);

        return response()->json([
            'data' => $base64,
            'message' => 'Preview generated successfully'
        ]);
    }

    /**
     * Save QR Code to DB and save as a physical image on disk.
     */
    public function saveAndDownload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'destination_url' => 'nullable|string',
            'content_data' => 'nullable|array',
            'foreground_color' => 'nullable|string',
            'background_color' => 'nullable|string',
            'is_dynamic' => 'boolean'
        ]);

        // 1. Save to Database
        $qrCode = new QrCode();
        $qrCode->user_id = auth()->id() ?? 1; 
        $qrCode->name = $request->name;
        $qrCode->type = $request->type;
        $qrCode->destination_url = $request->destination_url;
        $qrCode->content_data = $request->content_data;
        $qrCode->foreground_color = $request->foreground_color ?? '#000000';
        $qrCode->background_color = $request->background_color ?? '#ffffff';
        $qrCode->is_dynamic = $request->is_dynamic ?? true;
        $qrCode->unique_code = Str::random(10);
        $qrCode->save();

        // 2. Generate and Save physical file to disk
        $path = $this->qrCodeService->saveToDisk($qrCode, 'png');

        // Optional: Return a download response directly, or a JSON with the URL
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Saved successfully',
                'qr_code' => $qrCode,
                'download_url' => url(Storage::url($path))
            ]);
        }

        // Return direct download
        return Storage::disk('public')->download($path, $qrCode->name . '.png');
    }
}
