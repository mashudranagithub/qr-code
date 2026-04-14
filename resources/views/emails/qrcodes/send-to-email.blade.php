<x-mail::message>
# Your QR Code Identity

Hello {{ auth()->user()->name }},

As requested, here is your QR code for **{{ $qrCode->name }}**.

<x-mail::panel>
You can scan the image below directly or save it for your marketing materials.
</x-mail::panel>

![QR Code]({{ $qrCode->qr_image_url }})

**Identity Details:**
- **Name:** {{ $qrCode->name }}
- **Type:** {{ strtoupper($qrCode->type) }}
- **Target URL:** [{{ $qrCode->destination_url ?: 'Dynamic Tracking' }}]({{ $qrCode->destination_url ?: route('qr.track', $qrCode->unique_code) }})

<x-mail::button :url="route('dashboard')">
Manage My Collection
</x-mail::button>

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
