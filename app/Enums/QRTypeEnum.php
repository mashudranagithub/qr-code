<?php

namespace App\Enums;

enum QRTypeEnum: string
{
    case TEXT = 'text';
    case URL = 'url';
    case WIFI = 'wifi';
    case EMAIL = 'email';
    case VCARD = 'vcard';
    case SMS = 'sms';
    case PHONE = 'phone';
    case BIO_LINK = 'bio_link';
    
    public function label(): string
    {
        return match($this) {
            self::TEXT => 'Plain Text',
            self::URL => 'URL / Website',
            self::WIFI => 'WiFi Network',
            self::EMAIL => 'Email Address',
            self::VCARD => 'vCard / Contact',
            self::SMS => 'SMS Message',
            self::PHONE => 'Phone Number',
            self::BIO_LINK => 'Contact Profile (Bio Link)',
        };
    }
}
