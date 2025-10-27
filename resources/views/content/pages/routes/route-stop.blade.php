@extends('layouts/layoutMaster')

@section('title', 'Routes Stops')

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
              <th>S.No</th>
              <th>Route</th>
              <th>Stop Order</th>
              <th>Location</th>
              <th>Load</th>
              <th>Load Type</th>
              <th>Arrival</th>
              <th>Departure</th>
              <th>Actual Arrival</th>
              <th>Actual Departure</th>
              <th>Action</th>
            </tr>
        </thead>
      </table>
    </div>
  </div>

<!-- Add Route stop Modal -->
    <div class="modal fade" id="addRouteStopModal" tabindex="-1" aria-labelledby="addRouteStopModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addRouteStopModalLabel">Add Route Stop</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="addRouteStopForm">
            <div class="modal-body row g-3">

              <!-- Route (dropdown or hidden if route is fixed) -->
              <div class="col-md-6">
                <label for="route_id" class="form-label">Route</label>
                <select class="form-select" id="route_id" name="route_id" required>
                  <option value="">Select Route</option>
                  <!-- Populate dynamically with routes -->
                  @foreach($routes as $route)
                    <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Stop Order -->
              <div class="col-md-6">
                <label for="stop_order" class="form-label">Stop Order</label>
                <input type="number" class="form-control" id="stop_order" name="stop_order" required>
              </div>

              <!-- Location Name -->
              <div class="col-md-6">
                <label for="location_name" class="form-label">Location Name</label>
                <input type="text" class="form-control" id="location_name" name="location_name" required>
              </div>

              <!-- Load -->
              <div class="col-md-6">
                <label for="load" class="form-label">Load</label>
                <input type="text" class="form-control" id="load" name="load">
              </div>

              <!-- Load Type -->
              <div class="col-md-6">
                <label for="load_type" class="form-label">Load Type</label>
                <input type="text" class="form-control" id="load_type" name="load_type">
              </div>

              <!-- Arrival Time -->
              <div class="col-md-6">
                <label for="arrival_time" class="form-label">Planned Arrival</label>
                <input type="time" class="form-control" id="arrival_time" name="arrival_time">
              </div>

              <!-- Departure Time -->
              <div class="col-md-6">
                <label for="departure_time" class="form-label">Planned Departure</label>
                <input type="time" class="form-control" id="departure_time" name="departure_time">
              </div>

              <!-- Actual Arrival -->
              <div class="col-md-6">
                <label for="actual_arrival_time" class="form-label">Actual Arrival</label>
                <input type="time" class="form-control" id="actual_arrival_time" name="actual_arrival_time">
              </div>

              <!-- Actual Departure -->
              <div class="col-md-6">
                <label for="actual_departure_time" class="form-label">Actual Departure</label>
                <input type="time" class="form-control" id="actual_departure_time" name="actual_departure_time">
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add Route Stop</button>
            </div>
          </form>
        </div>
      </div>
    </div>
<!-- /Add Route stop Modal -->

<!-- edit Route Modal -->
  <div class="modal fade" id="editRouteModal" tabindex="-1" aria-labelledby="editRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editRouteModalLabel">Edit Route</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editRouteForm">
          <div class="modal-body row">
            <input type="hidden" name="id" id="edit_route_id">
            <!-- Route Name -->
            <div class="col-md-6 mb-3">
              <label for="edit_route_name" class="form-label">Route Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_route_name" name="route_name" placeholder="Enter route name" required>
            </div>

            <!-- Route Code -->
            <div class="col-md-6 mb-3">
              <label for="edit_route_code" class="form-label">Route Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_route_code" name="route_code" placeholder="R001" required>
            </div>

            <!-- Origin -->
            <div class="col-md-6 mb-3">
              <label for="edit_origin" class="form-label">Origin <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_origin" name="origin" placeholder="City A" required>
            </div>

            <!-- Destination -->
            <div class="col-md-6 mb-3">
              <label for="edit_destination" class="form-label">Destination <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_destination" name="destination" placeholder="City B" required>
            </div>

            <!-- Distance -->
            <div class="col-md-6 mb-3">
              <label for="edit_distance" class="form-label">Distance (km) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="edit_distance" name="distance" placeholder="e.g. 250" min="0" step="0.1" required>
            </div>

            <!-- Estimated Time -->
            <div class="col-md-6 mb-3">
              <label for="edit_estimated_time" class="form-label">Estimated Time (hrs) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="edit_estimated_time" name="estimated_time" placeholder="e.g. 5" min="0" step="0.1" required>
            </div>

            <!-- Expected Fuel -->
            <div class="col-md-6 mb-3">
              <label for="edit_expected_fuel" class="form-label">Expected Fuel (liters) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="edit_expected_fuel" name="expected_fuel" placeholder="e.g. 120" min="0" step="0.1" required>
            </div>

            <!-- Expected Cost -->
            <div class="col-md-6 mb-3">
              <label for="edit_expected_cost" class="form-label">Expected Cost <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="edit_expected_cost" name="expected_cost" placeholder="e.g. 5000" min="0" step="0.01" required>
            </div>

              <!-- Status -->
            <div class="col-md-6 mb-3">
              <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
              <select class="form-select" id="edit_status" name="status" required>
                <option value="planned" selected>Planned</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Route</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<!-- / edit Fuel Modal -->

<!-- view Route Modal -->
  <div class="modal fade" id="viewRouteModal" tabindex="-1" aria-labelledby="viewRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewRouteModalLabel">View Route</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="viewRouteDetails"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<!-- / view Fuel Modal -->


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


<!-- Toast Container -->
<x-toast-container />
<!-- / Toast Container -->

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
      tableTitle.innerHTML = 'Routes Stops';

      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/route-stops',
          dataSrc: function(json) {
            return json.data || json;
          }
        },
        columns: [
          { data: 'id' }, // For control column (expand/collapse, checkbox etc.)
          { data: 'id', title: 'S.no' },
          { data: 'route.route_name', title: 'Route' }, // assuming you eager load route in query
          { data: 'stop_order', title: 'Stop Order' },
          { data: 'location_name', title: 'Location' },
          { data: 'load', title: 'Load' },
          { data: 'load_type', title: 'Load Type' },
          { data: 'arrival_time', title: 'Arrival Time' },
          { data: 'departure_time', title: 'Departure Time' },
          { data: 'actual_arrival_time', title: 'Actual Arrival' },
          { data: 'actual_departure_time', title: 'Actual Departure' },
          { data: null, title: 'Action', orderable: false, searchable: false }
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
            render: (data, type, full) => full.route_name ?? '-' // Route Name
          },
          {
            targets: 3,
            render: (data, type, full) => full.route_code ?? '-' // Route Code
          },
          {
            targets: 4,
            render: (data, type, full) => full.origin ?? '-' // Origin
          },
          {
            targets: 5,
            render: (data, type, full) => full.destination ?? '-' // Destination
          },
          {
            targets: 6,
            render: (data, type, full) => full.distance ? `${full.distance} km` : '-' // Distance
          },
          {
            targets: 7,
            render: (data, type, full) => {
              const time = full.estimated_time ? `${full.estimated_time} hrs` : '-';
              const fuel = full.expected_fuel ? ` / ${full.expected_fuel} L` : '';
              return `${time}${fuel}`;
            }
          },

          {
            targets: 8,
            render: (data, type, full) => full.expected_cost ? `${full.expected_cost}PKR` : '-' // Expected Cost
          },
          {
            targets: 9,
            render: (data, type, full) => full.status ?? '-' // Status
          },

          {
            targets: 10,
            orderable: false,
            searchable: false,
            render: (data, type, full) => (
              '<div class="d-flex gap-1">' +
                `<button class="btn btn-icon btn-sm btn-outline-secondary waves-effect item-view" data-id="${full.id}"><i class="icon-base ti tabler-eye"></i></button>` +
               `<button class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit" data-id="${full.id}"><i class="icon-base ti tabler-pencil"></i></button>` +
                `<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="${full.id}"><i class="icon-base ti tabler-trash"></i></button>` +
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

          top2End: {
            features: [
              {
                buttons: [
                  {
                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add Route Stop</span></span>',
                    className: 'create-new btn btn-primary bg-primary',
                    attr: {
                      'data-bs-toggle': 'modal',
                      'data-bs-target': '#addRouteStopModal'
                    }
                  }
                ]
              }
            ]
          },

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
            const response = await fetch(`/routes/${deleteId}`, {
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



        // AJAX store for add route form
        const addRouteStopForm = document.getElementById('addRouteStopForm');
        if (addRouteStopForm) {
          addRouteStopForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(addRouteStopForm);
            addRouteStopForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            fetch('/route-stops', {
              method: 'POST',
              headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              },
              body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
              if (status === 200 || status === 201) {
                showToast('Success',  'Route stop added successfully!', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('addRouteStopModal'));
                modal.hide();
                addRouteStopForm.reset();
                dt_basic.ajax.reload();
              } else if (status === 422 && body.errors) {
                Object.keys(body.errors).forEach(field => {
                  const input = addRouteStopForm.querySelector(`[name="${field}"]`);
                  if (input) input.classList.add('is-invalid');
                });
                const errorMessages = Object.values(body.errors).flat().join('<br>');
                showToast('Error', 'Please fix the errors in the form.' + errorMessages, 'error');
              } else {
                showToast('Error', 'An error occurred while adding route stop.', 'error');
              }
            })
            .catch(() => {
              showToast('Error', 'Network error. Please try again.', 'error');
            });
          });
        }



      // ✅ Event delegation for Edit Fuel button clicks
      document.addEventListener('click', function (e) {
        const button = e.target.closest('.item-edit');
        if (!button) return;

          const routeId = button.getAttribute('data-id');

          fetch(`/routes/${routeId}`)
              .then(response => response.json())
              .then(result => {
              const route = result.data; // ✅ actual route record
              if (route) {
                const editRouteForm = document.getElementById('editRouteForm');
                if (editRouteForm) {
                  editRouteForm.dataset.id = routeId;

                  editRouteForm.querySelector('[name="id"]').value = route.id || '';
                  editRouteForm.querySelector('[name="route_name"]').value = route.route_name || '';
                  editRouteForm.querySelector('[name="route_code"]').value = route.route_code || '';
                  editRouteForm.querySelector('[name="origin"]').value = route.origin || '';
                  editRouteForm.querySelector('[name="destination"]').value = route.destination || '';
                  editRouteForm.querySelector('[name="distance"]').value = route.distance || '';
                  editRouteForm.querySelector('[name="estimated_time"]').value = route.estimated_time || '';
                  editRouteForm.querySelector('[name="expected_fuel"]').value = route.expected_fuel || '';
                  editRouteForm.querySelector('[name="expected_cost"]').value = route.expected_cost || '';
                  editRouteForm.querySelector('[name="status"]').value = route.status || '';

                  const modal = new bootstrap.Modal(document.getElementById('editRouteModal'));
                  modal.show();
                }
              }
        });
      });

      // ✅ AJAX update for Edit Route form
      const editRouteForm = document.getElementById('editRouteForm');
      if (editRouteForm) {
          editRouteForm.addEventListener('submit', function (e) {
              e.preventDefault();

              const formData = new FormData(editRouteForm);
              editRouteForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

              fetch(`/routes/${editRouteForm.dataset.id}`, {
                  method: 'POST', // Laravel PUT method via _method field
                  headers: {
                      'Accept': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: formData
              })
              .then(response => response.json().then(data => ({ status: response.status, body: data })))
              .then(({ status, body }) => {
                  if (status === 200) {
                      showToast('Success', 'Route record updated successfully!', 'success');
                      const modal = bootstrap.Modal.getInstance(document.getElementById('editRouteModal'));
                      modal.hide();
                      editRouteForm.reset();
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
                      showToast('Error', 'An error occurred while updating route.', 'error');
                  }
              })
              .catch(() => {
                  showToast('Error', 'Network error. Please try again.', 'error');
              });
          });
      }

          // Open View Modal
      $(document).on('click', '.item-view', function () {
          let id = $(this).data('id');
          viewRoute(id);
      });

      function viewRoute(id) {
          fetch(`/routes/${id}`)
              .then(res => res.json())
              .then(response => {
                  let route = response.data;

                  // Status badge color
                  let statusClass = {
                      planned: "primary",
                      active: "success",
                      completed: "info",
                      cancelled: "danger"
                  }[route.status] || "secondary";

                  let stopsHtml = "";
                  if (route.stops && route.stops.length > 0) {
                      stopsHtml = `
                        <h5 class="mt-4"><i class="bi bi-geo-alt-fill text-danger"></i> Stops</h5>
                        <ul class="list-group list-group-flush">
                          ${route.stops.map((stop, index) => `
                            <li class="list-group-item d-flex align-items-center">
                              <span class="badge bg-dark me-2">${index + 1}</span>
                              <div>
                                <strong>${stop.name}</strong><br>
                                <small class="text-muted">${stop.location || "No location"}</small>
                              </div>
                            </li>
                          `).join("")}
                        </ul>
                      `;
                  }

                  let assignmentsHtml = "";
                  if (route.assignments && route.assignments.length > 0) {
                      assignmentsHtml = `
                        <h5 class="mt-4"><i class="bi bi-person-badge text-primary"></i> Assignments</h5>
                        <div class="row">
                          ${route.assignments.map(assign => `
                            <div class="col-md-6">
                              <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                  <h6 class="fw-bold">${assign.driver_name || "Driver"}</h6>
                                  <p class="mb-1"><i class="bi bi-telephone"></i> ${assign.driver_contact || "N/A"}</p>
                                  <p class="mb-0"><i class="bi bi-calendar-event"></i> Assigned: ${assign.assigned_date || "N/A"}</p>
                                </div>
                              </div>
                            </div>
                          `).join("")}
                        </div>
                      `;
                  }

                  let detailsHtml = `
                    <div class="row g-4">
                      <div class="col-md-12">
                        <div class="card shadow-sm border-0">
                          <div class="card-body row align-items-center">
                            <!-- Left side: Basic Info -->
                            <div class="col-md-6 d-flex align-items-center">
                              <h5 class="fw-bold mb-0">
                                Basic Info
                              </h5>
                            </div>

                            <!-- Right side: Status Badge -->
                            <div class="col-md-6 text-md-end text-start">
                              <span class="badge bg-${statusClass} px-3 py-2 fs-6">
                                ${route.status.charAt(0).toUpperCase() + route.status.slice(1)}
                              </span>
                            </div>
                            <!-- Details -->
                            <div class="col-md-6 mt-3">
                              <p><strong>Route Name:</strong> ${route.route_name}</p>
                            </div>
                            <div class="col-md-6 mt-3">
                              <p><strong>Route Code:</strong> ${route.route_code}</p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="card shadow-sm border-0">
                          <div class="card-body row">
                            <h5 class="fw-bold"><i class="bi bi-map"></i> Details</h5>
                            <div class="col-md-6">
                              <p><strong>Origin:</strong> ${route.origin}</p>
                            </div>
                            <div class="col-md-6">
                              <p><strong>Destination:</strong> ${route.destination}</p>
                            </div>
                            <div class="col-md-6">
                              <p><strong>Distance:</strong> ${route.distance} km</p>
                            </div>
                            <div class="col-md-6">
                              <p><strong>Distance:</strong> ${route.distance} km</p>
                            </div>
                            <div class="col-md-6">
                              <p><strong>Estimated Time:</strong> ${route.estimated_time} hrs</p>
                            </div>
                            <div class="col-md-6">
                              <p><strong>Expected Fuel:</strong> ${route.expected_fuel} liters</p>
                            </div>
                            <div class="col-md-6">
                              <p><strong>Expected Cost:</strong> ${route.expected_cost}</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    ${stopsHtml}
                    ${assignmentsHtml}
                  `;

                  document.getElementById('viewRouteDetails').innerHTML = detailsHtml;

                  let viewModal = new bootstrap.Modal(document.getElementById('viewRouteModal'));
                  viewModal.show();
              })
              .catch(err => {
                  console.error("Error fetching route record:", err);
              });
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
