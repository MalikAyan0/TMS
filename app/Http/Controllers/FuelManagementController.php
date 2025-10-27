<?php

namespace App\Http\Controllers;

use App\Models\FuelManagement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Party;
use App\Models\FuelType;
use App\Models\Tank;
use Illuminate\Support\Facades\Storage;

class FuelManagementController extends Controller
{
  // List all fuel records (for DataTables)
  public function index(Request $request)
  {
    $fuels = FuelManagement::with(['vendor', 'fuelType', 'tank'])->get();
    $vendors = Party::where('status', 'active')->where('party_type', 'vendor')->get();
    $fuelTypes = FuelType::where('status', 'active')->get();
    $tanks = Tank::where('status', 'active')->get();

    // If AJAX or expects JSON, return JSON for DataTables
    if ($request->wantsJson() || $request->ajax()) {
      return response()->json(['data' => $fuels]);
    }

    // Otherwise, return the Blade view
    return view('content.pages.fuel-management.index', compact('fuels', 'vendors', 'fuelTypes', 'tanks'));
  }

  // Store a new fuel record
  public function store(Request $request)
  {
    $validated = $request->validate([
      'vendor_id' => 'required|exists:parties,id',
      'fuel_type_id' => 'required|exists:fuel_types,id',
      'tank_id' => 'required|exists:tanks,id',
      'qty' => 'required|numeric|min:0',
      'rate' => 'required|numeric|min:0',
      'amount' => 'required|numeric|min:0',
      'delivery_date' => 'nullable|date',
      'freight_charges' => 'nullable|numeric|min:0',
      'remarks' => 'nullable|string',
      'slip_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Added required slip image validation
    ]);

    // Handle the slip image upload
    if ($request->hasFile('slip_image')) {
      $path = $request->file('slip_image')->store('fuel-slips', 'public');
      $validated['slip_image'] = $path;
    }

    $fuel = FuelManagement::create($validated);
    return response()->json([
      'success' => true,
      'data' => $fuel,
      'message' => 'Fuel record created successfully with receipt image.'
    ]);
  }

  // Show a single fuel record
  public function show($id)
  {
    $fuel = FuelManagement::with(['vendor', 'fuelType', 'tank'])->findOrFail($id);
    return response()->json(['data' => $fuel]);
  }

  // Update a fuel record
  public function update(Request $request, $id)
  {
    $fuel = FuelManagement::findOrFail($id);

    $validated = $request->validate([
      'vendor_id' => 'required|exists:parties,id',
      'fuel_type_id' => 'required|exists:fuel_types,id',
      'tank_id' => 'required|exists:tanks,id',
      'qty' => 'required|numeric|min:0',
      'rate' => 'required|numeric|min:0',
      'amount' => 'required|numeric|min:0',
      'delivery_date' => 'nullable|date',
      'freight_charges' => 'nullable|numeric|min:0',
      'remarks' => 'nullable|string',
      'slip_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Changed to sometimes for update
    ]);

    // Handle the slip image upload for update
    if ($request->hasFile('slip_image')) {
      // Delete the old image if it exists
      if ($fuel->slip_image) {
        Storage::disk('public')->delete($fuel->slip_image);
      }

      $path = $request->file('slip_image')->store('fuel-slips', 'public');
      $validated['slip_image'] = $path;
    }

    $fuel->update($validated);
    return response()->json([
      'success' => true,
      'data' => $fuel,
      'message' => 'Fuel record updated successfully.'
    ]);
  }

  // Delete a fuel record
  public function destroy($id)
  {
    $fuel = FuelManagement::findOrFail($id);

    // Delete the associated image when deleting the record
    if ($fuel->slip_image) {
      Storage::disk('public')->delete($fuel->slip_image);
    }

    $fuel->delete();
    return response()->json(['success' => true]);
  }

  /**
   * Download the slip image
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function downloadSlip($id)
  {
    try {
      $fuel = FuelManagement::findOrFail($id);

      if (!$fuel->slip_image) {
        return response()->json(['error' => 'No slip image found'], 404);
      }

      $fullPath = storage_path('app/public/' . $fuel->slip_image);

      if (!file_exists($fullPath)) {
        return response()->json(['error' => 'Image file not found'], 404);
      }

      return response()->download($fullPath);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Error downloading image: ' . $e->getMessage()], 500);
    }
  }
}
