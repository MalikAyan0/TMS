@extends('layouts/layoutMaster')

@section('title', 'Fleet Management')

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
            <th>Tank</th>
            <th>Fleet</th>
            <th>Operation</th>
            <th>Driver</th>
            <th>Fill Date</th>
            <th>Qty</th>
            <th>Odometer Reading	</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

    <!-- Add Fleet Modal -->
  <div class="modal fade" id="addIssuanceModal" tabindex="-1" aria-labelledby="addIssuanceModal" aria-hidden="true">
    <form id="addFleetForm" action="{{ route('issuances.index') }}" method="POST">
      @csrf
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="addIssuanceModalLabel">Add New Issuance</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="text-center mb-4">
              <p class="text-body-secondary">Set issuance details</p>
            </div>
            <div class="row g-3">
              <!-- Add fleet form -->
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="tank_id">Tank</label>
                <select id="tank_id" name="tank_id" class="form-select">
                  <option value="">Select Tank</option>
                  @foreach ($tanks as $tank)
                    <option value="{{ $tank->id }}" >
                      {{ $tank->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="fleet_id">Fleet</label>
                <select id="fleet_id" name="fleet_id" class="form-select">
                  <option value="">Select Fleet</option>
                  @foreach ($fleets as $fleet)
                    <option value="{{ $fleet->id }}" >
                      {{ $fleet->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="operation_id">Operation</label>
                <select id="operation_id" name="operation_id" class="form-select">
                  <option value="">Select Operation</option>
                  @foreach ($operations as $operation)
                    <option value="{{ $operation->id }}" >
                      {{ $operation->label }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="driver">Driver</label>
                <input type="text" id="driver" name="driver" class="form-control" placeholder="Enter Driver Name">
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="fill_date">Fill Date</label>
                <input type="text" class="form-control" id="fill_date" name="fill_date" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}">
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="qty">Quantity</label>
                <input type="number" id="qty" name="qty" class="form-control" placeholder="Enter Quantity">
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="odometer_reading">Odometer Reading</label>
                <input type="number" id="odometer_reading" name="odometer_reading" class="form-control" placeholder="Enter Odometer Reading">
              </div>
              <div class="col-12 col-md-12 form-control-validation mb-3">
                <label class="form-label" for="remarks">Remarks</label>
                <textarea name="remarks" id="remarks" cols="30" rows="3" class="form-control" placeholder="Enter Remarks"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Fleet</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /Add Fleet Type Modal -->

  <!-- Edit Fleet Modal -->
  <div class="modal fade" id="editIssuanceModal" tabindex="-1" aria-labelledby="editIssuanceModalLabel" aria-hidden="true">
    <form id="editIssuanceForm" action="{{ route('issuances.update', ':id') }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="editIssuanceModalLabel">Edit Issuance</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="text-center mb-4">
              <p class="text-body-secondary">Update issuance details</p>
            </div>
            <div class="row g-3">
              <!-- Edit issuance form -->
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_tank_id">Tank</label>
                <select id="edit_tank_id" name="tank_id" class="form-select">
                  <option value="">Select Tank</option>
                  @foreach ($tanks as $tank)
                    <option value="{{ $tank->id }}">{{ $tank->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_fleet_id">Fleet</label>
                <select id="edit_fleet_id" name="fleet_id" class="form-select">
                  <option value="">Select Fleet</option>
                  @foreach ($fleets as $fleet)
                    <option value="{{ $fleet->id }}">{{ $fleet->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_operation_id">Operation</label>
                <select id="edit_operation_id" name="operation_id" class="form-select">
                  <option value="">Select Operation</option>
                  @foreach ($operations as $operation)
                    <option value="{{ $operation->id }}" >
                      {{ $operation->label }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_driver">Driver</label>
                <input type="text" class="form-control" id="edit_driver" name="driver" placeholder="Enter Driver Name">
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_fill_date">Fill Date</label>
                <input type="text" class="form-control" id="edit_fill_date" name="fill_date" placeholder="YYYY-MM-DD" value="{{ now()->format('Y-m-d') }}">
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_qty">Quantity</label>
                <input type="number" id="edit_qty" name="qty" class="form-control" placeholder="Enter Quantity">
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_odometer_reading">Odometer Reading</label>
                <input type="number" id="edit_odometer_reading" name="odometer_reading" class="form-control" placeholder="Enter Odometer Reading">
              </div>
              <div class="col-12 col-md-12 form-control-validation mb-3">
                <label class="form-label" for="edit_remarks">Remarks</label>
                <textarea name="remarks" id="edit_remarks" cols="30" rows="3" class="form-control" placeholder="Enter Remarks"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Issuance</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /Edit Issuance Modal -->
  <!-- View Issuance Modal -->
  <div class="modal fade" id="viewIssuanceModal" tabindex="-1" aria-labelledby="viewIssuanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewIssuanceModalLabel">  
            <i class="ti tabler-gas-station  me-2 fs-4"></i>
            View Issuance Details
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="viewIssuanceDetails">
            <!-- Issuance details will be populated here -->
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /View Fleet Modal -->


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

  <!-- Toast Container -->
  <x-toast-container />
  
<script>
    'use strict';
    document.addEventListener('DOMContentLoaded', function () {
      // FlatPickr Initialization & Validation
      (function () {
          const dateFields = document.querySelectorAll('[name="fill_date"]');

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
        tableTitle.innerHTML = 'Fleet Management';

        dt_basic = new DataTable(dt_basic_table, {
          ajax: {
            url: '/issuances',
            dataSrc: function(json) {
              return json.data || json;
            }
          },
          columns: [
            { data: 'id' }, // For control column
            { data: 'id', title: 'S.no' },
            { data: 'tank.name', title: 'Tank' },
            { data: 'fleet.name', title: 'Fleet' },
            { data: 'operation.label', title: 'Operation' },
            { data: 'driver', title: 'Driver' },
            { data: 'fill_date', title: 'Fill Date' },
            { data: 'qty', title: 'Qty' },
            { data: 'odometer_reading', title: 'Odometer Reading' },
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
              data: 'tank.name',
              orderable: true,
              searchable: true,
            },
            {
              targets: 3,
              data: 'fleet.name',
              orderable: true,
              searchable: true
            },
            {
              targets: 4,
              data: 'operation.label',
              orderable: true,
              searchable: true
            },
            {
              targets: 5,
              data: 'driver',
              orderable: true,
              searchable: true
            },
            {
              targets: 6,
              data: 'fill_date',
              orderable: true,
              searchable: true
            },
            {
              targets: 7,
              data: 'qty',
              orderable: true,
              searchable: true
            },
            {
              targets: 8,
              data: 'odometer_reading',
              orderable: true,
              searchable: true
            },
            {
              targets: 9,
              orderable: false,
              searchable: false,
              render: (data, type, full) => (
                '<div class="d-flex gap-1">' +
                @can('fuel.issuance.view')`<button class="btn btn-icon btn-sm btn-outline-secondary waves-effect item-view" data-id="${full.id}"><i class="icon-base ti tabler-eye"></i></button>` + @endcan
                @can('fuel.issuance.edit')`<button class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit" data-id="${full.id}"><i class="icon-base ti tabler-pencil"></i></button>` + @endcan
                @can('fuel.issuance.delete')`<button class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="${full.id}"><i class="icon-base ti tabler-trash"></i></button>` + @endcan
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
            @can('fuel.issuance.create')
              top2End: {
                features: [
                  {
                    buttons: [
                      {
                        text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add Issuance</span></span>',
                        className: 'create-new btn btn-primary bg-primary',
                        attr: {
                          'data-bs-toggle': 'modal',
                          'data-bs-target': '#addIssuanceModal'
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
      }

      @can('fuel.issuance.create')
        // Add Fleet Form Submission
        const addFleetForm = document.getElementById('addFleetForm');

        addFleetForm.addEventListener('submit', function (e) {
          e.preventDefault();

          const formData = new FormData(addFleetForm);

          fetch(addFleetForm.action, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json' // 👈 force JSON response
            },
            body: formData
          })
          .then(async response => {
            if (response.ok) {
              return response.json(); // ✅ success
            }

            // 👇 handle validation error (422)
            if (response.status === 422) {
              const errorData = await response.json();
              throw { type: 'validation', errors: errorData.errors };
            }

            // 👇 fallback for other errors
            const text = await response.text();
            throw { type: 'server', message: text };
          })
          .then(data => {
            if (data.success) {
              showToast('Success', data.message || 'Issuance added successfully!', 'success');

              // Close modal
              const modal = bootstrap.Modal.getInstance(document.getElementById('addIssuanceModal'));
              modal.hide();

              // Reset form
              addFleetForm.reset();

              // Reload DataTable
              dt_basic.ajax.reload();
            } else {
              showToast('Error', data.message || 'Failed to add Issuance.', 'error');
            }
          })
          .catch(error => {
            if (error.type === 'validation') {
              // Collect Laravel validation errors
              let messages = [];
              for (const [field, errs] of Object.entries(error.errors)) {
                messages.push(`${field}: ${errs.join(', ')}`);
              }
              showToast('Validation Error', messages.join('<br>'), 'error');
            } else {
              console.error('Error:', error);
              showToast('Error', error.message || 'An error occurred. Please try again.', 'error');
            }
          });
        });
      @endcan

      @can('fuel.issuance.delete')
        // Delete Confirmation
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        let deleteIssuanceId = null;

        document.addEventListener('click', function (e) {
          if (e.target.matches('.delete-record')) {
            deleteIssuanceId = e.target.dataset.id;
            const modal = new bootstrap.Modal(deleteConfirmModal);
            modal.show();
          }
        });

        confirmDeleteBtn.addEventListener('click', function () {
          if (deleteIssuanceId) {
            fetch(`/issuances/${deleteIssuanceId}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
              }
            })
            .then(response => {
              if (response.ok) {
                showToast('Success', 'Fleet deleted successfully!', 'success');
                // Close modal
                const modal = bootstrap.Modal.getInstance(deleteConfirmModal);
                modal.hide();
                dt_basic.ajax.reload();
              } else {
                showToast('Error', 'Failed to delete Fleet.', 'error');
              }
            })
            .catch(error => {
              console.error('Error:', error);
              showToast('Error', 'An error occurred. Please try again.', 'error');
            });
          }
        });
      @endcan
      
      @can('fuel.issuance.edit')
        // Modal & form
        const editIssuanceModal = document.getElementById('editIssuanceModal');
        const editIssuanceForm = document.getElementById('editIssuanceForm');


        // ✅ Move form submit handler OUTSIDE click listener
        editIssuanceForm.addEventListener("submit", function (e) {
          e.preventDefault();

          const issuanceId = editIssuanceForm.action.split("/").pop();
          const formData = new FormData(editIssuanceForm);
          formData.append("_method", "PUT"); // Laravel needs this

          fetch(`/issuances/${issuanceId}`, {
            method: "POST", // keep POST, spoof with _method=PUT
            headers: {
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
              "Accept": "application/json" // 👈 force JSON response
            },
            body: formData,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                showToast("Success", data.message, "success");

                if (typeof issuanceTable !== "undefined") {
                  issuanceTable.ajax.reload(null, false);
                }
                const modal = bootstrap.Modal.getInstance(editIssuanceModal);
                modal.hide();
                dt_basic.ajax.reload();
              } else {
                showToast("Error", data.message || "Failed to update Issuance.", "error");
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              showToast("Error", "Something went wrong.", "error");
            });
        });
      @endcan


    });

    @can('fuel.issuance.edit')
      document.addEventListener("click", function (e) {
        if (e.target.closest(".item-edit")) {
          const issuanceId = e.target.closest(".item-edit").dataset.id;

          // Fetch fleet data
          fetch(`/issuances/${issuanceId}/edit`)
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                // Update form action
                editIssuanceForm.action = `/issuances/${issuanceId}`;

                // Populate fields
                editIssuanceForm.querySelector("#edit_tank_id").value = data.data.tank_id;
                editIssuanceForm.querySelector("#edit_fleet_id").value = data.data.fleet_id;
                editIssuanceForm.querySelector("#edit_operation_id").value = data.data.operation_id;
                editIssuanceForm.querySelector("#edit_driver").value = data.data.driver;
                editIssuanceForm.querySelector("#edit_fill_date").value = data.data.fill_date;
                editIssuanceForm.querySelector("#edit_qty").value = data.data.qty;
                editIssuanceForm.querySelector("#edit_odometer_reading").value = data.data.odometer_reading;
                editIssuanceForm.querySelector("#edit_remarks").value = data.data.remarks;
                // Show modal
                const modal = new bootstrap.Modal(editIssuanceModal);
                modal.show();
              } else {
                showToast("Error", "Failed to load issuance data.", "error");
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              showToast("Error", "An error occurred. Please try again.", "error");
            });
        }
      });
    @endcan
  

</script>
@can('fuel.issuance.view')
<script>
  document.addEventListener("DOMContentLoaded", function () {
      // Attach click event for dynamically generated buttons
      $(document).on("click", ".item-view", function () {
          let issuanceId = $(this).data("id");

          // Clear previous data
          $("#viewIssuanceDetails").html("<p class='text-center text-muted'>Loading...</p>");

          // Fetch issuance details
          $.ajax({
              url: `/issuances/${issuanceId}`, // Route::resource('issuances', IssuanceController::class)
              type: "GET",
              dataType: "json",
              success: function (response) {
                  let issuance = response.data ?? response; // If API returns {data: {...}}

                  let detailsHtml = `
                      
                          
                          
                              <div class="row mb-3">
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-gas-station me-1"></i> Tank</p>
                                      <p class="text-muted">${issuance.tank?.name ?? '-'}</p>
                                  </div>
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-truck me-1"></i> Fleet</p>
                                      <p class="text-muted">${issuance.fleet?.name ?? '-'} (${issuance.fleet?.registration_number ?? '-'})</p>
                                  </div>
                              </div>

                              <div class="row mb-3">
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-briefcase me-1"></i> Operation</p>
                                      <p class="text-muted">${issuance.operation?.label ?? '-'}</p>
                                  </div>
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-calendar me-1"></i> Fill Date</p>
                                      <span class="badge bg-info">${issuance.fill_date ?? '-'}</span>
                                  </div>
                              </div>

                              <div class="row mb-3">
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-barrel me-1"></i> Quantity</p>
                                      <span class="badge bg-success">${issuance.qty ?? '-'} L</span>
                                  </div>
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-user me-1"></i> Driver</p>
                                      <p class="text-muted">${issuance.driver ?? '-'}</p>
                                  </div>
                              </div>

                              <div class="row mb-3">
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-dashboard me-1"></i> Odometer</p>
                                      <p class="text-muted">${issuance.odometer_reading ?? '-'}</p>
                                  </div>
                                  <div class="col-md-6">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-user-shield me-1"></i> Created By</p>
                                      <p class="text-muted">${issuance.creator?.name ?? '-'}</p>
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-12">
                                      <p class="mb-1 fw-bold"><i class="ti tabler-note me-1"></i> Remarks</p>
                                      <p class="text-muted">${issuance.remarks ?? '-'}</p>
                                  </div>
                              </div>
                          
                  `;



                  $("#viewIssuanceDetails").html(detailsHtml);

                  // Show modal
                  $("#viewIssuanceModal").modal("show");
              },
              error: function (xhr) {
                  $("#viewIssuanceDetails").html("<p class='text-danger text-center'>Failed to load issuance details.</p>");
              }
          });
      });
  });
</script>
@endcan
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