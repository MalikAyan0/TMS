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
            <th>Fleet Name</th>
            <th>Manufacturer</th>
            <th>Fleet type</th>
            <th>Registration</th>
            <th>First Driver</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@can('fleet.create')
  <!-- Add Fleet Modal -->
  <div class="modal fade" id="addFleetModal" tabindex="-1" aria-labelledby="addFleetModalLabel" aria-hidden="true">
    <form id="addFleetForm" action="{{ route('fleets.store') }}" method="POST">
      @csrf
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="addFleetModalLabel">Add New Fleet</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="text-center mb-4">
              <p class="text-body-secondary">Set fleet details</p>
            </div>
            <div class="row g-3">
              <!-- Add fleet form -->
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="name">Fleet Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter fleet name" required />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="fleet_manufacturer_id">Fleet Manufacturer <span class="text-danger">*</span></label>
                <select name="fleet_manufacturer_id" id="fleet_manufacturer_id" class="form-select" required>
                  <option >Select Manufacturer</option>
                  @foreach($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="fleet_type_id">Fleet Type <span class="text-danger">*</span></label>
                <select name="fleet_type_id" id="fleet_type_id" class="form-select" required>
                  <option >Select Type</option>
                  @foreach($fleetstypes as $fleettype)
                    <option value="{{ $fleettype->id }}">{{ $fleettype->title }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="registration_number">Registration Number <span class="text-danger">*</span></label>
                <input type="text" id="registration_number" name="registration_number" class="form-control" placeholder="Enter registration number" required />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="first_driver">First Driver</label>
                <input type="text" id="first_driver" name="first_driver" class="form-control" placeholder="Enter first driver name" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="second_driver">Second Driver</label>
                <input type="text" id="second_driver" name="second_driver" class="form-control" placeholder="Enter second driver name" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="registration_city">Registration City</label>
                <input type="text" id="registration_city" name="registration_city" class="form-control" placeholder="Enter registration city" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="ownership">Ownership</label>
                <input type="text" id="ownership" name="ownership" class="form-control" placeholder="Enter ownership" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="diesel_opening_inventory">Diesel Opening Inventory</label>
                <input type="number" id="diesel_opening_inventory" name="diesel_opening_inventory" class="form-control" placeholder="Enter diesel opening inventory" />
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
@endcan
@can('fleet.edit')
  <!-- Edit Fleet Modal -->
  <div class="modal fade" id="editFleetModal" tabindex="-1" aria-labelledby="editFleetModalLabel" aria-hidden="true">
    <form id="editFleetForm" action="{{ route('fleets.update', ':id') }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="editFleetModalLabel">Edit Fleet </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="text-center mb-4">
              <p class="text-body-secondary">Update fleet details</p>
            </div>
            <div class="row g-3">
              <!-- Edit fleet form -->
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_name">Fleet Name</label>
                <input type="text" id="edit_name" name="name" class="form-control" placeholder="Enter fleet name" required />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_fleet_manufacturer_id">Manufacturer</label>
                <select id="edit_fleet_manufacturer_id" name="fleet_manufacturer_id" class="form-select">
                  <option value="">Select Manufacturer</option>
                  @foreach ($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}" >
                      {{ $manufacturer->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_fleet_type_id">Fleet Type</label>
                <select id="edit_fleet_type_id" name="fleet_type_id" class="form-select">
                  <option value="">Select Fleet Type</option>
                  @foreach ($fleetstypes as $type)
                    <option value="{{ $type->id }}" >
                      {{ $type->title }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_registration_number">Registration Number</label>
                <input type="text" id="edit_registration_number" name="registration_number" class="form-control" placeholder="Enter registration number" value="" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_first_driver">First Driver</label>
                <input type="text" id="edit_first_driver" name="first_driver" class="form-control" placeholder="Enter first driver name" value="" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_second_driver">Second Driver</label>
                <input type="text" id="edit_second_driver" name="second_driver" class="form-control" placeholder="Enter second driver name" value="" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_registration_city">Registration City</label>
                <input type="text" id="edit_registration_city" name="registration_city" class="form-control" placeholder="Enter registration city" value="" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_ownership">Ownership</label>
                <input type="text" id="edit_ownership" name="ownership" class="form-control" placeholder="Enter ownership" value="" />
              </div>
              <div class="col-12 col-md-6 form-control-validation mb-3">
                <label class="form-label" for="edit_diesel_opening_inventory">Diesel Opening Inventory</label>
                <input type="number" id="edit_diesel_opening_inventory" name="diesel_opening_inventory" class="form-control" placeholder="Enter diesel opening inventory" value="" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Fleet</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /Edit Fleet Type Modal -->
@endcan
@can('fleet.view')
  <!-- View Fleet Modal -->
  <div class="modal fade" id="viewFleetModal" tabindex="-1" aria-labelledby="viewFleetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewFleetModalLabel">View Fleet Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="viewFleetDetails">
            <!-- Fleet details will be populated here -->
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /View Fleet Modal -->
@endcan

@can('fleet.delete')
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

  <!-- Toast Container -->
  <x-toast-container />
  
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
        tableTitle.innerHTML = 'Fleet Management';

        dt_basic = new DataTable(dt_basic_table, {
          ajax: {
            url: '/fleets',
            dataSrc: function(json) {
              return json.data || json;
            }
          },
          columns: [
            { data: 'id' }, // For control column
            { data: 'id', title: 'S.no' },
            { data: 'name', title: 'Fleet Name' },
            { data: 'manufacturer.name', title: 'Manufacturer' },
            { data: 'type.title', title: 'Fleet Type' },
            { data: 'registration_number', title: 'Registration' },
            { data: 'first_driver', title: 'First Driver' },
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
              data: 'name',
              orderable: true,
              searchable: true
            },
            {
              targets: 3,
              data: 'manufacturer.name',
              orderable: true,
              searchable: true,
            },
            {
              targets: 4,
              data: 'type.title',
              orderable: true,
              searchable: true
            },
            {
              targets: 5,
              data: 'registration_number',
              orderable: true,
              searchable: true
            },
            {
              targets: 6,
              data: 'first_driver',
              orderable: true,
              searchable: true,
              render: (data) => data || '-'
            },
            {
              targets: 7,
              orderable: false,
              searchable: false,
              render: (data, type, full) => (
                '<div class="d-flex gap-1">' +
                @can('fleet.view')`<button class="btn btn-icon btn-sm btn-outline-secondary waves-effect item-view" data-id="${full.id}"><i class="icon-base ti tabler-eye"></i></button>` + @endcan
                  @can('fleet.edit')`<button class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit" data-id="${full.id}"><i class="icon-base ti tabler-pencil"></i></button>` + @endcan
                  @can('fleet.delete')`<button class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="${full.id}"><i class="icon-base ti tabler-trash"></i></button>` + @endcan
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
            @can('fleet.create')
            top2End: {
              features: [
                {
                  buttons: [
                    {
                      text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add Fleet</span></span>',
                      className: 'create-new btn btn-primary bg-primary',
                      attr: {
                        'data-bs-toggle': 'modal',
                        'data-bs-target': '#addFleetModal'
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

      @can('fleet.create')
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
            showToast('Success', data.message || 'Fleet added successfully!', 'success');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addFleetModal'));
            modal.hide();

            // Reset form
            addFleetForm.reset();

            // Reload DataTable
            dt_basic.ajax.reload();
          } else {
            showToast('Error', data.message || 'Failed to add Fleet.', 'error');
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
      @can('fleet.delete')
      // Delete Confirmation
      const deleteConfirmModal = document.getElementById('deleteConfirmModal');
      const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

      let deleteFleetTypeId = null;

      document.addEventListener('click', function (e) {
        if (e.target.matches('.delete-record')) {
          deleteFleetTypeId = e.target.dataset.id;
          const modal = new bootstrap.Modal(deleteConfirmModal);
          modal.show();
        }
      });

      confirmDeleteBtn.addEventListener('click', function () {
        if (deleteFleetTypeId) {
          fetch(`/fleets/${deleteFleetTypeId}`, {
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
      @can('fleet.edit')
      // Modal & form
      const editFleetModal = document.getElementById('editFleetModal');
      const editFleetForm = document.getElementById('editFleetForm');


      // ✅ Move form submit handler OUTSIDE click listener
      editFleetForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const fleetId = editFleetForm.action.split("/").pop();
        const formData = new FormData(editFleetForm);
        formData.append("_method", "PUT"); // Laravel needs this

        fetch(`/fleets/${fleetId}`, {
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

              if (typeof fleetTypeTable !== "undefined") {
                fleetTypeTable.ajax.reload(null, false);
              }
              const modal = bootstrap.Modal.getInstance(editFleetModal);
              modal.hide();
              dt_basic.ajax.reload();
            } else {
              showToast("Error", data.message || "Failed to update fleet type.", "error");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            showToast("Error", "Something went wrong.", "error");
          });
      });
      @endcan


    });

    @can('fleet.edit')
    document.addEventListener("click", function (e) {
      if (e.target.closest(".item-edit")) {
        const fleetId = e.target.closest(".item-edit").dataset.id;

        // Fetch fleet data
        fetch(`/fleets/${fleetId}/edit`)
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Update form action
              editFleetForm.action = `/fleets/${fleetId}`;

              // Populate fields
              editFleetForm.querySelector("#edit_name").value = data.data.name;
              editFleetForm.querySelector("#edit_fleet_manufacturer_id").value = data.data.fleet_manufacturer_id;
              editFleetForm.querySelector("#edit_fleet_type_id").value = data.data.fleet_type_id;
              editFleetForm.querySelector("#edit_registration_number").value = data.data.registration_number;
              editFleetForm.querySelector("#edit_first_driver").value = data.data.first_driver || '';
              editFleetForm.querySelector("#edit_second_driver").value = data.data.second_driver || '';
              editFleetForm.querySelector("#edit_registration_city").value = data.data.registration_city || '';
              editFleetForm.querySelector("#edit_ownership").value = data.data.ownership || '';
              editFleetForm.querySelector("#edit_diesel_opening_inventory").value = data.data.diesel_opening_inventory || '';

              // Show modal
              const modal = new bootstrap.Modal(editFleetModal);
              modal.show();
            } else {
              showToast("Error", "Failed to load fleet type data.", "error");
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
@can('fleet.view')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Attach click event for dynamically generated buttons
    $(document).on("click", ".item-view", function () {
        let fleetId = $(this).data("id");

        // Clear previous data
        $("#viewFleetDetails").html("<p class='text-center text-muted'>Loading...</p>");

        // Fetch fleet details
        $.ajax({
            url: `/fleets/${fleetId}`,
            type: "GET",
            success: function (response) {
                let fleet = response.data ?? response;

                let detailsHtml = `
                    <div class="fleet-details">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-1">${fleet.name} </h4>
                                <p class="text-muted mb-0"><i class="ti tabler-building-factory"></i> Fleet Manufacturer: <span class="fw-semibold">${fleet.manufacturer?.name ?? '-'}</span></p>
                                <p class="text-muted mb-0"><i class="ti tabler-circle-dashed-letter-t"></i> Fleet Type: <span class="fw-semibold">${fleet.type?.title ?? '-'}</span></p>
                                <p class="text-muted"><i class="ti tabler-hash"></i> Registration #: <span class="fw-semibold">${fleet.registration_number ?? '-'}</span></p>
                            </div>
                        </div>

                        <h5 class="fw-bold mt-4 mb-2">Driver Information</h5>
                        <table class="table table-sm table-striped table-bordered shadow-sm">
                            <tbody>
                                <tr><th width="30%">First Driver</th><td>${fleet.first_driver ?? '-'}</td></tr>
                                <tr><th>Second Driver</th><td>${fleet.second_driver ?? '-'}</td></tr>
                            </tbody>
                        </table>

                        <h5 class="fw-bold mt-4 mb-2">Registration & Ownership</h5>
                        <table class="table table-sm table-striped table-bordered shadow-sm">
                            <tbody>
                                <tr><th width="30%">Registration City</th><td>${fleet.registration_city ?? '-'}</td></tr>
                                <tr><th>Ownership</th><td>${fleet.ownership ?? '-'}</td></tr>
                                <tr><th>Diesel Opening Inventory</th><td>${fleet.diesel_opening_inventory ?? '-'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                `;

                $("#viewFleetDetails").html(detailsHtml);

                // Show modal
                $("#viewFleetModal").modal("show");
            },
            error: function (xhr) {
                $("#viewFleetDetails").html("<p class='text-danger text-center'>Failed to load fleet details.</p>");
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