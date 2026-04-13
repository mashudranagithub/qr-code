<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $qrCode->content_data['name'] ?? 'Contact Profile' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, {{ $qrCode->background_color ?? '#f8f9fa' }} 0%, #e9ecef 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Inter', sans-serif; }
        .profile-card { max-width: 400px; width: 90%; background: white; border-radius: 24px; padding: 40px 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); text-align: center; }
        .avatar { width: 100px; height: 100px; background: {{ $qrCode->foreground_color ?? '#0d6efd' }}; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; font-weight: bold; }
        .btn-social { border-radius: 12px; padding: 12px; margin-bottom: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; transition: transform 0.2s; }
        .btn-social:hover { transform: translateY(-2px); }
        .btn-facebook { background: #3b5998; color: white; }
        .btn-youtube { background: #ff0000; color: white; }
        .btn-website { background: #333; color: white; }
        .btn-email { background: #007bff; color: white; }
        .btn-call { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="profile-card">
        <div class="avatar">
            {{ strtoupper(substr($qrCode->content_data['name'] ?? 'U', 0, 1)) }}
        </div>
        <h3 class="fw-bold mb-1">{{ $qrCode->content_data['name'] ?? 'Unknown' }}</h3>
        @if(!empty($qrCode->content_data['company']))
            <p class="text-primary fw-bold mb-1">{{ $qrCode->content_data['company'] }}</p>
        @endif
        <p class="text-muted mb-4">Contact Profile</p>

        <div class="d-grid gap-2">
            @if($qrCode->type === 'vcard')
                <a href="{{ route('qr.vcf', $qrCode->unique_code) }}" class="btn btn-primary btn-lg rounded-4 py-3 mb-2 shadow-sm font-weight-bold">
                    <i class="fas fa-user-plus me-2"></i> Add to Contacts
                </a>
            @endif

            @if(!empty($qrCode->content_data['mobile']))
                <a href="tel:{{ $qrCode->content_data['mobile'] }}" class="btn btn-social btn-call">
                    <i class="fas fa-phone"></i> Call Me
                </a>
            @endif

            @if(!empty($qrCode->content_data['email']))
                <a href="mailto:{{ $qrCode->content_data['email'] }}" class="btn btn-social btn-email">
                    <i class="fas fa-envelope"></i> Send Email
                </a>
            @endif

            @if(!empty($qrCode->content_data['website']))
                <a href="{{ $qrCode->content_data['website'] }}" target="_blank" class="btn btn-social btn-website">
                    <i class="fas fa-globe"></i> Visit Website
                </a>
            @endif

            @if(!empty($qrCode->content_data['company_website']))
                <a href="{{ $qrCode->content_data['company_website'] }}" target="_blank" class="btn btn-social btn-dark">
                    <i class="fas fa-briefcase"></i> Company Website
                </a>
            @endif

            @if(!empty($qrCode->content_data['facebook']))
                <a href="{{ $qrCode->content_data['facebook'] }}" target="_blank" class="btn btn-social btn-facebook">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
            @endif

            @if(!empty($qrCode->content_data['youtube']))
                <a href="{{ $qrCode->content_data['youtube'] }}" target="_blank" class="btn btn-social btn-youtube">
                    <i class="fab fa-youtube"></i> YouTube
                </a>
            @endif

            @if(!empty($qrCode->content_data['address']))
                <div class="mt-3 p-3 rounded" style="background: #f8f9fa; border: 1px dashed #ddd;">
                    <i class="fas fa-location-dot text-danger mb-2"></i><br>
                    <small class="text-muted">{{ $qrCode->content_data['address'] }}</small>
                </div>
            @endif
        </div>

        <div class="mt-4">
            <small class="text-muted">Powered by QRGen</small>
        </div>
    </div>
</body>
</html>
