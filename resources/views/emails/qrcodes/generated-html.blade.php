<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden; }
        .header { background: #0f172a; padding: 20px; text-align: center; }
        .content { padding: 40px; }
        .success-badge { background: #dcfce7; color: #15803d; padding: 8px 16px; border-radius: 30px; font-weight: bold; font-size: 13px; display: inline-block; margin-bottom: 20px; }
        .qr-stage { background: #f8fafc; padding: 30px; border-radius: 20px; text-align: center; margin: 30px 0; border: 1px dashed #cbd5e1; }
        .qr-image { background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: inline-block; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
        .btn { background: #6366f1; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 30px; display: inline-block; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://boichitra.com/ui/assets/images/settings/logo.png" alt="Boichitra Logo" style="max-height: 40px;">
        </div>
        
        <div class="content">
            <div class="success-badge">Successfully Generated!</div>
            <h2 style="margin-top: 0; color: #0f172a;">New QR Code: {{ $qrCode->name }}</h2>
            <p>Hello {{ auth()->user()->name }},</p>
            <p>Your premium QR identity has been successfully generated and is now live. Below is your new QR code ready for use.</p>

            <div class="qr-stage">
                <div class="qr-image">
                    {{-- EMBEDDING THE IMAGE DATA DIRECTLY --}}
                    <img src="{{ $message->embed(Illuminate\Support\Facades\Storage::disk('public')->path($qrCode->qr_path)) }}" alt="QR Code" width="200">
                </div>
                <p style="font-size: 12px; color: #64748b; margin-top: 15px;">QR Identity: {{ $qrCode->unique_code }}</p>
            </div>

            <p>You can track its performance and analytics directly from your dashboard at any time.</p>

            <div style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="btn">View Collection Analytics</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Boichitra QR Code. Professional Identity Delivery System.<br>
            Sent via Brevo Professional SMTP Relay
        </div>
    </div>
</body>
</html>
