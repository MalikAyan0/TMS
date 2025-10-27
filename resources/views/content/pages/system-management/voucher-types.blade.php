@extends('layouts/layoutMaster')

@section('title', 'Voucher Types - System Management')

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
<!-- Voucher Types Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Voucher Types <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage <span class="d-none d-sm-inline">different types of </span>vouchers <span class="d-none d-sm-inline">and financial documents</span></p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVoucherTypeModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add Voucher Type</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-voucher-types table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Title</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Voucher Type Modal -->
<div class="modal fade" id="addVoucherTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-file-text me-2"></i>
          Add New Voucher Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addVoucherTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="voucherTypeTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <input type="text" class="form-control" id="voucherTypeTitle" name="voucherTypeTitle"
                       placeholder="e.g., Cash Receipt, Payment Voucher, Journal Entry" required>
                <div class="invalid-feedback">Please provide a valid voucher type title.</div>
              </div>
              <div class="form-text">Enter the voucher type title (e.g., Cash Receipt, Payment Voucher, Bank Receipt, Journal Entry)</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Voucher Type
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Voucher Type Modal -->
<div class="modal fade" id="editVoucherTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Voucher Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editVoucherTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editVoucherTypeId" name="editVoucherTypeId">
          <div class="row g-3">
            <div class="col-12">
              <label for="editVoucherTypeTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <input type="text" class="form-control" id="editVoucherTypeTitle" name="editVoucherTypeTitle"
                       placeholder="e.g., Cash Receipt, Payment Voucher, Journal Entry" required>
                <div class="invalid-feedback">Please provide a valid voucher type title.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Voucher Type
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Voucher Type Modal -->
<div class="modal fade" id="viewVoucherTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Voucher Type Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <div class="card border">
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Title</label>
                    <p class="form-control-static" id="viewVoucherTypeTitle">-</p>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Status</label>
                    <div>
                      <span class="badge" id="viewVoucherTypeStatusBadge">-</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Created Date</label>
                    <p class="form-control-static" id="viewVoucherTypeCreatedDate">-</p>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Last Updated</label>
                    <p class="form-control-static" id="viewVoucherTypeUpdatedDate">-</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex gap-1">
        <button type="button" class="btn btn-primary" id="editFromViewBtn">
          <i class="ti tabler-edit me-1"></i>
          <span class="d-none d-sm-inline">Edit Voucher Type</span>
        </button>
        <button type="button" class="btn btn-outline-warning" id="toggleFromViewBtn">
          <i class="ti tabler-toggle-left me-1"></i>
          <span class="d-none d-sm-inline">Status</span>
        </button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />

<!-- Page Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize DataTable
  let dt_voucher_types;
  let currentVoucherTypeId = null;

  // Voucher Types DataTable
  const dt_voucher_types_table = document.querySelector('.datatables-voucher-types');
  if (dt_voucher_types_table) {
    // Add console logging for debugging
    console.log('Initializing DataTable for voucher types...');

    dt_voucher_types = new DataTable(dt_voucher_types_table, {
      ajax: {
        url: '/voucher-types',
        type: 'GET',
        dataSrc: function(json) {
          console.log('API Response:', json);
          if (json && json.success) {
            return json.data || [];
          } else {
            console.error('API Error:', json);
            showToast('Error', json?.message || 'Failed to load voucher types', 'error');
            return [];
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', xhr.responseText, status, error);
          console.error('Status Code:', xhr.status);
          let errorMessage = 'Failed to load voucher types. ';

          if (xhr.status === 404) {
            errorMessage += 'API endpoint not found (404).';
          } else if (xhr.status === 500) {
            errorMessage += 'Server error (500).';
          } else if (xhr.status === 0) {
            errorMessage += 'Network error. Check if the server is running.';
          } else {
            errorMessage += `Error ${xhr.status}: ${error}`;
          }

          showToast('Error', errorMessage, 'error');
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
          render: function(data, type, full, meta) {
            return meta.row + 1; // S.No starting from 1
          }
        },
        {
          data: 'title',
          responsivePriority: 1,
          render: function(data, type, full) {
            return `<div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                          <i class="ti tabler-file-text"></i>
                        </span>
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">Voucher Type</small>
                      </div>
                    </div>`;
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
                <button type="button" class="btn btn-icon btn-sm btn-outline-primary view-voucher-type" data-id="${full.id}" title="View Details">
                  <i class="ti tabler-eye"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-info edit-voucher-type" data-id="${full.id}" title="Edit">
                  <i class="ti tabler-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                  <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-voucher-type" data-id="${full.id}" title="Delete">
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
        topStart: {
          buttons: [
            {
              extend: 'collection',
              className: 'btn btn-label-secondary dropdown-toggle me-4',
              text: '<i class="ti tabler-upload icon-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
              buttons: [
                {
                  extend: 'print',
                  text: '<i class="ti tabler-printer me-1"></i>Print',
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [1, 2, 3, 4]
                  }
                },
                {
                  extend: 'csv',
                  text: '<i class="ti tabler-file-text me-1"></i>Csv',
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [1, 2, 3, 4]
                  }
                },
                {
                  extend: 'excel',
                  text: '<i class="ti tabler-file-spreadsheet me-1"></i>Excel',
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [1, 2, 3, 4]
                  }
                },
                {
                  extend: 'pdf',
                  text: '<i class="ti tabler-file-description me-1"></i>Pdf',
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [1, 2, 3, 4]
                  }
                }
              ]
            }
          ]
        },
        topEnd: {
          search: {
            placeholder: 'Search voucher types...'
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

  // Display validation errors
  function displayValidationErrors(errors) {
    let errorMessage = '';

    if (typeof errors === 'object') {
      Object.keys(errors).forEach(function(key) {
        if (Array.isArray(errors[key])) {
          errorMessage += errors[key].join(' ') + ' ';
        } else {
          errorMessage += errors[key] + ' ';
        }
      });
    } else {
      errorMessage = errors.toString();
    }

    errorMessage = errorMessage.trim();

    if (typeof window.showToast === 'function') {
      window.showToast('Validation Error', errorMessage, 'error');
    } else {
      alert('Validation Error: ' + errorMessage);
    }
  }

  // Helper function to get CSRF token
  function getCSRFToken() {
    const tokenElement = document.querySelector('meta[name="csrf-token"]');
    if (!tokenElement) {
      console.error('CSRF token not found');
      showToast('Error', 'Security token not found. Please refresh the page.', 'error');
      return null;
    }
    return tokenElement.getAttribute('content');
  }

  // Add Voucher Type Form Submission
  const addVoucherTypeForm = document.getElementById('addVoucherTypeForm');
  if (addVoucherTypeForm) {
    addVoucherTypeForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (addVoucherTypeForm.checkValidity()) {
        const formData = new FormData(addVoucherTypeForm);

        // Prepare voucher type data
        const voucherTypeData = {
          title: formData.get('voucherTypeTitle')
        };

        // Send API request
        const csrfToken = getCSRFToken();
        if (!csrfToken) return;

        fetch('/voucher-types', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(voucherTypeData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload table
            dt_voucher_types.ajax.reload();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addVoucherTypeModal')).hide();
            addVoucherTypeForm.reset();
            addVoucherTypeForm.classList.remove('was-validated');

            showToast('Success', data.message || 'Voucher type added successfully!', 'success');
          } else {
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to add voucher type', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while adding the voucher type', 'error');
        });
      }

      addVoucherTypeForm.classList.add('was-validated');
    });
  }

  // Edit Voucher Type Form Submission
  const editVoucherTypeForm = document.getElementById('editVoucherTypeForm');
  if (editVoucherTypeForm) {
    editVoucherTypeForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (editVoucherTypeForm.checkValidity()) {
        const formData = new FormData(editVoucherTypeForm);
        const voucherTypeId = parseInt(formData.get('editVoucherTypeId'));

        // Prepare voucher type data
        const voucherTypeData = {
          title: formData.get('editVoucherTypeTitle')
        };

        // Send API request
        const csrfToken = getCSRFToken();
        if (!csrfToken) return;

        fetch(`/voucher-types/${voucherTypeId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(voucherTypeData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload table
            dt_voucher_types.ajax.reload();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('editVoucherTypeModal')).hide();
            editVoucherTypeForm.reset();
            editVoucherTypeForm.classList.remove('was-validated');

            showToast('Success', data.message || 'Voucher type updated successfully!', 'success');
          } else {
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to update voucher type', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating the voucher type', 'error');
        });
      }

      editVoucherTypeForm.classList.add('was-validated');
    });
  }

  // View Voucher Type Details
  function viewVoucherType(voucherTypeId) {
    fetch(`/voucher-types/${voucherTypeId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const voucherType = data.data;

          // Update view modal fields
          document.getElementById('viewVoucherTypeTitle').textContent = voucherType.title;

          // Update status badge
          const statusBadge = document.getElementById('viewVoucherTypeStatusBadge');
          const statusConfig = {
            active: { class: 'bg-label-success', text: 'Active' },
            inactive: { class: 'bg-label-secondary', text: 'Inactive' }
          };
          const config = statusConfig[voucherType.status] || statusConfig.inactive;
          statusBadge.className = `badge ${config.class}`;
          statusBadge.textContent = config.text;

          document.getElementById('viewVoucherTypeCreatedDate').textContent = new Date(voucherType.created_at).toLocaleDateString();
          document.getElementById('viewVoucherTypeUpdatedDate').textContent = new Date(voucherType.updated_at).toLocaleDateString();

          // Store voucher type ID for actions
          currentVoucherTypeId = voucherType.id;

          // Update action buttons
          const toggleBtn = document.getElementById('toggleFromViewBtn');
          toggleBtn.innerHTML = `<i class="ti tabler-toggle-${voucherType.status === 'active' ? 'right' : 'left'} me-1"></i><span class="d-none d-sm-inline">${voucherType.status === 'active' ? 'Deactivate' : 'Activate'}</span>`;
          toggleBtn.className = `btn btn-outline-${voucherType.status === 'active' ? 'warning' : 'success'}`;

          // Show view modal
          const viewModal = new bootstrap.Modal(document.getElementById('viewVoucherTypeModal'));
          viewModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load voucher type details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading voucher type details', 'error');
      });
  }

  // Event delegation for action buttons
  document.addEventListener('click', function(e) {
    if (e.target.closest('.view-voucher-type')) {
      const voucherTypeId = parseInt(e.target.closest('.view-voucher-type').dataset.id);
      viewVoucherType(voucherTypeId);
    }

    if (e.target.closest('.edit-voucher-type')) {
      const voucherTypeId = parseInt(e.target.closest('.edit-voucher-type').dataset.id);

      fetch(`/voucher-types/${voucherTypeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const voucherType = data.data;

            // Populate edit form
            document.getElementById('editVoucherTypeId').value = voucherType.id;
            document.getElementById('editVoucherTypeTitle').value = voucherType.title;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editVoucherTypeModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load voucher type details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading voucher type details', 'error');
        });
    }

    if (e.target.closest('.toggle-status')) {
      const voucherTypeId = parseInt(e.target.closest('.toggle-status').dataset.id);

      const csrfToken = getCSRFToken();
      if (!csrfToken) return;

      fetch(`/voucher-types/${voucherTypeId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          dt_voucher_types.ajax.reload();
          showToast('Success', data.message || 'Voucher type status updated successfully', 'success');
        } else {
          showToast('Error', data.message || 'Failed to update voucher type status', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while updating voucher type status', 'error');
      });
    }

    if (e.target.closest('.delete-voucher-type')) {
      const voucherTypeId = parseInt(e.target.closest('.delete-voucher-type').dataset.id);

      if (confirm('Are you sure you want to delete this voucher type?')) {
        const csrfToken = getCSRFToken();
        if (!csrfToken) return;

        fetch(`/voucher-types/${voucherTypeId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            dt_voucher_types.ajax.reload();
            showToast('Success', data.message || 'Voucher type deleted successfully', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete voucher type', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting voucher type', 'error');
        });
      }
    }

    // Edit from view modal
    if (e.target.closest('#editFromViewBtn')) {
      if (currentVoucherTypeId) {
        fetch(`/voucher-types/${currentVoucherTypeId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const voucherType = data.data;

              // Hide view modal
              bootstrap.Modal.getInstance(document.getElementById('viewVoucherTypeModal')).hide();

              // Populate and show edit modal
              document.getElementById('editVoucherTypeId').value = voucherType.id;
              document.getElementById('editVoucherTypeTitle').value = voucherType.title;

              const editModal = new bootstrap.Modal(document.getElementById('editVoucherTypeModal'));
              editModal.show();
            } else {
              showToast('Error', data.message || 'Failed to load voucher type details', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while loading voucher type details', 'error');
          });
      }
    }

    // Toggle from view modal
    if (e.target.closest('#toggleFromViewBtn')) {
      if (currentVoucherTypeId) {
        const csrfToken = getCSRFToken();
        if (!csrfToken) return;

        fetch(`/voucher-types/${currentVoucherTypeId}/toggle`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            dt_voucher_types.ajax.reload();

            // Hide view modal
            bootstrap.Modal.getInstance(document.getElementById('viewVoucherTypeModal')).hide();

            showToast('Success', data.message || 'Voucher type status updated successfully', 'success');
          } else {
            showToast('Error', data.message || 'Failed to update voucher type status', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating voucher type status', 'error');
        });
      }
    }
  });
});

// Define showToast function if not available globally
if (typeof window.showToast !== 'function') {
  window.showToast = function(title, message, type = 'info') {
    // Try to use the toast system, fallback to alert if not available
    if (typeof window.toastr !== 'undefined') {
      switch(type) {
        case 'success':
          toastr.success(message, title);
          break;
        case 'error':
          toastr.error(message, title);
          break;
        case 'warning':
          toastr.warning(message, title);
          break;
        default:
          toastr.info(message, title);
      }
    } else {
      // Fallback to alert
      alert(`${title}: ${message}`);
    }
  };
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
