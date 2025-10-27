@extends('layouts/layoutMaster')

@section('title', 'Job Details - ' . $job->job_number)

@section('vendor-style')
@vite([])
@endsection

@section('content')
<!-- Job Details Header -->
<div class="row mb-4">
  <!-- Job Information Card -->
  <div class="col-md-4 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar avatar-xl mx-auto mb-3">
          <span class="avatar-initial rounded-circle bg-label-primary">
            <i class="ti tabler-briefcase fs-2"></i>
          </span>
        </div>
        <h4 class="mb-1">{{ $job->job_number }}</h4>
        <p class="text-muted mb-3">
          @php
            $status = strtolower($job->status);
            $badgeClasses = [
              'open' => 'bg-label-primary',
              'vehicle required' => 'bg-label-warning', // Added new status
              'on route' => 'bg-label-info',
              'stuck on port' => 'bg-label-danger',
              'vehicle returned' => 'bg-label-dark',
              'empty return' => 'bg-label-secondary',
              'completed' => 'bg-label-success',
              'cancelled' => 'bg-label-danger',
            ];
          @endphp
          <span class="badge {{ $badgeClasses[$status] ?? 'bg-label-secondary' }}">{{ $job->status }}</span>
        </p>

        <!-- Job Stats -->
        <div class="row text-center">
          @foreach (['container' => 'ti tabler-container', 'size' => 'ti tabler-ruler'] as $field => $icon)
          <div class="col-6">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-info">
                  <i class="{{ $icon }}"></i>
                </span>
              </div>
              <div>
                <h6 class="mb-0">{{ $job->$field }}</h6>
                <small class="text-muted">{{ ucfirst($field) }}</small>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <!-- Basic Job Information -->
  <div class="col-md-8 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
          <i class="ti tabler-info-circle me-1"></i>
          Job Information
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          @foreach ([
            'Job Number' => $job->job_number,
            'Bail Number' => $job->bailNumber->bail_number ?? '-',
            'Company' => $job->company->title ?? '-',
            'Line' => $job->line->name ?? '-',
            'Port' => $job->port->name ?? '-',
            'Forwarder' => $job->forwarder->title ?? '-',
            'NOC Deadline' => $job->noc_deadline ? \Carbon\Carbon::parse($job->noc_deadline)->format('M d, Y') : '-',
            'ETA' => $job->eta ? \Carbon\Carbon::parse($job->eta)->format('M d, Y') : '-',
            'Created Date' => $job->created_at->format('M d, Y H:i A')
          ] as $label => $value)
          <div class="col-md-6">
            <label class="form-label fw-medium">{{ $label }}</label>
            <p class="mb-0">{{ $value }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Logistics Information -->
@if($job->logistics)
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-truck me-1"></i>
          Logistics Information
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          @foreach ([
            'Vehicle Type' => $job->logistics->market_vehicle === 'yes' ? '<span class="badge bg-label-warning">Market Vehicle</span>' : '<span class="badge bg-label-info">Fleet Vehicle</span>',
            'Vehicle Details' => $job->logistics->market_vehicle === 'yes' ? ($job->logistics->market_vehicle_details ?? '-') : ($job->logistics->vehicle->registration_number ?? '-'),
            'Gate Pass' => $job->logistics->gate_pass ?? '-',
            'Gate Time' => $job->logistics->gate_time_passed ? \Carbon\Carbon::parse($job->logistics->gate_time_passed)->format('M d, Y H:i A') : '-',
            'Route' => $job->logistics->route->route_name ?? '-',
            'Patrol' => $job->logistics->route->expected_fuel ? $job->logistics->route->expected_fuel . ' Liters' : '-'
          ] as $label => $value)
          <div class="col-md-3">
            <label class="form-label fw-medium">{{ $label }}</label>
            <p class="mb-0">{!! $value !!}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endif

<!-- Empty Return Information -->
@if($job->emptyReturn)
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-truck-return me-1"></i>
          Empty Return Information
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          @foreach ([
            'Vehicle Type' => $job->emptyReturn->market_vehicle === 'yes' ? '<span class="badge bg-label-warning">Market Vehicle</span>' : '<span class="badge bg-label-info">Fleet Vehicle</span>',
            'Vehicle Details' => $job->emptyReturn->market_vehicle === 'yes' ? ($job->emptyReturn->market_vehicle_details ?? '-') : ($job->emptyReturn->vehicle->registration_number ?? '-'),
            'Gate Pass' => $job->emptyReturn->gate_pass ?? '-',
            'Gate Time' => $job->emptyReturn->gate_time_passed ? \Carbon\Carbon::parse($job->emptyReturn->gate_time_passed)->format('M d, Y H:i A') : '-',
            'Return Route' => $job->emptyReturn->route->route_name ?? '-',
            'Patrol' => $job->emptyReturn->route->expected_fuel ? $job->emptyReturn->route->expected_fuel . ' Liters' : '-'
          ] as $label => $value)
          <div class="col-md-3">
            <label class="form-label fw-medium">{{ $label }}</label>
            <p class="mb-0">{!! $value !!}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endif

<!-- Status History & Timeline -->
<div class="row mb-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-timeline me-1"></i>
          Status History
        </h5>
      </div>
      <div class="card-body p-4">
        @if($job->statusLogs && $job->statusLogs->count() > 0)
        <ul class="timeline mb-0">
          @foreach($job->statusLogs->sortByDesc('created_at') as $log)
          <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point timeline-point-{{ $log->status === 'Completed' ? 'success' : ($log->status === 'Cancelled' ? 'danger' : 'primary') }}"></span>
            <div class="timeline-event">
              <div class="timeline-header mb-3">
                <h6 class="mb-0">
                  {{ $log->old_status ? "Status changed from {$log->old_status} to {$log->status}" : "Status set to {$log->status}" }}
                </h6>
                <small class="text-body-secondary">{{ $log->changed_at ? \Carbon\Carbon::parse($log->changed_at)->format('M d, Y H:i A') : $log->created_at->format('M d, Y H:i A') }}</small>
              </div>
              @if($log->remarks)
              <div class="d-flex align-items-start mb-2">
                <div class="badge bg-label-info rounded d-flex align-items-center me-2">
                  <i class="ti tabler-message-circle me-1"></i>
                  <span class="h6 mb-0">{{ $log->remarks }}</span>
                </div>
              </div>
              @endif
              @if($log->changedBy)
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded-circle bg-label-secondary">
                    <i class="ti tabler-user"></i>
                  </span>
                </div>
                <div>
                  <p class="mb-0 small fw-medium">{{ $log->changedBy->name }}</p>
                  <small class="text-body-secondary">System User</small>
                </div>
              </div>
              @endif
            </div>
          </li>
          @endforeach
        </ul>
        @else
        <div class="text-center py-4">
          <div class="avatar avatar-lg mx-auto mb-3">
            <span class="avatar-initial rounded-circle bg-label-secondary">
              <i class="ti tabler-timeline fs-2"></i>
            </span>
          </div>
          <h6 class="mb-1">No Status History</h6>
          <p class="text-muted">Status changes will appear here once they occur.</p>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Comments -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-file-text me-1"></i>
          Comments
        </h5>
      </div>
      <div class="card-body p-4">
        @php
          $comments = $job->jobComments
            ? $job->jobComments->where('type', 'import')->sortByDesc('created_at')->map(function($comment) {
                return [
                  'user_name'   => $comment->user->name ?? 'Unknown User',
                  'status'      => $comment->status,
                  'comment'     => $comment->comment,
                  'created_at'  => $comment->created_at->format('M d, Y H:i A'),
                  'created_diff'=> $comment->created_at->diffForHumans(),
                ];
              })->values()
            : collect();
        @endphp

        @if($comments->count() > 0)
        <ul class="list-unstyled mb-0" id="comments-list">
          {{-- Comments will be rendered by JS --}}
        </ul>
        <nav>
          <ul class="pagination justify-content-center mt-3 mb-0" id="comments-pagination">
            {{-- Pagination will be rendered by JS --}}
          </ul>
        </nav>
        <script>
          const comments = @json($comments);
          const badgeClasses = {
            'open': 'bg-label-primary',
            'vehicle required': 'bg-label-warning',
            'on route': 'bg-label-info',
            'stuck on port': 'bg-label-danger',
            'vehicle returned': 'bg-label-dark',
            'empty return': 'bg-label-secondary',
            'completed': 'bg-label-success',
            'cancelled': 'bg-label-danger'
          };
          const perPage = 5;
          let currentPage = 1;

          function renderComments(page) {
            currentPage = page;
            const start = (page - 1) * perPage;
            const end = start + perPage;
            const paginated = comments.slice(start, end);

            const list = document.getElementById('comments-list');
            list.innerHTML = '';

            paginated.forEach(comment => {
              const badgeClass = badgeClasses[comment.status.toLowerCase()] || 'bg-label-secondary';
              list.innerHTML += `
                <li class="mb-3">
                  <div class="d-flex align-items-start">
                    <div class="avatar avatar-sm me-2">
                      <span class="avatar-initial rounded-circle bg-label-secondary">
                        <i class="ti tabler-user"></i>
                      </span>
                    </div>
                    <div>
                      <p class="mb-1 small fw-medium">${comment.user_name}</p>
                      <p class="mb-1 badge ${badgeClass}">${comment.status}</p>
                      <p class="mb-1">${comment.comment}</p>
                      <small class="text-body-secondary">${comment.created_at}</small>
                    </div>
                    <div class="ms-auto">
                      <span class="badge bg-label-secondary">
                        <i class="ti tabler-calendar-time me-1"></i>
                        ${comment.created_diff}
                      </span>
                    </div>
                  </div>
                </li>
              `;
            });

            renderPagination();
          }

          function renderPagination() {
            const totalPages = Math.ceil(comments.length / perPage);
            const pagination = document.getElementById('comments-pagination');
            pagination.innerHTML = '';
            if (totalPages <= 1) return;

            for (let page = 1; page <= totalPages; page++) {
              pagination.innerHTML += `
                <li class="page-item ${page === currentPage ? 'active' : ''}">
                  <a class="page-link" href="#" onclick="renderComments(${page}); return false;">${page}</a>
                </li>
              `;
            }
          }

          if (comments.length > 0) {
            renderComments(1);
          }
        </script>
        @else
        <div class="text-center py-4">
          <div class="avatar avatar-lg mx-auto mb-3">
            <span class="avatar-initial rounded-circle bg-label-secondary">
              <i class="ti tabler-message-circle fs-2"></i>
            </span>
          </div>
          <h6 class="mb-1">No Comments Yet</h6>
          <p class="text-muted">Be the first to comment on this job.</p>
        </div>
        @endif
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
