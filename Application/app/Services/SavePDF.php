<?php

namespace App\Services;

use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Facades\Pdf;

class SavePDF
{
    public static function save(string $path, string $view, array $data = []): void
    {
        Pdf::view($view, $data)
            ->format(Format::A4)
            ->save($path);
    }
}