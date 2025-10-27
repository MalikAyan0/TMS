@extends('layouts/layoutMaster')

@section('title', 'Workshop Jobs - System Management')

<!-- Vendor Styles -->
@section('vendor-style')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('content')
<!-- Workshop Jobs Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Workshop Jobs <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage workshop <span class="d-none d-sm-inline">job requests and approvals</span></p>
    </div>
    @can('workshop.request')
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkshopJobModal">
        <i class="ti tabler-plus me-1"></i>
        <span class="d-none d-sm-inline">Add New Job</span>
      </button>
    @endcan
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-workshop-jobs table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Parts</th>
          <th>Invoice</th>
          <th>Vehicle</th>
          <th>Reconciliation</th>
          <th>Vendor</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<hr class="my-12" />

<!-- Add New Workshop Job Modal -->
<div class="modal fade" id="addWorkshopJobModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-engine me-2"></i>
          Add New Workshop Job
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addWorkshopJobForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="parts_detail" class="form-label">Parts Detail <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-wrench"></i></span>
                <input type="text" class="form-control" id="parts_detail" name="parts_detail"
                  placeholder="e.g., Brake Pads, Oil Filter" required>
                <div class="invalid-feedback">Please provide parts details.</div>
              </div>
            </div>

            {{-- <div class="col-md-6">
              <label for="invoice" class="form-label">Invoice <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <input type="text" class="form-control" id="invoice" name="invoice"
                  placeholder="e.g., INV-001" required>
                <div class="invalid-feedback">Please provide invoice number.</div>
              </div>
            </div> --}}

            <div class="col-md-6">
              <label for="vehicle_id" class="form-label">Vehicle <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-car"></i></span>
                <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                  <option value="">Select Vehicle</option>
                  @foreach($fleets as $fleet)
                    <option value="{{ $fleet->id }}">{{ $fleet->name }} - {{ $fleet->registration_number }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select a vehicle.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="reconciliation" class="form-label">Reconciliation <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-currency-dollar"></i></span>
                <input type="number" step="0.01" class="form-control" id="reconciliation" name="reconciliation"
                  placeholder="e.g., 150.00" required>
                <div class="invalid-feedback">Please provide a valid reconciliation amount.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="location" class="form-label">Location</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <input type="text" class="form-control" id="location" name="location"
                  placeholder="e.g., Karachi Workshop">
              </div>
            </div>

            <div class="col-md-6">
              <label for="vendor_name" class="form-label">Vendor Name</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="vendor_name" name="vendor_name"
                  placeholder="e.g., ABC Auto Parts">
              </div>
            </div>



            <div class="col-md-6">
              <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <select class="form-select" id="type" name="type" required>
                  <option value="">Select Type</option>
                  <option value="kict">KICT</option>
                  <option value="byweste">Byweste</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <label for="slip_image" class="form-label">Slip Image</label>
              <div class="input-group">
                <input type="file" class="form-control" id="slip_image" name="slip_image" accept="image/*">
                <div class="invalid-feedback">Please select a valid image file.</div>
              </div>
            </div>

            <div class="col-12">
              <label for="description" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description"></textarea>
              </div>
            </div>


          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Job
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Workshop Job Modal -->
<div class="modal fade" id="editWorkshopJobModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Workshop Job
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editWorkshopJobForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editWorkshopJobId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="edit_parts_detail" class="form-label">Parts Detail <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-wrench"></i></span>
                <input type="text" class="form-control" id="edit_parts_detail" name="parts_detail"
                  placeholder="e.g., Brake Pads, Oil Filter" required>
                <div class="invalid-feedback">Please provide parts details.</div>
              </div>
            </div>

            {{-- <div class="col-md-6">
              <label for="edit_invoice" class="form-label">Invoice <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <input type="text" class="form-control" id="edit_invoice" name="invoice"
                  placeholder="e.g., INV-001" required>
                <div class="invalid-feedback">Please provide invoice number.</div>
              </div>
            </div> --}}

            <div class="col-md-6">
              <label for="edit_vehicle_id" class="form-label">Vehicle <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-car"></i></span>
                <select class="form-select" id="edit_vehicle_id" name="vehicle_id" required>
                  <option value="">Select Vehicle</option>
                  @foreach($fleets as $fleet)
                    <option value="{{ $fleet->id }}">{{ $fleet->name }} - {{ $fleet->registration_number }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select a vehicle.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="edit_reconciliation" class="form-label">Reconciliation <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-currency-dollar"></i></span>
                <input type="number" step="0.01" class="form-control" id="edit_reconciliation" name="reconciliation"
                  placeholder="e.g., 150.00" required>
                <div class="invalid-feedback">Please provide a valid reconciliation amount.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="edit_location" class="form-label">Location</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <input type="text" class="form-control" id="edit_location" name="location"
                  placeholder="e.g., Karachi Workshop">
              </div>
            </div>

            <div class="col-md-6">
              <label for="edit_vendor_name" class="form-label">Vendor Name</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="edit_vendor_name" name="vendor_name"
                  placeholder="e.g., ABC Auto Parts">
              </div>
            </div>

            <div class="col-md-6">
              <label for="edit_type" class="form-label">Type <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <select class="form-select" id="edit_type" name="type" required>
                  <option value="">Select Type</option>
                  <option value="kict">KICT</option>
                  <option value="byweste">Byweste</option>
                </select>
              </div>
            </div>

            {{-- <div class="col-md-6">
              <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-status-change"></i></span>
                <select class="form-select" id="edit_status" name="status" required>
                  <option value="requested">Requested</option>
                  <option value="approved">Approved</option>
                  <option value="paid">Paid</option>
                  <option value="rejected">Rejected</option>
                </select>
                <div class="invalid-feedback">Please select a status.</div>
              </div>
            </div> --}}

            <div class="col-md-6">
              <label for="edit_slip_image" class="form-label">Slip Image</label>
              <div class="input-group">
                <input type="file" class="form-control" id="edit_slip_image" name="slip_image" accept="image/*">
                <div class="invalid-feedback">Please select a valid image file.</div>
              </div>
            </div>

            <div class="col-12">
              <label for="edit_description" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Enter description"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Job
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Workshop Job Modal -->
<div class="modal fade" id="viewWorkshopJobModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Workshop Job Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- Job Header -->
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center">
                <div class="avatar avatar-md mx-auto mb-3">
                  <span class="avatar-initial rounded-circle bg-label-primary" id="viewJobAvatar">
                    <i class="ti tabler-engine fs-2"></i>
                  </span>
                </div>
                <h4 class="mb-1" id="viewJobTitle">-</h4>
                <p class="text-muted mb-0">
                  <span class="badge bg-label-primary font-monospace" id="viewJobInvoice">-</span>
                  <span class="badge bg-label-secondary ms-1" id="viewJobStatus">-</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Basic Information -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-info-circle me-1"></i>
                  Basic Information
                </h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-medium">Parts</label>
                  <p class="mb-0" id="viewJobParts">-</p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-medium">Vehicle</label>
                  <p class="mb-0" id="viewJobVehicle">-</p>
                </div>
                <div class="mb-0">
                  <label class="form-label fw-medium">Reconciliation</label>
                  <p class="mb-0" id="viewJobPrice">-</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-building me-1"></i>
                  Vendor Information
                </h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-medium">Vendor Name</label>
                  <p class="mb-0" id="viewJobVendor">-</p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-medium">Location</label>
                  <p class="mb-0" id="viewJobLocation">-</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-chart-line me-1"></i>
                  Additional Information
                </h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Type</label>
                    <p class="mb-0" id="viewJobType">-</p>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Description</label>
                    <p class="mb-0" id="viewJobDescription">-</p>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-12">
                    <label class="form-label fw-medium">Slip Image</label>
                    <div id="viewJobSlipImage">-</div>
                    <div id="viewJobSlipDownload" class="mt-2"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />

<!-- Page Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    let dt_workshop_jobs;

    // Initialize DataTable
    const dt_workshop_jobs_table = document.querySelector('.datatables-workshop-jobs');
    if (dt_workshop_jobs_table) {
      dt_workshop_jobs = new DataTable(dt_workshop_jobs_table, {
        ajax: {
          url: '/workshop-jobs',
          type: 'GET',
          dataSrc: function (json) {
            return json.jobs;
          },
          error: function (xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load workshop jobs. Please try again.', 'error');
          }
        },
        columns: [{
            data: null,
            orderable: false,
            searchable: false,
            render: function () {
              return '';
            }
          },
          {
            data: null,
            render: function (data, type, row, meta) {
              return meta.row + 1;
            }
          },
          {
            data: 'parts_detail',
            responsivePriority: 1,
            render: function (data, type, full) {
              return `<div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-2">
                          <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="ti tabler-engine"></i>
                          </span>
                        </div>
                        <div class="d-flex flex-column">
                          <span class="fw-medium">${data}</span>
                          <small class="text-muted">${full.invoice}</small>
                        </div>
                      </div>`;
            }
          },
          {
            data: 'invoice',
            render: function (data, type, full) {
              return `<span class="badge bg-label-primary font-monospace">${data}</span>`;
            }
          },
          {
            data: 'vehicle',
            render: function (data, type, full) {
              return data ? `${data.name} - ${data.registration_number}` : 'N/A';
            }
          },
          {
            data: 'reconciliation',
            render: function (data, type, full) {
              return `PKR ${parseFloat(data).toFixed(2)}`;
            }
          },
          {
            data: 'vendor_name',
            render: function (data, type, full) {
              return data || 'N/A';
            }
          },
          {
            data: 'status',
            render: function (data) {
              const statusConfig = {
                requested: {
                  class: 'warning',
                  text: 'Requested',
                  icon: 'clock'
                },
                approved: {
                  class: 'success',
                  text: 'Approved',
                  icon: 'check'
                },
                paid: {
                  class: 'info',
                  text: 'Paid',
                  icon: 'currency-dollar'
                },
                rejected: {
                  class: 'danger',
                  text: 'Rejected',
                  icon: 'x'
                }
              };
              const config = statusConfig[data] || statusConfig.requested;
              return `<span class="badge bg-label-${config.class}">
                        <i class="ti tabler-${config.icon} me-1"></i>${config.text}
                      </span>`;
            }
          },
          {
            data: null,
            responsivePriority: 2,
            orderable: false,
            searchable: false,
            render: function (data, type, full) {
              let buttons = `
                <div class="d-flex gap-1">
                  @can('workshop.view')
                    <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-job" data-id="${full.id}" title="View Details">
                      <i class="ti tabler-eye"></i>
                    </button>
                  @endcan`;

              if (full.status === 'requested') {
                buttons += `
                @can('workshop.accept/reject/payaid')
                  <button type="button" class="btn btn-icon btn-sm btn-outline-success approve-job" data-id="${full.id}" title="Approve">
                    <i class="ti tabler-check"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger reject-job" data-id="${full.id}" title="Reject">
                    <i class="ti tabler-x"></i>
                  </button>
                @endcan
                @can('workshop.edit')
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-job" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                @endcan
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-job" data-id="${full.id}" title="Delete">
                    <i class="ti tabler-trash"></i>
                  </button>`;
              } else if (full.status === 'approved') {
                buttons += `
                @can('workshop.accept/reject/payaid')
                  <button type="button" class="btn btn-icon btn-sm btn-outline-info paid-job" data-id="${full.id}" title="Mark as Paid">
                    <i class="ti tabler-currency-dollar"></i>
                  </button>
                @endcan`;
              }

              buttons += `</div>`;
              return buttons;
            }
          }
        ],
        columnDefs: [{
          className: 'control',
          orderable: false,
          targets: 0,
          render: function () {
            return '';
          }
        }],
        order: [
          [1, 'asc']
        ],
        layout: {
          topEnd: {
            search: {
              placeholder: 'Search workshop jobs...'
            }
          }
        },
        responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function (row) {
                const data = row.data();
                return 'Details of ' + data.parts_detail;
              }
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
              const data = columns
                .map(function (col) {
                  return col.title !== '' ?
                    `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        <td><strong>${col.title}:</strong></td>
                        <td>${col.data}</td>
                      </tr>` :
                    '';
                })
                .join('');
              return data ? $('<table class="table table-sm"/><tbody />').append(data) : false;
            }
          }
        }
      });
    }

    // Display validation errors function
    function displayValidationErrors(errors) {
      const fieldLabels = {
        parts_detail: 'Parts Detail',
        // invoice: 'Invoice',
        vehicle_id: 'Vehicle',
        reconciliation: 'Reconciliation',
        status: 'Status'
      };

      let errorMessage = '';
      Object.keys(errors).forEach((key, index) => {
        const fieldLabel = fieldLabels[key] || key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        const fieldErrors = errors[key];

        if (index > 0) errorMessage += '\n';
        errorMessage += `${fieldLabel}: ${fieldErrors.join(', ')}`;
      });

      showToast('Validation Error', errorMessage, 'error');
    }

    // Add Workshop Job Form Submission
    const addWorkshopJobForm = document.getElementById('addWorkshopJobForm');
    if (addWorkshopJobForm) {
      addWorkshopJobForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (addWorkshopJobForm.checkValidity()) {
          const formData = new FormData(addWorkshopJobForm);

          fetch('/workshop-jobs', {
              method: 'POST',
              body: formData,
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Close modal and reset form
                bootstrap.Modal.getInstance(document.getElementById('addWorkshopJobModal')).hide();
                addWorkshopJobForm.reset();
                addWorkshopJobForm.classList.remove('was-validated');

                // Reload table
                dt_workshop_jobs.ajax.reload();

                showToast('Success', data.message || 'Workshop job added successfully!', 'success');
              } else {
                // Handle validation errors
                if (data.errors) {
                  displayValidationErrors(data.errors);
                } else {
                  showToast('Error', data.message || 'Failed to add workshop job', 'error');
                }
              }
            })
            .catch(error => {
              console.error('Error:', error);
              showToast('Error', 'An error occurred while adding the workshop job', 'error');
            });
        }

        addWorkshopJobForm.classList.add('was-validated');
      });
    }

    // Edit Workshop Job Form Submission
    const editWorkshopJobForm = document.getElementById('editWorkshopJobForm');
    if (editWorkshopJobForm) {
      editWorkshopJobForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (editWorkshopJobForm.checkValidity()) {
          const formData = new FormData(editWorkshopJobForm);
          const jobId = formData.get('id');

          formData.append('_method', 'PUT');

          fetch(`/workshop-jobs/${jobId}`, {
              method: 'POST',
              body: formData,
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Close modal and reset form
                bootstrap.Modal.getInstance(document.getElementById('editWorkshopJobModal')).hide();
                editWorkshopJobForm.reset();
                editWorkshopJobForm.classList.remove('was-validated');

                // Reload table
                dt_workshop_jobs.ajax.reload();

                showToast('Success', data.message || 'Workshop job updated successfully!', 'success');
              } else {
                // Handle validation errors
                if (data.errors) {
                  displayValidationErrors(data.errors);
                } else {
                  showToast('Error', data.message || 'Failed to update workshop job', 'error');
                }
              }
            })
            .catch(error => {
              console.error('Error:', error);
              showToast('Error', 'An error occurred while updating the workshop job', 'error');
            });
        }

        editWorkshopJobForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function (e) {
      if (e.target.closest('.edit-job')) {
        const jobId = e.target.closest('.edit-job').dataset.id;
        editWorkshopJob(jobId);
      }

      if (e.target.closest('.view-job')) {
        const jobId = e.target.closest('.view-job').dataset.id;
        viewWorkshopJob(jobId);
      }

      if (e.target.closest('.delete-job')) {
        const jobId = e.target.closest('.delete-job').dataset.id;
        deleteWorkshopJob(jobId);
      }

      if (e.target.closest('.approve-job')) {
        const jobId = e.target.closest('.approve-job').dataset.id;
        updateJobStatus(jobId, 'approved');
      }

      if (e.target.closest('.reject-job')) {
        const jobId = e.target.closest('.reject-job').dataset.id;
        updateJobStatus(jobId, 'rejected');
      }

      if (e.target.closest('.paid-job')) {
        const jobId = e.target.closest('.paid-job').dataset.id;
        updateJobStatus(jobId, 'paid');
      }

      // Edit job from view modal
      if (e.target.closest('#editFromView')) {
        const jobId = document.getElementById('viewWorkshopJobModal').getAttribute('data-job-id');
        // Close view modal
        bootstrap.Modal.getInstance(document.getElementById('viewWorkshopJobModal')).hide();
        // Open edit modal
        setTimeout(() => {
          editWorkshopJob(jobId);
        }, 300);
      }

      // Delete job from view modal
      if (e.target.closest('#deleteFromView')) {
        const jobId = document.getElementById('viewWorkshopJobModal').getAttribute('data-job-id');
        if (jobId) {
          bootstrap.Modal.getInstance(document.getElementById('viewWorkshopJobModal')).hide();
          setTimeout(() => {
            deleteWorkshopJob(jobId);
          }, 300);
        }
      }
    });

    // Edit Workshop Job Function
    function editWorkshopJob(jobId) {
      fetch(`/workshop-jobs/${jobId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const job = data.data;

            // Populate edit form
            document.getElementById('editWorkshopJobId').value = job.id;
            document.getElementById('edit_parts_detail').value = job.parts_detail;
            // document.getElementById('edit_invoice').value = job.invoice;
            document.getElementById('edit_vehicle_id').value = job.vehicle_id;
            document.getElementById('edit_reconciliation').value = job.reconciliation;
            document.getElementById('edit_location').value = job.location;
            document.getElementById('edit_vendor_name').value = job.vendor_name;
            // document.getElementById('edit_status').value = job.status;
            document.getElementById('edit_description').value = job.description;
            document.getElementById('edit_type').value = job.type;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editWorkshopJobModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load workshop job details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading workshop job details', 'error');
        });
    }

    // View Workshop Job Function
    function viewWorkshopJob(jobId) {
      fetch(`/workshop-jobs/${jobId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const job = data.data;

            // Update modal content
            document.getElementById('viewJobAvatar').innerHTML = '<i class="ti tabler-wrench fs-2"></i>';

            // Basic information
            document.getElementById('viewJobTitle').textContent = job.parts_detail;
            document.getElementById('viewJobInvoice').textContent = job.invoice;

            // Status
            const statusClass = {
              requested: 'warning',
              approved: 'success',
              paid: 'info',
              rejected: 'danger'
            }[job.status] || 'secondary';
            document.getElementById('viewJobStatus').textContent = job.status.charAt(0).toUpperCase() + job.status.slice(1);
            document.getElementById('viewJobStatus').className = `badge bg-label-${statusClass} ms-1`;

            // Details
            document.getElementById('viewJobParts').textContent = job.parts_detail || '-';
            document.getElementById('viewJobVehicle').textContent = job.vehicle ? `${job.vehicle.name} - ${job.vehicle.registration_number}` : '-';
            document.getElementById('viewJobPrice').textContent = job.reconciliation ? `PKR ${parseFloat(job.reconciliation).toFixed(2)}` : '-';

            // Vendor information
            document.getElementById('viewJobVendor').textContent = job.vendor_name || '-';
            document.getElementById('viewJobLocation').textContent = job.location || '-';

            // Additional information
            document.getElementById('viewJobType').textContent = job.type ? job.type.toUpperCase() : '-';
            document.getElementById('viewJobDescription').textContent = job.description || '-';
            const slipImageDiv = document.getElementById('viewJobSlipImage');
            const slipDownloadDiv = document.getElementById('viewJobSlipDownload');
            if (job.slip_image) {
              slipImageDiv.innerHTML = `<img src="/storage/${job.slip_image}" alt="Slip Image" class="img-fluid" style="max-width: 200px;">`;
              slipDownloadDiv.innerHTML = `<a href="/storage/${job.slip_image}" download class="btn btn-sm btn-outline-primary mt-1">
                <i class="ti tabler-download"></i> Download Slip
              </a>`;
            } else {
              slipImageDiv.textContent = '-';
              slipDownloadDiv.innerHTML = '';
            }

            // Store job data for other buttons
            const viewModalElement = document.getElementById('viewWorkshopJobModal');
            viewModalElement.setAttribute('data-job-id', job.id);

            // Show modal
            const viewModal = new bootstrap.Modal(viewModalElement);
            viewModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load workshop job details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading workshop job details', 'error');
        });
    }

    // Delete Workshop Job Function
    function deleteWorkshopJob(jobId) {
      if (confirm('Are you sure you want to delete this workshop job? This action cannot be undone.')) {
        fetch(`/workshop-jobs/${jobId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Reload table
              dt_workshop_jobs.ajax.reload();

              showToast('Success', data.message || 'Workshop job deleted successfully!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to delete workshop job', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while deleting the workshop job', 'error');
          });
      }
    }

    // Update Job Status Function
    function updateJobStatus(jobId, status) {
      fetch(`/workshop-jobs/${jobId}/status`, {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload table
            dt_workshop_jobs.ajax.reload();
            showToast('Success', `Job status updated to ${status}!`, 'success');
          } else {
            showToast('Error', data.message || 'Failed to update job status', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating job status', 'error');
        });
    }

    // Toast notification function
    function showToast(title, message, type = 'info') {
      // Use the base toast component
      if (typeof window.showToast === 'function') {
        window.showToast(title, message, type);
      } else {
        // Fallback alert if toast component not available
        alert(`${title}: ${message}`);
      }
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
</style>
@endsection
