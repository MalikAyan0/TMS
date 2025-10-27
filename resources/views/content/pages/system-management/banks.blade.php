@extends('layouts/layoutMaster')

@section('title', 'Banks - System Management')

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
<!-- Banks Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Banks <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage banking <span class="d-none d-sm-inline">institutions and financial partners</span></p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New Bank</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-banks table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Bank</th>
          <th>Short Name</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-building-bank me-2"></i>
          Add New Bank
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addBankForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="bankName" class="form-label">Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building-bank"></i></span>
                <input type="text" class="form-control" id="bankName" name="name"
                       placeholder="e.g., National Bank of Pakistan" required>
                <div class="invalid-feedback">Please provide a valid bank name.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="bankShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="bankShortName" name="short_name"
                       placeholder="e.g., NBP" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
              <div class="form-text">Maximum 10 characters for easy identification</div>
            </div>

            <div class="col-12">
              <label for="bankAddress" class="form-label">Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <textarea class="form-control" id="bankAddress" name="address"
                          rows="3" placeholder="Enter complete bank address" required></textarea>
                <div class="invalid-feedback">Please provide a valid address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="bankContact" class="form-label">Contact <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-phone"></i></span>
                <input type="tel" class="form-control" id="bankContact" name="contact"
                       placeholder="e.g., +92-21-1234567" required>
                <div class="invalid-feedback">Please provide a valid contact number.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="bankEmail" class="form-label">Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-mail"></i></span>
                <input type="email" class="form-control" id="bankEmail" name="email"
                       placeholder="e.g., info@nbp.com.pk" required>
                <div class="invalid-feedback">Please provide a valid email address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="bankContactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-user"></i></span>
                <input type="text" class="form-control" id="bankContactPerson" name="contact_person"
                       placeholder="e.g., Ahmad Hassan" required>
                <div class="invalid-feedback">Please provide a valid contact person name.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Bank
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Bank Modal -->
<div class="modal fade" id="editBankModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Bank
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editBankForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editBankId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editBankName" class="form-label">Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building-bank"></i></span>
                <input type="text" class="form-control" id="editBankName" name="name"
                       placeholder="e.g., National Bank of Pakistan" required>
                <div class="invalid-feedback">Please provide a valid bank name.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editBankShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editBankShortName" name="short_name"
                       placeholder="e.g., NBP" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
            </div>

            <div class="col-12">
              <label for="editBankAddress" class="form-label">Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <textarea class="form-control" id="editBankAddress" name="address"
                          rows="3" placeholder="Enter complete bank address" required></textarea>
                <div class="invalid-feedback">Please provide a valid address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editBankContact" class="form-label">Contact <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-phone"></i></span>
                <input type="tel" class="form-control" id="editBankContact" name="contact"
                       placeholder="e.g., +92-21-1234567" required>
                <div class="invalid-feedback">Please provide a valid contact number.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editBankEmail" class="form-label">Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-mail"></i></span>
                <input type="email" class="form-control" id="editBankEmail" name="email"
                       placeholder="e.g., info@nbp.com.pk" required>
                <div class="invalid-feedback">Please provide a valid email address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editBankContactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-user"></i></span>
                <input type="text" class="form-control" id="editBankContactPerson" name="contact_person"
                       placeholder="e.g., Ahmad Hassan" required>
                <div class="invalid-feedback">Please provide a valid contact person name.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Bank
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Bank Modal -->
<div class="modal fade" id="viewBankModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Bank Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- Bank Header -->
          <div class="col-12">
            <div class="card ">
              <div class="card-body text-center">
                <div class="avatar avatar-md mx-auto mb-3">
                  <span class="avatar-initial rounded-circle bg-label-primary" id="viewBankAvatar">
                    <i class="ti tabler-building-bank fs-2"></i>
                  </span>
                </div>
                <h4 class="mb-1" id="viewBankTitle">-</h4>
                <p class="text-muted mb-0">
                  <span class="badge bg-label-primary font-monospace" id="viewBankShortName">-</span>
                  <span class="badge bg-label-secondary ms-1" id="viewBankStatus">-</span>
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
              <div class="card-body mt-3">
                <div class="mb-3">
                  <label class="form-label fw-medium">Bank Name</label>
                  <p class="mb-0" id="viewBankName">-</p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-medium">Short Code</label>
                  <p class="mb-0 font-monospace" id="viewBankCode">-</p>
                </div>
                <div class="mb-0">
                  <label class="form-label fw-medium">Created Date</label>
                  <p class="mb-0" id="viewBankCreated">-</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-phone me-1"></i>
                  Contact Information
                </h6>
              </div>
              <div class="card-body">
                <div class="mb-3 mt-3">
                  <label class="form-label fw-medium">Contact Person</label>
                  <p class="mb-0" id="viewBankContactPerson">-</p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-medium">Phone</label>
                  <p class="mb-0">
                    <a href="#" id="viewBankContact" class="text-decoration-none">-</a>
                  </p>
                </div>
                <div class="mb-0">
                  <label class="form-label fw-medium">Email</label>
                  <p class="mb-0">
                    <a href="#" id="viewBankEmail" class="text-decoration-none">-</a>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Address Information -->
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-map-pin me-1"></i>
                  Address Information
                </h6>
              </div>
              <div class="card-body">
                <p class="mb-0 mt-3" id="viewBankAddress">-</p>
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
                <div class="row mt-3">
                  <div class="col-md-4">
                    <label class="form-label fw-medium">Status</label>
                    <p class="mb-0">
                      <span class="badge" id="viewBankStatusBadge">-</span>
                    </p>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label fw-medium">Last Updated</label>
                    <p class="mb-0" id="viewBankUpdated">-</p>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label fw-medium">Bank ID</label>
                    <p class="mb-0 font-monospace" id="viewBankId">-</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="editFromView">
          <i class="ti tabler-edit me-1"></i>
          Edit
        </button>
        <button type="button" class="btn btn-outline-warning" id="toggleFromView">
          <i class="ti tabler-toggle-right me-1"></i>
          Toggle Status
        </button>
        <button type="button" class="btn btn-outline-success" id="duplicateFromView">
          <i class="ti tabler-copy me-1"></i>
          Duplicate
        </button>
        <button type="button" class="btn btn-outline-danger" id="deleteFromView">
          <i class="ti tabler-trash me-1"></i>
          Delete
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />

<!-- Page Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let dt_banks;

    // Initialize DataTable
    const dt_banks_table = document.querySelector('.datatables-banks');
    if (dt_banks_table) {
      dt_banks = new DataTable(dt_banks_table, {
        ajax: {
          url: '/banks',
          type: 'GET',
          dataSrc: function(json) {
            if (json.success) {
              return json.data;
            } else {
              showToast('Error', json.message || 'Failed to load banks', 'error');
              return [];
            }
          },
          error: function(xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load banks. Please try again.', 'error');
          }
        },
        columns: [
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function() {
              return '';
            }
          },
          {
            data: null,
            render: function(data, type, row, meta) {
              return meta.row + 1;
            }
          },
          {
            data: 'name',
            responsivePriority: 1,
            render: function(data, type, full) {
              return `<div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-2">
                          <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="ti tabler-building-bank"></i>
                          </span>
                        </div>
                        <div class="d-flex flex-column">
                          <span class="fw-medium">${data}</span>
                          <small class="text-muted">${full.short_name}</small>
                        </div>
                      </div>`;
            }
          },
          {
            data: 'short_name',
            render: function(data, type, full) {
              const statusColors = {
                active: 'primary',
                inactive: 'secondary'
              };
              const color = statusColors[full.status] || 'secondary';
              return `<span class="badge bg-label-${color} font-monospace">${data}</span>`;
            }
          },

          {
            data: 'status',
            render: function(data) {
              const statusConfig = {
                active: { class: 'success', text: 'Active', icon: 'check' },
                inactive: { class: 'secondary', text: 'Inactive', icon: 'x' }
              };
              const config = statusConfig[data] || statusConfig.inactive;
              return `<span class="badge bg-label-${config.class}">
                        <i class="ti tabler-${config.icon} me-1"></i>${config.text}
                      </span>`;
            }
          },
          {
            data: 'created_at',
            render: function(data) {
              return new Date(data).toLocaleDateString();
            }
          },
          {
            data: null,
            responsivePriority: 2,
            orderable: false,
            searchable: false,
            render: function(data, type, full) {
              return `
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-bank" data-id="${full.id}" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-bank" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-bank" data-id="${full.id}" title="Delete">
                    <i class="ti tabler-trash"></i>
                  </button>
                </div>
              `;
            }
          }
        ],
        columnDefs: [
          {
            className: 'control',
            orderable: false,
            targets: 0,
            render: function() {
              return '';
            }
          }
        ],
        order: [[1, 'asc']],
        layout: {
          topEnd: {
            search: {
              placeholder: 'Search banks...'
            }
          }
        },
        responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function(row) {
                const data = row.data();
                return 'Details of ' + data.name;
              }
            }),
            type: 'column',
            renderer: function(api, rowIdx, columns) {
              const data = columns
                .map(function(col) {
                  return col.title !== ''
                    ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        <td><strong>${col.title}:</strong></td>
                        <td>${col.data}</td>
                      </tr>`
                    : '';
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
        name: 'Bank Name',
        short_name: 'Short Name',
        address: 'Address',
        contact: 'Contact',
        email: 'Email',
        contact_person: 'Contact Person'
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

    // Auto-generate short name from name
    document.getElementById('bankName').addEventListener('input', function() {
      const name = this.value;
      const shortName = name
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 10);
      document.getElementById('bankShortName').value = shortName;
    });

    // Add Bank Form Submission
    const addBankForm = document.getElementById('addBankForm');
    if (addBankForm) {
      addBankForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addBankForm.checkValidity()) {
          const formData = new FormData(addBankForm);

          fetch('/banks', {
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
              bootstrap.Modal.getInstance(document.getElementById('addBankModal')).hide();
              addBankForm.reset();
              addBankForm.classList.remove('was-validated');

              // Reload table
              dt_banks.ajax.reload();

              showToast('Success', data.message || 'Bank added successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to add bank', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while adding the bank', 'error');
          });
        }

        addBankForm.classList.add('was-validated');
      });
    }

    // Edit Bank Form Submission
    const editBankForm = document.getElementById('editBankForm');
    if (editBankForm) {
      editBankForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editBankForm.checkValidity()) {
          const formData = new FormData(editBankForm);
          const bankId = formData.get('id');

          formData.append('_method', 'PUT');

          fetch(`/banks/${bankId}`, {
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
              bootstrap.Modal.getInstance(document.getElementById('editBankModal')).hide();
              editBankForm.reset();
              editBankForm.classList.remove('was-validated');

              // Reload table
              dt_banks.ajax.reload();

              showToast('Success', data.message || 'Bank updated successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to update bank', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating the bank', 'error');
          });
        }

        editBankForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-bank')) {
        const bankId = e.target.closest('.edit-bank').dataset.id;
        editBank(bankId);
      }

      if (e.target.closest('.view-bank')) {
        const bankId = e.target.closest('.view-bank').dataset.id;
        viewBank(bankId);
      }

      if (e.target.closest('.toggle-status')) {
        const bankId = e.target.closest('.toggle-status').dataset.id;
        const currentStatus = e.target.closest('.toggle-status').dataset.status;
        toggleBankStatus(bankId, currentStatus);
      }

      if (e.target.closest('.delete-bank')) {
        const bankId = e.target.closest('.delete-bank').dataset.id;
        deleteBank(bankId);
      }

      // Edit bank from view modal
      if (e.target.closest('#editFromView')) {
        const bankId = document.getElementById('viewBankModal').getAttribute('data-bank-id');
        // Close view modal
        bootstrap.Modal.getInstance(document.getElementById('viewBankModal')).hide();
        // Open edit modal
        setTimeout(() => {
          editBank(bankId);
        }, 300);
      }

      // Toggle status from view modal
      if (e.target.closest('#toggleFromView')) {
        const bankId = document.getElementById('viewBankModal').getAttribute('data-bank-id');
        const currentStatus = getCurrentBankStatus();
        if (bankId) {
          bootstrap.Modal.getInstance(document.getElementById('viewBankModal')).hide();
          setTimeout(() => {
            toggleBankStatus(bankId, currentStatus);
          }, 300);
        }
      }

      // Duplicate bank from view modal
      if (e.target.closest('#duplicateFromView')) {
        const bankId = document.getElementById('viewBankModal').getAttribute('data-bank-id');
        if (bankId) {
          bootstrap.Modal.getInstance(document.getElementById('viewBankModal')).hide();
          setTimeout(() => {
            duplicateBank(bankId);
          }, 300);
        }
      }

      // Delete bank from view modal
      if (e.target.closest('#deleteFromView')) {
        const bankId = document.getElementById('viewBankModal').getAttribute('data-bank-id');
        if (bankId) {
          bootstrap.Modal.getInstance(document.getElementById('viewBankModal')).hide();
          setTimeout(() => {
            deleteBank(bankId);
          }, 300);
        }
      }
    });

    // Get current bank status from view modal
    function getCurrentBankStatus() {
      const viewModal = document.getElementById('viewBankModal');
      return viewModal.getAttribute('data-bank-status');
    }

    // Duplicate Bank Function
    function duplicateBank(bankId) {
      fetch(`/banks/${bankId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const bank = data.data;

            // Populate add form with existing data (excluding ID)
            document.getElementById('bankName').value = bank.name + ' (Copy)';
            document.getElementById('bankShortName').value = bank.short_name + 'C';
            document.getElementById('bankAddress').value = bank.address;
            document.getElementById('bankContact').value = bank.contact;
            document.getElementById('bankEmail').value = bank.email;
            document.getElementById('bankContactPerson').value = bank.contact_person;

            // Show add modal
            const addModal = new bootstrap.Modal(document.getElementById('addBankModal'));
            addModal.show();

            showToast('Info', 'Bank data loaded for duplication. Please review and save.', 'info');
          } else {
            showToast('Error', data.message || 'Failed to load bank details for duplication', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading bank details for duplication', 'error');
        });
    }

    // Edit Bank Function
    function editBank(bankId) {
      fetch(`/banks/${bankId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const bank = data.data;

            // Populate edit form
            document.getElementById('editBankId').value = bank.id;
            document.getElementById('editBankName').value = bank.name;
            document.getElementById('editBankShortName').value = bank.short_name;
            document.getElementById('editBankAddress').value = bank.address;
            document.getElementById('editBankContact').value = bank.contact;
            document.getElementById('editBankEmail').value = bank.email;
            document.getElementById('editBankContactPerson').value = bank.contact_person;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editBankModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load bank details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading bank details', 'error');
        });
    }

    // View Bank Function
    function viewBank(bankId) {
      fetch(`/banks/${bankId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const bank = data.data;

            // Update modal content
            document.getElementById('viewBankAvatar').innerHTML = '<i class="ti tabler-building-bank fs-2"></i>';

            // Basic information
            document.getElementById('viewBankTitle').textContent = bank.name;
            document.getElementById('viewBankShortName').textContent = bank.short_name;

            // Status
            const statusClass = bank.status === 'active' ? 'success' : 'secondary';
            document.getElementById('viewBankStatus').textContent = bank.status.charAt(0).toUpperCase() + bank.status.slice(1);
            document.getElementById('viewBankStatus').className = `badge bg-label-${statusClass} ms-1`;

            // Details
            document.getElementById('viewBankName').textContent = bank.name || '-';
            document.getElementById('viewBankCode').textContent = bank.short_name || '-';
            document.getElementById('viewBankCreated').textContent = bank.created_at ? new Date(bank.created_at).toLocaleDateString() : '-';

            // Contact information
            document.getElementById('viewBankContactPerson').textContent = bank.contact_person || '-';
            document.getElementById('viewBankContact').textContent = bank.contact || '-';
            document.getElementById('viewBankContact').href = bank.contact ? `tel:${bank.contact}` : '#';
            document.getElementById('viewBankEmail').textContent = bank.email || '-';
            document.getElementById('viewBankEmail').href = bank.email ? `mailto:${bank.email}` : '#';

            // Address
            document.getElementById('viewBankAddress').textContent = bank.address || '-';

            // Additional information
            document.getElementById('viewBankStatusBadge').textContent = bank.status.charAt(0).toUpperCase() + bank.status.slice(1);
            document.getElementById('viewBankStatusBadge').className = `badge bg-label-${statusClass}`;
            document.getElementById('viewBankUpdated').textContent = bank.updated_at ? new Date(bank.updated_at).toLocaleDateString() : '-';
            document.getElementById('viewBankId').textContent = bank.id || '-';

            // Store bank data for other buttons
            const viewModalElement = document.getElementById('viewBankModal');
            viewModalElement.setAttribute('data-bank-id', bank.id);
            viewModalElement.setAttribute('data-bank-status', bank.status);

            // Update toggle button icon and text
            const toggleBtn = document.getElementById('toggleFromView');
            const toggleIcon = bank.status === 'active' ? 'toggle-right' : 'toggle-left';
            toggleBtn.innerHTML = `<i class="ti tabler-${toggleIcon} me-1"></i>Toggle Status`;

            // Show modal
            const viewModal = new bootstrap.Modal(viewModalElement);
            viewModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load bank details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading bank details', 'error');
        });
    }

    // Toggle Bank Status Function
    function toggleBankStatus(bankId, currentStatus) {
      const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
      const action = newStatus === 'active' ? 'activate' : 'deactivate';

      fetch(`/banks/${bankId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload table
          dt_banks.ajax.reload();

          showToast('Success', data.message || `Bank ${action}d successfully!`, 'success');
        } else {
          showToast('Error', data.message || `Failed to ${action} bank`, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', `An error occurred while ${action}ing the bank`, 'error');
      });
    }

    // Delete Bank Function
    function deleteBank(bankId) {
      if (confirm('Are you sure you want to delete this bank? This action cannot be undone.')) {
        fetch(`/banks/${bankId}`, {
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
            dt_banks.ajax.reload();

            showToast('Success', data.message || 'Bank deleted successfully!', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete bank', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting the bank', 'error');
        });
      }
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
