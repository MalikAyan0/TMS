@extends('layouts/layoutMaster')

@section('title', 'BL Number Details - ' . $bailNumber->bail_number)

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
<!-- Bail Number Details Header -->
<div class="row">
  <!-- Bail Information Card -->
  <div class="col-md-4 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar avatar-xl mx-auto mb-3">
          <span class="avatar-initial rounded-circle bg-label-primary">
            <i class="ti tabler-receipt fs-2"></i>
          </span>
        </div>
        <h4 class="mb-1">{{ $bailNumber->bail_number }}</h4>
        <p class="text-muted mb-3">
          <span class="badge bg-label-{{ $bailNumber->status === 'active' ? 'success' : 'secondary' }} ms-1">
            {{ ucfirst($bailNumber->status) }}
          </span>
        </p>

        <!-- Bail Stats -->
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

  <!-- Bail Details Card -->
  <div class="col-md-8 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="ti tabler-info-circle me-1"></i>
          BL Information
        </h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-medium">BL Number</label>
            <p class="mb-0">{{ $bailNumber->bail_number }}</p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Status</label>
            <p class="mb-0">
              <span class="badge bg-label-{{ $bailNumber->status === 'active' ? 'success' : 'secondary' }}">
                {{ ucfirst($bailNumber->status) }}
              </span>
            </p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Created Date</label>
            <p class="mb-0">{{ $bailNumber->created_at->format('M d, Y') }}</p>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-medium">Last Updated</label>
            <p class="mb-0">{{ $bailNumber->updated_at->format('M d, Y') }}</p>
          </div>
          <div class="col-12">
            <label class="form-label fw-medium">Description</label>
            <p class="mb-0">{{ $bailNumber->description ?? 'No description provided' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Jobs Table -->
<div class="card">
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table">
        <thead>
          <tr>
            <th></th>
            <th>S.no</th>
            <th>Job #</th>
            <th>BL #</th>
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
          <!-- Bail Number -->
          <div class="col-md-12">
            <label for="bail_number_id" class="form-label">BL Number<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="bail_number_id" name="bail_number_id" required>
                <option value="">Select BL Number</option>
                @foreach($bailNumbers as $bail)
                  <option value="{{ $bail->id }}" {{ $bail->id == $bailNumber->id ? 'selected' : '' }}>{{ $bail->bail_number }}</option>
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
                <label for="company_id_0" class="form-label">Description<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select name="containers[0][company_id]" id="company_id_0" class="form-select" required>
                    <option>Select Description</option>
                    @foreach($companies as $company)
                      <option value="{{ $company->id }}">{{ $company->title }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for="size_0" class="form-label">Size<span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control" id="size_0" name="containers[0][size]" placeholder="Enter Size" required />
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
                <label for="port_0" class="form-label">Port<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="port_0" name="containers[0][port_id]" required>
                    <option value="">Select Port of Loading</option>
                    @foreach($ports as $port)
                      <option value="{{ $port->id }}">{{ $port->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
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
              <div class="col-md-3">
                <label for="noc_deadline_0" class="form-label">NOC Deadline<span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="ti tabler-calendar-week"></i></span>
                  <input type="text" class="form-control vuexy_date me-0 pe-2" id="noc_deadline_0" name="containers[0][noc_deadline]" placeholder="Enter NOC Deadline (MM/DD/YYYY)" required />
                </div>
              </div>
              <div class="col-md-3">
                <label for="eta_0" class="form-label">ETA<span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="ti tabler-calendar-week"></i></span>
                  <input type="text" class="form-control vuexy_date me-0 pe-2" id="eta_0" name="containers[0][eta]" placeholder="Enter ETA (MM/DD/YYYY)" required />
                </div>
              </div>
              <div class="col-md-3">
                <label for="mode_0" class="form-label  d-block mb-3">Mode<span class="text-danger">*</span></label>
                <div class="form-check-inline">
                  <input type="radio" class="form-check-input mt-0" id="mode_0" name="containers[0][mode]" value="CFS" required />
                  <label class="form-check-label" for="mode_0">CFS</label>
                </div>
                <div class="form-check-inline">
                  <input type="radio" class="form-check-input mt-0" id="modes_0" name="containers[0][mode]" value="CY" required />
                  <label class="form-check-label" for="modes_0">CY</label>
                </div>
              </div>
              <div class="col-md-3">
                <label for="status_0" class="form-label">Status<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select" id="status_0" name="containers[0][status]" required>
                    <option value="">Select Status</option>
                    <option value="Open">Open</option>
                    <option value="Vehicle Required">Vehicle Required</option>
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
            <label for="edit_bail_number" class="form-label">BL Number<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_bail_number" name="bail_number_id" required>
                <option value="">Select BL Number</option>
                @foreach($bailNumbers as $bail)
                  <option value="{{ $bail->id }}">{{ $bail->bail_number }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <!-- Job Details Row -->
          <div class="col-md-6">
            <label for="job_title" class="form-label">Container<span class="text-danger">*</span></label> <!--EMCU6022154 value is liek this-->
            <div class="input-group">
              <input type="text" class="form-control" id="edit_container" name="container" placeholder="Enter Container Number" required />
            </div>
          </div>
          <div class="col-md-6">
            <label for="description" class="form-label">Description<span class="text-danger">*</span></label> <!--G/CARGO, DG/CARGO-->
            <div class="input-group">
              <select name="company_id" id="edit_company_id" class="form-select" required>
                <option>Select Description</option>
                @foreach($companies as $company)
                  <option value="{{ $company->id }}">{{ $company->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="size" class="form-label">Size<span class="text-danger">*</span></label> <!--20FT, 40FT, 40HC-->
            <div class="input-group">
              <input type="text" class="form-control" id="edit_size" name="size" placeholder="Enter Size" required />
            </div>
          </div>
          <div class="col-md-6">
            <label for="line" class="form-label">Line<span class="text-danger">*</span></label> <!--OSS Star-->
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
            <label for="port" class="form-label">Port<span class="text-danger">*</span></label> <!--Port of Loading-->
            <div class="input-group">
              <select class="form-select" id="edit_port" name="port_id" required>
                <option value="">Select Port of Loading</option>
                @foreach($ports as $port)
                  <option value="{{ $port->id }}">{{ $port->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="forwarder" class="form-label">Forwarder<span class="text-danger">*</span></label> <!--SEA Hawk-->
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
            <label for="noc_deadline" class="form-label">NOC Deadline<span class="text-danger">*</span></label> <!--10/15/2025-->
            <div class="input-group">
              <span class="input-group-text"><i class="ti tabler-calendar-week"></i></span>
              <input type="text" class="form-control vuexy_date" id="edit_noc_deadline" name="noc_deadline" placeholder="Enter NOC Deadline (MM/DD/YYYY)" required />
            </div>
          </div>
          <div class="col-md-6">
            <label for="eta" class="form-label">ETA<span class="text-danger">*</span></label> <!--10/15/2024-->
            <div class="input-group">
              <span class="input-group-text"><i class="ti tabler-calendar-week"></i></span>
              <input type="text" class="form-control vuexy_date" id="edit_eta" name="eta" placeholder="Enter ETA (MM/DD/YYYY)" required />
            </div>
          </div>
          <div class="col-md-6">
            <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_status" name="status" required>
                <option value="">Select Status</option>
                <option value="Open">Open</option>
                <option value="Vehicle Required">Vehicle Required</option>
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

<!-- View Job Modal (Modern & Attractive) -->
<div class="modal fade" id="viewJobModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom rounded-top p-4">
        <h5 class="modal-title d-flex align-items-center">
          <i class="ti tabler-eye me-2 fs-3"></i>
          <span>Job Details</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4 py-4">
        <div class="row g-4">
          <!-- Job Header -->
          <div class="col-12 text-center mb-3">
            <div class="avatar avatar-xl mx-auto mb-2 shadow-sm border rounded-circle border-primary">
              <span class="avatar-initial rounded-circle bg-gradient-primary" id="viewJobAvatar">
                <i class="ti tabler-briefcase fs-2"></i>
              </span>
            </div>
            <h5 class="mb-1 fw-bold" id="viewJobTitle">-</h5>
            <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
              <span class="badge rounded-pill bg-gradient-secondary px-3 py-2 fs-6" id="viewJobStatus">-</span>
              <span class="badge rounded-pill bg-info px-3 py-2 fs-6" id="viewJobBailNumber">Bail #: -</span>
            </div>
          </div>
          <!-- Job Details List -->
          <div class="col-12 p-5">
            <div class="row g-3 justify-content-center">


            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer border-0 rounded-bottom">
        <button type="button" class="btn btn-outline-primary" id="duplicateFromView">
          <i class="ti tabler-copy me-1"></i>
          <span class="d-none d-sm-inline">Duplicate</span>
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti tabler-x me-1"></i>
          Close
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
      tableTitle.innerHTML = 'Jobs';
      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/bails/{{ $bailNumber->id }}/jobs-data', // JSON file to add data
          type: 'GET', // or GET if your server expects it
          contentType: 'application/json',
          dataSrc: function(json) {
            return json.data || json;
          }
        },
        columns: [
          {data:'id'},
          { data: '', orderable: true, },
          { data: 'job_number', defaultContent: '-' },
          { data: 'bail_number.bail_number', defaultContent: '-' },
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

                  <a href="/jobs/${full.id}" >${job_number}</a>

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
                ? `<a href="/bails/${bail_id}/view">${bail}</a>`
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
          },
          {
            targets: 8
          },
          {
            responsivePriority: 4,
            targets: 9,
            render: function (data, type, row) {
              let status = data ? data.toLowerCase() : '-';
              let badgeClass = 'bg-label-secondary';

              switch (status) {
                case 'open':
                  badgeClass = 'bg-label-primary';
                  break;
                case 'vehicle required': // Added new status
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
              const showActions = status === 'open' || status === 'vehicle required'; // Updated condition
              return (
                '<div class="d-flex gap-1">' +
                '<a href="/jobs/' + full.id + '" class="btn btn-icon btn-sm btn-outline-secondary waves-effect "><i class="icon-base ti tabler-eye"></i></a>' +
                (showActions
                  ? '<button type="button" class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit"><i class="icon-base ti tabler-pencil"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="' + full.id + '"><i class="icon-base ti tabler-trash"></i></button>'
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
          const response = await fetch(`/jobs/${deleteJobId}`, {
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
        fetch('/jobs', {
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
    const editJobForm = document.getElementById('EditJobForm'); // fixed ID
    if (editJobForm) {
      let currentJobId = null;
      document.addEventListener('click', async function (e) {
        const editBtn = e.target.closest('.item-edit');
        if (editBtn) {
          const row = editBtn.closest('tr');
          const rowData = window.dt_basic.row(row).data();
          currentJobId = rowData.id;

          try {
            const response = await fetch(`/jobs/${currentJobId}/edit`, {
              method: 'GET',
              headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            });

            if (response.ok) {
              const jobData = await response.json();
              const data = jobData.data; // 👈 fix: access nested "data"

              // Populate form fields (must match Blade IDs)
               editJobForm.querySelector('#edit_bail_number').value = data.bail_number || '';
              editJobForm.querySelector('#edit_container').value = data.container || '';
              editJobForm.querySelector('#edit_companies_id').value = data.companies_id || '';
              editJobForm.querySelector('#edit_size').value = data.size || '';
              editJobForm.querySelector('#edit_line_id').value = data.line_id || '';
              editJobForm.querySelector('#edit_port').value = data.port_id || ''; // 👈 fixed ID
              editJobForm.querySelector('#edit_forwarder').value = data.forwarder_id || ''; // 👈 fixed ID
              editJobForm.querySelector('#edit_noc_deadline').value = data.noc_deadline || '';
              editJobForm.querySelector('#edit_eta').value = data.eta || '';
              editJobForm.querySelector('#edit_status').value = data.status || '';

              // Show the modal (correct modal ID)
              const modal = new bootstrap.Modal(document.getElementById('editJobModal'));
              modal.show();

            } else {
              showToast('Error', 'Failed to fetch job details.', 'error');
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
        formData.append('_method', 'PUT'); // ensure Laravel sees it as PUT

        editJobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        fetch(`/jobs/${currentJobId}`, {
          method: 'POST', // always POST with FormData + _method
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
          if (status === 200) {
            showToast('Success', body.message, 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editJobModal'));
            modal.hide();
            editJobForm.reset();
            window.dt_basic.ajax.reload();
          } else if (status === 422 && body.errors) {
            Object.keys(body.errors).forEach(field => {
              const input = editJobForm.querySelector(`[name="${field}"]`);
              if (input) input.classList.add('is-invalid');
            });
            const errorMessages = Object.values(body.errors).flat().join('<br>');
            showToast('Error', 'Please fix the errors in the form.<br>' + errorMessages, 'error');
          } else {
            showToast('Error', 'An error occurred while updating the job.', 'error');
          }
        })
        .catch(() => {
          showToast('Error', 'Network error. Please try again.', 'error');
        });
      });
    }

    // Handle click on "View" buttons in DataTable
     const viewJobModal = document.getElementById('viewJobModal');
    if (!viewJobModal) return;

    let currentJobId = null;

    // Handle click on view button
    document.addEventListener('click', async function (e) {
      const viewBtn = e.target.closest('.item-view');
      if (viewBtn) {
        const row = viewBtn.closest('tr');
        const rowData = window.dt_basic.row(row).data();
        currentJobId = rowData.id;

        try {
          const response = await fetch(`/jobs/${currentJobId}`, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });

          if (response.ok) {
            const jobData = await response.json();
            if (!jobData.success) {
              showToast('Error', jobData.message || 'Job not found.', 'error');
              return;
            }

            const data = jobData.data;

            // Populate modal fields
            document.getElementById('viewJobTitle').textContent = data.job_number || '-';
            document.getElementById('viewJobBailNumber').textContent = 'Bail #: ' + (data.bail_number.bail_number || '-');
            // document.getElementById('viewJobStatus').textContent = data.status || '-';
            const statusEl = document.getElementById('viewJobStatus');
            const status = data.status || '-';

            // Reset classes first
            statusEl.className = 'badge ms-1';

            // Apply status and color
            if (status.toLowerCase() === 'active') {
              statusEl.classList.add('bg-label-primary');
            } else if (status.toLowerCase() === 'pending') {
              statusEl.classList.add('bg-label-warning');
            } else if (status.toLowerCase() === 'completed') {
              statusEl.classList.add('bg-label-success');
            } else if (status.toLowerCase() === 'cancelled') {
              statusEl.classList.add('bg-label-danger');
            } else {
              statusEl.classList.add('bg-label-secondary');
            }

            statusEl.textContent = status;


            // Avatar (you can make it dynamic if needed)
            const avatarEl = document.getElementById('viewJobAvatar');
            avatarEl.innerHTML = `<i class="ti tabler-briefcase fs-2"></i>`;

            // --- Add extra preview content below header ---
            const bodyContainer = viewJobModal.querySelector('.modal-body .row.g-3');

            // Clear any old job details (if modal opened multiple times)
            const oldDetails = bodyContainer.querySelector('.job-details');
            if (oldDetails) oldDetails.remove();

            // Build a details card
           const detailsHtml = `
              <div class="col-12 job-details mt-3">
                <div class="row g-3 justify-content-center p-4">
                  <div class="col-md-6  pb-3 mb-3">
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-container fs-5 text-primary me-2"></i>
                      <span class="fw-semibold">Container:</span>
                      <span class="ms-2">${data.container ?? '-'}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-building-warehouse fs-5 text-info me-2"></i>
                      <span class="fw-semibold">Line:</span>
                      <span class="ms-2">${data.line?.name ?? '-'}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-map-pin fs-5 text-warning me-2"></i>
                      <span class="fw-semibold">Port:</span>
                      <span class="ms-2">${data.port?.name ?? '-'}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-truck fs-5 text-success me-2"></i>
                      <span class="fw-semibold">
                        ${data.market_vehicle === 'yes' ? 'Market Vehicle Details:' : 'Vehicle:'}
                      </span>
                      <span class="ms-2">
                        ${data.market_vehicle === 'yes'
                        ? (data.market_vehicle_details ?? '-')
                        : (data.vehicle?.registration_number ?? '-')}
                      </span>
                    </div>
                  </div>
                  <div class="col-md-6 pb-3 mb-3">
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-calendar-event fs-5 text-danger me-2"></i>
                      <span class="fw-semibold">Gate Pass Time:</span>
                      <span class="ms-2">${data.gate_time_passed ?? '-'}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-calendar-time fs-5 text-secondary me-2"></i>
                      <span class="fw-semibold">ETA:</span>
                      <span class="ms-2">${data.eta ?? '-'}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-calendar-check fs-5 text-primary me-2"></i>
                      <span class="fw-semibold">NOC Deadline:</span>
                      <span class="ms-2">${data.noc_deadline ?? '-'}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                      <i class="ti tabler-user fs-5 text-info me-2"></i>
                      <span class="fw-semibold">Forwarder:</span>
                      <span class="ms-2">${data.forwarder?.title ?? '-'}</span>
                    </div>
                  </div>
                </div>
                <div class="col-12 pb-3 mb-3">
                  <div class="alert alert-light border-start border-1  shadow-sm mb-0 align-items-center d-flex">
                    <i class="ti tabler-message fs-5 text-primary me-2"></i>
                    <span class="fw-semibold text-dark">Remarks:</span>
                    <span class="ms-2 text-dark">${data.remarks ?? '-'}</span>
                  </div>
                </div>
              </div>
            `;

            bodyContainer.insertAdjacentHTML('beforeend', detailsHtml);

            // Store ID in footer buttons
            // document.getElementById('editFromView').dataset.id = data.id;
            // document.getElementById('toggleFromView').dataset.id = data.id;
            document.getElementById('duplicateFromView').dataset.id = data.id;
            // document.getElementById('deleteFromView').dataset.id = data.id;


            // Show modal
            const modal = new bootstrap.Modal(viewJobModal);
            modal.show();

          } else {
            showToast('Error', 'Failed to fetch job details.', 'error');
          }
        } catch (err) {
          console.error('Fetch error:', err);
          showToast('Error', 'An error occurred while fetching job details.', 'error');
        }
      }
    });

    document.getElementById('duplicateFromView').addEventListener('click', async function () {
      const jobId = this.dataset.id;
      if (!jobId) {
        showToast('Error', 'No job selected to duplicate.', 'error');
        return;
      }

      try {
        // Fetch job data (same as view)
        const response = await fetch(`/jobs/${jobId}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });

        if (response.ok) {
          const jobData = await response.json();
          if (!jobData.success) {
            showToast('Error', jobData.message || 'Job not found.', 'error');
            return;
          }
          const data = jobData.data;

          // Populate the create job form with the fetched data
          const jobForm = document.getElementById('jobForm');
          if (jobForm) {
            jobForm.querySelector('#container').value = data.container || '';
            jobForm.querySelector('#companies_id').value = data.companies_id || '';
            jobForm.querySelector('#size').value = data.size || '';
            jobForm.querySelector('#line_id').value = data.line_id || '';
            jobForm.querySelector('#port').value = data.port_id || '';
            jobForm.querySelector('#forwarder').value = data.forwarder_id || '';
            jobForm.querySelector('#noc_deadline').value = data.noc_deadline || '';
            jobForm.querySelector('#eta').value = data.eta || '';
            jobForm.querySelector('#vehicle_id').value = data.vehicle_id || '';
            jobForm.querySelector('#gate_pass_time').value = data.gate_time_passed || '';
            jobForm.querySelector('#status').value = data.status || '';
            jobForm.querySelector('#remarks').value = data.remarks || '';
          }

          // Hide the view modal and show the create modal
          const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewJobModal'));
          viewModal.hide();
          const createModal = new bootstrap.Modal(document.getElementById('createJobModal'));
          createModal.show();

          showToast('Info', 'Fields copied. You can now edit and save as a new job.', 'info');
        } else {
          showToast('Error', 'Failed to fetch job details.', 'error');
        }
      } catch (err) {
        console.error('Duplicate error:', err);
        showToast('Error', 'An error occurred while duplicating the job.', 'error');
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
