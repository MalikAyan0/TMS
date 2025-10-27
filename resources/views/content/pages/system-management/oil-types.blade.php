@extends('layouts/layoutMaster')

@section('title', 'Oil Types Management - System Management')

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

<!-- Oil Types Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Oil Types <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage vehicle oil types and specifications</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOilTypeModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New Oil Type</span>
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-oil-types table">
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

<!-- Add New Oil Type Modal -->
<div class="modal fade" id="addOilTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-droplet me-2"></i>
          Add New Oil Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addOilTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="oilTypeLabel" class="form-label">Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="oilTypeLabel" name="oilTypeLabel"
                       placeholder="e.g., Engine Oil, Transmission Oil, Brake Oil" required>
                <div class="invalid-feedback">Please provide a valid oil type label.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Oil Type
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Oil Type Modal -->
<div class="modal fade" id="editOilTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Oil Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editOilTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editOilTypeId" name="editOilTypeId">
          <div class="row g-3">
            <div class="col-12">
              <label for="editOilTypeLabel" class="form-label">Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editOilTypeLabel" name="editOilTypeLabel"
                       placeholder="e.g., Engine Oil, Transmission Oil, Brake Oil" required>
                <div class="invalid-feedback">Please provide a valid oil type label.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Oil Type
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Oil Type Modal -->
<div class="modal fade" id="viewOilTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Oil Type Details
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
                    <label class="form-label fw-medium text-muted mb-1">Oil Type ID</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-hash me-2 text-primary"></i>
                      <span id="viewOilTypeId" class="fw-medium">-</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Status</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-circle-check me-2"></i>
                      <span id="viewOilTypeStatus" class="badge">-</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Oil Type Details -->
          <div class="col-12">
            <div class="card border-0 ">
              <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-droplet me-2"></i>
                  Oil Type Details
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label fw-medium text-muted mb-1">Label</label>
                    <div class="d-flex align-items-center p-3 bg-white rounded border">
                      <i id="viewOilTypeIcon" class="ti tabler-droplet me-3 text-warning fs-4"></i>
                      <div>
                        <div id="viewOilTypeLabel" class="fw-medium fs-5 mb-0">-</div>
                        <small class="text-muted">Oil Type Name</small>
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
                        <div id="viewOilTypeCreatedDate" class="fw-medium">-</div>
                        <small id="viewOilTypeCreatedTime" class="text-muted">-</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Last Updated</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-calendar-event me-2 text-info"></i>
                      <div>
                        <div id="viewOilTypeUpdatedDate" class="fw-medium">-</div>
                        <small id="viewOilTypeUpdatedTime" class="text-muted">-</small>
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

          <button type="button" class="btn btn-outline-primary" id="editFromView">
            <i class="ti tabler-edit me-1"></i>
            <span class="d-none d-sm-inline">Edit</span>
          </button>
          <button type="button" class="btn btn-outline-warning" id="toggleFromView">
            <i class="ti tabler-toggle-right me-1"></i>
            <span class="d-none d-sm-inline">Toggle Status</span>
          </button>
          <button type="button" class="btn btn-outline-success" id="duplicateFromView">
            <i class="ti tabler-copy me-1"></i>
            <span class="d-none d-sm-inline">Duplicate</span>
          </button>
          <button type="button" class="btn btn-outline-danger" id="deleteFromView">
            <i class="ti tabler-trash me-1"></i>
            <span class="d-none d-sm-inline">Delete</span>
          </button>
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
  const dt_oil_types_table = document.querySelector('.datatables-oil-types');
  let dt_oil_types;

  if (dt_oil_types_table) {
    dt_oil_types = new DataTable(dt_oil_types_table, {
      ajax: {
        url: '/oil-types',
        type: 'GET',
        dataSrc: function(response) {
          return response.data;
        },
        error: function(xhr, error, code) {
          console.error('Error loading oil types:', error);
          window.ToastManager.error('Error', 'Failed to load oil types');
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
          responsivePriority: 1,
          render: function(data, type, full) {
            const oilIcons = {
              'Engine Oil': 'ti tabler-engine',
              'Transmission Oil': 'ti tabler-manual-gearbox',
              'Brake Oil': 'ti tabler-droplet',
              'Hydraulic Oil': 'ti tabler-droplet',
              'Gear Oil': 'ti tabler-settings',
              'Coolant': 'ti tabler-snowflake',
              'Power Steering Oil': 'ti tabler-steering-wheel',
              'Differential Oil': 'ti tabler-settings-2'
            };
            const icon = oilIcons[data] || 'ti tabler-droplet';
            return `<div class="d-flex align-items-center">
                      <i class="${icon} me-2 text-warning"></i>
                      <div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">Oil Type</small>
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
          responsivePriority: 2,
          orderable: false,
          searchable: false,
          render: function(data, type, full) {
            return `
              <div class="d-flex align-items-center gap-1">
                <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-secondary view-oil-type" data-id="${full.id}" title="View">
                  <i class="ti tabler-eye"></i>
                </a>
                <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-primary edit-oil-type" data-id="${full.id}" title="Edit">
                  <i class="ti tabler-edit"></i>
                </a>
                <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                  <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                </a>
                <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-danger delete-oil-type" data-id="${full.id}" title="Delete">
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
            placeholder: 'Search oil types...'
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

  // Add Oil Type Form Submission
  const addOilTypeForm = document.getElementById('addOilTypeForm');
  if (addOilTypeForm) {
    addOilTypeForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (addOilTypeForm.checkValidity()) {
        const formData = new FormData(addOilTypeForm);
        const data = {
          label: formData.get('oilTypeLabel')
        };

        fetch('/oil-types', {
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
            dt_oil_types.ajax.reload();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addOilTypeModal')).hide();
            addOilTypeForm.reset();
            addOilTypeForm.classList.remove('was-validated');

            // Show success message
            window.ToastManager.success('Success', result.message || 'Oil type added successfully!');
          } else {
            window.ToastManager.error('Error', result.message || 'Failed to add oil type');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while adding the oil type');
        });
      }

      addOilTypeForm.classList.add('was-validated');
    });
  }

  // Edit Oil Type Form Submission
  const editOilTypeForm = document.getElementById('editOilTypeForm');
  if (editOilTypeForm) {
    editOilTypeForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (editOilTypeForm.checkValidity()) {
        const formData = new FormData(editOilTypeForm);
        const oilTypeId = formData.get('editOilTypeId');
        const data = {
          label: formData.get('editOilTypeLabel')
        };

        fetch(`/oil-types/${oilTypeId}`, {
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
            dt_oil_types.ajax.reload();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('editOilTypeModal')).hide();
            editOilTypeForm.reset();
            editOilTypeForm.classList.remove('was-validated');

            // Show success message
            window.ToastManager.success('Success', result.message || 'Oil type updated successfully!');
          } else {
            window.ToastManager.error('Error', result.message || 'Failed to update oil type');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while updating the oil type');
        });
      }

      editOilTypeForm.classList.add('was-validated');
    });
  }

  // Event delegation for action buttons
  document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-oil-type')) {
      const oilTypeId = e.target.closest('.edit-oil-type').dataset.id;

      fetch(`/oil-types/${oilTypeId}`)
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const oilType = result.data;
            // Populate edit form
            document.getElementById('editOilTypeId').value = oilType.id;
            document.getElementById('editOilTypeLabel').value = oilType.label;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editOilTypeModal'));
            editModal.show();
          } else {
            window.ToastManager.error('Error', 'Failed to load oil type details');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while loading oil type details');
        });
    }

    if (e.target.closest('.view-oil-type')) {
      const oilTypeId = e.target.closest('.view-oil-type').dataset.id;

      fetch(`/oil-types/${oilTypeId}`)
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const oilType = result.data;

            // Populate view modal with oil type data
            document.getElementById('viewOilTypeId').textContent = oilType.id;
            document.getElementById('viewOilTypeLabel').textContent = oilType.label;

            // Set status badge
            const statusElement = document.getElementById('viewOilTypeStatus');
            statusElement.textContent = oilType.status === 'active' ? 'Active' : 'Inactive';
            statusElement.className = `badge bg-label-${oilType.status === 'active' ? 'success' : 'secondary'}`;

            // Set oil type icon based on label
            const oilIcons = {
              'Engine Oil': 'ti tabler-engine',
              'Transmission Oil': 'ti tabler-manual-gearbox',
              'Brake Oil': 'ti tabler-droplet',
              'Hydraulic Oil': 'ti tabler-droplet',
              'Gear Oil': 'ti tabler-settings',
              'Coolant': 'ti tabler-snowflake',
              'Power Steering Oil': 'ti tabler-steering-wheel',
              'Differential Oil': 'ti tabler-settings-2'
            };
            const iconElement = document.getElementById('viewOilTypeIcon');
            iconElement.className = `${oilIcons[oilType.label] || 'ti tabler-droplet'} me-3 text-warning fs-4`;

            // Format and set dates
            const createdDate = new Date(oilType.created_at);
            const updatedDate = new Date(oilType.updated_at);

            document.getElementById('viewOilTypeCreatedDate').textContent = createdDate.toLocaleDateString();
            document.getElementById('viewOilTypeCreatedTime').textContent = createdDate.toLocaleTimeString();
            document.getElementById('viewOilTypeUpdatedDate').textContent = updatedDate.toLocaleDateString();
            document.getElementById('viewOilTypeUpdatedTime').textContent = updatedDate.toLocaleTimeString();

            // Setup quick action buttons
            const editBtn = document.getElementById('editFromView');
            const toggleBtn = document.getElementById('toggleFromView');
            const duplicateBtn = document.getElementById('duplicateFromView');
            const deleteBtn = document.getElementById('deleteFromView');

            // Update toggle button text and icon
            toggleBtn.innerHTML = `<i class="ti tabler-toggle-${oilType.status === 'active' ? 'left' : 'right'} me-1"></i>
                                  <span class="d-none d-sm-inline">${oilType.status === 'active' ? 'Deactivate' : 'Activate'}</span>`;
            toggleBtn.className = `btn btn-outline-${oilType.status === 'active' ? 'warning' : 'success'}`;

            // Set data attributes for quick actions
            editBtn.dataset.id = oilType.id;
            toggleBtn.dataset.id = oilType.id;
            toggleBtn.dataset.status = oilType.status;
            duplicateBtn.dataset.id = oilType.id;
            duplicateBtn.dataset.label = oilType.label;
            deleteBtn.dataset.id = oilType.id;

            // Show view modal
            const viewModal = new bootstrap.Modal(document.getElementById('viewOilTypeModal'));
            viewModal.show();
          } else {
            window.ToastManager.error('Error', 'Failed to load oil type details');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while loading oil type details');
        });
    }

    if (e.target.closest('.toggle-status')) {
      const oilTypeId = e.target.closest('.toggle-status').dataset.id;

      fetch(`/oil-types/${oilTypeId}/toggle`, {
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
          dt_oil_types.ajax.reload();
          window.ToastManager.success('Success', result.message || 'Oil type status updated successfully!');
        } else {
          window.ToastManager.error('Error', result.message || 'Failed to update oil type status');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        window.ToastManager.error('Error', 'An error occurred while updating status');
      });
    }

    if (e.target.closest('.delete-oil-type')) {
      const oilTypeId = e.target.closest('.delete-oil-type').dataset.id;

      if (confirm('Are you sure you want to delete this oil type?')) {
        fetch(`/oil-types/${oilTypeId}`, {
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
            dt_oil_types.ajax.reload();
            window.ToastManager.success('Success', result.message || 'Oil type deleted successfully!');
          } else {
            window.ToastManager.error('Error', result.message || 'Failed to delete oil type');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while deleting the oil type');
        });
      }
    }
  });

  // Quick action buttons in view modal
  document.addEventListener('click', function(e) {
    // Edit button in view modal
    if (e.target.closest('#editFromView')) {
      const oilTypeId = e.target.closest('#editFromView').dataset.id;

      // Close view modal first
      bootstrap.Modal.getInstance(document.getElementById('viewOilTypeModal')).hide();

      // Fetch and populate edit modal
      fetch(`/oil-types/${oilTypeId}`)
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            const oilType = result.data;
            document.getElementById('editOilTypeId').value = oilType.id;
            document.getElementById('editOilTypeLabel').value = oilType.label;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editOilTypeModal'));
            editModal.show();
          } else {
            window.ToastManager.error('Error', 'Failed to load oil type details');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while loading oil type details');
        });
    }

    // Toggle status button in view modal
    if (e.target.closest('#toggleFromView')) {
      const oilTypeId = e.target.closest('#toggleFromView').dataset.id;

      fetch(`/oil-types/${oilTypeId}/toggle`, {
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
          dt_oil_types.ajax.reload();

          // Close view modal
          bootstrap.Modal.getInstance(document.getElementById('viewOilTypeModal')).hide();

          window.ToastManager.success('Success', result.message || 'Oil type status updated successfully!');
        } else {
          window.ToastManager.error('Error', result.message || 'Failed to update oil type status');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        window.ToastManager.error('Error', 'An error occurred while updating status');
      });
    }

    // Duplicate button in view modal
    if (e.target.closest('#duplicateFromView')) {
      const duplicateBtn = e.target.closest('#duplicateFromView');
      const oilTypeLabel = duplicateBtn.dataset.label;

      // Close view modal first
      bootstrap.Modal.getInstance(document.getElementById('viewOilTypeModal')).hide();

      // Populate add modal with duplicated data
      document.getElementById('oilTypeLabel').value = oilTypeLabel + ' (Copy)';

      // Show add modal
      const addModal = new bootstrap.Modal(document.getElementById('addOilTypeModal'));
      addModal.show();

      window.ToastManager.info('Duplicate', 'Oil type data has been loaded for duplication. Please modify as needed.');
    }

    // Delete button in view modal
    if (e.target.closest('#deleteFromView')) {
      const oilTypeId = e.target.closest('#deleteFromView').dataset.id;

      if (confirm('Are you sure you want to delete this oil type?')) {
        fetch(`/oil-types/${oilTypeId}`, {
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
            dt_oil_types.ajax.reload();

            // Close view modal
            bootstrap.Modal.getInstance(document.getElementById('viewOilTypeModal')).hide();

            window.ToastManager.success('Success', result.message || 'Oil type deleted successfully!');
          } else {
            window.ToastManager.error('Error', result.message || 'Failed to delete oil type');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while deleting the oil type');
        });
      }
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
