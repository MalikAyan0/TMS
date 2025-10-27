<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use Illuminate\Http\Request;
use App\Models\FleetManufacturer;
use App\Models\FleetType;

use \Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{
  public function index(Request $request)
  {
    $manufacturers = FleetManufacturer::all();
    $fleetstypes = FleetType::all();
    $fleets = Fleet::with(['manufacturer', 'type', 'creator'])->latest()->get();

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $fleets
      ]);
    }

    return view('content.pages.fleets-management.index', compact('fleets', 'manufacturers', 'fleetstypes'));
  }

  public function create()
  {
    return view('fleets.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'fleet_manufacturer_id' => 'required|exists:fleet_manufacturers,id',
      'fleet_type_id' => 'required|exists:fleet_types,id',
      'first_driver' => 'nullable|string|max:255',
      'second_driver' => 'nullable|string|max:255',
      'registration_number' => 'required|string|max:255|unique:fleets',
      'registration_city' => 'nullable|string|max:255',
      'ownership' => 'nullable|string|max:255',
      'diesel_opening_inventory' => 'nullable|numeric|min:0',
    ]);

    $fleet = Fleet::create($request->all() + ['created_by' => Auth::user()->id]);

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Fleet created successfully.',
        'data' => $fleet
      ], 201);
    }

    return redirect()->route('content.pages.fleets-management.index')->with('success', 'Fleet created successfully.');
  }

  public function show(Fleet $fleet)
  {
    return response()->json([
      'success' => true,
      'data' => $fleet->load(['manufacturer', 'type', 'creator'])
    ]);
  }

  public function edit(Fleet $fleet)
  {
    return response()->json([
      'success' => true,
      'data' => $fleet
    ]);
  }

  public function update(Request $request, Fleet $fleet)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'fleet_manufacturer_id' => 'required|exists:fleet_manufacturers,id',
      'fleet_type_id' => 'required|exists:fleet_types,id',
      'first_driver' => 'nullable|string|max:255',
      'second_driver' => 'nullable|string|max:255',
      'registration_number' => 'required|string|max:255|unique:fleets,registration_number,' . $fleet->id,
      'registration_city' => 'nullable|string|max:255',
      'ownership' => 'nullable|string|max:255',
      'diesel_opening_inventory' => 'nullable|numeric|min:0',
    ]);

    $fleet->update($request->all());

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Fleet updated successfully.',
        'data' => $fleet
      ]);
    }

    return redirect()->route('content.pages.fleets-management.index')->with('success', 'Fleet updated successfully.');
  }

  public function destroy(Fleet $fleet, Request $request)
  {
    $fleet->delete();

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Fleet deleted successfully.'
      ]);
    }

    return redirect()->route('content.pages.fleets-management.index')->with('success', 'Fleet deleted successfully.');
  }
}
