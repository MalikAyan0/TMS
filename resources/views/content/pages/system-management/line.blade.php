@extends('layouts/layoutMaster')

@section('title', 'Lines - System Management')

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
<!-- Lines Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Line Management</h4>
      <p class="card-subtitle mb-0">Manage lines </p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLineModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline-block">Add New Line</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-lines table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Name</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Line Modal -->
<div class="modal fade" id="addLineModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add New Line
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addLineForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label" for="lineName">Line Name</label>
              <input type="text" id="lineName" name="name" class="form-control" placeholder="Enter Line Name" required />
              <div class="invalid-feedback">Please enter a valid line name.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="lineDescription">Description</label>
              <textarea name="description" id="lineDescription" rows="5" class="form-control" placeholder="Enter Description" required></textarea>
              <div class="invalid-feedback">Please enter a valid description.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Line
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Line Modal -->
<div class="modal fade" id="editLineModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Line
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editLineForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editLineId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="editLineName">Line Name</label>
              <input type="text" id="editLineName" name="name" class="form-control" placeholder="Enter Line Name" required />
              <div class="invalid-feedback">Please enter a valid line name.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="editLineStatus">Status</label>
              <select id="editLineStatus" name="status" class="form-select" required>
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <div class="invalid-feedback">Please select a valid status.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="editLineDescription">Description</label>
              <textarea  rows="5" id="editLineDescription" name="description" class="form-control" placeholder="Enter Description" required></textarea>
              <div class="invalid-feedback">Please enter a valid description.</div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Line
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Line Modal -->
<div class="modal fade" id="viewLineModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Line Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- Line Header -->
          <div class="col-12">
            <h4 class="mb-1" id="viewLineName">-</h4>
            <span class="badge bg-label-secondary ms-1" id="viewLineStatus">-</span>
            <span class="badge bg-label-info ms-1 mb-2" id="viewLineCreated">-</span>
            <div class=" ms-1" id="viewLineDescription">-</div>
          </div>
          <!-- Line Details -->
        </div>
      </div>
      <div class="modal-footer flex-nowrap flex-sm-wrap">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />



<script>
  document.addEventListener('DOMContentLoaded', function() {
    let dt_lines;

    // Initialize DataTable
    const dt_lines_table = document.querySelector('.datatables-lines');
    if (dt_lines_table) {
      dt_lines = new DataTable(dt_lines_table, {
        ajax: {
          url: '/lines',
          type: 'GET',
          dataSrc: function(json) {
            if (json.success) {
              return json.data;
            } else {
              showToast('Error', json.message || 'Failed to load Lines', 'error');
              return [];
            }
          },
          error: function(xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load Lines. Please try again.', 'error');
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
                        <div class="avatar avatar-sm me-2 d-none d-md-flex">
                          <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="ti tabler-line"></i>
                          </span>
                        </div>
                        <div class="d-flex flex-column">
                          <span class="fw-medium">${data}</span>
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
            data: null,
            orderable: false,
            searchable: false,
            responsivePriority: 2,
            render: function(data, type, full) {
              return `
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-line" data-id="${full.id}" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-line" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-line" data-id="${full.id}" title="Delete">
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
              placeholder: 'Search lines...'
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
        name: 'line Name',
        description: 'Description',
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

    // Add Line Form Submission
    const addLineForm = document.getElementById('addLineForm');
    if (addLineForm) {
      addLineForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addLineForm.checkValidity()) {
          const formData = new FormData(addLineForm);

          fetch('/lines', {
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
              bootstrap.Modal.getInstance(document.getElementById('addLineModal')).hide();
              addLineForm.reset();
              addLineForm.classList.remove('was-validated');

              // Reload table
              dt_lines.ajax.reload();

              showToast('Success', data.message || 'Line added successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to add line', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while adding the Line', 'error');
          });
        }

        addLineForm.classList.add('was-validated');
      });
    }

    // Edit Line Form Submission
    const editLineForm = document.getElementById('editLineForm');
    if (editLineForm) {
      editLineForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editLineForm.checkValidity()) {
          const formData = new FormData(editLineForm);
          const lineId = formData.get('id');

          formData.append('_method', 'PUT');

          fetch(`/lines/${lineId}`, {
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
              bootstrap.Modal.getInstance(document.getElementById('editLineModal')).hide();
              editLineForm.reset();
              editLineForm.classList.remove('was-validated');

              // Reload table
              dt_lines.ajax.reload();

              showToast('Success', data.message || 'Line updated successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to update line', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating the line', 'error');
          });
        }

        editLineForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-line')) {
        const lineId = e.target.closest('.edit-line').dataset.id;
        editLine(lineId);
      }

      if (e.target.closest('.view-line')) {
        const lineId = e.target.closest('.view-line').dataset.id;
        viewLine(lineId);
      }

      if (e.target.closest('.toggle-status')) {
        const lineId = e.target.closest('.toggle-status').dataset.id;
        const currentStatus = e.target.closest('.toggle-status').dataset.status;
        toggleLineStatus(lineId, currentStatus);
      }

      if (e.target.closest('.delete-line')) {
        const lineId = e.target.closest('.delete-line').dataset.id;
        deleteLine(lineId);
      }

      // Edit line from view modal
      if (e.target.closest('#editFromView')) {
        const lineId = document.getElementById('viewLineModal').getAttribute('data-line-id');
        // Close view modal
        bootstrap.Modal.getInstance(document.getElementById('viewLineModal')).hide();
        // Open edit modal
        setTimeout(() => {
          editLine(lineId);
        }, 300);
      }

      // Toggle status from view modal
      if (e.target.closest('#toggleFromView')) {
        const lineId = document.getElementById('viewLineModal').getAttribute('data-line-id');
        const currentStatus = getCurrentLineStatus();
        if (lineId) {
          bootstrap.Modal.getInstance(document.getElementById('viewLineModal')).hide();
          setTimeout(() => {
            toggleLineStatus(lineId, currentStatus);
          }, 300);
        }
      }

      // Duplicate line from view modal
      if (e.target.closest('#duplicateFromView')) {
        const lineId = document.getElementById('viewLineModal').getAttribute('data-line-id');
        if (lineId) {
          bootstrap.Modal.getInstance(document.getElementById('viewLineModal')).hide();
          setTimeout(() => {
            duplicateLine(lineId);
          }, 300);
        }
      }

      // Delete line from view modal
      if (e.target.closest('#deleteFromView')) {
        const lineId = document.getElementById('viewLineModal').getAttribute('data-line-id');
        if (lineId) {
          bootstrap.Modal.getInstance(document.getElementById('viewLineModal')).hide();
          setTimeout(() => {
            deleteLine(lineId);
          }, 300);
        }
      }
    });

    // Get current line status from view modal
    function getCurrentLineStatus() {
      const viewModal = document.getElementById('viewLineModal');
      return viewModal.getAttribute('data-line-status');
    }

    // Duplicate Line Function
    function duplicateLine(lineId) {
      fetch(`/lines/${lineId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const line = data.data;

            // Populate add form with existing data (excluding ID)
            document.getElementById('lineName').value = line.name + ' (Copy)';
            document.getElementById('lineDescription').value = line.description;
            // Show add modal
            const addModal = new bootstrap.Modal(document.getElementById('addLineModal'));
            addModal.show();

            showToast('Info', 'Line data loaded for duplication. Please review and save.', 'info');
          } else {
            showToast('Error', data.message || 'Failed to load Line details for duplication', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading Line details for duplication', 'error');
        });
    }

    // Edit Line Function
    function editLine(lineId) {
      fetch(`/lines/${lineId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const line = data.data;

            // Populate edit form
            document.getElementById('editLineId').value = line.id;
            document.getElementById('editLineName').value = line.name;
            document.getElementById('editLineDescription').value = line.description;
            document.getElementById('editLineStatus').value = line.status;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editLineModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load Line details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading Line details', 'error');
        });
    }

    // View Line Function
    function viewLine(lineId) {
      fetch(`/lines/${lineId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const line = data.data;

            // Update modal content

            // Basic information
            document.getElementById('viewLineName').textContent = line.name || '-';

            // Status
            const statusClass = line.status === 'active' ? 'success' : 'secondary';
            document.getElementById('viewLineStatus').textContent = line.status.charAt(0).toUpperCase() + line.status.slice(1);
            document.getElementById('viewLineStatus').className = `badge bg-label-${statusClass} ms-1`;

            // Details
            document.getElementById('viewLineName').textContent = line.name || '-';
            document.getElementById('viewLineCreated').textContent = line.created_at ? new Date(line.created_at).toLocaleDateString() : '-';
            document.getElementById('viewLineDescription').textContent = line.description || '-';



            // Store bank data for other buttons
            const viewModalElement = document.getElementById('viewLineModal');
            viewModalElement.setAttribute('data-line-id', line.id);
            viewModalElement.setAttribute('data-line-status', line.status);

            // Update toggle button icon and text
            const toggleBtn = document.getElementById('toggleFromView');
            const toggleIcon = line.status === 'active' ? 'toggle-right' : 'toggle-left';
            toggleBtn.innerHTML = `<i class="ti tabler-${toggleIcon} me-1"></i><span class="d-none d-sm-inline">Status</span>`;

            // Show modal
            const viewModal = new bootstrap.Modal(viewModalElement);
            viewModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load line details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading line details', 'error');
        });
    }

    // Toggle Line Status Function
    function toggleLineStatus(lineId, currentStatus) {
      const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
      const action = newStatus === 'active' ? 'activate' : 'deactivate';

      fetch(`/lines/${lineId}/toggle`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload table
          dt_lines.ajax.reload();

          showToast('Success', data.message || `Line ${action}d successfully!`, 'success');
        } else {
          showToast('Error', data.message || `Failed to ${action} line`, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', `An error occurred while ${action}ing the line`, 'error');
      });
    }

    // Delete Line Function
    function deleteLine(lineId) {
      if (confirm('Are you sure you want to delete this line? This action cannot be undone.')) {
        fetch(`/lines/${lineId}`, {
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
            dt_lines.ajax.reload();

            showToast('Success', data.message || 'Line deleted successfully!', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete line', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting the line', 'error');
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
</style>
@endsection
