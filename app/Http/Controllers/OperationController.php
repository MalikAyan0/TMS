<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class OperationController extends Controller
{
    /**
     * Display a listing of operations.
     */
    public function index(Request $request)
    {
      try {
        $operations = Operation::orderBy('id', 'asc')->get();

        if ($request->wantsJson()) {
          return response()->json([
            'success' => true,
            'data' => $operations,
            'message' => 'Operations retrieved successfully'
          ]);
        }

        return view('content.pages.system-management.operations', compact('operations'));
      } catch (Exception $e) {
        if ($request->wantsJson()) {
          return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve operations: ' . $e->getMessage()
          ], 500);
        }
        return view('content.pages.system-management.operations')->withErrors('Failed to retrieve operations: ' . $e->getMessage());
      }
    }

    /**
     * Store a newly created operation.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255',
                'status' => 'nullable|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $operation = Operation::create([
                'label' => $request->label,
                'status' => $request->status ?? 'active',
            ]);

            return response()->json([
                'success' => true,
                'data' => $operation,
                'message' => 'Operation created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create operation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified operation.
     */
    public function show(Operation $operation): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $operation,
                'message' => 'Operation retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve operation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified operation.
     */
    public function update(Request $request, Operation $operation): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'label' => 'required|string|max:255',
                'status' => 'nullable|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $operation->update([
                'label' => $request->label,
                'status' => $request->status ?? $operation->status,
            ]);

            return response()->json([
                'success' => true,
                'data' => $operation,
                'message' => 'Operation updated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update operation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified operation.
     */
    public function destroy(Operation $operation): JsonResponse
    {
        try {
            $operation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Operation deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete operation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle operation status.
     */
    public function toggleStatus(Operation $operation): JsonResponse
    {
        try {
            $newStatus = $operation->status === 'active' ? 'inactive' : 'active';

            $operation->update([
                'status' => $newStatus,
            ]);

            return response()->json([
                'success' => true,
                'data' => $operation,
                'message' => "Operation {$newStatus} successfully"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle operation status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update operation status.
     */
    public function updateStatus(Request $request, Operation $operation): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $operation->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'data' => $operation,
                'message' => 'Operation status updated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update operation status: ' . $e->getMessage()
            ], 500);
        }
    }
}
