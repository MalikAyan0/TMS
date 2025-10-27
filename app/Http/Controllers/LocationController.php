<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    try {
      $locations = Location::with(['creator', 'updater'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($location) {
          return [
            'id' => $location->id,
            'title' => $location->title,
            'short_name' => $location->short_name,
            'description' => $location->description,
            'type' => $location->type,
            'status' => $location->status,
            'country' => $location->country,
            'city' => $location->city,
            'address' => $location->address,
            'postal_code' => $location->postal_code,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'full_address' => $location->full_address,
            'coordinates' => $location->coordinates,
            'created_at' => $location->created_at,
            'updated_at' => $location->updated_at,
            'created_by_name' => $location->creator?->name,
            'updated_by_name' => $location->updater?->name,
          ];
        });

      if ($request->wantsJson()) {
        return response()->json([
          'success' => true,
          'data' => $locations,
          'message' => 'Locations retrieved successfully'
        ]);
      } else {
        return view('content.pages.system-management.locations', ['locations' => $locations]);
      }
    } catch (\Exception $e) {
      if ($request->wantsJson()) {
        return response()->json([
          'success' => false,
          'message' => 'Failed to retrieve locations: ' . $e->getMessage()
        ], 500);
      } else {
        return view('content.pages.system-management.locations', ['locations' => collect(), 'error' => $e->getMessage()]);
      }
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): JsonResponse
  {
    try {
      $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'short_name' => 'required|string|max:10|unique:locations,short_name',
        'description' => 'required|string',
        'type' => ['nullable', Rule::in(['port', 'warehouse', 'terminal', 'depot', 'office', 'other'])],
        'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance'])],
        'country' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'postal_code' => 'nullable|string|max:20',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
      ]);

      $validatedData['created_by'] = Auth::id();
      $validatedData['updated_by'] = Auth::id();

      $location = Location::create($validatedData);
      $location->load(['creator', 'updater']);

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $location->id,
          'title' => $location->title,
          'short_name' => $location->short_name,
          'description' => $location->description,
          'type' => $location->type,
          'status' => $location->status,
          'country' => $location->country,
          'city' => $location->city,
          'address' => $location->address,
          'postal_code' => $location->postal_code,
          'latitude' => $location->latitude,
          'longitude' => $location->longitude,
          'full_address' => $location->full_address,
          'coordinates' => $location->coordinates,
          'created_at' => $location->created_at,
          'updated_at' => $location->updated_at,
          'created_by_name' => $location->creator?->name,
          'updated_by_name' => $location->updater?->name,
        ],
        'message' => 'Location created successfully'
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $e->validator->errors()->getMessages()
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to create location: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Location $location): JsonResponse
  {
    try {
      $location->load(['creator', 'updater']);

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $location->id,
          'title' => $location->title,
          'short_name' => $location->short_name,
          'description' => $location->description,
          'type' => $location->type,
          'status' => $location->status,
          'country' => $location->country,
          'city' => $location->city,
          'address' => $location->address,
          'postal_code' => $location->postal_code,
          'latitude' => $location->latitude,
          'longitude' => $location->longitude,
          'full_address' => $location->full_address,
          'coordinates' => $location->coordinates,
          'created_at' => $location->created_at,
          'updated_at' => $location->updated_at,
          'created_by_name' => $location->creator?->name,
          'updated_by_name' => $location->updater?->name,
        ],
        'message' => 'Location retrieved successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to retrieve location: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Location $location): JsonResponse
  {
    try {
      $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'short_name' => ['required', 'string', 'max:10', Rule::unique('locations', 'short_name')->ignore($location->id)],
        'description' => 'required|string',
        'type' => ['nullable', Rule::in(['port', 'warehouse', 'terminal', 'depot', 'office', 'other'])],
        'status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance'])],
        'country' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'postal_code' => 'nullable|string|max:20',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
      ]);

      $validatedData['updated_by'] = Auth::id();

      $location->update($validatedData);
      $location->load(['creator', 'updater']);

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $location->id,
          'title' => $location->title,
          'short_name' => $location->short_name,
          'description' => $location->description,
          'type' => $location->type,
          'status' => $location->status,
          'country' => $location->country,
          'city' => $location->city,
          'address' => $location->address,
          'postal_code' => $location->postal_code,
          'latitude' => $location->latitude,
          'longitude' => $location->longitude,
          'full_address' => $location->full_address,
          'coordinates' => $location->coordinates,
          'created_at' => $location->created_at,
          'updated_at' => $location->updated_at,
          'created_by_name' => $location->creator?->name,
          'updated_by_name' => $location->updater?->name,
        ],
        'message' => 'Location updated successfully'
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
        'message' => 'Failed to update location: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Toggle the status of a location.
   */
  public function toggleStatus(Location $location): JsonResponse
  {
    try {
      $newStatus = $location->status === 'active' ? 'inactive' : 'active';

      $location->update([
        'status' => $newStatus,
        'updated_by' => Auth::id()
      ]);

      $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';

      return response()->json([
        'success' => true,
        'data' => [
          'id' => $location->id,
          'status' => $location->status
        ],
        'message' => "Location {$statusText} successfully"
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to toggle location status: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Location $location): JsonResponse
  {
    try {
      // Check if any export jobs use this location
      if ($location->exportJobs()->count() > 0) {
        return response()->json([
          'success' => false,
          'message' => 'Cannot delete this location as it is used by one or more export jobs.'
        ], 422);
      }

      $location->delete();

      return response()->json([
        'success' => true,
        'message' => 'Location deleted successfully.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete location: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get active locations for dropdown/select lists.
   */
  public function active(): JsonResponse
  {
    try {
      $locations = Location::active()
        ->select('id', 'title', 'short_name', 'city', 'country')
        ->orderBy('title')
        ->get();

      return response()->json([
        'success' => true,
        'data' => $locations,
        'message' => 'Active locations retrieved successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to retrieve active locations: ' . $e->getMessage()
      ], 500);
    }
  }
}
