<?php

namespace App\Http\Controllers;

use App\Models\SalesTaxTerritory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalesTaxTerritoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        try {
            $territories = SalesTaxTerritory::orderBy('short_name', 'asc')->get();
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $territories
                ]);
            }
            return view('content.pages.system-management.sales-tax-territories', compact('territories'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sales tax territories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'head' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'short_name' => 'required|string|max:15|unique:sales_tax_territories,short_name',
                'address' => 'required|string',
                'status' => 'sometimes|in:active,inactive'
            ]);

            $territory = SalesTaxTerritory::create($validated);

            return response()->json([
                'success' => true,
                'data' => $territory,
                'message' => 'Sales tax territory created successfully!'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sales tax territory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesTaxTerritory $salesTaxTerritory): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $salesTaxTerritory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sales tax territory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesTaxTerritory $salesTaxTerritory): JsonResponse
    {
        try {
            $validated = $request->validate([
                'head' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'short_name' => 'required|string|max:15|unique:sales_tax_territories,short_name,' . $salesTaxTerritory->id,
                'address' => 'required|string',
                'status' => 'sometimes|in:active,inactive'
            ]);

            $salesTaxTerritory->update($validated);

            return response()->json([
                'success' => true,
                'data' => $salesTaxTerritory->fresh(),
                'message' => 'Sales tax territory updated successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sales tax territory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(SalesTaxTerritory $salesTaxTerritory): JsonResponse
    {
        try {
            $newStatus = $salesTaxTerritory->status === 'active' ? 'inactive' : 'active';
            $salesTaxTerritory->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'data' => $salesTaxTerritory->fresh(),
                'message' => "Sales tax territory {$newStatus} successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle sales tax territory status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesTaxTerritory $salesTaxTerritory): JsonResponse
    {
        try {
            $salesTaxTerritory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sales tax territory deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete sales tax territory',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
