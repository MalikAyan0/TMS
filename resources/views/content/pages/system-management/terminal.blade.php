@extends('layouts/layoutMaster')

@section('title', 'Terminals - System Management')

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
      <h4 class="card-title mb-1">Terminal Management</h4>
      <p class="card-subtitle mb-0">Manage terminals</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTerminalModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline-block">Add New Terminal</span>
    </button>
  </div>

  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-terminals table">
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

<!-- Add New Terminal Modal -->
<div class="modal fade" id="addTerminalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Add New Terminal
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addTerminalForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label" for="terminalName">Terminal Name</label>
              <input type="text" id="terminalName" name="name" class="form-control" placeholder="Enter Terminal Name" required />
              <div class="invalid-feedback">Please enter a valid terminal name.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="terminalDescription">Description</label>
              <textarea name="description" id="terminalDescription" rows="5" class="form-control" placeholder="Enter Description" required></textarea>
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

<!-- Edit Terminal Modal -->
<div class="modal fade" id="editTerminalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Terminal
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editTerminalForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editTerminalId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="editTerminalName">Terminal Name</label>
              <input type="text" id="editTerminalName" name="name" class="form-control" placeholder="Enter Terminal Name" required />
              <div class="invalid-feedback">Please enter a valid terminal name.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="editTerminalStatus">Status</label>
              <select id="editTerminalStatus" name="status" class="form-select" required>
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <div class="invalid-feedback">Please select a valid status.</div>
            </div>
            <div class="col-md-12">
              <label class="form-label" for="editTerminalDescription">Description</label>
              <textarea  rows="5" id="editTerminalDescription" name="description" class="form-control" placeholder="Enter Description" required></textarea>
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

<!-- View Terminal Modal -->
<div class="modal fade" id="viewTerminalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Terminal Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- Terminal Header -->
          <div class="col-12">
            <h4 class="mb-1" id="viewTerminalName">-</h4>
            <span class="badge bg-label-secondary ms-1" id="viewTerminalStatus">-</span>
            <span class="badge bg-label-info ms-1 mb-2" id="viewTerminalCreated">-</span>
            <div class=" ms-1" id="viewTerminalDescription">-</div>
          </div>
          <!-- Terminal Details -->
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
    let dt_terminals;

    // Initialize DataTable
    const dt_terminals_table = document.querySelector('.datatables-terminals');
    if (dt_terminals_table) {
      dt_terminals = new DataTable(dt_terminals_table, {
        ajax: {
          url: '/terminals',
          type: 'GET',
          dataSrc: function(json) {
            if (json.success) {
              return json.data;
            } else {
              showToast('Error', json.message || 'Failed to load Terminals', 'error');
              return [];
            }
          },
          error: function(xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load Terminals. Please try again.', 'error');
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
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-terminal" data-id="${full.id}" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-terminal" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-terminal" data-id="${full.id}" title="Delete">
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
              placeholder: 'Search terminals...'
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
        name: 'Terminal Name',
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

    // Add Terminal Form Submission
    const addTerminalForm = document.getElementById('addTerminalForm');
    if (addTerminalForm) {
      addTerminalForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addTerminalForm.checkValidity()) {
          const formData = new FormData(addTerminalForm);

          fetch('/terminals', {
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
              bootstrap.Modal.getInstance(document.getElementById('addTerminalModal')).hide();
              addTerminalForm.reset();
              addTerminalForm.classList.remove('was-validated');

              // Reload table
              dt_terminals.ajax.reload();

              showToast('Success', data.message || 'Terminal added successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to add terminal', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while adding the Terminal', 'error');
          });
        }

        addTerminalForm.classList.add('was-validated');
      });
    }

    // Edit Terminal Form Submission
    const editTerminalForm = document.getElementById('editTerminalForm');
    if (editTerminalForm) {
      editTerminalForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editTerminalForm.checkValidity()) {
          const formData = new FormData(editTerminalForm);
          const terminalId = formData.get('id');

          formData.append('_method', 'PUT');

          fetch(`/terminals/${terminalId}`, {
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
              bootstrap.Modal.getInstance(document.getElementById('editTerminalModal')).hide();
              editTerminalForm.reset();
              editTerminalForm.classList.remove('was-validated');

              // Reload table
              dt_terminals.ajax.reload();

              showToast('Success', data.message || 'Terminal updated successfully!', 'success');
            } else {
              // Handle validation errors
              if (data.errors) {
                displayValidationErrors(data.errors);
              } else {
                showToast('Error', data.message || 'Failed to update terminal', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating the terminal', 'error');
          });
        }

        editTerminalForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-terminal')) {
        const terminalId = e.target.closest('.edit-terminal').dataset.id;
        editTerminal(terminalId);
      }

      if (e.target.closest('.view-terminal')) {
        const terminalId = e.target.closest('.view-terminal').dataset.id;
        viewTerminal(terminalId);
      }

      if (e.target.closest('.toggle-status')) {
        const terminalId = e.target.closest('.toggle-status').dataset.id;
        const currentStatus = e.target.closest('.toggle-status').dataset.status;
        toggleTerminalStatus(terminalId, currentStatus);
      }

      if (e.target.closest('.delete-terminal')) {
        const terminalId = e.target.closest('.delete-terminal').dataset.id;
        deleteTerminal(terminalId);
      }

      // Edit terminal from view modal
      if (e.target.closest('#editFromView')) {
        const terminalId = document.getElementById('viewTerminalModal').getAttribute('data-terminal-id');
        // Close view modal
        bootstrap.Modal.getInstance(document.getElementById('viewTerminalModal')).hide();
        // Open edit modal
        setTimeout(() => {
          editTerminal(terminalId);
        }, 300);
      }

      // Toggle status from view modal
      if (e.target.closest('#toggleFromView')) {
        const lineId = document.getElementById('viewLineModal').getAttribute('data-line-id');
        const currentStatus = getCurrentTerminalStatus();
        if (terminalId) {
          bootstrap.Modal.getInstance(document.getElementById('viewTerminalModal')).hide();
          setTimeout(() => {
            toggleTerminalStatus(terminalId, currentStatus);
          }, 300);
        }
      }

      // Duplicate terminal from view modal
      if (e.target.closest('#duplicateFromView')) {
        const terminalId = document.getElementById('viewTerminalModal').getAttribute('data-terminal-id');
        if (terminalId) {
          bootstrap.Modal.getInstance(document.getElementById('viewTerminalModal')).hide();
          setTimeout(() => {
            duplicateTerminal(terminalId);
          }, 300);
        }
      }

      // Delete terminal from view modal
      if (e.target.closest('#deleteFromView')) {
        const terminalId = document.getElementById('viewTerminalModal').getAttribute('data-terminal-id');
        if (terminalId) {
          bootstrap.Modal.getInstance(document.getElementById('viewTerminalModal')).hide();
          setTimeout(() => {
            deleteTerminal(terminalId);
          }, 300);
        }
      }
    });

    // Get current terminal status from view modal
    function getCurrentTerminalStatus() {
      const viewModal = document.getElementById('viewTerminalModal');
      return viewModal.getAttribute('data-terminal-status');
    }

    // Duplicate Terminal Function
    function duplicateTerminal(terminalId) {
      fetch(`/terminals/${terminalId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const terminal = data.data;

            // Populate add form with existing data (excluding ID)
            document.getElementById('terminalName').value = terminal.name + ' (Copy)';
            document.getElementById('terminalDescription').value = terminal.description;
            // Show add modal
            const addModal = new bootstrap.Modal(document.getElementById('addTerminalModal'));
            addModal.show();

            showToast('Info', 'Terminal data loaded for duplication. Please review and save.', 'info');
          } else {
            showToast('Error', data.message || 'Failed to load Terminal details for duplication', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading Terminal details for duplication', 'error');
        });
    }

    // Edit Terminal Function
    function editTerminal(terminalId) {
      fetch(`/terminals/${terminalId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const terminal = data.data;

            // Populate edit form
            document.getElementById('editTerminalId').value = terminal.id;
            document.getElementById('editTerminalName').value = terminal.name;
            document.getElementById('editTerminalDescription').value = terminal.description;
            document.getElementById('editTerminalStatus').value = terminal.status;

            // Show edit modal
            const editModal = new bootstrap.Modal(document.getElementById('editTerminalModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load Terminal details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading Terminal details', 'error');
        });
    }

    // View Terminal Function
    function viewTerminal(terminalId) {
      fetch(`/terminals/${terminalId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const terminal = data.data;

            // Update modal content

            // Basic information
            document.getElementById('viewTerminalName').textContent = terminal.name || '-';

            // Status
            const statusClass = terminal.status === 'active' ? 'success' : 'secondary';
            document.getElementById('viewTerminalStatus').textContent = terminal.status.charAt(0).toUpperCase() + terminal.status.slice(1);
            document.getElementById('viewTerminalStatus').className = `badge bg-label-${statusClass} ms-1`;

            // Details
            document.getElementById('viewTerminalCreated').textContent = terminal.created_at ? new Date(terminal.created_at).toLocaleDateString() : '-';
            document.getElementById('viewTerminalDescription').textContent = terminal.description || '-';



            // Store bank data for other buttons
            const viewModalElement = document.getElementById('viewTerminalModal');
            viewModalElement.setAttribute('data-terminal-id', terminal.id);
            viewModalElement.setAttribute('data-terminal-status', terminal.status);

            // Update toggle button icon and text
            const toggleBtn = document.getElementById('toggleFromView');
            const toggleIcon = terminal.status === 'active' ? 'toggle-right' : 'toggle-left';
            toggleBtn.innerHTML = `<i class="ti tabler-${toggleIcon} me-1"></i><span class="d-none d-sm-inline">Status</span>`;

            // Show modal
            const viewModal = new bootstrap.Modal(viewModalElement);
            viewModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load terminal details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading terminal details', 'error');
        });
    }

    // Toggle Terminal Status Function
    function toggleTerminalStatus(terminalId, currentStatus) {
      const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
      const action = newStatus === 'active' ? 'activate' : 'deactivate';

      fetch(`/terminals/${terminalId}/toggle`, {
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
          dt_terminals.ajax.reload();

          showToast('Success', data.message || `Terminal ${action}d successfully!`, 'success');
        } else {
          showToast('Error', data.message || `Failed to ${action} terminal`, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', `An error occurred while ${action}ing the terminal`, 'error');
      });
    }

    // Delete Terminal Function
    function deleteTerminal(terminalId) {
      if (confirm('Are you sure you want to delete this terminal? This action cannot be undone.')) {
        fetch(`/terminals/${terminalId}`, {
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
            dt_terminals.ajax.reload();

            showToast('Success', data.message || 'Terminal deleted successfully!', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete terminal', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting the terminal', 'error');
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
