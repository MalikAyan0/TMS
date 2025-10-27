<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JobTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $jobTypes = JobType::orderBy('short_name', 'asc')->get();
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $jobTypes
                ]);
            }
            return view('content.pages.system-management.jobs-types', compact('jobTypes'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'short_name' => 'required|string|max:10|unique:job_types,short_name',
                'description' => 'nullable|string|max:1000',
            ]);

            $jobType = JobType::create($validated);

            return response()->json([
                'success' => true,
                'data' => $jobType,
                'message' => 'Job type created successfully!'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create job type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JobType $jobType): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $jobType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobType $jobType): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'short_name' => 'required|string|max:10|unique:job_types,short_name,' . $jobType->id,
                'description' => 'nullable|string|max:1000',
            ]);

            $jobType->update($validated);

            return response()->json([
                'success' => true,
                'data' => $jobType->fresh(),
                'message' => 'Job type updated successfully!'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(JobType $jobType): JsonResponse
    {
        try {
            $newStatus = $jobType->status === 'active' ? 'inactive' : 'active';
            $jobType->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'data' => $jobType->fresh(),
                'message' => "Job type {$newStatus} successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle job type status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobType $jobType): JsonResponse
    {
        try {
            $jobType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job type deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job type',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
