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
  'resources/assets/js/jobs-queue.js',
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

<hr class="my-12" />

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
            <label for="bail_number_id" class="form-label">Bail Number<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="bail_number_id" name="bail_number_id" required>
                <option value="">Select Bail Number</option>
                @foreach($bailNumbers as $bail)
                  <option value="{{ $bail->id }}">{{ $bail->bail_number }}</option>
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
                  <input type="number" class="form-control" id="size_0" name="containers[0][size]" placeholder="Enter Size" required />
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
            <label for="edit_bail_number" class="form-label">Bail Number<span class="text-danger">*</span></label>
            <div class="input-group">
              <select class="form-select" id="edit_bail_number" name="bail_number_id" required>
                <option value="">Select Bail Number</option>
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
              <input type="number" class="form-control" id="edit_size" name="size" placeholder="Enter Size" required />
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
              <input type="text" class="form-control vuexy_date me-0 date-input-padding" id="edit_noc_deadline" name="noc_deadline" placeholder="Enter NOC Deadline (MM/DD/YYYY)" required />
            </div>
          </div>
          <div class="col-md-6">
            <label for="eta" class="form-label">ETA<span class="text-danger">*</span></label> <!--10/15/2024-->
            <div class="input-group">
              <span class="input-group-text"><i class="ti tabler-calendar-week"></i></span>
              <input type="text" class="form-control vuexy_date me-0 date-input-padding" id="edit_eta" name="eta" placeholder="Enter ETA (MM/DD/YYYY)" required />
            </div>
          </div>
          <div class="col-md-6">
            <label for="mode_0" class="form-label  d-block mb-3">Mode<span class="text-danger">*</span></label>
            <div class="form-check-inline">
              <input type="radio" class="form-check-input mt-0" id="edit_mode_0" name="containers[0][mode]" value="CFS" required />
              <label class="form-check-label" for="edit_mode_0">CFS</label>
            </div>
            <div class="form-check-inline">
              <input type="radio" class="form-check-input mt-0" id="edit_mode_1" name="containers[0][mode]" value="CY" required />
              <label class="form-check-label" for="edit_mode_1">CY</label>
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
          <input type="hidden" id="jobId" name="job_id">
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
