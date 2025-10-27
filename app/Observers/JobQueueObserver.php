<?php

namespace App\Observers;

use App\Models\JobQueue;
use Illuminate\Support\Facades\Cache;


class JobQueueObserver
{
  /**
   * Handle the JobQueue "created" event.
   */
  public function created(JobQueue $jobQueue): void
  {
    //
    Cache::forget('cached_jobs');
    Cache::forget('cached_logistics_jobs');
    Cache::forget('cached_empty_returns');
  }

  /**
   * Handle the JobQueue "updated" event.
   */
  public function updated(JobQueue $jobQueue): void
  {
    //
    Cache::forget('cached_jobs');
    Cache::forget('cached_logistics_jobs');
    Cache::forget('cached_empty_returns');
  }

  /**
   * Handle the JobQueue "deleted" event.
   */
  public function deleted(JobQueue $jobQueue): void
  {
    //
    Cache::forget('cached_jobs');
    Cache::forget('cached_logistics_jobs');
    Cache::forget('cached_empty_returns');
  }

  /**
   * Handle the JobQueue "restored" event.
   */
  public function restored(JobQueue $jobQueue): void
  {
    //
  }

  /**
   * Handle the JobQueue "force deleted" event.
   */
  public function forceDeleted(JobQueue $jobQueue): void
  {
    //
  }
}
