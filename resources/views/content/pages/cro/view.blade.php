@extends('layouts/layoutMaster')

@section('title', 'CRO Details - ' . $cro->cro_number)

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
<!-- CRO Details Header -->
<div class="row">
  <!-- CRO Information Card -->
  <div class="col-md-4 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar avatar-xl mx-auto mb-3">
          <span class="avatar-initial rounded-circle bg-label-primary">
            <i class="ti tabler-receipt fs-2"></i>
          </span>
        </div>
        <h4 class="mb-1">{{ $cro->cro_number }}</h4>
        <p class="text-muted mb-3">
          <span class="badge bg-label-{{ $cro->status === 'active' ? 'success' : 'secondary' }} ms-1">
            {{ ucfirst($cro->status) }}
          </span>
        </p>

        <!-- CRO Stats -->
        <div class="row text-center">
          <div class="col-6">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-info">
                  <i class="ti tabler-briefcase"></i>
                </span>
              </div>
              <div>
                <h5 class="mb-0">{{ $jobsCount }}</h5>
                <small class="text-muted">Total Jobs</small>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-warning">
                  <i class="ti tabler-clock"></i>
                </span>
              </div>
              <div>
                <h5 class="mb-0">{{ $activeJobsCount }}</h5>
                <small class="text-muted">Active Jobs</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CRO Details Card -->
  <div class="col-md-8 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-info-circle me-1"></i>
          CRO Information
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-medium">CRO Number</label>
            <p class="mb-0">{{ $cro->cro_number }}</p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Status</label>
            <p class="mb-0">
              <span class="badge bg-label-{{ $cro->status === 'active' ? 'success' : 'secondary' }}">
                {{ ucfirst($cro->status) }}
              </span>
            </p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Created Date</label>
            <p class="mb-0">{{ $cro->created_at ? $cro->created_at->format('M d, Y') : 'N/A' }}</p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Last Updated</label>
            <p class="mb-0">{{ $cro->updated_at ? $cro->updated_at->format('M d, Y') : 'N/A' }}</p>
          </div>
          <div class="col-12">
            <label class="form-label fw-medium">Description</label>
            <p class="mb-0">{{ $cro->description ?? 'No description provided' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Export Jobs Table -->
<div class="card">
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table">
        <thead>
          <tr>
            <th></th>
            <th>S.no</th>
            <th>CRO #</th>
            <th>Size</th>
            <th>Line</th>
            <th>Vehicle</th>
            <th>G/Pass Time</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />

<!--create new job modal -->
<div class="modal fade" id="createJobModal" tabindex="-1" aria-labelledby="createJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" >
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="createJobModalLabel">Create New Job</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="jobForm" class="row g-3" method="POST" action="#">
          @csrf
          <!-- CRO ID -->
          <div class="col-md-12">
            <label for="cro_id" class="form-label">CRO Number<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="cro_id" name="cro_id" required>
                <option value="">Select CRO</option>
                @foreach($cros as $cro)
                  <option value="{{ $cro->id }}">{{ $cro->cro_number }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div id="containersWrapper" >
            <div class="container-row col-12 row g-3 border mb-3 mt-2 p-3 rounded">
              <div class="col-md-12 text-end">
                <button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect remove-container-row" style="display:none;"><i class="icon-base ti tabler-trash"></i></button>
              </div>
              <!-- Job Details Row -->
              <div class="col-md-3">
                <label for="container_0" class="form-label">Container<span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control" id="container_0" name="containers[0][container]" placeholder="Enter Container Number" required />
                </div>
              </div>
              <div class="col-md-3">
                <label for="size_0" class="form-label">Size<span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="number" class="form-control" id="size_0" name="containers[0][size]" placeholder="Enter Size" value="20" required />
                </div>
              </div>
              <div class="col-md-3">
                <label for="line_id_0" class="form-label">Line<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="line_id_0" name="containers[0][line_id]" required>
                    <option value="">Select Line</option>
                    @foreach($lines as $line)
                      <option value="{{ $line->id }}">{{ $line->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for="pod_0" class="form-label">POD<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="pod_0" name="containers[0][pod_id]" required>
                    <option value="">Select POD</option>
                    @foreach($pods as $pod)
                      <option value="{{ $pod->id }}">{{ $pod->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for="terminal_0" class="form-label">Terminal<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="terminal_0" name="containers[0][terminal_id]" required>
                    <option value="">Select Terminal</option>
                    @foreach($terminals as $terminal)
                      <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for="empty_pickup_0" class="form-label">Empty Pickup Location<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="empty_pickup_0" name="containers[0][empty_pickup]" required>
                    <option value="">Select Empty Pickup Location</option>
                    @foreach($emptypickupLocations as $location)
                      <option value="{{ $location->id }}">{{ $location->title }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <label for="forwarder_0" class="form-label">Forwarder<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="forwarder_0" name="containers[0][forwarder_id]" required>
                    <option value="">Select Forwarder</option>
                    @foreach($forwarders as $forwarder)
                      <option value="{{ $forwarder->id }}">{{ $forwarder->title }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <label for="status_0" class="form-label">Status<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="status_0" name="containers[0][status]" required>
                    <option value="">Select Status</option>
                    <option value="Open" selected>Open</option>
                    <option value="In Progress">In Progress</option>
                    <option value="On Route">On Route</option>
                    <option value="Stuck On Port">Stuck On Port</option>
                    <option value="Vehicle Returned">Vehicle Returned</option>
                    <option value="Empty Return">Empty Return</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <button type="button" class="btn btn-primary btn-sm" id="addContainerRow">
              <i class="ti tabler-plus"></i> Add Container
            </button>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="jobForm" class="btn btn-primary">
          <i class="ti tabler-device-floppy me-1"></i>
          Save Job
        </button>
      </div>
    </div>
  </div>
</div>


<!-- Edit job Modal -->
<div class="modal fade" id="editJobModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Job
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="EditJobForm" class="row g-3">
          @csrf
          <div class="col-md-12">
            <label for="edit_cro_id" class="form-label">CRO Number<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_cro_id" name="cro_id" required>
                <option value="">Select CRO</option>
                @foreach($cros as $cro)
                  <option value="{{ $cro->id }}">{{ $cro->cro_number }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <!-- Job Details Row -->
          <div class="col-md-6">
            <label for="edit_container" class="form-label">Container</label>
            <div class="input-group">
              <input type="text" class="form-control" id="edit_container" name="container" placeholder="Enter Container Number" />
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_size" class="form-label">Size<span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="number" class="form-control" id="edit_size" name="size" placeholder="Enter Size" required />
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_line_id" class="form-label">Line<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_line_id" name="line_id" required>
                <option value="">Select Line</option>
                @foreach($lines as $line)
                  <option value="{{ $line->id }}">{{ $line->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_forwarder" class="form-label">Forwarder<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_forwarder" name="forwarder_id" required>
                <option value="">Select Forwarder</option>
                @foreach($forwarders as $forwarder)
                  <option value="{{ $forwarder->id }}">{{ $forwarder->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_pod" class="form-label">POD<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_pod" name="pod_id" required>
                <option value="">Select POD</option>
                @foreach($pods as $pod)
                  <option value="{{ $pod->id }}">{{ $pod->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_terminal" class="form-label">Terminal<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_terminal" name="terminal_id" required>
                <option value="">Select Terminal</option>
                @foreach($terminals as $terminal)
                  <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_empty_pickup" class="form-label">Empty Pickup Location<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_empty_pickup" name="empty_pickup" required>
                <option value="">Select Empty Pickup Location</option>
                @foreach($emptypickupLocations as $location)
                  <option value="{{ $location->id }}">{{ $location->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_status" class="form-label">Status<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_status" name="status" required>
                <option value="">Select Status</option>
                <option value="Open">Open</option>
                <option value="In Progress">In Progress</option>
                <option value="On Route">On Route</option>
                <option value="Stuck On Port">Stuck On Port</option>
                <option value="Vehicle Returned">Vehicle Returned</option>
                <option value="Empty Return">Empty Return</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="EditJobForm" class="btn btn-primary">
          <i class="ti tabler-device-floppy me-1"></i>
          Update Job
        </button>
      </div>
    </div>
  </div>
</div>



<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this record?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
          <input type="hidden" id="jobQueueId" name="jobs_queue_id">
          <input type="hidden" id="status" name="status">
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    let containerIndex = 1;
    const addBtn = document.getElementById('addContainerRow');
    const wrapper = document.getElementById('containersWrapper');

    addBtn.addEventListener('click', function () {
      const firstRow = wrapper.querySelector('.container-row');
      const newRow = firstRow.cloneNode(true);

      // Update all input/select IDs and names
      newRow.querySelectorAll('input, select, label').forEach(function (el) {
        if (el.hasAttribute('id')) {
          const baseId = el.getAttribute('id').replace(/_\d+$/, '');
          el.setAttribute('id', baseId + '_' + containerIndex);
        }
        if (el.hasAttribute('name')) {
          const baseName = el.getAttribute('name').replace(/\[\d+\]/, '[' + containerIndex + ']');
          el.setAttribute('name', baseName);
        }
        if (el.tagName === 'LABEL' && el.hasAttribute('for')) {
          const baseFor = el.getAttribute('for').replace(/_\d+$/, '');
          el.setAttribute('for', baseFor + '_' + containerIndex);
        }
        if (el.tagName === 'INPUT') {
          el.value = '';
        }
        if (el.tagName === 'SELECT') {
          el.selectedIndex = 0;
        }
      });

      // Show remove button
      newRow.querySelector('.remove-container-row').style.display = 'inline-block';

      wrapper.appendChild(newRow);
      (function () {
      // Initialize Flatpickr on all inputs with class "vuexy_date"
      const dateInputs = document.querySelectorAll('.vuexy_date');

      dateInputs.forEach(function (input) {
        input.flatpickr({
          enableTime: false,
          monthSelectorType: 'static',
          static: true,
          dateFormat: 'm/d/Y',
          onChange: function () {
            if (typeof fv !== 'undefined') {
              fv.revalidateField(input.name); // Revalidate each field by its name
            }
          }
        });
      });
    })();
      containerIndex++;
    });

    wrapper.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove-container-row')) {
        e.target.closest('.container-row').remove();
      }
    });
  });
</script>

<script>
  'use strict';



  let fv, offCanvasEl;
  document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
      // Initialize Flatpickr on all inputs with class "vuexy_date"
      const dateInputs = document.querySelectorAll('.vuexy_date');

      dateInputs.forEach(function (input) {
        input.flatpickr({
          enableTime: false,
          monthSelectorType: 'static',
          static: true,
          dateFormat: 'm/d/Y',
          onChange: function () {
            if (typeof fv !== 'undefined') {
              fv.revalidateField(input.name); // Revalidate each field by its name
            }
          }
        });
      });
    })();

    // init function
    const dt_basic_table = document.querySelector('.datatables-basic');
    let dt_basic;
    if (dt_basic_table) {
      let tableTitle = document.createElement('h5');
      tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
      tableTitle.innerHTML = 'Export Jobs';
      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/cros/{{ $cro->id }}/jobs-data',
          type: 'GET', 
          contentType: 'application/json',
          dataSrc: function(json) {
            return json.data || json;
          }
        },
        columns: [
          {data:'id'},
          { data: '', orderable: true, },
          { data: 'cro.cro_number', defaultContent: '-' },
          { data: 'size' },
          { data: 'line.name', defaultContent: '-' },
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
            responsivePriority: 2,
            targets: 2,
            render: function (data, type, full, meta) {
              let cro = '-';
              let cro_id = null;

              if (full.cro) {
                // Case: cro is an object with cro + id
                if (typeof full.cro === 'object') {
                  cro = full.cro.cro_number || '-';
                  cro_id = full.cro.id;
                }
                // Case: cro is just a string
                else {
                  cro = full.cro_number || '-';
                  // If your API also sends cro_id separately, grab it
                  if (full.cro_id) {
                    cro_id = full.cro_id;
                  }
                }
              }

              return cro !== '-' && cro_id
                ? `<a href="/cros/${cro_id}/view">${cro}</a>`
                : cro;
            }
          },
          {
            responsivePriority: 3,
            targets: 3,
            render: function (data, type, full, row) {
              return `<span class="text-truncate text-body">${data || '-'}</span>`;
            }
            },
            {
              targets: 4,
            },
            {
              targets: 5
            },
            {
              targets: 6,
              responsivePriority: 5,
            },
            // {
            //   targets: 7
            // },
            {
            responsivePriority: 4,
            targets: 7,
            render: function (data, type, row) {
              let status = data ? data : '-';
              let badgeClass = 'bg-label-secondary';

              switch (status) {
              case 'open':
                badgeClass = 'bg-label-primary';
                break;
              case 'in progress':
                badgeClass = 'bg-label-warning';
                break;
              case 'on route':
                badgeClass = 'bg-label-info';
                break;
              case 'stuck on port':
                badgeClass = 'bg-label-danger';
                break;
              case 'vehicle returned':
                badgeClass = 'bg-label-dark';
                break;
              case 'empty return':
                badgeClass = 'bg-label-secondary';
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

              return `<span class="badge ${badgeClass}">${data || '-'}</span>`;
            }
            },
            {
            targets: -1,
            responsivePriority: 3,
            title: 'Actions',
            orderable: false,
            searchable: false,
            render: function (data, type, full, meta) {
              const status = (full.status || '').toLowerCase();
              const showActions = status === 'open' || status === 'in progress';
              return (
                '<div class="d-flex gap-1">' +
                '<a href="/export-jobs/' + full.id + '" class="btn btn-icon btn-sm btn-outline-secondary waves-effect"><i class="icon-base ti tabler-eye"></i></a>' +
                (showActions
                  ? '<button type="button" class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit"><i class="icon-base ti tabler-pencil"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="' + full.id + '"><i class="icon-base ti tabler-trash"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>'
                    : ''
                ) +
                '</div>'
              );
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
                  {
                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add New Job</span></span>',
                    className: 'create-new btn btn-primary',
                    attr: {
                      'data-bs-toggle': 'modal',
                      'data-bs-target': '#createJobModal'
                    }
                  }
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
      document.addEventListener('click', async function (e) {
        const deleteBtn = e.target.closest('.delete-record');
        if (deleteBtn) {
          deleteRow = deleteBtn.closest('tr');
          deleteJobId = deleteBtn.getAttribute('data-id');
          if (!deleteJobId) return alert('Job ID not found.');
          const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
          modal.show();
        }
      });
      document.getElementById('confirmDeleteBtn').addEventListener('click', async function () {
        if (!deleteRow || !deleteJobId) return;
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
        modal.hide();
        try {
          const response = await fetch(`/export-jobs/${deleteJobId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          if (response.ok) {
            dt_basic.row(deleteRow).remove().draw();

            const modalEl = document.querySelector('.dtr-bs-modal');
            if (modalEl && modalEl.classList.contains('show')) {
              const modal = bootstrap.Modal.getInstance(modalEl);
              modal?.hide();
            }

            // ✅ Always trigger toast here
            showToast('Success', 'Job deleted successfully!', 'success');

            // Refresh DataTable if needed
            window.dt_basic.ajax.reload();
          } else {
            const error = await response.json();
            showToast('Error', 'Failed to delete the job.', 'error');
          }
        } catch (err) {
          console.error('Delete error:', err);
          showToast('Error', 'An error occurred while deleting.', 'error');
        }
        deleteRow = null;
        deleteJobId = null;
      });
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

    // AJAX store for job form
    const jobForm = document.getElementById('jobForm');
    if (jobForm) {
      jobForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(jobForm);
        jobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        fetch('/export-jobs', {
          method: 'POST',
          contentType: 'application/json',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
          if (status === 200 || status === 201) {
            showToast('Success',  'Job created successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('createJobModal'));
            modal.hide();
            jobForm.reset();
            window.dt_basic.ajax.reload();
          } else if (status === 422 && body.errors) {
            Object.keys(body.errors).forEach(field => {
              const input = jobForm.querySelector(`[name="${field}"]`);
              if (input) input.classList.add('is-invalid');
            });
            const errorMessages = Object.values(body.errors).flat().join('<br>');
            showToast('Error', 'Please fix the errors in the form.' + errorMessages, 'error');
          } else {
            showToast('Error', 'An error occurred while creating the job.', 'error');
          }
        })
        .catch(() => {
          showToast('Error', 'Network error. Please try again.', 'error');
        });
      });
    }

    //edit Job fetch
    const editJobForm = document.getElementById('EditJobForm');
    if (editJobForm) {
      let currentJobId = null;
      document.addEventListener('click', async function (e) {
        const editBtn = e.target.closest('.item-edit');
        if (editBtn) {
          const row = editBtn.closest('tr');
          const rowData = window.dt_basic.row(row).data();
          currentJobId = rowData.id;

          try {
            const response = await fetch(`/export-jobs/${currentJobId}/edit`, {
              method: 'GET',
              headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            });

            if (response.ok) {
              const jobData = await response.json();
              const data = jobData.data;

              // Clear any existing validation errors
              editJobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

              // Helper function to safely set field value
              function setFieldValue(selector, value) {
                const field = editJobForm.querySelector(selector);
                if (field) {
                  field.value = value || '';
                } else {
                  console.warn(`Field not found: ${selector}`);
                }
              }

              // Populate form fields with null checks
              setFieldValue('#edit_cro_id', data.cro_id);
              setFieldValue('#edit_container', data.container);
              setFieldValue('#edit_company_id', data.company_id);
              setFieldValue('#edit_size', data.size);
              setFieldValue('#edit_line_id', data.line_id);
              setFieldValue('#edit_port', data.port_id);
              setFieldValue('#edit_forwarder', data.forwarder_id);
              setFieldValue('#edit_pod', data.pod_id);
              setFieldValue('#edit_terminal', data.terminal_id);
              setFieldValue('#edit_empty_pickup', data.empty_pickup);
              setFieldValue('#edit_status', data.status);

              // Show the modal
              const modal = new bootstrap.Modal(document.getElementById('editJobModal'));
              modal.show();

            } else {
              const error = await response.json();
              showToast('Error', error.message || 'Failed to fetch job details.', 'error');
            }
          } catch (err) {
            console.error('Fetch error:', err);
            showToast('Error', 'An error occurred while fetching job details.', 'error');
          }
        }
      });

      editJobForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!currentJobId) {
          return showToast('Error', 'No job selected for update.', 'error');
        }

        const formData = new FormData(editJobForm);
        formData.append('_method', 'PUT');

        // Clear previous validation errors
        editJobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Show loading state on submit button with null check
        const submitBtn = editJobForm.querySelector('button[type="submit"]') ||
                         editJobForm.querySelector('.btn-primary') ||
                         document.querySelector('#editJobModal button[type="submit"]');

        let originalText = '';
        if (submitBtn) {
          originalText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Updating...';
        }

        fetch(`/export-jobs/${currentJobId}`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
          if (status === 200) {
            showToast('Success', body.message || 'Job updated successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editJobModal'));
            modal.hide();
            editJobForm.reset();
            window.dt_basic.ajax.reload();
            currentJobId = null;
          } else if (status === 422 && body.errors) {
            // Handle validation errors
            Object.keys(body.errors).forEach(field => {
              const input = editJobForm.querySelector(`[name="${field}"]`);
              if (input) {
                input.classList.add('is-invalid');
                // Find or create error feedback element
                let feedback = input.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                  feedback = document.createElement('div');
                  feedback.classList.add('invalid-feedback');
                  input.parentNode.appendChild(feedback);
                }
                feedback.textContent = body.errors[field][0];
              }
            });
            const errorMessages = Object.values(body.errors).flat().join('<br>');
            showToast('Error', 'Please fix the errors in the form.<br>' + errorMessages, 'error');
          } else {
            showToast('Error', body.message || 'An error occurred while updating the job.', 'error');
          }
        })
        .catch((err) => {
          console.error('Update error:', err);
          showToast('Error', 'Network error. Please try again.', 'error');
        })
        .finally(() => {
          // Reset button state with null check
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
      });

      // Reset form when modal is hidden
      document.getElementById('editJobModal').addEventListener('hidden.bs.modal', function () {
        editJobForm.reset();
        editJobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        editJobForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        currentJobId = null;
      });
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

      fetch('/export-job-comments', {
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
            alert('Failed to add comment.');
          }
        })
        .catch(error => console.error('Error:', error));
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


.was-validated .form-control:valid,
.was-validated .form-select:valid,
.was-validated .form-control:valid:focus,
.was-validated .form-select:valid:focus {
  border-color: #198754;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

.was-validated .form-control:invalid,
.was-validated .form-select:invalid,
.was-validated .form-control:invalid:focus,
.was-validated .form-select:invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.table-responsive {
  border-radius: 0.375rem;
}

.btn-icon {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.input-group-text {
  background-color: rgba(var(--bs-primary-rgb), 0.1);
  border-color: rgba(var(--bs-primary-rgb), 0.2);
  color: rgb(var(--bs-primary-rgb));
}

.modal-xl {
  max-width: 1140px;
}

.form-text {
  font-size: 0.75rem;
  color: var(--bs-secondary);
}

.font-monospace {
  font-family: var(--bs-font-monospace) !important;
}

.badge {
  font-size: 0.75rem;
}

textarea.form-control {
  resize: vertical;
  min-height: 80px;
}

.avatar {
  width: 32px;
  height: 32px;
}

.avatar-xl {
  width: 64px;
  height: 64px;
}

.avatar-initial {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.875rem;
}

.text-wrap {
  word-wrap: break-word;
  white-space: normal;
}

.modal-lg {
  max-width: 800px;
}

.card-header {
  background-color: rgba(var(--bs-primary-rgb), 0.05);
  border-bottom: 1px solid rgba(var(--bs-primary-rgb), 0.1);
}

.card-header .card-title {
  color: rgb(var(--bs-primary-rgb));
  font-weight: 600;
}

.form-label.fw-medium {
  font-weight: 500;
  color: var(--bs-gray-700);
  margin-bottom: 0.25rem;
}

.bg-light {
  background-color: rgba(var(--bs-primary-rgb), 0.03) !important;
}

.modal-footer .btn {
  margin: 0 0.25rem;
}

.font-monospace {
  font-family: var(--bs-font-monospace) !important;
}

.vuexy_date {
    width: auto !important;
  }

</style>
@endsection
