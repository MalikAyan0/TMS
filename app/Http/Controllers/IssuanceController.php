<?php

namespace App\Http\Controllers;

use App\Models\Issuance;
use Illuminate\Http\Request;
use App\Models\Tank;
use App\Models\Fleet;
use App\Models\Operation;

class IssuanceController extends Controller
{
    public function index(Request $request)
    {
        $issuances = Issuance::with(['tank', 'fleet', 'operation'])->latest()->get();
        $tanks = Tank::all();
        $fleets = Fleet::all();
        $operations = Operation::where('status', 'active')->get();

        if ($request->wantsJson()) {
            return response()->json($issuances);
        }

        return view('content.pages.fuel-management.issuance', compact('issuances', 'tanks', 'fleets', 'operations'));
    }

    public function create()
    {
        return view('content.pages.fuel-management.issuance', compact('tanks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tank_id'           => 'required|exists:tanks,id',
            'fleet_id'          => 'required|exists:fleets,id',
            'operation_id'      => 'required|exists:operations,id',
            'fill_date'         => 'required|date',
            'qty'               => 'required|numeric|min:0',
            'driver'            => 'nullable|string|max:255',
            'odometer_reading'  => 'nullable|integer',
            'remarks'           => 'nullable|string',
        ]);

        $issuance = Issuance::create($validated + ['created_by' => auth()->id()]);

        return $request->wantsJson()
            ? response()->json([
                'success' => true,
                'message' => 'Issuance created successfully.',
                'data' => $issuance
            ], 200)
            : redirect()->route('content.pages.fuel-management.issuance')->with('success', 'Issuance created successfully.');
    }

    public function show(Issuance $issuance)
    {
        $issuance->load(['tank', 'fleet', 'operation', 'creator']);

        return request()->wantsJson()
            ? response()->json($issuance)
            : view('content.pages.fuel-management.issuance', compact('issuance'));
    }

    public function edit(Issuance $issuance)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $issuance->id,
                'tank_id' => $issuance->tank_id,
                'fleet_id' => $issuance->fleet_id,
                'operation_id' => $issuance->operation_id,
                'fill_date' => $issuance->fill_date,
                'qty' => $issuance->qty,
                'driver' => $issuance->driver,
                'odometer_reading' => $issuance->odometer_reading,
                'remarks' => $issuance->remarks,
            ]
        ]);
    }

    public function update(Request $request, Issuance $issuance)
    {
        $validated = $request->validate([
            'tank_id'           => 'required|exists:tanks,id',
            'fleet_id'          => 'required|exists:fleets,id',
            'operation_id'      => 'required|exists:operations,id',
            'fill_date'         => 'required|date',
            'qty'               => 'required|numeric|min:0',
            'driver'            => 'nullable|string|max:255',
            'odometer_reading'  => 'nullable|integer',
            'remarks'           => 'nullable|string',
        ]);

        $issuance->update($validated);

        return $request->wantsJson()
            ? response()->json(
              [
                  'success' => true,
                  'message' => 'Issuance updated successfully.',
                  'data' => $issuance
              ])
            : redirect()->route('content.pages.fuel-management.issuance')->with('success', 'Issuance updated successfully.');
    }

    public function destroy(Issuance $issuance)
    {
        $issuance->delete();

        return request()->wantsJson()
            ? response()->json(null, 204)
            : redirect()->route('content.pages.fuel-management.issuance')->with('success', 'Issuance deleted successfully.');
    }
}
