<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Order;
use App\service\Invoice\InvoiceApiService;
use App\service\Invoice\InvoicePdfService;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceApiService $api,
        private InvoicePdfService $pdf
    ) {}

    public function index()
    {
        $invoices = $this->api->listMyInvoices();

        return $this->complet(InvoiceResource::collection($invoices));
    }

    public function showByOrder(Order $order)
    {
        $invoice = $this->api->getInvoiceByOrderForMe($order);

        return $this->complet(new InvoiceResource($invoice));
    }

    public function downloadByOrder(Order $order)
    {
        $invoice = $this->api->getInvoiceByOrderForMe($order);

        $path = $this->pdf->relativePath($invoice);

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Invoice PDF not found');
        }

        return Storage::disk('public')->download($path, $invoice->invoice_number . '.pdf');
    }
}
