<?php

namespace App\Http\Controllers\pages;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Fleet;
use App\Models\Route;
use App\Models\JobQueue;
use App\Models\ExportJob;
use App\Models\ExtraRoute;
use App\Models\FuelPayment;
use App\Models\WorkshopJob;
use App\Models\JobLogistics;
use App\Models\LoadedReturn;
use Illuminate\Http\Request;
use App\Models\ExportLogistic;
use App\Models\FuelManagement;
use App\Models\JobEmptyReturn;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HomePage extends Controller
{
  public function index()
  {
    // Fuel Data
    $totalFuelPurchased = FuelManagement::sum('qty');
    $lastFuelPurchase = FuelManagement::latest()->first()?->created_at?->format('d M Y');
    $totalFuelPaid = 0;

    // Calculate the actual used fuel from all payments
    $allPayments = FuelPayment::where('payment_status', 'paid')->get();

    foreach ($allPayments as $payment) {
      $fuelAmount = app('App\Http\Controllers\FuelManagerController')
        ->getFuelAmountForReference($payment->reference_id, $payment->reference_type);
      $totalFuelPaid += $fuelAmount;
    }

    // Import Jobs
    $totalImportJobs = JobQueue::count();
    $totalImportJobsCompleted = JobQueue::where('status', 'Completed')->count();

    // Export Jobs
    $totalExportJobs = ExportJob::count();
    $totalExportJobsCompleted = ExportJob::where('status', 'Completed')->count();

    // Workshop Jobs
    $totalWorkshopJobs = WorkshopJob::count();
    $paidWorkshopJobs = WorkshopJob::where('status', 'paid')->count();

    // Fleet Payments
    $totalFleetPayments = '100,000';
    $lastFleetPayment = FuelPayment::latest()->first()?->created_at?->format('d M Y');

    // Fleet Data
    $totalFleets = Fleet::count();
    $totalActiveVehicles = Fleet::count(); // Assuming 'status' column

    // User Data
    $totalUsers = User::count();
    $onlineUsersToday = DB::table('sessions')->where('last_activity', '>=', Carbon::now()->subMinutes(15))->distinct('user_id')->count('user_id');


    return view('content.pages.pages-home', compact(
      'totalFuelPurchased',
      'lastFuelPurchase',
      'totalFuelPaid',
      'totalImportJobs',
      'totalImportJobsCompleted',
      'totalExportJobs',
      'totalExportJobsCompleted',
      'totalWorkshopJobs',
      'paidWorkshopJobs',
      'totalFleetPayments',
      'lastFleetPayment',
      'totalFleets',
      'totalActiveVehicles',
      'totalUsers',
      'onlineUsersToday',
    ));
  }
}
