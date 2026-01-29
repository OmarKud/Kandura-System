<?php

namespace App\service\Invoice;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceService
{
    public function __construct(
        private InvoicePdfService $pdfService
    ) {}

    public function ensureInvoiceForCompletedOrder(Order $order): Invoice
    {
        return DB::transaction(function () use ($order) {

            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            $existing = Invoice::where('order_id', $order->id)->first();
            if ($existing) {
                return $existing;
            }

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'order_id'        => $order->id,
                'total'           => (float)($order->final_price ?? $order->price ?? $order->price ?? 0),
                'pdf_url'         => null,
            ]);

            $pdfUrl = $this->pdfService->generateAndStore($invoice);
            $invoice->update(['pdf_url' => $pdfUrl]);

            return $invoice;
        });
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $candidate = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
        } while (Invoice::where('invoice_number', $candidate)->exists());

        return $candidate;
    }
}
