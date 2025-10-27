@extends('layouts/layoutMaster')

@section('title', 'Fuel Tank Details')

@section('content')
<div class="row mb-4">
  <!-- Tank Information -->
 <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-barrel me-1"></i>
          Fuel Tank Information
        </h5>
      </div>
      <div class="card-body p-4">
        <!-- Tank Name -->
        <div class="d-flex align-items-center mb-3">
          <div class="avatar me-2">
            <span class="avatar-initial rounded-circle bg-label-primary">
              <i class="ti tabler-barrel"></i>
            </span>
          </div>
          <div>
            <h6 class="mb-0 fw-bold">Tank Name</h6>
            <p class="mb-0 text-muted">{{ $tank->name }}</p>
          </div>
        </div>

        <!-- Supervisor -->
        <div class="d-flex align-items-center mb-3">
          <div class="avatar me-2">
            <span class="avatar-initial rounded-circle bg-label-primary">
              <i class="ti tabler-user"></i>
            </span>
          </div>

          <div>
            <h6 class="mb-0 fw-bold">Supervisor</h6>
            <p class="mb-0 text-muted">{{ $tank->supervisor ? $tank->supervisor->name : 'N/A' }}</p>
          </div>
        </div>

        <!-- Capacity -->
        <div class="d-flex align-items-center mb-3">
          <div class="avatar me-2">
            <span class="avatar-initial rounded-circle bg-label-primary">
              <i class="ti tabler-scale"></i>
            </span>
          </div>
          <div>
            <h6 class="mb-0 fw-bold">Capacity</h6>
            <p class="mb-0 text-muted">{{ $tank->capacity_volume }} Liters</p>
          </div>
        </div>

        <!-- Available Fuel with Progress -->
        <div class="d-flex align-items-center mb-3">
          <div class="avatar me-2">
            <span class="avatar-initial rounded-circle bg-label-primary">
              <i class="ti tabler-droplet"></i>
            </span>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-1 fw-bold">Available Fuel</h6>
            <div class="progress" style="height: 6px;">
              @php
                // Calculate total added fuel
                $totalAdded = $tank->fuelManagements->sum('qty');

                // Calculate used fuel from all payments
                $totalUsedFuel = 0;
                $payments = \App\Models\FuelPayment::where('tank_id', $tank->id)
                  ->where('payment_status', 'paid')
                  ->get();

                foreach ($payments as $payment) {
                  $fuelAmount = \App\Http\Controllers\FuelManagerController::getFuelAmountForReference($payment->reference_id, $payment->reference_type);
                  $totalUsedFuel += $fuelAmount;
                }

                // Calculate available fuel
                $availableFuel = $totalAdded - $totalUsedFuel;

                // Calculate percentage against tank capacity
                $percentage = $tank->capacity_volume > 0
                  ? ($availableFuel / $tank->capacity_volume) * 100
                  : 0;

                // Cap at 100%
                $percentage = min(100, $percentage);
              @endphp
              <div class="progress-bar bg-info" role="progressbar"
                   style="width: {{ $percentage }}%;"
                   aria-valuenow="{{ $percentage }}"
                   aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
            <small class="text-muted">{{ $availableFuel }} / {{ $tank->capacity_volume }} Liters</small>
          </div>
        </div>

        <!-- Used Fuel -->
        <div class="d-flex align-items-center mb-3">
          <div class="avatar me-2">
            <span class="avatar-initial rounded-circle bg-label-warning">
              <i class="ti tabler-gas-station"></i>
            </span>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-1 fw-bold">Used Fuel</h6>
            <div class="progress" style="height: 6px;">
              @php
                $totalAdded = $tank->fuelManagements->sum('qty');

                // Calculate the actual used fuel from all payments
                $totalUsedFuel = 0;
                $payments = \App\Models\FuelPayment::where('tank_id', $tank->id)
                  ->where('payment_status', 'paid')
                  ->get();

                foreach ($payments as $payment) {
                  $fuelAmount = \App\Http\Controllers\FuelManagerController::getFuelAmountForReference($payment->reference_id, $payment->reference_type);
                  $totalUsedFuel += $fuelAmount;
                }

                $usedFuel = $totalUsedFuel;
                $usedPercentage = $totalAdded > 0
                  ? ($usedFuel / $totalAdded) * 100
                  : 0;
              @endphp
              <div class="progress-bar bg-warning" role="progressbar"
                   style="width: {{ $usedPercentage }}%;"
                   aria-valuenow="{{ $usedPercentage }}"
                   aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
            <small class="text-muted">{{ $usedFuel }} / {{ $totalAdded }} Liters</small>
          </div>
        </div>

        <!-- Status -->
        <div class="d-flex align-items-center">
          <div class="avatar me-2">
            <span class="avatar-initial rounded-circle bg-label-primary">
              <i class="ti tabler-power"></i>
            </span>
          </div>
          <div>
            <h6 class="mb-0 fw-bold">Status</h6>
            <span class="badge rounded-pill bg-{{ $tank->status === 'active' ? 'success' : 'secondary' }}">
              {{ ucfirst($tank->status) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Fuel Usage Summary -->
  <div class="col-md-8">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-chart-bar me-1"></i>
          Fuel Usage Summary
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row">
          <!-- Total Added Fuel -->
          <div class="col-md-4 mb-4 mb-md-0">
            <div class="card bg-label-success h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <span class="avatar-initial rounded-circle bg-success">
                      <i class="ti tabler-plus text-white"></i>
                    </span>
                  </div>
                  <h6 class="mb-0 fw-semibold">Total Added</h6>
                </div>
                <h4 class="mb-1">{{ number_format($totalAdded) }} L</h4>
                <p class="text-muted mb-0">Total fuel added to tank</p>
              </div>
            </div>
          </div>

          <!-- Used Fuel -->
          <div class="col-md-4 mb-4 mb-md-0">
            <div class="card bg-label-warning h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <span class="avatar-initial rounded-circle bg-warning">
                      <i class="ti tabler-minus text-white"></i>
                    </span>
                  </div>
                  <h6 class="mb-0 fw-semibold">Total Used</h6>
                </div>
                @php
                  // Calculate the actual used fuel from all payments
                  $totalUsedFuel = 0;

                  // Get all fuel payments for this tank
                  $allPayments = \App\Models\FuelPayment::where('tank_id', $tank->id)
                    ->where('payment_status', 'paid')
                    ->get();

                  foreach ($allPayments as $payment) {
                    $fuelAmount = \App\Http\Controllers\FuelManagerController::getFuelAmountForReference($payment->reference_id, $payment->reference_type);
                    $totalUsedFuel += $fuelAmount;
                  }

                  // Recalculate usedFuel based on payments
                  $usedFuel = $totalUsedFuel;
                @endphp
                <h4 class="mb-1">{{ number_format($usedFuel) }} L</h4>
                <p class="text-muted mb-0">Total fuel used from tank</p>
              </div>
            </div>
          </div>

          <!-- Available Fuel -->
          <div class="col-md-4">
            <div class="card bg-label-info h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <span class="avatar-initial rounded-circle bg-info">
                      <i class="ti tabler-droplet-filled text-white"></i>
                    </span>
                  </div>
                  <h6 class="mb-0 fw-semibold">Available</h6>
                </div>
                @php
                  // Recalculate available fuel based on total added and actual used
                  $availableFuel = $totalAdded - $totalUsedFuel;
                @endphp
                <h4 class="mb-1">{{ number_format($availableFuel) }} L</h4>
                <p class="text-muted mb-0">Current available fuel</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Summary -->
        <div class="mt-4">
          <h6 class="fw-semibold mb-3">Recent Fuel Payments</h6>
          <div class="table-responsive">
            <table class="table table-sm table-borderless">
              <thead>
                <tr class="text-muted">
                  <th>Reference</th>
                  <th>Route</th>
                  <th>Date</th>
                  <th class="text-end">Fuel Used</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $fuelPayments = \App\Models\FuelPayment::where('tank_id', $tank->id)
                    ->where('payment_status', 'paid')
                    ->orderBy('payment_date', 'desc')
                    ->take(5)
                    ->get();
                @endphp
                @forelse($fuelPayments as $payment)
                  @php
                    $routeName = 'Unknown Route';
                    $fuelAmount = 0;

                    if ($payment->reference_type === 'logistics') {
                      $logistics = \App\Models\JobLogistics::with('route')->find($payment->reference_id);
                      if ($logistics && $logistics->route) {
                        $routeName = $logistics->route->route_name;
                        $fuelAmount = $logistics->route->expected_fuel;
                      }
                    } elseif ($payment->reference_type === 'empty_return') {
                      $emptyReturn = \App\Models\JobEmptyReturn::with('route')->find($payment->reference_id);
                      if ($emptyReturn && $emptyReturn->route) {
                        $routeName = $emptyReturn->route->route_name;
                        $fuelAmount = $emptyReturn->route->expected_fuel;
                      }
                    } elseif ($payment->reference_type === 'export_logistics') {
                      $exportLogistic = \App\Models\ExportLogistic::with('route')->find($payment->reference_id);
                      if ($exportLogistic && $exportLogistic->route) {
                        $routeName = $exportLogistic->route->route_name;
                        $fuelAmount = $exportLogistic->route->expected_fuel;
                      }
                    } elseif ($payment->reference_type === 'loaded_return') {
                      $loadedReturn = \App\Models\LoadedReturn::with('route')->find($payment->reference_id);
                      if ($loadedReturn && $loadedReturn->route) {
                        $routeName = $loadedReturn->route->route_name;
                        $fuelAmount = $loadedReturn->route->expected_fuel;
                      }
                    } elseif ($payment->reference_type === 'extra_route_empty_return' || $payment->reference_type === 'extra_route_export_logistics') {
                      $extraRoute = \App\Models\ExtraRoute::with('route')->find($payment->reference_id);
                      if ($extraRoute && $extraRoute->route) {
                        $routeName = $extraRoute->route->route_name;
                        $fuelAmount = $extraRoute->route->expected_fuel;
                      }
                    }
                  @endphp
                  <tr>
                    <td>
                      <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->reference_type)) }}</span>
                      #{{ $payment->reference_id }}
                    </td>
                    <td>{{ $routeName }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                    <td class="text-end">{{ $fuelAmount }} L</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">No fuel payments found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if(count($fuelPayments) > 0)
            <div class="text-center mt-3">
              <a href="{{ route('fuel-manager.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti tabler-list me-1"></i> View All Payments
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Fuel Management Table -->
  <div class="col-md-12 mt-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-receipt me-1"></i>
          Fuel Management
        </h5>
      </div>
      <div class="card-datatable table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Vendor</th>
              <th>Fuel Type</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Amount</th>
              <th>Delivery Date</th>
              <th>Freight Charges</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($tank->fuelManagements as $management)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $management->vendor->title ?? 'N/A' }}</td>
              <td>{{ $management->fuel_type->label ?? 'N/A' }}</td>
              <td>{{ $management->qty }}</td>
              <td>{{ $management->rate }}</td>
              <td>{{ $management->amount }}</td>
              <td>{{ \Carbon\Carbon::parse($management->delivery_date)->format('M d, Y') }}</td>
              <td>{{ $management->freight_charges }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center">No fuel management records found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<style>
  .card-subtitle {
    color: var(--bs-secondary);
  }

  .card-header {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    border-bottom: 1px solid rgba(var(--bs-primary-rgb), 0.1);
  }

  .card-header .card-title {
    color: rgb(var(--bs-primary-rgb));
    font-weight: 600;
  }

</style>
@endsection
