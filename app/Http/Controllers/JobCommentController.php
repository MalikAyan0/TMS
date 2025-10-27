<?php

namespace App\Http\Controllers;

use App\Models\JobComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobCommentController extends Controller
{
  /**
   * Fetch comments for a specific job and status.
   */
  public function index(Request $request)
  {
    $validated = $request->validate([
      'job_id' => 'required|integer',
      'status' => 'required|string',
      'type' => 'required|in:import,export',
    ]);

    $comments = JobComment::with('user')
      ->where('job_id', $validated['job_id'])
      ->where('status', $validated['status'])
      ->where('type', $validated['type'])
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json($comments);
  }

  /**
   * Store a new comment.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'job_id' => 'required|integer',
      'type' => 'required|in:import,export',
      'status' => 'required|string|max:255',
      'comment' => 'required|string|max:1000',
    ]);

    // Validate that the job exists based on type
    if ($validated['type'] === 'import') {
      $request->validate([
        'job_id' => 'exists:jobs_queue,id',
      ]);
    } else {
      $request->validate([
        'job_id' => 'exists:export_jobs,id',
      ]);
    }

    $comment = JobComment::create([
      'job_id' => $validated['job_id'],
      'type' => $validated['type'],
      'status' => $validated['status'],
      'comment' => $validated['comment'],
      'user_id' => Auth::id(),
    ]);

    // Load the user relationship for the response
    $comment->load('user');

    return response()->json(['success' => true, 'comment' => $comment]);
  }

  /**
   * Get comments for an import job
   */
  public function getImportJobComments($jobId, $status)
  {
    $comments = JobComment::with('user')
      ->where('job_id', $jobId)
      ->where('status', $status)
      ->where('type', 'import')
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json($comments);
  }

  /**
   * Get comments for an export job
   */
  public function getExportJobComments($jobId, $status)
  {
    $comments = JobComment::with('user')
      ->where('job_id', $jobId)
      ->where('status', $status)
      ->where('type', 'export')
      ->orderBy('created_at', 'desc')
      ->get();

    return response()->json($comments);
  }
}
