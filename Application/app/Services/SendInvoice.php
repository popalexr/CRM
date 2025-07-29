<?php

namespace App\Services;

use App\Mail\InvoiceReadyMail;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\TemporaryFiles;
use Illuminate\Support\Facades\Mail;

class SendInvoice
{
    public static function send(int $fileId, int $client_id, int $invoice_id): bool
    {
        $file = TemporaryFiles::find($fileId);

        if (blank($file)) {
            return false;
        }

        $filePath = storage_path($file->file_path);

        if (!file_exists($filePath)) {
            return false;
        }

        $client = Clients::find($client_id);

        if (blank($client)) {
            return false;
        }

        $invoice = Invoices::find($invoice_id);

        if (blank($invoice)) {
            return false;
        }

        Mail::to($client->client_email)
            ->queue(new InvoiceReadyMail($invoice, $client, $file->file_path));

        return true;
    }
}