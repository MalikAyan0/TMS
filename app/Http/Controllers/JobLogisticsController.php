<?php

namespace App\Http\Controllers;

use App\Models\JobQueue;
use App\Models\JobLogistics;
use App\Models\JobStatusLog;
use App\Models\Fleet;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class JobLogisticsController extends Controller
{
  /**
   * Display a listing of jobs for logistics operations.
   */
  public function index(Request $request)
  {
    // ✅ Step 1: Cache jobs list for logistics
    $jobs = Cache::remember('cached_logistics_jobs', 60 * 5, function () {
      return JobQueue::with([
        'line:id,name',
        'port:id,name',
        'bailNumber:id,bail_number',
        'logistics:id,vehicle_id,jobs_queue_id,gate_time_passed,route_id',
        'logistics.vehicle:id,registration_number',
        'logistics.route:id,route_name',
      ])
        ->select('id', 'job_number', 'container', 'status', 'line_id', 'port_id', 'bail_number_id')
        ->whereIn('status', ['Open', 'Vehicle Required', 'On Route', 'Vehicle Returned'])
        ->orderBy('created_at', 'desc')
        ->get();
    });

    // ✅ Step 3: Return JSON early if API call
    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'data' => $jobs,
      ]);
    }

    $fleets = Fleet::all();
    $routes = Route::where('status', 'active')->where('load', 'LOAD')->get();

    // ✅ Step 5: Return view
    return view('content.pages.logistics.index', compact('jobs', 'fleets', 'routes'));
  }

  /**
   * Show the form for assigning logistics to a specific job.
   */
  public function show(JobQueue $job)
  {
    $job->load(['forwarder', 'company', 'line', 'port', 'bailNumber', 'logistics.vehicle', 'logistics.route']);

    $fleets = Fleet::all();
    $routes = Route::where('status', 'active')->where('load', 'LOAD')->get();

    // if (request()->expectsJson()) {
    return response()->json([
      'success' => true,
      'data' => [
        'job' => $job,
        'fleets' => $fleets,
        'routes' => $routes
      ]
    ]);
    // }

    // return view('content.pages.logistics.show', compact('job', 'fleets', 'routes'));
  }

  /**
   * Assign logistics to a job.
   */
  public function assign(Request $request, JobQueue $job)
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
      // Check if logistics record exists
      $logistics = $job->logistics;

      if (!$logistics) {
        // Create new logistics record
        $validated['jobs_queue_id'] = $job->id;
        $validated['gate_time_passed'] = now()->setTimezone('Asia/Karachi');
        JobLogistics::create($validated);
      } else {
        // Update existing logistics record
        if (empty($logistics->gate_time_passed)) {
          $validated['gate_time_passed'] = now()->setTimezone('Asia/Karachi');
        }
        $logistics->update($validated);
      }

      // Update job status to 'On Route' if it's still 'Open'

      $job->update(['status' => 'On Route']);
    });

    // if ($request->expectsJson()) {
    return response()->json([
      'success' => true,
      'message' => 'Logistics assigned successfully!',
      'data' => $job->load('logistics.vehicle', 'logistics.route')
    ]);
    // }

    // return redirect()->back()->with('success', 'Logistics assigned successfully!');
  }

  /**
   * Update logistics information for a job.
   */
  public function update(Request $request, JobQueue $job)
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

    // if ($request->expectsJson()) {
    return response()->json([
      'success' => true,
      'message' => 'Logistics updated successfully!',
      'data' => $job->load('logistics.vehicle', 'logistics.route')
    ]);
    // }

    // return redirect()->back()->with('success', 'Logistics updated successfully!');
  }

  /**
   * Mark job as on route.
   */
  public function markOnRoute(Request $request, JobQueue $job)
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

      // Create job status log entry with all required fields
      JobStatusLog::create([
        'jobs_queue_id' => $job->id,
        'status' => 'On Route',
        'changed_by' => Auth::id(),
        'changed_at' => now(),
        'remarks' => $request->input('remarks')
      ]);
    });

    // if ($request->expectsJson()) {
    return response()->json([
      'success' => true,
      'message' => 'Job marked as on route successfully!',
      'data' => $job
    ]);
    // }

    // return redirect()->back()->with('success', 'Job marked as on route successfully!');
  }

  /**
   * Mark job as vehicle returned.
   */
  public function markVehicleReturned(Request $request, JobQueue $job)
  {
    if (!$job->logistics) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot mark as vehicle returned. Logistics not assigned.'
      ], 422);
    }

    // Make remarks optional
    $request->validate([
      'remarks' => 'nullable|string|max:500'
    ]);

    DB::transaction(function () use ($job, $request) {
      // Update job status
      $oldStatus = $job->status;
      $job->update(['status' => 'Vehicle Returned']);

      // Create job status log entry with all required fields
      JobStatusLog::create([
        'jobs_queue_id' => $job->id,
        'status' => 'Vehicle Returned',
        'changed_by' => Auth::id(),
        'changed_at' => now(),
        'remarks' => $request->input('remarks')
      ]);
    });

    // if ($request->expectsJson()) {
    return response()->json([
      'success' => true,
      'message' => 'Job marked as vehicle returned successfully!',
      'data' => $job
    ]);
    // }

    // return redirect()->back()->with('success', 'Job marked as vehicle returned successfully!');
  }

  /**
   * Get available vehicles for assignment.
   */
  public function getAvailableVehicles(Request $request)
  {
    $vehicles = Fleet::where('status', 'active')
      ->whereDoesntHave('logistics', function ($query) {
        $query->whereHas('jobQueue', function ($q) {
          $q->whereIn('status', ['Vehicle Required', 'On Route']); // Updated condition
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
      'total_jobs' => JobQueue::count(),
      'pending_logistics' => JobQueue::where('status', 'Open')->count(),
      'vehicle_required' => JobQueue::where('status', 'Vehicle Required')->count(), // Added new status
      'on_route' => JobQueue::where('status', 'On Route')->count(),
      'vehicle_returned' => JobQueue::where('status', 'Vehicle Returned')->count(),
      'completed' => JobQueue::where('status', 'Completed')->count(),
      'active_vehicles' => Fleet::where('status', 'active')
        ->whereHas('logistics', function ($query) {
          $query->whereHas('jobQueue', function ($q) {
            $q->whereIn('status', ['Vehicle Required', 'On Route']); // Updated condition
          });
        })->count(),
    ];

    return response()->json([
      'success' => true,
      'data' => $stats
    ]);
  }
}
