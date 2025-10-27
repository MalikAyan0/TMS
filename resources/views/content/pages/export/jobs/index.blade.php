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
<!-- DataTable with Buttons -->
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
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<hr class="my-12" />

<!-- Create New Job Modal -->
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
                <label for="container_0" class="form-label">Container</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="container_0" name="containers[0][container]" placeholder="Enter Container Number"  />
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
                <label for="job_type_0" class="form-label">Job Type<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select job-type-select" id="job_type_0" name="containers[0][job_type]" required>
                    <option value="">Select Job Type</option>
                    <option value="Empty">Empty</option>
                    <option value="Loaded">Loaded</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <label for="status_0" class="form-label">Status<span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-select status-select" id="status_0" name="containers[0][status]" required>
                    <option value="">Select Status</option>
                    <option value="Open">Open</option>
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


<!-- Edit Job Modal -->
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
            <label for="edit_job_type" class="form-label">Job Type<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select job-type-select" id="edit_job_type" name="job_type" required>
                <option value="">Select Job Type</option>
                <option value="Empty">Empty</option>
                <option value="Loaded">Loaded</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label for="edit_status" class="form-label">Status<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select status-select" id="edit_status" name="status" required>
                <option value="">Select Status</option>
                <option value="Open">Open</option>
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
          <input type="hidden" id="jobQueueId" name="job_id">
          <input type="hidden" id="status" name="status">
          <input type="hidden" name="type" value="export">
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

<!-- Convert to Vehicle Required Modal -->
<div class="modal fade" id="convertToVehicleRequiredModal" tabindex="-1" aria-labelledby="convertToVehicleRequiredModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="convertToVehicleRequiredModalLabel">
          <i class="ti tabler-truck me-2"></i>
          Convert to Vehicle Required
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="convertToVehicleRequiredForm" method="POST" action="#">
          @csrf
          <input type="hidden" id="convert_job_id" name="job_id" />

          <!-- Job Information Display -->
          <div class="col-12 mb-3">
            <div class="alert alert-info">
              <h6 class="mb-2">Job Information</h6>
              <p class="mb-0"><strong>Container:</strong> <span id="convert_container">-</span></p>
            </div>
          </div>

          <!-- Remarks Field -->
          <div class="mb-3">
            <label for="convert_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="convert_remarks" name="remarks" rows="3" placeholder="Enter any remarks for converting to Vehicle Required (optional)"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="convertToVehicleRequiredForm" class="btn btn-warning">
          <i class="ti tabler-truck me-1"></i>
          Vehicle Required from loaded return
        </button>
      </div>
    </div>
  </div>
</div>

<!-- convert to Ready to Move Modal -->
<div class="modal fade" id="readyToMoveModal" tabindex="-1" aria-labelledby="readyToMoveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="readyToMoveModalLabel">
          <i class="ti tabler-truck me-2"></i>
          Convert to Ready to Move
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="readyToMoveForm" method="POST" action="#">
          @csrf
          <input type="hidden" id="ready_to_move_convert_job_id" name="job_id" />
          <!-- Remarks Field -->
          <div class="mb-3">
            <label for="ready_to_move_convert_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="ready_to_move_convert_remarks" name="remarks" rows="3" placeholder="Enter any remarks for converting to Vehicle Required (optional)"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="readyToMoveForm" class="btn btn-warning">
          <i class="ti tabler-truck me-1"></i>
          Ready to Move
        </button>
      </div>
    </div>
  </div>
</div>

<!-- convert to Container Return Modal -->
<div class="modal fade" id="containerReturnModal" tabindex="-1" aria-labelledby="containerReturnModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="containerReturnModalLabel">
          <i class="ti tabler-truck me-2"></i>
          Convert to Container Return
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="containerReturnForm" method="POST" action="#">
          @csrf
          <input type="hidden" id="container_return_job_id" name="job_id" />
          <!-- Remarks Field -->
          <div class="mb-3">
            <label for="container_return_convert_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="container_return_convert_remarks" name="remarks" rows="3" placeholder="Enter any remarks for converting to Vehicle Required (optional)"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="containerReturnForm" class="btn btn-warning">
          <i class="ti tabler-truck me-1"></i>
           Container Return
        </button>
      </div>
    </div>
  </div>
</div>

<!-- convert to Vehicle Required Modal -->
<div class="modal fade" id="vehicleRequiredModal" tabindex="-1" aria-labelledby="vehicleRequiredModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-4 border-bottom">
        <h5 class="modal-title" id="vehicleRequiredModalLabel">
          <i class="ti tabler-truck me-2"></i>
          Convert to Vehicle Required
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="vehicleRequiredForm" method="POST" action="#">
          @csrf
          <input type="hidden" id="vehicle_required_job_id" name="job_id" />
          <!-- Remarks Field -->
          <div class="mb-3">
            <label for="vehicle_required_convert_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="vehicle_required_convert_remarks" name="remarks" rows="3" placeholder="Enter any remarks for converting to Vehicle Required (optional)"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top p-4">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="vehicleRequiredForm" class="btn btn-warning">
          <i class="ti tabler-truck me-1"></i>
          Vehicle Required
        </button>
      </div>
    </div>
  </div>
</div>


<!-- Toast Container -->
<x-toast-container />
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
          url: '/export-jobs',
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
            responsivePriority: 4,
            targets: 5,
            render: function (data, type, row) {
              let status = data ? data : '-';
              let badgeClass = 'bg-label-secondary';

              switch (status) {
              case 'Open':
                badgeClass = 'bg-label-primary';
                break;
              case 'Vehicle Required':
                badgeClass = 'bg-label-warning';
                break;
              case 'Ready To Move':
                badgeClass = 'bg-label-info';
                break;
              case 'In Transit':
                badgeClass = 'bg-label-success';
                break;
              case 'Delivered':
                badgeClass = 'bg-label-dark';
                break;
              case 'Dry Off':
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
                  ? '<button type="button" class="btn btn-icon btn-sm btn-outline-warning waves-effect vehicle-required" data-id="' + full.id + '" title="Vehicle Required"><i class="icon-base ti tabler-truck"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit"><i class="icon-base ti tabler-pencil"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="' + full.id + '"><i class="icon-base ti tabler-trash"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>'
                    : ''
                ) +
                (status === 'empty pick'
                    ? '<button type="button" class="btn btn-icon btn-sm btn-outline-success waves-effect ready-to-move" data-id="' + full.id + '" title="Ready to Move"><i class="icon-base ti tabler-check"></i></button>' +
                      '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '" title="Add Comments"><i class="icon-base ti tabler-message-circle"></i></button>'
                  : ''
                ) +
                (status === 'ready to move'
                    ? '<button type="button" class="btn btn-icon btn-sm btn-outline-warning waves-effect open-remarks-dialog" data-id="' + full.id + '" title="Convert to Vehicle Required"><i class="icon-base ti tabler-truck"></i></button>' +
                      '<button type="button" class="btn btn-icon btn-sm btn-outline-success waves-effect mark-as-container-returned" data-id="' + full.id + '" title="Mark as Container Returned"><i class="icon-base ti tabler-container"></i></button>' +
                      '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>'
                    : ''
                ) +
                (status === 'container returned'
                    ? '<button type="button" class="btn btn-icon btn-sm btn-outline-warning waves-effect vehicle-required" data-id="' + full.id + '" title="Vehicle Required"><i class="icon-base ti tabler-truck"></i></button>' +
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
  // Add this after your existing DOMContentLoaded event listener
  document.addEventListener('DOMContentLoaded', function() {
    // Function to update status options based on job type
    function updateStatusOptions(jobTypeSelect) {
      const container = jobTypeSelect.closest('.container-row') || jobTypeSelect.closest('form');
      const statusSelect = container.querySelector('.status-select');
      const jobType = jobTypeSelect.value;

      // Clear existing options
      statusSelect.innerHTML = '<option value="">Select Status</option>';

      // Add appropriate options based on job type
      if (jobType === 'Empty') {
        statusSelect.add(new Option('Open', 'Open'));
      } else if (jobType === 'Loaded') {
        statusSelect.add(new Option('Open', 'Open'));
        statusSelect.add(new Option('Ready To Move', 'Ready To Move'));
      }
    }

    // Add event listeners for job type selects in create form
    document.querySelectorAll('.job-type-select').forEach(select => {
      select.addEventListener('change', function() {
        updateStatusOptions(this);
      });
    });

    // Update status options when adding new container row
    const addContainerRow = document.getElementById('addContainerRow');
    if (addContainerRow) {
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type === 'childList' && mutation.addedNodes.length) {
            mutation.addedNodes.forEach(function(node) {
              if (node.classList && node.classList.contains('container-row')) {
                const jobTypeSelect = node.querySelector('.job-type-select');
                if (jobTypeSelect) {
                  jobTypeSelect.addEventListener('change', function() {
                    updateStatusOptions(this);
                  });
                }
              }
            });
          }
        });
      });

      observer.observe(document.getElementById('containersWrapper'), {
        childList: true,
        subtree: true
      });
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Function to open the "Convert to Vehicle Required" modal
    document.addEventListener('click', function (e) {
      const button = e.target.closest('.open-remarks-dialog');
      if (button) {
        const jobId = button.dataset.id;

        // Fetch job details and populate the modal
        fetch(`/export-jobs/${jobId}/edit`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const job = data.data;

              // Populate modal fields
              document.getElementById('convert_job_id').value = job.id;
              document.getElementById('convert_container').textContent = job.container || '-';

              // Show the modal
              const modal = new bootstrap.Modal(document.getElementById('convertToVehicleRequiredModal'));
              modal.show();
            } else {
              console.error('Failed to fetch job details:', data.message);
            }
          })
          .catch(error => {
            console.error('Error fetching job details:', error);
          });
      }
    });

    // Handle form submission for converting to "Vehicle Required"
    const convertForm = document.getElementById('convertToVehicleRequiredForm');
    if (convertForm) {
      convertForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(convertForm);
        const jobId = formData.get('job_id');

        fetch(`/export-jobs/${jobId}/convert-to-vehicle-required`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: formData,
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close the modal and reload the DataTable
              bootstrap.Modal.getInstance(document.getElementById('convertToVehicleRequiredModal')).hide();
              window.dt_basic.ajax.reload();
              showToast('Success', 'Job status updated to Vehicle Required!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to update job status.', 'error');
            }
          })
          .catch(error => {
            console.error('Error updating job status:', error);
            showToast('Error', 'An error occurred while updating job status.', 'error');
          });
      });
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Function to open the "Ready to Move" modal
    document.addEventListener('click', function (e) {
      const button = e.target.closest('.ready-to-move');
      if (button) {
        const jobId = button.dataset.id;

        if (!jobId) {
          console.error('Job ID is undefined.');
          return;
        }

        // Populate the modal with the job ID
        document.getElementById('ready_to_move_convert_job_id').value = jobId;


        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('readyToMoveModal'));
        modal.show();
      }
    });

    // Handle form submission for marking as "Ready to Move"
    const readyToMoveForm = document.getElementById('readyToMoveForm');
    if (readyToMoveForm) {
      readyToMoveForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(readyToMoveForm);
        const jobId = formData.get('job_id');

        fetch(`/export-jobs/${jobId}/ready-to-move-status`, {
          method: 'PATCH',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: formData,
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close the modal and reload the DataTable
              bootstrap.Modal.getInstance(document.getElementById('readyToMoveModal')).hide();
              window.dt_basic.ajax.reload();
              showToast('Success', 'Job status updated to Ready to Move!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to update job status.', 'error');
            }
          })
          .catch(error => {
            console.error('Error updating job status:', error);
            showToast('Error', 'An error occurred while updating job status.', 'error');
          });
      });
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Function to open the "Container Return" modal
    document.addEventListener('click', function (e) {
      const button = e.target.closest('.mark-as-container-returned');
      if (button) {
        const jobId = button.dataset.id;

        if (!jobId) {
          console.error('Job ID is undefined.');
          return;
        }

        // Populate the modal with the job ID
        document.getElementById('container_return_job_id').value = jobId;

        // Fetch job details to display in the modal (optional)
        fetch(`/export-jobs/${jobId}/edit`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // You can populate more fields here if needed
              // For example: document.getElementById('container_display').textContent = data.data.container || '-';
            }
          })
          .catch(error => {
            console.error('Error fetching job details:', error);
          });

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('containerReturnModal'));
        modal.show();
      }
    });

    // Handle form submission for container return
    const containerReturnForm = document.getElementById('containerReturnForm');
    if (containerReturnForm) {
      containerReturnForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(containerReturnForm);
        const jobId = formData.get('job_id');

        fetch(`/export-jobs/${jobId}/container-returned`, {
          method: 'PATCH',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: formData,
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close the modal and reload the DataTable
              bootstrap.Modal.getInstance(document.getElementById('containerReturnModal')).hide();
              window.dt_basic.ajax.reload();
              showToast('Success', 'Job status updated to Container Returned!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to update job status.', 'error');
            }
          })
          .catch(error => {
            console.error('Error updating job status:', error);
            showToast('Error', 'An error occurred while updating job status.', 'error');
          });
      });
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Function to open the "Vehicle Required" modal
    document.addEventListener('click', function (e) {
      const button = e.target.closest('.vehicle-required');
      if (button) {
        const jobId = button.dataset.id;

        if (!jobId) {
          console.error('Job ID is undefined.');
          return;
        }

        // Populate the modal with the job ID
        document.getElementById('vehicle_required_job_id').value = jobId;

        // Fetch job details to display in the modal (optional)
        fetch(`/export-jobs/${jobId}/edit`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // You can populate more fields here if needed
              // For example: document.getElementById('vehicle_required_container').textContent = data.data.container || '-';
            }
          })
          .catch(error => {
            console.error('Error fetching job details:', error);
          });

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('vehicleRequiredModal'));
        modal.show();
      }
    });

    // Handle form submission for "Vehicle Required"
    const vehicleRequiredForm = document.getElementById('vehicleRequiredForm');
    if (vehicleRequiredForm) {
      vehicleRequiredForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(vehicleRequiredForm);
        const jobId = formData.get('job_id');

        fetch(`/export-jobs/${jobId}/convert-to-vehicle-required`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: formData,
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close the modal and reload the DataTable
              bootstrap.Modal.getInstance(document.getElementById('vehicleRequiredModal')).hide();
              window.dt_basic.ajax.reload();
              showToast('Success', 'Job status updated to Vehicle Required!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to update job status.', 'error');
            }
          })
          .catch(error => {
            console.error('Error updating job status:', error);
            showToast('Error', 'An error occurred while updating job status.', 'error');
          });
      });
    }
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
  .date-input-padding{
   padding-right: 120px !important;
  }
</style>
@endsection
