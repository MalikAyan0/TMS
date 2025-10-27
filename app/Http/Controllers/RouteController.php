<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
  /**
   * Display a listing of routes.
   */
  public function index(Request $request)
  {
    try {
      $routes = Route::with('stops', 'assignments')->get();

      if ($request->wantsJson()) {
        return response()->json([
          'success' => true,
          'data' => $routes
        ], 200);
      }

      return view('content.pages.routes.index', compact('routes'));
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch routes',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Store a newly created route in storage.
   */
  public function store(Request $request)
  {
    try {
      $request->validate([
        'route_name'      => 'required|string|max:255',
        'route_code'      => 'required|string|max:50|unique:routes,route_code',
        'origin'          => 'required|string|max:255',
        'destination'     => 'required|string|max:255',
        'load'            => 'required|in:LOAD,EMPTY',
        'expected_fuel'   => 'required|numeric|min:0',
        'status'          => 'in:planned,active,completed,cancelled',
      ]);

      $route = Route::create([
        'route_name'     => $request->route_name,
        'route_code'     => $request->route_code,
        'origin'         => $request->origin,
        'destination'    => $request->destination,
        'load'           => $request->load,
        'expected_fuel'  => $request->expected_fuel,
        'status'         => $request->status ?? 'planned',
        'created_by'     => Auth::id(),
      ]);

      if ($request->wantsJson()) {
        return response()->json([
          'success' => true,
          'message' => 'Route created successfully',
          'data' => $route
        ], 201);
      }

      return redirect()->route('routes.index')->with('success', 'Route created successfully.');
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to create route',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified route.
   */
  public function show($id)
  {
    try {
      $route = Route::with(['stops', 'assignments'])->findOrFail($id);

      return response()->json([
        'success' => true,
        'data'    => $route
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Route not found',
        'error'   => $e->getMessage()
      ], 404);
    }
  }


  /**
   * Update the specified route.
   */
  public function update(Request $request, $id)
  {
    try {
      $route = Route::findOrFail($id);

      $request->validate([
        'route_name'      => 'required|string|max:255',
        'route_code'      => 'required|string|max:50|unique:routes,route_code,' . $route->id,
        'origin'          => 'required|string|max:255',
        'destination'     => 'required|string|max:255',
        'load'            => 'required|in:LOAD,EMPTY',
        'expected_fuel'   => 'required|numeric|min:0',
        'status'          => 'in:planned,active,completed,cancelled',
      ]);

      $route->update([
        'route_name'     => $request->route_name,
        'route_code'     => $request->route_code,
        'origin'         => $request->origin,
        'destination'    => $request->destination,
        'load'           => $request->load,
        'expected_fuel'  => $request->expected_fuel,
        'status'         => $request->status,
      ]);

      if ($request->wantsJson()) {
        return response()->json([
          'success' => true,
          'message' => 'Route updated successfully',
          'data' => $route
        ], 200);
      }

      return redirect()->route('routes.index')->with('success', 'Route updated successfully.');
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update route',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Remove the specified route.
   */
  public function destroy(Request $request, $id)
  {
    try {
      $route = Route::findOrFail($id);
      $route->delete();

      if ($request->wantsJson()) {
        return response()->json([
          'success' => true,
          'message' => 'Route deleted successfully'
        ], 200);
      }

      return redirect()->route('routes.index')->with('success', 'Route deleted successfully.');
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete route',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
