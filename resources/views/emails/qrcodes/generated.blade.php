<x-mail::message>
# QR Code Generated Successfully!

Hello {{ auth()->user()->name }},

Your new QR code **{{ $qrCode->name }}** has been generated and is now active in your dashboard.

<x-mail::panel>
**Identity Name:** {{ $qrCode->name }}
**Type:** {{ strtoupper($qrCode->type) }}
**Tracking:** {{ $qrCode->is_dynamic ? 'Dynamic (Active)' : 'Static' }}
</x-mail::panel>

You can download your QR code or view its analytics at any time by visiting your dashboard.

<x-mail::button :url="route('dashboard')">
View My Dashboard
</x-mail::button>

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
