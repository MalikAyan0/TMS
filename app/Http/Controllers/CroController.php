<?php

namespace App\Http\Controllers;

use App\Models\Cro;
use App\Models\Pod;
use App\Models\Line;
use App\Models\Fleet;
use App\Models\Party;
use App\Models\Route;
use App\Models\Location;
use App\Models\Terminal;
use Illuminate\Http\Request;

class CroController extends Controller
{
  /**
   * Display a listing of the CROs.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $cros = Cro::latest()->get();

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $cros
      ]);
    }

    return view('content.pages.cro.index', compact('cros'));
  }

  /**
   * Store a newly created CRO in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'cro_number' => 'required|string|unique:cros,cro_number',
      'description' => 'nullable|string',
      'status' => 'required|in:active,inactive',
    ]);

    $cro = Cro::create($validated);

    // if ($request->ajax() || $request->wantsJson()) {
    return response()->json([
      'success' => true,
      'message' => 'CRO created successfully.',
      'data' => $cro
    ], 201);
    // }

    // return redirect()->route('cros.index')->with('success', 'CRO created successfully.');
  }

  /**
   * Display the specified CRO.
   *
   * @param  \App\Models\Cro  $cro
   * @return \Illuminate\Http\Response
   */
  /**
   * Display the specified bail number with associated jobs.
   */
  public function show(Cro $cro)
  {
    // Load jobs with necessary relationships
    $jobs = $cro->exportJobs()->with(['line', 'pod', 'terminal', 'forwarder', 'emptyPickupLocation'])
      ->orderBy('created_at', 'desc')
      ->get();

    // Calculate job statistics
    $jobsCount = $jobs->count();
    $activeJobsCount = $jobs->whereNotIn('status', ['Completed', 'Cancelled'])->count();

    // Get necessary data for forms
    $forwarders = Party::where('status', 'active')->where('party_type', 'customer')->get();
    $lines = Line::where('status', 'active')->get();
    $pods = Pod::where('status', 'active')->get();
    $terminals = Terminal::where('status', 'active')->get();
    $emptypickupLocations = Location::where('status', 'active')->get();
    $fleets = Fleet::all();
    $routes = Route::where('status', 'active')->get();
    $cros = Cro::where('status', 'active')->get();

    // If it's an AJAX request, return JSON
    if (request()->expectsJson()) {
      return response()->json([
        'success' => true,
        'data' => [
          'cro' => $cro,
          'jobs' => $jobs,
          'jobs_count' => $jobsCount,
          'active_jobs_count' => $activeJobsCount
        ]
      ]);
    }

    // Return view with data
    return view('content.pages.cro.view', compact(
      'cro',
      'jobs',
      'jobsCount',
      'activeJobsCount',
      'forwarders',
      'lines',
      'pods',
      'terminals',
      'emptypickupLocations',
      'fleets',
      'cros',
      'routes'
    ));
  }

  /**
   * Update the specified CRO in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Cro  $cro
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Cro $cro)
  {
    $validated = $request->validate([
      'cro_number' => 'required|string|unique:cros,cro_number,' . $cro->id,
      'description' => 'nullable|string',
      'status' => 'required|in:active,inactive',
    ]);

    $cro->update($validated);

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'CRO updated successfully.',
        'data' => $cro
      ]);
    }

    return redirect()->route('cros.index')->with('success', 'CRO updated successfully.');
  }

  /**
   * Remove the specified CRO from storage.
   *
   * @param  \App\Models\Cro  $cro
   * @return \Illuminate\Http\Response
   */
  public function destroy(Cro $cro)
  {
    $cro->delete();

    return response()->json([
      'success' => true,
      'message' => 'CRO deleted successfully.'
    ]);
  }

  /**
   * Get export jobs data for the specified CRO.
   */
  public function getJobsData(Cro $cro)
  {
    $jobs = $cro->exportJobs()
      ->with(['line', 'pod', 'terminal', 'forwarder', 'cro', 'logistics.vehicle', 'emptyPickupLocation'])
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $jobs
    ]);
  }

  /**
   * Get active CROs for dropdowns.
   */
  public function getActiveCros()
  {
    $cros = Cro::where('status', 'active')
      ->select('id', 'cro_number', 'description')
      ->orderBy('cro_number')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $cros
    ]);
  }
}
