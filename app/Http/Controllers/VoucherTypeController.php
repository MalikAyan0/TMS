<?php

namespace App\Http\Controllers;

use App\Models\VoucherType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class VoucherTypeController extends Controller
{
    /**
     * Display a listing of the voucher types.
     */
    public function index()
    {
        try {
            $voucherTypes = VoucherType::orderBy('created_at', 'desc')->get();

            // Transform the data to ensure all fields are present
            $transformedData = $voucherTypes->map(function ($voucherType) {
                return [
                    'id' => $voucherType->id,
                    'title' => $voucherType->title,
                    'status' => $voucherType->status,
                    'created_at' => $voucherType->created_at?->toISOString(),
                    'updated_at' => $voucherType->updated_at?->toISOString(),
                ];
            });
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $transformedData,
                    'message' => 'Voucher types retrieved successfully'
                ]);
            }
            return view('content.pages.system-management.voucher-types', compact('voucherTypes'));
        } catch (\Exception $e) {
            \Log::error('VoucherType index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve voucher types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created voucher type.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:voucher_types,title',
            ]);

            $voucherType = VoucherType::create([
                'title' => $validated['title'],
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'data' => $voucherType,
                'message' => 'Voucher type created successfully'
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
                'message' => 'Failed to create voucher type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified voucher type.
     */
    public function show(VoucherType $voucherType): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $voucherType,
                'message' => 'Voucher type retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve voucher type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified voucher type.
     */
    public function update(Request $request, VoucherType $voucherType): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('voucher_types', 'title')->ignore($voucherType->id)
                ],
            ]);

            $voucherType->update([
                'title' => $validated['title']
            ]);

            return response()->json([
                'success' => true,
                'data' => $voucherType->fresh(),
                'message' => 'Voucher type updated successfully'
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
                'message' => 'Failed to update voucher type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified voucher type.
     */
    public function toggleStatus(VoucherType $voucherType): JsonResponse
    {
        try {
            $newStatus = $voucherType->status === 'active' ? 'inactive' : 'active';
            $voucherType->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'data' => $voucherType->fresh(),
                'message' => "Voucher type {$newStatus} successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle voucher type status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified voucher type.
     */
    public function destroy(VoucherType $voucherType): JsonResponse
    {
        try {
            $voucherType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Voucher type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete voucher type',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
