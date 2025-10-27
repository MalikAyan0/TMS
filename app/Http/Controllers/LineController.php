<?php

namespace App\Http\Controllers;

use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LineController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $lines = Line::all();
    if (request()->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $lines
      ]);
    }
    return view('content.pages.system-management.line', compact('lines'));
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

    $line = Line::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Line created successfully',
      'data' => $line
    ], 200);
  }

  /**
   * Display the specified resource.
   */
  public function show(Line $line)
  {
    //
    try {
      return response()->json([
        'success' => true,
        'data' => $line
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Line not found',
        'error' => $e->getMessage()
      ], 404);
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Line $line)
  {
    //
    return response()->json([
      'success' => true,
      'data' => $line
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Line $line)
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

    $line->update($validator->validated());

    return response()->json([
      'success' => true,
      'message' => 'Line updated successfully',
      'data' => $line
    ]);
  }

  /**
   * Toggle line status.
   */
  public function toggleStatus(Line $line)
  {
    try {
      $line->status = $line->status === 'active' ? 'inactive' : 'active';
      $line->save();

      return response()->json([
        'success' => true,
        'message' => 'Line status updated successfully',
        'data' => $line
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update line status',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Line $line)
  {
    //
    try {
      $line->delete();
      return response()->json([
        'success' => true,
        'message' => 'Line deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete line',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
