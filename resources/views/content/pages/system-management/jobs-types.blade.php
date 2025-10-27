@extends('layouts/layoutMaster')

@section('title', 'Job Types - System Management')

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
<!-- Job Types Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Job Types</h4>
      <p class="card-subtitle mb-0">Manage different types of  jobs.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJobTypeModal">
      <i class="ti tabler-plus me-1"></i>
    <span class="d-none d-sm-inline">Add Job Type</span>
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-job-types table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Title</th>
          <th>Short Name</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Job Type Modal -->
<div class="modal fade" id="addJobTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-briefcase me-2"></i>
          Add New Job Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addJobTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="jobTypeTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-briefcase"></i></span>
                <input type="text" class="form-control" id="jobTypeTitle" name="title"
                       placeholder="e.g., Long Distance Delivery" required>
                <div class="invalid-feedback">Please provide a valid job type title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="jobTypeShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="jobTypeShortName" name="short_name"
                       placeholder="e.g., LDD" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
              <div class="form-text">Maximum 10 characters for easy identification</div>
            </div>

            <div class="col-12">
              <label for="jobTypeDescription" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-notes"></i></span>
                <textarea class="form-control" id="jobTypeDescription" name="description"
                          rows="3" placeholder="Optional description of the job type"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="addJobTypeBtn">
            <span class="indicator-label">
              <i class="ti tabler-device-floppy me-1"></i>
              Save Job Type
            </span>
            <span class="indicator-progress d-none">
              <span class="spinner-border spinner-border-sm" role="status"></span>
              Saving...
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Job Type Modal -->
<div class="modal fade" id="editJobTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Job Type
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editJobTypeForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editJobTypeId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editJobTypeTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-briefcase"></i></span>
                <input type="text" class="form-control" id="editJobTypeTitle" name="title"
                       placeholder="e.g., Long Distance Delivery" required>
                <div class="invalid-feedback">Please provide a valid job type title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editJobTypeShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editJobTypeShortName" name="short_name"
                       placeholder="e.g., LDD" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
            </div>

            <div class="col-12">
              <label for="editJobTypeDescription" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-notes"></i></span>
                <textarea class="form-control" id="editJobTypeDescription" name="description"
                          rows="3" placeholder="Optional description of the job type"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="editJobTypeBtn">
            <span class="indicator-label">
              <i class="ti tabler-device-floppy me-1"></i>
              Update Job Type
            </span>
            <span class="indicator-progress d-none">
              <span class="spinner-border spinner-border-sm" role="status"></span>
              Updating...
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Job Type Modal -->
<div class="modal fade" id="viewJobTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Job Type Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
              <div class="row g-4">
                <div class="col-md-6">
                  <div class="d-flex align-items-center mb-3">
                    <i class="ti tabler-briefcase me-2 text-primary"></i>
                    <div>
                      <h6 class="mb-1">Title</h6>
                      <p class="mb-0" id="viewJobTypeTitle">-</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="d-flex align-items-center mb-3">
                    <i class="ti tabler-tag me-2 text-info"></i>
                    <div>
                      <h6 class="mb-1">Short Name</h6>
                      <span class="badge bg-label-primary font-monospace" id="viewJobTypeShortName">-</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="d-flex align-items-center mb-3">
                    <i class="ti tabler-circle-check me-2 text-success"></i>
                    <div>
                      <h6 class="mb-1">Status</h6>
                      <span id="viewJobTypeStatus">-</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="d-flex align-items-center mb-3">
                    <i class="ti tabler-calendar me-2 text-warning"></i>
                    <div>
                      <h6 class="mb-1">Created Date</h6>
                      <p class="mb-0" id="viewJobTypeCreated">-</p>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-flex align-items-start mb-3">
                    <i class="ti tabler-notes me-2 text-secondary mt-1"></i>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">Description</h6>
                      <p class="mb-0 text-muted" id="viewJobTypeDescription">-</p>
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

<!-- Toast Container -->
<x-toast-container />

<!-- Page Scripts -->
@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let dt_job_types;
    let currentJobTypeId = null;

    // Initialize DataTable
    const dt_job_types_table = document.querySelector('.datatables-job-types');

    if (dt_job_types_table) {
      dt_job_types = new DataTable(dt_job_types_table, {
        ajax: {
          url: '/jobs-types',
          type: 'GET',
          dataSrc: function(json) {
            if (json.success) {
              return json.data;
            } else {
              showToast('Error', json.message || 'Failed to load job types', 'error');
              return [];
            }
          },
          error: function(xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load job types. Please try again.', 'error');
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
            data: 'title',
            render: function(data, type, full) {
              return `<div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">${full.description || 'No description'}</small>
                      </div>`;
            }
          },
          {
            data: 'short_name',
            render: function(data, type, full) {
              const statusColors = {
                active: 'primary',
                inactive: 'secondary'
              };
              const color = statusColors[full.status] || 'secondary';
              return `<span class="badge bg-label-${color} font-monospace">${data}</span>`;
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
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function(data, type, full) {
              return `
                <div class="d-flex align-items-center gap-1">
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary waves-effect view-job-type" data-id="${full.id}" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary waves-effect edit-job-type" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} waves-effect toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-job-type" data-id="${full.id}" title="Delete">
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
              placeholder: 'Search job types...'
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

    // Auto-generate short name from title
    document.getElementById('jobTypeTitle').addEventListener('input', function() {
      const title = this.value;
      const shortName = title
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 10);
      document.getElementById('jobTypeShortName').value = shortName;
    });

    // Add Job Type Form Submission
    const addJobTypeForm = document.getElementById('addJobTypeForm');
    if (addJobTypeForm) {
      addJobTypeForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addJobTypeForm.checkValidity()) {
          const submitBtn = document.getElementById('addJobTypeBtn');
          const indicatorLabel = submitBtn.querySelector('.indicator-label');
          const indicatorProgress = submitBtn.querySelector('.indicator-progress');

          // Show loading state
          submitBtn.disabled = true;
          indicatorLabel.classList.add('d-none');
          indicatorProgress.classList.remove('d-none');

          const formData = new FormData(addJobTypeForm);
          const jobTypeData = Object.fromEntries(formData.entries());

          fetch('/jobs-types', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(jobTypeData)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('addJobTypeModal')).hide();
              addJobTypeForm.reset();
              addJobTypeForm.classList.remove('was-validated');

              // Reload DataTable
              dt_job_types.ajax.reload();

              showToast('Success', data.message || 'Job type added successfully!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to add job type', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while adding the job type', 'error');
          })
          .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            indicatorLabel.classList.remove('d-none');
            indicatorProgress.classList.add('d-none');
          });
        }

        addJobTypeForm.classList.add('was-validated');
      });
    }

    // Edit Job Type Form Submission
    const editJobTypeForm = document.getElementById('editJobTypeForm');
    if (editJobTypeForm) {
      editJobTypeForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editJobTypeForm.checkValidity()) {
          const submitBtn = document.getElementById('editJobTypeBtn');
          const indicatorLabel = submitBtn.querySelector('.indicator-label');
          const indicatorProgress = submitBtn.querySelector('.indicator-progress');

          // Show loading state
          submitBtn.disabled = true;
          indicatorLabel.classList.add('d-none');
          indicatorProgress.classList.remove('d-none');

          const formData = new FormData(editJobTypeForm);
          const jobTypeData = Object.fromEntries(formData.entries());
          const jobTypeId = jobTypeData.id;

          fetch(`/jobs-types/${jobTypeId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(jobTypeData)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('editJobTypeModal')).hide();
              editJobTypeForm.reset();
              editJobTypeForm.classList.remove('was-validated');

              // Reload DataTable
              dt_job_types.ajax.reload();

              showToast('Success', data.message || 'Job type updated successfully!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to update job type', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating the job type', 'error');
          })
          .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            indicatorLabel.classList.remove('d-none');
            indicatorProgress.classList.add('d-none');
          });
        }

        editJobTypeForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.view-job-type')) {
        const jobTypeId = e.target.closest('.view-job-type').dataset.id;
        viewJobType(jobTypeId);
      }

      if (e.target.closest('.edit-job-type')) {
        const jobTypeId = e.target.closest('.edit-job-type').dataset.id;
        editJobType(jobTypeId);
      }

      if (e.target.closest('.toggle-status')) {
        const jobTypeId = e.target.closest('.toggle-status').dataset.id;
        const currentStatus = e.target.closest('.toggle-status').dataset.status;
        toggleJobTypeStatus(jobTypeId, currentStatus);
      }

      if (e.target.closest('.delete-job-type')) {
        const jobTypeId = e.target.closest('.delete-job-type').dataset.id;
        deleteJobType(jobTypeId);
      }
    });

    // Edit from view modal
    document.getElementById('editFromView').addEventListener('click', function() {
      if (currentJobTypeId) {
        bootstrap.Modal.getInstance(document.getElementById('viewJobTypeModal')).hide();
        setTimeout(() => {
          editJobType(currentJobTypeId);
        }, 300);
      }
    });

    // Toggle from view modal
    document.getElementById('toggleFromView').addEventListener('click', function() {
      if (currentJobTypeId) {
        // Get current status from the view modal
        const statusElement = document.getElementById('viewJobTypeStatus');
        const currentStatus = statusElement.textContent.toLowerCase().includes('active') ? 'active' : 'inactive';

        bootstrap.Modal.getInstance(document.getElementById('viewJobTypeModal')).hide();
        setTimeout(() => {
          toggleJobTypeStatus(currentJobTypeId, currentStatus);
        }, 300);
      }
    });

    // Duplicate from view modal
    document.getElementById('duplicateFromView').addEventListener('click', function() {
      if (currentJobTypeId) {
        duplicateJobType(currentJobTypeId);
      }
    });

    // Delete from view modal
    document.getElementById('deleteFromView').addEventListener('click', function() {
      if (currentJobTypeId) {
        bootstrap.Modal.getInstance(document.getElementById('viewJobTypeModal')).hide();
        setTimeout(() => {
          deleteJobType(currentJobTypeId);
        }, 300);
      }
    });

    // View Job Type Function
    function viewJobType(jobTypeId) {
      fetch(`/jobs-types/${jobTypeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobType = data.data;
            currentJobTypeId = jobType.id;

            document.getElementById('viewJobTypeTitle').textContent = jobType.title;
            document.getElementById('viewJobTypeShortName').textContent = jobType.short_name;
            document.getElementById('viewJobTypeDescription').textContent = jobType.description || 'No description available';
            document.getElementById('viewJobTypeCreated').textContent = jobType.created_at ? new Date(jobType.created_at).toLocaleDateString() : '-';

            // Set status badge
            const statusConfig = {
              active: { class: 'success', text: 'Active', icon: 'check' },
              inactive: { class: 'secondary', text: 'Inactive', icon: 'x' }
            };
            const config = statusConfig[jobType.status] || statusConfig.inactive;
            document.getElementById('viewJobTypeStatus').innerHTML = `
              <span class="badge bg-label-${config.class}">
                <i class="ti tabler-${config.icon} me-1"></i>${config.text}
              </span>
            `;

            // Update toggle button text based on current status
            const toggleBtn = document.getElementById('toggleFromView');
            if (jobType.status === 'active') {
              toggleBtn.innerHTML = '<i class="ti tabler-toggle-left me-1"></i>Deactivate';
              toggleBtn.className = 'btn btn-outline-warning';
            } else {
              toggleBtn.innerHTML = '<i class="ti tabler-toggle-right me-1"></i>Activate';
              toggleBtn.className = 'btn btn-outline-success';
            }

            const viewModal = new bootstrap.Modal(document.getElementById('viewJobTypeModal'));
            viewModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load job type details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading job type details', 'error');
        });
    }

    // Edit Job Type Function
    function editJobType(jobTypeId) {
      fetch(`/jobs-types/${jobTypeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobType = data.data;

            document.getElementById('editJobTypeId').value = jobType.id;
            document.getElementById('editJobTypeTitle').value = jobType.title;
            document.getElementById('editJobTypeShortName').value = jobType.short_name;
            document.getElementById('editJobTypeDescription').value = jobType.description || '';

            const editModal = new bootstrap.Modal(document.getElementById('editJobTypeModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load job type details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading job type details', 'error');
        });
    }

    // Toggle Job Type Status Function
    function toggleJobTypeStatus(jobTypeId, currentStatus) {
      const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
      const action = newStatus === 'active' ? 'activate' : 'deactivate';

      fetch(`/jobs-types/${jobTypeId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          dt_job_types.ajax.reload();
          showToast('Success', data.message || `Job type ${action}d successfully!`, 'success');
        } else {
          showToast('Error', data.message || `Failed to ${action} job type`, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', `An error occurred while ${action}ing the job type`, 'error');
      });
    }

    // Delete Job Type Function
    function deleteJobType(jobTypeId) {
      if (confirm('Are you sure you want to delete this job type? This action cannot be undone.')) {
        fetch(`/jobs-types/${jobTypeId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            dt_job_types.ajax.reload();
            showToast('Success', data.message || 'Job type deleted successfully!', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete job type', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting the job type', 'error');
        });
      }
    }

    // Duplicate Job Type Function
    function duplicateJobType(jobTypeId) {
      fetch(`/jobs-types/${jobTypeId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const jobType = data.data;

            // Close view modal
            bootstrap.Modal.getInstance(document.getElementById('viewJobTypeModal')).hide();

            // Populate add form with duplicated data
            setTimeout(() => {
              document.getElementById('jobTypeTitle').value = jobType.title + ' (Copy)';
              document.getElementById('jobTypeShortName').value = jobType.short_name + '_C';
              document.getElementById('jobTypeDescription').value = jobType.description || '';

              const addModal = new bootstrap.Modal(document.getElementById('addJobTypeModal'));
              addModal.show();

              showToast('Info', 'Job type data loaded for duplication. Please review and save.', 'info');
            }, 300);
          } else {
            showToast('Error', data.message || 'Failed to load job type for duplication', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while preparing job type duplication', 'error');
        });
    }

    // Toast notification function
    function showToast(title, message, type = 'info') {
      // Try to use custom toast if available, otherwise fallback to alert
      if (typeof window.showToast === 'function') {
        window.showToast(title, message, type);
      } else {
        // Fallback toast implementation
        const toastContainer = document.querySelector('[data-toast-container]') || document.body;

        const toastId = 'toast-' + Date.now();
        const typeColors = {
          success: 'success',
          error: 'danger',
          warning: 'warning',
          info: 'primary'
        };
        const color = typeColors[type] || 'primary';

        const toastHTML = `
          <div class="toast align-items-center text-bg-${color} border-0" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}" style="position: fixed; top: 20px; right: 20px; z-index: 1055;">
            <div class="d-flex">
              <div class="toast-body">
                <strong>${title}:</strong> ${message}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);

        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 5000 });
        toast.show();

        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
          toastElement.remove();
        });
      }
    }
  });
</script>
@endsection

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
