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
        'logo_path',
        'is_dynamic',
        'unique_code',
        'content_data',
    ];

    protected $casts = [
        'content_data' => 'array',
        'is_dynamic' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scans()
    {
        return $this->hasMany(QrScanAnalytic::class);
    }
}
