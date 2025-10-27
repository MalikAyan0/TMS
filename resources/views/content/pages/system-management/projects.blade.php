@extends('layouts/layoutMaster')

@section('title', 'Projects Management - System Management')

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

<!-- Projects Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Projects <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage transportation and logistics projects</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New Project</span>
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-projects table">
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

<!-- Add New Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-folder-plus me-2"></i>
          Add New Project
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addProjectForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="projectTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-folder"></i></span>
                <input type="text" class="form-control" id="projectTitle" name="projectTitle"
                       placeholder="e.g., Cross-Country Delivery System" required>
                <div class="invalid-feedback">Please provide a valid project title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="projectShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="projectShortName" name="projectShortName"
                       placeholder="e.g., CCDS" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
              <div class="form-text">Maximum 10 characters for easy identification</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Project
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Project
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editProjectForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editProjectId" name="editProjectId">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editProjectTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-folder"></i></span>
                <input type="text" class="form-control" id="editProjectTitle" name="editProjectTitle"
                       placeholder="e.g., Cross-Country Delivery System" required>
                <div class="invalid-feedback">Please provide a valid project title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editProjectShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editProjectShortName" name="editProjectShortName"
                       placeholder="e.g., CCDS" maxlength="10" required>
                <div class="invalid-feedback">Please provide a valid short name (max 10 characters).</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Project
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Project Modal -->
<div class="modal fade" id="viewProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          View Project
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Basic Information -->
          <div class="col-12">
            <div class="card h-100">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-info-circle me-2"></i>
                  Basic Information
                </h6>
                <span id="viewProjectStatus" class="">-</span>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Title</label>
                    <p class="form-control-static" id="viewProjectTitle">-</p>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Short Name</label>
                    <p class="form-control-static">
                      <span id="viewProjectShortName" class="badge bg-label-primary">-</span>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Timestamps -->
          <div class="col-12">
            <div class="card h-100">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-clock me-2"></i>
                  Timestamps
                </h6>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Created Date</label>
                    <p class="form-control-static" id="viewProjectCreated">-</p>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Last Updated</label>
                    <p class="form-control-static" id="viewProjectUpdated">-</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex gap-2">
        <button type="button" id="quickEditBtn" class="btn btn-outline-secondary">
          <i class="ti tabler-edit me-1"></i>Edit
        </button>
        <button type="button" id="quickToggleBtn" class="btn btn-outline-warning">
          <i class="ti tabler-toggle-left me-1"></i>Toggle Status
        </button>
        <button type="button" id="quickDuplicateBtn" class="btn btn-outline-success">
          <i class="ti tabler-copy me-1"></i>Duplicate
        </button>
        <button type="button" id="quickDeleteBtn" class="btn btn-outline-danger">
          <i class="ti tabler-trash me-1"></i>Delete
        </button>
        <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Page Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let projectsData = [];
    let dt_projects;  // Function to load projects from API
    function loadProjects() {
      fetch('/projects', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json', // important
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })

        .then(response => response.json())
        .then(result => {
          if (result.success) {
            projectsData = result.data;

            if (dt_projects) {
              dt_projects.clear().rows.add(projectsData).draw();
            } else {
              initializeDataTable();
            }
          } else {
            window.ToastManager.error('Error', 'Failed to load projects');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while loading projects');
        });
    }

    function initializeDataTable() {
      const dt_projects_table = document.querySelector('.datatables-projects');

      if (dt_projects_table) {
        dt_projects = new DataTable(dt_projects_table, {
          data: projectsData,
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
                return meta.row + 1; // S.No starting from 1
              }
            },
            {
              data: 'title',
              responsivePriority: 1,
              render: function(data, type, full) {
                return `<div class="d-flex flex-column">
                          <span class="fw-medium">${data}</span>
                          <small class="text-muted">Project</small>
                        </div>`;
              }
            },
            {
              data: 'short_name',
              render: function(data, type, full) {
                const statusColors = {
                  active: 'success',
                  pending: 'warning',
                  completed: 'info',
                  inactive: 'secondary'
                };
                const color = statusColors[full.status] || 'secondary';
                return `<span class="badge bg-label-${color}">${data}</span>`;
              }
            },
            {
              data: 'status',
              render: function(data) {
                const statusConfig = {
                  active: { class: 'success', text: 'Active' },
                  inactive: { class: 'secondary', text: 'Inactive' },
                  pending: { class: 'warning', text: 'Pending' },
                  completed: { class: 'info', text: 'Completed' }
                };
                const config = statusConfig[data] || statusConfig.inactive;
                return `<span class="badge bg-label-${config.class}">${config.text}</span>`;
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
                  <div class="d-inline-flex gap-1">
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-primary view-project" data-id="${full.id}" title="View"><i class="ti tabler-eye"></i></a>
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-secondary edit-project" data-id="${full.id}" title="Edit"><i class="ti tabler-edit"></i></a>
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}"><i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i></a>
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-outline-danger delete-project" data-id="${full.id}" title="Delete"><i class="ti tabler-trash"></i></a>
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
          order: [[1, 'desc']],
          layout: {

            topEnd: {
              search: {
                placeholder: 'Search projects...'
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

    // Load projects on page load
    loadProjects();

    // Auto-generate short name from title
    document.getElementById('projectTitle').addEventListener('input', function() {
      const title = this.value;
      const shortName = title
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 10);
      document.getElementById('projectShortName').value = shortName;
    });

    // Add Project Form Submission
    const addProjectForm = document.getElementById('addProjectForm');
    if (addProjectForm) {
      addProjectForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addProjectForm.checkValidity()) {
          const formData = new FormData(addProjectForm);

          const projectData = {
            title: formData.get('projectTitle'),
            short_name: formData.get('projectShortName')
          };

          fetch('/projects', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(projectData)
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              // Reload projects data
              loadProjects();

              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('addProjectModal')).hide();
              addProjectForm.reset();
              addProjectForm.classList.remove('was-validated');

              window.ToastManager.success('Success', result.message || 'Project added successfully!');
            } else {
              if (result.errors) {
                // Handle validation errors
                Object.keys(result.errors).forEach(field => {
                  const input = addProjectForm.querySelector(`[name="${field}"]`);
                  if (input) {
                    input.setCustomValidity(result.errors[field][0]);
                    input.reportValidity();
                  }
                });
              }
              window.ToastManager.error('Error', result.message || 'Failed to add project');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while adding the project');
          });
        }

        addProjectForm.classList.add('was-validated');
      });
    }

    // Edit Project Form Submission
    const editProjectForm = document.getElementById('editProjectForm');
    if (editProjectForm) {
      editProjectForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editProjectForm.checkValidity()) {
          const formData = new FormData(editProjectForm);
          const projectId = formData.get('editProjectId');

          const projectData = {
            title: formData.get('editProjectTitle'),
            short_name: formData.get('editProjectShortName')
          };

          fetch(`/projects/${projectId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(projectData)
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              // Reload projects data
              loadProjects();

              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('editProjectModal')).hide();
              editProjectForm.reset();
              editProjectForm.classList.remove('was-validated');

              window.ToastManager.success('Success', result.message || 'Project updated successfully!');
            } else {
              if (result.errors) {
                // Handle validation errors
                Object.keys(result.errors).forEach(field => {
                  const input = editProjectForm.querySelector(`[name="edit${field.charAt(0).toUpperCase() + field.slice(1)}"]`);
                  if (input) {
                    input.setCustomValidity(result.errors[field][0]);
                    input.reportValidity();
                  }
                });
              }
              window.ToastManager.error('Error', result.message || 'Failed to update project');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while updating the project');
          });
        }

        editProjectForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-project')) {
        const projectId = parseInt(e.target.closest('.edit-project').dataset.id);

        // Fetch project details from API
        fetch(`/projects/${projectId}`)
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              const project = result.data;

              // Populate edit form
              document.getElementById('editProjectId').value = project.id;
              document.getElementById('editProjectTitle').value = project.title;
              document.getElementById('editProjectShortName').value = project.short_name;

              // Show edit modal
              const editModal = new bootstrap.Modal(document.getElementById('editProjectModal'));
              editModal.show();
            } else {
              window.ToastManager.error('Error', result.message || 'Failed to fetch project details');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while fetching project details');
          });
      }

      if (e.target.closest('.view-project')) {
        const projectId = parseInt(e.target.closest('.view-project').dataset.id);

        // Fetch project details from API
        fetch(`/projects/${projectId}`)
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              const project = result.data;

              // Populate view modal
              document.getElementById('viewProjectTitle').textContent = project.title;
              document.getElementById('viewProjectShortName').textContent = project.short_name;
              document.getElementById('viewProjectStatus').innerHTML = `<span class="badge bg-${project.status === 'active' ? 'success' : 'danger'}">${project.status.charAt(0).toUpperCase() + project.status.slice(1)}</span>`;
              document.getElementById('viewProjectCreated').textContent = new Date(project.created_at).toLocaleDateString();
              document.getElementById('viewProjectUpdated').textContent = new Date(project.updated_at).toLocaleDateString();

              // Set up quick action buttons
              document.getElementById('quickEditBtn').onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewProjectModal')).hide();
                setTimeout(() => {
                  // Populate edit form
                  document.getElementById('editProjectId').value = project.id;
                  document.getElementById('editProjectTitle').value = project.title;
                  document.getElementById('editProjectShortName').value = project.short_name;

                  // Show edit modal
                  const editModal = new bootstrap.Modal(document.getElementById('editProjectModal'));
                  editModal.show();
                }, 300);
              };

              document.getElementById('quickToggleBtn').onclick = () => {
                toggleProjectStatus(project.id);
                bootstrap.Modal.getInstance(document.getElementById('viewProjectModal')).hide();
              };

              document.getElementById('quickDuplicateBtn').onclick = () => {
                bootstrap.Modal.getInstance(document.getElementById('viewProjectModal')).hide();
                setTimeout(() => {
                  // Populate add form with project data (without ID)
                  document.getElementById('projectTitle').value = project.title + ' (Copy)';
                  document.getElementById('projectShortName').value = project.short_name + 'C';

                  // Show add modal
                  const addModal = new bootstrap.Modal(document.getElementById('addProjectModal'));
                  addModal.show();
                }, 300);
              };

              document.getElementById('quickDeleteBtn').onclick = () => {
                deleteProject(project.id);
                bootstrap.Modal.getInstance(document.getElementById('viewProjectModal')).hide();
              };

              // Show view modal
              const viewModal = new bootstrap.Modal(document.getElementById('viewProjectModal'));
              viewModal.show();
            } else {
              window.ToastManager.error('Error', result.message || 'Failed to fetch project details');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            window.ToastManager.error('Error', 'An error occurred while fetching project details');
          });
      }

      if (e.target.closest('.toggle-status')) {
        const projectId = parseInt(e.target.closest('.toggle-status').dataset.id);
        toggleProjectStatus(projectId);
      }

      if (e.target.closest('.delete-project')) {
        const projectId = parseInt(e.target.closest('.delete-project').dataset.id);
        deleteProject(projectId);
      }
    });

    // Toggle Project Status Function
    function toggleProjectStatus(projectId) {
      fetch(`/projects/${projectId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          // Reload projects data to reflect the status change
          loadProjects();

          window.ToastManager.success('Success', result.message || 'Project status updated successfully!');
        } else {
          window.ToastManager.error('Error', result.message || 'Failed to update project status');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        window.ToastManager.error('Error', 'An error occurred while updating project status');
      });
    }

    // Delete Project Function
    function deleteProject(projectId) {
      if (confirm('Are you sure you want to delete this project?')) {
        fetch(`/projects/${projectId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            // Reload projects data
            loadProjects();

            window.ToastManager.success('Success', result.message || 'Project deleted successfully!');
          } else {
            window.ToastManager.error('Error', result.message || 'Failed to delete project');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          window.ToastManager.error('Error', 'An error occurred while deleting the project');
        });
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
