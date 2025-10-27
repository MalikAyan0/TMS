<?php

namespace App\Http\Controllers;

use App\Models\OilType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OilTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $oilTypes = OilType::orderBy('id', 'asc')->get();

            // Check if the request expects JSON
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $oilTypes,
                    'message' => 'Oil types retrieved successfully'
                ]);
            }

            // Otherwise return the view
            return view('content.pages.system-management.oil-types', compact('oilTypes'));

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to retrieve oil types',
                    'error' => $e->getMessage()
                ], 500);
            }

            // For normal view requests, you could return an error page
            return redirect()->back()->with('error', 'Failed to retrieve oil types.');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'label' => 'required|string|max:255|unique:oil_types,label'
            ]);

            $oilType = OilType::create($validatedData);

            return response()->json([
                'success' => true,
                'data' => $oilType,
                'message' => 'Oil type created successfully'
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
                'message' => 'Failed to create oil type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OilType $oilType)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $oilType,
                'message' => 'Oil type retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve oil type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OilType $oilType)
    {
        try {
            $validatedData = $request->validate([
                'label' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('oil_types', 'label')->ignore($oilType->id)
                ]
            ]);

            $oilType->update($validatedData);

            return response()->json([
                'success' => true,
                'data' => $oilType->fresh(),
                'message' => 'Oil type updated successfully'
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
                'message' => 'Failed to update oil type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OilType $oilType)
    {
        try {
            $oilType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Oil type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete oil type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(OilType $oilType)
    {
        try {
            $newStatus = $oilType->status === 'active' ? 'inactive' : 'active';
            $oilType->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'data' => $oilType->fresh(),
                'message' => "Oil type {$newStatus} successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle oil type status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
