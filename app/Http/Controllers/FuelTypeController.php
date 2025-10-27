<?php

namespace App\Http\Controllers;

use App\Models\FuelType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class FuelTypeController extends Controller
{
    /**
     * Display a listing of fuel types.
     */
    public function index(Request $request)
    {
      try {
        $fuelTypes = FuelType::orderBy('id', 'asc')->get();

        if ($request->wantsJson()) {
          return response()->json([
            'success' => true,
            'data' => $fuelTypes,
            'message' => 'Fuel types retrieved successfully'
          ]);
        }

        return view('content.pages.system-management.fuel-types', compact('fuelTypes'));
      } catch (Exception $e) {
        if ($request->wantsJson()) {
          return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve fuel types: ' . $e->getMessage()
          ], 500);
        }

        return view('content.pages.system-management.fuel-types', ['fuelTypes' => collect(), 'error' => $e->getMessage()]);
      }
    }

    /**
     * Store a newly created fuel type.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255|unique:fuel_types,label',
                'status' => 'nullable|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fuelType = FuelType::create([
                'label' => $request->label,
                'status' => $request->status ?? 'active',
            ]);

            return response()->json([
                'success' => true,
                'data' => $fuelType,
                'message' => 'Fuel type created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create fuel type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified fuel type.
     */
    public function show(FuelType $fuelType): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $fuelType,
                'message' => 'Fuel type retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fuel type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified fuel type.
     */
    public function update(Request $request, FuelType $fuelType): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255|unique:fuel_types,label,' . $fuelType->id,
                'status' => 'nullable|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fuelType->update([
                'label' => $request->label,
                'status' => $request->status ?? $fuelType->status,
            ]);

            return response()->json([
                'success' => true,
                'data' => $fuelType,
                'message' => 'Fuel type updated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update fuel type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified fuel type.
     */
    public function destroy(FuelType $fuelType): JsonResponse
    {
        try {
            $fuelType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fuel type deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete fuel type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle fuel type status.
     */
    public function toggleStatus(FuelType $fuelType): JsonResponse
    {
        try {
            $newStatus = $fuelType->status === 'active' ? 'inactive' : 'active';

            $fuelType->update([
                'status' => $newStatus,
            ]);

            return response()->json([
                'success' => true,
                'data' => $fuelType,
                'message' => "Fuel type {$newStatus} successfully"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle fuel type status: ' . $e->getMessage()
            ], 500);
        }
    }
}
