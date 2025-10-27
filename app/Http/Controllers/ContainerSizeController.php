<?php

namespace App\Http\Controllers;

use App\Models\ContainerSize;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ContainerSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $containerSizes = ContainerSize::orderBy('created_at', 'desc')->get();
          if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $containerSizes
                ]);
            }
            return view('content.pages.system-management.container-sizes', compact('containerSizes'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve container sizes',
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
            $validator = Validator::make($request->all(), [
                'container_size' => 'required|string|max:255|unique:container_sizes,container_size',
            ], [
                'container_size.required' => 'Container size is required',
                'container_size.string' => 'Container size must be a string',
                'container_size.max' => 'Container size cannot exceed 255 characters',
                'container_size.unique' => 'This container size already exists',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $containerSize = ContainerSize::create([
                'container_size' => $request->container_size,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'data' => $containerSize,
                'message' => 'Container size created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create container size',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $containerSize = ContainerSize::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $containerSize,
                'message' => 'Container size retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Container size not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $containerSize = ContainerSize::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'container_size' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('container_sizes', 'container_size')->ignore($id)
                ],
            ], [
                'container_size.required' => 'Container size is required',
                'container_size.string' => 'Container size must be a string',
                'container_size.max' => 'Container size cannot exceed 255 characters',
                'container_size.unique' => 'This container size already exists',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $containerSize->update([
                'container_size' => $request->container_size,
            ]);

            return response()->json([
                'success' => true,
                'data' => $containerSize,
                'message' => 'Container size updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update container size',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $containerSize = ContainerSize::findOrFail($id);
            $containerSize->delete();

            return response()->json([
                'success' => true,
                'message' => 'Container size deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete container size',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(string $id): JsonResponse
    {
        try {
            $containerSize = ContainerSize::findOrFail($id);
            $containerSize->toggleStatus();

            return response()->json([
                'success' => true,
                'data' => $containerSize,
                'message' => 'Container size status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update container size status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
