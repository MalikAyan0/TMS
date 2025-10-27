<?php

namespace App\Http\Controllers;

use App\Models\JobEmptyReturn;
use App\Models\JobQueue;
use App\Models\JobStatusLog;
use App\Models\Fleet;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class JobEmptyReturnController extends Controller
{
  /**
   * Display a listing of empty returns.
   */
  public function index(Request $request)
  {
    // ✅ Step 1: Cache results to reduce DB load
    $emptyReturns = Cache::remember(
      'cached_empty_returns',
      60 * 5, // cache for 5 minutes
      function () {
        return JobQueue::with([
          'line:id,name',
          'port:id,name',
          'bailNumber:id,bail_number',
        ])
          ->select([
            'id',
            'job_number',
            'container',
            'status',
            'line_id',
            'port_id',
            'bail_number_id',
            'eta',
          ])
          ->whereIn('status', ['Vehicle Returned', 'Empty Return', 'Completed'])
          ->latest()
          ->get();
      }
    );

    // ✅ Step 2: Optional formatting/transformation (e.g., date formatting)
    $emptyReturns->transform(function ($job) {
      $job->eta = $job->eta ? date('m/d/Y', strtotime($job->eta)) : null;
      return $job;
    });

    // ✅ Step 3: Return JSON early for API/AJAX
    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'data' => $emptyReturns
      ]);
    }

    $fleets = Fleet::all();
    $routes = Route::where('status', 'active')->where('load', 'EMPTY')->get();

    // ✅ Step 5: Return optimized view
    return view('content.pages.empty-returns.index', compact('emptyReturns', 'fleets', 'routes'));
  }


  /**
   * Show the form for creating a new empty return.
   */
  public function create(JobQueue $job)
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
   * Store a newly created empty return.
   */
  public function store(Request $request, JobQueue $job)
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
      // Create empty return record
      $validated['jobs_queue_id'] = $job->id;
      $validated['gate_time_passed'] = now();
      JobEmptyReturn::create($validated);

      // Update job status to 'Empty Return'
      $oldStatus = $job->status;
      $job->update(['status' => 'Empty Return']);

      // Create job status log entry
      JobStatusLog::create([
        'jobs_queue_id' => $job->id,
        'status' => 'Empty Return',
        'changed_by' => Auth::id(),
        'changed_at' => now(),
        'remarks' => 'Empty return assigned'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Empty return assigned successfully!',
      'data' => $job->load('emptyReturn.vehicle', 'emptyReturn.route')
    ]);
  }

  /**
   * Display the specified empty return.
   */
  public function show(JobQueue $job)
  {
    $job->load(['emptyReturn.vehicle', 'emptyReturn.route']);

    return response()->json([
      'success' => true,
      'data' => $job
    ]);
  }

  /**
   * Show the form for editing the specified empty return.
   */
  public function edit(JobEmptyReturn $emptyReturn)
  {
    $emptyReturn->load(['jobQueue', 'vehicle', 'route']);
    $fleets = Fleet::where('status', 'active')->get();
    $routes = Route::where('status', 'active')->where('load', 'EMPTY')->get();

    return response()->json([
      'success' => true,
      'data' => [
        'emptyReturn' => $emptyReturn,
        'fleets' => $fleets,
        'routes' => $routes
      ]
    ]);
  }

  /**
   * Update the specified empty return.
   */
  public function update(Request $request, JobEmptyReturn $emptyReturn)
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

    $emptyReturn->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Empty return updated successfully!',
      'data' => $emptyReturn->load('jobQueue', 'vehicle', 'route')
    ]);
  }

  /**
   * Remove the specified empty return.
   */
  public function destroy(JobEmptyReturn $emptyReturn)
  {
    DB::transaction(function () use ($emptyReturn) {
      $job = $emptyReturn->jobQueue;

      // Delete the empty return record
      $emptyReturn->delete();

      // Update job status back to 'Vehicle Returned'
      $oldStatus = $job->status;
      $job->update(['status' => 'Vehicle Returned']);

      // Create job status log entry
      JobStatusLog::create([
        'jobs_queue_id' => $job->id,
        'old_status' => $oldStatus,
        'new_status' => 'Vehicle Returned',
        'changed_by' => Auth::id(),
        'changed_at' => now(),
        'remarks' => 'Empty return removed'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Empty return removed successfully!'
    ]);
  }

  /**
   * Mark empty return as completed.
   */
  public function markCompleted(Request $request, JobQueue $job)
  {
    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($job, $request) {
      // Update job status to 'Completed'
      $oldStatus = $job->status;
      $job->update(['status' => 'Completed']);

      // Create job status log entry
      JobStatusLog::create([
        'jobs_queue_id' => $job->id,
        'old_status' => $oldStatus,
        'new_status' => 'Completed',
        'changed_by' => Auth::id(),
        'changed_at' => now(),
        'remarks' => $request->input('remarks') ?: 'Job marked as completed'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Job marked as completed successfully!',
      'data' => $job
    ]);
  }

  /**
   * Mark empty return as completed by empty return ID.
   */
  public function markCompletedByReturn(Request $request, JobEmptyReturn $emptyReturn)
  {
    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($emptyReturn, $request) {
      $job = $emptyReturn->jobQueue;

      // Update job status to 'Completed'
      $oldStatus = $job->status;
      $job->update(['status' => 'Completed']);

      // Create job status log entry
      JobStatusLog::create([
        'jobs_queue_id' => $job->id,
        'old_status' => $oldStatus,
        'new_status' => 'Completed',
        'changed_by' => Auth::id(),
        'changed_at' => now(),
        'remarks' => $request->input('remarks') ?: 'Empty return completed'
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Empty return marked as completed successfully!',
      'data' => $emptyReturn->jobQueue
    ]);
  }

  /**
   * Get available vehicles for empty return assignment.
   */
  public function getAvailableVehicles(Request $request)
  {
    $vehicles = Fleet::where('status', 'active')
      ->whereDoesntHave('emptyReturns', function ($query) {
        $query->whereHas('jobQueue', function ($q) {
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
   * Get empty return statistics.
   */
  public function getStatistics()
  {
    $stats = [
      'total_empty_returns' => JobEmptyReturn::count(),
      'pending_empty_returns' => JobQueue::where('status', 'Empty Return')->count(),
      'completed_today' => JobQueue::where('status', 'Completed')
        ->whereDate('updated_at', today())
        ->count(),
      'active_vehicles' => Fleet::where('status', 'active')
        ->whereHas('emptyReturns', function ($query) {
          $query->whereHas('jobQueue', function ($q) {
            $q->where('status', 'Empty Return');
          });
        })->count(),
    ];

    return response()->json([
      'success' => true,
      'data' => $stats
    ]);
  }

  /**
   * Add an extra route to an empty return.
   */
  public function addExtraRoute(Request $request, JobQueue $job)
  {
    $request->validate([
      'extra_route_id' => 'required|exists:routes,id',
      'extra_route_reason' => 'required|string|max:500',
    ]);

    DB::transaction(function () use ($request, $job) {
      // Fetch the related JobEmptyReturn entry
      $emptyReturn = $job->emptyReturn;

      if (!$emptyReturn) {
        throw new \Exception('No empty return entry found for this job.');
      }

      // Attach the extra route to the empty return
      $emptyReturn->extraRoutes()->create([
        'route_id' => $request->input('extra_route_id'),
        'reason' => $request->input('extra_route_reason'),
        'added_by' => Auth::id(),
        'added_at' => now(),
        'reference_id' => $emptyReturn->id,
        'reference_type' => 'job_empty_return',
        'job_id' => $job->id,
        'vehicle_id' => $emptyReturn->vehicle_id,
        'assigned_by' => Auth::id(),
      ]);
    });

    return response()->json([
      'success' => true,
      'message' => 'Extra route added successfully!',
      'data' => $job->load('emptyReturn.extraRoutes.route'),
    ]);
  }

  /**
   * Get extra routes for a specific job.
   */
  public function getExtraRoutes(JobQueue $job)
  {
    try {
      // Load extra routes with their associated route details
      $job->load(['emptyReturn.extraRoutes.route']);

      // Check if extra routes exist
      if (!$job->emptyReturn || !$job->emptyReturn->extraRoutes) {
        return response()->json(['success' => true, 'routes' => []]);
      }

      // Return extra routes
      return response()->json([
        'success' => true,
        'routes' => $job->emptyReturn->extraRoutes
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch extra routes.',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
