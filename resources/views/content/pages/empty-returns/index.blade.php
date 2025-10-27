@extends('layouts/layoutMaster')

@section('title', 'Empty Returns')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js',
  'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js',
  'resources/assets/vendor/libs/pickr/pickr.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/forms-pickers.js',
])
@endsection

@section('content')
<!-- Empty Returns Statistics Cards -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h4 class="mb-1" id="totalEmptyReturns">{{ $emptyReturns->count() }}</h4>
            <p class="text-muted mb-0">Total Empty Returns</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-primary">
              <i class="ti tabler-truck-return"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h4 class="mb-1" id="pendingReturns">-</h4>
            <p class="text-muted mb-0">Pending Returns</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-warning">
              <i class="ti tabler-clock"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h4 class="mb-1" id="completedToday">-</h4>
            <p class="text-muted mb-0">Completed Today</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-success">
              <i class="ti tabler-checks"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h4 class="mb-1" id="activeVehicles">-</h4>
            <p class="text-muted mb-0">Active Vehicles</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-info">
              <i class="ti tabler-truck"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- DataTable with Buttons -->
<div class="card">
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-basic table">
      <thead>
        <tr>
          <th></th>
          <th>S.no</th>
          <th>Job #</th>
          <th>Bail #</th>
          <th>Container</th>
          <th>Line</th>
          <th>Port</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Assign Empty Return Modal -->
<div class="modal fade" id="assignEmptyReturnModal" tabindex="-1" aria-labelledby="assignEmptyReturnModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="assignEmptyReturnModalLabel">
          <i class="ti tabler-truck-return me-2"></i>
          Assign Empty Return
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="assignEmptyReturnForm" class="row g-3" method="POST" action="#">
          @csrf
          <input type="hidden" id="assign_job_id" name="job_id" />

          <!-- Job Information Display -->
          <div class="col-12">
            <div class="alert alert-info">
              <h6 class="mb-2">Job Information</h6>
              <p class="mb-1"><strong>Job #:</strong> <span id="assign_job_number">-</span></p>
              <p class="mb-1"><strong>Container:</strong> <span id="assign_container">-</span></p>
              <p class="mb-0"><strong>Status:</strong> <span id="assign_status">-</span></p>
            </div>
          </div>

          <!-- Vehicle Type Selection -->
          <div class="col-12">
            <label class="form-label">Vehicle Type<span class="text-danger">*</span></label>
            <div class="row">
              <div class="col-md-6">
                <div class="form-check">
                  <input type="radio" class="form-check-input" id="fleet_vehicle_return" name="market_vehicle" value="no" required>
                  <label class="form-check-label" for="fleet_vehicle_return">
                    <i class="ti tabler-truck me-1"></i>Fleet Vehicle
                  </label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check">
                  <input type="radio" class="form-check-input" id="market_vehicle_return" name="market_vehicle" value="yes" required>
                  <label class="form-check-label" for="market_vehicle_return">
                    <i class="ti tabler-car me-1"></i>Market Vehicle
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Fleet Vehicle Selection -->
          <div class="col-md-6" id="fleet_vehicle_return_section" style="display: none;">
            <label for="vehicle_id" class="form-label">Select Vehicle<span class="text-danger">*</span></label>
            <select class="form-select" id="vehicle_id" name="vehicle_id">
              <option value="">Select Vehicle</option>
              @foreach($fleets as $fleet)
                <option value="{{ $fleet->id }}">{{ $fleet->name }} - {{ $fleet->registration_number }}</option>
              @endforeach
            </select>
          </div>

          <!-- Market Vehicle Details -->
          <div class="col-md-6" id="market_vehicle_return_section" style="display: none;">
            <label for="market_vehicle_details" class="form-label">Vehicle Details<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="market_vehicle_details" name="market_vehicle_details" placeholder="Enter vehicle registration/details">
          </div>

          <!-- Gate Pass -->
          <div class="col-md-6">
            <label for="gate_pass" class="form-label">Gate Pass<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="gate_pass" name="gate_pass" placeholder="Enter Gate Pass Number" required>
          </div>

          <!-- Route -->
          <div class="col-md-6">
            <label for="route_id" class="form-label">Route</label>
            <select class="form-select" id="route_id" name="route_id">
              <option value="">Select Route</option>
              @foreach($routes as $route)
                <option value="{{ $route->id }}">{{ $route->route_name }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="assignEmptyReturnForm" class="btn btn-primary">
          <i class="ti tabler-device-floppy me-1"></i>
          Assign Empty Return
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Mark Completed Modal -->
<div class="modal fade" id="markCompletedModal" tabindex="-1" aria-labelledby="markCompletedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="markCompletedModalLabel">
          <i class="ti tabler-checks me-2"></i>
          Mark Job Completed
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="markCompletedForm" method="POST" action="#">
          @csrf
          <input type="hidden" id="completed_job_id" name="job_id" />

          <!-- Job Information Display -->
          <div class="col-12 mb-3">
            <div class="alert alert-info">
              <h6 class="mb-2">Job Information</h6>
              <p class="mb-1"><strong>Job #:</strong> <span id="completed_job_number">-</span></p>
              <p class="mb-0"><strong>Container:</strong> <span id="completed_container">-</span></p>
            </div>
          </div>

          <!-- Remarks Field -->
          <div class="mb-3">
            <label for="completed_remarks" class="form-label">Completion Remarks</label>
            <textarea class="form-control" id="completed_remarks" name="remarks" rows="3" placeholder="Enter any remarks for job completion (optional)"></textarea>
          </div>

          <!-- Add Extra Route Button -->
          <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-outline-primary" id="btnAddExtraRoute">
              <i class="ti tabler-route me-1"></i>
              Add Another Route
            </button>
          </div>

          <!-- Extra Route Section (initially hidden) -->
          <div id="extraRouteSection" class="border rounded p-3 mb-3" style="display: none;">
            <h6 class="mb-3">Additional Route</h6>

            <div class="mb-3">
              <label for="extra_route_id" class="form-label">Select Route <span class="text-danger">*</span></label>
              <select class="form-select" id="extra_route_id" name="extra_route_id">
                <option value="">Select Route</option>
                @foreach($routes as $route)
                  <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label for="extra_route_reason" class="form-label">Reason <span class="text-danger">*</span></label>
              <textarea class="form-control" id="extra_route_reason" name="extra_route_reason" rows="2"
                placeholder="Explain why this additional route is needed"></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-primary" id="btnSaveExtraRoute">
                <i class="ti tabler-device-floppy me-1"></i>
                Save Route
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary" id="btnCancelExtraRoute">
                <i class="ti tabler-x me-1"></i>
                Cancel
              </button>
            </div>
          </div>

          <!-- Added Extra Routes List -->
          <div id="extraRoutesListContainer" class="mb-3" style="display: none;">
            <h6 class="mb-2">Added Routes:</h6>
            <ul class="list-group" id="extraRoutesList"></ul>
          </div>

          <!-- Hidden input to store extra routes JSON -->
          <input type="hidden" id="extra_routes_data" name="extra_routes_data" value="[]">
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="markCompletedForm" class="btn btn-success">
          <i class="ti tabler-checks me-1"></i>
          Mark Completed
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commentsModalLabel">Comments for Status: <span id="statusLabel"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCommentForm">
          @csrf
          <input type="hidden" id="jobQueueId" name="job_id">
          <input type="hidden" id="status" name="status">
          <input type="hidden" name="type" value="import">
          <div class="mb-3">
            <label for="comment" class="form-label">Add Comment</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Enter your comment..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />

<script>
  'use strict';

  let dt_basic;
  document.addEventListener('DOMContentLoaded', function (e) {

    // Initialize DataTable
    const dt_basic_table = document.querySelector('.datatables-basic');
    if (dt_basic_table) {
      let tableTitle = document.createElement('h5');
      tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
      tableTitle.innerHTML = 'Empty Returns Management';

      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/empty-returns',
          type: 'GET',
          contentType: 'application/json',
          dataSrc: function(json) {
            return json.data || json;
          }
        },
        columns: [
          {data:'id'},
          { data: '', orderable: true, },
          { data: 'job_number', defaultContent: '-' },
          { data: 'bailNumber.bail_number', defaultContent: '-' },
          { data: 'container', defaultContent: '-' },
          { data: 'line.name', defaultContent: '-' },
          { data: 'port.name', defaultContent: '-' },
          { data: 'status', defaultContent: '-' },
          { data: 'id' },
        ],
        columnDefs: [
          {
            className: 'control',
            orderable: false,
            searchable: false,
            responsivePriority: 0,
            targets: 0,
            render: function (data, type, full, meta) {
              return '';
            }
          },
          {
            targets: 1,
            orderable: false,
            searchable: false,
            responsivePriority: 1,
            render: function (data, type, full, meta) {
              return meta.row + 1;
            }
          },
          {
            targets: 2,
            responsivePriority: 1,
            render: function (data, type, full, meta) {
              const job_number = full.job_number || '-';
              return `<span class="fw-medium">${job_number}</span>`;
            }
          },
          {
            targets: 3,
            responsivePriority: 2,
            render: function (data, type, full, meta) {
              let bail = '-';
              let bail_id = null;

              if (full.bail_number) {
                if (typeof full.bail_number === 'object') {
                  bail = full.bail_number.bail_number;
                  bail_id = full.bail_number.id;
                }
              }

              return bail !== '-' && bail_id
                ? `<a href="/bails/${bail_id}/view">${bail}</a>`
                : bail;
            }
          },
          {
            targets: 7,
            render: function (data, type, row) {
              let status = data || '-';
              let badgeClass = 'bg-label-secondary';

              switch (status.toLowerCase()) {
                case 'vehicle returned':
                  badgeClass = 'bg-label-warning';
                  break;
                case 'empty return':
                  badgeClass = 'bg-label-info';
                  break;
                case 'completed':
                  badgeClass = 'bg-label-success';
                  break;
                default:
                  badgeClass = 'bg-label-secondary';
              }

              return `<span class="badge ${badgeClass}">${status}</span>`;
            }
          },
          {
            targets: -1,
            responsivePriority: 3,
            title: 'Actions',
            orderable: false,
            searchable: false,
            render: function (data, type, full, meta) {
              let actions = '<div class="d-flex gap-1">';

              // Always show view button
              actions += '<a href="/jobs/' + full.id + '" class="btn btn-icon btn-sm btn-outline-secondary" title="View Job"><i class="icon-base ti tabler-eye"></i></a>';

              // Show Empty Return button if status is Vehicle Returned
              if (full.status === 'Vehicle Returned') {
                actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-primary assign-empty-return" data-id="' + full.id + '" title="Assign Empty Return"><i class="icon-base ti tabler-truck-return"></i></button>';
                actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>';

              }

              // Show Mark Completed button if status is Empty Return
              if (full.status === 'Empty Return') {
                actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-success mark-completed" data-id="' + full.id + '" title="Mark Completed"><i class="icon-base ti tabler-checks"></i></button>';
                actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>';

              }

              actions += '</div>';
              return actions;
            }
          }
        ],
        order: [[1, 'desc']],
        // ...existing layout configuration...
      });

      window.dt_basic = dt_basic;
    }


    // Vehicle type radio button handler
    document.addEventListener('change', function(e) {
      if (e.target.name === 'market_vehicle') {
        const fleetSection = document.getElementById('fleet_vehicle_return_section');
        const marketSection = document.getElementById('market_vehicle_return_section');
        const vehicleSelect = document.getElementById('vehicle_id');
        const marketInput = document.getElementById('market_vehicle_details');

        if (e.target.value === 'no') {
          fleetSection.style.display = 'block';
          marketSection.style.display = 'none';
          vehicleSelect.required = true;
          marketInput.required = false;
          marketInput.value = '';
        } else {
          fleetSection.style.display = 'none';
          marketSection.style.display = 'block';
          vehicleSelect.required = false;
          marketInput.required = true;
          vehicleSelect.value = '';
        }
      }
    });

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.assign-empty-return')) {
        const jobId = e.target.closest('.assign-empty-return').dataset.id;
        assignEmptyReturn(jobId);
      }

      if (e.target.closest('.mark-completed')) {
        const jobId = e.target.closest('.mark-completed').dataset.id;
        markCompleted(jobId);
      }
    });

    // Assign Empty Return Function
    function assignEmptyReturn(jobId) {
      // Fetch job details first
      fetch(`/empty-returns/${jobId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobData = data.data;

            // Populate job information in modal
            document.getElementById('assign_job_id').value = jobData.id;
            document.getElementById('assign_job_number').textContent = jobData.job_number || '-';
            document.getElementById('assign_container').textContent = jobData.container || '-';
            document.getElementById('assign_status').textContent = jobData.status || '-';

            // Reset form
            document.getElementById('assignEmptyReturnForm').reset();
            document.getElementById('assign_job_id').value = jobData.id;

            // Hide both vehicle sections initially
            document.getElementById('fleet_vehicle_return_section').style.display = 'none';
            document.getElementById('market_vehicle_return_section').style.display = 'none';

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('assignEmptyReturnModal'));
            modal.show();
          } else {
            showToast('Error', 'Failed to load job details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading job details', 'error');
        });
    }

    function markCompleted(jobId) {
      // Fetch job details first
      fetch(`/empty-returns/${jobId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobData = data.data;

            // Populate job information in modal
            document.getElementById('completed_job_id').value = jobData.id;
            document.getElementById('completed_job_number').textContent = jobData.job_number || '-';
            document.getElementById('completed_container').textContent = jobData.container || '-';

            // Reset form
            document.getElementById('markCompletedForm').reset();
            document.getElementById('completed_job_id').value = jobData.id;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('markCompletedModal'));
            modal.show();
          } else {
            showToast('Error', 'Failed to load job details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading job details', 'error');
        });
    }

    // Assign Empty Return Form Submission
    const assignEmptyReturnForm = document.getElementById('assignEmptyReturnForm');
    if (assignEmptyReturnForm) {
      assignEmptyReturnForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate vehicle selection based on type
        const marketVehicle = document.querySelector('input[name="market_vehicle"]:checked');
        const vehicleId = document.getElementById('vehicle_id').value;
        const marketVehicleDetails = document.getElementById('market_vehicle_details').value;
        const gatePass = document.getElementById('gate_pass').value.trim();

        // Validation checks
        if (!marketVehicle) {
          showToast('Error', 'Please select vehicle type (Fleet or Market)', 'error');
          return;
        }

        if (marketVehicle.value === 'no' && !vehicleId) {
          showToast('Error', 'Please select a fleet vehicle', 'error');
          return;
        }

        if (marketVehicle.value === 'yes' && !marketVehicleDetails.trim()) {
          showToast('Error', 'Please enter market vehicle details', 'error');
          return;
        }

        if (!gatePass) {
          showToast('Error', 'Please enter gate pass number', 'error');
          return;
        }

        const formData = new FormData(assignEmptyReturnForm);
        const jobId = formData.get('job_id');

        // Show loading state
        const submitBtn = document.querySelector('#assignEmptyReturnModal button[type="submit"]');
        let originalText = '';
        if (submitBtn) {
          originalText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="ti tabler-loader me-1"></i>Assigning...';
        }

        fetch(`/empty-returns/${jobId}/store`, {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('assignEmptyReturnModal')).hide();
            assignEmptyReturnForm.reset();

            // Hide vehicle sections
            document.getElementById('fleet_vehicle_return_section').style.display = 'none';
            document.getElementById('market_vehicle_return_section').style.display = 'none';

            // Reload DataTable
            dt_basic.ajax.reload();
            loadStatistics();

            showToast('Success', data.message, 'success');
          } else {
            showToast('Error', data.message || 'Failed to assign empty return', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while assigning empty return', 'error');
        })
        .finally(() => {
          // Reset button state
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
      });
    }

    // Mark Completed Form Submission
    const markCompletedForm = document.getElementById('markCompletedForm');
    if (markCompletedForm) {
      markCompletedForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(markCompletedForm);
        const jobId = formData.get('job_id');
        const remarks = formData.get('remarks');

        const submitBtn = document.querySelector('#markCompletedModal button[type="submit"]');
        let originalText = '';
        if (submitBtn) {
          originalText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="ti tabler-loader me-1"></i>Processing...';
        }

        fetch(`/empty-returns/${jobId}/mark-completed`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            remarks: remarks
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('markCompletedModal')).hide();
            markCompletedForm.reset();
            dt_basic.ajax.reload();
            loadStatistics();
            showToast('Success', data.message, 'success');
          } else {
            showToast('Error', data.message || 'Failed to mark as completed', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred', 'error');
        })
        .finally(() => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
      });
    }

    // Modal reset on close
    document.getElementById('assignEmptyReturnModal').addEventListener('hidden.bs.modal', function () {
      // Reset form and hide sections
      document.getElementById('assignEmptyReturnForm').reset();
      document.getElementById('fleet_vehicle_return_section').style.display = 'none';
      document.getElementById('market_vehicle_return_section').style.display = 'none';

      // Reset required attributes
      document.getElementById('vehicle_id').required = false;
      document.getElementById('market_vehicle_details').required = false;
    });

    document.getElementById('markCompletedModal').addEventListener('hidden.bs.modal', function () {
      // Reset form
      document.getElementById('markCompletedForm').reset();
    });

    // Open comments modal
    document.addEventListener('click', function (e) {
      const commentsBtn = e.target.closest('.add-comments-btn');
      if (commentsBtn) {
        const jobQueueId = commentsBtn.dataset.jobQueueId;
        const status = commentsBtn.dataset.status;

        // Set modal title and hidden inputs
        document.getElementById('statusLabel').textContent = status;
        document.getElementById('jobQueueId').value = jobQueueId;
        document.getElementById('status').value = status;


        // Show modal
        const commentsModal = new bootstrap.Modal(document.getElementById('commentsModal'));
        commentsModal.show();
      }
    });


    // Handle add comment form submission
    document.getElementById('addCommentForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch('/job-comments', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showToast('Success', 'Comment added successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('commentsModal'));
            modal.hide();
            // Clear comment input
            document.getElementById('comment').value = '';
          } else {
            showToast('Error', data.message || 'Failed to add comment.', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while adding the comment.', 'error');
        });
    });

  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    let extraRoutes = [];

    const markCompletedModal = document.getElementById('markCompletedModal');

    markCompletedModal.addEventListener('shown.bs.modal', function () {
      // Elements inside modal
      const btnAddExtraRoute = document.getElementById('btnAddExtraRoute');
      const btnSaveExtraRoute = document.getElementById('btnSaveExtraRoute');
      const btnCancelExtraRoute = document.getElementById('btnCancelExtraRoute');
      const extraRouteSection = document.getElementById('extraRouteSection');
      const extraRoutesListContainer = document.getElementById('extraRoutesListContainer');
      const extraRoutesList = document.getElementById('extraRoutesList');
      const extraRoutesDataInput = document.getElementById('extra_routes_data');
      const completedJobId = document.getElementById('completed_job_id').value;

      // ✅ Reset
      extraRouteSection.style.display = 'none';
      extraRoutesList.innerHTML = '';
      extraRoutes = [];

      // ✅ Fetch existing extra routes for this job
      fetch(`/empty-returns/${completedJobId}/extra-routes`)
        .then(response => response.json())
        .then(data => {
          if (data.success && Array.isArray(data.routes)) {
            extraRoutes = data.routes.map(route => ({
              id: route.id,
              route_id: route.route_id,
              route_name: route.route?.route_name || `Route #${route.route_id}`,
              reason: route.reason || 'No reason provided',
            }));
            renderExtraRoutes();
          }
        })
        .catch(error => console.error('Error fetching existing routes:', error));

      // ✅ Show Add Route Section
      btnAddExtraRoute.onclick = () => {
        extraRouteSection.style.display = 'block';
      };

      // ✅ Save Route
      btnSaveExtraRoute.onclick = () => {
        const routeId = document.getElementById('extra_route_id').value;
        const routeReason = document.getElementById('extra_route_reason').value.trim();

        if (!routeId || !routeReason) {
          alert('Please select a route and provide a reason.');
          return;
        }

        // Send data via AJAX
        fetch(`/empty-returns/${completedJobId}/add-extra-route`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify({
            extra_route_id: routeId,
            extra_route_reason: routeReason,
          }),
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const routeName = document.querySelector(`#extra_route_id option[value="${routeId}"]`).textContent;
              extraRoutes.push({
                route_id: routeId,
                route_name: routeName,
                reason: routeReason,
              });

              extraRoutesDataInput.value = JSON.stringify(extraRoutes);
              renderExtraRoutes();

              // Reset form
              extraRouteSection.style.display = 'none';
              document.getElementById('extra_route_id').value = '';
              document.getElementById('extra_route_reason').value = '';
              alert('Route added successfully!');
            } else {
              alert('Failed to add route: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the route.');
          });
      };

      // ✅ Cancel Add
      btnCancelExtraRoute.onclick = () => {
        extraRouteSection.style.display = 'none';
        document.getElementById('extra_route_id').value = '';
        document.getElementById('extra_route_reason').value = '';
      };

      // ✅ Render Route List (no delete buttons)
      function renderExtraRoutes() {
        extraRoutesList.innerHTML = '';

        if (extraRoutes.length === 0) {
          extraRoutesListContainer.style.display = 'none';
          return;
        }

        extraRoutesListContainer.style.display = 'block';
        extraRoutes.forEach(route => {
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-center';
          li.innerHTML = `
            <span><strong>${route.route_name}</strong> - ${route.reason}</span>
          `;
          extraRoutesList.appendChild(li);
        });
      }
    });
  });
</script>

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
