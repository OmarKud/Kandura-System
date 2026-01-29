<?php

namespace App\service\Invoice;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoicePdfService
{
    public function generateAndStore(Invoice $invoice): string
{
    $invoice->loadMissing([
        'order',
        'order.user',
        'order.address',
        'order.designOrders',
        'order.designOrders.design',
        'order.designOrders.measurement',
        'order.designOrders.options',
    ]);

    $pdf = Pdf::loadView('pdf.invoice', [
        'invoice' => $invoice,
    ])->setPaper('a4', 'portrait');

    $relativePath = "invoices/{$invoice->invoice_number}.pdf";

    Storage::disk('public')->put($relativePath, $pdf->output());

    return asset("storage/{$relativePath}");
}


    public function relativePath(Invoice $invoice): string
    {
        return "invoices/{$invoice->invoice_number}.pdf";
    }
}
