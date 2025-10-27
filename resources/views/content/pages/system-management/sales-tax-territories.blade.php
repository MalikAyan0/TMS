@extends('layouts/layoutMaster')

@section('title', 'Sales Tax Territories - System Management')

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
<!-- Sales Tax Territories Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Sales Tax <span class="d-none d-sm-inline">Territories</span></h4>
      <p class="card-subtitle mb-0">Manage tax <span class="d-none d-sm-inline">jurisdictions and territorial tax classifications</span></p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTerritoryModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New Territory</span>
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-territories table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Head</th>
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

<!-- Add New Territory Modal -->
<div class="modal fade" id="addTerritoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-map-pin-plus me-2"></i>
          Add New Territory
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addTerritoryForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="territoryHead" class="form-label">Head <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-user-star"></i></span>
                <input type="text" class="form-control" id="territoryHead" name="head"
                       placeholder="e.g., Regional Tax Office" required>
                <div class="invalid-feedback">Please provide a valid territory head.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="territoryTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="territoryTitle" name="title"
                       placeholder="e.g., California State Tax Territory" required>
                <div class="invalid-feedback">Please provide a valid territory title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="territoryShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="territoryShortName" name="short_name"
                       placeholder="e.g., CA-TAX" maxlength="15" required>
                <div class="invalid-feedback">Please provide a valid short name (max 15 characters).</div>
              </div>
              <div class="form-text">Maximum 15 characters for easy identification</div>
            </div>

            <div class="col-12">
              <label for="territoryAddress" class="form-label">Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <textarea class="form-control" id="territoryAddress" name="address"
                          rows="3" placeholder="Enter full address including city, state, and postal code" required></textarea>
                <div class="invalid-feedback">Please provide a valid address.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="addTerritoryBtn">
            <span class="indicator-label">
              <i class="ti tabler-device-floppy me-1"></i>
              Save Territory
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

<!-- Edit Territory Modal -->
<div class="modal fade" id="editTerritoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Territory
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editTerritoryForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editTerritoryId" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editTerritoryHead" class="form-label">Head <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-user-star"></i></span>
                <input type="text" class="form-control" id="editTerritoryHead" name="head"
                       placeholder="e.g., Regional Tax Office" required>
                <div class="invalid-feedback">Please provide a valid territory head.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editTerritoryTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="editTerritoryTitle" name="title"
                       placeholder="e.g., California State Tax Territory" required>
                <div class="invalid-feedback">Please provide a valid territory title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editTerritoryShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editTerritoryShortName" name="short_name"
                       placeholder="e.g., CA-TAX" maxlength="15" required>
                <div class="invalid-feedback">Please provide a valid short name (max 15 characters).</div>
              </div>
            </div>

            <div class="col-12">
              <label for="editTerritoryAddress" class="form-label">Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <textarea class="form-control" id="editTerritoryAddress" name="address"
                          rows="3" placeholder="Enter full address including city, state, and postal code" required></textarea>
                <div class="invalid-feedback">Please provide a valid address.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="editTerritoryBtn">
            <span class="indicator-label">
              <i class="ti tabler-device-floppy me-1"></i>
              Update Territory
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

<!-- View Territory Modal -->
<div class="modal fade" id="viewTerritoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Territory Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Territory Header -->
          <div class="col-12">
            <div class="card bg-label-primary">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-lg me-3">
                    <span class="avatar-initial rounded-circle bg-primary">
                      <i class="ti tabler-map-pin-star ti-lg"></i>
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h5 class="mb-1" id="viewTerritoryTitle">-</h5>
                    <p class="mb-0 text-muted">
                      <i class="ti tabler-tag me-1"></i>
                      <span id="viewTerritoryShortName">-</span>
                    </p>
                  </div>
                  <div class="text-end">
                    <span class="badge" id="viewTerritoryStatus">-</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card ">
              <div class="card-body row">
                <!-- Territory Information -->
                <div class="col-md-6">
                  <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded-circle bg-label-info">
                        <i class="ti tabler-user-star"></i>
                      </span>
                    </div>
                    <div>
                      <h6 class="mb-0">Territory Head</h6>
                      <span class="text-muted" id="viewTerritoryHead">-</span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded-circle bg-label-success">
                        <i class="ti tabler-calendar"></i>
                      </span>
                    </div>
                    <div>
                      <h6 class="mb-0">Created Date</h6>
                      <span class="text-muted" id="viewTerritoryCreated">-</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>


          <!-- Address Information -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-start">
                  <div class="avatar avatar-sm me-3 mt-1">
                    <span class="avatar-initial rounded-circle bg-label-warning">
                      <i class="ti tabler-map-pin"></i>
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="mb-2">Full Address</h6>
                    <p class="mb-0 text-wrap" id="viewTerritoryAddress">-</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" id="viewModalEditBtn">
          <i class="ti tabler-edit me-1"></i>
          <span class="d-none d-sm-inline">Edit Territory</span>
        </button>
        <button type="button" class="btn btn-outline-secondary" id="viewModalToggleBtn">
          <i class="ti tabler-toggle-left me-1"></i>
          <span class="d-none d-sm-inline">Status</span>
        </button>
        <button type="button" class="btn btn-outline-info" id="viewModalDuplicateBtn">
          <i class="ti tabler-copy me-1"></i>
          <span class="d-none d-sm-inline">Duplicate Territory</span>
        </button>
        <button type="button" class="btn btn-outline-danger" id="viewModalDeleteBtn">
          <i class="ti tabler-trash me-1"></i>
          <span class="d-none d-sm-inline">Delete Territory</span>
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

<!-- Page Scripts -->
@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let dt_territories;

    // Initialize DataTable
    const dt_territories_table = document.querySelector('.datatables-territories');

    if (dt_territories_table) {
      dt_territories = new DataTable(dt_territories_table, {
        ajax: {
          url: '/sales-tax-territories',
          type: 'GET',
          dataSrc: function(json) {
            if (json.success) {
              return json.data;
            } else {
              showToast('Error', json.message || 'Failed to load sales tax territories', 'error');
              return [];
            }
          },
          error: function(xhr, error, thrown) {
            console.error('DataTable Error:', error);
            showToast('Error', 'Failed to load sales tax territories. Please try again.', 'error');
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
            data: 'head',
            responsivePriority: 1,
            render: function(data, type, full) {
              return `<div class="d-flex align-items-center">
                        <div class="avatar-wrapper me-2">
                          <div class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                              <i class="ti tabler-user-star"></i>
                            </span>
                          </div>
                        </div>
                        <div class="d-flex flex-column">
                          <span class="fw-medium">${data}</span>
                          <small class="text-muted">Territory Head</small>
                        </div>
                      </div>`;
            }
          },
          {
            data: 'title',
            render: function(data, type, full) {
              return `<div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">Tax Territory</small>
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
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-territory" data-id="${full.id}" title="View Details">
                    <i class="ti tabler-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-territory" data-id="${full.id}" title="Edit">
                    <i class="ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-territory" data-id="${full.id}" title="Delete">
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
              placeholder: 'Search territories...'
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
    document.getElementById('territoryTitle').addEventListener('input', function() {
      const title = this.value;
      const shortName = title
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 15);
      document.getElementById('territoryShortName').value = shortName;
    });

    // Add Territory Form Submission
    const addTerritoryForm = document.getElementById('addTerritoryForm');
    if (addTerritoryForm) {
      addTerritoryForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addTerritoryForm.checkValidity()) {
          const submitBtn = document.getElementById('addTerritoryBtn');
          const indicatorLabel = submitBtn.querySelector('.indicator-label');
          const indicatorProgress = submitBtn.querySelector('.indicator-progress');

          // Show loading state
          submitBtn.disabled = true;
          indicatorLabel.classList.add('d-none');
          indicatorProgress.classList.remove('d-none');

          const formData = new FormData(addTerritoryForm);
          const territoryData = Object.fromEntries(formData.entries());

          fetch('/sales-tax-territories', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(territoryData)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('addTerritoryModal')).hide();
              addTerritoryForm.reset();
              addTerritoryForm.classList.remove('was-validated');

              // Reload DataTable
              dt_territories.ajax.reload();

              showToast('Success', data.message || 'Territory added successfully!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to add territory', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while adding the territory', 'error');
          })
          .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            indicatorLabel.classList.remove('d-none');
            indicatorProgress.classList.add('d-none');
          });
        }

        addTerritoryForm.classList.add('was-validated');
      });
    }

    // Edit Territory Form Submission
    const editTerritoryForm = document.getElementById('editTerritoryForm');
    if (editTerritoryForm) {
      editTerritoryForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editTerritoryForm.checkValidity()) {
          const submitBtn = document.getElementById('editTerritoryBtn');
          const indicatorLabel = submitBtn.querySelector('.indicator-label');
          const indicatorProgress = submitBtn.querySelector('.indicator-progress');

          // Show loading state
          submitBtn.disabled = true;
          indicatorLabel.classList.add('d-none');
          indicatorProgress.classList.remove('d-none');

          const formData = new FormData(editTerritoryForm);
          const territoryData = Object.fromEntries(formData.entries());
          const territoryId = territoryData.id;

          fetch(`/sales-tax-territories/${territoryId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(territoryData)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('editTerritoryModal')).hide();
              editTerritoryForm.reset();
              editTerritoryForm.classList.remove('was-validated');

              // Reload DataTable
              dt_territories.ajax.reload();

              showToast('Success', data.message || 'Territory updated successfully!', 'success');
            } else {
              showToast('Error', data.message || 'Failed to update territory', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating the territory', 'error');
          })
          .finally(() => {
            // Reset loading state
            submitBtn.disabled = false;
            indicatorLabel.classList.remove('d-none');
            indicatorProgress.classList.add('d-none');
          });
        }

        editTerritoryForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-territory')) {
        const territoryId = e.target.closest('.edit-territory').dataset.id;
        editTerritory(territoryId);
      }

      if (e.target.closest('.view-territory')) {
        const territoryId = e.target.closest('.view-territory').dataset.id;
        viewTerritory(territoryId);
      }

      if (e.target.closest('.toggle-status')) {
        const territoryId = e.target.closest('.toggle-status').dataset.id;
        const currentStatus = e.target.closest('.toggle-status').dataset.status;
        toggleTerritoryStatus(territoryId, currentStatus);
      }

      if (e.target.closest('.delete-territory')) {
        const territoryId = e.target.closest('.delete-territory').dataset.id;
        deleteTerritory(territoryId);
      }

      // View modal action buttons
      if (e.target.closest('#viewModalEditBtn')) {
        const territoryId = e.target.closest('#viewModalEditBtn').dataset.id;
        bootstrap.Modal.getInstance(document.getElementById('viewTerritoryModal')).hide();
        editTerritory(territoryId);
      }

      if (e.target.closest('#viewModalToggleBtn')) {
        const territoryId = e.target.closest('#viewModalToggleBtn').dataset.id;
        const currentStatus = e.target.closest('#viewModalToggleBtn').dataset.status;
        bootstrap.Modal.getInstance(document.getElementById('viewTerritoryModal')).hide();
        toggleTerritoryStatus(territoryId, currentStatus);
      }

      if (e.target.closest('#viewModalDuplicateBtn')) {
        const territoryId = e.target.closest('#viewModalDuplicateBtn').dataset.id;
        bootstrap.Modal.getInstance(document.getElementById('viewTerritoryModal')).hide();
        duplicateTerritory(territoryId);
      }

      if (e.target.closest('#viewModalDeleteBtn')) {
        const territoryId = e.target.closest('#viewModalDeleteBtn').dataset.id;
        bootstrap.Modal.getInstance(document.getElementById('viewTerritoryModal')).hide();
        deleteTerritory(territoryId);
      }
    });

    // View Territory Function
    function viewTerritory(territoryId) {
      fetch(`/sales-tax-territories/${territoryId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const territory = data.data;

            // Populate modal with territory data
            document.getElementById('viewTerritoryTitle').textContent = territory.title;
            document.getElementById('viewTerritoryShortName').textContent = territory.short_name;
            document.getElementById('viewTerritoryHead').textContent = territory.head;
            document.getElementById('viewTerritoryAddress').textContent = territory.address;
            document.getElementById('viewTerritoryCreated').textContent = territory.created_at ?
              new Date(territory.created_at).toLocaleDateString() : '-';

            // Update status badge
            const statusBadge = document.getElementById('viewTerritoryStatus');
            if (territory.status === 'active') {
              statusBadge.className = 'badge bg-success';
              statusBadge.innerHTML = '<i class="ti tabler-check me-1"></i>Active';
            } else {
              statusBadge.className = 'badge bg-secondary';
              statusBadge.innerHTML = '<i class="ti tabler-x me-1"></i>Inactive';
            }

            // Update action buttons with territory data
            document.getElementById('viewModalEditBtn').setAttribute('data-id', territory.id);
            document.getElementById('viewModalToggleBtn').setAttribute('data-id', territory.id);
            document.getElementById('viewModalToggleBtn').setAttribute('data-status', territory.status);
            document.getElementById('viewModalDuplicateBtn').setAttribute('data-id', territory.id);
            document.getElementById('viewModalDeleteBtn').setAttribute('data-id', territory.id);

            // Update toggle button text and icon
            const toggleBtn = document.getElementById('viewModalToggleBtn');
            if (territory.status === 'active') {
              toggleBtn.className = 'btn btn-outline-warning';
              toggleBtn.innerHTML = '<i class="ti tabler-toggle-right me-1"></i><span class="d-none d-sm-inline">Deactivate</span>';
            } else {
              toggleBtn.className = 'btn btn-outline-success';
              toggleBtn.innerHTML = '<i class="ti tabler-toggle-left me-1"></i><span class="d-none d-sm-inline">Activate</span>';
            }

            // Show the modal
            const viewModal = new bootstrap.Modal(document.getElementById('viewTerritoryModal'));
            viewModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load territory details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading territory details', 'error');
        });
    }

    // Edit Territory Function
    function editTerritory(territoryId) {
      fetch(`/sales-tax-territories/${territoryId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const territory = data.data;

            document.getElementById('editTerritoryId').value = territory.id;
            document.getElementById('editTerritoryHead').value = territory.head;
            document.getElementById('editTerritoryTitle').value = territory.title;
            document.getElementById('editTerritoryShortName').value = territory.short_name;
            document.getElementById('editTerritoryAddress').value = territory.address;

            const editModal = new bootstrap.Modal(document.getElementById('editTerritoryModal'));
            editModal.show();
          } else {
            showToast('Error', data.message || 'Failed to load territory details', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading territory details', 'error');
        });
    }

    // Toggle Territory Status Function
    function toggleTerritoryStatus(territoryId, currentStatus) {
      const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
      const action = newStatus === 'active' ? 'activate' : 'deactivate';

      fetch(`/sales-tax-territories/${territoryId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          dt_territories.ajax.reload();
          showToast('Success', data.message || `Territory ${action}d successfully!`, 'success');
        } else {
          showToast('Error', data.message || `Failed to ${action} territory`, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', `An error occurred while ${action}ing the territory`, 'error');
      });
    }

    // Duplicate Territory Function
    function duplicateTerritory(territoryId) {
      fetch(`/sales-tax-territories/${territoryId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const territory = data.data;

            // Pre-fill the add form with territory data
            document.getElementById('territoryHead').value = territory.head;
            document.getElementById('territoryTitle').value = territory.title + ' (Copy)';
            document.getElementById('territoryShortName').value = territory.short_name + '-C';
            document.getElementById('territoryAddress').value = territory.address;

            // Show the add modal
            const addModal = new bootstrap.Modal(document.getElementById('addTerritoryModal'));
            addModal.show();

            showToast('Info', 'Territory data loaded for duplication. Please review and save.', 'info');
          } else {
            showToast('Error', data.message || 'Failed to load territory for duplication', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while loading territory for duplication', 'error');
        });
    }

    // Delete Territory Function
    function deleteTerritory(territoryId) {
      if (confirm('Are you sure you want to delete this territory? This action cannot be undone.')) {
        fetch(`/sales-tax-territories/${territoryId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            dt_territories.ajax.reload();
            showToast('Success', data.message || 'Territory deleted successfully!', 'success');
          } else {
            showToast('Error', data.message || 'Failed to delete territory', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while deleting the territory', 'error');
        });
      }
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
