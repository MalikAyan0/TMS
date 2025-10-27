<?php

namespace App\Http\Controllers;

use App\Models\JobQueue;
use App\Models\JobLogistics;
use App\Models\BailNumber;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Company;
use App\Models\Port;
use App\Models\Line;
use App\Models\Fleet;
use Illuminate\Support\Facades\Cache;

class JobQueueController extends Controller
{
  /**
   * Display a listing of the job queue.
   */
  public function index(Request $request)
  {
    // ✅ Step 1: Cached jobs with relationships
    $jobs = Cache::remember('cached_jobs', 60 * 5, function () {
      return JobQueue::with([
        'line:id,name',
        'port:id,name',
        'bailNumber:id,bail_number',
        'logistics:id,vehicle_id,jobs_queue_id,gate_time_passed',
        'logistics.vehicle:id,registration_number',
      ])
        ->select(['id', 'bail_number_id', 'job_number', 'container', 'status', 'line_id', 'port_id'])
        ->latest()
        ->get();
    });

    // ✅ Step 3: Return JSON if API call
    if ($request->expectsJson()) {
      return response()->json($jobs);
    }

    // ✅ Step 4: Dropdown data (cache these too if performance matters)
    $forwarders = Party::active()->customers()->select('id', 'title')->get();
    $companies  = Company::active()->select('id', 'title')->get();
    $ports      = Port::active()->select('id', 'name')->get();
    $lines      = Line::active()->select('id', 'name')->get();
    $bailNumbers = BailNumber::active()->select('id', 'bail_number')->get();

    // ✅ Step 5: Return view
    return view('content.pages.jobs.index', compact(
      'jobs',
      'forwarders',
      'companies',
      'ports',
      'lines',
      'bailNumbers'
    ));
  }



  /**
   * Display jobs for a specific bail number.
   */
  public function bailIndex(Request $request, $bailNumberId)
  {
    // Fetch all jobs with this bail number
    $jobs = JobQueue::with(['forwarder', 'company', 'line', 'port', 'bailNumber', 'logistics.vehicle'])
      ->where('bail_number_id', $bailNumberId)
      ->get();
    $jobCount = $jobs->count();

    $forwarders = Party::where('status', 'active')->where('party_type', 'customer')->get();
    $companies = Company::where('status', 'active')->get();
    $ports = Port::where('status', 'active')->get();
    $lines = Line::where('status', 'active')->get();
    $fleets = Fleet::all();

    // If AJAX/API request, return JSON
    if ($request->expectsJson()) {
      return response()->json($jobs);
    }

    // Pass bailId to blade for JS
    return view('content.pages.jobs.bail_jobs', compact('bailNumberId', 'jobs', 'jobCount', 'forwarders', 'companies', 'ports', 'lines', 'fleets'));
  }

  /**
   * Store a newly created job in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'bail_number_id' => 'required|exists:bail,id',
      'containers' => 'required|array|min:1',
      'containers.*.container' => 'required|string|max:255',
      'containers.*.company_id' => 'required|exists:companies,id',
      'containers.*.size' => 'required|integer',
      'containers.*.line_id' => 'required|exists:lines,id',
      'containers.*.forwarder_id' => 'required|exists:parties,id',
      'containers.*.port_id' => 'required|exists:ports,id',
      'containers.*.noc_deadline' => 'nullable|date',
      'containers.*.eta' => 'nullable|date',
      'containers.*.mode' => 'required|in:CFS,CY',
      'containers.*.status' => 'required|in:Open,Vehicle Required,On Route,Stuck On Port,Vehicle Returned,Empty Return,Completed,Cancelled',
    ]);

    $jobs = [];
    foreach ($validated['containers'] as $containerData) {
      // Convert dates to database format
      if (!empty($containerData['noc_deadline'])) {
        $containerData['noc_deadline'] = date('Y-m-d', strtotime($containerData['noc_deadline']));
      }
      if (!empty($containerData['eta'])) {
        $containerData['eta'] = date('Y-m-d', strtotime($containerData['eta']));
      }
      $containerData['bail_number_id'] = $validated['bail_number_id'];
      $containerData['job_number'] = 'JOB-' . date('Ymd') . '-' . rand(1000, 9999);

      $jobs[] = JobQueue::create($containerData);
    }

    // If AJAX/API request, return JSON
    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Jobs created successfully!',
        'jobs' => $jobs
      ], 201);
    }
    return redirect()->back()->with('success', 'Jobs created successfully!');
  }

  public function show(JobQueue $job)
  {
    if (empty($job)) {
      return response()->json([
        'success' => false,
        'message' => 'Job not found.'
      ], 404);
    }

    // Eager load related models
    $job->load(['forwarder', 'company', 'line', 'port', 'bailNumber', 'logistics.vehicle', 'logistics.route', 'jobComments']);

    return view('content.pages.jobs.view', compact('job'));
  }

  /**
   * Show the form for editing the specified job.
   */
  public function edit(JobQueue $job)
  {
    if (empty($job)) {
      return response()->json([
        'success' => false,
        'message' => 'Job not found.'
      ], 404);
    }

    $job->load(['bailNumber', 'logistics']);

    return response()->json([
      'success' => true,
      'data' => [
        'id' => $job->id,
        'job_number' => $job->job_number,
        'bail_number_id' => $job->bail_number_id,
        'container' => $job->container,
        'company_id' => $job->company_id,
        'size' => $job->size,
        'line_id' => $job->line_id,
        'forwarder_id' => $job->forwarder_id,
        'port_id' => $job->port_id,
        'noc_deadline' => $job->noc_deadline ? date('m/d/Y', strtotime($job->noc_deadline)) : null,
        'eta' => $job->eta ? date('m/d/Y', strtotime($job->eta)) : null,
        'mode' => $job->mode,
        'status' => $job->status,
      ]
    ]);
  }

  /**
   * Update the specified job in storage.
   */
  public function update(Request $request, JobQueue $job)
  {
    $validated = $request->validate([
      'bail_number_id' => 'required|exists:bail,id',
      'container' => 'required|string|max:255',
      'company_id' => 'required|exists:companies,id',
      'size' => 'required|integer',
      'line_id' => 'required|exists:lines,id',
      'forwarder_id' => 'required|exists:parties,id',
      'port_id' => 'required|exists:ports,id',
      'noc_deadline' => 'required|date',
      'eta' => 'required|date',
      'status' => 'required|in:Open,Vehicle Required,On Route,Stuck On Port,Vehicle Returned,Empty Return,Completed,Cancelled',
    ]);

    //convert the dates to database format
    if (!empty($validated['noc_deadline'])) {
      $validated['noc_deadline'] = date('Y-m-d', strtotime($validated['noc_deadline']));
    }
    if (!empty($validated['eta'])) {
      $validated['eta'] = date('Y-m-d', strtotime($validated['eta']));
    }

    $job->update($validated);

    // If AJAX/API request, return JSON
    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Job updated successfully test!',
        'job' => $job
      ], 200);
    }

    return redirect()->back()->with('success', 'Job updated successfully!');
  }

  /**
   * Mark a job as approved or unapproved.
   */
  public function toggleApproval(Request $request, JobQueue $jobQueue)
  {
    $validated = $request->validate([
      'approval_status' => 'required|in:Approved,Unapproved',
    ]);

    $jobQueue->update($validated);
    return redirect()->back()->with('success', 'Job approval status updated successfully!');
  }

  /**
   * Remove the specified job from storage.
   */

  public function destroy($id)
  {
    $job = JobQueue::findOrFail($id);
    $job->delete();
    return response()->json(['message' => 'Job deleted successfully.'], 200);
  }
}
