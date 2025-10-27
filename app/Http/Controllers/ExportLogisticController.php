<?php

namespace App\Http\Controllers;

use App\Models\ExportLogistic;
use App\Models\ExportJob;
use App\Models\Fleet;
use App\Models\Route;
use App\Models\ExportJobStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ExportLogisticController extends Controller
{
  /**
   * Display a listing of jobs for logistics operations.
   */
  public function index(Request $request)
  {
    $jobs = Cache::remember(
      'cached_export_logistics_jobs',
      60 * 5, // Cache for 5 minutes
      function () {
        $jobs = ExportJob::with([
          'cro',
          'forwarder',
          'line',
          'pod',
          'terminal',
          'logistics.vehicle',
          'logistics.route'
        ])
          ->whereIn('status', ['Vehicle Required', 'On Route', 'Empty Pick'])
          ->whereHas('statusLogs', function ($query) {
            $query->whereRaw('export_job_status_logs.id = (
                        SELECT MAX(id)
                        FROM export_job_status_logs AS latest
                        WHERE latest.export_job_id = export_job_status_logs.export_job_id
                    )')
              ->where(function ($q) {
                // ✅ Case 1: Vehicle Required → previous Open
                $q->where(function ($q2) {
                  $q2->where('status', 'Vehicle Required')
                    ->whereRaw('(SELECT status FROM export_job_status_logs AS prev
                                            WHERE prev.export_job_id = export_job_status_logs.export_job_id
                                            AND prev.id < export_job_status_logs.id
                                            ORDER BY prev.id DESC LIMIT 1) = "Open"');
                })
                  // ✅ Case 2: On Route → previous Vehicle Required → Open before that
                  ->orWhere(function ($q2) {
                    $q2->where('status', 'On Route')
                      ->whereRaw('(SELECT status FROM export_job_status_logs AS prev
                                            WHERE prev.export_job_id = export_job_status_logs.export_job_id
                                            AND prev.id < export_job_status_logs.id
                                            ORDER BY prev.id DESC LIMIT 1) = "Vehicle Required"')
                      ->whereRaw('(SELECT status FROM export_job_status_logs AS prev2
                                            WHERE prev2.export_job_id = export_job_status_logs.export_job_id
                                            AND prev2.id < (
                                                SELECT prev.id
                                                FROM export_job_status_logs AS prev
                                                WHERE prev.export_job_id = export_job_status_logs.export_job_id
                                                AND prev.id < export_job_status_logs.id
                                                ORDER BY prev.id DESC LIMIT 1
                                            )
                                            ORDER BY prev2.id DESC LIMIT 1) = "Open"');
                  })
                  // ✅ Case 3a: Vehicle Required → previous Container Returned
                  ->orWhere(function ($q2) {
                    $q2->where('status', 'Vehicle Required')
                      ->whereRaw('(SELECT status FROM export_job_status_logs AS prev
                                            WHERE prev.export_job_id = export_job_status_logs.export_job_id
                                            AND prev.id < export_job_status_logs.id
                                            ORDER BY prev.id DESC LIMIT 1) = "Container Returned"');
                  })
                  // ✅ Case 3b: On Route → previous Vehicle Required → Container Returned before that
                  ->orWhere(function ($q2) {
                    $q2->where('status', 'On Route')
                      ->whereRaw('(SELECT status FROM export_job_status_logs AS prev
                                            WHERE prev.export_job_id = export_job_status_logs.export_job_id
                                            AND prev.id < export_job_status_logs.id
                                            ORDER BY prev.id DESC LIMIT 1) = "Vehicle Required"')
                      ->whereRaw('(SELECT status FROM export_job_status_logs AS prev2
                                            WHERE prev2.export_job_id = export_job_status_logs.export_job_id
                                            AND prev2.id < (
                                                SELECT prev.id
                                                FROM export_job_status_logs AS prev
                                                WHERE prev.export_job_id = export_job_status_logs.export_job_id
                                                AND prev.id < export_job_status_logs.id
                                                ORDER BY prev.id DESC LIMIT 1
                                            )
                                            ORDER BY prev2.id DESC LIMIT 1) = "Container Returned"');
                  });
              });
          })
          ->orderBy('created_at', 'desc')
          ->get();

        // ✅ Efficiently tag jobs that EVER had "Container Returned"
        $jobIds = $jobs->pluck('id');
        $jobsWithContainerReturned = DB::table('export_job_status_logs')
          ->whereIn('export_job_id', $jobIds)
          ->where('status', 'Container Returned')
          ->pluck('export_job_id')
          ->unique()
          ->toArray();

        // ✅ Attach flag to each job in collection
        $jobs->each(function ($job) use ($jobsWithContainerReturned) {
          $job->had_container_returned = in_array($job->id, $jobsWithContainerReturned);
        });

        return $jobs; // ✅ MUST return value for Cache::remember()
      }
    );

    // ✅ Return JSON or view
    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'data' => $jobs
      ]);
    }

    $fleets = Fleet::all();
    $routes = Route::where('status', 'active')->where('load', 'EMPTY')->get();

    return view('content.pages.export.logistics.index', compact('jobs', 'fleets', 'routes'));
  }


  /**
   * Show the form for assigning logistics to a specific job.
   */
  public function show(ExportJob $job)
  {
    $job->load(['forwarder', 'line', 'pod', 'terminal', 'cro', 'logistics.vehicle', 'logistics.route', 'emptyPickupLocation']);

    $fleets = Fleet::all();
    $routes = Route::where('status', 'active')->where('load', 'LOAD')->get();

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
   * Assign logistics to a job.
   */
  public function assign(Request $request, ExportJob $job)
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

    DB::transaction(function () use ($job, $validated) {
      // Always create a new logistics record to implement one-to-many relationship
      $validated['export_job_id'] = $job->id;
      $validated['gate_time_passed'] = now()->setTimezone('Asia/Karachi');

      // Create new logistics entry
      ExportLogistic::create($validated);

      // Update job status to 'On Route' and create status log
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'On Route',
        'remarks' => 'Logistics assigned with gate pass: ' . $validated['gate_pass'],
        'changed_by' => Auth::id()
      ]);

      $job->update(['status' => 'On Route']);
    });

    return response()->json([
      'success' => true,
      'message' => 'Logistics assigned successfully!',
      'data' => $job->load('logistics.vehicle', 'logistics.route')
    ]);
  }

  /**
   * Update logistics information for a job.
   */
  public function update(Request $request, ExportJob $job)
  {
    $logistics = $job->logistics;

    if (!$logistics) {
      return response()->json([
        'success' => false,
        'message' => 'No logistics record found for this job.'
      ], 404);
    }

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

    $logistics->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Logistics updated successfully!',
      'data' => $job->load('logistics.vehicle', 'logistics.route')
    ]);
  }

  /**
   * Mark job as on route.
   */
  public function markOnRoute(Request $request, ExportJob $job)
  {
    if (!$job->logistics) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot mark as on route. Logistics not assigned.'
      ], 422);
    }

    // Make remarks optional
    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($job, $request) {
      // Update job status
      $oldStatus = $job->status;
      $job->update(['status' => 'On Route']);

      // Create job status log entry
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'On Route',
        'changed_by' => Auth::id(),
        'remarks' => $request->input('remarks')
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Job marked as on route successfully!',
      'data' => $job
    ]);
  }

  /**
   * Mark job as vehicle returned.
   */
  public function markVehicleReturned(Request $request, ExportJob $job)
  {
    if (!$job->logistics) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot mark as empty pick. Logistics not assigned.'
      ], 422);
    }

    // Make remarks optional and container required
    $request->validate([
      'container' => 'required|string|max:255',
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($job, $request) {
      // Update job status and container
      $oldStatus = $job->status;
      $job->update([
        'status' => 'Empty Pick',
        'container' => $request->input('container')
      ]);

      // Create job status log entry
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => 'Empty Pick',
        'changed_by' => Auth::id(),
        'remarks' => $request->input('remarks')
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Job marked as empty pick successfully!',
      'data' => $job
    ]);
  }

  /**
   * Mark job as completed.
   */
  public function markCompleted(Request $request, ExportJob $job)
  {
    // Validate the request
    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);
    $job->update(['status' => 'Completed']);

    // Create job status log entry
    ExportJobStatusLog::create([
      'export_job_id' => $job->id,
      'status' => 'Completed',
      'changed_by' => Auth::id(),
      'remarks' => $request->input('remarks') ?: 'Job marked as completed'
    ]);
    return response()->json([
      'success' => true,
      'message' => 'Job marked as completed successfully!',
      'data' => $job->fresh(['cro', 'forwarder', 'line', 'pod', 'terminal', 'logistics.vehicle', 'logistics.route'])
    ]);
  }

  /**
   * Get available vehicles for assignment.
   */
  public function getAvailableVehicles(Request $request)
  {
    $vehicles = Fleet::where('status', 'active')
      ->whereDoesntHave('exportLogistics', function ($query) {
        $query->whereHas('exportJob', function ($q) {
          $q->whereIn('status', ['In Progress', 'On Route']);
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
   * Get logistics statistics.
   */
  public function getStatistics()
  {
    $stats = [
      'total_jobs' => ExportJob::count(),
      'pending_logistics' => ExportJob::where('status', 'Open')->count(),
      'in_progress' => ExportJob::where('status', 'In Progress')->count(),
      'on_route' => ExportJob::where('status', 'On Route')->count(),
      'vehicle_returned' => ExportJob::where('status', 'Vehicle Returned')->count(),
      'completed' => ExportJob::where('status', 'Completed')->count(),
      'active_vehicles' => Fleet::where('status', 'active')
        ->whereHas('exportLogistics', function ($query) {
          $query->whereHas('exportJob', function ($q) {
            $q->whereIn('status', ['In Progress', 'On Route']);
          });
        })->count(),
    ];

    return response()->json([
      'success' => true,
      'data' => $stats
    ]);
  }

  /**
   * Get extra routes for a specific job.
   */
  public function getExtraRoutes(ExportJob $job)
  {
    try {
      // Fetch the related ExportLogistic entry
      $logistic = $job->logistics()->first(); // Ensure we get a single instance

      if (!$logistic) {
        return response()->json(['success' => true, 'routes' => []]);
      }

      // Load the extra routes relationship
      $logistic->load(['extraRoutes.route']);

      return response()->json([
        'success' => true,
        'routes' => $logistic->extraRoutes
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch extra routes.',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function addExtraRoute(Request $request, ExportJob $job)
  {
    $request->validate([
      'extra_route_id' => 'required|exists:routes,id',
      'extra_route_reason' => 'required|string|max:500',
    ]);

    DB::transaction(function () use ($request, $job) {
      // Fetch the related ExportLogistic entry
      $logistic = $job->logistics()->first(); // Ensure we get a single instance

      if (!$logistic) {
        throw new \Exception('No logistics entry found for this job.');
      }

      // Attach the extra route to the logistics
      $logistic->extraRoutes()->create([
        'route_id' => $request->input('extra_route_id'),
        'reason' => $request->input('extra_route_reason'),
        'added_by' => Auth::id(),
        'added_at' => now(),
        'reference_id' => $logistic->id,
        'reference_type' => 'export_logistics',
        'job_id' => $job->id,
        'vehicle_id' => $logistic->vehicle_id,
        'assigned_by' => Auth::id(),
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Extra route added successfully!',
      'data' => $job->load('logistics.extraRoutes.route'),
    ]);
  }
}
