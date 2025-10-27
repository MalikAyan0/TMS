@extends('layouts/layoutMaster')

@section('title', 'Logistics Dashboard - Apps')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss',
'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss'])
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/app-logistics-dashboard.scss')
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js',
'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('page-script')
@vite('resources/assets/js/app-logistics-dashboard.js')
@endsection

@section('content')
<!-- Jobs Related Data Row -->
<div class="row g-6 mb-6">
  <div class="col-12">
    <h5 class="text-primary mb-4">Jobs Overview</h5>
  </div>
  <div class="col-lg-4 col-sm-6">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-success">
              <i class="icon-base ti tabler-truck icon-28px"></i>
            </span>
          </div>
          <h4 class="mb-0">{{ $totalImportJobsCompleted ?? 0 }}</h4>
        </div>
        <p class="mb-1">Import Jobs Completed</p>
        <p class="mb-0">
          <small class="text-body-secondary">Total: {{ $totalImportJobs ?? 0 }}</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-sm-6">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-info">
              <i class="icon-base ti tabler-package icon-28px"></i>
            </span>
          </div>
          <h4 class="mb-0">{{ $totalExportJobsCompleted ?? 0 }}</h4>
        </div>
        <p class="mb-1">Export Jobs Completed</p>
        <p class="mb-0">
          <small class="text-body-secondary">Total: {{ $totalExportJobs ?? 0 }}</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-sm-6">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="icon-base ti tabler-engine icon-28px"></i>
            </span>
          </div>
          <h4 class="mb-0">{{ $totalWorkshopJobs ?? 0 }}</h4>
        </div>
        <p class="mb-1">Workshop Jobs</p>
        <p class="mb-0">
          <small class="text-body-secondary">Paid: {{ $paidWorkshopJobs ?? 0 }}</small>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Fuel Related Data Row -->
<div class="row g-6 mb-6">
  <div class="col-12">
    <h5 class="text-primary mb-4">Fuel Overview</h5>
  </div>
  <div class="col-lg-6 col-sm-6">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="icon-base ti tabler-gas-station icon-28px"></i>
            </span>
          </div>
          <h4 class="mb-0">{{ $totalFuelPurchased ?? 0 }} L</h4>
        </div>
        <p class="mb-1">Total Fuel Purchased</p>
        <p class="mb-0">
          <small class="text-body-secondary">Last Purchase: {{ $lastFuelPurchase ?? 'N/A' }}</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-sm-6">
    <div class="card card-border-shadow-secondary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-secondary">
              <i class="icon-base ti tabler-currency-dollar icon-28px"></i>
            </span>
          </div>
          <h4 class="mb-0">{{ $totalFuelPaid ?? 0 }} L</h4>
        </div>
        <p class="mb-1">Total Fuel Paid</p>
        <p class="mb-0">
          <small class="text-body-secondary">Pending: {{ ($totalFuelPurchased ?? 0) - ($totalFuelPaid ?? 0) }} L</small>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Workshop Related Data Row -->
<div class="row g-6 mb-6">
  <div class="col-12">
    <h5 class="text-primary mb-4">Workshop & System Overview</h5>
  </div>
  <div class="col-lg-6 col-sm-6">
    <div class="card card-border-shadow-secondary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-secondary">
              <i class="icon-base ti tabler-car icon-28px"></i>
            </span>
          </div>
          <h4 class="mb-0">{{ $totalActiveVehicles ?? 0 }}</h4>
        </div>
        <p class="mb-1">Active Vehicles</p>
        <p class="mb-0">
          <small class="text-body-secondary">Total Fleets: {{ $totalFleets ?? 0 }}</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-sm-6">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="avatar me-4">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="icon-base ti tabler-users icon-28px"></i>
            </span>
          </div>
          <h4 class="mb-0">{{ $onlineUsersToday ?? 0 }}</h4>
        </div>
        <p class="mb-1">Online Users Today</p>
        <p class="mb-0">
          <small class="text-body-secondary">Total Users: {{ $totalUsers ?? 0 }}</small>
        </p>
      </div>
    </div>
  </div>
</div>



@endsection
