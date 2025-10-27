<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouteStop;
use App\Models\Route;

class RouteStopController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $stops = RouteStop::with('route')->get();
    $routes = Route::all();
    if (request()->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $stops
      ], 200);
    }
    return view('content.pages.routes.route-stop', compact('stops', 'routes'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
    $routes = Route::all();
    return view('content.pages.routes.route-stop-create', compact('routes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'route_id'              => 'required|exists:routes,id',
      'stop_order'            => 'required|integer|min:1',
      'location_name'         => 'required|string|max:255',
      // 'latitude'              => 'nullable|numeric|between:-90,90',
      // 'longitude'             => 'nullable|numeric|between:-180,180',
      'load'                  => 'nullable|string|max:255',
      'load_type'             => 'nullable|string|max:255',
      'arrival_time'          => 'required|date_format:H:i',
      'departure_time'        => 'required|date_format:H:i',
      'actual_arrival_time'   => 'nullable|date_format:H:i',
      'actual_departure_time' => 'nullable|date_format:H:i',
    ]);

    $stop = RouteStop::create($validated);

    if ($request->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $stop
      ], 201);
    }

    return redirect()
      ->route('route-stops.index')
      ->with('success', 'Route stop created successfully.');
  }


  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
