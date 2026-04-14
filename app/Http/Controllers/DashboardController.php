<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch user's QR codes
        $qrCodes = Auth::user()->qrCodes()->latest()->paginate(10);
        return view('dashboard', compact('qrCodes'));
    }

    public function showAnalytics(QrCode $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403);
        }

        $scans = $qrCode->scans()->orderBy('created_at', 'desc')->get();
        
        // Grouping for charts
        $scansByDate = $qrCode->scans()
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $scansByDevice = $qrCode->scans()
            ->selectRaw('device_type, count(*) as count')
            ->groupBy('device_type')
            ->get();

        $scansByCountry = $qrCode->scans()
            ->selectRaw('country, count(*) as count')
            ->groupBy('country')
            ->get();

        return view('analytics', compact('qrCode', 'scans', 'scansByDate', 'scansByDevice', 'scansByCountry'));
    }

    public function destroy(QrCode $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete physical file
        if ($qrCode->qr_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($qrCode->qr_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($qrCode->qr_path);
        }

        $qrCode->delete();

        return redirect()->route('dashboard')->with('success', 'QR Code deleted successfully.');
    }
}
