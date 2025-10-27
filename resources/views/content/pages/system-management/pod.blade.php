@extends('layouts/layoutMaster')

@section('title', 'PODs - System Management')

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
<!-- POD Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">POD Management</h4>
      <p class="card-subtitle mb-0">Manage PODs</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPODModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline-block">Add New POD</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-pods table">
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

<!-- Add New POD Modal -->
<div class="modal fade" id="addPODModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add New POD
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addPODForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label" for="podName">POD Name</label>
              <input type="text" id="podName" name="name" class="form-control" placeholder="Enter POD Name" required />
              <div class="invalid-feedback">Please enter a valid POD name.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="podDescription">Description</label>
              <textarea name="description" id="podDescription" rows="5" class="form-control" placeholder="Enter Description" required></textarea>
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

<!-- Edit POD Modal -->
<div class="modal fade" id="editPODModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit POD
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editPODForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editPODId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="editPODName">POD Name</label>
              <input type="text" id="editPODName" name="name" class="form-control" placeholder="Enter POD Name" required />
              <div class="invalid-feedback">Please enter a valid POD name.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="editPODStatus">Status</label>
              <select id="editPODStatus" name="status" class="form-select" required>
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <div class="invalid-feedback">Please select a valid status.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="editPODDescription">Description</label>
              <textarea  rows="5" id="editPODDescription" name="description" class="form-control" placeholder="Enter Description" required></textarea>
              <div class="invalid-feedback">Please enter a valid description.</div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update POD
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View POD Modal -->
<div class="modal fade" id="viewPODModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          POD Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- POD Header -->
          <div class="col-12">
            <h4 class="mb-1" id="viewPODName">-</h4>
            <span class="badge bg-label-secondary ms-1" id="viewPODStatus">-</span>
            <span class="badge bg-label-info ms-1 mb-2" id="viewPODCreated">-</span>
            <div class=" ms-1" id="viewPODDescription">-</div>
          </div>
          <!-- POD Details -->
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
    let dt_pods;

    // Initialize DataTable
    const dt_pods_table = document.querySelector('.datatables-pods');
    if (dt_pods_table) {
      dt_pods = new DataTable(dt_pods_table, {
        ajax: {
          url: '/pods',
          type: 'GET',
          dataSrc: function(json) {
            if (json.success) {
              return json.data;
            } else {
              showToast('Error', json.message || 'Failed to load PODs', 'error');
              return [];
            }
          },
          error: function(xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load PODs. Please try again.', 'error');
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
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-pod" data-id="${full.id}" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-pod" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-pod" data-id="${full.id}" title="Delete">
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
              placeholder: 'Search PODs...'
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

    // Add POD Form Submission
    const addPODForm = document.getElementById('addPODForm');
    if (addPODForm) {
      addPODForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addPODForm.checkValidity()) {
          const formData = new FormData(addPODForm);

          fetch('/pods', {
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
              bootstrap.Modal.getInstance(document.getElementById('addPODModal')).hide();
              addPODForm.reset();
              addPODForm.classList.remove('was-validated');

              // Reload table
              dt_pods.ajax.reload();

              showToast('Success', data.message || 'POD added successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to add POD', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while adding the POD', 'error');
          });
        }

        addPODForm.classList.add('was-validated');
      });
    }

    // Edit POD Form Submission
    const editPODForm = document.getElementById('editPODForm');
    if (editPODForm) {
      editPODForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editPODForm.checkValidity()) {
          const formData = new FormData(editPODForm);
          const podId = formData.get('id');

          formData.append('_method', 'PUT');

          fetch(`/pods/${podId}`, {
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
              bootstrap.Modal.getInstance(document.getElementById('editPODModal')).hide();
              editPODForm.reset();
              editPODForm.classList.remove('was-validated');

              // Reload table
              dt_pods.ajax.reload();

              showToast('Success', data.message || 'POD updated successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to update POD', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating the POD', 'error');
          });
        }

        editPODForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-pod')) {
        const podId = e.target.closest('.edit-pod').dataset.id;
        editPOD(podId);
      }

      if (e.target.closest('.view-pod')) {
        const podId = e.target.closest('.view-pod').dataset.id;
        viewPOD(podId);
      }

      if (e.target.closest('.toggle-status')) {
        const podId = e.target.closest('.toggle-status').dataset.id;
        const currentStatus = e.target.closest('.toggle-status').dataset.status;
        togglePODStatus(podId, currentStatus);
      }

      if (e.target.closest('.delete-pod')) {
        const podId = e.target.closest('.delete-pod').dataset.id;
        deletePOD(podId);
      }

      // Edit pod from view modal
      if (e.target.closest('#editFromView')) {
        const podId = document.getElementById('viewPODModal').getAttribute('data-pod-id');
        // Close view modal
        bootstrap.Modal.getInstance(document.getElementById('viewPODModal')).hide();
        // Open edit modal
        setTimeout(() => {
          editPOD(podId);
        }, 300);
      }

      // Toggle status from view modal
      if (e.target.closest('#toggleFromView')) {
        const podId = document.getElementById('viewPODModal').getAttribute('data-pod-id');
        const currentStatus = getCurrentPODStatus();
        if (podId) {
          bootstrap.Modal.getInstance(document.getElementById('viewPODModal')).hide();
          setTimeout(() => {
            togglePODStatus(podId, currentStatus);
          }, 300);
        }
      }

      // Duplicate pod from view modal
      if (e.target.closest('#duplicateFromView')) {
        const podId = document.getElementById('viewPODModal').getAttribute('data-pod-id');
        if (podId) {
          bootstrap.Modal.getInstance(document.getElementById('viewPODModal')).hide();
          setTimeout(() => {
            duplicatePOD(podId);
          }, 300);
        }
      }

      // Delete pod from view modal
      if (e.target.closest('#deleteFromView')) {
        const podId = document.getElementById('viewPODModal').getAttribute('data-pod-id');
        if (podId) {
          bootstrap.Modal.getInstance(document.getElementById('viewPODModal')).hide();
          setTimeout(() => {
            deletePOD(podId);
          }, 300);
        }
      }
    });

    // Get current pod status from view modal
    function getCurrentPODStatus() {
      const viewModal = document.getElementById('viewPODModal');
      return viewModal.getAttribute('data-pod-status');
    }

    // Duplicate POD Function
    function duplicatePOD(podId) {
      fetch(`/pods/${podId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const pod = data.data;

            // Populate add form with existing data (excluding ID)
            document.getElementById('podName').value = pod.name + ' (Copy)';
            document.getElementById('podDescription').value = pod.description;
            // Show add modal
            const addModal = new bootstrap.Modal(document.getElementById('addPODModal'));
            addModal.show();

            showToast('Info', 'POD data loaded for duplication. Please review and save.', 'info');
          } else {
            showToast('Error', data.message || 'Failed to load POD details for duplication', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading POD details for duplication', 'error');
        });
    }

    // Edit POD Function
    function editPOD(podId) {
      fetch(`/pods/${podId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const pod = data.data;

            // Populate edit form
            document.getElementById('editPODId').value = pod.id;
            document.getElementById('editPODName').value = pod.name;
            document.getElementById('editPODDescription').value = pod.description;
            document.getElementById('editPODStatus').value = pod.status;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editPODModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load POD details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading POD details', 'error');
        });
    }

    // View POD Function
    function viewPOD(podId) {
      fetch(`/pods/${podId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const pod = data.data;

            // Update modal content

            // Basic information
            document.getElementById('viewPODName').textContent = pod.name || '-';

            // Status
            const statusClass = pod.status === 'active' ? 'success' : 'secondary';
            document.getElementById('viewPODStatus').textContent = pod.status.charAt(0).toUpperCase() + pod.status.slice(1);
            document.getElementById('viewPODStatus').className = `badge bg-label-${statusClass} ms-1`;

            // Details
            document.getElementById('viewPODCreated').textContent = pod.created_at ? new Date(pod.created_at).toLocaleDateString() : '-';
            document.getElementById('viewPODDescription').textContent = pod.description || '-';



            // Store bank data for other buttons
            const viewModalElement = document.getElementById('viewPODModal');
            viewModalElement.setAttribute('data-pod-id', pod.id);
            viewModalElement.setAttribute('data-pod-status', pod.status);

            // Update toggle button icon and text
            const toggleBtn = document.getElementById('toggleFromView');
            const toggleIcon = pod.status === 'active' ? 'toggle-right' : 'toggle-left';
            toggleBtn.innerHTML = `<i class="ti tabler-${toggleIcon} me-1"></i><span class="d-none d-sm-inline">Status</span>`;

            // Show modal
            const viewModal = new bootstrap.Modal(viewModalElement);
            viewModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load pod details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading pod details', 'error');
        });
    }

    // Toggle POD Status Function
    function togglePODStatus(podId, currentStatus) {
      const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
      const action = newStatus === 'active' ? 'activate' : 'deactivate';

      fetch(`/pods/${podId}/toggle`, {
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
          dt_pods.ajax.reload();

          showToast('Success', data.message || `POD ${action}d successfully!`, 'success');
        } else {
          showToast('Error', data.message || `Failed to ${action} POD`, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', `An error occurred while ${action}ing the POD`, 'error');
      });
    }

    // Delete POD Function
    function deletePOD(podId) {
      if (confirm('Are you sure you want to delete this POD? This action cannot be undone.')) {
        fetch(`/pods/${podId}`, {
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
            dt_pods.ajax.reload();

            showToast('Success', data.message || 'POD deleted successfully!', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete POD', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting the POD', 'error');
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
