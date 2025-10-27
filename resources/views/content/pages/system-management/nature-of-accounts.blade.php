

@extends('layouts/layoutMaster')

@section('title', 'Nature of Accounts - System Management')

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
<!-- Nature of Accounts Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Nature of Accounts</h4>
      <p class="card-subtitle mb-0">Manage chart of accounts and account classifications</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New Account</span>
    </button>
  </div>
  <div class="card-datatable table-responsive pt-0">
    <table class="datatables-accounts table">
      <thead>
        <tr>
          <th></th>
          <th>S.No</th>
          <th>Code</th>
          <th>Title</th>
          <th>Type</th>
          <th>Status</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Add New Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-calculator me-2"></i>
          Add New Account
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addAccountForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="accountTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <input type="text" class="form-control" id="accountTitle" name="title"
                       placeholder="e.g., Transportation Revenue" required>
                <div class="invalid-feedback">Please provide a valid account title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="accountCode" class="form-label">Code Start From <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-hash"></i></span>
                <input type="number" class="form-control" id="accountCode" name="code"
                       placeholder="e.g., 1001" min="1000" max="9999" required>
                <div class="invalid-feedback">Please provide a valid account code (1000-9999).</div>
              </div>
              <div class="form-text">Account code must be between 1000-9999</div>
            </div>

            <div class="col-md-6">
              <label for="accountType" class="form-label">Account Type <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-category"></i></span>
                <select class="form-select" id="accountType" name="type" required>
                  <option value="">Select Account Type</option>
                  <option value="asset">Asset</option>
                  <option value="liability">Liability</option>
                  <option value="equity">Equity</option>
                  <option value="revenue">Revenue</option>
                  <option value="expense">Expense</option>
                </select>
                <div class="invalid-feedback">Please select an account type.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="accountDescription" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-notes"></i></span>
                <input type="text" class="form-control" id="accountDescription" name="description"
                       placeholder="Optional description">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Account
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Account Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Account
        </h5>
        <div class="d-flex align-items-center gap-3">
          <span id="editAccountStatus" class="badge"></span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <form id="editAccountForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editAccountId" name="editAccountId">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="editAccountTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-file-text"></i></span>
                <input type="text" class="form-control" id="editAccountTitle" name="title"
                       placeholder="e.g., Transportation Revenue" required>
                <div class="invalid-feedback">Please provide a valid account title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editAccountCode" class="form-label">Code Start From <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-hash"></i></span>
                <input type="number" class="form-control" id="editAccountCode" name="code"
                       placeholder="e.g., 1001" min="1000" max="9999" required>
                <div class="invalid-feedback">Please provide a valid account code (1000-9999).</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editAccountType" class="form-label">Account Type <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-category"></i></span>
                <select class="form-select" id="editAccountType" name="type" required>
                  <option value="">Select Account Type</option>
                  <option value="asset">Asset</option>
                  <option value="liability">Liability</option>
                  <option value="equity">Equity</option>
                  <option value="revenue">Revenue</option>
                  <option value="expense">Expense</option>
                </select>
                <div class="invalid-feedback">Please select an account type.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editAccountDescription" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-notes"></i></span>
                <input type="text" class="form-control" id="editAccountDescription" name="description"
                       placeholder="Optional description">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Account
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Account Modal -->
<div class="modal fade" id="viewAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Account Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Basic Information -->
          <div class="col-12">
            <div class="card border-0">
              <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-info-circle me-2"></i>
                  Basic Information
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Account Code</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-hash me-2 text-primary"></i>
                      <span id="viewAccountCode" class="fw-medium badge font-monospace">-</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Status</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-circle-check me-2"></i>
                      <span id="viewAccountStatus" class="badge">-</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Account Details -->
          <div class="col-12">
            <div class="card border-0">
              <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-calculator me-2"></i>
                  Account Details
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label fw-medium text-muted mb-1">Title</label>
                    <div class="d-flex align-items-center p-3 bg-white rounded border">
                      <i id="viewAccountIcon" class="ti tabler-file-text me-3 text-primary fs-4"></i>
                      <div>
                        <div id="viewAccountTitle" class="fw-medium fs-5 mb-0">-</div>
                        <small class="text-muted">Account Name</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Account Type</label>
                    <div class="d-flex align-items-center">
                      <i id="viewAccountTypeIcon" class="ti tabler-category me-2 text-info"></i>
                      <span id="viewAccountType" class="badge">-</span>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Description</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-notes me-2 text-muted"></i>
                      <span id="viewAccountDescription" class="fw-medium">-</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Timestamps -->
          <div class="col-12">
            <div class="card border-0">
              <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="card-title mb-2 border-bottom pb-2">
                  <i class="ti tabler-clock me-2"></i>
                  Timestamps
                </h6>
              </div>
              <div class="card-body pt-2">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Created Date</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-calendar-plus me-2 text-success"></i>
                      <div>
                        <div id="viewAccountCreatedDate" class="fw-medium">-</div>
                        <small id="viewAccountCreatedTime" class="text-muted">-</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium text-muted mb-1">Last Updated</label>
                    <div class="d-flex align-items-center">
                      <i class="ti tabler-calendar-event me-2 text-info"></i>
                      <div>
                        <div id="viewAccountUpdatedDate" class="fw-medium">-</div>
                        <small id="viewAccountUpdatedTime" class="text-muted">-</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-outline-primary" id="editFromView">
            <i class="ti tabler-edit me-1"></i>
            Edit
          </button>
          <button type="button" class="btn btn-outline-warning" id="toggleFromView">
            <i class="ti tabler-toggle-right me-1"></i>
            Toggle Status
          </button>
          <button type="button" class="btn btn-outline-danger" id="deleteFromView">
            <i class="ti tabler-trash me-1"></i>
            Delete
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ti tabler-x me-1"></i>
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Base Toast Component -->
<x-toast-container />

<!-- Page Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Ensure ToastManager is available - Fallback implementation
    if (typeof window.ToastManager === 'undefined') {
      console.warn('ToastManager not found, creating fallback...');

      // Create toast container if it doesn't exist
      if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '1060';
        document.body.appendChild(toastContainer);
      }

      // Fallback ToastManager implementation
      window.ToastManager = {
        show: function(title, message, type = 'info') {
          const toastId = 'toast-' + Date.now();
          const iconMap = {
            success: 'ti tabler-check',
            error: 'ti tabler-x',
            warning: 'ti tabler-alert-triangle',
            info: 'ti tabler-info-circle'
          };
          const colorMap = {
            success: 'text-bg-success',
            error: 'text-bg-danger',
            warning: 'text-bg-warning',
            info: 'text-bg-info'
          };

          const toastHtml = `
            <div id="${toastId}" class="toast ${colorMap[type] || colorMap.info}" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <i class="${iconMap[type] || iconMap.info} me-2"></i>
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body">${message}</div>
            </div>
          `;

          const container = document.getElementById('toast-container');
          container.insertAdjacentHTML('beforeend', toastHtml);

          const toastElement = document.getElementById(toastId);
          const toast = new bootstrap.Toast(toastElement, { delay: 5000 });
          toast.show();

          toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
          });
        },
        success: function(title, message) { this.show(title, message, 'success'); },
        error: function(title, message) { this.show(title, message, 'error'); },
        warning: function(title, message) { this.show(title, message, 'warning'); },
        info: function(title, message) { this.show(title, message, 'info'); }
      };
    }

    let accountsData = [];
    let dt_accounts;

    // Initialize DataTable
    const dt_accounts_table = document.querySelector('.datatables-accounts');

    if (dt_accounts_table) {
      dt_accounts = new DataTable(dt_accounts_table, {
        data: [],
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
            render: function(data, type, full, meta) {
              return meta.row + 1; // S.No instead of ID
            }
          },
          {
            data: 'code',
            render: function(data, type, full) {
              const typeColors = {
                asset: 'success',
                liability: 'danger',
                equity: 'info',
                revenue: 'primary',
                expense: 'warning'
              };
              const color = typeColors[full.type] || 'secondary';
              return `<span class="badge bg-label-${color} font-monospace">${data}</span>`;
            }
          },
          {
            data: 'title',
            responsivePriority: 1,
            render: function(data, type, full) {
              return `<div class="d-flex flex-column">
                        <span class="fw-medium">${data}</span>
                        <small class="text-muted">${full.description || 'No description'}</small>
                      </div>`;
            }
          },
          {
            data: 'type',
            render: function(data) {
              const typeConfig = {
                asset: { class: 'success', text: 'Asset', icon: 'building-bank' },
                liability: { class: 'danger', text: 'Liability', icon: 'credit-card' },
                equity: { class: 'info', text: 'Equity', icon: 'chart-pie' },
                revenue: { class: 'primary', text: 'Revenue', icon: 'trending-up' },
                expense: { class: 'warning', text: 'Expense', icon: 'trending-down' }
              };
              const config = typeConfig[data] || typeConfig.asset;
              return `<span class="badge bg-label-${config.class}">
                        <i class="ti tabler-${config.icon} me-1"></i>${config.text}
                      </span>`;
            }
          },
          {
            data: 'status',
            render: function(data) {
              const statusConfig = {
                active: { class: 'success', text: 'Active' },
                inactive: { class: 'secondary', text: 'Inactive' }
              };
              const config = statusConfig[data] || statusConfig.active;
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
                  <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-account" title="View" data-id="${full.id}">
                    <i class="ti tabler-eye"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-account" title="Edit" data-id="${full.id}">
                    <i class="ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}" data-id="${full.id}" data-status="${full.status}">
                    <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
                  </button>
                  <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-account" title="Delete" data-id="${full.id}">
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
        order: [[2, 'asc']],
        layout: {

          topEnd: {
            search: {
              placeholder: 'Search accounts...'
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

    // Load accounts data from API
    function loadAccounts() {
      fetch('/nature-of-accounts', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json', // important
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            accountsData = data.data;
            dt_accounts.clear().rows.add(accountsData).draw();
          } else {
            ToastManager.show('Error', data.message || 'Failed to load accounts', 'error');
          }
        })
        .catch(error => {
          console.error('Error loading accounts:', error);
          ToastManager.show('Error', 'Failed to load accounts', 'error');
        });
    }

    // Auto-suggest next available code
    function getNextAvailableCode() {
      if (accountsData.length === 0) return 1000;

      const existingCodes = accountsData.map(account => account.code);
      let nextCode = Math.max(...existingCodes) + 1;

      // Ensure it's at least 1000
      if (nextCode < 1000) {
        nextCode = 1000;
      }

      return nextCode;
    }

    // Set next available code when modal opens
    document.getElementById('addAccountModal').addEventListener('show.bs.modal', function() {
      document.getElementById('accountCode').value = getNextAvailableCode();
    });

    // Add Account Form Submission
    const addAccountForm = document.getElementById('addAccountForm');
    if (addAccountForm) {
      addAccountForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (addAccountForm.checkValidity()) {
          const formData = new FormData(addAccountForm);
          const accountData = {
            title: formData.get('title'),
            code: formData.get('code'),
            type: formData.get('type'),
            description: formData.get('description')
          };

          fetch('/nature-of-accounts', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(accountData)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('addAccountModal')).hide();
              addAccountForm.reset();
              addAccountForm.classList.remove('was-validated');

              // Reload data
              loadAccounts();

              ToastManager.show('Success', data.message, 'success');
            } else {
              if (data.errors) {
                // Show validation errors
                Object.keys(data.errors).forEach(field => {
                  const input = addAccountForm.querySelector(`[name="${field}"]`);
                  if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.parentNode.querySelector('.invalid-feedback');
                    if (feedback) {
                      feedback.textContent = data.errors[field][0];
                    }
                  }
                });
              } else {
                ToastManager.show('Error', data.message || 'Failed to create account', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error creating account:', error);
            ToastManager.show('Error', 'Failed to create account', 'error');
          });
        }

        addAccountForm.classList.add('was-validated');
      });
    }

    // Edit Account Form Submission
    const editAccountForm = document.getElementById('editAccountForm');
    if (editAccountForm) {
      editAccountForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (editAccountForm.checkValidity()) {
          const formData = new FormData(editAccountForm);
          const accountId = formData.get('editAccountId');
          const accountData = {
            title: formData.get('title'),
            code: formData.get('code'),
            type: formData.get('type'),
            description: formData.get('description')
          };

          fetch(`/nature-of-accounts/${accountId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(accountData)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Close modal and reset form
              bootstrap.Modal.getInstance(document.getElementById('editAccountModal')).hide();
              editAccountForm.reset();
              editAccountForm.classList.remove('was-validated');

              // Reload data
              loadAccounts();

              ToastManager.show('Success', data.message, 'success');
            } else {
              if (data.errors) {
                // Show validation errors
                Object.keys(data.errors).forEach(field => {
                  const input = editAccountForm.querySelector(`[name="${field}"]`);
                  if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.parentNode.querySelector('.invalid-feedback');
                    if (feedback) {
                      feedback.textContent = data.errors[field][0];
                    }
                  }
                });
              } else {
                ToastManager.show('Error', data.message || 'Failed to update account', 'error');
              }
            }
          })
          .catch(error => {
            console.error('Error updating account:', error);
            ToastManager.show('Error', 'Failed to update account', 'error');
          });
        }

        editAccountForm.classList.add('was-validated');
      });
    }

    // Event delegation for action buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.edit-account')) {
        const accountId = parseInt(e.target.closest('.edit-account').dataset.id);
        const account = accountsData.find(a => a.id === accountId);

        if (account) {
          // Populate edit form
          document.getElementById('editAccountId').value = account.id;
          document.getElementById('editAccountTitle').value = account.title;
          document.getElementById('editAccountCode').value = account.code;
          document.getElementById('editAccountType').value = account.type;
          document.getElementById('editAccountDescription').value = account.description || '';

          // Update status badge in header
          const statusBadge = document.getElementById('editAccountStatus');
          const statusConfig = {
            active: { class: 'bg-label-success', text: 'Active' },
            inactive: { class: 'bg-label-secondary', text: 'Inactive' }
          };
          const config = statusConfig[account.status] || statusConfig.active;
          statusBadge.className = `badge ${config.class}`;
          statusBadge.textContent = config.text;

          // Show edit modal
          const editModal = new bootstrap.Modal(document.getElementById('editAccountModal'));
          editModal.show();
        }
      }

      if (e.target.closest('.view-account')) {
        const accountId = parseInt(e.target.closest('.view-account').dataset.id);
        const account = accountsData.find(a => a.id === accountId);

        if (account) {
          // Populate view modal
          populateViewModal(account);

          // Show view modal
          const viewModal = new bootstrap.Modal(document.getElementById('viewAccountModal'));
          viewModal.show();
        }
      }

      if (e.target.closest('.toggle-status')) {
        const accountId = parseInt(e.target.closest('.toggle-status').dataset.id);

        fetch(`/nature-of-accounts/${accountId}/toggle`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            loadAccounts();
            ToastManager.show('Success', data.message, 'success');
          } else {
            ToastManager.show('Error', data.message || 'Failed to toggle status', 'error');
          }
        })
        .catch(error => {
          console.error('Error toggling status:', error);
          ToastManager.show('Error', 'Failed to toggle status', 'error');
        });
      }

      if (e.target.closest('.delete-account')) {
        const accountId = parseInt(e.target.closest('.delete-account').dataset.id);

        if (confirm('Are you sure you want to delete this account?')) {
          fetch(`/nature-of-accounts/${accountId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              loadAccounts();
              ToastManager.show('Success', data.message, 'success');
            } else {
              ToastManager.show('Error', data.message || 'Failed to delete account', 'error');
            }
          })
          .catch(error => {
            console.error('Error deleting account:', error);
            ToastManager.show('Error', 'Failed to delete account', 'error');
          });
        }
      }
    });

    // Function to populate view modal
    function populateViewModal(account) {
      // Basic Information
      document.getElementById('viewAccountCode').textContent = account.code;

      // Update status badge
      const statusConfig = {
        active: { class: 'bg-label-success', text: 'Active' },
        inactive: { class: 'bg-label-secondary', text: 'Inactive' }
      };
      const statusElement = document.getElementById('viewAccountStatus');
      const statusConf = statusConfig[account.status] || statusConfig.active;
      statusElement.className = `badge ${statusConf.class}`;
      statusElement.textContent = statusConf.text;

      // Account code badge color based on type
      const typeColors = {
        asset: 'success',
        liability: 'danger',
        equity: 'info',
        revenue: 'primary',
        expense: 'warning'
      };
      const codeElement = document.getElementById('viewAccountCode');
      const color = typeColors[account.type] || 'secondary';
      codeElement.className = `fw-medium badge bg-label-${color} font-monospace`;

      // Account Details
      document.getElementById('viewAccountTitle').textContent = account.title;

      // Account Type with icon
      const typeConfig = {
        asset: { class: 'success', text: 'Asset', icon: 'building-bank' },
        liability: { class: 'danger', text: 'Liability', icon: 'credit-card' },
        equity: { class: 'info', text: 'Equity', icon: 'chart-pie' },
        revenue: { class: 'primary', text: 'Revenue', icon: 'trending-up' },
        expense: { class: 'warning', text: 'Expense', icon: 'trending-down' }
      };
      const typeConf = typeConfig[account.type] || typeConfig.asset;

      const typeIconElement = document.getElementById('viewAccountTypeIcon');
      typeIconElement.className = `ti tabler-${typeConf.icon} me-2 text-${typeConf.class}`;

      const typeElement = document.getElementById('viewAccountType');
      typeElement.className = `badge bg-label-${typeConf.class}`;
      typeElement.textContent = typeConf.text;

      // Update main icon based on account type
      const mainIconElement = document.getElementById('viewAccountIcon');
      mainIconElement.className = `ti tabler-${typeConf.icon} me-3 text-${typeConf.class} fs-4`;

      // Description
      document.getElementById('viewAccountDescription').textContent = account.description || 'No description provided';

      // Timestamps
      const createdDate = new Date(account.created_at);
      document.getElementById('viewAccountCreatedDate').textContent = createdDate.toLocaleDateString();
      document.getElementById('viewAccountCreatedTime').textContent = createdDate.toLocaleTimeString();

      const updatedDate = new Date(account.updated_at);
      document.getElementById('viewAccountUpdatedDate').textContent = updatedDate.toLocaleDateString();
      document.getElementById('viewAccountUpdatedTime').textContent = updatedDate.toLocaleTimeString();

      // Update action buttons with current account data
      document.getElementById('editFromView').dataset.accountId = account.id;
      document.getElementById('toggleFromView').dataset.accountId = account.id;
      document.getElementById('toggleFromView').dataset.currentStatus = account.status;
      document.getElementById('deleteFromView').dataset.accountId = account.id;

      // Update toggle button text and icon
      const toggleBtn = document.getElementById('toggleFromView');
      if (account.status === 'active') {
        toggleBtn.innerHTML = '<i class="ti tabler-toggle-left me-1"></i>Deactivate';
        toggleBtn.className = 'btn btn-outline-warning';
      } else {
        toggleBtn.innerHTML = '<i class="ti tabler-toggle-right me-1"></i>Activate';
        toggleBtn.className = 'btn btn-outline-success';
      }
    }

    // View modal action handlers
    document.getElementById('editFromView').addEventListener('click', function() {
      const accountId = parseInt(this.dataset.accountId);
      const account = accountsData.find(a => a.id === accountId);

      if (account) {
        // Close view modal
        bootstrap.Modal.getInstance(document.getElementById('viewAccountModal')).hide();

        // Populate and show edit modal
        document.getElementById('editAccountId').value = account.id;
        document.getElementById('editAccountTitle').value = account.title;
        document.getElementById('editAccountCode').value = account.code;
        document.getElementById('editAccountType').value = account.type;
        document.getElementById('editAccountDescription').value = account.description || '';

        // Update status badge in edit modal header
        const statusBadge = document.getElementById('editAccountStatus');
        const statusConfig = {
          active: { class: 'bg-label-success', text: 'Active' },
          inactive: { class: 'bg-label-secondary', text: 'Inactive' }
        };
        const config = statusConfig[account.status] || statusConfig.active;
        statusBadge.className = `badge ${config.class}`;
        statusBadge.textContent = config.text;

        // Show edit modal
        const editModal = new bootstrap.Modal(document.getElementById('editAccountModal'));
        editModal.show();
      }
    });

    document.getElementById('toggleFromView').addEventListener('click', function() {
      const accountId = parseInt(this.dataset.accountId);

      // Close view modal first
      bootstrap.Modal.getInstance(document.getElementById('viewAccountModal')).hide();

      fetch(`/nature-of-accounts/${accountId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          loadAccounts();
          ToastManager.show('Success', data.message, 'success');
        } else {
          ToastManager.show('Error', data.message || 'Failed to toggle status', 'error');
        }
      })
      .catch(error => {
        console.error('Error toggling status:', error);
        ToastManager.show('Error', 'Failed to toggle status', 'error');
      });
    });

    document.getElementById('deleteFromView').addEventListener('click', function() {
      const accountId = parseInt(this.dataset.accountId);

      if (confirm('Are you sure you want to delete this account?')) {
        // Close view modal first
        bootstrap.Modal.getInstance(document.getElementById('viewAccountModal')).hide();

        fetch(`/nature-of-accounts/${accountId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            loadAccounts();
            ToastManager.show('Success', data.message, 'success');
          } else {
            ToastManager.show('Error', data.message || 'Failed to delete account', 'error');
          }
        })
        .catch(error => {
          console.error('Error deleting account:', error);
          ToastManager.show('Error', 'Failed to delete account', 'error');
        });
      }
    });

    // Load accounts on page load
    loadAccounts();
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
