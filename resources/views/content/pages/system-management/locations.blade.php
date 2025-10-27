@extends('layouts/layoutMaster')

@section('title', 'Locations Management - System Management')

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

<!-- Locations Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Locations <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage shipping and transportation locations</p>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocationModal">
        <i class="ti tabler-plus me-1"></i>
        <span class="d-none d-sm-inline">Add Location</span>
      </button>
    </div>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-locations table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Title</th>
          <th>Short Name</th>
          <th>Description</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Location Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-map-pin me-2"></i>
          Add New Location
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addLocationForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="locationTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="locationTitle" name="locationTitle"
                       placeholder="e.g., New York Port Terminal" required>
                <div class="invalid-feedback">Please provide a valid location title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="locationShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="locationShortName" name="locationShortName"
                       placeholder="e.g., NYC-PT" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
              <div class="form-text">Maximum 10 characters for easy identification</div>
            </div>

            <div class="col-12">
              <label for="locationDescription" class="form-label">Description <span class="text-danger">*</span></label>
              <textarea class="form-control" id="locationDescription" name="locationDescription" rows="4"
                        placeholder="Detailed description of the location, including address, facilities, and operational details..." required></textarea>
              <div class="invalid-feedback">Please provide a location description.</div>
            </div>

            <div class="col-md-6">
              <label for="locationType" class="form-label">Location Type</label>
              <select class="form-select" id="locationType" name="locationType">
                <option value="">Select Type</option>
                <option value="port">Port</option>
                <option value="warehouse">Warehouse</option>
                <option value="terminal">Terminal</option>
                <option value="depot">Depot</option>
                <option value="office">Office</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="locationStatus" class="form-label">Status</label>
              <select class="form-select" id="locationStatus" name="locationStatus">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Under Maintenance</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="locationCountry" class="form-label">Country</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-world"></i></span>
                <input type="text" class="form-control" id="locationCountry" name="locationCountry"
                       placeholder="e.g., United States">
              </div>
            </div>

            <div class="col-md-6">
              <label for="locationCity" class="form-label">City</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map"></i></span>
                <input type="text" class="form-control" id="locationCity" name="locationCity"
                       placeholder="e.g., New York">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Location
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Location Modal -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Location
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editLocationForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editLocationId" name="editLocationId">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editLocationTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="editLocationTitle" name="editLocationTitle"
                       placeholder="e.g., New York Port Terminal" required>
                <div class="invalid-feedback">Please provide a valid location title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editLocationShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editLocationShortName" name="editLocationShortName"
                       placeholder="e.g., NYC-PT" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
            </div>

            <div class="col-12">
              <label for="editLocationDescription" class="form-label">Description <span class="text-danger">*</span></label>
              <textarea class="form-control" id="editLocationDescription" name="editLocationDescription" rows="4"
                        placeholder="Detailed description of the location..." required></textarea>
              <div class="invalid-feedback">Please provide a location description.</div>
            </div>

            <div class="col-md-6">
              <label for="editLocationType" class="form-label">Location Type</label>
              <select class="form-select" id="editLocationType" name="editLocationType">
                <option value="">Select Type</option>
                <option value="port">Port</option>
                <option value="warehouse">Warehouse</option>
                <option value="terminal">Terminal</option>
                <option value="depot">Depot</option>
                <option value="office">Office</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="editLocationStatus" class="form-label">Status</label>
              <select class="form-select" id="editLocationStatus" name="editLocationStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Under Maintenance</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="editLocationCountry" class="form-label">Country</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-world"></i></span>
                <input type="text" class="form-control" id="editLocationCountry" name="editLocationCountry"
                       placeholder="e.g., United States">
              </div>
            </div>

            <div class="col-md-6">
              <label for="editLocationCity" class="form-label">City</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map"></i></span>
                <input type="text" class="form-control" id="editLocationCity" name="editLocationCity"
                       placeholder="e.g., New York">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Location
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Location Modal -->
<div class="modal fade" id="viewLocationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Location Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Basic Information -->
          <div class="col-md-6">
            <div class="card h-100" style="background-color: #f8f9fa;">
              <div class="card-body">
                <h6 class="card-title mb-3 border-bottom pb-2">
                  <i class="ti tabler-info-circle me-2"></i>
                  Basic Information
                </h6>
                <div class="d-flex align-items-center mb-3">
                  <div class="avatar avatar-sm me-3">
                    <span class="avatar-initial rounded bg-label-primary">
                      <i class="ti tabler-map-pin" id="viewLocationIcon"></i>
                    </span>
                  </div>
                  <div>
                    <h6 class="mb-0" id="viewLocationTitle">-</h6>
                    <small class="text-muted">Location Title</small>
                  </div>
                </div>
                <div class="mb-3">
                  <span class="badge bg-label-primary" id="viewLocationShortName">-</span>
                  <span class="badge bg-label-success ms-2" id="viewLocationStatus">Active</span>
                </div>
                <div class="mb-3">
                  <span class="badge bg-label-info" id="viewLocationType">Other</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Location Details -->
          <div class="col-md-6">
            <div class="card h-100" style="background-color: #f8f9fa;">
              <div class="card-body">
                <h6 class="card-title mb-3 border-bottom pb-2">
                  <i class="ti tabler-world me-2"></i>
                  Location Details
                </h6>
                <div class="mb-3">
                  <small class="text-muted d-block">Country</small>
                  <span id="viewLocationCountry">-</span>
                </div>
                <div class="mb-3">
                  <small class="text-muted d-block">City</small>
                  <span id="viewLocationCity">-</span>
                </div>
                <div class="mb-3">
                  <small class="text-muted d-block">Full Address</small>
                  <span id="viewLocationAddress">-</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="col-12">
            <div class="card" style="background-color: #f8f9fa;">
              <div class="card-body">
                <h6 class="card-title mb-3 border-bottom pb-2">
                  <i class="ti tabler-file-text me-2"></i>
                  Description
                </h6>
                <p class="mb-0" id="viewLocationDescription">-</p>
              </div>
            </div>
          </div>

          <!-- Timestamps -->
          <div class="col-12">
            <div class="card" style="background-color: #f8f9fa;">
              <div class="card-body">
                <h6 class="card-title mb-3 border-bottom pb-2">
                  <i class="ti tabler-clock me-2"></i>
                  Timestamps
                </h6>
                <div class="row">
                  <div class="col-md-6">
                    <small class="text-muted d-block">Created Date</small>
                    <span id="viewLocationCreated">-</span>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted d-block">Last Updated</small>
                    <span id="viewLocationUpdated">-</span>
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
  const API_BASE = '/locations';

  // CSRF token for requests
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // Initialize DataTable
  const dt_locations_table = document.querySelector('.datatables-locations');
  let dt_locations;

  // Load data from API
  async function loadLocationData() {
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
      console.error('Error loading location data:', error);
      ToastManager.error('Error', 'Failed to load locations: ' + error.message);
      return [];
    }
  }

  // Initialize DataTable with API data
  async function initializeDataTable() {
    const locationData = await loadLocationData();

    if (dt_locations_table) {
      dt_locations = new DataTable(dt_locations_table, {
        data: locationData,
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
            render: function(data, type, row, meta) {
              // Calculate proper sequential numbering regardless of sort order
              return meta.settings._iDisplayStart + meta.row + 1;
            }
          },
          {
            data: 'title',
            responsivePriority: 1,
            render: function(data, type, full) {
              return `<div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">${full.city || 'N/A'}, ${full.country || 'N/A'}</small>
                      </div>`;
            }
          },
          {
            data: 'short_name',
            render: function(data, type, full) {
              const typeColors = {
                port: 'primary',
                warehouse: 'info',
                terminal: 'warning',
                depot: 'success',
                office: 'secondary',
                other: 'dark'
              };
              const color = typeColors[full.type] || 'secondary';
              return `<span class="badge bg-label-${color}">${data}</span>`;
            }
          },
          {
            data: 'description',
            render: function(data) {
              return data && data.length > 100 ? data.substring(0, 100) + '...' : (data || 'N/A');
            }
          },
          {
            data: 'status',
            render: function(data) {
              const statusConfig = {
                active: { class: 'success', text: 'Active' },
                inactive: { class: 'secondary', text: 'Inactive' },
                maintenance: { class: 'warning', text: 'Maintenance' }
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
                <div class="d-flex gap-1">
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-secondary view-location" title="View" data-id="${full.id}">
                    <i class="ti tabler-eye"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-primary edit-location" title="Edit" data-id="${full.id}">
                    <i class="ti tabler-edit"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}" data-id="${full.id}" data-status="${full.status}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </a>
                  <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-danger delete-location" title="Delete" data-id="${full.id}">
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
        order: [[1, 'asc']], // Order by Title column (index 2) in ascending order
        layout: {
          topEnd: {
            search: {
              placeholder: 'Search locations...'
            }
          }
        },
        responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function(row) {
                const data = row.data();
                return 'Details of ' + data.title;
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

  // Auto-generate short name from title
  document.getElementById('locationTitle').addEventListener('input', function() {
    const title = this.value;
    const shortName = title
      .split(' ')
      .map(word => word.charAt(0).toUpperCase())
      .join('')
      .substring(0, 10);
    document.getElementById('locationShortName').value = shortName;
  });

  // Add Location Form Submission
  const addLocationForm = document.getElementById('addLocationForm');

  if (addLocationForm) {
      const fieldMap = {
          title: 'locationTitle',
          short_name: 'locationShortName',
          description: 'locationDescription',
          type: 'locationType',
          status: 'locationStatus',
          country: 'locationCountry',
          city: 'locationCity'
      };

      const clearValidationErrors = (form) => {
          form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
          form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
          form.querySelectorAll('[aria-describedby]').forEach(el => el.removeAttribute('aria-describedby'));
      };

      const attachFieldError = (form, fieldName, messages) => {
          const name = fieldMap[fieldName] || fieldName;
          const elements = form.querySelectorAll(`[name="${name}"]`);
          if (!elements.length) return;

          const targetEl = elements[elements.length - 1];
          const errorDiv = document.createElement('div');
          errorDiv.className = 'invalid-feedback';
          errorDiv.textContent = Array.isArray(messages) ? messages.join(' ') : messages;

          targetEl.classList.add('is-invalid');
          targetEl.insertAdjacentElement('afterend', errorDiv);

          const id = targetEl.id || `err_${name}_${Date.now()}`;
          targetEl.id = id;
          errorDiv.id = `fb_${id}`;
          targetEl.setAttribute('aria-describedby', errorDiv.id);
      };

      const showValidationErrors = (form, errors) => {
          let firstInvalid = null;
          Object.entries(errors).forEach(([field, msgs]) => {
              attachFieldError(form, field, msgs);
              if (!firstInvalid) firstInvalid = form.querySelector(`[name="${fieldMap[field] || field}"]`);
          });

          if (firstInvalid) {
              firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
              firstInvalid.focus();
          }
      };

      const getFirstErrorMessage = (errors) => {
          const firstFieldErrors = Object.values(errors)?.[0];
          return Array.isArray(firstFieldErrors) ? firstFieldErrors[0] : 'Validation error.';
      };

      addLocationForm.addEventListener('submit', async (e) => {
          e.preventDefault();
          clearValidationErrors(addLocationForm);

          const formData = new FormData(addLocationForm);
          const locationData = {
              title: formData.get('locationTitle'),
              short_name: formData.get('locationShortName'),
              description: formData.get('locationDescription'),
              type: formData.get('locationType') || 'other',
              status: formData.get('locationStatus'),
              country: formData.get('locationCountry'),
              city: formData.get('locationCity')
          };

          try {
              const response = await fetch(API_BASE, {
                  method: 'POST',
                  credentials: 'same-origin',
                  headers: {
                      'Content-Type': 'application/json',
                      'Accept': 'application/json',
                      'X-Requested-With': 'XMLHttpRequest',
                      'X-CSRF-TOKEN': csrfToken
                  },
                  body: JSON.stringify(locationData)
              });

              const result = await response.json().catch(() => ({}));

              if (response.ok && result.success) {
                  dt_locations.row.add(result.data).draw();
                  bootstrap.Modal.getInstance(document.getElementById('addLocationModal'))?.hide();
                  addLocationForm.reset();
                  addLocationForm.classList.remove('was-validated');
                  ToastManager.success('Success', result.message || 'Location added successfully.');
              }
              else if (response.status === 422 && result.errors) {
                  showValidationErrors(addLocationForm, result.errors);
                  ToastManager.error('Validation Error', getFirstErrorMessage(result.errors));
              }
              else if (response.status === 401) {
                  ToastManager.error('Authentication Error', 'Please login again.');
                  setTimeout(() => window.location.href = '/login', 1500);
              }
              else {
                  ToastManager.error('Error', result.message || `Request failed with status ${response.status}`);
              }
          } catch (error) {
              console.error('Error adding location:', error);
              ToastManager.error('Error', error.message || 'An unexpected error occurred.');
          } finally {
              addLocationForm.classList.add('was-validated');
          }
      });
  }



  // Edit Location Form Submission
  const editLocationForm = document.getElementById('editLocationForm');
  if (editLocationForm) {
    editLocationForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      if (editLocationForm.checkValidity()) {
        try {
          const formData = new FormData(editLocationForm);
          const locationId = parseInt(formData.get('editLocationId'));
          const locationData = {
            title: formData.get('editLocationTitle'),
            short_name: formData.get('editLocationShortName'),
            description: formData.get('editLocationDescription'),
            type: formData.get('editLocationType') || 'other',
            status: formData.get('editLocationStatus'),
            country: formData.get('editLocationCountry'),
            city: formData.get('editLocationCity')
          };

          const result = await makeAPIRequest(`${API_BASE}/${locationId}`, {
            method: 'PUT',
            body: JSON.stringify(locationData)
          });

          if (result.success) {
            // Reload table data
            const updatedData = await loadLocationData();
            dt_locations.clear().rows.add(updatedData).draw();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('editLocationModal')).hide();
            editLocationForm.reset();
            editLocationForm.classList.remove('was-validated');

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error updating location:', error);
          ToastManager.error('Error', error.message);
        }
      }

      editLocationForm.classList.add('was-validated');
    });
  }

  // Event delegation for action buttons
  document.addEventListener('click', async function(e) {
    if (e.target.closest('.edit-location')) {
      const locationId = parseInt(e.target.closest('.edit-location').dataset.id);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${locationId}`);

        if (result.success) {
          const location = result.data;

          // Populate edit form
          document.getElementById('editLocationId').value = location.id;
          document.getElementById('editLocationTitle').value = location.title;
          document.getElementById('editLocationShortName').value = location.short_name;
          document.getElementById('editLocationDescription').value = location.description;
          document.getElementById('editLocationType').value = location.type;
          document.getElementById('editLocationStatus').value = location.status;
          document.getElementById('editLocationCountry').value = location.country || '';
          document.getElementById('editLocationCity').value = location.city || '';

          // Show edit modal
          const editModal = new bootstrap.Modal(document.getElementById('editLocationModal'));
          editModal.show();
        }
      } catch (error) {
        console.error('Error fetching location:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('.view-location')) {
      const locationId = parseInt(e.target.closest('.view-location').dataset.id);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${locationId}`);

        if (result.success) {
          const location = result.data;

          // Populate view modal - Basic Information
          document.getElementById('viewLocationTitle').textContent = location.title;
          document.getElementById('viewLocationShortName').textContent = location.short_name;

          // Set status badge
          const statusBadge = document.getElementById('viewLocationStatus');
          const statusConfig = {
            active: { class: 'bg-label-success', text: 'Active' },
            inactive: { class: 'bg-label-secondary', text: 'Inactive' },
            maintenance: { class: 'bg-label-warning', text: 'Maintenance' }
          };
          const config = statusConfig[location.status] || statusConfig.inactive;
          statusBadge.className = `badge ${config.class} ms-2`;
          statusBadge.textContent = config.text;

          // Set type badge
          const typeBadge = document.getElementById('viewLocationType');
          const typeColors = {
            port: 'bg-label-primary',
            warehouse: 'bg-label-info',
            terminal: 'bg-label-warning',
            depot: 'bg-label-success',
            office: 'bg-label-secondary',
            other: 'bg-label-dark'
          };
          const typeColor = typeColors[location.type] || typeColors.other;
          typeBadge.className = `badge ${typeColor}`;
          typeBadge.textContent = location.type ? location.type.charAt(0).toUpperCase() + location.type.slice(1) : 'Other';

          // Location Details
          document.getElementById('viewLocationCountry').textContent = location.country || 'N/A';
          document.getElementById('viewLocationCity').textContent = location.city || 'N/A';
          document.getElementById('viewLocationAddress').textContent =
            (location.city && location.country) ? `${location.city}, ${location.country}` : 'N/A';

          // Description
          document.getElementById('viewLocationDescription').textContent = location.description || 'No description available';

          // Set timestamps
          document.getElementById('viewLocationCreated').textContent =
            location.created_at ? new Date(location.created_at).toLocaleDateString() : '-';
          document.getElementById('viewLocationUpdated').textContent =
            location.updated_at ? new Date(location.updated_at).toLocaleDateString() : '-';

          // Store location data for quick actions
          const viewModal = document.getElementById('viewLocationModal');
          viewModal.dataset.locationId = location.id;
          viewModal.dataset.locationData = JSON.stringify(location);

          // Update quick action buttons
          const toggleBtn = document.getElementById('toggleFromView');
          const toggleIcon = toggleBtn.querySelector('i');
          toggleBtn.className = `btn btn-outline-${location.status === 'active' ? 'warning' : 'success'}`;
          toggleIcon.className = `ti tabler-toggle-${location.status === 'active' ? 'right' : 'left'} me-1`;
          toggleBtn.innerHTML = `<i class="${toggleIcon.className}"></i>${location.status === 'active' ? 'Deactivate' : 'Activate'}`;

          // Show view modal
          const viewModalInstance = new bootstrap.Modal(document.getElementById('viewLocationModal'));
          viewModalInstance.show();
        }
      } catch (error) {
        console.error('Error fetching location:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('.toggle-status')) {
      const locationId = parseInt(e.target.closest('.toggle-status').dataset.id);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${locationId}/toggle`, {
          method: 'PATCH'
        });

        if (result.success) {
          // Reload table data
          const updatedData = await loadLocationData();
          dt_locations.clear().rows.add(updatedData).draw();

          ToastManager.success('Success', result.message);
        }
      } catch (error) {
        console.error('Error toggling status:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('.delete-location')) {
      const locationId = parseInt(e.target.closest('.delete-location').dataset.id);

      if (confirm('Are you sure you want to delete this location?')) {
        try {
          const result = await makeAPIRequest(`${API_BASE}/${locationId}`, {
            method: 'DELETE'
          });

          if (result.success) {
            // Reload table data
            const updatedData = await loadLocationData();
            dt_locations.clear().rows.add(updatedData).draw();

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error deleting location:', error);
          ToastManager.error('Error', error.message);
        }
      }
    }

    // Quick action handlers from view modal
    if (e.target.closest('#editFromView')) {
      const viewModal = document.getElementById('viewLocationModal');
      const locationData = JSON.parse(viewModal.dataset.locationData);

      // Close view modal
      bootstrap.Modal.getInstance(viewModal).hide();

      // Populate and show edit modal
      document.getElementById('editLocationId').value = locationData.id;
      document.getElementById('editLocationTitle').value = locationData.title;
      document.getElementById('editLocationShortName').value = locationData.short_name;
      document.getElementById('editLocationDescription').value = locationData.description;
      document.getElementById('editLocationType').value = locationData.type;
      document.getElementById('editLocationStatus').value = locationData.status;
      document.getElementById('editLocationCountry').value = locationData.country || '';
      document.getElementById('editLocationCity').value = locationData.city || '';

      const editModal = new bootstrap.Modal(document.getElementById('editLocationModal'));
      editModal.show();
    }

    if (e.target.closest('#toggleFromView')) {
      const viewModal = document.getElementById('viewLocationModal');
      const locationId = parseInt(viewModal.dataset.locationId);

      try {
        const result = await makeAPIRequest(`${API_BASE}/${locationId}/toggle`, {
          method: 'PATCH'
        });

        if (result.success) {
          // Close view modal
          bootstrap.Modal.getInstance(viewModal).hide();

          // Reload table data
          const updatedData = await loadLocationData();
          dt_locations.clear().rows.add(updatedData).draw();

          ToastManager.success('Success', result.message);
        }
      } catch (error) {
        console.error('Error toggling location status:', error);
        ToastManager.error('Error', error.message);
      }
    }

    if (e.target.closest('#duplicateFromView')) {
      const viewModal = document.getElementById('viewLocationModal');
      const locationData = JSON.parse(viewModal.dataset.locationData);

      // Close view modal
      bootstrap.Modal.getInstance(viewModal).hide();

      // Populate add modal with duplicated data
      document.getElementById('locationTitle').value = locationData.title + ' (Copy)';
      document.getElementById('locationShortName').value = (locationData.short_name + 'C').substring(0, 10);
      document.getElementById('locationDescription').value = locationData.description;
      document.getElementById('locationType').value = locationData.type;
      document.getElementById('locationStatus').value = 'inactive'; // Set to inactive for safety
      document.getElementById('locationCountry').value = locationData.country || '';
      document.getElementById('locationCity').value = locationData.city || '';

      const addModal = new bootstrap.Modal(document.getElementById('addLocationModal'));
      addModal.show();

      ToastManager.info('Duplicate', 'Location data has been loaded for duplication. Please modify as needed.');
    }

    if (e.target.closest('#deleteFromView')) {
      const viewModal = document.getElementById('viewLocationModal');
      const locationId = parseInt(viewModal.dataset.locationId);

      if (confirm('Are you sure you want to delete this location?')) {
        try {
          const result = await makeAPIRequest(`${API_BASE}/${locationId}`, {
            method: 'DELETE'
          });

          if (result.success) {
            // Close view modal
            bootstrap.Modal.getInstance(viewModal).hide();

            // Reload table data
            const updatedData = await loadLocationData();
            dt_locations.clear().rows.add(updatedData).draw();

            ToastManager.success('Success', result.message);
          }
        } catch (error) {
          console.error('Error deleting location:', error);
          ToastManager.error('Error', error.message);
        }
      }
    }
  });

  // Initialize the DataTable with API data
  initializeDataTable();
});

// Test API function - global scope
async function testAPI() {
  try {
    const response = await fetch('/locations', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    const result = await response.json();

    if (result.success) {
      ToastManager.success('API Test', `Successfully loaded ${result.data.length} locations from API!`);
      console.log('API Response:', result);
    } else {
      ToastManager.error('API Test Failed', result.message);
    }
  } catch (error) {
    console.error('API Test Error:', error);
    ToastManager.error('API Test Failed', 'Unable to connect to API: ' + error.message);
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
