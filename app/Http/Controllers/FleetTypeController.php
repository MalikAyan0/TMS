<?php

namespace App\Http\Controllers;

use App\Models\FleetType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;


class FleetTypeController extends Controller
{
    // List all fleet types
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $fleetTypes = FleetType::with('creator')->latest()->get();

            return response()->json([
                'data' => $fleetTypes
            ]);
        }

        return view('content.pages.fleets-management.fleets-types');
    }


    // Show create form
    public function create()
    {
        return view('fleet_types.create');
    }

    // Store fleet type
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
                'message' => 'Validation failed.',
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('fleet_types', 'public');
        }

        $fleetType = FleetType::create([
            'title' => $request->title,
            'image' => $imagePath,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fleet Type created successfully.',
            'data'    => $fleetType
        ]);
    }


    // Show edit form
    public function edit(FleetType $fleetType)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id'    => $fleetType->id,
                'title' => $fleetType->title,
                'image' => $fleetType->image ? asset('storage/'.$fleetType->image) : null,
            ]
        ]);
    }


    // Update fleet type
    public function update(Request $request, FleetType $fleetType)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $imagePath = $fleetType->image; // keep old image by default

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($fleetType->image && \Storage::disk('public')->exists($fleetType->image)) {
                \Storage::disk('public')->delete($fleetType->image);
            }

            // store new one
            $imagePath = $request->file('image')->store('fleet_types', 'public');
        }

        $fleetType->update([
            'title' => $request->title,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fleet Type updated successfully.',
            'data' => $fleetType
        ]);
    }



    // Delete
    public function destroy(FleetType $fleetType)
    {
        try {
            // Delete image if exists
            if ($fleetType->image && Storage::disk('public')->exists($fleetType->image)) {
                Storage::disk('public')->delete($fleetType->image);
            }

            // Delete the record
            $fleetType->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Fleet Type deleted successfully.'
                ]);
            }

            return redirect()->route('fleet-types.index')
                ->with('success', 'Fleet Type deleted successfully.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to delete Fleet Type.'
                ], 500);
            }

            return redirect()->route('fleet-types.index')
                ->with('error', 'Failed to delete Fleet Type.');
        }
    }
}
