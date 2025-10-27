<?php

namespace App\Http\Controllers;

use App\Models\ExportJob;
use App\Models\Cro;
use App\Models\Line;
use App\Models\Party;
use App\Models\Pod;
use App\Models\Terminal;
use App\Models\Location;
use App\Enums\ExportJobStatus;
use Illuminate\Http\Request;
use App\Models\ExportJobStatusLog; // Add this at the top with other imports
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ExportJobController extends Controller
{
  /**
   * Display a listing of the export jobs.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    // ✅ Step 1: Cache export jobs for performance
    $exportJobs = Cache::remember(
      'cached_export_jobs',
      60 * 5,
      function () {
        return ExportJob::with([
          'cro:id,cro_number',
          'line:id,name',
        ])
          ->select([
            'id',
            'cro_id',
            'size',
            'line_id',
            'status',
            'created_at',
          ])
          ->latest()
          ->get();
      }
    );

    // ✅ Step 2: Optional data transformation (if needed)
    $exportJobs->transform(function ($job) {
      $job->created_date = $job->created_at ? date('m/d/Y', strtotime($job->created_at)) : null;
      return $job;
    });

    // ✅ Step 3: Return JSON if request is AJAX/API
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'data' => $exportJobs
      ]);
    }

    $lines = Line::all();
    $forwarders = Party::all();
    $pods = Pod::active()->get();
    $terminals = Terminal::active()->get();
    $cros = Cro::latest()->get();
    $emptypickupLocations = Location::all();

    return view('content.pages.export.jobs.index', compact(
      'exportJobs',
      'lines',
      'forwarders',
      'pods',
      'terminals',
      'cros',
      'emptypickupLocations'
    ));
  }

  /**
   * Store a newly created export job in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'cro_id' => 'required|exists:cros,id',
      'containers.*.container' => 'nullable|string',
      'containers.*.size' => 'required|integer',
      'containers.*.line_id' => 'required|exists:lines,id',
      'containers.*.forwarder_id' => 'required|exists:parties,id',
      'containers.*.pod_id' => 'required|exists:pods,id',
      'containers.*.terminal_id' => 'required|exists:terminals,id',
      'containers.*.empty_pickup' => 'nullable|exists:locations,id',
      'containers.*.status' => 'required|in:' . implode(',', array_map(fn($case) => $case->value, ExportJobStatus::cases())),
      'containers.*.job_type' => 'required|in:Empty,Loaded', // Added job_type validation
    ]);

    $jobs = [];
    foreach ($validated['containers'] as $containerData) {
      // Add the CRO ID to each container's data
      $containerData['cro_id'] = $validated['cro_id'];

      // Generate a unique CRO number for each job
      $containerData['cro_number'] = 'CRO-' . date('Ymd') . '-' . rand(1000, 9999);

      // Create the job
      $job = ExportJob::create($containerData);
      $jobs[] = $job;

      // Log the initial status
      ExportJobStatusLog::create([
        'export_job_id' => $job->id,
        'status' => $containerData['status'],
        'remarks' => 'Initial job creation',
        'changed_by' => Auth::id(),
      ]);
    }

    // If AJAX/API request, return JSON
    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Export jobs created successfully!',
        'data' => $jobs
      ], 201);
    }

    return redirect()->route('export-jobs.index')->with('success', 'Export jobs created successfully.');
  }

  /**
   * Display the specified export job.
   *
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function show(ExportJob $exportJob)
  {
    $exportJob->load(['cro',  'line', 'forwarder',  'pod', 'terminal']);

    return view('content.pages.export.jobs.view', compact('exportJob'));
  }

  /**
   * Update the specified export job in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, ExportJob $exportJob)
  {
    $validated = $request->validate([
      'cro_id' => 'required|exists:cros,id',
      'container' => 'nullable|string',
      'size' => 'required|integer',
      'line_id' => 'required|exists:lines,id',
      'forwarder_id' => 'required|exists:parties,id',
      'pod_id' => 'required|exists:pods,id',
      'terminal_id' => 'required|exists:terminals,id',
      'empty_pickup' => 'nullable|exists:locations,id',
      'status' => 'required|in:' . implode(',', array_map(fn($case) => $case->value, ExportJobStatus::cases())),
      'job_type' => 'required|in:Empty,Loaded', // Added job_type validation
    ]);

    // Check if the status has changed
    $previousStatus = $exportJob->status;

    $exportJob->update($validated);

    // Log the status change if it has changed
    if ($previousStatus !== $validated['status']) {
      ExportJobStatusLog::create([
        'export_job_id' => $exportJob->id,
        'status' => $validated['status'],
        'remarks' => 'Status updated via job update',
        'changed_by' => Auth::id(),
      ]);
    }

    if ($request->ajax() || $request->wantsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Export job updated successfully.',
        'data' => $exportJob->fresh(['cro', 'line', 'forwarder', 'pod', 'terminal'])
      ]);
    }

    return redirect()->route('export-jobs.index')->with('success', 'Export job updated successfully.');
  }

  /**
   * Remove the specified export job from storage.
   *
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function destroy(ExportJob $exportJob)
  {
    $exportJob->delete();

    return response()->json([
      'success' => true,
      'message' => 'Export job deleted successfully.'
    ]);
  }

  /**
   * Update the status of an export job.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function updateStatus(Request $request, ExportJob $exportJob)
  {
    $validated = $request->validate([
      'status' => 'required|in:' . implode(',', array_map(fn($case) => $case->value, ExportJobStatus::cases())),
    ]);

    $exportJob->update([
      'status' => $validated['status']
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Export job status updated successfully.',
      'data' => $exportJob->fresh()
    ]);
  }

  /**
   * Show the form for editing the specified export job.
   *
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function edit(ExportJob $exportJob)
  {
    if (empty($exportJob)) {
      return response()->json([
        'success' => false,
        'message' => 'Export job not found.'
      ], 404);
    }

    $exportJob->load(['cro', 'line', 'forwarder', 'pod', 'terminal', 'emptyPickupLocation']);

    return response()->json([
      'success' => true,
      'data' => [
        'id' => $exportJob->id,
        'cro_id' => $exportJob->cro_id,
        'container' => $exportJob->container,
        'size' => $exportJob->size,
        'line_id' => $exportJob->line_id,
        'forwarder_id' => $exportJob->forwarder_id,
        'pod_id' => $exportJob->pod_id,
        'terminal_id' => $exportJob->terminal_id,
        'empty_pickup' => $exportJob->empty_pickup,
        'status' => $exportJob->status,
      ]
    ]);
  }

  /**
   * Convert the status of an export job to "Vehicle Required".
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function convertToVehicleRequired(Request $request, ExportJob $exportJob)
  {
    $validated = $request->validate([
      'remarks' => 'nullable|string',
    ]);

    // Check if the current status is already "Vehicle Required"
    if ($exportJob->status === ExportJobStatus::VehicleRequired) {
      return response()->json([
        'success' => false,
        'message' => 'The export job is already in "Vehicle Required" status.',
      ], 400);
    }

    // Check for invalid status transitions
    if (!in_array($exportJob->status, [ExportJobStatus::Open, ExportJobStatus::ReadyToMove, ExportJobStatus::ContainerReturned, ExportJobStatus::OnRoute])) {
      return response()->json([
        'success' => false,
        'message' => 'The status cannot be changed to "Vehicle Required" from the current status.',
      ], 400);
    }

    // Update the status of the export job
    $exportJob->update([
      'status' => ExportJobStatus::VehicleRequired,
    ]);

    // Log the status change
    ExportJobStatusLog::create([
      'export_job_id' => $exportJob->id,
      'status' => ExportJobStatus::VehicleRequired,
      'remarks' => $validated['remarks'] ?? null,
      'changed_by' => Auth::id(),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Export job status updated to "Vehicle Required".',
      'data' => $exportJob->fresh(),
    ]);
  }

  /**
   * Update the status of an export job to "Ready to Move."
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function readyToMoveStatus(Request $request, ExportJob $exportJob)
  {
    $validated = $request->validate([
      'remarks' => 'nullable|string',
    ]);

    // Check if the current status is already "Ready to Move"
    if ($exportJob->status === ExportJobStatus::ReadyToMove) {
      return response()->json([
        'success' => false,
        'message' => 'The export job is already in "Ready to Move" status.',
      ], 400);
    }

    // Check for invalid status transitions
    if (!in_array($exportJob->status, [ExportJobStatus::Open, ExportJobStatus::VehicleRequired, ExportJobStatus::EmptyPick])) {
      return response()->json([
        'success' => false,
        'message' => 'The status cannot be changed to "Ready to Move" from the current status.',
      ], 400);
    }

    // Update the status of the export job
    $exportJob->update([
      'status' => ExportJobStatus::ReadyToMove,
    ]);

    // Log the status change
    ExportJobStatusLog::create([
      'export_job_id' => $exportJob->id,
      'status' => ExportJobStatus::ReadyToMove,
      'remarks' => $validated['remarks'] ?? null,
      'changed_by' => Auth::id(),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Export job status updated to "Ready to Move".',
      'data' => $exportJob->fresh(),
    ]);
  }

  /**
   * Update the status of an export job to "Container Returned".
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function containerReturned(Request $request, ExportJob $exportJob)
  {
    $validated = $request->validate([
      'remarks' => 'nullable|string',
    ]);

    // Check if the current status is already "Container Returned"
    if ($exportJob->status === ExportJobStatus::ContainerReturned) {
      return response()->json([
        'success' => false,
        'message' => 'The export job is already in "Container Returned" status.',
      ], 400);
    }

    // Check for invalid status transitions
    if (!in_array($exportJob->status, [ExportJobStatus::ReadyToMove, ExportJobStatus::VehicleRequired, ExportJobStatus::OnRoute])) {
      return response()->json([
        'success' => false,
        'message' => 'The status cannot be changed to "Container Returned" from the current status.',
      ], 400);
    }

    // Update the status of the export job
    $exportJob->update([
      'status' => ExportJobStatus::ContainerReturned,
    ]);

    // Log the status change
    ExportJobStatusLog::create([
      'export_job_id' => $exportJob->id,
      'status' => ExportJobStatus::ContainerReturned,
      'remarks' => $validated['remarks'] ?? null,
      'changed_by' => Auth::id(),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Export job status updated to "Container Returned".',
      'data' => $exportJob->fresh(),
    ]);
  }

  /**
   * Update the status of an export job from "Container Returned" to "Vehicle Required".
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\ExportJob  $exportJob
   * @return \Illuminate\Http\Response
   */
  public function vehicleRequired(Request $request, ExportJob $exportJob)
  {
    $validated = $request->validate([
      'remarks' => 'nullable|string',
    ]);

    // Check if the current status is already "Vehicle Required"
    if ($exportJob->status === ExportJobStatus::VehicleRequired) {
      return response()->json([
        'success' => false,
        'message' => 'The export job is already in "Vehicle Required" status.',
      ], 400);
    }

    // Check for invalid status transitions - only allow from Container Returned
    if ($exportJob->status !== ExportJobStatus::ContainerReturned) {
      return response()->json([
        'success' => false,
        'message' => 'The status can only be changed to "Vehicle Required" from "Container Returned" status.',
      ], 400);
    }

    // Update the status of the export job
    $exportJob->update([
      'status' => ExportJobStatus::VehicleRequired,
    ]);

    // Log the status change
    ExportJobStatusLog::create([
      'export_job_id' => $exportJob->id,
      'status' => ExportJobStatus::VehicleRequired,
      'remarks' => $validated['remarks'] ?? null,
      'changed_by' => Auth::id(),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Export job status updated to "Vehicle Required".',
      'data' => $exportJob->fresh(),
    ]);
  }
}
