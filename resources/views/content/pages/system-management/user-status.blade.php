@extends('layouts/layoutMaster')

@section('title', 'User Status Management - System Management')

<!-- Vendor Styles -->
@section('vendor-style')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
'resources/assets/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/vendor/libs/animate-css/animate.scss'])
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

<!-- User Status Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">User Status <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage user status labels and their configurations</p>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStatusModal">
        <i class="ti tabler-plus me-1"></i>
        <span class="d-none d-sm-inline">Add New Status</span>
      </button>
    </div>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-status table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Status Label</th>
          <th>Color</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Status Modal -->
<div class="modal fade" id="addStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-plus me-2"></i>
          Add New Status
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addStatusForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="statusLabel" class="form-label">Status Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="statusLabel" name="statusLabel"
                       placeholder="e.g., Active, Inactive, Pending" required>
                <div class="invalid-feedback">Please provide a valid status label.</div>
              </div>
            </div>

            <div class="col-12">
              <label for="statusColor" class="form-label">Status Color</label>
              <div class="d-flex gap-2 flex-wrap">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="statusColor" id="colorPrimary" value="primary" checked>
                  <label class="form-check-label" for="colorPrimary">
                    <span class="badge bg-primary">Primary</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="statusColor" id="colorSuccess" value="success">
                  <label class="form-check-label" for="colorSuccess">
                    <span class="badge bg-success">Success</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="statusColor" id="colorWarning" value="warning">
                  <label class="form-check-label" for="colorWarning">
                    <span class="badge bg-warning">Warning</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="statusColor" id="colorDanger" value="danger">
                  <label class="form-check-label" for="colorDanger">
                    <span class="badge bg-danger">Danger</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="statusColor" id="colorInfo" value="info">
                  <label class="form-check-label" for="colorInfo">
                    <span class="badge bg-info">Info</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="statusColor" id="colorSecondary" value="secondary">
                  <label class="form-check-label" for="colorSecondary">
                    <span class="badge bg-secondary">Secondary</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="statusDescription" class="form-label">Description (Optional)</label>
              <textarea class="form-control" id="statusDescription" name="statusDescription" rows="3"
                        placeholder="Brief description of this status..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Status
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Status Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Status
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editStatusForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editStatusId" name="editStatusId">
          <div class="row g-3">
            <div class="col-12">
              <label for="editStatusLabel" class="form-label">Status Label <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editStatusLabel" name="editStatusLabel"
                       placeholder="e.g., Active, Inactive, Pending" required>
                <div class="invalid-feedback">Please provide a valid status label.</div>
              </div>
            </div>

            <div class="col-12">
              <label for="editStatusColor" class="form-label">Status Color</label>
              <div class="d-flex gap-2 flex-wrap">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="editStatusColor" id="editColorPrimary" value="primary">
                  <label class="form-check-label" for="editColorPrimary">
                    <span class="badge bg-primary">Primary</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="editStatusColor" id="editColorSuccess" value="success">
                  <label class="form-check-label" for="editColorSuccess">
                    <span class="badge bg-success">Success</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="editStatusColor" id="editColorWarning" value="warning">
                  <label class="form-check-label" for="editColorWarning">
                    <span class="badge bg-warning">Warning</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="editStatusColor" id="editColorDanger" value="danger">
                  <label class="form-check-label" for="editColorDanger">
                    <span class="badge bg-danger">Danger</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="editStatusColor" id="editColorInfo" value="info">
                  <label class="form-check-label" for="editColorInfo">
                    <span class="badge bg-info">Info</span>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="editStatusColor" id="editColorSecondary" value="secondary">
                  <label class="form-check-label" for="editColorSecondary">
                    <span class="badge bg-secondary">Secondary</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="editStatusDescription" class="form-label">Description (Optional)</label>
              <textarea class="form-control" id="editStatusDescription" name="editStatusDescription" rows="3"
                        placeholder="Brief description of this status..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update <span class="d-none d-sm-inline">Status</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Status Modal -->
<div class="modal fade" id="viewStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Status Details
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
                    <label class="form-label fw-medium text-muted mb-1">Status ID</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-hash me-2 text-primary"></i>
                      <span id="viewStatusId" class="fw-medium">-</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">System Status</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-circle-check me-2"></i>
                      <span id="viewStatusSystemStatus" class="badge">-</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Status Details -->
          <div class="col-12">
            <div class="card border-0 ">
              <div class="card-header bg-transparent border-0 pb-0 ">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-tag me-2"></i>
                  Status Configuration
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3 borderd">
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Label</label>
                    <div class="d-flex align-items-center justify-content-center p-3 bg-white rounded  h-100">
                      <div class="text-center">
                        <i class="ti tabler-tag text-info fs-1 mb-2"></i>
                        <div id="viewStatusLabel" class="fw-medium fs-5 mb-0">-</div>
                        <small class="text-muted">Status Label</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Color Theme</label>
                    <div class="d-flex align-items-center justify-content-center p-3 bg-white rounded  h-100">
                      <div class="text-center">
                        <span id="viewStatusColorBadge" class="badge fs-6 px-4 py-2 mb-2">
                          <span id="viewStatusColorName" class="text-capitalize">Sample</span>
                        </span>
                        <div class="small text-muted">Color Theme</div>
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
              <div class="card-header bg-transparent border-0 pb-0 ">
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
                        <div id="viewStatusCreatedDate" class="fw-medium">-</div>
                        <small id="viewStatusCreatedTime" class="text-muted">-</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Last Updated</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-calendar-event me-2 text-info"></i>
                      <div>
                        <div id="viewStatusUpdatedDate" class="fw-medium">-</div>
                        <small id="viewStatusUpdatedTime" class="text-muted">-</small>
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
    const API_BASE = '/users-status';

    // CSRF token for requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Initialize Sanctum CSRF protection
    async function initializeSanctum() {
      try {
        await fetch('/sanctum/csrf-cookie', {
          method: 'GET',
          credentials: 'same-origin'
        });
      } catch (error) {
        console.error('Error initializing Sanctum:', error);
      }
    }

    // Initialize DataTable
    const dt_status_table = document.querySelector('.datatables-status');
    let dt_status;

    // Load data from API
    async function loadStatusData() {
      try {
        const response = await fetch(API_BASE, {
          method: 'GET',
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
          }
        });

        if (!response.ok) {
          if (response.status === 401) {
            const errorMessage = 'Authentication required. Please login again.';
            ToastManager.error('Authentication Error', errorMessage);
            // Redirect to login page after a delay
            setTimeout(() => {
              window.location.href = '/login';
            }, 2000);
            throw new Error(errorMessage);
          }
          throw new Error('Failed to fetch data');
        }

        const result = await response.json();

        if (result.success) {
          return result.data;
        } else {
          throw new Error(result.message || 'Failed to load data');
        }
      } catch (error) {
        console.error('Error loading status data:', error);
        ToastManager.error('Error', 'Failed to load user statuses: ' + error.message);
        return [];
      }
    }

    // Initialize DataTable with API data
    async function initializeDataTable() {
      // Initialize Sanctum first
      await initializeSanctum();

      const statusData = await loadStatusData();

      if (dt_status_table) {
        dt_status = new DataTable(dt_status_table, {
          data: statusData,
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
                return `<span class="fw-medium">${data}</span>`;
              }
            },
            {
              data: 'color',
              render: function(data, type, full) {
                return `<span class="badge bg-${data}">${full.label}</span>`;
              }
            },
            {
              data: 'status',
              render: function(data, type, full) {
                const statusClass = data === 'active' ? 'success' : 'secondary';
                const statusText = data === 'active' ? 'Active' : 'Inactive';
                return `<span class="badge bg-label-${statusClass}">${statusText}</span>`;
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
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-secondary view-status" data-id="${full.id}" title="View">
                      <i class="ti tabler-eye"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-primary edit-status" data-id="${full.id}" title="Edit">
                      <i class="ti tabler-edit"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                      <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-danger delete-status" data-id="${full.id}" title="Delete">
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
                placeholder: 'Search status...'
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
                          <td>${col.title}:</td>
                          <td>${col.data}</td>
                        </tr>`
                      : '';
                  })
                  .join('');
                return data ? $('<table class="table"/><tbody />').append(data) : false;
              }
            }
          }
        });
      }
    }

    // API request helper
    async function makeAPIRequest(url, options = {}) {
      const defaultOptions = {
        credentials: 'same-origin',
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
        if (response.status === 401) {
          const errorMessage = 'Authentication required. Please login again.';
          ToastManager.error('Authentication Error', errorMessage);
          // Redirect to login page after a delay
          setTimeout(() => {
            window.location.href = '/login';
          }, 2000);
          throw new Error(errorMessage);
        }
        throw new Error(result.message || 'Request failed');
      }

      return result;
    }

    // Add Status Form Submission
    const addStatusForm = document.getElementById('addStatusForm');
    if (addStatusForm) {
      addStatusForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (addStatusForm.checkValidity()) {
          try {
            const formData = new FormData(addStatusForm);
            const statusData = {
              label: formData.get('statusLabel'),
              color: formData.get('statusColor'),
              description: formData.get('statusDescription')
            };

            const result = await makeAPIRequest(API_BASE, {
              method: 'POST',
              body: JSON.stringify(statusData)
            });

            if (result.success) {
              // Add new row to DataTable
              dt_status.row.add(result.data).draw();

              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('addStatusModal')).hide();
              addStatusForm.reset();
              addStatusForm.classList.remove('was-validated');

              ToastManager.success('Success', result.message);
            }
          } catch (error) {
            console.error('Error adding status:', error);
            ToastManager.error('Error', error.message);
          }
        }

        addStatusForm.classList.add('was-validated');
      });
    }

    // Edit Status Form Submission
    const editStatusForm = document.getElementById('editStatusForm');
    if (editStatusForm) {
      editStatusForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (editStatusForm.checkValidity()) {
          try {
            const formData = new FormData(editStatusForm);
            const statusId = parseInt(formData.get('editStatusId'));
            const statusData = {
              label: formData.get('editStatusLabel'),
              color: formData.get('editStatusColor'),
              description: formData.get('editStatusDescription')
            };

            const result = await makeAPIRequest(`${API_BASE}/${statusId}`, {
              method: 'PUT',
              body: JSON.stringify(statusData)
            });

            if (result.success) {
              // Reload table data
              const updatedData = await loadStatusData();
              dt_status.clear().rows.add(updatedData).draw();

              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('editStatusModal')).hide();
              editStatusForm.reset();
              editStatusForm.classList.remove('was-validated');

              ToastManager.success('Success', result.message);
            }
          } catch (error) {
            console.error('Error updating status:', error);
            ToastManager.error('Error', error.message);
          }
        }

        editStatusForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', async function(e) {
      if (e.target.closest('.view-status')) {
        const statusId = parseInt(e.target.closest('.view-status').dataset.id);

        try {
          const result = await makeAPIRequest(`${API_BASE}/${statusId}`);

          if (result.success) {
            const status = result.data;

            // Populate view modal with status data
            document.getElementById('viewStatusId').textContent = status.id;
            document.getElementById('viewStatusLabel').textContent = status.label;

            // Set system status badge
            const systemStatusElement = document.getElementById('viewStatusSystemStatus');
            systemStatusElement.textContent = status.status === 'active' ? 'Active' : 'Inactive';
            systemStatusElement.className = `badge bg-label-${status.status === 'active' ? 'success' : 'secondary'}`;

            // Set color badge and name
            const colorBadge = document.getElementById('viewStatusColorBadge');
            const colorName = document.getElementById('viewStatusColorName');
            colorBadge.className = `badge bg-${status.color} fs-6 px-4 py-2 mb-2`;
            colorName.textContent = status.color;

            // Format and set dates
            const createdDate = new Date(status.created_at);
            const updatedDate = new Date(status.updated_at);

            document.getElementById('viewStatusCreatedDate').textContent = createdDate.toLocaleDateString();
            document.getElementById('viewStatusCreatedTime').textContent = createdDate.toLocaleTimeString();
            document.getElementById('viewStatusUpdatedDate').textContent = updatedDate.toLocaleDateString();
            document.getElementById('viewStatusUpdatedTime').textContent = updatedDate.toLocaleTimeString();

            // Setup quick action buttons
            const editBtn = document.getElementById('editFromView');
            const toggleBtn = document.getElementById('toggleFromView');
            const deleteBtn = document.getElementById('deleteFromView');
            const duplicateBtn = document.getElementById('duplicateFromView');

            // Update toggle button text and icon
            toggleBtn.innerHTML = `<i class="ti tabler-toggle-${status.status === 'active' ? 'left' : 'right'} me-1"></i>
                                 <span class="d-none d-sm-inline">${status.status === 'active' ? 'Deactivate' : 'Activate'}</span>`;
            toggleBtn.className = `btn btn-outline-${status.status === 'active' ? 'warning' : 'success'}`;

            // Set data attributes for quick actions
            editBtn.dataset.id = status.id;
            toggleBtn.dataset.id = status.id;
            toggleBtn.dataset.status = status.status;
            deleteBtn.dataset.id = status.id;
            duplicateBtn.dataset.id = status.id;

            // Show view modal
            const viewModal = new bootstrap.Modal(document.getElementById('viewStatusModal'));
            viewModal.show();
          }
        } catch (error) {
          console.error('Error fetching status:', error);
          ToastManager.error('Error', 'Failed to load status details: ' + error.message);
        }
      }

      if (e.target.closest('.edit-status')) {
        const statusId = parseInt(e.target.closest('.edit-status').dataset.id);

        try {
          const result = await makeAPIRequest(`${API_BASE}/${statusId}`);

          if (result.success) {
            const status = result.data;

            // Populate edit form
            document.getElementById('editStatusId').value = status.id;
            document.getElementById('editStatusLabel').value = status.label;
            document.getElementById('editStatusDescription').value = status.description || '';

            // Set color radio button
            const colorRadio = document.querySelector(`input[name="editStatusColor"][value="${status.color}"]`);
            if (colorRadio) colorRadio.checked = true;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editStatusModal'));
            editModal.show();
          }
        } catch (error) {
          console.error('Error fetching status:', error);
          ToastManager.error('Error', error.message);
        }
      }

      if (e.target.closest('.toggle-status')) {
        const statusId = parseInt(e.target.closest('.toggle-status').dataset.id);

        try {
          const result = await makeAPIRequest(`${API_BASE}/${statusId}/toggle`, {
            method: 'PATCH'
          });

          if (result.success) {
            // Reload table data
            const updatedData = await loadStatusData();
            dt_status.clear().rows.add(updatedData).draw();

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error toggling status:', error);
          ToastManager.error('Error', error.message);
        }
      }

      if (e.target.closest('.delete-status')) {
        const statusId = parseInt(e.target.closest('.delete-status').dataset.id);

        if (confirm('Are you sure you want to delete this status?')) {
          try {
            const result = await makeAPIRequest(`${API_BASE}/${statusId}`, {
              method: 'DELETE'
            });

            if (result.success) {
              // Reload table data
              const updatedData = await loadStatusData();
              dt_status.clear().rows.add(updatedData).draw();

              ToastManager.success('Success', result.message);
            }
          } catch (error) {
            console.error('Error deleting status:', error);
            ToastManager.error('Error', error.message);
          }
        }
      }
    });

    // Quick action buttons in view modal
    document.addEventListener('click', async function(e) {
      // Edit button in view modal
      if (e.target.closest('#editFromView')) {
        const statusId = parseInt(e.target.closest('#editFromView').dataset.id);

        // Close view modal first
        bootstrap.Modal.getInstance(document.getElementById('viewStatusModal')).hide();

        try {
          const result = await makeAPIRequest(`${API_BASE}/${statusId}`);

          if (result.success) {
            const status = result.data;

            // Populate edit form
            document.getElementById('editStatusId').value = status.id;
            document.getElementById('editStatusLabel').value = status.label;
            document.getElementById('editStatusDescription').value = status.description || '';

            // Set color radio button
            const colorRadio = document.querySelector(`input[name="editStatusColor"][value="${status.color}"]`);
            if (colorRadio) colorRadio.checked = true;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editStatusModal'));
            editModal.show();
          }
        } catch (error) {
          console.error('Error fetching status:', error);
          ToastManager.error('Error', 'Failed to load status details: ' + error.message);
        }
      }

      // Toggle status button in view modal
      if (e.target.closest('#toggleFromView')) {
        const statusId = parseInt(e.target.closest('#toggleFromView').dataset.id);

        try {
          const result = await makeAPIRequest(`${API_BASE}/${statusId}/toggle`, {
            method: 'PATCH'
          });

          if (result.success) {
            // Reload table data
            const updatedData = await loadStatusData();
            dt_status.clear().rows.add(updatedData).draw();

            // Close view modal
            bootstrap.Modal.getInstance(document.getElementById('viewStatusModal')).hide();

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error toggling status:', error);
          ToastManager.error('Error', 'Failed to toggle status: ' + error.message);
        }
      }

      // Duplicate button in view modal
      if (e.target.closest('#duplicateFromView')) {
        const statusId = parseInt(e.target.closest('#duplicateFromView').dataset.id);

        try {
          const result = await makeAPIRequest(`${API_BASE}/${statusId}`);

          if (result.success) {
            const status = result.data;

            // Close view modal
            bootstrap.Modal.getInstance(document.getElementById('viewStatusModal')).hide();

            // Pre-populate add form with current status data
            document.getElementById('statusLabel').value = status.label + ' (Copy)';
            document.getElementById('statusDescription').value = status.description || '';

            // Set color radio button
            const colorRadio = document.querySelector(`input[name="statusColor"][value="${status.color}"]`);
            if (colorRadio) colorRadio.checked = true;

            // Show add modal for duplication
            const addModal = new bootstrap.Modal(document.getElementById('addStatusModal'));
            addModal.show();

            ToastManager.info('Duplicate Mode', 'Status data loaded for duplication. Modify and save as needed.');
          }
        } catch (error) {
          console.error('Error duplicating status:', error);
          ToastManager.error('Error', 'Failed to duplicate status: ' + error.message);
        }
      }

      // Delete button in view modal
      if (e.target.closest('#deleteFromView')) {
        const statusId = parseInt(e.target.closest('#deleteFromView').dataset.id);

        if (confirm('Are you sure you want to delete this status?')) {
          try {
            const result = await makeAPIRequest(`${API_BASE}/${statusId}`, {
              method: 'DELETE'
            });

            if (result.success) {
              // Reload table data
              const updatedData = await loadStatusData();
              dt_status.clear().rows.add(updatedData).draw();

              // Close view modal
              bootstrap.Modal.getInstance(document.getElementById('viewStatusModal')).hide();

              ToastManager.success('Success', result.message);
            }
          } catch (error) {
            console.error('Error deleting status:', error);
            ToastManager.error('Error', 'Failed to delete status: ' + error.message);
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
