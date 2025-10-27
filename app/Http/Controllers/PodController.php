<?php

namespace App\Http\Controllers;

use App\Models\Pod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PodController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $pods = Pod::all();
    if (request()->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $pods
      ]);
    }
    return view('content.pages.system-management.pod', compact('pods'));
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

    $pod = Pod::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'POD created successfully',
      'data' => $pod
    ], 200);
  }

  /**
   * Display the specified resource.
   */
  public function show(Pod $pod)
  {
    //
    try {
      return response()->json([
        'success' => true,
        'data' => $pod
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'POD not found',
        'error' => $e->getMessage()
      ], 404);
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Pod $pod)
  {
    //
    return response()->json([
      'success' => true,
      'data' => $pod
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Pod $pod)
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

    $pod->update($validator->validated());

    return response()->json([
      'success' => true,
      'message' => 'POD updated successfully',
      'data' => $pod
    ]);
  }

  /**
   * Toggle pod status.
   */
  public function toggleStatus(Pod $pod)
  {
    try {
      $pod->status = $pod->status === 'active' ? 'inactive' : 'active';
      $pod->save();

      return response()->json([
        'success' => true,
        'message' => 'POD status updated successfully',
        'data' => $pod
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update POD status',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Pod $pod)
  {
    //
    try {
      $pod->delete();
      return response()->json([
        'success' => true,
        'message' => 'POD deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete POD',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
