@extends('layouts/layoutMaster')

@section('title', 'Operations Management - System Management')

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

<!-- Operations Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Operations <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage operations</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOperationModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add Operations</span>
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-operations table">
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

<!-- Add New Operation Modal -->
<div class="modal fade" id="addOperationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-settings me-2"></i>
          Add New Operation
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addOperationForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="operationLabel" class="form-label">Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="operationLabel" name="operationLabel"
                       placeholder="e.g., Container Loading Operation" required>
                <div class="invalid-feedback">Please provide a valid operation label.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Operation
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Operation Modal -->
<div class="modal fade" id="editOperationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Operation
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editOperationForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editOperationId" name="editOperationId">
          <div class="row g-3">
            <div class="col-12">
              <label for="editOperationLabel" class="form-label">Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editOperationLabel" name="editOperationLabel"
                       placeholder="e.g., Container Loading Operation" required>
                <div class="invalid-feedback">Please provide a valid operation label.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Operation
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Operation Modal -->
<div class="modal fade" id="viewOperationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Operation Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Basic Information -->
          <div class="col-md-6">
            <div class="card h-100" style="background-color: #f8f9fa;">
              <div class="card-body">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-info-circle me-2"></i>
                  Basic Information
                </h6>
                <div class="d-flex align-items-center mb-3">
                  <div class="avatar avatar-sm me-3">
                    <span class="avatar-initial rounded bg-label-primary">
                      <i class="ti tabler-settings" id="viewOperationIcon"></i>
                    </span>
                  </div>
                  <div>
                    <h6 class="mb-0" id="viewOperationLabel">-</h6>
                    <small class="text-muted">Operation Label</small>
                  </div>
                </div>
                <div class="mb-3">
                  <span class="badge bg-label-success" id="viewOperationStatus">Active</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Timestamps -->
          <div class="col-md-6">
            <div class="card h-100" style="background-color: #f8f9fa;">
              <div class="card-body">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-clock me-2"></i>
                  Timestamps
                </h6>
                <div class="mb-3">
                  <small class="text-muted d-block">Created Date</small>
                  <span id="viewOperationCreated">-</span>
                </div>
                <div class="mb-3">
                  <small class="text-muted d-block">Last Updated</small>
                  <span id="viewOperationUpdated">-</span>
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
            <span class="d-none d-sm-inline">Edit</span>
          </button>
          <button type="button" class="btn btn-outline-warning" id="toggleFromView">
            <i class="ti tabler-toggle-right me-1"></i>
            <span class="d-none d-sm-inline">Status</span>
          </button>
          <button type="button" class="btn btn-outline-success" id="duplicateFromView">
            <i class="ti tabler-copy me-1"></i>
            <span class="d-none d-sm-inline">Duplicate</span>
          </button>
          <button type="button" class="btn btn-outline-danger" id="deleteFromView">
            <i class="ti tabler-trash me-1"></i>
            <span class="d-none d-sm-inline">Delete</span>
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

  // API endpoints
  const API_BASE = '/operations';

  // CSRF token for requests
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // Initialize DataTable
  const dt_operations_table = document.querySelector('.datatables-operations');
  let dt_operations;

  // Load data from API
  async function loadOperationData() {
    try {
      const response = await fetch(API_BASE, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken
        }
      });

      if (!response.ok) {
        throw new Error('Failed to fetch data');
      }

      const result = await response.json();

      if (result.success) {
        return result.data;
      } else {
        throw new Error(result.message || 'Failed to load data');
      }
    } catch (error) {
      console.error('Error loading operation data:', error);
      ToastManager.error('Error', 'Failed to load operations: ' + error.message);
      return [];
    }
  }

  // Initialize DataTable with API data
  async function initializeDataTable() {
    const operationData = await loadOperationData();

    if (dt_operations_table) {
      dt_operations = new DataTable(dt_operations_table, {
        data: operationData,
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
            orderable: false,
            searchable: false,
            render: function(data, type, full, meta) {
              return meta.settings._iDisplayStart + meta.row + 1;
            }
          },
          {
            data: 'label',
            responsivePriority: 1,
            render: function(data, type, full) {
              return `<div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">Operation</small>
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
              return data ? new Date(data).toLocaleDateString() : '';
            }
          },
          {
            data: null,
            responsivePriority: 2,
            orderable: false,
            searchable: false,
            render: function(data, type, full) {
              return `
                <div class="d-inline-flex gap-1">
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-secondary view-operation" title="View Details" data-id="${full.id}">
                    <i class="ti tabler-eye"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-primary edit-operation" title="Edit" data-id="${full.id}">
                    <i class="ti tabler-edit"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}" data-id="${full.id}" data-status="${full.status}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-danger delete-operation" title="Delete" data-id="${full.id}">
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
        order: [[2, 'asc']],
        layout: {

          topEnd: {
            search: {
              placeholder: 'Search operations...'
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
  }

  // API request helper
  async function makeAPIRequest(url, options = {}) {
    const defaultOptions = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      }
    };

    const response = await fetch(url, { ...defaultOptions, ...options });
    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || 'Request failed');
    }

    return result;
  }

  // Add Operation Form Submission
  const addOperationForm = document.getElementById('addOperationForm');
  if (addOperationForm) {
    addOperationForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      if (addOperationForm.checkValidity()) {
        try {
          const formData = new FormData(addOperationForm);
          const operationData = {
            label: formData.get('operationLabel')
          };

          const result = await makeAPIRequest(API_BASE, {
            method: 'POST',
            body: JSON.stringify(operationData)
          });

          if (result.success) {
            // Add new row to DataTable
            dt_operations.row.add(result.data).draw();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addOperationModal')).hide();
            addOperationForm.reset();
            addOperationForm.classList.remove('was-validated');

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error adding operation:', error);
          ToastManager.error('Error', error.message);
        }
      }

      addOperationForm.classList.add('was-validated');
    });
  }

  // Edit Operation Form Submission
  const editOperationForm = document.getElementById('editOperationForm');
  if (editOperationForm) {
    editOperationForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      if (editOperationForm.checkValidity()) {
        try {
          const formData = new FormData(editOperationForm);
          const operationId = parseInt(formData.get('editOperationId'));
          const operationData = {
            label: formData.get('editOperationLabel')
          };

          const result = await makeAPIRequest(`${API_BASE}/${operationId}`, {
            method: 'PUT',
            body: JSON.stringify(operationData)
          });

          if (result.success) {
            // Reload table data
            const updatedData = await loadOperationData();
            dt_operations.clear().rows.add(updatedData).draw();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('editOperationModal')).hide();
            editOperationForm.reset();
            editOperationForm.classList.remove('was-validated');

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error updating operation:', error);
          ToastManager.error('Error', error.message);
        }
      }

      editOperationForm.classList.add('was-validated');
    });
  }

  // Event delegation for action buttons
  document.addEventListener('click', async function(e) {
    if (e.target.closest('.edit-operation')) {
      const operationId = parseInt(e.target.closest('.edit-operation').dataset.id);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${operationId}`);

        if (result.success) {
          const operation = result.data;

          // Populate edit form
          document.getElementById('editOperationId').value = operation.id;
          document.getElementById('editOperationLabel').value = operation.label;

          // Show edit modal
          const editModal = new bootstrap.Modal(document.getElementById('editOperationModal'));
          editModal.show();
        }
      } catch (error) {
        console.error('Error fetching operation:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('.view-operation')) {
      const operationId = parseInt(e.target.closest('.view-operation').dataset.id);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${operationId}`);

        if (result.success) {
          const operation = result.data;

          // Populate view modal
          document.getElementById('viewOperationLabel').textContent = operation.label;

          // Set status badge
          const statusBadge = document.getElementById('viewOperationStatus');
          const statusConfig = {
            active: { class: 'bg-label-success', text: 'Active' },
            inactive: { class: 'bg-label-secondary', text: 'Inactive' }
          };
          const config = statusConfig[operation.status] || statusConfig.inactive;
          statusBadge.className = `badge ${config.class}`;
          statusBadge.textContent = config.text;

          // Set timestamps
          document.getElementById('viewOperationCreated').textContent =
            operation.created_at ? new Date(operation.created_at).toLocaleDateString() : '-';
          document.getElementById('viewOperationUpdated').textContent =
            operation.updated_at ? new Date(operation.updated_at).toLocaleDateString() : '-';

          // Store operation data for quick actions
          const viewModal = document.getElementById('viewOperationModal');
          viewModal.dataset.operationId = operation.id;
          viewModal.dataset.operationData = JSON.stringify(operation);

          // Update quick action buttons
          const toggleBtn = document.getElementById('toggleFromView');
          const toggleIcon = toggleBtn.querySelector('i');
          toggleBtn.className = `btn btn-outline-${operation.status === 'active' ? 'warning' : 'success'}`;
          toggleIcon.className = `ti tabler-toggle-${operation.status === 'active' ? 'right' : 'left'} me-1`;
          toggleBtn.innerHTML = `<i class="${toggleIcon.className}"></i><span class="d-none d-sm-inline">${operation.status === 'active' ? 'Deactivate' : 'Activate'}</span>`;

          // Show view modal
          const viewModalInstance = new bootstrap.Modal(document.getElementById('viewOperationModal'));
          viewModalInstance.show();
        }
      } catch (error) {
        console.error('Error fetching operation:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('.toggle-status')) {
      const operationId = parseInt(e.target.closest('.toggle-status').dataset.id);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${operationId}/toggle`, {
          method: 'PATCH'
        });

        if (result.success) {
          // Reload table data
          const updatedData = await loadOperationData();
          dt_operations.clear().rows.add(updatedData).draw();

          ToastManager.success('Success', result.message);
        }
      } catch (error) {
        console.error('Error toggling operation status:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('.delete-operation')) {
      const operationId = parseInt(e.target.closest('.delete-operation').dataset.id);

      if (confirm('Are you sure you want to delete this operation?')) {
        try {
          const result = await makeAPIRequest(`${API_BASE}/${operationId}`, {
            method: 'DELETE'
          });

          if (result.success) {
            // Reload table data
            const updatedData = await loadOperationData();
            dt_operations.clear().rows.add(updatedData).draw();

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error deleting operation:', error);
          ToastManager.error('Error', error.message);
        }
      }
    }

    // Quick action handlers from view modal
    if (e.target.closest('#editFromView')) {
      const viewModal = document.getElementById('viewOperationModal');
      const operationData = JSON.parse(viewModal.dataset.operationData);

      // Close view modal
      bootstrap.Modal.getInstance(viewModal).hide();

      // Populate and show edit modal
      document.getElementById('editOperationId').value = operationData.id;
      document.getElementById('editOperationLabel').value = operationData.label;

      const editModal = new bootstrap.Modal(document.getElementById('editOperationModal'));
      editModal.show();
    }

    if (e.target.closest('#toggleFromView')) {
      const viewModal = document.getElementById('viewOperationModal');
      const operationId = parseInt(viewModal.dataset.operationId);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${operationId}/toggle`, {
          method: 'PATCH'
        });

        if (result.success) {
          // Close view modal
          bootstrap.Modal.getInstance(viewModal).hide();

          // Reload table data
          const updatedData = await loadOperationData();
          dt_operations.clear().rows.add(updatedData).draw();

          ToastManager.success('Success', result.message);
        }
      } catch (error) {
        console.error('Error toggling operation status:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('#duplicateFromView')) {
      const viewModal = document.getElementById('viewOperationModal');
      const operationData = JSON.parse(viewModal.dataset.operationData);

      // Close view modal
      bootstrap.Modal.getInstance(viewModal).hide();

      // Populate add modal with duplicated data
      document.getElementById('operationLabel').value = operationData.label + ' (Copy)';

      const addModal = new bootstrap.Modal(document.getElementById('addOperationModal'));
      addModal.show();

      ToastManager.info('Duplicate', 'Operation data has been loaded for duplication. Please modify as needed.');
    }

    if (e.target.closest('#deleteFromView')) {
      const viewModal = document.getElementById('viewOperationModal');
      const operationId = parseInt(viewModal.dataset.operationId);

      if (confirm('Are you sure you want to delete this operation?')) {
        try {
          const result = await makeAPIRequest(`${API_BASE}/${operationId}`, {
            method: 'DELETE'
          });

          if (result.success) {
            // Close view modal
            bootstrap.Modal.getInstance(viewModal).hide();

            // Reload table data
            const updatedData = await loadOperationData();
            dt_operations.clear().rows.add(updatedData).draw();

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error deleting operation:', error);
          ToastManager.error('Error', error.message);
        }
      }
    }
  });

  // Initialize the DataTable with API data
  initializeDataTable();
});

// Test toast function - global scope
function testToast() {
  if (typeof bootstrap === 'undefined') {
    alert('Bootstrap is not loaded');
    return;
  }

  if (typeof ToastManager !== 'undefined') {
    console.log('Testing toasts...');
    ToastManager.success('Test Success!', 'Toast is working perfectly!');
    setTimeout(() => ToastManager.info('Info Test', 'This is an info message'), 1000);
    setTimeout(() => ToastManager.warning('Warning Test', 'This is a warning message'), 2000);
    setTimeout(() => ToastManager.error('Error Test', 'This is an error message'), 3000);
  } else {
    console.error('ToastManager is not available');
    alert('ToastManager is not available');
  }
}
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
