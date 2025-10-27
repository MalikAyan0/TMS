<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use App\Models\JobQueue;
use App\Observers\JobQueueObserver;
use App\Models\ExportJob;
use App\Observers\ExportJobObserver;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    // Register UserStatusService
    $this->app->singleton(\App\Services\UserStatusService::class, function ($app) {
      return new \App\Services\UserStatusService();
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    JobQueue::observe(JobQueueObserver::class);
    ExportJob::observe(ExportJobObserver::class);

    Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
      if ($src !== null) {
        return [
          'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
        ];
      }
      return [];
    });
  }
}
