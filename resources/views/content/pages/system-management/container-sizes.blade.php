@extends('layouts/layoutMaster')

@section('title', 'Container Sizes - System Management')

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
<!-- Container Sizes Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Container Sizes <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage shipping container sizes and dimensions</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContainerSizeModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add Container Size</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-container-sizes table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Container Size</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Container Size Modal -->
<div class="modal fade" id="addContainerSizeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-box me-2"></i>
          Add Container Size
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addContainerSizeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="containerSize" class="form-label">Container Size <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-box"></i></span>
                <input type="text" class="form-control" id="containerSize" name="containerSize"
                       placeholder="e.g., 20ft, 40ft, 45ft High Cube" required>
                <div class="invalid-feedback">Please provide a valid container size.</div>
              </div>
              <div class="form-text">Enter the container size (e.g., 20ft, 40ft, 40ft High Cube, 45ft High Cube)</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Container Size
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Container Size Modal -->
<div class="modal fade" id="editContainerSizeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Container Size
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editContainerSizeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editContainerSizeId" name="editContainerSizeId">
          <div class="row g-3">
            <div class="col-12">
              <label for="editContainerSize" class="form-label">Container Size <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-box"></i></span>
                <input type="text" class="form-control" id="editContainerSize" name="editContainerSize"
                       placeholder="e.g., 20ft, 40ft, 45ft High Cube" required>
                <div class="invalid-feedback">Please provide a valid container size.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update&nbsp;<span class="d-none d-sm-inline">Container Size</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Container Size Modal -->
<div class="modal fade" id="viewContainerSizeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Container Size Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label fw-medium">Container Size</label>
                <p class="form-control-static" id="viewContainerSize">-</p>
              </div>
              <div class="col-sm-6">
                <label class="form-label fw-medium">Status</label>
                <div>
                  <span class="badge" id="viewContainerSizeStatusBadge">-</span>
                </div>
              </div>
              <div class="col-sm-6">
                <label class="form-label fw-medium">Created Date</label>
                <p class="form-control-static" id="viewContainerSizeCreatedDate">-</p>
              </div>
              <div class="col-sm-6">
                <label class="form-label fw-medium">Last Updated</label>
                <p class="form-control-static" id="viewContainerSizeUpdatedDate">-</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex gap-1">

        <button type="button" class="btn btn-primary" id="editFromViewBtn">
          <i class="ti tabler-edit me-1"></i>
          <span class="d-none d-sm-inline">Edit Container Size</span>
        </button>
        <button type="button" class="btn btn-outline-warning" id="toggleFromViewBtn">
          <i class="ti tabler-toggle-left me-1"></i>
          <span class="d-none d-sm-inline"> Status</span>
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
  let dt_container_sizes;
  let currentContainerSizeId = null;

  // Container Sizes DataTable
  const dt_container_sizes_table = document.querySelector('.datatables-container-sizes');
  if (dt_container_sizes_table) {
    dt_container_sizes = new DataTable(dt_container_sizes_table, {
      ajax: {
        url: '/container-sizes',
        type: 'GET',
        dataSrc: function(json) {
          if (json.success) {
            return json.data;
          } else {
            showToast('Error', json.message || 'Failed to load container sizes', 'error');
            return [];
          }
        },
        error: function(xhr, status, error) {
          showToast('Error', 'Failed to load container sizes. Please try again.', 'error');
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
          data: 'container_size',
          responsivePriority: 1,
          render: function(data, type, full) {
            return `<div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                          <i class="ti tabler-box"></i>
                        </span>
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">Container</small>
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
                <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-container-size" data-id="${full.id}" title="View Details">
                  <i class="ti tabler-eye"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-container-size" data-id="${full.id}" title="Edit">
                  <i class="ti tabler-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                  <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-container-size" data-id="${full.id}" title="Delete">
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
            placeholder: 'Search container sizes...'
          }
        }
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: function(row) {
              const data = row.data();
              return 'Details of ' + data.container_size;
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

  // Add Container Size Form Submission
  const addContainerSizeForm = document.getElementById('addContainerSizeForm');
  if (addContainerSizeForm) {
    addContainerSizeForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (addContainerSizeForm.checkValidity()) {
        const formData = new FormData(addContainerSizeForm);

        // Prepare container size data
        const containerSizeData = {
          container_size: formData.get('containerSize')
        };

        // Send API request
        fetch('/container-sizes', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(containerSizeData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload table
            dt_container_sizes.ajax.reload();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addContainerSizeModal')).hide();
            addContainerSizeForm.reset();
            addContainerSizeForm.classList.remove('was-validated');

            showToast('Success', data.message || 'Container size added successfully!', 'success');
          } else {
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to add container size', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while adding the container size', 'error');
        });
      }

      addContainerSizeForm.classList.add('was-validated');
    });
  }

  // Edit Container Size Form Submission
  const editContainerSizeForm = document.getElementById('editContainerSizeForm');
  if (editContainerSizeForm) {
    editContainerSizeForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (editContainerSizeForm.checkValidity()) {
        const formData = new FormData(editContainerSizeForm);
        const containerSizeId = parseInt(formData.get('editContainerSizeId'));

        // Prepare container size data
        const containerSizeData = {
          container_size: formData.get('editContainerSize')
        };

        // Send API request
        fetch(`/container-sizes/${containerSizeId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(containerSizeData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload table
            dt_container_sizes.ajax.reload();

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('editContainerSizeModal')).hide();
            editContainerSizeForm.reset();
            editContainerSizeForm.classList.remove('was-validated');

            showToast('Success', data.message || 'Container size updated successfully!', 'success');
          } else {
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to update container size', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating the container size', 'error');
        });
      }

      editContainerSizeForm.classList.add('was-validated');
    });
  }

  // View Container Size Details
  function viewContainerSize(containerSizeId) {
    fetch(`/container-sizes/${containerSizeId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const containerSize = data.data;

          // Update view modal fields
          document.getElementById('viewContainerSize').textContent = containerSize.container_size;

          // Update status badge
          const statusBadge = document.getElementById('viewContainerSizeStatusBadge');
          const statusConfig = {
            active: { class: 'bg-label-success', text: 'Active' },
            inactive: { class: 'bg-label-secondary', text: 'Inactive' }
          };
          const config = statusConfig[containerSize.status] || statusConfig.inactive;
          statusBadge.className = `badge ${config.class}`;
          statusBadge.textContent = config.text;

          document.getElementById('viewContainerSizeCreatedDate').textContent = new Date(containerSize.created_at).toLocaleDateString();
          document.getElementById('viewContainerSizeUpdatedDate').textContent = new Date(containerSize.updated_at).toLocaleDateString();

          // Store container size ID for actions
          currentContainerSizeId = containerSize.id;

          // Update action buttons
          const toggleBtn = document.getElementById('toggleFromViewBtn');
          toggleBtn.innerHTML = `<i class="ti tabler-toggle-${containerSize.status === 'active' ? 'right' : 'left'} me-1"></i><span class="d-none d-sm-inline">${containerSize.status === 'active' ? 'Deactivate' : 'Activate'}</span>`;
          toggleBtn.className = `btn btn-outline-${containerSize.status === 'active' ? 'warning' : 'success'}`;

          // Show view modal
          const viewModal = new bootstrap.Modal(document.getElementById('viewContainerSizeModal'));
          viewModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load container size details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading container size details', 'error');
      });
  }

  // Event delegation for action buttons
  document.addEventListener('click', function(e) {
    if (e.target.closest('.view-container-size')) {
      const containerSizeId = parseInt(e.target.closest('.view-container-size').dataset.id);
      viewContainerSize(containerSizeId);
    }

    if (e.target.closest('.edit-container-size')) {
      const containerSizeId = parseInt(e.target.closest('.edit-container-size').dataset.id);

      fetch(`/container-sizes/${containerSizeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const containerSize = data.data;

            // Populate edit form
            document.getElementById('editContainerSizeId').value = containerSize.id;
            document.getElementById('editContainerSize').value = containerSize.container_size;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editContainerSizeModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load container size details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading container size details', 'error');
        });
    }

    if (e.target.closest('.toggle-status')) {
      const containerSizeId = parseInt(e.target.closest('.toggle-status').dataset.id);

      fetch(`/container-sizes/${containerSizeId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          dt_container_sizes.ajax.reload();
          showToast('Success', data.message || 'Container size status updated successfully', 'success');
        } else {
          showToast('Error', data.message || 'Failed to update container size status', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while updating container size status', 'error');
      });
    }

    if (e.target.closest('.delete-container-size')) {
      const containerSizeId = parseInt(e.target.closest('.delete-container-size').dataset.id);

      if (confirm('Are you sure you want to delete this container size?')) {
        fetch(`/container-sizes/${containerSizeId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            dt_container_sizes.ajax.reload();
            showToast('Success', data.message || 'Container size deleted successfully', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete container size', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting container size', 'error');
        });
      }
    }

    // Edit from view modal
    if (e.target.closest('#editFromViewBtn')) {
      if (currentContainerSizeId) {
        fetch(`/container-sizes/${currentContainerSizeId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const containerSize = data.data;

              // Hide view modal
              bootstrap.Modal.getInstance(document.getElementById('viewContainerSizeModal')).hide();

              // Populate and show edit modal
              document.getElementById('editContainerSizeId').value = containerSize.id;
              document.getElementById('editContainerSize').value = containerSize.container_size;

              const editModal = new bootstrap.Modal(document.getElementById('editContainerSizeModal'));
              editModal.show();
            } else {
              showToast('Error', data.message || 'Failed to load container size details', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while loading container size details', 'error');
          });
      }
    }

    // Toggle from view modal
    if (e.target.closest('#toggleFromViewBtn')) {
      if (currentContainerSizeId) {
        fetch(`/container-sizes/${currentContainerSizeId}/toggle`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            dt_container_sizes.ajax.reload();

            // Hide view modal
            bootstrap.Modal.getInstance(document.getElementById('viewContainerSizeModal')).hide();

            showToast('Success', data.message || 'Container size status updated successfully', 'success');
          } else {
            showToast('Error', data.message || 'Failed to update container size status', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating container size status', 'error');
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
