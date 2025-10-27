<?php

namespace App\Http\Controllers;

use App\Models\Port;
use Illuminate\Http\Request;

class PortController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $ports = Port::all();
    if (request()->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $ports
      ]);
    }
    return view('content.pages.system-management.port', compact('ports'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
    $validated = $request->validate(
      [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
      ]
    );

    $port = Port::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Port created successfully',
      'data' => $port
    ], 200);
  }

  /**
   * Display the specified resource.
   */
  public function show(Port $port)
  {
    //
    try {
      return response()->json([
        'success' => true,
        'data' => $port
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Port not found',
        'error' => $e->getMessage()
      ], 404);
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Port $port)
  {
    //
    return response()->json([
      'success' => true,
      'data' => $port
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Port $port)
  {
    //
    $validated = $request->validate(
      [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'status' => 'required|in:active,inactive',
      ]
    );

    $port->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Port updated successfully',
      'data' => $port
    ], 200);
  }

  /**
   * Toggle port status.
   */
  public function toggleStatus(Port $port)
  {
    try {
      $port->status = $port->status === 'active' ? 'inactive' : 'active';
      $port->save();

      return response()->json([
        'success' => true,
        'message' => 'Port status updated successfully',
        'data' => $port
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update port status',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Port $port)
  {
    //
    try {
      $port->delete();
      return response()->json([
        'success' => true,
        'message' => 'Port deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete port',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
