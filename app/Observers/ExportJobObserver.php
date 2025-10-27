<?php

namespace App\Observers;

use App\Models\ExportJob;
use Illuminate\Support\Facades\Cache;

class ExportJobObserver
{
  /**
   * Handle the ExportJob "created" event.
   */
  public function created(ExportJob $exportJob): void
  {
    //
    Cache::forget('cached_export_jobs');
    Cache::forget('cached_export_logistics_jobs');
  }

  /**
   * Handle the ExportJob "updated" event.
   */
  public function updated(ExportJob $exportJob): void
  {
    //
    Cache::forget('cached_export_jobs');
    Cache::forget('cached_export_logistics_jobs');
  }

  /**
   * Handle the ExportJob "deleted" event.
   */
  public function deleted(ExportJob $exportJob): void
  {
    //
    Cache::forget('cached_export_jobs');
    Cache::forget('cached_export_logistics_jobs');
  }

  /**
   * Handle the ExportJob "restored" event.
   */
  public function restored(ExportJob $exportJob): void
  {
    //
  }

  /**
   * Handle the ExportJob "force deleted" event.
   */
  public function forceDeleted(ExportJob $exportJob): void
  {
    //
  }
}
