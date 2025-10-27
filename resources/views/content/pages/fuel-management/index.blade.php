@extends('layouts/layoutMaster')

@section('title', 'Fuel Purchase')

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
            <th>Vendor</th>
            <th>Fuel type</th>
            <th>Tank</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

  @can('fuel.create')
    <!-- Add Fuel Modal -->
    <div class="modal fade" id="addFuelModal" tabindex="-1" aria-labelledby="addFuelModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addFuelModalLabel">Add Fuel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addFuelForm" enctype="multipart/form-data">
            <div class="modal-body row">
              <div class="mb-3 col-md-6 col-12">
                <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                <select class="form-select" id="vendor_id" name="vendor_id" required>
                  <option value="">Select Vendor</option>
                  @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->title }}</option>
                  @endforeach
                </select>
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
                <label for="tank_id" class="form-label">Tank <span class="text-danger">*</span></label>
                <select class="form-select" id="tank_id" name="tank_id" required>
                  <option value="">Select Tank</option>
                  @foreach($tanks as $tank)
                    <option value="{{ $tank->id }}">{{ $tank->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="qty" class="form-label">Qty <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="qty" name="qty" required>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="rate" class="form-label">Rate <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="rate" name="rate" required>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" readonly>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="delivery_date" class="form-label">Delivery Date</label>
                <input type="text" class="form-control" id="delivery_date" name="delivery_date" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}">
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="freight_charges" class="form-label">Freight Charges</label>
                <input type="number" class="form-control" id="freight_charges" name="freight_charges">
              </div>
              <div class="mb-3 col-md-12 col-12">
                <label for="slip_image" class="form-label">Receipt/Slip Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="slip_image" name="slip_image" accept="image/*" required>
                <div class="form-text">Upload a clear image of the receipt/slip (JPEG, PNG, JPG, GIF - Max 2MB)</div>
              </div>
              <div class="mb-3 col-md-12 col-12">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add Fuel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endcan

  @can('fuel.edit')
  <!-- edit Fuel Modal -->
    <div class="modal fade" id="editFuelModal" tabindex="-1" aria-labelledby="editFuelModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFuelModalLabel">Edit Fuel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editFuelForm" enctype="multipart/form-data">
            <div class="modal-body row">
              <input type="hidden" name="id" id="edit_fuel_id">
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                <select class="form-select" id="edit_vendor_id" name="vendor_id" required>
                  <option value="">Select Vendor</option>
                  @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->title }}</option>
                  @endforeach
                </select>
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
                <label for="edit_tank_id" class="form-label">Tank <span class="text-danger">*</span></label>
                <select class="form-select" id="edit_tank_id" name="tank_id" required>
                  <option value="">Select Tank</option>
                  @foreach($tanks as $tank)
                    <option value="{{ $tank->id }}">{{ $tank->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_qty" class="form-label">Qty <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="edit_qty" name="qty" required>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_rate" class="form-label">Rate <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="edit_rate" name="rate" required>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_amount" class="form-label">Amount</label>
                <input type="number" class="form-control" id="edit_amount" name="amount" readonly>
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_delivery_date" class="form-label">Delivery Date</label>
                <input type="text" class="form-control" id="edit_delivery_date" name="delivery_date" placeholder="MM-DD-YYYY">
              </div>
              <div class="mb-3 col-md-6 col-12">
                <label for="edit_freight_charges" class="form-label">Freight Charges</label>
                <input type="number" class="form-control" id="edit_freight_charges" name="freight_charges">
              </div>
              <div class="mb-3 col-md-12 col-12">
                <label for="edit_slip_image" class="form-label">Receipt/Slip Image</label>
                <input type="file" class="form-control" id="edit_slip_image" name="slip_image" accept="image/*">
                <div class="form-text">Upload a new image to replace the existing one (JPEG, PNG, JPG, GIF - Max 2MB)</div>
                <div id="current_slip_image_container" class="mt-2" style="display: none;">
                  <label class="form-label">Current Image:</label>
                  <div>
                    <img id="current_slip_image" src="" alt="Current slip" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;" />
                  </div>
                </div>
              </div>
              <div class="mb-3 col-md-12 col-12">
                <label for="edit_remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="edit_remarks" name="remarks" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Update Fuel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <!-- / edit Fuel Modal -->
  @endcan
  @can('fuel.view')
  <!-- view Fuel Modal -->
    <div class="modal fade" id="viewFuelModal" tabindex="-1" aria-labelledby="viewFuelModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewFuelModalLabel">View Fuel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="viewFuelDetails"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  <!-- / view Fuel Modal -->
  @endcan
  @can('fuel.delete')
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
  <!-- / Delete Confirmation Modal -->
  @endcan

<!-- Toast Container -->
<x-toast-container />
<!-- / Toast Container -->
<script>
  // Auto-calculate amount
  document.getElementById('qty')?.addEventListener('input', calcAmount);
  document.getElementById('rate')?.addEventListener('input', calcAmount);
  function calcAmount() {
    const qty = parseFloat(document.getElementById('qty').value) || 0;
    const rate = parseFloat(document.getElementById('rate').value) || 0;
    document.getElementById('amount').value = (qty * rate).toFixed(2);
  }
</script>
<script>
  'use strict';
  document.addEventListener('DOMContentLoaded', function () {
    // FlatPickr Initialization & Validation
    (function () {
        const dateFields = document.querySelectorAll('[name="delivery_date"]');

        if (dateFields.length) {
            flatpickr(dateFields, {
                enableTime: false,
                monthSelectorType: 'static',
                static: true,
                dateFormat: 'Y-m-d',
                onChange: function () {
                    // Optional validation logic
                }
            });
        }
    })();


    // DataTable Initialization
    const dt_basic_table = document.querySelector('.datatables-basic');
    let dt_basic;
    if (dt_basic_table) {
      let tableTitle = document.createElement('h5');
      tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
      tableTitle.innerHTML = 'Fuel Purchase';

      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/fuel-management',
          dataSrc: function(json) {
            return json.data || json;
          }
        },
        columns: [
          { data: 'id' }, // For control column
          { data: 'id', title: 'S.no' },
          { data: 'vendor.title', title: 'Vendor' },
          { data: 'fuel_type.title', title: 'Fuel type' },
          { data: 'tank.name', title: 'Tank' },
          { data: 'qty', title: 'Qty' },
          { data: 'rate', title: 'Rate' },
          { data: 'amount', title: 'Amount' },
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
            render: (data, type, full, meta) => meta.row + 1 // S.no
          },
          {
            targets: 2,
            render: (data, type, full) => full.vendor?.title ?? '-' // Vendor name
          },
          {
            targets: 3,
            render: (data, type, full) => full.fuel_type?.label ?? '-' // Fuel type
          },
          {
            targets: 4,
            render: (data, type, full) => full.tank?.name ?? '-' // Tank
          },
          {
            targets: 5,
            render: (data, type, full) => full.qty ?? '-' // Qty
          },
          {
            targets: 6,
            render: (data, type, full) => full.rate ?? '-' // Rate
          },
          {
            targets: 7,
            render: (data, type, full) => full.amount ?? '0.00' // Amount
          },
          {
            targets: 8,
            orderable: false,
            searchable: false,
            render: (data, type, full) => (
              '<div class="d-flex gap-1">' +
                @can('fuel.view')`<button class="btn btn-icon btn-sm btn-outline-secondary waves-effect item-view" data-id="${full.id}"><i class="icon-base ti tabler-eye"></i></button>` + @endcan
                @can('fuel.edit')`<button class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit" data-id="${full.id}"><i class="icon-base ti tabler-pencil"></i></button>` + @endcan
                @can('fuel.delete')`<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="${full.id}"><i class="icon-base ti tabler-trash"></i></button>` + @endcan
              '</div>'
            )
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
          @can('fuel.create')
          top2End: {
            features: [
              {
                buttons: [
                  {
                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add Fuel</span></span>',
                    className: 'create-new btn btn-primary bg-primary',
                    attr: {
                      'data-bs-toggle': 'modal',
                      'data-bs-target': '#addFuelModal'
                    }
                  }
                ]
              }
            ]
          },
          @endcan
          topStart: {
            rowClass: 'row mx-0 px-3 my-0 justify-content-between border-bottom',
            features: [
              {
                pageLength: {
                  menu: [10, 25, 50, 100],
                  text: 'Show_MENU_entries'
                }
              }
            ]
          },
          topEnd: {
            search: {
              placeholder: ''
            }
          },
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

      @can('fuel.delete')
        // Delete row logic
        let deleteRow = null;
        let deleteId = null;
        document.addEventListener('click', function (e) {
          const deleteBtn = e.target.closest('.delete-record');
          if (deleteBtn) {
            deleteRow = deleteBtn.closest('tr');
            deleteId = deleteBtn.getAttribute('data-id');
            if (!deleteId) return alert('Fuel record ID not found.');
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
          }
        });

        document.getElementById('confirmDeleteBtn')?.addEventListener('click', async function () {
          if (!deleteRow || !deleteId) return;
          const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
          modal.hide();
          try {
            const response = await fetch(`/fuel-management/${deleteId}`, {
              method: 'DELETE',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            });
            if (response.ok) {
              dt_basic.row(deleteRow).remove().draw();
              showToast('Success', 'Fuel record deleted successfully!', 'success');
            } else {
              showToast('Error', 'Failed to delete the fuel record.', 'error');
            }
          } catch (err) {
            showToast('Error', 'An error occurred while deleting.', 'error');
          }
          deleteRow = null;
          deleteId = null;
        });
      @endcan

      @can('fuel.create')
      // AJAX store for add fuel form
      const addFuelForm = document.getElementById('addFuelForm');
      if (addFuelForm) {
        addFuelForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(addFuelForm);

          // Make sure we're actually sending multipart form data for file upload
          addFuelForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

          fetch('/fuel-management', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              // Don't set Content-Type here, the browser will set it correctly with the boundary for multipart/form-data
            },
            body: formData
          })
          .then(response => response.json().then(data => ({ status: response.status, body: data })))
          .then(({ status, body }) => {
            if (status === 200 || status === 201) {
              showToast('Success',  'Fuel record added successfully!', 'success');
              const modal = bootstrap.Modal.getInstance(document.getElementById('addFuelModal'));
              modal.hide();
              addFuelForm.reset();
              dt_basic.ajax.reload();
            } else if (status === 422 && body.errors) {
              Object.keys(body.errors).forEach(field => {
                const input = addFuelForm.querySelector(`[name="${field}"]`);
                if (input) input.classList.add('is-invalid');
              });
              const errorMessages = Object.values(body.errors).flat().join('<br>');
              showToast('Error', 'Please fix the errors in the form.' + errorMessages, 'error');
            } else {
              showToast('Error', 'An error occurred while adding fuel.', 'error');
            }
          })
          .catch(() => {
            showToast('Error', 'Network error. Please try again.', 'error');
          });
        });
      }
      @endcan

      @can('fuel.edit')
      // Event delegation for Edit Fuel button clicks
      document.addEventListener('click', function (e) {
        const button = e.target.closest('.item-edit');
        if (!button) return;

          const fuelId = button.getAttribute('data-id');

          fetch(`/fuel-management/${fuelId}`)
              .then(response => response.json())
              .then(result => {
              const fuel = result.data;
              if (fuel) {
                const editFuelForm = document.getElementById('editFuelForm');
                if (editFuelForm) {
                  editFuelForm.dataset.id = fuelId;

                  // Set form values
                  editFuelForm.querySelector('[name="vendor_id"]').value = fuel.vendor_id || '';
                  editFuelForm.querySelector('[name="fuel_type_id"]').value = fuel.fuel_type_id || '';
                  editFuelForm.querySelector('[name="tank_id"]').value = fuel.tank_id || '';
                  editFuelForm.querySelector('[name="qty"]').value = fuel.qty || '';
                  editFuelForm.querySelector('[name="rate"]').value = fuel.rate || '';
                  editFuelForm.querySelector('[name="amount"]').value = fuel.amount || '';
                  editFuelForm.querySelector('[name="delivery_date"]').value = fuel.delivery_date || '';
                  editFuelForm.querySelector('[name="freight_charges"]').value = fuel.freight_charges || '';
                  editFuelForm.querySelector('[name="remarks"]').value = fuel.remarks || '';

                  // Handle slip image preview
                  const imageContainer = document.getElementById('current_slip_image_container');
                  const imageElement = document.getElementById('current_slip_image');

                  if (fuel.slip_image) {
                    imageElement.src = `/storage/${fuel.slip_image}`;
                    imageContainer.style.display = 'block';
                  } else {
                    imageContainer.style.display = 'none';
                  }

                  const modal = new bootstrap.Modal(document.getElementById('editFuelModal'));
                  modal.show();
                }
              }
        });
      });

      // AJAX update for Edit Fuel form
      const editFuelForm = document.getElementById('editFuelForm');
      if (editFuelForm) {
          editFuelForm.addEventListener('submit', function (e) {
              e.preventDefault();

              const formData = new FormData(editFuelForm);
              editFuelForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

              fetch(`/fuel-management/${editFuelForm.dataset.id}`, {
                  method: 'POST',
                  headers: {
                      'Accept': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                      // Don't set Content-Type for multipart/form-data
                  },
                  body: formData
              })
              .then(response => response.json().then(data => ({ status: response.status, body: data })))
              .then(({ status, body }) => {
                  if (status === 200) {
                      showToast('Success', 'Fuel record updated successfully!', 'success');
                      const modal = bootstrap.Modal.getInstance(document.getElementById('editFuelModal'));
                      modal.hide();
                      editFuelForm.reset();
                      if (typeof dt_basic !== 'undefined') {
                          dt_basic.ajax.reload();
                      }
                  } else if (status === 422 && body.errors) {
                      Object.keys(body.errors).forEach(field => {
                          const input = editFuelForm.querySelector(`[name="${field}"]`);
                          if (input) input.classList.add('is-invalid');
                      });
                      const errorMessages = Object.values(body.errors).flat().join('<br>');
                      showToast('Error', 'Please fix the errors in the form.<br>' + errorMessages, 'error');
                  } else {
                      showToast('Error', 'An error occurred while updating fuel.', 'error');
                  }
              })
              .catch(() => {
                  showToast('Error', 'Network error. Please try again.', 'error');
              });
          });
      }
      @endcan

      @can('fuel.view')
          // Open View Modal
      $(document).on('click', '.item-view', function () {
          let id = $(this).data('id');
          viewFuel(id);
      });

      // Fetch & Display Fuel Record
      function viewFuel(id) {
          fetch(`/fuel-management/${id}`)
              .then(res => res.json())
              .then(response => {
                  let fuel = response.data;

                  // Create the slip image HTML
                  let slipImageHtml = '';
                  if (fuel.slip_image) {
                      slipImageHtml = `
                          <tr>
                              <th>Receipt/Slip Image</th>
                              <td>
                                  <div class="d-flex flex-column">
                                      <img src="/storage/${fuel.slip_image}" alt="Receipt" class="mb-2" style="max-width: 300px; max-height: 300px;">
                                      <button type="button" class="btn btn-sm btn-primary" onclick="downloadSlipImage(${fuel.id})">
                                          <i class="ti tabler-download me-1"></i> Download Receipt
                                      </button>
                                  </div>
                              </td>
                          </tr>
                      `;
                  } else {
                      slipImageHtml = `<tr><th>Receipt/Slip Image</th><td>No receipt image available</td></tr>`;
                  }

                  let detailsHtml = `
                      <table class="table table-bordered table-striped">
                          <tbody>
                              <tr><th>ID</th><td>${fuel.id}</td></tr>
                              <tr><th>Vendor</th><td>${fuel.vendor?.title ?? 'N/A'} (${fuel.vendor?.short_name ?? ''})</td></tr>
                              <tr><th>Fuel Type</th><td>${fuel.fuel_type?.label ?? 'N/A'}</td></tr>
                              <tr><th>Tank</th><td>${fuel.tank?.name ?? 'N/A'} (${fuel.tank?.location ?? ''})</td></tr>
                              <tr><th>Quantity</th><td>${fuel.qty}</td></tr>
                              <tr><th>Rate</th><td>${fuel.rate}</td></tr>
                              <tr><th>Amount</th><td>${fuel.amount}</td></tr>
                              <tr><th>Delivery Date</th><td>${formatDate(fuel.delivery_date)}</td></tr>
                              <tr><th>Freight Charges</th><td>${fuel.freight_charges}</td></tr>
                              ${slipImageHtml}
                              <tr><th>Remarks</th><td>${fuel.remarks || 'No remarks'}</td></tr>
                          </tbody>
                      </table>
                  `;

                  document.getElementById('viewFuelDetails').innerHTML = detailsHtml;

                  let viewModal = new bootstrap.Modal(document.getElementById('viewFuelModal'));
                  viewModal.show();
              })
              .catch(err => {
                  console.error("Error fetching fuel record:", err);
              });
      }

      // Function to download slip image
      function downloadSlipImage(id) {
          const downloadFrame = document.getElementById('download-frame') || document.createElement('iframe');
          downloadFrame.id = 'download-frame';
          downloadFrame.style.display = 'none';
          downloadFrame.src = `/fuel-management/${id}/download-slip`;
          
          if (!document.getElementById('download-frame')) {
              document.body.appendChild(downloadFrame);
          }
          
          // Show a success message
          setTimeout(() => {
              showToast('Success', 'Download started.', 'success');
          }, 500);
      }

      // helper
      function formatDate(dateStr) {
          if (!dateStr) return 'N/A';
          let d = new Date(dateStr);
          return d.toLocaleDateString('en-GB', {
              day: '2-digit',
              month: 'short',
              year: 'numeric'
          });
      }
      @endcan
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
