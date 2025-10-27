<?php

use Illuminate\Http\Request;
use Spatie\Permission\Commands\Show;
use App\Http\Controllers\pages\Page2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CroController;
use App\Http\Controllers\PodController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TankController;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\PortController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\OilTypeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FuelTypeController;
use App\Http\Controllers\IssuanceController;
use App\Http\Controllers\JobQueueController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\ExportJobController;
use App\Http\Controllers\FleetTypeController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\RouteStopController;
use App\Http\Controllers\BailNumberController;
use App\Http\Controllers\JobCommentController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\FuelManagerController;
use App\Http\Controllers\VoucherTypeController;
use App\Http\Controllers\WorkshopJobController;
use App\Http\Controllers\JobLogisticsController;
use App\Http\Controllers\LoadedReturnController;
use App\Http\Controllers\ContainerSizeController;
use App\Http\Controllers\ExportLogisticController;
use App\Http\Controllers\FuelManagementController;
use App\Http\Controllers\JobEmptyReturnController;
use App\Http\Controllers\NatureOfAccountController;
use App\Http\Controllers\FleetManufacturerController;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\SalesTaxTerritoryController;

// Main Page Route
Route::get('/', [HomePage::class, 'index'])->name('pages-home')->middleware('auth');
Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2')->middleware('auth');

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// Authentication routes (guest only)
Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (authentication required)
Route::middleware('auth')->group(function () {

  // Main Page Route
  Route::get('/', [HomePage::class, 'index'])->name('pages-home');

  // Logout route
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  //user
  Route::resource('users', UserController::class);

  // Routes
  Route::resource('routes', RouteController::class);
  Route::post('routes/{route}', [RouteController::class, 'update'])->name('routes.update');

  // Bail Number routes
  Route::prefix('bails')->group(function () {
    Route::get('/', [BailNumberController::class, 'index'])->name('bails.index');
    Route::post('/', [BailNumberController::class, 'store'])->name('bails.store');
    Route::get('/active', [BailNumberController::class, 'getActiveBailNumbers'])->name('bails.active');
    Route::get('/{bailNumber}', [BailNumberController::class, 'show'])->name('bails.show');
    Route::get('/{bailNumber}/edit', [BailNumberController::class, 'edit'])->name('bails.edit');
    Route::put('/{bailNumber}', [BailNumberController::class, 'update'])->name('bails.update');
    Route::patch('/{bailNumber}/toggle-status', [BailNumberController::class, 'toggleStatus'])->name('bails.toggle-status');
    Route::delete('/{bailNumber}', [BailNumberController::class, 'destroy'])->name('bails.destroy');
    Route::post('/bulk-action', [BailNumberController::class, 'bulkAction'])->name('bails.bulk-action');
    Route::get('/{bailNumber}/view', [BailNumberController::class, 'show'])->name('bails.view'); // Add this route
    Route::get('/{bailNumber}/jobs-data', [BailNumberController::class, 'getJobsData'])->name('bail-numbers.jobs-data');
  });

  // Route Stops
  Route::resource('route-stops', RouteStopController::class);

  // Fleet
  Route::middleware(['can:fleet.view'])->group(function () {
    Route::resource('fleets', FleetController::class);
  });

  // Fleet Types
  Route::middleware(['can:fleet.type.view'])->group(function () {
    Route::resource('fleet-types', FleetTypeController::class);
  });

  // Fleet Manufacturers
  Route::middleware(['can:fleet.manufacturer.view'])->group(function () {
    Route::resource('fleet-manufacturers', FleetManufacturerController::class);
  });

  // Issuances
  Route::middleware(['can:fuel.issuance.view'])->group(function () {
    Route::resource('issuances', IssuanceController::class);
  });

  // Role Management
  Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/{role}/permissions-json', [RoleController::class, 'permissionsJson'])->name('roles.permissions-json');
    Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
  });


  Route::middleware(['can:import.manage'])->group(function () {
    Route::get('/jobs/{job}', [JobQueueController::class, 'show'])->name('jobs.show');
  });

  // Jobs Page Route
  Route::middleware(['can:import.create'])->group(function () {
    Route::prefix('jobs')->group(function () {
      Route::get('/', [JobQueueController::class, 'index'])->name('jobs.index');
      Route::get('/{job}/edit', [JobQueueController::class, 'edit'])->name('jobs.edit');
      Route::post('/', [JobQueueController::class, 'store'])->name('jobs.store');
      Route::put('/{job}', [JobQueueController::class, 'update'])->name('jobs.update');
      Route::delete('/{job}', [JobQueueController::class, 'destroy'])->name('jobs.destroy');
      Route::post('/{job}/approve', [JobQueueController::class, 'toggleApproval'])->name('jobs.approve');
    });
  });

  // Logistics Routes
  Route::middleware(['can:import.logistics'])->group(function () {
    Route::prefix('logistics')->group(function () {
      Route::get('/', [JobLogisticsController::class, 'index'])->name('jobs.logistics.index');
      Route::get('/{job}', [JobLogisticsController::class, 'show'])->name('jobs.logistics.show');
      Route::post('/edit/{job}', [JobLogisticsController::class, 'update'])->name('jobs.logistics.edit');
      Route::post('/{job}/assign', [JobLogisticsController::class, 'assign'])->name('jobs.logistics.assign');
      Route::patch('/{job}/mark-on-route', [JobLogisticsController::class, 'markOnRoute'])->name('jobs.logistics.mark-on-route');
      Route::patch('/{job}/mark-vehicle-returned', [JobLogisticsController::class, 'markVehicleReturned'])->name('jobs.logistics.mark-vehicle-returned');
    });
  });

  // Empty Return Routes
  Route::prefix('empty-returns')->group(function () {
    Route::get('/', [JobEmptyReturnController::class, 'index'])->name('jobs.empty-returns.index');
    Route::post('/{job}/store', [JobEmptyReturnController::class, 'store'])->name('jobs.empty-returns.store');
    Route::get('/{job}', [JobEmptyReturnController::class, 'show'])->name('jobs.empty-returns.show');
    Route::get('/{job}/edit', [JobEmptyReturnController::class, 'edit'])->name('jobs.empty-returns.edit');
    Route::put('/{job}', [JobEmptyReturnController::class, 'update'])->name('jobs.empty-returns.update');
    Route::patch('/{job}/mark-completed', [JobEmptyReturnController::class, 'markCompleted'])->name('jobs.empty-returns.mark-completed');
    Route::post('/{job}/add-extra-route', [JobEmptyReturnController::class, 'addExtraRoute'])->name('empty-returns.add-extra-route');
    Route::get('/{job}/extra-routes', [JobEmptyReturnController::class, 'getExtraRoutes'])->name('empty-returns.extra-routes');
  });

  // Fuel manager routes
  Route::prefix('fuel-manager')->group(function () {
    Route::middleware(['can:fuelPayment.manage'])->group(function () {
      Route::get('/', [FuelManagerController::class, 'index'])->name('fuel-manager.index');
      Route::post('/data', [FuelManagerController::class, 'getData'])->name('fuel-manager.data');
      Route::post('/export', [FuelManagerController::class, 'export'])->name('fuel-manager.export');
      Route::post('/mark-paid', [FuelManagerController::class, 'markAsPaid'])->name('fuel-manager.mark-paid');
    });
    // New routes for tank management
    Route::get('/get-tanks', [FuelManagerController::class, 'getAvailableTanks'])->name('fuel-manager.get-tanks');
    Route::get('/get-tank-details', [FuelManagerController::class, 'getTankDetails'])->name('fuel-manager.get-tank-details');
    Route::get('/auth-user-tanks', [FuelManagerController::class, 'getAuthUserTanks'])->name('fuel-manager.auth-user-tanks');
    Route::get('print-slip/{id}/{type}', [FuelManagerController::class, 'printSlip'])->name('fuel-manager.print-slip');
  });

  // job invoice routes
  Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
  Route::get('/invoices/{invoice}/view', [InvoiceController::class, 'show'])->name('invoices.show');

  // fuel management routes
  Route::prefix('fuel-management')->group(function () {
    Route::get('/', [FuelManagementController::class, 'index'])->name('fuel-management.index');
    Route::post('/', [FuelManagementController::class, 'store'])->name('fuel-management.store');
    Route::get('/{fuelManagement}', [FuelManagementController::class, 'show'])->name('fuel-management.show');
    Route::POST('/{fuelManagement}', [FuelManagementController::class, 'update'])->name('fuel-management.update');
    Route::patch('/{fuelManagement}/toggle', [FuelManagementController::class, 'toggleStatus'])->name('fuel-management.toggle');
    Route::delete('/{fuelManagement}', [FuelManagementController::class, 'destroy'])->name('fuel-management.destroy');
  });

  // fuel tank routes
  Route::prefix('fuel-tanks')->group(function () {
    Route::get('/', [TankController::class, 'index'])->name('fuel-tanks.index');
    Route::get('/create', [TankController::class, 'create'])->name('fuel-tanks.create');
    Route::post('/', [TankController::class, 'store'])->name('fuel-tanks.store');
    Route::get('/{fuelTank}', [TankController::class, 'show'])->name('fuel-tanks.show');
    Route::get('/{fuelTank}/edit', [TankController::class, 'edit'])->name('fuel-tanks.edit');
    Route::put('/{fuelTank}', [TankController::class, 'update'])->name('fuel-tanks.update');
    Route::patch('/{fuelTank}/toggle', [TankController::class, 'toggleStatus'])->name('fuel-tanks.toggle');
    Route::delete('/{fuelTank}', [TankController::class, 'destroy'])->name('fuel-tanks.destroy');
  });

  // System Management Routes

  //Line routes
  Route::resource('lines', LineController::class);
  Route::post('lines/{line}', [LineController::class, 'update'])->name('lines.update');
  Route::put('lines/{line}/toggle', [LineController::class, 'toggleStatus'])->name('lines.toggle');

  // Port routes
  Route::resource('ports', PortController::class);
  Route::post('ports/{port}', [PortController::class, 'update'])->name('ports.update');
  Route::patch('ports/{port}/toggle', [PortController::class, 'toggleStatus'])->name('ports.toggle');


  // Location routes
  Route::prefix('locations')->group(function () {
    Route::get('/', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/active', [LocationController::class, 'active'])->name('locations.active');
    Route::get('/{location}', [LocationController::class, 'show'])->name('locations.show');
    Route::put('/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::patch('/{location}/toggle', [LocationController::class, 'toggleStatus'])->name('locations.toggle');
    Route::delete('/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
  });

  // Operation routes
  Route::prefix('operations')->group(function () {
    Route::get('/', [OperationController::class, 'index'])->name('operations.index');
    Route::post('/', [OperationController::class, 'store'])->name('operations.store');
    Route::get('/{operation}', [OperationController::class, 'show'])->name('operations.show');
    Route::put('/{operation}', [OperationController::class, 'update'])->name('operations.update');
    Route::patch('/{operation}/toggle', [OperationController::class, 'toggleStatus'])->name('operations.toggle');
    Route::patch('/{operation}/status', [OperationController::class, 'updateStatus'])->name('operations.status');
    Route::delete('/{operation}', [OperationController::class, 'destroy'])->name('operations.destroy');
  });

  // Fuel Type routes
  Route::prefix('fuel-types')->group(function () {
    Route::get('/', [FuelTypeController::class, 'index'])->name('fuel-types.index');
    Route::post('/', [FuelTypeController::class, 'store'])->name('fuel-types.store');
    Route::get('/{fuelType}', [FuelTypeController::class, 'show'])->name('fuel-types.show');
    Route::put('/{fuelType}', [FuelTypeController::class, 'update'])->name('fuel-types.update');
    Route::patch('/{fuelType}/toggle', [FuelTypeController::class, 'toggleStatus'])->name('fuel-types.toggle');
    Route::delete('/{fuelType}', [FuelTypeController::class, 'destroy'])->name('fuel-types.destroy');
  });

  // Oil Type routes
  Route::prefix('oil-types')->group(function () {
    Route::get('/', [OilTypeController::class, 'index'])->name('oil-types.index');
    Route::post('/', [OilTypeController::class, 'store'])->name('oil-types.store');
    Route::get('/{oilType}', [OilTypeController::class, 'show'])->name('oil-types.show');
    Route::put('/{oilType}', [OilTypeController::class, 'update'])->name('oil-types.update');
    Route::patch('/{oilType}/toggle', [OilTypeController::class, 'toggleStatus'])->name('oil-types.toggle');
    Route::delete('/{oilType}', [OilTypeController::class, 'destroy'])->name('oil-types.destroy');
  });

  // Project routes
  Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::patch('/{project}/toggle', [ProjectController::class, 'toggleStatus'])->name('projects.toggle');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
  });

  // Nature of Account routes
  Route::prefix('nature-of-accounts')->group(function () {
    Route::get('/', [NatureOfAccountController::class, 'index'])->name('nature-of-accounts.index');
    Route::post('/', [NatureOfAccountController::class, 'store'])->name('nature-of-accounts.store');
    Route::get('/{natureOfAccount}', [NatureOfAccountController::class, 'show'])->name('nature-of-accounts.show');
    Route::put('/{natureOfAccount}', [NatureOfAccountController::class, 'update'])->name('nature-of-accounts.update');
    Route::patch('/{natureOfAccount}/toggle', [NatureOfAccountController::class, 'toggleStatus'])->name('nature-of-accounts.toggle');
    Route::delete('/{natureOfAccount}', [NatureOfAccountController::class, 'destroy'])->name('nature-of-accounts.destroy');
  });

  Route::view('/chart-of-accounts', 'content.pages.system-management.chart-of-accounts')->name('chart-of-accounts');

  // Job Type routes
  Route::prefix('jobs-types')->group(function () {
    Route::get('/', [JobTypeController::class, 'index'])->name('job-types.index');
    Route::post('/', [JobTypeController::class, 'store'])->name('job-types.store');
    Route::get('/{jobType}', [JobTypeController::class, 'show'])->name('job-types.show');
    Route::put('/{jobType}', [JobTypeController::class, 'update'])->name('job-types.update');
    Route::patch('/{jobType}/toggle', [JobTypeController::class, 'toggleStatus'])->name('job-types.toggle');
    Route::delete('/{jobType}', [JobTypeController::class, 'destroy'])->name('job-types.destroy');
  });

  // Sales Tax Territory routes
  Route::prefix('sales-tax-territories')->group(function () {
    Route::get('/', [SalesTaxTerritoryController::class, 'index'])->name('sales-tax-territories.index');
    Route::post('/', [SalesTaxTerritoryController::class, 'store'])->name('sales-tax-territories.store');
    Route::get('/{salesTaxTerritory}', [SalesTaxTerritoryController::class, 'show'])->name('sales-tax-territories.show');
    Route::put('/{salesTaxTerritory}', [SalesTaxTerritoryController::class, 'update'])->name('sales-tax-territories.update');
    Route::patch('/{salesTaxTerritory}/toggle', [SalesTaxTerritoryController::class, 'toggleStatus'])->name('sales-tax-territories.toggle');
    Route::delete('/{salesTaxTerritory}', [SalesTaxTerritoryController::class, 'destroy'])->name('sales-tax-territories.destroy');
  });

  // Company routes
  Route::prefix('companies')->group(function () {
    Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
    Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::put('/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::patch('/{company}/toggle', [CompanyController::class, 'toggleStatus'])->name('companies.toggle');
    Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
  });

  // Party routes
  Route::prefix('parties')->group(function () {
    Route::view('/', 'content.pages.system-management.parties')->name('parties');
    Route::get('/apiIndex', [PartyController::class, 'apiIndex'])->name('parties.index');
    Route::get('/type/{type}', [PartyController::class, 'apiByType'])->name('parties.byType');
    Route::post('/', [PartyController::class, 'store'])->name('parties.store');
    Route::get('/{party}', [PartyController::class, 'show'])->name('parties.show');
    Route::put('/{party}', [PartyController::class, 'update'])->name('parties.update');
    Route::patch('/{party}/toggle', [PartyController::class, 'toggleStatus'])->name('parties.toggle');
    Route::delete('/{party}', [PartyController::class, 'destroy'])->name('parties.destroy');
  });

  // Bank routes
  Route::prefix('banks')->group(function () {
    Route::get('/', [BankController::class, 'index'])->name('banks.index');

    Route::post('/', [BankController::class, 'store'])->name('banks.store');
    Route::get('/active', [BankController::class, 'getActive'])->name('banks.active');
    Route::get('/search', [BankController::class, 'search'])->name('banks.search');
    Route::get('/{bank}', [BankController::class, 'show'])->name('banks.show');
    Route::put('/{bank}', [BankController::class, 'update'])->name('banks.update');
    Route::patch('/{bank}/toggle', [BankController::class, 'toggleStatus'])->name('banks.toggle');
    Route::delete('/{bank}', [BankController::class, 'destroy'])->name('banks.destroy');
  });

  // Container Size routes
  Route::prefix('container-sizes')->group(function () {
    Route::get('/', [ContainerSizeController::class, 'index'])->name('container-sizes.index');
    Route::post('/', [ContainerSizeController::class, 'store'])->name('container-sizes.store');
    Route::get('/{containerSize}', [ContainerSizeController::class, 'show'])->name('container-sizes.show');
    Route::put('/{containerSize}', [ContainerSizeController::class, 'update'])->name('container-sizes.update');
    Route::patch('/{containerSize}/toggle', [ContainerSizeController::class, 'toggleStatus'])->name('container-sizes.toggle');
    Route::delete('/{containerSize}', [ContainerSizeController::class, 'destroy'])->name('container-sizes.destroy');
  });

  // Parties API Routes
  Route::prefix('api/parties')->group(function () {
    Route::get('/', [PartyController::class, 'apiIndex'])->name('api.parties.index');
    Route::get('/type/{type}', [PartyController::class, 'apiByType'])->name('api.parties.by-type');
    Route::post('/', [PartyController::class, 'store'])->name('api.parties.store');
    Route::get('/{party}', [PartyController::class, 'show'])->name('api.parties.show');
    Route::put('/{party}', [PartyController::class, 'update'])->name('api.parties.update');
    Route::patch('/{party}/toggle-status', [PartyController::class, 'toggleStatus'])->name('api.parties.toggle-status');
    Route::delete('/{party}', [PartyController::class, 'destroy'])->name('api.parties.destroy');
    Route::get('/counts/summary', [PartyController::class, 'getCounts'])->name('api.parties.counts');
  });

  // Voucher Type routes
  Route::prefix('voucher-types')->group(function () {
    Route::get('/', [VoucherTypeController::class, 'index'])->name('voucher-types.index');
    Route::post('/', [VoucherTypeController::class, 'store'])->name('voucher-types.store');
    Route::get('/{voucherType}', [VoucherTypeController::class, 'show'])->name('voucher-types.show');
    Route::put('/{voucherType}', [VoucherTypeController::class, 'update'])->name('voucher-types.update');
    Route::patch('/{voucherType}/toggle', [VoucherTypeController::class, 'toggleStatus'])->name('voucher-types.toggle');
    Route::delete('/{voucherType}', [VoucherTypeController::class, 'destroy'])->name('voucher-types.destroy');
  });

  // User Status routes
  Route::prefix('users-status')->group(function () {
    Route::get('/', [UserStatusController::class, 'index'])->name('users-status.index');
    Route::post('/', [UserStatusController::class, 'store'])->name('users-status.store');
    Route::get('/active', [UserStatusController::class, 'active'])->name('users-status.active');
    Route::get('/search', [UserStatusController::class, 'search'])->name('users-status.search');
    Route::get('/stats', [UserStatusController::class, 'stats'])->name('users-status.stats');
    Route::get('/colors', [UserStatusController::class, 'colors'])->name('users-status.colors');
    Route::post('/bulk', [UserStatusController::class, 'bulk'])->name('users-status.bulk');
    Route::get('/{id}', [UserStatusController::class, 'show'])->name('users-status.show');
    Route::put('/{id}', [UserStatusController::class, 'update'])->name('users-status.update');
    Route::patch('/{id}/toggle', [UserStatusController::class, 'toggle'])->name('users-status.toggle');
    Route::delete('/{id}', [UserStatusController::class, 'destroy'])->name('users-status.destroy');
  });

  // Job Queue Comments
  Route::post('/job-comments', [JobCommentController::class, 'store'])->name('job-comments.store');
  Route::get('/job-comments', [JobCommentController::class, 'index'])->name('job-comments.index');
  Route::get('/job-comments/import/{jobId}/{status}', [JobCommentController::class, 'getImportJobComments'])->name('job-comments.import');
  Route::get('/job-comments/export/{jobId}/{status}', [JobCommentController::class, 'getExportJobComments'])->name('job-comments.export');

  // Keep the old route for backward compatibility but redirect to the new endpoint
  Route::post('/job-queue-comments', function (Request $request) {
    return redirect()->route('job-comments.store')->withInput();
  });

  // Add this route to your existing routes:
  Route::get('/fuel-management/{id}/download-slip', [FuelManagementController::class, 'downloadSlip'])->name('fuel-management.download-slip');

  Route::resource('cros', CroController::class);
  Route::get('/cros/{cro}/view', [CroController::class, 'show'])->name('cros.show');
  Route::get('/cros/{cro}/jobs-data', [CroController::class, 'getJobsData'])->name('cros.jobs-data');



  // POD Routes
  Route::resource('pods', PodController::class);
  Route::put('pods/{id}/toggle', [PodController::class, 'toggleStatus'])->name('pods.toggle');
  Route::post('pods/{id}/restore', [PodController::class, 'restore'])->name('pods.restore');
  Route::delete('pods/{id}/force-delete', [PodController::class, 'forceDelete'])->name('pods.force-delete');

  // Terminal Routes
  Route::resource('terminals', TerminalController::class);
  Route::put('terminals/{terminal}/toggle', [TerminalController::class, 'toggleStatus'])->name('terminals.toggle-status');
  Route::post('terminals/{id}/restore', [TerminalController::class, 'restore'])->name('terminals.restore');

  Route::middleware(['can:export.manage'])->group(function () {
    Route::resource('export-jobs', ExportJobController::class)->only(['show']);
  });

  // Export Jobs Routes
  Route::middleware(['can:export.create'])->group(function () {
    Route::resource('export-jobs', ExportJobController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('export-jobs/{exportJob}/update-status', [ExportJobController::class, 'updateStatus'])->name('export-jobs.update-status');
    Route::post('export-jobs/{exportJob}/convert-to-vehicle-required', [ExportJobController::class, 'convertToVehicleRequired'])->name('export-jobs.convert-to-vehicle-required');
    Route::patch('export-jobs/{exportJob}/ready-to-move-status', [ExportJobController::class, 'readyToMoveStatus'])->name('export-jobs.ready-to-move-status');
    Route::patch('export-jobs/{exportJob}/container-returned', [ExportJobController::class, 'containerReturned'])->name('export-jobs.container-returned');
    Route::post('export-jobs/{exportJob}/vehicle-required', [ExportJobController::class, 'vehicleRequired'])->name('export-jobs.vehicle-required');
    Route::patch('/export-logistics/{job}/mark-completed', [ExportLogisticController::class, 'markCompleted'])->name('export-logistics.mark-completed');
  });

  // Export Logistics Routes
  Route::middleware(['can:export.logistics'])->group(function () {
    Route::get('/export-logistics', [ExportLogisticController::class, 'index'])->name('export-logistics.index');
    Route::get('/export-logistics/{job}', [ExportLogisticController::class, 'show'])->name('export-logistics.show');
    Route::post('/export-logistics/{job}/assign', [ExportLogisticController::class, 'assign'])->name('export-logistics.assign');
    Route::patch('/export-logistics/{job}/mark-on-route', [ExportLogisticController::class, 'markOnRoute'])->name('export-logistics.mark-on-route');
    Route::patch('/export-logistics/{job}/mark-vehicle-returned', [ExportLogisticController::class, 'markVehicleReturned'])->name('export-logistics.mark-vehicle-returned');
  });

  // Export Logistics Extra Routes
  Route::get('/export-logistics/{job}/extra-routes', [ExportLogisticController::class, 'getExtraRoutes'])->name('export-logistics.extra-routes');
  Route::post('/export-logistics/{job}/add-extra-route', [ExportLogisticController::class, 'addExtraRoute'])->name('export-logistics.add-extra-route');

  // Special logistics actions
  Route::post('export-jobs/{exportJobId}/assign-vehicle', [ExportLogisticController::class, 'assignVehicle'])->name('export-logistics.assign-vehicle');
  Route::post('export-jobs/{exportJobId}/record-gate-pass', [ExportLogisticController::class, 'recordGatePass'])->name('export-logistics.record-gate-pass');
  Route::get('available-vehicles', [ExportLogisticController::class, 'getAvailableVehicles'])->name('export-logistics.available-vehicles');
  Route::get('available-routes', [ExportLogisticController::class, 'getAvailableRoutes'])->name('export-logistics.available-routes');

  // Loaded Return Routes
  Route::get('/loaded-returns', [LoadedReturnController::class, 'index'])->name('loaded-returns.index');
  Route::get('/loaded-returns/{job}', [LoadedReturnController::class, 'show'])->name('loaded-returns.show');
  Route::post('/loaded-returns/{job}/store', [LoadedReturnController::class, 'store'])->name('loaded-returns.store');
  Route::put('/loaded-returns/{loadedReturn}', [LoadedReturnController::class, 'update'])->name('loaded-returns.update');
  Route::patch('/loaded-returns/{job}/mark-completed', [LoadedReturnController::class, 'markCompleted'])->name('loaded-returns.mark-completed');
  Route::patch('/loaded-returns/{job}/mark-dry-off', [LoadedReturnController::class, 'markDryOff'])->name('loaded-returns.mark-dry-off');
  Route::get('/loaded-returns/statistics', [LoadedReturnController::class, 'getStatistics'])->name('loaded-returns.statistics');

  // Workshop Jobs Routes
  Route::middleware(['can:workshop.manage'])->group(function () {
    Route::resource('workshop-jobs', WorkshopJobController::class)->only(['index', 'destroy']);
  });
  Route::middleware(['can:workshop.request'])->group(function () {
    Route::resource('workshop-jobs', WorkshopJobController::class)->only(['create', 'store']);
  });
  Route::middleware(['can:workshop.edit'])->group(function () {
    Route::resource('workshop-jobs', WorkshopJobController::class)->only(['edit', 'update', 'show']);
  });
  Route::middleware(['can:workshop.accept/reject/payaid'])->group(function () {
    Route::get('/workshop-jobs-data', [WorkshopJobController::class, 'getData'])->name('workshop-jobs.data');
    Route::patch('/workshop-jobs/{workshopJob}/status', [WorkshopJobController::class, 'updateStatus'])->name('workshop-jobs.update-status');
  });
});
