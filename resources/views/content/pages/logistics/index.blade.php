@extends('layouts/layoutMaster')

@section('title', 'Jobs')

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
<!-- Logistics Statistics Cards -->
<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h4 class="mb-1">{{ $jobs->where('status', 'Open')->count() }}</h4>
            <p class="text-muted mb-0">Pending Assignment</p>
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
            <h4 class="mb-1">{{ $jobs->where('status', 'On Route')->count() }}</h4>
            <p class="text-muted mb-0">On Route</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-primary">
              <i class="ti tabler-route"></i>
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
            <h4 class="mb-1">{{ $jobs->whereNotNull('logistics')->count() }}</h4>
            <p class="text-muted mb-0">Total Assigned</p>
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
            <h4 class="mb-1">{{ $jobs->where('status', 'Vehicle Required')->count() }}</h4>
            <p class="text-muted mb-0">Vehicle Required</p>
          </div>
          <div class="avatar">
            <span class="avatar-initial rounded-circle bg-label-warning">
              <i class="ti tabler-alert-circle"></i>
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
          <th>Vehicle</th>
          <th>G/Pass Time</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Assign Logistics Modal -->
<div class="modal fade" id="assignLogisticsModal" tabindex="-1" aria-labelledby="assignLogisticsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="assignLogisticsModalLabel">
          <i class="ti tabler-truck me-2"></i>
          Assign Logistics
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="assignLogisticsForm" class="row g-3" method="POST" action="#">
          @csrf
          <input type="hidden" id="assign_job_id" name="job_id" />

          <!-- Job Information Display -->
          <div class="col-12">
            <div class="alert alert-info">
              <h6 class="mb-2">Job Information</h6>
              <p class="mb-1"><strong>Job #:</strong> <span id="assign_job_number">-</span></p>
              <p class="mb-1"><strong>Container:</strong> <span id="assign_container">-</span></p>
              <p class="mb-0"><strong>Bail #:</strong> <span id="assign_bail_number">-</span></p>
            </div>
          </div>

          <!-- Vehicle Type Selection -->
          <div class="col-12">
            <label class="form-label">Vehicle Type<span class="text-danger">*</span></label>
            <div class="row">
              <div class="col-md-6">
                <div class="form-check">
                  <input type="radio" class="form-check-input" id="fleet_vehicle" name="market_vehicle" value="no" required>
                  <label class="form-check-label" for="fleet_vehicle">
                    <i class="ti tabler-truck me-1"></i>Fleet Vehicle
                  </label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check">
                  <input type="radio" class="form-check-input" id="market_vehicle" name="market_vehicle" value="yes" required>
                  <label class="form-check-label" for="market_vehicle">
                    <i class="ti tabler-car me-1"></i>Market Vehicle
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Fleet Vehicle Selection -->
          <div class="col-md-6" id="fleet_vehicle_section" style="display: none;">
            <label for="vehicle_id" class="form-label">Select Vehicle<span class="text-danger">*</span></label>
            <select class="form-select" id="vehicle_id" name="vehicle_id">
              <option value="">Select Vehicle</option>
              @foreach($fleets as $fleet)
                <option value="{{ $fleet->id }}">{{ $fleet->name }} - {{ $fleet->registration_number }}</option>
              @endforeach
            </select>
          </div>

          <!-- Market Vehicle Details -->
          <div class="col-md-6" id="market_vehicle_section" style="display: none;">
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
            <label for="route_id" class="form-label">Route <span class="text-danger">*</span></label>
            <select class="form-select" id="route_id" name="route_id" required>
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
        <button type="submit" form="assignLogisticsForm" class="btn btn-primary">
          <i class="ti tabler-device-floppy me-1"></i>
          Update
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Mark On Route Modal -->
<div class="modal fade" id="markOnRouteModal" tabindex="-1" aria-labelledby="markOnRouteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="markOnRouteModalLabel">
          <i class="ti tabler-route me-2"></i>
          Mark Job On Route
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="markOnRouteForm" method="POST" action="#">
          @csrf
          <input type="hidden" id="on_route_job_id" name="job_id" />

          <!-- Job Information Display -->
          <div class="col-12 mb-3">
            <div class="alert alert-info">
              <h6 class="mb-2">Job Information</h6>
              <p class="mb-1"><strong>Job #:</strong> <span id="on_route_job_number">-</span></p>
              <p class="mb-0"><strong>Container:</strong> <span id="on_route_container">-</span></p>
            </div>
          </div>

          <!-- Remarks Field -->
          <div class="mb-3">
            <label for="on_route_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="on_route_remarks" name="remarks" rows="3" placeholder="Enter any remarks for marking this job on route (optional)"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="markOnRouteForm" class="btn btn-primary">
          <i class="ti tabler-route me-1"></i>
          Mark On Route
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Mark Vehicle Returned Modal -->
<div class="modal fade" id="markVehicleReturnedModal" tabindex="-1" aria-labelledby="markVehicleReturnedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="markVehicleReturnedModalLabel">
          <i class="ti tabler-arrow-back me-2"></i>
          Mark Vehicle Returned
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="markVehicleReturnedForm" method="POST" action="#">
          @csrf
          <input type="hidden" id="vehicle_returned_job_id" name="job_id" />

          <!-- Job Information Display -->
          <div class="col-12 mb-3">
            <div class="alert alert-info">
              <h6 class="mb-2">Job Information</h6>
              <p class="mb-1"><strong>Job #:</strong> <span id="vehicle_returned_job_number">-</span></p>
              <p class="mb-0"><strong>Container:</strong> <span id="vehicle_returned_container">-</span></p>
            </div>
          </div>

          <!-- Remarks Field -->
          <div class="mb-3">
            <label for="vehicle_returned_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="vehicle_returned_remarks" name="remarks" rows="3" placeholder="Enter any remarks for marking vehicle as returned (optional)"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="markVehicleReturnedForm" class="btn btn-warning">
          <i class="ti tabler-arrow-back me-1"></i>
          Mark Vehicle Returned
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
      tableTitle.innerHTML = 'Logistics Management';

      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/logistics',
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
          { data: 'container' },
          { data: 'line.name', defaultContent: '-' },
          { data: 'port.name', defaultContent: '-' },
          { data: 'logistics.vehicle.registration_number', defaultContent: '-' },
          {
            data: 'logistics.gate_time_passed',
            defaultContent: '-',
            render: function (data, type, full, meta) {
              if (!data) return '-';
              // Format the date using Intl.DateTimeFormat
              const date = new Date(data);
              return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
              }).format(date);
            },
          },
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
              // Show row number starting from 1
              return meta.row + 1;
            }
            },

          {
            targets: 2,
            responsivePriority: 1,
            render: function (data, type, full, meta) {
              const job_number = full['job_number'] || '';
              const company = (full.company && full.company.title) ? full.company.title : '';
              const rowOutput = `

                  <a href="/jobs/${full.id}">${job_number}</a>

              `;
              return rowOutput;
            }
          },
          {
            responsivePriority: 2,
            targets: 3,
            render: function (data, type, full, meta) {
              let bail = '-';
              let bail_id = null;

              if (full.bail_number) {
                // Case: bail_number is an object with bail_number + id
                if (typeof full.bail_number === 'object') {
                  bail = full.bail_number.bail_number;
                  bail_id = full.bail_number.id;
                }
                // Case: bail_number is just a string
                else {
                  bail = full.bail_number;
                  // If your API also sends bail_number_id separately, grab it
                  if (full.bail_number_id) {
                    bail_id = full.bail_number_id;
                  }
                }
              }

              return bail !== '-' && bail_id
                ? `<a href="/bail-numbers/${bail_id}/view">${bail}</a>`
                : bail;
            }
          },
          {
            responsivePriority: 3,
            targets: 4,
            render: function (data, type, full, row) {
              const container = full['container'] || '';
              return `<span class="text-truncate text-body">${container}</span>` || '-';
            }
          },
          {
            targets: 5,
          },
          {
            targets: 6
          },
          {
            targets: 7,
            responsivePriority: 5,
            render: function (data, type, full, row) {
              // Handle vehicle display for both fleet and market vehicles
              if (full.logistics) {
                if (full.logistics.market_vehicle === 'yes') {
                  return full.logistics.market_vehicle_details || '-';
                } else if (full.logistics.vehicle) {
                  return full.logistics.vehicle.registration_number || '-';
                }
              }
              return '-';
            }
          },
          {
            targets: 8
          },
          {
            responsivePriority: 4,
            targets: 9,
            render: function (data, type, row) {
              let status = data || '-';
              let badgeClass = 'bg-label-secondary';

              switch (status.toLowerCase()) {
                case 'open':
                  badgeClass = 'bg-label-warning';
                  break;
                case 'vehicle required': // Added new status
                  badgeClass = 'bg-label-warning';
                  break;
                case 'on route':
                  badgeClass = 'bg-label-primary';
                  break;
                case 'stuck on port':
                  badgeClass = 'bg-label-danger';
                  break;
                case 'vehicle returned':
                  badgeClass = 'bg-label-secondary';
                  break;
                case 'empty return':
                  badgeClass = 'bg-label-dark';
                  break;
                case 'completed':
                  badgeClass = 'bg-label-success';
                  break;
                case 'cancelled':
                  badgeClass = 'bg-label-danger';
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

              // Show different actions based on job status and logistics assignment
              if (!full.logistics) {
                // No logistics assigned - show assign button
                actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-primary assign-logistics" data-id="' + full.id + '" title="Assign Logistics"><i class="icon-base ti tabler-truck"></i></button>';
              } else {
                // Logistics assigned - show update and status change buttons
                if (full.status === 'Vehicle Required') {
                  actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-success mark-on-route" data-id="' + full.id + '" title="Mark On Route"><i class="icon-base ti tabler-route"></i></button>';
                  actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>';
                } else if (full.status === 'On Route') {
                  actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-warning mark-returned" data-id="' + full.id + '" title="Mark Vehicle Returned"><i class="icon-base ti tabler-arrow-back"></i></button>';
                }
                actions += '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>';
              }

              // Always show view button
              actions += '<a href="/jobs/' + full.id + '" class="btn btn-icon btn-sm btn-outline-secondary" title="View Job"><i class="icon-base ti tabler-eye"></i></a>';

              actions += '</div>';

              return actions;
            }
          }
        ],
        select: {
          style: 'multi',
          selector: 'td:nth-child(2)'
        },
        order: [[1, 'asc']],
        layout: {
          top2Start: {
            rowClass: 'row card-header flex-column flex-md-row border-bottom mx-0 px-3',
            features: [tableTitle]
          },
          top2End: {
            features: [
              {
                buttons: [
                  {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-4',
                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-upload icon-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                    buttons: [
                      {
                        extend: 'print',
                        text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-printer me-1"></i>Print</span>`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (inner.indexOf('<') > -1) {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(inner, 'text/html');
                                let text = '';
                                const userNameElements = doc.querySelectorAll('.user-name');
                                if (userNameElements.length > 0) {
                                  userNameElements.forEach(el => {
                                    const nameText =
                                      el.querySelector('.fw-medium')?.textContent ||
                                      el.querySelector('.d-block')?.textContent ||
                                      el.textContent;
                                    text += nameText.trim() + ' ';
                                  });
                                } else {
                                  text = doc.body.textContent || doc.body.innerText;
                                }
                                return text.trim();
                              }
                              return inner;
                            }
                          }
                        },
                        customize: function (win) {
                          win.document.body.style.color = config.colors.headingColor;
                          win.document.body.style.borderColor = config.colors.borderColor;
                          win.document.body.style.backgroundColor = config.colors.bodyBg;
                          const table = win.document.body.querySelector('table');
                          table.classList.add('compact');
                          table.style.color = 'inherit';
                          table.style.borderColor = 'inherit';
                          table.style.backgroundColor = 'inherit';
                        }
                      },
                      {
                        extend: 'csv',
                        text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-text me-1"></i>Csv</span>`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      },
                      {
                        extend: 'excel',
                        text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-spreadsheet me-1"></i>Excel</span>`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      },
                      {
                        extend: 'pdf',
                        text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-description me-1"></i>Pdf</span>`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      },
                      {
                        extend: 'copy',
                        text: `<i class="icon-base ti tabler-copy me-1"></i>Copy`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      }
                    ]
                  },
                ]
              }
            ]
          },
          topStart: {
            rowClass: 'row mx-0 px-3 my-0 justify-content-between border-bottom',
            features: [
              {
                pageLength: {
                  menu: [10, 25, 50, 100],
                  text: 'Show_MENU_entries'
                }
              }
            ]
          },
          topEnd: {
            search: {
              placeholder: ''
            }
          },
          bottomStart: {
            rowClass: 'row mx-3 justify-content-between',
            features: ['info']
          },
          bottomEnd: 'paging'
        },
        language: {
          paginate: {
            next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
            previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
            first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
            last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
          }
        },
        responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function (row) {
                const data = row.data();
                return 'Details of ' + data['job'];
              }
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
              const data = columns
                .map(function (col) {
                  return col.title !== ''
                    ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        <td>${col.title}:</td>
                        <td>${col.data}</td>
                      </tr>`
                    : '';
                })
                .join('');
              if (data) {
                const div = document.createElement('div');
                div.classList.add('table-responsive');
                const table = document.createElement('table');
                div.appendChild(table);
                table.classList.add('table');
                table.classList.add('datatables-basic');
                const tbody = document.createElement('tbody');
                tbody.innerHTML = data;
                table.appendChild(tbody);
                return div;
              }
              return false;
            }
          }
        },

      });
      // Expose DataTable instance globally for reload
      window.dt_basic = dt_basic;
      let deleteRow = null;
      let deleteJobId = null;

    }
    setTimeout(() => {
      const elementsToModify = [
        { selector: '.dt-buttons .btn', classToRemove: 'btn-secondary' },
        //{ selector: '.dt-search .form-control', classToRemove: 'form-control-sm', classToAdd: 'ms-4' },
        { selector: '.dt-length .form-select', classToRemove: 'form-select-sm' },
        { selector: '.dt-layout-table', classToRemove: 'row mt-2' },
        { selector: '.dt-layout-end', classToAdd: 'mt-0' },
        //{ selector: '.dt-layout-end .dt-search', classToAdd: 'mt-0 mt-md-6 mb-6' },
        { selector: '.dt-layout-start', classToAdd: 'mt-0' },
        { selector: '.dt-layout-end .dt-buttons', classToAdd: 'mb-0' },
        { selector: '.dt-layout-full', classToRemove: 'col-md col-12', classToAdd: 'table-responsive' }
      ];
      elementsToModify.forEach(({ selector, classToRemove, classToAdd }) => {
        document.querySelectorAll(selector).forEach(element => {
          if (classToRemove) {
            classToRemove.split(' ').forEach(className => element.classList.remove(className));
          }
          if (classToAdd) {
            classToAdd.split(' ').forEach(className => element.classList.add(className));
          }
        });
      });
    }, 100);


    let currentJobId = null;

    // Vehicle type radio button handler
    document.addEventListener('change', function(e) {
      if (e.target.name === 'market_vehicle') {
        const fleetSection = document.getElementById('fleet_vehicle_section');
        const marketSection = document.getElementById('market_vehicle_section');
        const vehicleSelect = document.getElementById('vehicle_id');
        const marketInput = document.getElementById('market_vehicle_details');

        if (e.target.value === 'no') {
          // Fleet vehicle selected - show vehicle select, hide market input
          fleetSection.style.display = 'block';
          marketSection.style.display = 'none';
          vehicleSelect.required = true;
          marketInput.required = false;
          marketInput.value = '';
        } else {
          // Market vehicle selected - hide vehicle select, show market input
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
      if (e.target.closest('.assign-logistics')) {
        const jobId = e.target.closest('.assign-logistics').dataset.id;
        assignLogistics(jobId);
      }

      if (e.target.closest('.edit-logistics')) {
        const jobId = e.target.closest('.edit-logistics').dataset.id;
        editLogistics(jobId);
      }

      if (e.target.closest('.mark-on-route')) {
        const jobId = e.target.closest('.mark-on-route').dataset.id;
        markOnRoute(jobId);
      }

      if (e.target.closest('.mark-returned')) {
        const jobId = e.target.closest('.mark-returned').dataset.id;
        markVehicleReturned(jobId);
      }
    });

    // Assign Logistics Function
    function assignLogistics(jobId) {
      // Fetch job details first
      fetch(`/logistics/${jobId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobData = data.data.job;
            const bailNumber = data.data.job.bail_number;

            // Populate job information in modal
            document.getElementById('assign_job_id').value = jobData.id;
            document.getElementById('assign_job_number').textContent = jobData.job_number || '-';
            document.getElementById('assign_container').textContent = jobData.container || '-';
            document.getElementById('assign_bail_number').textContent = bailNumber?.bail_number || '-';

            // Reset form
            document.getElementById('assignLogisticsForm').reset();
            document.getElementById('assign_job_id').value = jobData.id; // Reset clears this, so set it again

            // Hide both vehicle sections initially
            document.getElementById('fleet_vehicle_section').style.display = 'none';
            document.getElementById('market_vehicle_section').style.display = 'none';

            // Reset required attributes
            document.getElementById('vehicle_id').required = false;
            document.getElementById('market_vehicle_details').required = false;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('assignLogisticsModal'));
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

    // Edit Logistics Function (placeholder for future implementation)
    function editLogistics(jobId) {
      showToast('Info', 'Edit logistics functionality will be implemented soon', 'info');
    }

    // Mark On Route Function
    function markOnRoute(jobId) {
      // Fetch job details first
      fetch(`/logistics/${jobId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobData = data.data.job;

            // Populate job information in modal
            document.getElementById('on_route_job_id').value = jobData.id;
            document.getElementById('on_route_job_number').textContent = jobData.job_number || '-';
            document.getElementById('on_route_container').textContent = jobData.container || '-';

            // Reset form
            document.getElementById('markOnRouteForm').reset();
            document.getElementById('on_route_job_id').value = jobData.id; // Reset clears this, so set it again

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('markOnRouteModal'));
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

    // Mark Vehicle Returned Function
    function markVehicleReturned(jobId) {
      // Fetch job details first
      fetch(`/logistics/${jobId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobData = data.data.job;

            // Populate job information in modal
            document.getElementById('vehicle_returned_job_id').value = jobData.id;
            document.getElementById('vehicle_returned_job_number').textContent = jobData.job_number || '-';
            document.getElementById('vehicle_returned_container').textContent = jobData.container || '-';

            // Reset form
            document.getElementById('markVehicleReturnedForm').reset();
            document.getElementById('vehicle_returned_job_id').value = jobData.id; // Reset clears this, so set it again

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('markVehicleReturnedModal'));
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

    // Assign Logistics Form Submission
    const assignLogisticsForm = document.getElementById('assignLogisticsForm');
    if (assignLogisticsForm) {
      assignLogisticsForm.addEventListener('submit', function(e) {
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

        const formData = new FormData(assignLogisticsForm);
        const jobId = formData.get('job_id');

        // Show loading state - find the correct submit button
        const submitBtn = document.querySelector('#assignLogisticsModal button[type="submit"]') ||
                          assignLogisticsForm.querySelector('button[type="submit"]');

        let originalText = '';
        if (submitBtn) {
          originalText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="ti tabler-loader me-1"></i>Assigning...';
        }

        fetch(`/logistics/${jobId}/assign`, {
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
            bootstrap.Modal.getInstance(document.getElementById('assignLogisticsModal')).hide();
            assignLogisticsForm.reset();

            // Hide vehicle sections
            document.getElementById('fleet_vehicle_section').style.display = 'none';
            document.getElementById('market_vehicle_section').style.display = 'none';

            // Reload DataTable
            dt_basic.ajax.reload();

            showToast('Success', data.message, 'success');
          } else {
            showToast('Error', data.message || 'Failed to assign logistics', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while assigning logistics', 'error');
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

    // Mark On Route Form Submission
    const markOnRouteForm = document.getElementById('markOnRouteForm');
    if (markOnRouteForm) {
      markOnRouteForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(markOnRouteForm);
        const jobId = formData.get('job_id');
        const remarks = formData.get('remarks');

        // Show loading state
        const submitBtn = document.querySelector('#markOnRouteModal button[type="submit"]');
        let originalText = '';
        if (submitBtn) {
          originalText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="ti tabler-loader me-1"></i>Processing...';
        }

        fetch(`/logistics/${jobId}/mark-on-route`, {
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
            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('markOnRouteModal')).hide();
            markOnRouteForm.reset();

            // Reload DataTable
            dt_basic.ajax.reload();
            showToast('Success', data.message, 'success');
          } else {
            showToast('Error', data.message || 'Failed to mark job on route', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while marking job on route', 'error');
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

    // Mark Vehicle Returned Form Submission
    const markVehicleReturnedForm = document.getElementById('markVehicleReturnedForm');
    if (markVehicleReturnedForm) {
      markVehicleReturnedForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(markVehicleReturnedForm);
        const jobId = formData.get('job_id');
        const remarks = formData.get('remarks');

        // Show loading state
        const submitBtn = document.querySelector('#markVehicleReturnedModal button[type="submit"]');
        let originalText = '';
        if (submitBtn) {
          originalText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="ti tabler-loader me-1"></i>Processing...';
        }

        fetch(`/logistics/${jobId}/mark-vehicle-returned`, {
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
            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('markVehicleReturnedModal')).hide();
            markVehicleReturnedForm.reset();

            // Reload DataTable
            dt_basic.ajax.reload();
            showToast('Success', data.message, 'success');
          } else {
            showToast('Error', data.message || 'Failed to mark vehicle as returned', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while marking vehicle as returned', 'error');
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

    // Modal reset on close
    document.getElementById('assignLogisticsModal').addEventListener('hidden.bs.modal', function () {
      // Reset form and hide sections
      document.getElementById('assignLogisticsForm').reset();
      document.getElementById('fleet_vehicle_section').style.display = 'none';
      document.getElementById('market_vehicle_section').style.display = 'none';

      // Reset required attributes
      document.getElementById('vehicle_id').required = false;
      document.getElementById('market_vehicle_details').required = false;
    });

    document.getElementById('markOnRouteModal').addEventListener('hidden.bs.modal', function () {
      // Reset form
      document.getElementById('markOnRouteForm').reset();
    });

    document.getElementById('markVehicleReturnedModal').addEventListener('hidden.bs.modal', function () {
      // Reset form
      document.getElementById('markVehicleReturnedForm').reset();
    });

    // Toast notification function
    function showToast(title, message, type = 'info') {
      if (typeof window.showToast === 'function') {
        window.showToast(title, message, type);
      } else {
        alert(`${title}: ${message}`);
      }
    }

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

      // Add type field for import jobs
      formData.append('type', 'import');

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
  .vuexy_date {
    width: auto !important;
  }
</style>
@endsection
