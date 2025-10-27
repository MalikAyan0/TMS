<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tank;
use App\Models\Fleet;
use App\Models\Route;
use App\Models\Company;
use App\Models\JobQueue;
use App\Models\ExtraRoute;
use App\Models\FuelPayment;
use App\Models\JobLogistics;
use App\Models\LoadedReturn;
use Illuminate\Http\Request;
use App\Models\ExportLogistic;
use App\Models\JobEmptyReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FuelManagerController extends Controller
{
  /**
   * Cache TTL in seconds (5 minutes)
   */
  const CACHE_TTL = 300;

  /**
   * Display the fuel manager index page.
   */
  public function index()
  {
    $fleets = Cache::remember('all_fleets', self::CACHE_TTL, function () {
      return Fleet::all();
    });

    return view('content.pages.fuel-manager.index', compact('fleets'));
  }

  /**
   * Get filtered fuel usage data.
   */
  public function getData(Request $request)
  {
    $validated = $request->validate([
      'fleet_id' => 'nullable|exists:fleets,id',
      'date_from' => 'nullable|date',
      'date_to' => 'nullable|date|after_or_equal:date_from',
    ]);

    // Build queries with eager loading optimization
    $logisticsQuery = $this->buildLogisticsQuery($validated);
    $emptyReturnsQuery = $this->buildEmptyReturnsQuery($validated);
    $exportLogisticsQuery = $this->buildExportLogisticsQuery($validated);
    $loadedReturnsQuery = $this->buildLoadedReturnsQuery($validated);

    // Execute queries
    $logistics = $logisticsQuery->get();
    $emptyReturns = $emptyReturnsQuery->get();
    $exportLogistics = $exportLogisticsQuery->get();
    $loadedReturns = $loadedReturnsQuery->get();

    // Fetch extra routes for the requested fleet
    $extraRoutes = $this->getExtraRoutesForFleet($validated['fleet_id'] ?? null, $validated);

    foreach ($extraRoutes as $extraRoute) {
      if ($extraRoute->reference_type === 'export_logistics') {
        $extraRoute->reference_type = 'extra_route_export_logistics';
      } elseif ($extraRoute->reference_type === 'job_empty_return') {
        $extraRoute->reference_type = 'extra_route_empty_return';
      }
    }

    // Process data and calculate totals
    list($data, $totalRoutes, $totalFuel) = $this->processAllRouteData(
      $logistics,
      $emptyReturns,
      $exportLogistics,
      $loadedReturns,
      $extraRoutes
    );

    // Handle empty data case
    if (empty($data)) {
      return $this->emptyDataResponse();
    }

    // Calculate summary statistics
    $totals = ['total_fuel' => $this->formatNumber($totalFuel)];
    $summary = [
      'total_routes' => $totalRoutes,
      'total_fuel' => $this->formatNumber($totalFuel),
      'avg_fuel' => $totalRoutes > 0 ? $this->formatNumber($totalFuel / $totalRoutes) : 0
    ];

    return response()->json([
      'success' => true,
      'data' => $data,
      'summary' => $summary,
      'totals' => $totals,
      'message' => null
    ]);
  }

  /**
   * Fetch extra routes for the requested fleet.
   */
  private function getExtraRoutesForFleet($fleetId, $filters)
  {
    $query = ExtraRoute::with('route:id,route_name,expected_fuel');

    if ($fleetId) {
      $query->where('vehicle_id', $fleetId);
    }

    if (!empty($filters['date_from'])) {
      $query->whereDate('created_at', '>=', Carbon::parse($filters['date_from']));
    }

    if (!empty($filters['date_to'])) {
      $query->whereDate('created_at', '<=', Carbon::parse($filters['date_to']));
    }

    return $query->get();
  }

  /**
   * Build logistics query with filters
   */
  private function buildLogisticsQuery($filters)
  {
    $query = JobLogistics::with([
      'vehicle:id,name,registration_number',
      'route:id,route_name,expected_fuel'
    ]);

    if (!empty($filters['fleet_id'])) {
      $query->where('vehicle_id', $filters['fleet_id']);
    }

    if (!empty($filters['date_from'])) {
      $query->whereDate('created_at', '>=', Carbon::parse($filters['date_from']));
    }

    if (!empty($filters['date_to'])) {
      $query->whereDate('created_at', '<=', Carbon::parse($filters['date_to']));
    }

    return $query->orderBy('created_at', 'desc');
  }

  /**
   * Build empty returns query with filters
   */
  private function buildEmptyReturnsQuery($filters)
  {
    $query = JobEmptyReturn::with([
      'vehicle:id,name,registration_number',
      'route:id,route_name,expected_fuel'
    ]);

    if (!empty($filters['fleet_id'])) {
      $query->where('vehicle_id', $filters['fleet_id']);
    }

    if (!empty($filters['date_from'])) {
      $query->whereDate('created_at', '>=', Carbon::parse($filters['date_from']));
    }

    if (!empty($filters['date_to'])) {
      $query->whereDate('created_at', '<=', Carbon::parse($filters['date_to']));
    }

    return $query->orderBy('created_at', 'desc');
  }

  /**
   * Build export logistics query with filters
   */
  private function buildExportLogisticsQuery($filters)
  {
    $query = ExportLogistic::with([
      'vehicle:id,name,registration_number',
      'route:id,route_name,expected_fuel'
    ]);

    if (!empty($filters['fleet_id'])) {
      $query->where('vehicle_id', $filters['fleet_id']);
    }

    if (!empty($filters['date_from'])) {
      $query->whereDate('created_at', '>=', Carbon::parse($filters['date_from']));
    }

    if (!empty($filters['date_to'])) {
      $query->whereDate('created_at', '<=', Carbon::parse($filters['date_to']));
    }

    return $query->orderBy('created_at', 'desc');
  }

  /**
   * Build loaded returns query with filters
   */
  private function buildLoadedReturnsQuery($filters)
  {
    $query = LoadedReturn::with([
      'vehicle:id,name,registration_number',
      'route:id,route_name,expected_fuel'
    ]);

    if (!empty($filters['fleet_id'])) {
      $query->where('vehicle_id', $filters['fleet_id']);
    }

    if (!empty($filters['date_from'])) {
      $query->whereDate('created_at', '>=', Carbon::parse($filters['date_from']));
    }

    if (!empty($filters['date_to'])) {
      $query->whereDate('created_at', '<=', Carbon::parse($filters['date_to']));
    }

    return $query->orderBy('created_at', 'desc');
  }

  /**
   * Process all route data types
   */
  private function processAllRouteData($logistics, $emptyReturns, $exportLogistics, $loadedReturns, $extraRoutes = [])
  {
    $data = [];
    $totalRoutes = 0;
    $totalFuel = 0;

    // Cache payment status to reduce DB queries
    $logisticsPayments = $this->getPaymentStatuses('logistics', $logistics->pluck('id'));
    $emptyReturnsPayments = $this->getPaymentStatuses('empty_return', $emptyReturns->pluck('id'));
    $exportLogisticsPayments = $this->getPaymentStatuses('export_logistics', $exportLogistics->pluck('id'));
    $loadedReturnsPayments = $this->getPaymentStatuses('loaded_return', $loadedReturns->pluck('id'));

    // Fetch payment statuses for extra routes
    $extraRouteEmptyReturnPayments = $this->getPaymentStatuses('extra_route_empty_return', $extraRoutes->where('reference_type', 'extra_route_empty_return')->pluck('id'));
    $extraRouteExportLogisticsPayments = $this->getPaymentStatuses('extra_route_export_logistics', $extraRoutes->where('reference_type', 'extra_route_export_logistics')->pluck('id'));

    // Process regular logistics
    foreach ($logistics as $logistic) {
      if (!$logistic->vehicle || !$logistic->route) continue;

      $expectedFuel = $logistic->route->expected_fuel ? floatval($logistic->route->expected_fuel) : 0;
      $paymentInfo = $logisticsPayments[$logistic->id] ?? ['status' => 'unpaid', 'id' => null];

      $data[] = $this->formatRouteData(
        $logistic->id,
        $logistic->created_at,
        $logistic->route->route_name ?? 'No Route Assigned',
        $logistic->vehicle,
        $expectedFuel,
        'Logistics',
        'logistics',
        $paymentInfo['status'],
        $paymentInfo['id']
      );

      $totalRoutes++;
      $totalFuel += $expectedFuel;
    }

    // Process empty returns
    foreach ($emptyReturns as $emptyReturn) {
      if (!$emptyReturn->vehicle || !$emptyReturn->route) continue;

      $expectedFuel = $emptyReturn->route->expected_fuel ? floatval($emptyReturn->route->expected_fuel) : 0;
      $paymentInfo = $emptyReturnsPayments[$emptyReturn->id] ?? ['status' => 'unpaid', 'id' => null];

      $data[] = $this->formatRouteData(
        $emptyReturn->id,
        $emptyReturn->created_at,
        $emptyReturn->route->route_name ?? 'No Route Assigned',
        $emptyReturn->vehicle,
        $expectedFuel,
        'Empty Return',
        'empty_return',
        $paymentInfo['status'],
        $paymentInfo['id']
      );

      $totalRoutes++;
      $totalFuel += $expectedFuel;
    }

    // Process export logistics
    foreach ($exportLogistics as $exportLogistic) {
      if ($exportLogistic->market_vehicle === 'yes' || !$exportLogistic->vehicle || !$exportLogistic->route) continue;

      $expectedFuel = $exportLogistic->route->expected_fuel ? floatval($exportLogistic->route->expected_fuel) : 0;
      $paymentInfo = $exportLogisticsPayments[$exportLogistic->id] ?? ['status' => 'unpaid', 'id' => null];

      $data[] = $this->formatRouteData(
        $exportLogistic->id,
        $exportLogistic->created_at,
        $exportLogistic->route->route_name ?? 'No Route Assigned',
        $exportLogistic->vehicle,
        $expectedFuel,
        'Export Logistics',
        'export_logistics',
        $paymentInfo['status'],
        $paymentInfo['id']
      );

      $totalRoutes++;
      $totalFuel += $expectedFuel;
    }

    // Process loaded returns
    foreach ($loadedReturns as $loadedReturn) {
      if ($loadedReturn->market_vehicle === 'yes' || !$loadedReturn->vehicle || !$loadedReturn->route) continue;

      $expectedFuel = $loadedReturn->route->expected_fuel ? floatval($loadedReturn->route->expected_fuel) : 0;
      $paymentInfo = $loadedReturnsPayments[$loadedReturn->id] ?? ['status' => 'unpaid', 'id' => null];

      $data[] = $this->formatRouteData(
        $loadedReturn->id,
        $loadedReturn->created_at,
        $loadedReturn->route->route_name ?? 'No Route Assigned',
        $loadedReturn->vehicle,
        $expectedFuel,
        'Loaded Return',
        'loaded_return',
        $paymentInfo['status'],
        $paymentInfo['id']
      );

      $totalRoutes++;
      $totalFuel += $expectedFuel;
    }

    // Process extra routes
    foreach ($extraRoutes as $extraRoute) {
      if (!$extraRoute->vehicle || !$extraRoute->route) continue;

      $expectedFuel = $extraRoute->route->expected_fuel ? floatval($extraRoute->route->expected_fuel) : 0;

      $referenceType = $extraRoute->reference_type === 'extra_route_empty_return'
        ? 'Extra Route (Empty Return)'
        : 'Extra Route (Export Logistics)';

      // Get payment info based on reference_type
      $paymentInfo = [];
      if ($extraRoute->reference_type === 'extra_route_empty_return') {
        $paymentInfo = $extraRouteEmptyReturnPayments[$extraRoute->id] ?? ['status' => 'unpaid', 'id' => null];
      } elseif ($extraRoute->reference_type === 'extra_route_export_logistics') {
        $paymentInfo = $extraRouteExportLogisticsPayments[$extraRoute->id] ?? ['status' => 'unpaid', 'id' => null];
      } else {
        $paymentInfo = ['status' => 'unpaid', 'id' => null];
      }

      $data[] = $this->formatRouteData(
        $extraRoute->id,
        $extraRoute->created_at,
        $extraRoute->route->route_name ?? 'No Route Assigned',
        $extraRoute->vehicle,
        $expectedFuel,
        $referenceType,
        $extraRoute->reference_type,
        $paymentInfo['status'],
        $paymentInfo['id']
      );

      $totalRoutes++;
      $totalFuel += $expectedFuel;
    }

    // Sort by date
    usort($data, function ($a, $b) {
      return strtotime($b['date']) - strtotime($a['date']);
    });

    return [$data, $totalRoutes, $totalFuel];
  }

  /**
   * Get payment statuses for a batch of references
   */
  private function getPaymentStatuses($referenceType, $referenceIds)
  {
    if (empty($referenceIds)) {
      return [];
    }

    $payments = FuelPayment::where('reference_type', $referenceType)
      ->whereIn('reference_id', $referenceIds)
      ->get();

    $statuses = [];
    foreach ($payments as $payment) {
      $statuses[$payment->reference_id] = [
        'status' => $payment->payment_status,
        'id' => $payment->id
      ];
    }

    return $statuses;
  }

  /**
   * Format route data for response
   */
  private function formatRouteData($id, $date, $routeName, $vehicle, $fuel, $type, $refType, $paymentStatus, $paymentId)
  {
    return [
      'id' => $id,
      'date' => $date->format('Y-m-d'),
      'route_name' => $routeName,
      'fleet_name' => "{$vehicle->name} - {$vehicle->registration_number}",
      'fuel_used' => $this->formatNumber($fuel),
      'type' => $type,
      'reference_type' => $refType,
      'payment_status' => $paymentStatus,
      'fuel_payment_id' => $paymentId
    ];
  }

  /**
   * Format number (return int if whole number, otherwise rounded float)
   */
  private function formatNumber($number)
  {
    return $number == floor($number) ? (int)$number : round($number, 2);
  }

  /**
   * Return empty data response
   */
  private function emptyDataResponse()
  {
    return response()->json([
      'success' => true,
      'data' => [],
      'summary' => [
        'total_routes' => 0,
        'total_fuel' => 0,
        'avg_fuel' => 0
      ],
      'totals' => [
        'total_fuel' => 0
      ],
      'message' => 'No fuel usage data found for the selected criteria.'
    ]);
  }

  /**
   * Export fuel usage data.
   */
  public function export(Request $request)
  {
    $dataResponse = $this->getData($request);
    $responseData = json_decode($dataResponse->getContent(), true);

    if (!$responseData['success'] || empty($responseData['data'])) {
      return redirect()->back()->with('error', 'No data found for export.');
    }

    $data = $responseData['data'];
    $summary = $responseData['summary'];
    $filename = 'fuel_usage_report_' . date('Y-m-d_H-i-s') . '.csv';

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
      'Cache-Control' => 'no-cache, no-store, must-revalidate',
      'Pragma' => 'no-cache',
      'Expires' => '0'
    ];

    $callback = function () use ($data, $summary) {
      $file = fopen('php://output', 'w');
      fputcsv($file, ['Date', 'Route Name', 'Fleet', 'Type', 'Fuel Used (L)']);

      foreach ($data as $row) {
        fputcsv($file, [
          $row['date'],
          $row['route_name'],
          $row['fleet_name'],
          $row['type'],
          $row['fuel_used']
        ]);
      }

      fputcsv($file, []);
      fputcsv($file, ['SUMMARY']);
      fputcsv($file, ['Total Routes', $summary['total_routes']]);
      fputcsv($file, ['Total Fuel Used (L)', $summary['total_fuel']]);
      fputcsv($file, ['Average Fuel per Route (L)', $summary['avg_fuel']]);

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  /**
   * Mark fuel payment as paid.
   */
  public function markAsPaid(Request $request)
  {
    $validated = $request->validate([
      'reference_id' => 'required|integer',
      'reference_type' => 'required|in:logistics,empty_return,export_logistics,loaded_return,extra_route_empty_return,extra_route_export_logistics',
      'payment_notes' => 'nullable|string|max:500',
      'tank_id' => 'required|exists:tanks,id'
    ]);

    try {
      DB::beginTransaction();

      // Get the tank with its fuel management records
      $tank = Tank::with(['fuelManagements' => function ($query) {
        $query->select('tank_id', DB::raw('SUM(qty) as total_qty'))
          ->groupBy('tank_id');
      }])->findOrFail($validated['tank_id']);

      $totalFuelAdded = $tank->fuelManagements->first() ? $tank->fuelManagements->first()->total_qty : 0;

      // Get the fuel needed for this payment
      $fuelNeeded = $this->getFuelAmountForReference(
        $validated['reference_id'],
        $validated['reference_type']
      );

      // Calculate total fuel already used with a single query
      $totalFuelUsed = FuelPayment::where('tank_id', $validated['tank_id'])
        ->where('payment_status', 'paid')
        ->get()
        ->sum(function ($payment) {
          return $this->getFuelAmountForReference(
            $payment->reference_id,
            $payment->reference_type
          );
        });

      $availableFuel = $totalFuelAdded - $totalFuelUsed;

      if ($fuelNeeded > $availableFuel) {
        DB::rollBack();
        return response()->json([
          'success' => false,
          'message' => "Insufficient fuel in tank. Available: {$availableFuel}L, Required: {$fuelNeeded}L"
        ], 422);
      }

      // Handle fleet ID for different reference types
      $fleetId = null;
      if (in_array($validated['reference_type'], ['extra_route_empty_return', 'extra_route_export_logistics'])) {
        $extraRoute = ExtraRoute::findOrFail($validated['reference_id']);
        $fleetId = $extraRoute->vehicle_id;
      } else {
        $fleetId = $this->getFleetIdFromReference($validated['reference_id'], $validated['reference_type']);
      }

      if (!$fleetId) {
        throw new \Exception("Fleet ID could not be determined for the given reference.");
      }

      $fuelPayment = FuelPayment::updateOrCreate(
        [
          'reference_id' => $validated['reference_id'],
          'reference_type' => $validated['reference_type']
        ],
        [
          'fleet_id' => $fleetId,
          'tank_id' => $validated['tank_id'],
          'payment_status' => 'paid',
          'payment_date' => now()->toDateString(),
          'payment_notes' => $validated['payment_notes'] ?? null
        ]
      );

      DB::commit();

      // Clear relevant caches
      Cache::forget('tank_details_' . $validated['tank_id']);

      return response()->json([
        'success' => true,
        'message' => 'Payment marked as paid successfully.'
      ]);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error marking payment as paid: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'An error occurred while processing the payment: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get the fuel amount for a reference - with cache
   */
  public static function getFuelAmountForReference($referenceId, $referenceType)
  {
    $cacheKey = "fuel_amount_{$referenceType}_{$referenceId}";

    return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($referenceId, $referenceType) {
      $routeId = null;

      switch ($referenceType) {
        case 'logistics':
          $logistics = JobLogistics::select('route_id')->find($referenceId);
          $routeId = $logistics->route_id ?? null;
          break;
        case 'empty_return':
          $emptyReturn = JobEmptyReturn::select('route_id')->find($referenceId);
          $routeId = $emptyReturn->route_id ?? null;
          break;
        case 'export_logistics':
          $exportLogistic = ExportLogistic::select('route_id')->find($referenceId);
          $routeId = $exportLogistic->route_id ?? null;
          break;
        case 'loaded_return':
          $loadedReturn = LoadedReturn::select('route_id')->find($referenceId);
          $routeId = $loadedReturn->route_id ?? null;
          break;
        case 'extra_route_empty_return':
        case 'extra_route_export_logistics':
          $extraRoute = ExtraRoute::select('route_id')->find($referenceId);
          $routeId = $extraRoute->route_id ?? null;
          break;
      }

      if ($routeId) {
        $route = Route::select('expected_fuel')->find($routeId);
        return $route ? floatval($route->expected_fuel) : 0;
      }

      return 0;
    });
  }

  /**
   * Get fleet ID from reference - with cache
   */
  private function getFleetIdFromReference($referenceId, $referenceType)
  {
    $cacheKey = "fleet_id_{$referenceType}_{$referenceId}";

    return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($referenceId, $referenceType) {
      switch ($referenceType) {
        case 'logistics':
          $logistics = JobLogistics::select('vehicle_id')->find($referenceId);
          return $logistics ? $logistics->vehicle_id : null;
        case 'empty_return':
          $emptyReturn = JobEmptyReturn::select('vehicle_id')->find($referenceId);
          return $emptyReturn ? $emptyReturn->vehicle_id : null;
        case 'export_logistics':
          $exportLogistic = ExportLogistic::select('vehicle_id')->find($referenceId);
          return $exportLogistic ? $exportLogistic->vehicle_id : null;
        case 'loaded_return':
          $loadedReturn = LoadedReturn::select('vehicle_id')->find($referenceId);
          return $loadedReturn ? $loadedReturn->vehicle_id : null;
        default:
          return null;
      }
    });
  }

  /**
   * Get available tanks for the authenticated user
   */
  public function getAvailableTanks()
  {
    try {
      $userId = Auth::id();
      $cacheKey = "user_tanks_{$userId}";

      $tanksData = Cache::remember($cacheKey, self::CACHE_TTL, function () {
        $tanks = FuelPayment::getAuthUserTanks();

        return $tanks->map(function ($tank) {
          return FuelPayment::getTankDetails($tank->id);
        });
      });

      return response()->json([
        'success' => true,
        'data' => $tanksData,
        'message' => 'Tanks retrieved successfully'
      ]);
    } catch (\Exception $e) {
      Log::error('Failed to retrieve tanks: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to retrieve tanks: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get detailed information about a specific tank
   */
  public function getTankDetails(Request $request)
  {
    try {
      $tankId = $request->input('tank_id');

      if (!$tankId) {
        return response()->json([
          'success' => false,
          'message' => 'Tank ID is required'
        ], 400);
      }

      $cacheKey = "tank_details_{$tankId}";
      $tankDetails = Cache::remember($cacheKey, 60, function () use ($tankId) {
        $tank = Tank::with(['fuelType:id,label', 'supervisor:id,name', 'fuelManagements'])
          ->find($tankId);

        if (!$tank) {
          return null;
        }

        $totalFuelAdded = $tank->fuelManagements->sum('qty');

        $fuelPayments = FuelPayment::where('tank_id', $tankId)
          ->where('payment_status', 'paid')
          ->get(['id', 'reference_id', 'reference_type', 'payment_date']);

        // Process payments in batches to calculate fuel amounts
        $totalFuelUsed = 0;
        $processedRoutes = [];

        // Group by reference type for fewer DB queries
        $paymentsByType = $fuelPayments->groupBy('reference_type');

        // Process logistics
        if (isset($paymentsByType['logistics'])) {
          $logisticsIds = $paymentsByType['logistics']->pluck('reference_id')->toArray();
          $logisticsMap = JobLogistics::whereIn('id', $logisticsIds)->get(['id', 'route_id'])
            ->keyBy('id');

          $routeIds = $logisticsMap->pluck('route_id')->filter()->toArray();
          $routeMap = Route::whereIn('id', $routeIds)->get(['id', 'route_name', 'expected_fuel'])
            ->keyBy('id');

          foreach ($paymentsByType['logistics'] as $payment) {
            $logistics = $logisticsMap[$payment->reference_id] ?? null;
            if ($logistics && isset($routeMap[$logistics->route_id])) {
              $route = $routeMap[$logistics->route_id];
              $expectedFuel = floatval($route->expected_fuel);
              $totalFuelUsed += $expectedFuel;

              $processedRoutes[] = [
                'id' => $route->id,
                'name' => $route->route_name,
                'expected_fuel' => $expectedFuel,
                'payment_id' => $payment->id,
                'payment_date' => $payment->payment_date,
                'reference_type' => 'logistics'
              ];
            }
          }
        }

        // Process empty returns
        if (isset($paymentsByType['empty_return'])) {
          $emptyReturnIds = $paymentsByType['empty_return']->pluck('reference_id')->toArray();
          $emptyReturnMap = JobEmptyReturn::whereIn('id', $emptyReturnIds)->get(['id', 'route_id'])
            ->keyBy('id');

          $routeIds = $emptyReturnMap->pluck('route_id')->filter()->toArray();
          $routeMap = Route::whereIn('id', $routeIds)->get(['id', 'route_name', 'expected_fuel'])
            ->keyBy('id');

          foreach ($paymentsByType['empty_return'] as $payment) {
            $emptyReturn = $emptyReturnMap[$payment->reference_id] ?? null;
            if ($emptyReturn && isset($routeMap[$emptyReturn->route_id])) {
              $route = $routeMap[$emptyReturn->route_id];
              $expectedFuel = floatval($route->expected_fuel);
              $totalFuelUsed += $expectedFuel;

              $processedRoutes[] = [
                'id' => $route->id,
                'name' => $route->route_name,
                'expected_fuel' => $expectedFuel,
                'payment_id' => $payment->id,
                'payment_date' => $payment->payment_date,
                'reference_type' => 'empty_return'
              ];
            }
          }
        }

        // Process export logistics
        if (isset($paymentsByType['export_logistics'])) {
          $exportLogisticIds = $paymentsByType['export_logistics']->pluck('reference_id')->toArray();
          $exportLogisticMap = ExportLogistic::whereIn('id', $exportLogisticIds)->get(['id', 'route_id'])
            ->keyBy('id');

          $routeIds = $exportLogisticMap->pluck('route_id')->filter()->toArray();
          $routeMap = Route::whereIn('id', $routeIds)->get(['id', 'route_name', 'expected_fuel'])
            ->keyBy('id');

          foreach ($paymentsByType['export_logistics'] as $payment) {
            $exportLogistic = $exportLogisticMap[$payment->reference_id] ?? null;
            if ($exportLogistic && isset($routeMap[$exportLogistic->route_id])) {
              $route = $routeMap[$exportLogistic->route_id];
              $expectedFuel = floatval($route->expected_fuel);
              $totalFuelUsed += $expectedFuel;

              $processedRoutes[] = [
                'id' => $route->id,
                'name' => $route->route_name,
                'expected_fuel' => $expectedFuel,
                'payment_id' => $payment->id,
                'payment_date' => $payment->payment_date,
                'reference_type' => 'export_logistics'
              ];
            }
          }
        }

        // Process loaded returns
        if (isset($paymentsByType['loaded_return'])) {
          $loadedReturnIds = $paymentsByType['loaded_return']->pluck('reference_id')->toArray();
          $loadedReturnMap = LoadedReturn::whereIn('id', $loadedReturnIds)->get(['id', 'route_id'])
            ->keyBy('id');

          $routeIds = $loadedReturnMap->pluck('route_id')->filter()->toArray();
          $routeMap = Route::whereIn('id', $routeIds)->get(['id', 'route_name', 'expected_fuel'])
            ->keyBy('id');

          foreach ($paymentsByType['loaded_return'] as $payment) {
            $loadedReturn = $loadedReturnMap[$payment->reference_id] ?? null;
            if ($loadedReturn && isset($routeMap[$loadedReturn->route_id])) {
              $route = $routeMap[$loadedReturn->route_id];
              $expectedFuel = floatval($route->expected_fuel);
              $totalFuelUsed += $expectedFuel;

              $processedRoutes[] = [
                'id' => $route->id,
                'name' => $route->route_name,
                'expected_fuel' => $expectedFuel,
                'payment_id' => $payment->id,
                'payment_date' => $payment->payment_date,
                'reference_type' => 'loaded_return'
              ];
            }
          }
        }

        // Process extra route empty return
        if (isset($paymentsByType['extra_route_empty_return'])) {
          $extraRouteIds = $paymentsByType['extra_route_empty_return']->pluck('reference_id')->toArray();
          $extraRouteMap = ExtraRoute::whereIn('id', $extraRouteIds)->get(['id', 'route_id'])
            ->keyBy('id');

          $routeIds = $extraRouteMap->pluck('route_id')->filter()->toArray();
          $routeMap = Route::whereIn('id', $routeIds)->get(['id', 'route_name', 'expected_fuel'])
            ->keyBy('id');

          foreach ($paymentsByType['extra_route_empty_return'] as $payment) {
            $extraRoute = $extraRouteMap[$payment->reference_id] ?? null;
            if ($extraRoute && isset($routeMap[$extraRoute->route_id])) {
              $route = $routeMap[$extraRoute->route_id];
              $expectedFuel = floatval($route->expected_fuel);
              $totalFuelUsed += $expectedFuel;

              $processedRoutes[] = [
                'id' => $route->id,
                'name' => $route->route_name,
                'expected_fuel' => $expectedFuel,
                'payment_id' => $payment->id,
                'payment_date' => $payment->payment_date,
                'reference_type' => 'extra_route_empty_return'
              ];
            }
          }
        }

        // Process extra route export logistics
        if (isset($paymentsByType['extra_route_export_logistics'])) {
          $extraRouteIds = $paymentsByType['extra_route_export_logistics']->pluck('reference_id')->toArray();
          $extraRouteMap = ExtraRoute::whereIn('id', $extraRouteIds)->get(['id', 'route_id'])
            ->keyBy('id');

          $routeIds = $extraRouteMap->pluck('route_id')->filter()->toArray();
          $routeMap = Route::whereIn('id', $routeIds)->get(['id', 'route_name', 'expected_fuel'])
            ->keyBy('id');

          foreach ($paymentsByType['extra_route_export_logistics'] as $payment) {
            $extraRoute = $extraRouteMap[$payment->reference_id] ?? null;
            if ($extraRoute && isset($routeMap[$extraRoute->route_id])) {
              $route = $routeMap[$extraRoute->route_id];
              $expectedFuel = floatval($route->expected_fuel);
              $totalFuelUsed += $expectedFuel;

              $processedRoutes[] = [
                'id' => $route->id,
                'name' => $route->route_name,
                'expected_fuel' => $expectedFuel,
                'payment_id' => $payment->id,
                'payment_date' => $payment->payment_date,
                'reference_type' => 'extra_route_export_logistics'
              ];
            }
          }
        }

        $availableFuel = $totalFuelAdded - $totalFuelUsed;

        return [
          'id' => $tank->id,
          'name' => $tank->name,
          'fuel_type' => $tank->fuelType ? $tank->fuelType->label : 'Unknown',
          'supervisor' => $tank->supervisor ? $tank->supervisor->name : 'Unassigned',
          'capacity_volume' => $tank->capacity_volume,
          'total_fuel_added' => $totalFuelAdded,
          'total_fuel_used' => $totalFuelUsed,
          'available_fuel' => $availableFuel,
          'location' => $tank->location,
          'status' => $tank->status,
          'fuel_payments_count' => $fuelPayments->count(),
          'processed_routes' => $processedRoutes
        ];
      });

      if ($tankDetails === null) {
        return response()->json([
          'success' => false,
          'message' => 'Tank not found'
        ], 404);
      }

      return response()->json([
        'success' => true,
        'data' => $tankDetails,
        'message' => 'Tank details retrieved successfully'
      ]);
    } catch (\Exception $e) {
      Log::error('Failed to retrieve tank details: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to retrieve tank details: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get tanks assigned to the authenticated user
   */
  public function getAuthUserTanks()
  {
    try {
      $userId = Auth::id();
      $cacheKey = "auth_user_tanks_{$userId}";

      $tanksDetails = Cache::remember($cacheKey, self::CACHE_TTL, function () {
        return FuelPayment::getAuthUserTankDetails();
      });

      return response()->json([
        'success' => true,
        'data' => $tanksDetails,
        'message' => 'Auth user tanks retrieved successfully'
      ]);
    } catch (\Exception $e) {
      Log::error('Failed to retrieve auth user tanks: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to retrieve auth user tanks: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show the print slip for a fuel payment
   */
  public function printSlip($id, $type)
  {
    // Get the payment record
    $payment = FuelPayment::where('reference_id', $id)
      ->where('reference_type', $type)
      ->where('payment_status', 'paid')
      ->firstOrFail();

    // Get route and fleet names
    $routeName = 'N/A';
    $fleetName = 'N/A';
    $fuelAmount = 0;
    $containerNumber = 'N/A';

    if ($type === 'logistics') {
      $logistics = JobLogistics::with(['route', 'vehicle'])->find($id);
      if ($logistics) {
        $routeName = $logistics->route->route_name ?? 'N/A';
        $fleetName = $logistics->vehicle->name ?? 'N/A';
        $fuelAmount = $logistics->route->expected_fuel ?? 0;
        $containerNumber = $this->getContainerNumber($logistics, 'logistics');
      }
    } elseif ($type === 'empty_return') {
      $emptyReturn = JobEmptyReturn::with(['route', 'vehicle'])->find($id);
      if ($emptyReturn) {
        $routeName = $emptyReturn->route->route_name ?? 'N/A';
        $fleetName = $emptyReturn->vehicle->name ?? 'N/A';
        $fuelAmount = $emptyReturn->route->expected_fuel ?? 0;
        $containerNumber = $this->getContainerNumber($emptyReturn, 'empty_return');
      }
    } elseif ($type === 'export_logistics') {
      $exportLogistic = ExportLogistic::with(['route', 'vehicle'])->find($id);
      if ($exportLogistic) {
        $routeName = $exportLogistic->route->route_name ?? 'N/A';
        $fleetName = $exportLogistic->vehicle->name ?? 'N/A';
        $fuelAmount = $exportLogistic->route->expected_fuel ?? 0;
        $containerNumber = $this->getContainerNumber($exportLogistic, 'export_logistics');
      }
    } elseif ($type === 'loaded_return') {
      $loadedReturn = LoadedReturn::with(['route', 'vehicle'])->find($id);
      if ($loadedReturn) {
        $routeName = $loadedReturn->route->route_name ?? 'N/A';
        $fleetName = $loadedReturn->vehicle->name ?? 'N/A';
        $fuelAmount = $loadedReturn->route->expected_fuel ?? 0;
        $containerNumber = $this->getContainerNumber($loadedReturn, 'loaded_return');
      }
    } elseif ($type === 'extra_route_empty_return') {
      $extraRoute = ExtraRoute::with(['route', 'vehicle'])->find($id);
      if ($extraRoute) {
        $routeName = $extraRoute->route->route_name ?? 'N/A';
        $fleetName = $extraRoute->vehicle->name ?? 'N/A';
        $fuelAmount = $extraRoute->route->expected_fuel ?? 0;
        // Fetch from original job
        if ($extraRoute->reference_type === 'job_empty_return') {
          $originalJob = JobEmptyReturn::find($extraRoute->reference_id);
          $containerNumber = $this->getContainerNumber($originalJob, 'empty_return');
        }
      }
    } elseif ($type === 'extra_route_export_logistics') {
      $extraRoute = ExtraRoute::with(['route', 'vehicle'])->find($id);
      if ($extraRoute) {
        $routeName = $extraRoute->route->route_name ?? 'N/A';
        $fleetName = $extraRoute->vehicle->name ?? 'N/A';
        $fuelAmount = $extraRoute->route->expected_fuel ?? 0;
        // Fetch from original job
        if ($extraRoute->reference_type === 'export_logistics') {
          $originalJob = ExportLogistic::find($extraRoute->reference_id);
          $containerNumber = $this->getContainerNumber($originalJob, 'export_logistics');
        }
      }
    }

    // Get tank details
    $tankName = 'N/A';
    $tank = Tank::find($payment->tank_id);
    if ($tank) {
      $tankName = $tank->name;
    }

    // Get company information
    $company = Company::first() ?? (object)['name' => 'Transport Management System', 'logo' => null];

    return view('content.pages.fuel-manager.slip', compact(
      'payment',
      'routeName',
      'fleetName',
      'fuelAmount',
      'tankName',
      'containerNumber',
      'company'
    ));
  }

  /**
   * Helper to get container number for a job
   */
  private function getContainerNumber($job, $type)
  {
    if (!$job || !isset($job->jobs_queue_id)) {
      return 'N/A';
    }

    $jobQueue = JobQueue::where('id', $job->jobs_queue_id)->first();
    $containerNumber = $jobQueue ? ($jobQueue->container ?? 'N/A') : 'N/A';

    // Fallback to job's container field if available
    if ($containerNumber === 'N/A' && !empty($job->container)) {
      $containerNumber = $job->container;
    }

    return $containerNumber;
  }
}
