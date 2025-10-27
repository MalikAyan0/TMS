@extends('layouts/layoutMaster')

@section('title', 'Fuel Type Management - System Management')

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
<!-- Include Toast Container Component -->
<x-toast-container />

<!-- Fuel Type Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Fuel Type Management</h4>
      <p class="card-subtitle mb-0">Manage vehicle fuel types and specifications</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFuelTypeModal">
      <i class="ti tabler-plus me-1"></i>
      Add New Fuel Type
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-fuel-types table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Label</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Fuel Type Modal -->
<div class="modal fade" id="addFuelTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-gas-station me-2"></i>
          Add New Fuel Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addFuelTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="fuelTypeLabel" class="form-label">Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="fuelTypeLabel" name="fuelTypeLabel"
                       placeholder="e.g., Diesel, Gasoline, Electric" required>
                <div class="invalid-feedback">Please provide a valid fuel type label.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Fuel Type
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Fuel Type Modal -->
<div class="modal fade" id="editFuelTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Fuel Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editFuelTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editFuelTypeId" name="editFuelTypeId">
          <div class="row g-3">
            <div class="col-12">
              <label for="editFuelTypeLabel" class="form-label">Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editFuelTypeLabel" name="editFuelTypeLabel"
                       placeholder="e.g., Diesel, Gasoline, Electric" required>
                <div class="invalid-feedback">Please provide a valid fuel type label.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Fuel Type
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Fuel Type Modal -->
<div class="modal fade" id="viewFuelTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Fuel Type Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Basic Information -->
          <div class="col-12">
            <div class="card border-0 ">
              <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-info-circle me-2"></i>
                  Basic Information
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Fuel Type ID</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-hash me-2 text-primary"></i>
                      <span id="viewFuelTypeId" class="fw-medium">-</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Status</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-circle-check me-2"></i>
                      <span id="viewFuelTypeStatus" class="badge">-</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Fuel Type Details -->
          <div class="col-12">
            <div class="card border-0 ">
              <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-gas-station me-2"></i>
                  Label
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3">
                  <div class="col-12">
                    <div class="d-flex align-items-center justify-content-center p-3 rounded  h-100 mb-3">
                      <div class="text-center">
                        <i id="viewFuelTypeIcon" class="ti tabler-gas-station text-primary fs-1 mb-2 "></i>
                        <div id="viewFuelTypeLabel" class="fw-medium fs-5 mb-0">-</div>
                        <small class="text-muted">Fuel Type</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Timestamps -->
          <div class="col-12">
            <div class="card border-0 ">
              <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-clock me-2"></i>
                  Timestamps
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Created Date</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-calendar-plus me-2 text-success"></i>
                      <div>
                        <div id="viewFuelTypeCreatedDate" class="fw-medium">-</div>
                        <small id="viewFuelTypeCreatedTime" class="text-muted">-</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Last Updated</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-calendar-event me-2 text-info"></i>
                      <div>
                        <div id="viewFuelTypeUpdatedDate" class="fw-medium">-</div>
                        <small id="viewFuelTypeUpdatedTime" class="text-muted">-</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex gap-2">
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
        </div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Page Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Ensure ToastManager is available - Fallback implementation
    if (typeof window.ToastManager === 'undefined') {
      console.warn('ToastManager not found, creating fallback...');

      // Create toast container if it doesn't exist
      let toastContainer = document.getElementById('toastContainer');
      if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        toastContainer.style.top = '20px';
        toastContainer.style.right = '20px';
        toastContainer.id = 'toastContainer';
        document.body.appendChild(toastContainer);
      }

      // Fallback ToastManager implementation
      window.ToastManager = {
        container: toastContainer,

        show: function(title, message, type = 'info', duration = 5000) {
          const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);

          // Icon mapping for different types
          const iconMap = {
            success: 'ti tabler-check',
            error: 'ti tabler-x',
            warning: 'ti tabler-alert-triangle',
            info: 'ti tabler-info-circle',
            primary: 'ti tabler-bell'
          };

          // Color mapping for different types
          const colorMap = {
            success: 'text-success',
            error: 'text-danger',
            warning: 'text-warning',
            info: 'text-info',
            primary: 'text-primary'
          };

          const icon = iconMap[type] || iconMap.info;
          const iconColor = colorMap[type] || colorMap.info;

          const toastHtml = `
            <div class="bs-toast toast toast-ex fade"
                role="alert" aria-live="assertive" aria-atomic="true"
                data-bs-delay="${duration}" id="${toastId}">
              <div class="toast-header">
                <i class="icon-base ${icon} icon-xs me-2 ${iconColor}"></i>
                <div class="me-auto fw-medium">${this.escapeHtml(title)}</div>
                <small class="text-body-secondary">now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">${this.escapeHtml(message)}</div>
            </div>
          `;

          // Insert toast into container
          this.container.insertAdjacentHTML('beforeend', toastHtml);

          // Get the toast element and show it
          const toastElement = document.getElementById(toastId);
          const toast = new bootstrap.Toast(toastElement, {
            delay: duration
          });

          // Show the toast
          toast.show();

          // Remove toast from DOM after it's hidden
          toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
          });

          return toast;
        },

        success: function(title, message, duration = 5000) {
          return this.show(title, message, 'success', duration);
        },

        error: function(title, message, duration = 7000) {
          return this.show(title, message, 'error', duration);
        },

        warning: function(title, message, duration = 6000) {
          return this.show(title, message, 'warning', duration);
        },

        info: function(title, message, duration = 5000) {
          return this.show(title, message, 'info', duration);
        },

        primary: function(title, message, duration = 5000) {
          return this.show(title, message, 'primary', duration);
        },

        clear: function() {
          const toasts = this.container.querySelectorAll('.toast');
          toasts.forEach(toast => {
            const bsToast = bootstrap.Toast.getInstance(toast);
            if (bsToast) {
              bsToast.hide();
            }
          });
        },

        // Utility function to escape HTML
        escapeHtml: function(text) {
          const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
          };
          return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
      };
    }

    // Initialize DataTable
    const dt_fuel_types_table = document.querySelector('.datatables-fuel-types');
    let dt_fuel_types;

    if (dt_fuel_types_table) {
      dt_fuel_types = new DataTable(dt_fuel_types_table, {
        ajax: {
          url: '/fuel-types',
          type: 'GET',
          dataSrc: function(response) {
            return response.data;
          },
          error: function(xhr, error, code) {
            console.error('Error loading fuel types:', error);
            window.ToastManager.error('Error', 'Failed to load fuel types');
            return [];
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
              return meta.settings._iDisplayStart + meta.row + 1;
            }
          },
          {
            data: 'label',
            render: function(data, type, full) {
              const fuelIcons = {
                'Diesel': 'ti tabler-gas-station',
                'Gasoline': 'ti tabler-gas-station',
                'Electric': 'ti tabler-bolt',
                'Hybrid': 'ti tabler-leaf',
                'CNG': 'ti tabler-flame',
                'LPG': 'ti tabler-flame',
                'Biodiesel': 'ti tabler-leaf',
                'Ethanol': 'ti tabler-droplet'
              };
              const icon = fuelIcons[data] || 'ti tabler-gas-station';
              return `<div class="d-flex align-items-center">
                        <i class="${icon} me-2 text-primary"></i>
                        <div class="d-flex flex-column">
                          <span class="fw-medium">${data}</span>
                          <small class="text-muted">Fuel Type</small>
                        </div>
                      </div>`;
            }
          },
          {
            data: 'status',
            render: function(data) {
              const statusConfig = {
                active: { class: 'success', text: 'Active' },
                inactive: { class: 'secondary', text: 'Inactive' }
              };
              const config = statusConfig[data] || statusConfig.inactive;
              return `<span class="badge bg-label-${config.class}">${config.text}</span>`;
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
            orderable: false,
            searchable: false,
            render: function(data, type, full) {
              return `
                <div class="d-flex align-items-center gap-1">
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-secondary view-fuel-type" data-id="${full.id}" title="View">
                    <i class="ti tabler-eye"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-primary edit-fuel-type" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-danger delete-fuel-type" data-id="${full.id}" title="Delete">
                    <i class="ti tabler-trash"></i>
                  </a>
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
              placeholder: 'Search fuel types...'
            }
          }
        },
        responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function(row) {
                const data = row.data();
                return 'Details of ' + data.label;
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

    // Add Fuel Type Form Submission
    const addFuelTypeForm = document.getElementById('addFuelTypeForm');
    if (addFuelTypeForm) {
      addFuelTypeForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addFuelTypeForm.checkValidity()) {
          const formData = new FormData(addFuelTypeForm);
          const data = {
            label: formData.get('fuelTypeLabel')
          };

          fetch('/fuel-types', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              // Refresh DataTable
              dt_fuel_types.ajax.reload();

              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('addFuelTypeModal')).hide();
              addFuelTypeForm.reset();
              addFuelTypeForm.classList.remove('was-validated');

              // Show success message
              window.ToastManager.success('Success', result.message || 'Fuel type added successfully!');
            } else {
              window.ToastManager.error('Error', result.message || 'Failed to add fuel type');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while adding the fuel type');
          });
        }

        addFuelTypeForm.classList.add('was-validated');
      });
    }

    // Edit Fuel Type Form Submission
    const editFuelTypeForm = document.getElementById('editFuelTypeForm');
    if (editFuelTypeForm) {
      editFuelTypeForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editFuelTypeForm.checkValidity()) {
          const formData = new FormData(editFuelTypeForm);
          const fuelTypeId = formData.get('editFuelTypeId');
          const data = {
            label: formData.get('editFuelTypeLabel')
          };

          fetch(`/fuel-types/${fuelTypeId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              // Refresh DataTable
              dt_fuel_types.ajax.reload();

              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('editFuelTypeModal')).hide();
              editFuelTypeForm.reset();
              editFuelTypeForm.classList.remove('was-validated');

              // Show success message
              window.ToastManager.success('Success', result.message || 'Fuel type updated successfully!');
            } else {
              window.ToastManager.error('Error', result.message || 'Failed to update fuel type');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while updating the fuel type');
          });
        }

        editFuelTypeForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-fuel-type')) {
        const fuelTypeId = e.target.closest('.edit-fuel-type').dataset.id;

        fetch(`/fuel-types/${fuelTypeId}`)
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              const fuelType = result.data;
              // Populate edit form
              document.getElementById('editFuelTypeId').value = fuelType.id;
              document.getElementById('editFuelTypeLabel').value = fuelType.label;

              // Show edit modal
              const editModal = new bootstrap.Modal(document.getElementById('editFuelTypeModal'));
              editModal.show();
            } else {
              window.ToastManager.error('Error', 'Failed to load fuel type details');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while loading fuel type details');
          });
      }

      if (e.target.closest('.view-fuel-type')) {
        const fuelTypeId = e.target.closest('.view-fuel-type').dataset.id;

        fetch(`/fuel-types/${fuelTypeId}`)
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              const fuelType = result.data;

              // Populate view modal with fuel type data
              document.getElementById('viewFuelTypeId').textContent = fuelType.id;
              document.getElementById('viewFuelTypeLabel').textContent = fuelType.label;

              // Set status badge
              const statusElement = document.getElementById('viewFuelTypeStatus');
              statusElement.textContent = fuelType.status === 'active' ? 'Active' : 'Inactive';
              statusElement.className = `badge bg-label-${fuelType.status === 'active' ? 'success' : 'secondary'}`;

              // Set fuel type icon based on label
              const fuelIcons = {
                'Diesel': 'ti tabler-gas-station',
                'Gasoline': 'ti tabler-gas-station',
                'Electric': 'ti tabler-bolt',
                'Hybrid': 'ti tabler-leaf',
                'CNG': 'ti tabler-flame',
                'LPG': 'ti tabler-flame',
                'Biodiesel': 'ti tabler-leaf',
                'Ethanol': 'ti tabler-droplet'
              };
              const iconElement = document.getElementById('viewFuelTypeIcon');
              iconElement.className = `${fuelIcons[fuelType.label] || 'ti tabler-gas-station'} text-primary fs-1 mb-2`;

              // Format and set dates
              const createdDate = new Date(fuelType.created_at);
              const updatedDate = new Date(fuelType.updated_at);

              document.getElementById('viewFuelTypeCreatedDate').textContent = createdDate.toLocaleDateString();
              document.getElementById('viewFuelTypeCreatedTime').textContent = createdDate.toLocaleTimeString();
              document.getElementById('viewFuelTypeUpdatedDate').textContent = updatedDate.toLocaleDateString();
              document.getElementById('viewFuelTypeUpdatedTime').textContent = updatedDate.toLocaleTimeString();

              // Setup quick action buttons
              const editBtn = document.getElementById('editFromView');
              const toggleBtn = document.getElementById('toggleFromView');
              const deleteBtn = document.getElementById('deleteFromView');
              const duplicateBtn = document.getElementById('duplicateFromView');

              // Update toggle button text and icon
              toggleBtn.innerHTML = `<i class="ti tabler-toggle-${fuelType.status === 'active' ? 'left' : 'right'} me-1"></i>
                                    ${fuelType.status === 'active' ? 'Deactivate' : 'Activate'}`;
              toggleBtn.className = `btn btn-outline-${fuelType.status === 'active' ? 'warning' : 'success'}`;

              // Set data attributes for quick actions
              editBtn.dataset.id = fuelType.id;
              toggleBtn.dataset.id = fuelType.id;
              toggleBtn.dataset.status = fuelType.status;
              deleteBtn.dataset.id = fuelType.id;
              duplicateBtn.dataset.id = fuelType.id;

              // Show view modal
              const viewModal = new bootstrap.Modal(document.getElementById('viewFuelTypeModal'));
              viewModal.show();
            } else {
              window.ToastManager.error('Error', 'Failed to load fuel type details');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while loading fuel type details');
          });
      }

      if (e.target.closest('.toggle-status')) {
        const fuelTypeId = e.target.closest('.toggle-status').dataset.id;

        fetch(`/fuel-types/${fuelTypeId}/toggle`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            // Refresh DataTable
            dt_fuel_types.ajax.reload();
            window.ToastManager.success('Success', result.message || 'Fuel type status updated successfully!');
          } else {
            window.ToastManager.error('Error', result.message || 'Failed to update fuel type status');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while updating status');
        });
      }

      if (e.target.closest('.delete-fuel-type')) {
        const fuelTypeId = e.target.closest('.delete-fuel-type').dataset.id;

        if (confirm('Are you sure you want to delete this fuel type?')) {
          fetch(`/fuel-types/${fuelTypeId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              // Refresh DataTable
              dt_fuel_types.ajax.reload();
              window.ToastManager.success('Success', result.message || 'Fuel type deleted successfully!');
            } else {
              window.ToastManager.error('Error', result.message || 'Failed to delete fuel type');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while deleting the fuel type');
          });
        }
      }
    });

    // Quick action buttons in view modal
    document.addEventListener('click', function(e) {
      // Edit button in view modal
      if (e.target.closest('#editFromView')) {
        const fuelTypeId = e.target.closest('#editFromView').dataset.id;

        // Close view modal first
        bootstrap.Modal.getInstance(document.getElementById('viewFuelTypeModal')).hide();

        // Fetch and populate edit modal
        fetch(`/fuel-types/${fuelTypeId}`)
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              const fuelType = result.data;
              document.getElementById('editFuelTypeId').value = fuelType.id;
              document.getElementById('editFuelTypeLabel').value = fuelType.label;

              // Show edit modal
              const editModal = new bootstrap.Modal(document.getElementById('editFuelTypeModal'));
              editModal.show();
            } else {
              window.ToastManager.error('Error', 'Failed to load fuel type details');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while loading fuel type details');
          });
      }

      // Toggle status button in view modal
      if (e.target.closest('#toggleFromView')) {
        const fuelTypeId = e.target.closest('#toggleFromView').dataset.id;

        fetch(`/fuel-types/${fuelTypeId}/toggle`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            // Refresh DataTable
            dt_fuel_types.ajax.reload();

            // Close view modal
            bootstrap.Modal.getInstance(document.getElementById('viewFuelTypeModal')).hide();

            window.ToastManager.success('Success', result.message || 'Fuel type status updated successfully!');
          } else {
            window.ToastManager.error('Error', result.message || 'Failed to update fuel type status');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while updating status');
        });
      }

      // Delete button in view modal
      if (e.target.closest('#deleteFromView')) {
        const fuelTypeId = e.target.closest('#deleteFromView').dataset.id;

        if (confirm('Are you sure you want to delete this fuel type?')) {
          fetch(`/fuel-types/${fuelTypeId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              // Refresh DataTable
              dt_fuel_types.ajax.reload();

              // Close view modal
              bootstrap.Modal.getInstance(document.getElementById('viewFuelTypeModal')).hide();

              window.ToastManager.success('Success', result.message || 'Fuel type deleted successfully!');
            } else {
              window.ToastManager.error('Error', result.message || 'Failed to delete fuel type');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while deleting the fuel type');
          });
        }
      }

      // Duplicate button in view modal
      if (e.target.closest('#duplicateFromView')) {
        const fuelTypeId = e.target.closest('#duplicateFromView').dataset.id;

        fetch(`/fuel-types/${fuelTypeId}`)
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              const fuelType = result.data;

              // Close view modal
              bootstrap.Modal.getInstance(document.getElementById('viewFuelTypeModal')).hide();

              // Pre-populate add form with current fuel type data
              document.getElementById('fuelTypeLabel').value = fuelType.label + ' (Copy)';

              // Show add modal for duplication
              const addModal = new bootstrap.Modal(document.getElementById('addFuelTypeModal'));
              addModal.show();

              window.ToastManager.info('Duplicate Mode', 'Fuel type data loaded for duplication. Modify and save as needed.');
            } else {
              window.ToastManager.error('Error', 'Failed to load fuel type details');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while duplicating fuel type');
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

.modal-lg {
  max-width: 800px;
}

.form-text {
  font-size: 0.75rem;
  color: var(--bs-secondary);
}
</style>
@endsection
