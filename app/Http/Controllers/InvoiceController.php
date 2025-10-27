<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Party;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::with(['jobQueue', 'customer'])->paginate(20);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_queue_id' => 'required|exists:job_queue,id',
            'customer_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
        ]);

        if ($request->hasFile('invoice_file')) {
            $validated['invoice_file'] = $request->file('invoice_file')->store('invoice_files', 'public');
        }

        $invoice = Invoice::create($validated);
        return response()->json(['success' => true, 'invoice' => $invoice], 201);
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['jobQueue', 'customer']);
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'job_queue_id' => 'required|exists:job_queue,id',
            'customer_id' => 'required|exists:parties,id',
            'invoice_date' => 'required|date',
            'invoice_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
        ]);

        if ($request->hasFile('invoice_file')) {
            $validated['invoice_file'] = $request->file('invoice_file')->store('invoice_files', 'public');
        }

        $invoice->update($validated);
        return response()->json(['success' => true, 'invoice' => $invoice]);
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['jobQueue', 'customer']);
        return view('content.pages.jobs.invoices.show', compact('invoice'));
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(['success' => true]);
    }
}
