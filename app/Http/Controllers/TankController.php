<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\FuelType;
use App\Models\User;
use Illuminate\Http\Request;

class TankController extends Controller
{
  // List all tanks (JSON for DataTables, view for normal)
  public function index(Request $request)
  {
    $tanks = Tank::with(['fuelType', 'supervisor'])->get();
    $fuelTypes = FuelType::all();
    $fuelSupervisors = User::all();

    if ($request->wantsJson() || $request->ajax()) {
      return response()->json(['data' => $tanks]);
    }

    return view('content.pages.tanks-management.index', compact('tanks', 'fuelTypes', 'fuelSupervisors'));
  }

  // Store a new tank
  public function store(Request $request)
  {
    $validated = $request->validate([
      'fuel_type_id' => 'required|exists:fuel_types,id',
      'name' => 'required|string|max:255',
      'supervisor_id' => 'required|exists:users,id',
      'location' => 'required|string|max:255',
      'capacity_volume' => 'required|numeric|min:0',
      'remarks' => 'nullable|string',
    ]);

    $tank = Tank::create($validated);
    return response()->json(['success' => true, 'data' => $tank]);
  }

  // Show a single tank
  public function show($id)
  {
    $tank = Tank::with(['fuelType', 'supervisor', 'fuelManagements'])->findOrFail($id);

    // Calculate total quantity from fuelManagements
    $totalQuantity = $tank->fuelManagements->sum('qty');

    // Calculate available fuel
    $availableFuel =  $totalQuantity;

    return view('content.pages.tanks-management.view', compact('tank', 'availableFuel'));
  }

  // Update a tank
  public function update(Request $request, $id)
  {
    $tank = Tank::findOrFail($id);

    $validated = $request->validate([
      'fuel_type_id' => 'required|exists:fuel_types,id',
      'name' => 'required|string|max:255',
      'supervisor_id' => 'nullable|exists:users,id',
      'location' => 'nullable|string|max:255',
      'capacity_volume' => 'nullable|numeric|min:0',
      'remarks' => 'nullable|string',
    ]);

    $tank->update($validated);
    return response()->json(['success' => true, 'data' => $tank]);
  }

  // Toggle tank status
  public function toggleStatus(Request $request, $id)
  {
    $tank = Tank::findOrFail($id);
    $tank->status = $request->status;
    $tank->save();
    return response()->json(['success' => true]);
  }

  // Delete a tank
  public function destroy($id)
  {
    $tank = Tank::findOrFail($id);
    $tank->delete();
    return response()->json(['success' => true]);
  }
}
