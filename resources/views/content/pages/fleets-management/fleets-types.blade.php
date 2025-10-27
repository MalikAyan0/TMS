@extends('layouts/layoutMaster')

@section('title', 'Fleet Types')

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
            <th>Image</th>
            <th>Title</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  @can('fleet.type.create')
    <!-- Add Fleet Type Modal -->
    <div class="modal fade" id="addFleetTypeModal" tabindex="-1" aria-labelledby="addFleetTypeModalLabel" aria-hidden="true">
      <form id="addFleetTypeForm" action="{{ route('fleet-types.store') }}" method="POST">
        @csrf
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="addFleetTypeModalLabel">Add New Fleet Type</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="text-center mb-4">
                <p class="text-body-secondary">Set fleet type details</p>
              </div>
              <div class="row g-3">
                <!-- Add fleet type form -->
                <div class="col-12 form-control-validation mb-3">
                  <label class="form-label" for="title">Fleet Type Name</label>
                  <input type="text" id="title" name="title" class="form-control" placeholder="Enter a fleet type name" />
                </div>
                <div class="col-12">
                  <h5 class="mb-4">Fleet Type Image</h5>
                  <input type="file" id="image" name="image" class="form-control" />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add Fleet Type</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- /Add Fleet Type Modal -->
  @endcan

  @can('fleet.type.edit')
    <!-- Edit Fleet Type Modal -->
    <div class="modal fade" id="editFleetTypeModal" tabindex="-1" aria-labelledby="editFleetTypeModalLabel" aria-hidden="true">
      <form id="editFleetTypeForm" action="{{ route('fleet-types.update', ':id') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="editFleetTypeModalLabel">Edit Fleet Type</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="text-center mb-4">
                <p class="text-body-secondary">Update fleet type details</p>
              </div>
              <div class="row g-3">
                <!-- Edit fleet type form -->
                <div class="col-12 form-control-validation mb-3">
                  <label class="form-label" for="edit_title">Fleet Type Name</label>
                  <input type="text" id="edit_title" name="title" class="form-control" placeholder="Enter a fleet type name" />
                </div>
                <div class="col-12">
                  <h5 class="mb-4">Fleet Type Image</h5>
                  <input type="file" id="edit_image" name="image" class="form-control" />
                  <!-- 👇 ADD THIS PREVIEW IMAGE -->
                  <div class="mt-2">
                    <img id="edit_image_preview" 
                        src="" 
                        alt="Preview" 
                        class="img-thumbnail" width="100">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update Fleet Type</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- /Edit Fleet Type Modal -->
  @endcan

  @can('fleet.type.delete')
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
      tableTitle.innerHTML = 'Fleet Types';

      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/fleet-types',
          dataSrc: function(json) {
            return json.data || json;
          }
        },
        columns: [
          { data: 'id' }, // For control column
          { data: 'id', title: 'S.no' },
          { data: 'image', title: 'Image' },
          { data: 'title', title: 'Title' },
          { data: 'creator.name', title: 'Created By' },
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
            orderable: false,
            searchable: false,
            render: function (data, type, full) {
              const title = full.title;
              const image = full.image;
              let output;

              if (image) {
                output = `<img src="/storage/${image}" alt="${title}" class="rounded-circle" style="width:40px; height:40px;">`;
              } else {
                const stateNum = Math.floor(Math.random() * 6) + 1;
                const states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                const state = states[stateNum];
                const initials = (title?.match(/\b\w/g) || []).slice(0, 2).join('').toUpperCase();
                output = `<span class="avatar-initial rounded-circle bg-label-${state}" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center; font-weight:bold;">${initials}</span>`;
              }

              return `
                <div class="d-flex justify-content-left align-items-center role-name">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                      ${output}
                    </div>
                  </div>
                  
                </div>
              `;
            }
          },
          {
            targets: 3,
            data: 'title',
            orderable: true,
            searchable: true
          },
          {
            target : 4,
            data: 'creator.name',
            orderable: true,
            searchable: true
          },
          {
            targets: 5,
            orderable: false,
            searchable: false,
            render: (data, type, full) => (
              '<div class="d-flex gap-1">' +
                @can('fleet.type.edit')`<button class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit" data-id="${full.id}"><i class="icon-base ti tabler-pencil"></i></button>` +@endcan
                @can('fleet.type.delete')`<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="${full.id}"><i class="icon-base ti tabler-trash"></i></button>` +@endcan
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
          @can('fleet.type.create')
          top2End: {
            features: [
              {
                buttons: [
                  {
                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add Fleet Type</span></span>',
                    className: 'create-new btn btn-primary bg-primary',
                    attr: {
                      'data-bs-toggle': 'modal',
                      'data-bs-target': '#addFleetTypeModal'
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

    @can('fleet.type.create')
      // Add Fleet Type Form Submission
      const addFleetTypeForm = document.getElementById('addFleetTypeForm');

      addFleetTypeForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(addFleetTypeForm);

        fetch(addFleetTypeForm.action, {
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
            showToast('Success', data.message || 'Fleet Type added successfully!', 'success');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addFleetTypeModal'));
            modal.hide();

            // Reset form
            addFleetTypeForm.reset();

            // Reload DataTable
            dt_basic.ajax.reload();
          } else {
            showToast('Error', data.message || 'Failed to add Fleet Type.', 'error');
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

    @can('fleet.type.delete')
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
          fetch(`/fleet-types/${deleteFleetTypeId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'
            }
          })
          .then(response => {
            if (response.ok) {
              showToast('Success', 'Fleet Type deleted successfully!', 'success');
              // Close modal
              const modal = bootstrap.Modal.getInstance(deleteConfirmModal);
              modal.hide();
              dt_basic.ajax.reload();
            } else {
              showToast('Error', 'Failed to delete Fleet Type.', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred. Please try again.', 'error');
          });
        }
      });
    @endcan

    @can('fleet.type.edit')
    // Modal & form
    const editFleetTypeModal = document.getElementById('editFleetTypeModal');
    const editFleetTypeForm = document.getElementById('editFleetTypeForm');

    // Preview image when selecting new file
    document.getElementById("edit_image").addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        document.getElementById("edit_image_preview").src = URL.createObjectURL(file);
      }
    });

    // ✅ Move form submit handler OUTSIDE click listener
    editFleetTypeForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const fleetTypeId = editFleetTypeForm.action.split("/").pop();
      const formData = new FormData(editFleetTypeForm);
      formData.append("_method", "PUT"); // Laravel needs this

      fetch(`/fleet-types/${fleetTypeId}`, {
        method: "POST", // keep POST, spoof with _method=PUT
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
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
            const modal = bootstrap.Modal.getInstance(editFleetTypeModal);
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

  @can('fleet.type.edit')
    document.addEventListener("click", function (e) {
      if (e.target.closest(".item-edit")) {
        const fleetTypeId = e.target.closest(".item-edit").dataset.id;

        // Fetch fleet type data
        fetch(`/fleet-types/${fleetTypeId}/edit`)
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Update form action
              editFleetTypeForm.action = `/fleet-types/${fleetTypeId}`;

              // Populate fields
              editFleetTypeForm.querySelector("#edit_title").value = data.data.title;
              editFleetTypeForm.querySelector("#edit_image").value = "";

              // Show preview image
              const previewImg = document.getElementById("edit_image_preview");
              previewImg.src = data.data.image ? data.data.image : "";

              // Show modal
              const modal = new bootstrap.Modal(editFleetTypeModal);
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
