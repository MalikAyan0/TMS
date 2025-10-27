@extends('layouts/layoutMaster')

@section('title', 'Ports - System Management')

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
      <h4 class="card-title mb-1">Port Management</h4>
      <p class="card-subtitle mb-0">Manage Ports</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPortModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline-block">Add New Port</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-ports table">
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

<!-- Add New Port Modal -->
<div class="modal fade" id="addPortModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add New Port
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addPortForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label" for="portName">Port Name</label>
              <input type="text" id="portName" name="name" class="form-control" placeholder="Enter Port Name" required />
              <div class="invalid-feedback">Please enter a valid port name.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="portDescription">Description</label>
              <textarea name="description" id="portDescription" rows="5" class="form-control" placeholder="Enter Description" required></textarea>
              <div class="invalid-feedback">Please enter a valid description.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Port
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Port Modal -->
<div class="modal fade" id="editPortModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Port
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editPortForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editPortId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="editPortName">Port Name</label>
              <input type="text" id="editPortName" name="name" class="form-control" placeholder="Enter Port Name" required />
              <div class="invalid-feedback">Please enter a valid port name.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="editPortStatus">Status</label>
              <select id="editPortStatus" name="status" class="form-select" required>
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <div class="invalid-feedback">Please select a valid status.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="editPortDescription">Description</label>
              <textarea  rows="5" id="editPortDescription" name="description" class="form-control" placeholder="Enter Description" required></textarea>
              <div class="invalid-feedback">Please enter a valid description.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Port
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Port Modal -->
<div class="modal fade" id="viewPortModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Port Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- Port Header -->
          <div class="col-12">
            <h4 class="mb-1" id="viewPortName">-</h4>
            <span class="badge bg-label-secondary ms-1" id="viewPortStatus">-</span>
            <span class="badge bg-label-info ms-1 mb-2" id="viewPortCreated">-</span>
            <div class=" ms-1" id="viewPortDescription">-</div>
          </div>
          <!-- Port Details -->
        </div>
      </div>
      <div class="modal-footer">
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />



<script>
document.addEventListener('DOMContentLoaded', function() {
  let dt_ports;

  // Initialize DataTable
  const dt_ports_table = document.querySelector('.datatables-ports');
  if (dt_ports_table) {
    dt_ports = new DataTable(dt_ports_table, {
      ajax: {
        url: '/ports',
        type: 'GET',
        dataSrc: function(json) {
          if (json.success) {
            return json.data;
          } else {
            showToast('Error', json.message || 'Failed to load Ports', 'error');
            return [];
          }
        },
        error: function(xhr, error, thrown) {
          console.error('DataTable Error:', error);
          showToast('Error', 'Failed to load Ports. Please try again.', 'error');
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
          render: function(data, type, full) {
            return `<div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                          <i class="ti tabler-ship"></i>
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
          render: function(data, type, full) {
            return `
              <div class="d-flex gap-1">
                <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-port" data-id="${full.id}" title="View Details">
                  <i class="ti tabler-eye"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-port" data-id="${full.id}" title="Edit">
                  <i class="ti tabler-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                  <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-port" data-id="${full.id}" title="Delete">
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
            placeholder: 'Search Ports...'
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
      name: ' Port Name',
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

  // Add Port Form Submission
  const addPortForm = document.getElementById('addPortForm');
  if (addPortForm) {
    addPortForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (addPortForm.checkValidity()) {
        const formData = new FormData(addPortForm);

        fetch('/ports', {
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
            bootstrap.Modal.getInstance(document.getElementById('addPortModal')).hide();
            addPortForm.reset();
            addPortForm.classList.remove('was-validated');

            // Reload table
            dt_ports.ajax.reload();

            showToast('Success', data.message || 'Port added successfully!', 'success');
          } else {
            // Handle validation errors
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to add port', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while adding the Port', 'error');
        });
      }

      addPortForm.classList.add('was-validated');
    });
  }

  // Edit Port Form Submission
  const editPortForm = document.getElementById('editPortForm');
  if (editPortForm) {
    editPortForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (editPortForm.checkValidity()) {
        const formData = new FormData(editPortForm);
        const portId = formData.get('id');

        formData.append('_method', 'PUT');

        fetch(`/ports/${portId}`, {
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
            bootstrap.Modal.getInstance(document.getElementById('editPortModal')).hide();
            editPortForm.reset();
            editPortForm.classList.remove('was-validated');

            // Reload table
            dt_ports.ajax.reload();

            showToast('Success', data.message || 'Port updated successfully!', 'success');
          } else {
            // Handle validation errors
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to update port', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating the port', 'error');
        });
      }

      editPortForm.classList.add('was-validated');
    });
  }

  // Event delegation for action buttons
  document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-port')) {
      const portId = e.target.closest('.edit-port').dataset.id;
      editPort(portId);
    }

    if (e.target.closest('.view-port')) {
      const portId = e.target.closest('.view-port').dataset.id;
      viewPort(portId);
    }

    if (e.target.closest('.toggle-status')) {
      const portId = e.target.closest('.toggle-status').dataset.id;
      const currentStatus = e.target.closest('.toggle-status').dataset.status;
      togglePortStatus(portId, currentStatus);
    }

    if (e.target.closest('.delete-port')) {
      const portId = e.target.closest('.delete-port').dataset.id;
      deletePort(portId);
    }

    // Edit port from view modal
    if (e.target.closest('#editFromView')) {
      const portId = document.getElementById('viewPortModal').getAttribute('data-port-id');
      // Close view modal
      bootstrap.Modal.getInstance(document.getElementById('viewPortModal')).hide();
      // Open edit modal
      setTimeout(() => {
        editPort(portId);
      }, 300);
    }

    // Toggle status from view modal
    if (e.target.closest('#toggleFromView')) {
      const portId = document.getElementById('viewPortModal').getAttribute('data-port-id');
      const currentStatus = getCurrentPortStatus();
      if (portId) {
        bootstrap.Modal.getInstance(document.getElementById('viewPortModal')).hide();
        setTimeout(() => {
          togglePortStatus(portId, currentStatus);
        }, 300);
      }
    }

    // Duplicate port from view modal
    if (e.target.closest('#duplicateFromView')) {
      const portId = document.getElementById('viewPortModal').getAttribute('data-port-id');
      if (portId) {
        bootstrap.Modal.getInstance(document.getElementById('viewPortModal')).hide();
        setTimeout(() => {
          duplicatePort(portId);
        }, 300);
      }
    }

    // Delete port from view modal
    if (e.target.closest('#deleteFromView')) {
      const portId = document.getElementById('viewPortModal').getAttribute('data-port-id');
      if (portId) {
        bootstrap.Modal.getInstance(document.getElementById('viewPortModal')).hide();
        setTimeout(() => {
          deletePort(portId);
        }, 300);
      }
    }
  });

  // Get current port status from view modal
  function getCurrentPortStatus() {
    const viewModal = document.getElementById('viewPortModal');
    return viewModal.getAttribute('data-port-status');
  }

  // Duplicate Port Function
  function duplicatePort(portId) {
    fetch(`/ports/${portId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const port = data.data;

          // Populate add form with existing data (excluding ID)
          document.getElementById('portName').value = port.name + ' (Copy)';
          document.getElementById('portDescription').value = port.description;
          // Show add modal
          const addModal = new bootstrap.Modal(document.getElementById('addPortModal'));
          addModal.show();

          showToast('Info', 'Port data loaded for duplication. Please review and save.', 'info');
        } else {
          showToast('Error', data.message || 'Failed to load Port details for duplication', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading Port details for duplication', 'error');
      });
  }

  // Edit Port Function
  function editPort(portId) {
    fetch(`/ports/${portId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const port = data.data;

          // Populate edit form
          document.getElementById('editPortId').value = port.id;
          document.getElementById('editPortName').value = port.name;
          document.getElementById('editPortDescription').value = port.description;
          document.getElementById('editPortStatus').value = port.status;

          // Show edit modal
          const editModal = new bootstrap.Modal(document.getElementById('editPortModal'));
          editModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load Port details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading Port details', 'error');
      });
  }

  // View Port Function
  function viewPort(portId) {
    fetch(`/ports/${portId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const port = data.data;

          // Update modal content

          // Basic information
          document.getElementById('viewPortName').textContent = port.name || '-';

          // Status
          const statusClass = port.status === 'active' ? 'success' : 'secondary';
          document.getElementById('viewPortStatus').textContent = port.status.charAt(0).toUpperCase() + port.status.slice(1);
          document.getElementById('viewPortStatus').className = `badge bg-label-${statusClass} ms-1`;

          // Details
          document.getElementById('viewPortName').textContent = port.name || '-';
          document.getElementById('viewPortCreated').textContent = port.created_at ? new Date(port.created_at).toLocaleDateString() : '-';
          document.getElementById('viewPortDescription').textContent = port.description || '-';



          // Store bank data for other buttons
          const viewModalElement = document.getElementById('viewPortModal');
          viewModalElement.setAttribute('data-port-id', port.id);
          viewModalElement.setAttribute('data-port-status', port.status);

          // Update toggle button icon and text
          const toggleBtn = document.getElementById('toggleFromView');
          const toggleIcon = port.status === 'active' ? 'toggle-right' : 'toggle-left';
          toggleBtn.innerHTML = `<i class="ti tabler-${toggleIcon} me-1"></i>Toggle Status`;

          // Show modal
          const viewModal = new bootstrap.Modal(viewModalElement);
          viewModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load port details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading port details', 'error');
      });
  }

  // Toggle Port Status Function
  function togglePortStatus(portId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const action = newStatus === 'active' ? 'activate' : 'deactivate';

    fetch(`/ports/${portId}/toggle`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reload table
        dt_ports.ajax.reload();

        showToast('Success', data.message || `Port ${action}d successfully!`, 'success');
      } else {
        showToast('Error', data.message || `Failed to ${action} port`, 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showToast('Error', `An error occurred while ${action}ing the port`, 'error');
    });
  }

  // Delete Port Function
  function deletePort(portId) {
    if (confirm('Are you sure you want to delete this port? This action cannot be undone.')) {
      fetch(`/ports/${portId}`, {
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
          dt_ports.ajax.reload();

          showToast('Success', data.message || 'Port deleted successfully!', 'success');
        } else {
          showToast('Error', data.message || 'Failed to delete port', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while deleting the port', 'error');
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
