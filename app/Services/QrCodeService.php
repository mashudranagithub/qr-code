<?php

namespace App\Services;

use App\Models\QrCode as QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * Generate the Result instance based on the QrCode model.
     */
    protected function getResult(QrCodeModel $qrCodeModel, string $format = 'png')
    {
        $content = $this->determineContent($qrCodeModel);
        
        $writer = $format === 'svg' ? new SvgWriter() : new PngWriter();

        $qrCode = new QrCode(
            data: $content,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: $this->hexToColor($qrCodeModel->foreground_color ?? '#000000'),
            backgroundColor: $this->hexToColor($qrCodeModel->background_color ?? '#ffffff')
        );

        $logo = null;
        if ($qrCodeModel->logo_path && Storage::disk('public')->exists($qrCodeModel->logo_path)) {
            $logo = new Logo(
                path: Storage::disk('public')->path($qrCodeModel->logo_path),
                resizeToWidth: 50,
                punchoutBackground: true
            );
        }

        return $writer->write($qrCode, $logo);
    }

    /**
     * Generate a QR Code image based on the QrCode model.
     * Returns the raw PNG binary string.
     */
    public function generate(QrCodeModel $qrCodeModel, string $format = 'png'): string
    {
        $result = $this->getResult($qrCodeModel, $format);
        return $result->getString();
    }

    /**
     * Get Base64 representation of the QR code (for on-the-fly rendering in UI).
     */
    public function getBase64Preview(QrCodeModel $qrCodeModel, string $format = 'png'): string
    {
        $result = $this->getResult($qrCodeModel, $format);
        return $result->getDataUri();
    }

    /**
     * Generate and save the QR Code to disk.
     * Returns the relative path on the public disk.
     */
    public function saveToDisk(QrCodeModel $qrCodeModel, string $format = 'png'): string
    {
        $result = $this->getResult($qrCodeModel, $format);
        
        $extension = $format === 'svg' ? 'svg' : 'png';
        $filename = 'qrcodes/' . ($qrCodeModel->unique_code ?? Str::uuid()) . '.' . $extension;

        // Save binary data directly into the public disk
        Storage::disk('public')->put($filename, $result->getString());

        return $filename;
    }

    /**
     * Convert HEX string to RGB Color object
     */
    protected function hexToColor(string $hex): Color
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return new Color((int)$r, (int)$g, (int)$b);
    }

    /**
     * Determine what the QR code should point to.
     */
    protected function determineContent(QrCodeModel $qrCodeModel): string
    {
        if ($qrCodeModel->is_dynamic) {
            return route('qr.track', ['unique_code' => $qrCodeModel->unique_code]);
        }

        if ($qrCodeModel->type === 'vcard') {
            return $this->buildVCard($qrCodeModel->content_data ?? []);
        }

        return $qrCodeModel->destination_url ?? '';
    }

    protected function buildVCard(array $data): string
    {
        $vcard = "BEGIN:VCARD\nVERSION:3.0\n";
        $vcard .= "FN:" . ($data['name'] ?? '') . "\n";
        $vcard .= "N:;" . ($data['name'] ?? '') . ";;;\n";
        if (!empty($data['company'])) $vcard .= "ORG:" . $data['company'] . "\n";
        if (!empty($data['email'])) $vcard .= "EMAIL:" . $data['email'] . "\n";
        if (!empty($data['mobile'])) $vcard .= "TEL;TYPE=cell:" . $data['mobile'] . "\n";
        if (!empty($data['website'])) $vcard .= "URL:" . $data['website'] . "\n";
        if (!empty($data['company_website'])) $vcard .= "URL;TYPE=work:" . $data['company_website'] . "\n";
        if (!empty($data['facebook'])) $vcard .= "X-SOCIALPROFILE;TYPE=facebook:" . $data['facebook'] . "\n";
        if (!empty($data['youtube'])) $vcard .= "X-SOCIALPROFILE;TYPE=youtube:" . $data['youtube'] . "\n";
        if (!empty($data['address'])) $vcard .= "ADR;TYPE=work:;;" . $data['address'] . "\n";
        $vcard .= "END:VCARD";

        return $vcard;
    }
}
