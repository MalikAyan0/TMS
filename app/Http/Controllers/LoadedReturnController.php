<?php

namespace App\Http\Controllers;

use App\Models\LoadedReturn;
use App\Models\ExportJob;
use App\Models\Fleet;
use App\Models\Route;
use App\Models\ExportJobStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoadedReturnController extends Controller
{
  /**
   * Display a listing of loaded returns.
   */
  public function index(Request $request)
  {
    $loadedReturns = ExportJob::with([
      'cro',
      'forwarder',
      'line',
      'pod',
      'terminal',
      'logistics.vehicle',
      'logistics.route',
      'loadedReturn.vehicle',
      'loadedReturn.route'
    ])
      ->whereIn('status', ['Vehicle Required', 'On Route'])
      ->whereHas('statusLogs', function ($query) {
        $query
          // ✅ Ensure this is the latest log for the job
          ->whereRaw('export_job_status_logs.id = (
            SELECT MAX(id)
            FROM export_job_status_logs AS latest
            WHERE latest.export_job_id = export_job_status_logs.export_job_id
        )')
          ->whereIn('status', ['Vehicle Required', 'On Route'])
          ->whereRaw('(
            SELECT COUNT(*)
            FROM export_job_status_logs AS ready_to_move
            WHERE ready_to_move.export_job_id = export_job_status_logs.export_job_id
            AND ready_to_move.status = "Ready To Move"
            AND ready_to_move.id < export_job_status_logs.id
        ) > 0') // ✅ Must have a "Ready To Move" log before current
          ->whereRaw('(
            SELECT status
            FROM export_job_status_logs AS prev
            WHERE prev.export_job_id = export_job_status_logs.export_job_id
            AND prev.id < export_job_status_logs.id
            ORDER BY prev.id DESC
            LIMIT 1
        ) IN ("Ready To Move", "Vehicle Required")');
      })
      ->orderBy('created_at', 'desc')
      ->get();


    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'data' => $loadedReturns
      ]);
    }

    $fleets = Fleet::all();
    $routes = Route::where('status', 'active')->where('load', 'LOAD')->get();

    return view('content.pages.export.loaded-returns.index', compact('loadedReturns', 'fleets', 'routes'));
  }

  /**
   * Show the form for creating a new loaded return.
   */
  public function create(ExportJob $job)
  {
    $fleets = Fleet::where('status', 'active')->get();
    $routes = Route::where('status', 'active')->where('load', 'EMPTY')->get();

    return response()->json([
      'success' => true,
      'data' => [
        'job' => $job,
        'fleets' => $fleets,
        'routes' => $routes
      ]
    ]);
  }

  /**
   * Store a newly created loaded return.
   */
  public function store(Request $request, ExportJob $job)
  {
    // Base validation rules
    $rules = [
      'market_vehicle' => 'required|in:yes,no',
      'gate_pass' => 'required|string|max:255',
      'route_id' => 'required|exists:routes,id',
    ];

    // Conditional validation based on market_vehicle value
    if ($request->input('market_vehicle') === 'yes') {
      $rules['market_vehicle_details'] = 'required|string|max:255';
    } else {
      $rules['vehicle_id'] = 'required|exists:fleets,id';
    }

    $validated = $request->validate($rules);

    DB::transaction(function () use ($job, $validated) {
      // Create loaded return record
      $validated['export_job_id'] = $job->id;
      $validated['gate_time_passed'] = now();
      LoadedReturn::create($validated);

      // Update job status to 'On Route'
      $job->update(['status' => 'On Route']);

      // Create job status log entry
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'On Route',
        'changed_by' => Auth::id(),
        'remarks' => 'Loaded return assigned'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Loaded return assigned successfully!',
      'data' => $job->load('loadedReturn.vehicle', 'loadedReturn.route')
    ]);
  }

  /**
   * Display the specified loaded return.
   */
  public function show(ExportJob $job)
  {
    $job->load(['loadedReturn.vehicle', 'loadedReturn.route', 'line', 'pod', 'terminal', 'cro']);

    return response()->json([
      'success' => true,
      'data' => $job
    ]);
  }

  /**
   * Show the form for editing the specified loaded return.
   */
  public function edit(LoadedReturn $loadedReturn)
  {
    $loadedReturn->load(['exportJob', 'vehicle', 'route']);
    $fleets = Fleet::where('status', 'active')->get();
    $routes = Route::where('status', 'active')->where('load', 'EMPTY')->get();

    return response()->json([
      'success' => true,
      'data' => [
        'loadedReturn' => $loadedReturn,
        'fleets' => $fleets,
        'routes' => $routes
      ]
    ]);
  }

  /**
   * Update the specified loaded return.
   */
  public function update(Request $request, LoadedReturn $loadedReturn)
  {
    // Base validation rules
    $rules = [
      'market_vehicle' => 'required|in:yes,no',
      'gate_pass' => 'required|string|max:255',
      'route_id' => 'nullable|exists:routes,id',
    ];

    // Conditional validation based on market_vehicle value
    if ($request->input('market_vehicle') === 'yes') {
      $rules['market_vehicle_details'] = 'required|string|max:255';
    } else {
      $rules['vehicle_id'] = 'required|exists:fleets,id';
    }

    $validated = $request->validate($rules);

    $loadedReturn->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Loaded return updated successfully!',
      'data' => $loadedReturn->load('exportJob', 'vehicle', 'route')
    ]);
  }

  /**
   * Remove the specified loaded return.
   */
  public function destroy(LoadedReturn $loadedReturn)
  {
    DB::transaction(function () use ($loadedReturn) {
      $job = $loadedReturn->exportJob;

      // Delete the loaded return record
      $loadedReturn->delete();

      // Update job status back to 'Vehicle Returned'
      $job->update(['status' => 'Vehicle Returned']);

      // Create job status log entry
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'Vehicle Returned',
        'changed_by' => Auth::id(),
        'remarks' => 'Loaded return removed'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Loaded return removed successfully!'
    ]);
  }

  /**
   * Mark loaded return as completed.
   */
  public function markCompleted(Request $request, ExportJob $job)
  {
    if (!$job->loadedReturn) {
      return response()->json([
        'success' => false,
        'message' => 'Loaded return not found for this job'
      ], 404);
    }

    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($job, $request) {
      // Update job status to 'Completed'
      $job->update(['status' => 'Completed']);

      // Create job status log entry
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'Completed',
        'changed_by' => Auth::id(),
        'remarks' => $request->input('remarks') ?: 'Job marked as completed'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Job marked as completed successfully!',
      'data' => $job->fresh()
    ]);
  }

  /**
   * Mark loaded return as completed by loaded return ID.
   */
  public function markCompletedByReturn(Request $request, LoadedReturn $loadedReturn)
  {
    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($loadedReturn, $request) {
      $job = $loadedReturn->exportJob;

      // Update job status to 'Completed'
      $job->update(['status' => 'Completed']);

      // Create job status log entry
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'Completed',
        'changed_by' => Auth::id(),
        'remarks' => $request->input('remarks') ?: 'Loaded return completed'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Loaded return marked as completed successfully!',
      'data' => $loadedReturn->exportJob->fresh()
    ]);
  }

  /**
   * Mark loaded return as dry off.
   */
  public function markDryOff(Request $request, ExportJob $job)
  {
    if (!$job->loadedReturn) {
      return response()->json([
        'success' => false,
        'message' => 'Loaded return not found for this job'
      ], 404);
    }

    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($job, $request) {
      // Update job status to 'Dry Off'
      $job->update(['status' => 'Dry Off']);

      // Create job status log entry
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'Dry Off',
        'changed_by' => Auth::id(),
        'remarks' => $request->input('remarks') ?: 'Job marked as dry off'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Job marked as dry off successfully!',
      'data' => $job->fresh(['loadedReturn', 'line', 'pod', 'terminal', 'cro'])
    ]);
  }

  /**
   * Get available vehicles for loaded return assignment.
   */
  public function getAvailableVehicles(Request $request)
  {
    $vehicles = Fleet::where('status', 'active')
      ->whereDoesntHave('loadedReturns', function ($query) {
        $query->whereHas('exportJob', function ($q) {
          $q->where('status', 'Empty Return');
        });
      })
      ->select('id', 'registration_number', 'model', 'type')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $vehicles
    ]);
  }

  /**
   * Get loaded return statistics.
   */
  public function getStatistics()
  {
    $stats = [
      'total_loaded_returns' => LoadedReturn::count(),
      'pending_loaded_returns' => ExportJob::where('status', 'Empty Return')->count(),
      'completed_today' => ExportJob::where('status', 'Completed')
        ->whereDate('updated_at', today())
        ->count(),
      'active_vehicles' => Fleet::where('status', 'active')
        ->whereHas('loadedReturns', function ($query) {
          $query->whereHas('exportJob', function ($q) {
            $q->where('status', 'Empty Return');
          });
        })->count(),
    ];

    return response()->json([
      'success' => true,
      'data' => $stats
    ]);
  }
}
