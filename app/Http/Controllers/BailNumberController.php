<?php

namespace App\Http\Controllers;

use App\Models\BailNumber;
use App\Models\Company;
use App\Models\Fleet;
use App\Models\Line;
use App\Models\Party;
use App\Models\Port;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BailNumberController extends Controller
{
  /**
   * Display a listing of bail numbers.
   */
  public function index(Request $request)
  {
    $query = BailNumber::query();


    if ($request->has('search') && $request->search) {
      $searchTerm = $request->search;
      $query->where(function ($q) use ($searchTerm) {
        $q->where('bail_number', 'like', "%{$searchTerm}%")
          ->orWhere('description', 'like', "%{$searchTerm}%");
      });
    }

    if ($request->has('status') && $request->status) {
      $query->where('status', $request->status);
    }

    $bailNumbers = $query->orderBy('created_at', 'desc')->get();

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'data' => $bailNumbers
      ]);
    }

    return view('content.pages.bails.index', compact('bailNumbers'));
  }

  /**
   * Store a newly created bail number.
   */
  public function store(Request $request)
  {
    try {
      $validated = $request->validate([
        'bail_number' => 'required|string|max:255|unique:bail,bail_number', // Correct table name
        'description' => 'nullable|string|max:1000',
        'status' => 'required|in:active,inactive',
      ]);

      // Create the bail number
      $bailNumber = BailNumber::create($validated);

      return response()->json([
        'success' => true,
        'message' => 'BL number created successfully!',
        'data' => $bailNumber
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors' => $e->errors(),
      ], 422);
    } catch (\Exception $e) {
      // Log the exception for debugging
      Log::error('Error creating bail number: ' . $e->getMessage(), [
        'trace' => $e->getTraceAsString()
      ]);

      // Return a more detailed error message in development
      if (config('app.debug')) {
        return response()->json([
          'success' => false,
          'message' => 'An unexpected error occurred: ' . $e->getMessage(),
        ], 500);
      }

      // Generic error message for production
      return response()->json([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again.',
      ], 500);
    }
  }

  /**
   * Display the specified bail number with associated jobs.
   */
  public function show(BailNumber $bailNumber)
  {
    // Load jobs with necessary relationships
    $jobs = $bailNumber->jobQueues()
      ->with(['company', 'line', 'port', 'forwarder'])
      ->orderBy('created_at', 'desc')
      ->get();

    // Calculate job statistics
    $jobsCount = $jobs->count();
    $activeJobsCount = $jobs->whereNotIn('status', ['Completed', 'Cancelled'])->count();
    $forwarders = Party::where('status', 'active')->where('party_type', 'customer')->get();
    $companies = Company::where('status', 'active')->get();
    $ports = Port::where('status', 'active')->get();
    $lines = Line::where('status', 'active')->get();
    $fleets = Fleet::all();
    $bailNumbers = BailNumber::where('status', 'active')->get();
    $routes = Route::where('status', 'active')->get();

    // If it's an AJAX request, return JSON
    if (request()->expectsJson()) {
      return response()->json([
        'success' => true,
        'data' => [
          'bail_number' => $bailNumber,
          'jobs' => $jobs,
          'jobs_count' => $jobsCount,
          'active_jobs_count' => $activeJobsCount
        ]
      ]);
    }

    // Return view with data
    return view('content.pages.bails.view', compact(
      'bailNumber',
      'jobs',
      'jobsCount',
      'activeJobsCount',
      'forwarders',
      'companies',
      'ports',
      'lines',
      'fleets',
      'bailNumbers',
      'routes'
    ));
  }

  /**
   * Get jobs data for the specified bail number.
   */
  public function getJobsData(BailNumber $bailNumber)
  {
    $jobs = $bailNumber->jobQueues()
      ->with([
        'company:id,title',
        'line:id,name',
        'port:id,name',
        'forwarder:id,title',
        'bailNumber:id,bail_number',
        'logistics:id,vehicle_id,jobs_queue_id,gate_time_passed',
        'logistics.vehicle:id,registration_number'
      ])
      ->select([
        'id',
        'job_number',
        'container',
        'status',
        'company_id',
        'line_id',
        'port_id',
        'forwarder_id',
        'bail_number_id'
      ])
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $jobs
    ]);
  }

  /**
   * Show the form for editing the specified bail number.
   */
  public function edit(BailNumber $bailNumber)
  {
    return response()->json([
      'success' => true,
      'data' => $bailNumber
    ]);
  }

  /**
   * Update the specified bail number.
   */
  public function update(Request $request, BailNumber $bailNumber)
  {
    $validated = $request->validate([
      'bail_number' => 'required|string|max:255|unique:bail,bail_number,' . $bailNumber->id,
      'description' => 'nullable|string',
      'status' => 'required|in:active,inactive',
    ]);

    $bailNumber->update($validated);

    // if ($request->expectsJson()) {
    return response()->json([
      'success' => true,
      'message' => 'Bail number updated successfully!',
      'data' => $bailNumber
    ]);
    // }

    // return redirect()->back()->with('success', 'Bail number updated successfully!');
  }

  /**
   * Remove the specified bail number.
   */
  public function destroy(Request $request, BailNumber $bailNumber)
  {
    // Check if bail number has associated jobs
    if ($bailNumber->jobQueues()->count() > 0) {
      // if ($request->expectsJson()) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot delete bail number that has associated jobs.'
      ], 422);
      // }

      // return redirect()->back()->with('error', 'Cannot delete bail number that has associated jobs.');
    }

    $bailNumber->delete();

    // if ($request->expectsJson()) {
    return response()->json([
      'success' => true,
      'message' => 'Bail number deleted successfully!'
    ]);
    // }

    // return redirect()->back()->with('success', 'Bail number deleted successfully!');
  }

  /**
   * Get active bail numbers for dropdowns.
   */
  public function getActiveBailNumbers()
  {
    $bailNumbers = BailNumber::active()
      ->select('id', 'bail_number', 'description')
      ->orderBy('bail_number')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $bailNumbers
    ]);
  }

  /**
   * Toggle status of bail number.
   */
  public function toggleStatus(Request $request, BailNumber $bailNumber)
  {
    $newStatus = $bailNumber->status === 'active' ? 'inactive' : 'active';
    $bailNumber->update(['status' => $newStatus]);

    // if ($request->expectsJson()) {
    return response()->json([
      'success' => true,
      'message' => "Bail number status changed to {$newStatus}.",
      'data' => $bailNumber
    ]);
    // }

    // return redirect()->back()->with('success', "Bail number status changed to {$newStatus}.");
  }

  /**
   * Bulk operations for bail numbers.
   */
  public function bulkAction(Request $request)
  {
    $validated = $request->validate([
      'action' => 'required|in:activate,deactivate,delete',
      'ids' => 'required|array|min:1',
      'ids.*' => 'exists:bail,id'
    ]);

    $bailNumbers = BailNumber::whereIn('id', $validated['ids']);

    switch ($validated['action']) {
      case 'activate':
        $bailNumbers->update(['status' => 'active']);
        $message = 'Selected bail numbers activated successfully.';
        break;

      case 'deactivate':
        $bailNumbers->update(['status' => 'inactive']);
        $message = 'Selected bail numbers deactivated successfully.';
        break;

      case 'delete':
        // Check if any bail numbers have associated jobs
        $hasJobs = $bailNumbers->whereHas('jobQueues')->count();
        if ($hasJobs > 0) {
          return response()->json([
            'success' => false,
            'message' => 'Cannot delete bail numbers that have associated jobs.'
          ], 422);
        }

        $bailNumbers->delete();
        $message = 'Selected bail numbers deleted successfully.';
        break;
    }

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => $message
      ]);
    }

    return redirect()->back()->with('success', $message);
  }
}
