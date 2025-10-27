@extends('layouts/layoutMaster')

@section('title', 'Tank Management')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js',
  'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js',
  'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js',
  'resources/assets/vendor/libs/pickr/pickr.js'
])
@endsection

@section('page-script')
@vite([
  'resources/assets/js/forms-pickers.js',
])
@endsection
@section('content')
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table">
        <thead>
          <tr>
            <th></th>

            <th>S.no</th>
            <th>Tank Name</th>
            <th>Location</th>
            <th>Volume</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  @can('fuel-tank.create')
    <!-- Add Tank Modal -->
    <div class="modal fade" id="addTankModal" tabindex="-1" aria-labelledby="addTankModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addTankModalLabel">Add Tank</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addTankForm">
            <div class="modal-body row">
              <div class="mb-3 col-md-6 col-12">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Name"/>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="fuel_type_id" class="form-label">Fuel Type <span class="text-danger">*</span></label>
                <select class="form-select" id="fuel_type_id" name="fuel_type_id" required>
                  <option value="">Select Fuel Type</option>
                  @foreach($fuelTypes as $fuelType)
                    <option value="{{ $fuelType->id }}">{{ $fuelType->label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="supervisor_id" class="form-label">Supervisor <span class="text-danger">*</span></label>
                <select class="form-select" id="supervisor_id" name="supervisor_id" required>
                  <option value="">Select Supervisor</option>
                  @foreach($fuelSupervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="location" class="form-label">Tank Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="location" name="location" required>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="capacity_volume" class="form-label">Capacity Volume <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="capacity_volume" name="capacity_volume" required>
              </div>
              <div class="mb-3 col-md-12 col-12">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add Tank</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endcan


  @can('fuel-tank.edit')
    <!-- Edit Tank Modal -->
    <div class="modal fade" id="editTankModal" tabindex="-1" aria-labelledby="editTankModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editTankModalLabel">Edit Tank</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editTankForm">
            <input type="hidden" id="edit_tank_id" name="id">
            <div class="modal-body row">
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="edit_name" placeholder="Name"/>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_fuel_type_id" class="form-label">Fuel Type <span class="text-danger">*</span></label>
                <select class="form-select" id="edit_fuel_type_id" name="fuel_type_id" required>
                  <option value="">Select Fuel Type</option>
                  @foreach($fuelTypes as $fuelType)
                    <option value="{{ $fuelType->id }}">{{ $fuelType->label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_supervisor_id" class="form-label">Supervisor <span class="text-danger">*</span></label>
                <select class="form-select" id="edit_supervisor_id" name="supervisor_id" required>
                  <option value="">Select Supervisor</option>
                  @foreach($fuelSupervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_location" class="form-label">Tank Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_location" name="location" required>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_capacity_volume" class="form-label">Capacity Volume <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="edit_capacity_volume" name="capacity_volume" required>
              </div>
              <div class="mb-3 col-md-12 col-12">
                <label for="edit_remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="edit_remarks" name="remarks" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Update Tank</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endcan

    <!-- View Tank Modal -->
    <div class="modal fade" id="viewTankModal" tabindex="-1" aria-labelledby="viewTankModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewTankModalLabel">Tank Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body ">

              <div class=" row">
                <div class="mb-3 col-md-6 col-12">
                  <label class="form-label fw-bold">Name:</label>
                  <div id="view_name"></div>
                </div>
                <div class="mb-3 col-md-6 col-12">
                  <label class="form-label fw-bold">Fuel Type:</label>
                  <div id="view_fuel_type"></div>
                </div>
                <div class="mb-3 col-md-6 col-12">
                  <label class="form-label fw-bold">Supervisor:</label>
                  <div id="view_supervisor"></div>
                </div>
                <div class="mb-3 col-md-6 col-12">
                  <label class="form-label fw-bold">Location:</label>
                  <div id="view_location"></div>
                </div>
                <div class="mb-3 col-md-6 col-12">
                  <label class="form-label fw-bold">Capacity Volume:</label>
                  <div id="view_capacity_volume"></div>
                </div>
                <div class="mb-3 col-md-12 col-12">
                  <label class="form-label fw-bold">Remarks:</label>
                  <div id="view_remarks"></div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>




  @can('fuel-tank.delete')
  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this record?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>
  @endcan
<x-toast-container />

<script>
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  initFlatpickr();
  initDataTable();
  initDeleteLogic();
  initAddTankForm();
});

/* ------------------------------
 *  Flatpickr Initialization
 * ------------------------------ */
const initFlatpickr = () => {
  const dateInput = document.querySelector('[name="delivery_date"]');
  if (!dateInput) return;

  dateInput.flatpickr({
    enableTime: false,
    monthSelectorType: 'static',
    static: true,
    dateFormat: 'm/d/Y'
  });
};

/* ------------------------------
 *  DataTable Initialization
 * ------------------------------ */
let dt_basic;

const initDataTable = () => {
  const tableEl = document.querySelector('.datatables-basic');
  if (!tableEl) return;

  const tableTitle = document.createElement('h5');
  tableTitle.classList.add(
    'card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6'
  );
  tableTitle.textContent = 'Tank Management';

  dt_basic = new DataTable(tableEl, {
    ajax: {
      url: '/fuel-tanks',
      dataSrc: json => json.data || json
    },
    columns: [
      { data: 'id' },

      { data: 'id', title: 'S.no' },
      { data: 'name', title: 'Tank Name' },
      { data: 'fuel_type.label', title: 'Fuel type' },
      { data: 'location', title: 'Tank Location' },
      { data: 'capacity_volume', title: 'Capacity Volume' },
      // { data: 'supervisor.name', title: 'Supervisor' },
      { data: 'status', title: 'Status' },
      { data: null, title: 'Action' }
    ],
    columnDefs: [
      {
        targets: 0,
        className: 'control',
        orderable: false,
        searchable: false,
        responsivePriority: 2,
        render: () => ''
      },
      {
        targets: 1,
        searchable: false,
        render: (data, type, full, meta) => meta.row + 1
      },
      { targets: 2, render: (data, type, full) => full.name ?? '-' },
      { targets: 3, render: (data, type, full) => full.fuel_type?.label ?? '-' },
      { targets: 4, render: (data, type, full) => full.location ?? '-' },
      { targets: 5, render: (data, type, full) => full.capacity_volume ?? '-' },
      {
        targets: 6,
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
        targets: 7,
        orderable: false,
        searchable: false,
        render: (data, type, full) => {
          return `
            <div class="d-flex gap-1 align-items-center">
              @can('fuel-tank.view')
              <a href="/fuel-tanks/${full.id}"
                      class="btn btn-sm btn-icon btn-outline-info waves-effect"
                      title="View">
                <i class="icon-base ti tabler-eye"></i>
              </a>
              @endcan

              @can('fuel-tank.edit')
              <button type="button"
                      class="btn btn-sm btn-icon btn-outline-secondary waves-effect item-edit"
                      title="Edit">
                <i class="icon-base ti tabler-pencil"></i>
              </button>
              @endcan
              @can('fuel-tank.edit')
              <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                  <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                </button>
              @endcan
              @can('fuel-tank.delete')
              <button type="button"
                      class="btn btn-sm btn-icon btn-outline-danger waves-effect delete-record"
                      data-id="${full.id}"
                      title="Delete">
                <i class="icon-base ti tabler-trash"></i>
              </button>
              @endcan

            </div>
          `;
        }
      }
    ],
    select: {
      style: 'multi',
      selector: 'td:nth-child(2)'
    },
    order: [[2, 'asc']],
    layout: {
      top2Start: {
        rowClass: 'row card-header flex-column flex-md-row border-bottom mx-0 px-3',
        features: [tableTitle]
      },
      @can('fuel-tank.create')
      top2End: {
        features: [{
          buttons: [{
            text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add Tank</span></span>',
            className: 'btn btn-primary create-new bg-primary',
            attr: {
              'data-bs-toggle': 'modal',
              'data-bs-target': '#addTankModal'
            }
          }]
        }]
      },
      @endcan
      topStart: {
        rowClass: 'row mx-0 px-3 my-0 justify-content-between border-bottom',
        features: [{
          pageLength: {
            menu: [10, 25, 50, 100],
            text: 'Show_MENU_entries'
          }
        }]
      },
      topEnd: { search: { placeholder: '' } },
      bottomStart: {
        rowClass: 'row mx-3 justify-content-between',
        features: ['info']
      },
      bottomEnd: 'paging'
    },
    responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function (row) {
                const data = row.data();
                return 'Details of ' + data['job'];
              }
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
              const data = columns
                .map(function (col) {
                  return col.title !== ''
                    ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        <td>${col.title}:</td>
                        <td>${col.data}</td>
                      </tr>`
                    : '';
                })
                .join('');
              if (data) {
                const div = document.createElement('div');
                div.classList.add('table-responsive');
                const table = document.createElement('table');
                div.appendChild(table);
                table.classList.add('table');
                table.classList.add('datatables-basic');
                const tbody = document.createElement('tbody');
                tbody.innerHTML = data;
                table.appendChild(tbody);
                return div;
              }
              return false;
            }
          }
        },
  });
};

/* ------------------------------
 *  Delete Logic
 * ------------------------------ */
let deleteRow = null;
let deleteId = null;

const initDeleteLogic = () => {
  document.addEventListener('click', e => {
    const btn = e.target.closest('.delete-record');
    if (!btn) return;

    deleteRow = btn.closest('tr');
    deleteId = btn.getAttribute('data-id');
    if (!deleteId) {
      alert('Fuel record ID not found.');
      return;
    }
    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
  });

  document.getElementById('confirmDeleteBtn')?.addEventListener('click', async () => {
    if (!deleteRow || !deleteId) return;

    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
    try {
      const res = await fetch(`/fuel-tanks/${deleteId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      });

      if (res.ok) {
        dt_basic.row(deleteRow).remove().draw();
        showToast('Success', 'Fuel tank record deleted successfully!', 'success');
      } else {
        showToast('Error', 'Failed to delete the fuel tank record.', 'error');
      }
    } catch {
      showToast('Error', 'An error occurred while deleting.', 'error');
    }

    deleteRow = null;
    deleteId = null;
  });
};

@can('fuel-tank.create')
  /* ------------------------------
  *  Add Tank Form
  * ------------------------------ */
  const initAddTankForm = () => {
    const form = document.getElementById('addTankForm');
    if (!form) return;

    form.addEventListener('submit', e => {
      e.preventDefault();

      const formData = new FormData(form);
      form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

      fetch('/fuel-tanks', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: formData
      })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
          if (status === 200 || status === 201) {
            showToast('Success', 'Fuel tank record added successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('addTankModal')).hide();
            form.reset();
            dt_basic.ajax.reload();
          } else if (status === 422 && body.errors) {
            Object.keys(body.errors).forEach(field => {
              const input = form.querySelector(`[name="${field}"]`);
              if (input) input.classList.add('is-invalid');
            });
            const errors = Object.values(body.errors).flat().join('<br>');
            showToast('Error', 'Please fix the errors in the form.' + errors, 'error');
          } else {
            showToast('Error', 'An error occurred while adding fuel tank.', 'error');
          }
        })
        .catch(() => {
          showToast('Error', 'Network error. Please try again.', 'error');
        });
    });
  };
@endcan

@can('fuel-tank.edit')
  // Edit Tank Modal logic
  document.addEventListener('click', function (e) {
    const editBtn = e.target.closest('.item-edit');
    if (editBtn) {
      const row = editBtn.closest('tr');
      const rowData = dt_basic.row(row).data();

      // Fill modal fields
      document.getElementById('edit_tank_id').value = rowData.id;
      document.getElementById('edit_name').value = rowData.name ?? '';
      document.getElementById('edit_fuel_type_id').value = rowData.fuel_type_id ?? '';
      document.getElementById('edit_supervisor_id').value = rowData.supervisor_id ?? '';
      document.getElementById('edit_location').value = rowData.location ?? '';
      document.getElementById('edit_capacity_volume').value = rowData.capacity_volume ?? '';
      document.getElementById('edit_remarks').value = rowData.remarks ?? '';

      // Show modal
      new bootstrap.Modal(document.getElementById('editTankModal')).show();
    }
  });

  // Handle Edit Tank form submit
  document.getElementById('editTankForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const id = document.getElementById('edit_tank_id').value;
    const form = e.target;
    const formData = new FormData(form);

    fetch(`/fuel-tanks/${id}`, {
      method: 'POST', // Use 'PUT' if your backend expects it, or 'POST' with _method=PUT
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: (() => {
        formData.append('_method', 'PUT');
        return formData;
      })()
    })
      .then(res => res.json().then(data => ({ status: res.status, body: data })))
      .then(({ status, body }) => {
        if (status === 200 || status === 201) {
          showToast('Success', 'Tank updated successfully!', 'success');
          bootstrap.Modal.getInstance(document.getElementById('editTankModal')).hide();
          dt_basic.ajax.reload();
        } else if (status === 422 && body.errors) {
          Object.keys(body.errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) input.classList.add('is-invalid');
          });
          const errors = Object.values(body.errors).flat().join('<br>');
          showToast('Error', 'Please fix the errors in the form.' + errors, 'error');
        } else {
          showToast('Error', 'An error occurred while updating the tank.', 'error');
        }
      })
      .catch(() => {
        showToast('Error', 'Network error. Please try again.', 'error');
      });
  });
@endcan

@can('fuel-tank.view')
  // View Tank Modal logic
  document.addEventListener('click', function (e) {
    const viewBtn = e.target.closest('.item-view');
    if (viewBtn) {
      const row = viewBtn.closest('tr');
      const rowData = dt_basic.row(row).data();

      // Fill modal fields
      document.getElementById('view_name').textContent = rowData.name ?? '-';
      document.getElementById('view_fuel_type').textContent = rowData.fuel_type?.label ?? '-';
      document.getElementById('view_supervisor').textContent = rowData.supervisor?.name ?? '-';
      document.getElementById('view_location').textContent = rowData.location ?? '-';
      document.getElementById('view_capacity_volume').textContent = rowData.capacity_volume ?? '-';
      document.getElementById('view_remarks').textContent = rowData.remarks ?? '-';

      // Show modal
      new bootstrap.Modal(document.getElementById('viewTankModal')).show();
    }
  });

@endcan

// Handle Tank Status Toggle Button
document.addEventListener('click', function(e) {
  const toggleBtn = e.target.closest('.toggle-status');
  if (toggleBtn) {
    const id = toggleBtn.getAttribute('data-id');
    const currentStatus = toggleBtn.getAttribute('data-status');
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    // Confirmation dialog removed

    fetch(`/fuel-tanks/${id}/toggle`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
      showToast('Success', `Tank status updated to ${newStatus}.`, 'success');
      dt_basic.ajax.reload();
    })
    .catch(() => {
      showToast('Error', 'Failed to update status.', 'error');
      dt_basic.ajax.reload();
    });
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
