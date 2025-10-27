@extends('layouts/layoutMaster')

@section('title', 'CRO Numbers - System Management')

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
<!-- CRO Numbers Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">CRO Numbers <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage CRO Numbers</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCROModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New CRO</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-cros table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>CRO Number</th>
          <th>Description</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New CRO Number Modal -->
<div class="modal fade" id="addCROModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-receipt me-2"></i>
          Add New CRO Number
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addCROForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label for="croNumber" class="form-label">CRO Number <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-receipt"></i></span>
                <input type="text" class="form-control" id="croNumber" name="cro_number"
                       placeholder="e.g., CR-2024-001" required>
                <div class="invalid-feedback">Please provide a valid CRO number.</div>
              </div>
            </div>

            <div class="col-md-12">
              <label for="croDescription" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-description"></i></span>
                <textarea class="form-control" id="croDescription" name="description"
                          rows="3" placeholder="Enter CRO number description or notes"></textarea>
              </div>
            </div>

            <div class="col-md-12">
              <label for="croStatus" class="form-label">Status <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-toggle-left"></i></span>
                <select class="form-select" id="croStatus" name="status" required>
                  <option value="">Select Status</option>
                  <option value="active" selected>Active</option>
                  <option value="inactive">Inactive</option>
                </select>
                <div class="invalid-feedback">Please select a valid status.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save CRO Number
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit CRO Number Modal -->
<div class="modal fade" id="editCROModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit CRO Number
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editCROForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editCROId" name="id">
          <div class="row g-3">
            <div class="col-md-12">
              <label for="editCRONumber" class="form-label">CRO Number <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-receipt"></i></span>
                <input type="text" class="form-control" id="editCRONumber" name="cro_number"
                       placeholder="e.g., CR-2024-001" required>
                <div class="invalid-feedback">Please provide a valid CRO number.</div>
              </div>
            </div>

            <div class="col-md-12">
              <label for="editCRODescription" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-description"></i></span>
                <textarea class="form-control" id="editCRODescription" name="description"
                          rows="3" placeholder="Enter CRO number description or notes"></textarea>
              </div>
            </div>

            <div class="col-md-12">
              <label for="editCROStatus" class="form-label">Status <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-toggle-left"></i></span>
                <select class="form-select" id="editCROStatus" name="status" required>
                  <option value="">Select Status</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
                <div class="invalid-feedback">Please select a valid status.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update CRO Number
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Include the base confirm modal -->
@include('components.base-confirm-modal')

<!-- Toast Container -->
<x-toast-container />

<!-- Page Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let dt_cros;

    // Initialize DataTable
    const dt_cros_table = document.querySelector('.datatables-cros');
    if (dt_cros_table) {
      dt_cros = new DataTable(dt_cros_table, {
        ajax: {
          url: '/cros',
          type: 'GET',
          dataSrc: function(json) {
            if (json.success) {
              return json.data;
            } else {
              showToast('Error', json.message || 'Failed to load cro numbers', 'error');
              return [];
            }
          },
          error: function(xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load cro numbers. Please try again.', 'error');
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
            data: 'cro_number',
            responsivePriority: 1,
            render: function(data, type, full) {
              return `<div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-2">
                          <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="ti tabler-receipt"></i>
                          </span>
                        </div>
                        <div class="d-flex flex-column">
                          <span class="fw-medium">${data}</span>
                        </div>
                      </div>`;
            }
          },
          {
            data: 'description',
            render: function(data) {
              if (!data) return '-';
              return data.length > 50 ? data.substring(0, 50) + '...' : data;
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
                  <a href="/cros/${full.id}/view" class="btn btn-icon btn-sm btn-outline-secondary" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </a>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-cro" data-id="${full.id}" title="Delete">
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
              placeholder: 'Search cro numbers...'
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
        cro_number: 'CRO Number',
        description: 'Description',
        status: 'Status'
      };

      let errorMessage = '';
      Object.keys(errors).forEach((key, index) => {
        const fieldLabel = fieldLabels[key] || key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        const fieldErrors = errors[key];

        if (index > 0) errorMessage += '';
        errorMessage += `${fieldLabel} ${fieldErrors.join(', ')}`;
      });

      showToast('Validation Error', errorMessage, 'error');
    }

    // Add CRO Form Submission
    const addCROForm = document.getElementById('addCROForm');
    if (addCROForm) {
      addCROForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (addCROForm.checkValidity()) {
          const formData = new FormData(addCROForm);

          fetch('/cros', {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
          })
            .then((response) => {
              if (!response.ok) {
                return response.json().then((data) => {
                  throw data;
                });
              }
              return response.json();
            })
            .then((data) => {
              if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addCROModal')).hide();
                addCROForm.reset();
                addCROForm.classList.remove('was-validated');
                dt_cros.ajax.reload();
                showToast('Success', data.message || 'CRO number added successfully!', 'success');
              }
            })
            .catch((error) => {
              if (error.errors) {
                displayValidationErrors(error.errors); // Display validation errors
              } else {
                showToast('Error', error.message || 'An unexpected error occurred.', 'error');
              }
            });
        }

        addCROForm.classList.add('was-validated');
      });
    }

    // Edit CRO Form Submission
    const editCROForm = document.getElementById('editCROForm');
    if (editCROForm) {
      editCROForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editCROForm.checkValidity()) {
          const formData = new FormData(editCROForm);
          const croId = formData.get('id');

          formData.append('_method', 'PUT');

          fetch(`/cros/${croId}`, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              bootstrap.Modal.getInstance(document.getElementById('editCROModal')).hide();
              editCROForm.reset();
              editCROForm.classList.remove('was-validated');
              dt_cros.ajax.reload();
              showToast('Success', data.message || 'CRO number updated successfully!', 'success');
            } else {
              if (data.errors) {
                displayValidationErrors(data.errors); // Use updated function
              } else {
                showToast('Error', data.message || 'Failed to update CRO number', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating the BL number', 'error');
          });
        }

        editCROForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-cro')) {
        const croId = e.target.closest('.edit-cro').dataset.id;
        editCRO(croId);
      }

      if (e.target.closest('.toggle-status')) {
        const croId = e.target.closest('.toggle-status').dataset.id;
        const currentStatus = e.target.closest('.toggle-status').dataset.status;
        toggleCROStatus(croId, currentStatus);
      }

      if (e.target.closest('.delete-cro')) {
        const croId = e.target.closest('.delete-cro').dataset.id;
        deleteCRO(croId);
      }
    });

    // Edit CRO Function
    function editCRO(croId) {
      fetch(`/cros/${croId}/edit`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const cro = data.data;
            document.getElementById('editCROId').value = cro.id;
            document.getElementById('editCRONumber').value = cro.cro_number;
            document.getElementById('editCRODescription').value = cro.description || '';
            document.getElementById('editCROStatus').value = cro.status;

            const editModal = new bootstrap.Modal(document.getElementById('editCROModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load CRO number details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading CRO number details', 'error');
        });
    }

    // Toggle CRO Status Function
    function toggleCROStatus(croId, currentStatus) {
      const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
      const action = newStatus === 'active' ? 'activate' : 'deactivate';

      fetch(`/cros/${croId}/toggle-status`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          dt_cros.ajax.reload();
          showToast('Success', data.message || `CRO number ${action}d successfully!`, 'success');
        } else {
          showToast('Error', data.message || `Failed to ${action} CRO number`, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', `An error occurred while ${action}ing the CRO number`, 'error');
      });
    }

    // Delete CRO Function
    function deleteCRO(croId) {
      // Show confirmation modal
      const confirmModal = new bootstrap.Modal(document.getElementById('baseConfirmModal'));
      confirmModal.show();

      // Set up confirm and cancel actions
      document.getElementById('confirmDeleteBtn').onclick = function() {
        fetch(`/cros/${croId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            dt_cros.ajax.reload();
            showToast('Success', data.message || 'CRO number deleted successfully!', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete CRO number', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting the CRO number', 'error');
        });

        confirmModal.hide();
      };

      document.getElementById('cancelDeleteBtn').onclick = function() {
        confirmModal.hide();
      };
    }

    document.addEventListener('click', function (e) {
      const deleteBtn = e.target.closest('.delete-cro');
      if (deleteBtn) {
        const croId = deleteBtn.dataset.id;

        // Set the modal message
        document.getElementById('baseConfirmModalMessage').textContent = 'Are you sure you want to delete this CRO number? This action cannot be undone.';

        // Show the modal
        const confirmModal = new bootstrap.Modal(document.getElementById('baseConfirmModal'));
        confirmModal.show();

        // Remove any existing event listeners from the confirm button
        const confirmBtn = document.getElementById('baseConfirmModalConfirmBtn');
        confirmBtn.replaceWith(confirmBtn.cloneNode(true)); // Replace the button to remove old listeners
        const newConfirmBtn = document.getElementById('baseConfirmModalConfirmBtn');

        // Attach a new event listener to the confirm button
        newConfirmBtn.addEventListener('click', function () {
          confirmModal.hide();

          // Ensure the backdrop is removed after the modal is hidden
          document.querySelectorAll('.modal-backdrop').forEach((backdrop) => backdrop.remove());

          // Perform the delete request
          fetch(`/cros/${croId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json',
            },
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                showToast('Success', data.message || 'CRO number deleted successfully!', 'success');
                dt_cros.ajax.reload(); // Reload the DataTable
              } else {
                showToast('Error', data.message || 'Failed to delete CRO number.', 'error');
              }
            })
            .catch((error) => {
              console.error('Error:', error);
              showToast('Error', 'An error occurred while deleting the CRO number.', 'error');
            });
        });
      }
    });

    // Toast notification function
    function showToast(title, message, type = 'info') {
      if (typeof window.showToast === 'function') {
        window.showToast(title, message, type);
      } else {
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
