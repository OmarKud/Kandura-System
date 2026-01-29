<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardInvoiceController extends Controller
{
    private function assertAdmin(): void
    {
        $u = auth()->user();
        if (!$u || !in_array((int)$u->role_id, [3, 4], true)) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->assertAdmin();

        $q = trim((string)$request->get('q', ''));
        $perPage = (int)$request->get('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        $invoices = Invoice::query()
            ->select(['id','invoice_number','order_id','total','pdf_url','created_at'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('invoice_number', 'like', "%{$q}%")
                      ->orWhere('order_id', $q);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('dashboard.invoices.index', compact('invoices','q','perPage'));
    }

    public function show(Invoice $invoice)
    {
        $this->assertAdmin();

        $invoice->setVisible(['id','invoice_number','order_id','total','pdf_url','created_at']);

        return view('dashboard.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $this->assertAdmin();

        $path = "invoices/{$invoice->invoice_number}.pdf";

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Invoice PDF not found');
        }

        return Storage::disk('public')->download($path, $invoice->invoice_number . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
