<?php

namespace App\Http\Controllers;

use App\Models\FleetManufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FleetManufacturerController extends Controller
{
    public function index(Request $request)
    {
        $manufacturers = FleetManufacturer::with('creator')->latest()->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $manufacturers
            ]);
        }

        return view('content.pages.fleets-management.fleets-manufacturer', compact('manufacturers'));
    }


    public function create()
    {
        return view('fleet_manufacturers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('fleet_manufacturers', 'public');
        }

        $manufacturer = FleetManufacturer::create([
            'name'       => $request->name,
            'image'      => $imagePath,
            'created_by' => auth()->id(),
        ]);

        // If request wants JSON → return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fleet Manufacturer created successfully.',
                'data'    => $manufacturer
            ], 201);
        }

        // Otherwise → redirect
        return redirect()->route('fleet-manufacturers.index')
            ->with('success', 'Fleet Manufacturer created successfully.');
    }


    public function show(FleetManufacturer $fleetManufacturer)
    {
        return view('fleet_manufacturers.show', compact('fleetManufacturer'));
    }

    public function edit(FleetManufacturer $fleetManufacturer)
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'id'    => $fleetManufacturer->id,
                'name' => $fleetManufacturer->name,
                'image' => $fleetManufacturer->image ? asset('storage/'.$fleetManufacturer->image) : null,
            ]
        ]);
    }

    public function update(Request $request, FleetManufacturer $fleetManufacturer)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $imagePath = $fleetManufacturer->image;

        if ($request->hasFile('image')) {
            if ($fleetManufacturer->image && Storage::disk('public')->exists($fleetManufacturer->image)) {
                Storage::disk('public')->delete($fleetManufacturer->image);
            }
            $imagePath = $request->file('image')->store('fleet_manufacturers', 'public');
        }

        $fleetManufacturer->update([
            'name'  => $request->name,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fleet Manufacturer updated successfully.',
            'data'    => $fleetManufacturer
        ]);
    }

    public function destroy(FleetManufacturer $fleetManufacturer)
    {
        if ($fleetManufacturer->image && Storage::disk('public')->exists($fleetManufacturer->image)) {
            Storage::disk('public')->delete($fleetManufacturer->image);
        }

        $fleetManufacturer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fleet Manufacturer deleted successfully.'
        ]);
    }
}
