<?php

namespace App\Http\Controllers;

use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TerminalController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $terminals = Terminal::all();
    if (request()->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $terminals
      ]);
    }
    return view('content.pages.system-management.terminal', compact('terminals'));
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

    $terminal = Terminal::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Terminal created successfully',
      'data' => $terminal
    ], 200);
  }

  /**
   * Display the specified resource.
   */
  public function show(Terminal $terminal)
  {
    //
    try {
      return response()->json([
        'success' => true,
        'data' => $terminal
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terminal not found',
        'error' => $e->getMessage()
      ], 404);
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Terminal $terminal)
  {
    //
    return response()->json([
      'success' => true,
      'data' => $terminal
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Terminal $terminal)
  {
    //
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'description' => 'required|string|max:1000',
      'status' => 'required|in:active,inactive',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $validator->errors()
      ], 422);
    }

    $terminal->update($validator->validated());

    return response()->json([
      'success' => true,
      'message' => 'Terminal updated successfully',
      'data' => $terminal
    ]);
  }

  /**
   * Toggle terminal status.
   */
  public function toggleStatus(Terminal $terminal)
  {
    try {
      $terminal->status = $terminal->status === 'active' ? 'inactive' : 'active';
      $terminal->save();

      return response()->json([
        'success' => true,
        'message' => 'Terminal status updated successfully',
        'data' => $terminal
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update terminal status',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Terminal $terminal)
  {
    //
    try {
      $terminal->delete();
      return response()->json([
        'success' => true,
        'message' => 'Terminal deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete terminal',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
