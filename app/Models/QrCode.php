<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'destination_url',
        'foreground_color',
        'background_color',
        'qr_path',
        'logo_path',
        'is_dynamic',
        'unique_code',
        'content_data',
    ];

    protected $casts = [
        'content_data' => 'array',
        'is_dynamic' => 'boolean',
    ];

    protected $appends = ['qr_image_url', 'logo_url'];

    public function getQrImageUrlAttribute()
    {
        $path = $this->qr_path ?? 'qrcodes/' . $this->unique_code . '.png';
        return asset('storage/' . $path);
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo_path) return null;
        return asset('storage/' . $this->logo_path);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scans()
    {
        return $this->hasMany(QrScanAnalytic::class);
    }
}
