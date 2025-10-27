@extends('layouts/layoutMaster')

@section('title', 'Companies - System Management')

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
<!-- Companies Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Descriptions</h4>
      <p class="card-subtitle mb-0">Manage Descriptions information</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New Descriptions</span>
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-companies table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Descriptions</th>
          <th>Short Name</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Company Modal -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-building-plus me-2"></i>
          Add New Descriptions
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addCompanyForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="companyTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="companyTitle" name="title"
                       placeholder="e.g., ABC Transportation Ltd." required>
                <div class="invalid-feedback">Please provide a valid company title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="companyShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="companyShortName" name="short_name"
                       placeholder="e.g., ABC" maxlength="15" required>
                <div class="invalid-feedback">Please provide a valid short name (max 15 characters).</div>
              </div>
              <div class="form-text">Maximum 15 characters for easy identification</div>
            </div>

            <div class="col-12">
              <label for="companyLogo" class="form-label">Logo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-photo"></i></span>
                <input type="file" class="form-control" id="companyLogo" name="logo"
                       accept="image/*">
              </div>
              <div class="form-text">Upload company logo (JPG, PNG, GIF)</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Descriptions
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Company Modal -->
<div class="modal fade" id="editCompanyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Descriptions
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editCompanyForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editCompanyId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editCompanyTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="editCompanyTitle" name="title"
                       placeholder="e.g., ABC Transportation Ltd." required>
                <div class="invalid-feedback">Please provide a valid Descriptions title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editCompanyShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editCompanyShortName" name="short_name"
                       placeholder="e.g., ABC" maxlength="15" required>
                <div class="invalid-feedback">Please provide a valid short name (max 15 characters).</div>
              </div>
            </div>

            <div class="col-12">
              <label for="editCompanyLogo" class="form-label">Logo</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-photo"></i></span>
                <input type="file" class="form-control" id="editCompanyLogo" name="logo" accept="image/*">
              </div>
              <div class="form-text">Upload new logo or leave empty to keep current</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Descriptions
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Company Modal -->
<div class="modal fade" id="viewCompanyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Descriptions Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Company Header -->
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body d-flex align-items-center">

                <!-- Logo -->
                <div class="me-3">
                  <span class="text-muted" id="viewCompanyLogo">No logo uploaded</span>
                </div>

                <!-- Company Info -->
                <div class="flex-grow-1">
                  <h4 class="fw-bold mb-1 text-dark" id="viewCompanyTitle">Descriptions Name</h4>

                  <p class="mb-1 text-muted small">
                    <i class="ti tabler-tag me-1"></i>
                    <span id="viewCompanyShortName">Short Name</span>
                  </p>

                  <p class="mb-0 text-muted small">
                    <i class="ti tabler-calendar me-1"></i>
                    <span id="viewCompanyCreated">Created Date</span>
                  </p>
                </div>

                <!-- Status -->
                <div class="text-end">
                  <span class="badge rounded-pill bg-success px-3 py-2" id="viewCompanyStatus">
                    Active
                  </span>
                </div>
              </div>
            </div>
          </div>



        </div>
      </div>
      <div class="modal-footer">

          <button type="button" class="btn btn-primary" id="viewModalEditBtn">
            <i class="ti tabler-edit me-1"></i>
            Edit
          </button>
          <button type="button" class="btn btn-outline-secondary" id="viewModalToggleBtn">
            <i class="ti tabler-toggle-left me-1"></i>
            <span>Status</span>
          </button>
          <button type="button" class="btn btn-outline-info" id="viewModalDuplicateBtn">
            <i class="ti tabler-copy me-1"></i>
            Duplicate
          </button>
          <button type="button" class="btn btn-outline-danger" id="viewModalDeleteBtn">
            <i class="ti tabler-trash me-1"></i>
            Delete
          </button>

        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="ti tabler-x me-1"></i>
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />

<script>
document.addEventListener('DOMContentLoaded', function() {
  let dt_companies;

  // Initialize DataTable
  const dt_companies_table = document.querySelector('.datatables-companies');

  if (dt_companies_table) {
    dt_companies = new DataTable(dt_companies_table, {
      ajax: {
        url: '/companies',
        type: 'GET',
        dataSrc: function(json) {
          if (json.success) {
            return json.data;
          } else {
            showToast('Error', json.message || 'Failed to load companies', 'error');
            return [];
          }
        },
        error: function(xhr, error, thrown) {
          console.error('DataTable Error:', error);
          showToast('Error', 'Failed to load companies. Please try again.', 'error');
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
          responsivePriority: 1,
          render: function(data, type, full) {
            const logoDisplay = full.logo ?
              `<img src="<?php echo asset('storage/companies/${full.logo}'); ?>" class="rounded-circle me-2" width="32" height="32" alt="Logo">` :
              `<div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-primary">
                  <i class="ti tabler-building"></i>
                </span>
              </div>`;

            return `<div class="d-flex align-items-center">
                      ${logoDisplay}
                      <div class="d-flex flex-column">
                        <span class="fw-medium text-truncate">${data}</span>
                      </div>
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
          responsivePriority: 2,
          orderable: false,
          searchable: false,
          render: function(data, type, full) {
            return `
              <div class="d-flex gap-1">
                <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-company" data-id="${full.id}" title="View Details">
                  <i class="ti tabler-eye"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-company" data-id="${full.id}" title="Edit">
                  <i class="ti tabler-edit"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                  <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-company" data-id="${full.id}" title="Delete">
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
            placeholder: 'Search companies...'
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
  document.getElementById('companyTitle').addEventListener('input', function() {
    const title = this.value;
    const shortName = title
      .split(' ')
      .map(word => word.charAt(0).toUpperCase())
      .join('')
      .substring(0, 15);
    document.getElementById('companyShortName').value = shortName;
  });

  // Add Company Form Submission
  const addCompanyForm = document.getElementById('addCompanyForm');
  if (addCompanyForm) {
    addCompanyForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (addCompanyForm.checkValidity()) {
        const formData = new FormData(addCompanyForm);

        fetch('/companies', {
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
            bootstrap.Modal.getInstance(document.getElementById('addCompanyModal')).hide();
            addCompanyForm.reset();
            addCompanyForm.classList.remove('was-validated');

            // Reload DataTable
            dt_companies.ajax.reload();

            showToast('Success', data.message || 'Company added successfully!', 'success');
          } else {
            // Handle validation errors
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to add company', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while adding the company', 'error');
        });
      }

      addCompanyForm.classList.add('was-validated');
    });
  }

  // Edit Company Form Submission
  const editCompanyForm = document.getElementById('editCompanyForm');
  if (editCompanyForm) {
    editCompanyForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (editCompanyForm.checkValidity()) {
        const formData = new FormData(editCompanyForm);
        const companyId = formData.get('id');

        formData.append('_method', 'PUT');

        fetch(`/companies/${companyId}`, {
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
            bootstrap.Modal.getInstance(document.getElementById('editCompanyModal')).hide();
            editCompanyForm.reset();
            editCompanyForm.classList.remove('was-validated');

            // Reload DataTable
            dt_companies.ajax.reload();

            showToast('Success', data.message || 'Company updated successfully!', 'success');
          } else {
            // Handle validation errors
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to update company', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating the company', 'error');
        });
      }

      editCompanyForm.classList.add('was-validated');
    });
  }

  // Event delegation for action buttons
  document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-company')) {
      const companyId = e.target.closest('.edit-company').dataset.id;
      editCompany(companyId);
    }

    if (e.target.closest('.view-company')) {
      const companyId = e.target.closest('.view-company').dataset.id;
      viewCompany(companyId);
    }

    if (e.target.closest('.toggle-status')) {
      const companyId = e.target.closest('.toggle-status').dataset.id;
      const currentStatus = e.target.closest('.toggle-status').dataset.status;
      toggleCompanyStatus(companyId, currentStatus);
    }

    if (e.target.closest('.delete-company')) {
      const companyId = e.target.closest('.delete-company').dataset.id;
      deleteCompany(companyId);
    }

    // View modal action buttons
    if (e.target.closest('#viewModalEditBtn')) {
      const companyId = e.target.closest('#viewModalEditBtn').dataset.id;
      bootstrap.Modal.getInstance(document.getElementById('viewCompanyModal')).hide();
      editCompany(companyId);
    }

    if (e.target.closest('#viewModalToggleBtn')) {
      const companyId = e.target.closest('#viewModalToggleBtn').dataset.id;
      const currentStatus = e.target.closest('#viewModalToggleBtn').dataset.status;
      bootstrap.Modal.getInstance(document.getElementById('viewCompanyModal')).hide();
      toggleCompanyStatus(companyId, currentStatus);
    }

    if (e.target.closest('#viewModalDuplicateBtn')) {
      const companyId = e.target.closest('#viewModalDuplicateBtn').dataset.id;
      bootstrap.Modal.getInstance(document.getElementById('viewCompanyModal')).hide();
      duplicateCompany(companyId);
    }

    if (e.target.closest('#viewModalDeleteBtn')) {
      const companyId = e.target.closest('#viewModalDeleteBtn').dataset.id;
      bootstrap.Modal.getInstance(document.getElementById('viewCompanyModal')).hide();
      deleteCompany(companyId);
    }
  });

  // View Company Function
  function viewCompany(companyId) {
    fetch(`/companies/${companyId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const company = data.data;

          // Populate modal with company data
          document.getElementById('viewCompanyTitle').textContent = company.title;
          document.getElementById('viewCompanyShortName').textContent = company.short_name;
          document.getElementById('viewCompanyCreated').textContent = company.created_at ?
            new Date(company.created_at).toLocaleDateString() : '-';

          // Update logo display
          const logoElement = document.getElementById('viewCompanyLogo');
          if (company.logo) {
            logoElement.innerHTML = `<img src="/storage/companies/${company.logo}" alt="Logo" class="img-thumbnail rounded-circle me-2" style="max-width: 100px;">`;
          } else {
            logoElement.innerHTML = `<i class="ti tabler-photo"></i>`;
          }

          // Update status badge
          const statusBadge = document.getElementById('viewCompanyStatus');
          if (company.status === 'active') {
            statusBadge.className = 'badge bg-success';
            statusBadge.innerHTML = '<i class="ti tabler-check me-1"></i>Active';
          } else {
            statusBadge.className = 'badge bg-secondary';
            statusBadge.innerHTML = '<i class="ti tabler-x me-1"></i>Inactive';
          }

          // Update action buttons with company data
          document.getElementById('viewModalEditBtn').setAttribute('data-id', company.id);
          document.getElementById('viewModalToggleBtn').setAttribute('data-id', company.id);
          document.getElementById('viewModalToggleBtn').setAttribute('data-status', company.status);
          document.getElementById('viewModalDuplicateBtn').setAttribute('data-id', company.id);
          document.getElementById('viewModalDeleteBtn').setAttribute('data-id', company.id);

          // Update toggle button text and icon
          const toggleBtn = document.getElementById('viewModalToggleBtn');
          if (company.status === 'active') {
            toggleBtn.className = 'btn btn-outline-warning';
            toggleBtn.innerHTML = '<i class="ti tabler-toggle-right me-1"></i><span>Deactivate</span>';
          } else {
            toggleBtn.className = 'btn btn-outline-success';
            toggleBtn.innerHTML = '<i class="ti tabler-toggle-left me-1"></i><span>Activate</span>';
          }

          // Show the modal
          const viewModal = new bootstrap.Modal(document.getElementById('viewCompanyModal'));
          viewModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load company details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading company details', 'error');
      });
  }

  // Edit Company Function
  function editCompany(companyId) {
    fetch(`/companies/${companyId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const company = data.data;

          document.getElementById('editCompanyId').value = company.id;
          document.getElementById('editCompanyTitle').value = company.title;
          document.getElementById('editCompanyShortName').value = company.short_name;

          const editModal = new bootstrap.Modal(document.getElementById('editCompanyModal'));
          editModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load company details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading company details', 'error');
      });
  }

  // Toggle Company Status Function
  function toggleCompanyStatus(companyId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const action = newStatus === 'active' ? 'activate' : 'deactivate';

    fetch(`/companies/${companyId}/toggle`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        dt_companies.ajax.reload();
        showToast('Success', data.message || `Company ${action}d successfully!`, 'success');
      } else {
        showToast('Error', data.message || `Failed to ${action} company`, 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showToast('Error', `An error occurred while ${action}ing the company`, 'error');
    });
  }

  // Duplicate Company Function
  function duplicateCompany(companyId) {
    fetch(`/companies/${companyId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const company = data.data;

          // Pre-fill the add form with company data
          document.getElementById('companyTitle').value = company.title + ' (Copy)';
          document.getElementById('companyShortName').value = company.short_name + '-C';

          // Show the add modal
          const addModal = new bootstrap.Modal(document.getElementById('addCompanyModal'));
          addModal.show();

          showToast('Info', 'Company data loaded for duplication. Please review and save.', 'info');
        } else {
          showToast('Error', data.message || 'Failed to load company for duplication', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading company for duplication', 'error');
      });
  }

  // Delete Company Function
  function deleteCompany(companyId) {
    if (confirm('Are you sure you want to delete this company? This action cannot be undone.')) {
      fetch(`/companies/${companyId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          dt_companies.ajax.reload();
          showToast('Success', data.message || 'Company deleted successfully!', 'success');
        } else {
          showToast('Error', data.message || 'Failed to delete company', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while deleting the company', 'error');
      });
    }
  }

  // Display validation errors function
  function displayValidationErrors(errors) {
    const fieldLabels = {
      title: 'Company Title',
      short_name: 'Short Name',
      address: 'Address',
      phone: 'Phone',
      email: 'Email',
      contact_person: 'Contact Person',
      ntn: 'NTN',
      logo: 'Logo'
    };

    let errorMessage = '';
    Object.keys(errors).forEach((key, index) => {
      const fieldLabel = fieldLabels[key] || key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
      const fieldErrors = errors[key];

      if (index > 0) errorMessage += '\n';
      errorMessage += `${fieldLabel}: ${fieldErrors.join(', ')}`;
    });

    // Use base toast component with proper z-index
    if (typeof window.showToast === 'function') {
      window.showToast('Validation Error', errorMessage, 'error');
    } else {
      // Fallback to alert if toast not available
      alert('Validation Error:\n' + errorMessage);
    }
  }

  // Toast notification function
  function showToast(title, message, type = 'info') {
    // Always use the base toast component
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

img.rounded-circle {
  object-fit: cover;
}
</style>
@endsection
