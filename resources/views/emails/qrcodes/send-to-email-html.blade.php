<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden; }
        .header { background: #0f172a; padding: 20px; text-align: center; }
        .content { padding: 40px; }
        .qr-stage { background: #f8fafc; padding: 30px; border-radius: 20px; text-align: center; margin: 30px 0; }
        .qr-image { background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: inline-block; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
        .btn { background: #6366f1; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 30px; display: inline-block; font-weight: bold; margin-top: 20px; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .info-table td { padding: 10px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .label { color: #64748b; font-weight: 600; width: 40%; }
        .value { color: #0f172a; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://boichitra.com/ui/assets/images/settings/logo.png" alt="Boichitra Logo" style="max-height: 40px;">
        </div>
        
        <div class="content">
            <h2 style="margin-top: 0;">Your QR Code Delivery</h2>
            <p>Hello {{ auth()->user()->name }},</p>
            <p>You requested a manual delivery of your QR code identity. You can scan the image below directly or save the attached PNG file for your marketing materials.</p>

            <div class="qr-stage">
                <div class="qr-image">
                    {{-- EMBEDDING THE IMAGE DATA DIRECTLY --}}
                    <img src="{{ $message->embed(Illuminate\Support\Facades\Storage::disk('public')->path($qrCode->qr_path)) }}" alt="QR Code" width="200">
                </div>
            </div>

            <table class="info-table">
                <tr>
                    <td class="label">Identity Name</td>
                    <td class="value">{{ $qrCode->name }}</td>
                </tr>
                <tr>
                    <td class="label">Type</td>
                    <td class="value text-uppercase">{{ strtoupper($qrCode->type) }}</td>
                </tr>
                <tr>
                    <td class="label">Target Destination</td>
                    <td class="value">{{ $qrCode->destination_url ?: 'Dynamic Tracking' }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="btn">Manage My QR Collection</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Boichitra QR Code. Professional Identity Delivery System.<br>
            Sent to {{ auth()->user()->email }}
        </div>
    </div>
</body>
</html>
