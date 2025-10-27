<?php

namespace App\Http\Controllers;

use App\Models\WorkshopJob;
use App\Models\Fleet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkshopJobController extends Controller
{
  /**
   * Display a listing of workshop jobs.
   */
  public function index()
  {
    $fleets = Fleet::all();
    $jobs = WorkshopJob::with('vehicle')->get();
    if (request()->wantsJson()) {
      return response()->json([
        'jobs' => $jobs,
      ]);
    }
    return view('content.pages.workshop-mannagement.index', compact('fleets'));
  }

  /**
   * Get workshop jobs data for DataTable.
   */
  public function getData()
  {
    $jobs = WorkshopJob::with('vehicle')->get();

    $data = $jobs->map(function ($job) {
      return [
        'id' => $job->id,
        'parts' => $job->parts,
        'invoice' => $job->invoice,
        'vehicle' => $job->vehicle,
        'price' => $job->price,
        'vendor_name' => $job->vendor_name,
        'status' => $job->status,
        'created_at' => $job->created_at->format('Y-m-d'),
      ];
    });

    return response()->json([
      'success' => true,
      'data' => $data,
    ]);
  }

  /**
   * Store a newly created workshop job.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'parts_detail' => 'required|string',
      // 'invoice' => 'required|string',
      'vehicle_id' => 'required|exists:fleets,id',
      'reconciliation' => 'required|numeric|min:0',
      'location' => 'nullable|string',
      'vendor_name' => 'nullable|string',
      'slip_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'description' => 'nullable|string',
      'type' => 'required|in:kict,byweste',
    ]);
    // Generate unique invoice if not provided
    $lastJob = WorkshopJob::latest('id')->first();
    $nextNumber = $lastJob ? $lastJob->id + 1 : 1;
    $invoice = 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    $request->merge(['invoice' => $invoice]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->errors(),
      ], 422);
    }

    $data = $request->all();
    if ($request->hasFile('slip_image')) {
      $data['slip_image'] = $request->file('slip_image')->store('workshop_slips', 'public');
    }

    $job = WorkshopJob::create($data);

    return response()->json([
      'success' => true,
      'message' => 'Workshop job created successfully.',
      'data' => $job,
    ]);
  }

  /**
   * Display the specified workshop job.
   */
  public function show(WorkshopJob $workshopJob)
  {
    return response()->json([
      'success' => true,
      'data' => $workshopJob->load('vehicle'),
    ]);
  }

  /**
   * Update the specified workshop job.
   */
  public function update(Request $request, WorkshopJob $workshopJob)
  {
    $validator = Validator::make($request->all(), [
      'parts_detail' => 'required|string',
      // 'invoice' => 'required|string',
      'vehicle_id' => 'required|exists:fleets,id',
      'reconciliation' => 'required|numeric|min:0',
      'location' => 'nullable|string',
      'vendor_name' => 'nullable|string',
      'slip_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      // 'status' => 'required|in:requested,approved,paid,rejected',
      'description' => 'nullable|string',
      'type' => 'required|in:kict,byweste',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->errors(),
      ], 422);
    }

    $data = $request->all();
    if ($request->hasFile('slip_image')) {
      $data['slip_image'] = $request->file('slip_image')->store('workshop_slips', 'public');
    }

    $workshopJob->update($data);

    return response()->json([
      'success' => true,
      'message' => 'Workshop job updated successfully.',
      'data' => $workshopJob->load('vehicle'),
    ]);
  }

  /**
   * Update the status of the specified workshop job.
   */
  public function updateStatus(Request $request, WorkshopJob $workshopJob)
  {
    $request->validate([
      'status' => 'required|in:requested,approved,paid,rejected',
    ]);

    $workshopJob->update(['status' => $request->status]);

    return response()->json([
      'success' => true,
      'message' => 'Job status updated successfully.',
    ]);
  }

  /**
   * Remove the specified workshop job.
   */
  public function destroy(WorkshopJob $workshopJob)
  {
    $workshopJob->delete();

    return response()->json([
      'success' => true,
      'message' => 'Workshop job deleted successfully.',
    ]);
  }
}
