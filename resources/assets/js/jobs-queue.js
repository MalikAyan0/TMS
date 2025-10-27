'use strict';



  let fv, offCanvasEl;
  document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
      // Initialize Flatpickr on all inputs with class "vuexy_date"
      const dateInputs = document.querySelectorAll('.vuexy_date');

      dateInputs.forEach(function (input) {
        input.flatpickr({
          enableTime: false,
          monthSelectorType: 'static',
          static: true,
          dateFormat: 'm/d/Y',
          onChange: function () {
            if (typeof fv !== 'undefined') {
              fv.revalidateField(input.name); // Revalidate each field by its name
            }
          }
        });
      });
    })();

    // init function
    const dt_basic_table = document.querySelector('.datatables-basic');
    let dt_basic;
    if (dt_basic_table) {
      let tableTitle = document.createElement('h5');
      tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
      tableTitle.innerHTML = 'Jobs Queue';
      dt_basic = new DataTable(dt_basic_table, {
        ajax: {
          url: '/jobs',
          type: 'GET', // or GET if your server expects it
          contentType: 'application/json',
          dataSrc: function(json) {
            return json.data || json;
          }
        },
        columns: [
          {data:'id'},
          { data: '', orderable: true, },
          { data: 'job_number', defaultContent: '-' },
          { data: 'bail_number.bail_number', defaultContent: '-' },
          { data: 'container' },
          { data: 'line.name', defaultContent: '-' },
          { data: 'port.name', defaultContent: '-' },
          { data: 'logistics.vehicle.registration_number', defaultContent: '-' },
          {
            data: 'logistics.gate_time_passed',
            defaultContent: '-',
            render: function (data, type, full, meta) {
              if (!data) return '-';
              // Format the date using Intl.DateTimeFormat
              const date = new Date(data);
              return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
              }).format(date);
            },
          },
          { data: 'status', defaultContent: '-' },
          { data: 'id' },

        ],
        columnDefs: [
          {
            className: 'control',
            orderable: false,
            searchable: false,
            responsivePriority: 0,
            targets: 0,
            render: function (data, type, full, meta) {
              return '';
            }
          },
          {
            targets: 1,
            orderable: false,
            searchable: false,
            responsivePriority: 1,
            render: function (data, type, full, meta) {
              // Show row number starting from 1
              return meta.row + 1;
            }
            },

          {
            targets: 2,
            responsivePriority: 1,
            render: function (data, type, full, meta) {
              const job_number = full['job_number'] || '';
              const company = (full.company && full.company.title) ? full.company.title : '';
              const rowOutput = `
                <div class="d-flex flex-column">
                  <span class="job_name text-truncate text-heading fw-medium">${job_number}</span>
                </div>
              `;
              return rowOutput;
            }
          },
          {
            responsivePriority: 2,
            targets: 3,
            render: function (data, type, full, meta) {
              let bail = '-';
              let bail_id = null;

              if (full.bail_number) {
                // Case: bail_number is an object with bail_number + id
                if (typeof full.bail_number === 'object') {
                  bail = full.bail_number.bail_number;
                  bail_id = full.bail_number.id;
                }
                // Case: bail_number is just a string
                else {
                  bail = full.bail_number;
                  // If your API also sends bail_number_id separately, grab it
                  if (full.bail_number_id) {
                    bail_id = full.bail_number_id;
                  }
                }
              }

              return bail !== '-' && bail_id
                ? `<a href="/bails/${bail_id}/view">${bail}</a>`
                : bail;
            }
          },
          {
            responsivePriority: 3,
            targets: 4,
            render: function (data, type, full, row) {
              const container = full['container'] || '';
              return `<span class="text-truncate text-body">${container}</span>` || '-';
            }
            },
            {
            targets: 5,
            },
            {
            targets: 6
            },
            {
            targets: 7,
            responsivePriority: 5,
            },
            {
            targets: 8
            },
            {
            responsivePriority: 4,
            targets: 9,
            render: function (data, type, row) {
              let status = data ? data.toLowerCase() : '-';
              let badgeClass = 'bg-label-secondary';

              switch (status) {
                case 'open':
                  badgeClass = 'bg-label-primary';
                  break;
                case 'vehicle required': // Updated status
                  badgeClass = 'bg-label-warning';
                  break;
                case 'on route':
                  badgeClass = 'bg-label-info';
                  break;
                case 'stuck on port':
                  badgeClass = 'bg-label-danger';
                  break;
                case 'vehicle returned':
                  badgeClass = 'bg-label-dark';
                  break;
                case 'empty return':
                  badgeClass = 'bg-label-secondary';
                  break;
                case 'completed':
                  badgeClass = 'bg-label-success';
                  break;
                case 'cancelled':
                  badgeClass = 'bg-label-danger';
                  break;
                default:
                  badgeClass = 'bg-label-secondary';
              }

              return `<span class="badge ${badgeClass}">${data || '-'}</span>`;
            }
            },
            {
            targets: -1,
            responsivePriority: 3,
            title: 'Actions',
            orderable: false,
            searchable: false,
            render: function (data, type, full, meta) {
              const status = (full.status || '').toLowerCase();
              const showActions = status === 'open' || status === 'vehicle required'; // Updated condition
              return (
                '<div class="d-flex gap-1">' +
                '<a href="/jobs/' + full.id + '" class="btn btn-icon btn-sm btn-outline-secondary waves-effect"><i class="icon-base ti tabler-eye"></i></a>' +
                (showActions
                  ? '<button type="button" class="btn btn-icon btn-sm btn-outline-primary waves-effect item-edit"><i class="icon-base ti tabler-pencil"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect delete-record" data-id="' + full.id + '"><i class="icon-base ti tabler-trash"></i></button>' +
                    '<button type="button" class="btn btn-icon btn-sm btn-outline-info waves-effect add-comments-btn" data-job-queue-id="' + full.id + '" data-status="' + full.status + '"><i class="icon-base ti tabler-message-circle"></i></button>'
                    : ''
                ) +
                '</div>'
              );
            }
            }
          ],
          select: {
            style: 'multi',
            selector: 'td:nth-child(2)'
          },
          order: [[1, 'asc']],
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
                extend: 'collection',
                className: 'btn btn-label-primary dropdown-toggle me-4',
                text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-upload icon-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                buttons: [
                  {
                  extend: 'print',
                  text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-printer me-1"></i>Print</span>`,
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10],
                    format: {
                    body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (inner.indexOf('<') > -1) {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(inner, 'text/html');
                                let text = '';
                                const userNameElements = doc.querySelectorAll('.user-name');
                                if (userNameElements.length > 0) {
                                  userNameElements.forEach(el => {
                                    const nameText =
                                      el.querySelector('.fw-medium')?.textContent ||
                                      el.querySelector('.d-block')?.textContent ||
                                      el.textContent;
                                    text += nameText.trim() + ' ';
                                  });
                                } else {
                                  text = doc.body.textContent || doc.body.innerText;
                                }
                                return text.trim();
                              }
                              return inner;
                            }
                          }
                        },
                        customize: function (win) {
                          win.document.body.style.color = config.colors.headingColor;
                          win.document.body.style.borderColor = config.colors.borderColor;
                          win.document.body.style.backgroundColor = config.colors.bodyBg;
                          const table = win.document.body.querySelector('table');
                          table.classList.add('compact');
                          table.style.color = 'inherit';
                          table.style.borderColor = 'inherit';
                          table.style.backgroundColor = 'inherit';
                        }
                      },
                      {
                        extend: 'csv',
                        text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-text me-1"></i>Csv</span>`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      },
                      {
                        extend: 'excel',
                        text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-spreadsheet me-1"></i>Excel</span>`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      },
                      {
                        extend: 'pdf',
                        text: `<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-description me-1"></i>Pdf</span>`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      },
                      {
                        extend: 'copy',
                        text: `<i class="icon-base ti tabler-copy me-1"></i>Copy`,
                        className: 'dropdown-item',
                        exportOptions: {
                          columns: [3, 4, 5, 6, 7, 8, 9, 10],
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              const parser = new DOMParser();
                              const doc = parser.parseFromString(inner, 'text/html');
                              let text = '';
                              const userNameElements = doc.querySelectorAll('.user-name');
                              if (userNameElements.length > 0) {
                                userNameElements.forEach(el => {
                                  const nameText =
                                    el.querySelector('.fw-medium')?.textContent ||
                                    el.querySelector('.d-block')?.textContent ||
                                    el.textContent;
                                  text += nameText.trim() + ' ';
                                });
                              } else {
                                text = doc.body.textContent || doc.body.innerText;
                              }
                              return text.trim();
                            }
                          }
                        }
                      }
                    ]
                  },
                  {
                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add New Job</span></span>',
                    className: 'create-new btn btn-primary',
                    attr: {
                      'data-bs-toggle': 'modal',
                      'data-bs-target': '#createJobModal'
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
        language: {
          paginate: {
            next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
            previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
            first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
            last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
          }
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
      // Expose DataTable instance globally for reload
      window.dt_basic = dt_basic;
      let deleteRow = null;
      let deleteJobId = null;
      document.addEventListener('click', async function (e) {
        const deleteBtn = e.target.closest('.delete-record');
        if (deleteBtn) {
          deleteRow = deleteBtn.closest('tr');
          deleteJobId = deleteBtn.getAttribute('data-id');
          if (!deleteJobId) return alert('Job ID not found.');
          const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
          modal.show();
        }
      });
      document.getElementById('confirmDeleteBtn').addEventListener('click', async function () {
        if (!deleteRow || !deleteJobId) return;
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
        modal.hide();
        try {
          const response = await fetch(`/jobs/${deleteJobId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          if (response.ok) {
            dt_basic.row(deleteRow).remove().draw();

            const modalEl = document.querySelector('.dtr-bs-modal');
            if (modalEl && modalEl.classList.contains('show')) {
              const modal = bootstrap.Modal.getInstance(modalEl);
              modal?.hide();
            }

            // ✅ Always trigger toast here
            showToast('Success', 'Job deleted successfully!', 'success');

            // Refresh DataTable if needed
            window.dt_basic.ajax.reload();
          } else {
            const error = await response.json();
            showToast('Error', 'Failed to delete the job.', 'error');
          }
        } catch (err) {
          console.error('Delete error:', err);
          showToast('Error', 'An error occurred while deleting.', 'error');
        }
        deleteRow = null;
        deleteJobId = null;
      });
    }
    setTimeout(() => {
      const elementsToModify = [
        { selector: '.dt-buttons .btn', classToRemove: 'btn-secondary' },
        //{ selector: '.dt-search .form-control', classToRemove: 'form-control-sm', classToAdd: 'ms-4' },
        { selector: '.dt-length .form-select', classToRemove: 'form-select-sm' },
        { selector: '.dt-layout-table', classToRemove: 'row mt-2' },
        { selector: '.dt-layout-end', classToAdd: 'mt-0' },
        //{ selector: '.dt-layout-end .dt-search', classToAdd: 'mt-0 mt-md-6 mb-6' },
        { selector: '.dt-layout-start', classToAdd: 'mt-0' },
        { selector: '.dt-layout-end .dt-buttons', classToAdd: 'mb-0' },
        { selector: '.dt-layout-full', classToRemove: 'col-md col-12', classToAdd: 'table-responsive' }
      ];
      elementsToModify.forEach(({ selector, classToRemove, classToAdd }) => {
        document.querySelectorAll(selector).forEach(element => {
          if (classToRemove) {
            classToRemove.split(' ').forEach(className => element.classList.remove(className));
          }
          if (classToAdd) {
            classToAdd.split(' ').forEach(className => element.classList.add(className));
          }
        });
      });
    }, 100);

    // AJAX store for job form
    const jobForm = document.getElementById('jobForm');
    if (jobForm) {
      jobForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(jobForm);
        jobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        fetch('/jobs', {
          method: 'POST',
          contentType: 'application/json',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
          if (status === 200 || status === 201) {
            showToast('Success',  'Job created successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('createJobModal'));
            modal.hide();
            jobForm.reset();
            window.dt_basic.ajax.reload();
          } else if (status === 422 && body.errors) {
            Object.keys(body.errors).forEach(field => {
              const input = jobForm.querySelector(`[name="${field}"]`);
              if (input) input.classList.add('is-invalid');
            });
            const errorMessages = Object.values(body.errors).flat().join('<br>');
            showToast('Error', 'Please fix the errors in the form.' + errorMessages, 'error');
          } else {
            showToast('Error', 'An error occurred while creating the job.', 'error');
          }
        })
        .catch(() => {
          showToast('Error', 'Network error. Please try again.', 'error');
        });
      });
    }

    //edit Job fetch
    const editJobForm = document.getElementById('EditJobForm');
    if (editJobForm) {
      let currentJobId = null;
      document.addEventListener('click', async function (e) {
        const editBtn = e.target.closest('.item-edit');
        if (editBtn) {
          const row = editBtn.closest('tr');
          const rowData = window.dt_basic.row(row).data();
          currentJobId = rowData.id;

          try {
            const response = await fetch(`/jobs/${currentJobId}/edit`, {
              method: 'GET',
              headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            });

            if (response.ok) {
              const jobData = await response.json();
              const data = jobData.data;

              // Clear any existing validation errors
              editJobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

              // Helper function to safely set field value
              function setFieldValue(selector, value) {
                const field = editJobForm.querySelector(selector);
                if (field) {
                  field.value = value || '';
                } else {
                  console.warn(`Field not found: ${selector}`);
                }
              }

              // Populate form fields with null checks
              setFieldValue('#edit_bail_number', data.bail_number_id);
              setFieldValue('#edit_container', data.container);
              setFieldValue('#edit_company_id', data.company_id);
              setFieldValue('#edit_size', data.size);
              setFieldValue('#edit_line_id', data.line_id);
              setFieldValue('#edit_port', data.port_id);
              setFieldValue('#edit_forwarder', data.forwarder_id);
              setFieldValue('#edit_noc_deadline', data.noc_deadline);
              setFieldValue('#edit_eta', data.eta);
                // Set mode radio buttons for containers[0][mode]
                if (data.mode) {
                const modeValue = data.mode;
                const modeInputs = editJobForm.querySelectorAll('input[name="containers[0][mode]"]');
                modeInputs.forEach(input => {
                  input.checked = input.value === modeValue;
                });
                }
              setFieldValue('#edit_status', data.status);

              // Trigger flatpickr revalidation for date fields
              const dateInputs = editJobForm.querySelectorAll('.vuexy_date');
              dateInputs.forEach(function (input) {
                if (input._flatpickr && input.value) {
                  input._flatpickr.setDate(input.value, false);
                }
              });

              // Show the modal
              const modal = new bootstrap.Modal(document.getElementById('editJobModal'));
              modal.show();

            } else {
              const error = await response.json();
              showToast('Error', error.message || 'Failed to fetch job details.', 'error');
            }
          } catch (err) {
            console.error('Fetch error:', err);
            showToast('Error', 'An error occurred while fetching job details.', 'error');
          }
        }
      });

      editJobForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!currentJobId) {
          return showToast('Error', 'No job selected for update.', 'error');
        }

        const formData = new FormData(editJobForm);
        formData.append('_method', 'PUT');

        // Clear previous validation errors
        editJobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Show loading state on submit button with null check
        const submitBtn = editJobForm.querySelector('button[type="submit"]') ||
                         editJobForm.querySelector('.btn-primary') ||
                         document.querySelector('#editJobModal button[type="submit"]');

        let originalText = '';
        if (submitBtn) {
          originalText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Updating...';
        }

        fetch(`/jobs/${currentJobId}`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: formData
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
          if (status === 200) {
            showToast('Success', body.message || 'Job updated successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editJobModal'));
            modal.hide();
            editJobForm.reset();
            window.dt_basic.ajax.reload();
            currentJobId = null;
          } else if (status === 422 && body.errors) {
            // Handle validation errors
            Object.keys(body.errors).forEach(field => {
              const input = editJobForm.querySelector(`[name="${field}"]`);
              if (input) {
                input.classList.add('is-invalid');
                // Find or create error feedback element
                let feedback = input.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                  feedback = document.createElement('div');
                  feedback.classList.add('invalid-feedback');
                  input.parentNode.appendChild(feedback);
                }
                feedback.textContent = body.errors[field][0];
              }
            });
            const errorMessages = Object.values(body.errors).flat().join('<br>');
            showToast('Error', 'Please fix the errors in the form.<br>' + errorMessages, 'error');
          } else {
            showToast('Error', body.message || 'An error occurred while updating the job.', 'error');
          }
        })
        .catch((err) => {
          console.error('Update error:', err);
          showToast('Error', 'Network error. Please try again.', 'error');
        })
        .finally(() => {
          // Reset button state with null check
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        });
      });

      // Reset form when modal is hidden
      document.getElementById('editJobModal').addEventListener('hidden.bs.modal', function () {
        editJobForm.reset();
        editJobForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        editJobForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        currentJobId = null;
      });
    }

    // Open comments modal when comments button is clicked
    document.addEventListener('click', function (e) {
      const commentsBtn = e.target.closest('.add-comments-btn');
      if (commentsBtn) {
        const jobQueueId = commentsBtn.dataset.jobQueueId;
        const status = commentsBtn.dataset.status;

        // Set modal title and hidden inputs
        document.getElementById('statusLabel').textContent = status;
        document.getElementById('jobId').value = jobQueueId;
        document.getElementById('status').value = status;

        // Show modal
        const commentsModal = new bootstrap.Modal(document.getElementById('commentsModal'));
        commentsModal.show();
      }
    });


    // Handle add comment form submission
    document.getElementById('addCommentForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch('/job-comments', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showToast('Success', 'Comment added successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('commentsModal'));
            modal.hide();
            // Clear comment input
            document.getElementById('comment').value = '';
          } else {
            alert('Failed to add comment.');
          }
        })
        .catch(error => console.error('Error:', error));
    });

  });
