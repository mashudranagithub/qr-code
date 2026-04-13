<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrScanAnalytic extends Model
{
    protected $fillable = [
        'qr_code_id',
        'ip_address',
        'user_agent',
        'device_type',
        'country',
        'city',
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }
}
