@extends('layouts/layoutMaster')

@section('title', 'Parties - System Management')

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
<!-- Parties Management -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="card-title mb-1">Parties <span class="d-none d-sm-inline">Management</span></h4>
      <p class="card-subtitle mb-0">Manage customers and vendors <span class="d-none d-sm-inline">for transportation services</span></p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartyModal">
      <i class="ti tabler-plus me-1"></i>
      <span class="d-none d-sm-inline">Add New Party</span>
    </button>
  </div>

  <!-- Nav Pills -->
  <div class="card-body">
    <div class="nav-align-top nav-tabs-shadow">
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#customers-tab" aria-controls="customers-tab" aria-selected="true">
          <i class="ti tabler-users me-1"></i>
          Customers
          <span class="badge bg-primary ms-1" id="customers-count">0</span>
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#vendors-tab" aria-controls="vendors-tab" aria-selected="false">
          <i class="ti tabler-building-store me-1"></i>
          Vendors
          <span class="badge bg-primary ms-1" id="vendors-count">0</span>
        </button>
      </li>
    </ul>
    </div>
  </div>

  <!-- Tab Content -->
  <div class="tab-content">
    <!-- Customers Tab -->
    <div class="tab-pane fade show active" id="customers-tab" role="tabpanel">
      <div class="card-datatable table-responsive pt-0">
        <table class="datatables-customers table">
          <thead>
            <tr>
              <th></th>
              <th>S.No</th>
              <th>Customer</th>
              <th>Contact</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

    <!-- Vendors Tab -->
    <div class="tab-pane fade" id="vendors-tab" role="tabpanel">
      <div class="card-datatable table-responsive pt-0">
        <table class="datatables-vendors table">
          <thead>
            <tr>
              <th></th>
              <th>S.No</th>
              <th>Vendor</th>
              <th>Contact</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add New Party Modal -->
<div class="modal fade" id="addPartyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-user-plus me-2"></i>
          Add New Party
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addPartyForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <div class="row g-3">
            <!-- Party Type Selection -->
            <div class="col-12">
              <label class="form-label">Party Type<span class="text-danger">*</span>:</label>
              <div class="form-check-inline ms-2">
                <input class="form-check-input" type="radio" name="party_type" id="customerType" value="customer" required>
                <label class="form-check-label me-3" for="customerType">
                  <i class="ti tabler-users me-1"></i>Customer
                </label>
                <input class="form-check-input" type="radio" name="party_type" id="vendorType" value="vendor" required>
                <label class="form-check-label" for="vendorType">
                  <i class="ti tabler-building-store me-1"></i>Vendor
                </label>
              </div>
              <div class="invalid-feedback">Please select a party type.</div>
            </div>

            <div class="col-md-6">
              <label for="partyTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="partyTitle" name="title"
                       placeholder="e.g., ABC Logistics Company" required>
                <div class="invalid-feedback">Please provide a valid party title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="partyShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="partyShortName" name="short_name"
                       placeholder="e.g., ABC" maxlength="15" required>
                <div class="invalid-feedback">Please provide a valid short name (max 15 characters).</div>
              </div>
              <div class="form-text">Maximum 15 characters for easy identification</div>
            </div>

            <div class="col-md-6">
              <label for="partyContact" class="form-label">Contact <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-phone"></i></span>
                <input type="tel" class="form-control" id="partyContact" name="contact"
                       placeholder="e.g., +92-21-1234567" required>
                <div class="invalid-feedback">Please provide a valid contact number.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="partyEmail" class="form-label">Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-mail"></i></span>
                <input type="email" class="form-control" id="partyEmail" name="email"
                       placeholder="e.g., info@abc.com" required>
                <div class="invalid-feedback">Please provide a valid email address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="partyContactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-user"></i></span>
                <input type="text" class="form-control" id="partyContactPerson" name="contact_person"
                       placeholder="e.g., John Smith" required>
                <div class="invalid-feedback">Please provide a valid contact person name.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="partyNTN" class="form-label">NTN <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-id"></i></span>
                <input type="text" class="form-control" id="partyNTN" name="ntn"
                       placeholder="e.g., 1234567-8" required>
                <div class="invalid-feedback">Please provide a valid NTN number.</div>
              </div>
            </div>

            <div class="col-12">
              <label for="partyAddress" class="form-label">Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <textarea class="form-control" id="partyAddress" name="address"
                          rows="3" placeholder="Enter complete business address" required></textarea>
                <div class="invalid-feedback">Please provide a valid address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="partyBankName" class="form-label">Bank Name</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building-bank"></i></span>
                <input type="text" class="form-control" id="partyBankName" name="bank_name"
                       placeholder="e.g., National Bank of Pakistan">
              </div>
            </div>

            <div class="col-md-6">
              <label for="partyIBAN" class="form-label">IBAN</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-credit-card"></i></span>
                <input type="text" class="form-control" id="partyIBAN" name="iban"
                       placeholder="e.g., PK36SCBL0000001123456702">
              </div>
              <div class="form-text">International Bank Account Number</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Save Party
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Party Modal -->
<div class="modal fade" id="editPartyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-edit me-2"></i>
          Edit Party
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editPartyForm" class="needs-validation" novalidate>
        <div class="modal-body">
          <input type="hidden" id="editPartyId" name="id">
          <div class="row g-3">
            <!-- Party Type Selection -->
            <div class="col-12">
              <label class="form-label">Party Type <span class="text-danger">*</span></label>
              <div class="form-check-inline">
                <input class="form-check-input" type="radio" name="party_type" id="editCustomerType" value="customer" required>
                <label class="form-check-label me-3" for="editCustomerType">
                  <i class="ti tabler-users me-1"></i>Customer
                </label>
                <input class="form-check-input" type="radio" name="party_type" id="editVendorType" value="vendor" required>
                <label class="form-check-label" for="editVendorType">
                  <i class="ti tabler-building-store me-1"></i>Vendor
                </label>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyTitle" class="form-label">Title <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building"></i></span>
                <input type="text" class="form-control" id="editPartyTitle" name="title"
                       placeholder="e.g., ABC Logistics Company" required>
                <div class="invalid-feedback">Please provide a valid party title.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyShortName" class="form-label">Short Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-tag"></i></span>
                <input type="text" class="form-control" id="editPartyShortName" name="short_name"
                       placeholder="e.g., ABC" maxlength="15" required>
                <div class="invalid-feedback">Please provide a valid short name (max 15 characters).</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyContact" class="form-label">Contact <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-phone"></i></span>
                <input type="tel" class="form-control" id="editPartyContact" name="contact"
                       placeholder="e.g., +92-21-1234567" required>
                <div class="invalid-feedback">Please provide a valid contact number.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyEmail" class="form-label">Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-mail"></i></span>
                <input type="email" class="form-control" id="editPartyEmail" name="email"
                       placeholder="e.g., info@abc.com" required>
                <div class="invalid-feedback">Please provide a valid email address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyContactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-user"></i></span>
                <input type="text" class="form-control" id="editPartyContactPerson" name="contact_person"
                       placeholder="e.g., John Smith" required>
                <div class="invalid-feedback">Please provide a valid contact person name.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyNTN" class="form-label">NTN <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-id"></i></span>
                <input type="text" class="form-control" id="editPartyNTN" name="ntn"
                       placeholder="e.g., 1234567-8" required>
                <div class="invalid-feedback">Please provide a valid NTN number.</div>
              </div>
            </div>

            <div class="col-12">
              <label for="editPartyAddress" class="form-label">Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-map-pin"></i></span>
                <textarea class="form-control" id="editPartyAddress" name="address"
                          rows="3" placeholder="Enter complete business address" required></textarea>
                <div class="invalid-feedback">Please provide a valid address.</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyBankName" class="form-label">Bank Name</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-building-bank"></i></span>
                <input type="text" class="form-control" id="editPartyBankName" name="bank_name"
                       placeholder="e.g., National Bank of Pakistan">
              </div>
            </div>

            <div class="col-md-6">
              <label for="editPartyIBAN" class="form-label">IBAN</label>
              <div class="input-group">
                <span class="input-group-text"><i class="ti tabler-credit-card"></i></span>
                <input type="text" class="form-control" id="editPartyIBAN" name="iban"
                       placeholder="e.g., PK36SCBL0000001123456702">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti tabler-device-floppy me-1"></i>
            Update Party
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Party Modal -->
<div class="modal fade" id="viewPartyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti tabler-eye me-2"></i>
          Party Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <!-- Party Header -->
          <div class="col-12">
            <div class="card bg-light">
              <div class="card-body text-center">
                <div class="avatar avatar-xl mx-auto mb-3">
                  <span class="avatar-initial rounded-circle bg-label-primary" id="viewPartyAvatar">
                    <i class="ti tabler-building fs-2"></i>
                  </span>
                </div>
                <h4 class="mb-1" id="viewPartyTitle">-</h4>
                <p class="text-muted mb-0">
                  <span class="badge bg-label-primary" id="viewPartyType">-</span>
                  <span class="badge bg-label-secondary ms-1" id="viewPartyStatus">-</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Basic Information -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-info-circle me-1"></i>
                  Basic Information
                </h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-medium">Short Name</label>
                  <p class="mb-0" id="viewPartyShortName">-</p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-medium">NTN</label>
                  <p class="mb-0 font-monospace" id="viewPartyNTN">-</p>
                </div>
                <div class="mb-0">
                  <label class="form-label fw-medium">Created Date</label>
                  <p class="mb-0" id="viewPartyCreated">-</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-phone me-1"></i>
                  Contact Information
                </h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-medium">Contact Person</label>
                  <p class="mb-0" id="viewPartyContactPerson">-</p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-medium">Phone</label>
                  <p class="mb-0">
                    <a href="#" id="viewPartyContact" class="text-decoration-none">-</a>
                  </p>
                </div>
                <div class="mb-0">
                  <label class="form-label fw-medium">Email</label>
                  <p class="mb-0">
                    <a href="#" id="viewPartyEmail" class="text-decoration-none">-</a>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Address Information -->
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-map-pin me-1"></i>
                  Address Information
                </h6>
              </div>
              <div class="card-body">
                <p class="mb-0" id="viewPartyAddress">-</p>
              </div>
            </div>
          </div>

          <!-- Banking Information -->
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title mb-0">
                  <i class="ti tabler-building-bank me-1"></i>
                  Banking Information
                </h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <label class="form-label fw-medium">Bank Name</label>
                    <p class="mb-0" id="viewPartyBankName">-</p>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-medium">IBAN</label>
                    <p class="mb-0 font-monospace" id="viewPartyIBAN">-</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" id="editFromView">
          <i class="ti tabler-edit me-1"></i>
          <span class="d-none d-sm-inline">Edit</span>
        </button>
        <button type="button" class="btn btn-outline-warning" id="toggleFromView">
          <i class="ti tabler-toggle-right me-1"></i>
          <span class="d-none d-sm-inline">Status</span>
        </button>
        <button type="button" class="btn btn-outline-success" id="duplicateFromView">
          <i class="ti tabler-copy me-1"></i>
          <span class="d-none d-sm-inline">Duplicate</span>
        </button>
        <button type="button" class="btn btn-outline-danger" id="deleteFromView">
          <i class="ti tabler-trash me-1"></i>
          <span class="d-none d-sm-inline">Delete</span>
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Toast Container -->
<x-toast-container />

<!-- Page Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  let dt_customers, dt_vendors;

  // Initialize DataTables
  const dt_customers_table = document.querySelector('.datatables-customers');
  const dt_vendors_table = document.querySelector('.datatables-vendors');

  if (dt_customers_table) {
    dt_customers = new DataTable(dt_customers_table, {
      ajax: {
        url: '/api/parties/type/customer',
        type: 'GET',
        dataSrc: function(json) {
          if (json.success) {
            updateCounts();
            return json.data;
          } else {
            showToast('Error', json.message || 'Failed to load customers', 'error');
            return [];
          }
        },
        error: function(xhr, error, thrown) {
          console.error('DataTable Error:', error);
          showToast('Error', 'Failed to load customers. Please try again.', 'error');
        }
      },
      columns: getTableColumns(),
      ...getTableConfig('customers')
    });
  }

  if (dt_vendors_table) {
    dt_vendors = new DataTable(dt_vendors_table, {
      ajax: {
        url: '/api/parties/type/vendor',
        type: 'GET',
        dataSrc: function(json) {
          if (json.success) {
            updateCounts();
            return json.data;
          } else {
            showToast('Error', json.message || 'Failed to load vendors', 'error');
            return [];
          }
        },
        error: function(xhr, error, thrown) {
          console.error('DataTable Error:', error);
          showToast('Error', 'Failed to load vendors. Please try again.', 'error');
        }
      },
      columns: getTableColumns(),
      ...getTableConfig('vendors')
    });
  }

  // Common table columns configuration
  function getTableColumns() {
    return [
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
        render: function(data, type, full) {
          const partyIcon = full.party_type === 'customer' ? 'users' : 'building-store';
          const partyColor = full.party_type === 'customer' ? 'primary' : 'success';

          return `<div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                      <span class="avatar-initial rounded-circle bg-label-${partyColor}">
                        <i class="ti tabler-${partyIcon}"></i>
                      </span>
                    </div>
                    <div class="d-flex flex-column">
                      <span class="fw-medium">${data}</span>
                      <small class="text-muted">${full.short_name}</small>
                    </div>
                  </div>`;
        }
      },
      {
        data: 'contact_person',
        render: function(data, type, full) {
          return `<div class="d-flex flex-column">
                    <span class="fw-medium">${data}</span>
                    <small class="text-muted">
                      <i class="ti tabler-phone me-1"></i>${full.contact}
                    </small>
                  </div>`;
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
        data: null,
        orderable: false,
        searchable: false,
        render: function(data, type, full) {
          return `
            <div class="d-flex gap-1">
              <button type="button" class="btn btn-icon btn-sm btn-outline-secondary view-party" data-id="${full.id}" title="View Details">
                <i class="ti tabler-eye"></i>
              </button>
              <button type="button" class="btn btn-icon btn-sm btn-outline-primary edit-party" data-id="${full.id}" title="Edit">
                <i class="ti tabler-edit"></i>
              </button>
              <button type="button" class="btn btn-icon btn-sm btn-outline-${full.status === 'active' ? 'warning' : 'success'} toggle-status" data-id="${full.id}" data-status="${full.status}" title="${full.status === 'active' ? 'Deactivate' : 'Activate'}">
                <i class="ti tabler-toggle-${full.status === 'active' ? 'right' : 'left'}"></i>
              </button>
              <button type="button" class="btn btn-icon btn-sm btn-outline-danger delete-party" data-id="${full.id}" title="Delete">
                <i class="ti tabler-trash"></i>
              </button>
            </div>
          `;
        }
      }
    ];
  }

  // Common table configuration
  function getTableConfig(type) {
    return {
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
            placeholder: `Search ${type}...`
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
    };
  }

  // Update counts from API
  function updateCounts() {
    fetch('/api/parties')
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const customers = data.data.filter(p => p.party_type === 'customer').length;
          const vendors = data.data.filter(p => p.party_type === 'vendor').length;

          document.getElementById('customers-count').textContent = customers;
          document.getElementById('vendors-count').textContent = vendors;
        }
      })
      .catch(error => {
        console.error('Error updating counts:', error);
      });
  }

  // Auto-generate short name from title
  document.getElementById('partyTitle').addEventListener('input', function() {
    const title = this.value;
    const shortName = title
      .split(' ')
      .map(word => word.charAt(0).toUpperCase())
      .join('')
      .substring(0, 15);
    document.getElementById('partyShortName').value = shortName;
  });

  // Display validation errors function
  function displayValidationErrors(errors) {
    const fieldLabels = {
      party_type: 'Party Type',
      title: 'Title',
      short_name: 'Short Name',
      address: 'Address',
      contact: 'Contact',
      email: 'Email',
      contact_person: 'Contact Person',
      bank_name: 'Bank Name',
      iban: 'IBAN',
      ntn: 'NTN'
    };

    let errorMessage = '';
    Object.keys(errors).forEach((key, index) => {
      const fieldLabel = fieldLabels[key] || key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
      const fieldErrors = errors[key];

      if (index > 0) errorMessage += '\n';
      errorMessage += `${fieldLabel}: ${fieldErrors.join(', ')}`;
    });

    // Use base toast component
    if (typeof window.showToast === 'function') {
      window.showToast('Validation Error', errorMessage, 'error');
    } else {
      alert('Validation Error:\n' + errorMessage);
    }
  }

  // Add Party Form Submission
  const addPartyForm = document.getElementById('addPartyForm');
  if (addPartyForm) {
    addPartyForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (addPartyForm.checkValidity()) {
        const formData = new FormData(addPartyForm);

        fetch('/api/parties', {
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
            bootstrap.Modal.getInstance(document.getElementById('addPartyModal')).hide();
            addPartyForm.reset();
            addPartyForm.classList.remove('was-validated');

            // Reload appropriate table
            if (data.data.party_type === 'customer') {
              dt_customers.ajax.reload();
            } else {
              dt_vendors.ajax.reload();
            }

            showToast('Success', data.message || 'Party added successfully!', 'success');
          } else {
            // Handle validation errors
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to add party', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while adding the party', 'error');
        });
      }

      addPartyForm.classList.add('was-validated');
    });
  }

  // Edit Party Form Submission
  const editPartyForm = document.getElementById('editPartyForm');
  if (editPartyForm) {
    editPartyForm.addEventListener('submit', function(e) {
      e.preventDefault();

      if (editPartyForm.checkValidity()) {
        const formData = new FormData(editPartyForm);
        const partyId = formData.get('id');

        formData.append('_method', 'PUT');

        fetch(`/api/parties/${partyId}`, {
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
            bootstrap.Modal.getInstance(document.getElementById('editPartyModal')).hide();
            editPartyForm.reset();
            editPartyForm.classList.remove('was-validated');

            // Reload both tables
            dt_customers.ajax.reload();
            dt_vendors.ajax.reload();

            showToast('Success', data.message || 'Party updated successfully!', 'success');
          } else {
            // Handle validation errors
            if (data.errors) {
              displayValidationErrors(data.errors);
            } else {
              showToast('Error', data.message || 'Failed to update party', 'error');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('Error', 'An error occurred while updating the party', 'error');
        });
      }

      editPartyForm.classList.add('was-validated');
    });
  }

  // Event delegation for action buttons
  document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-party')) {
      const partyId = e.target.closest('.edit-party').dataset.id;
      editParty(partyId);
    }

    if (e.target.closest('.view-party')) {
      const partyId = e.target.closest('.view-party').dataset.id;
      viewParty(partyId);
    }

    if (e.target.closest('.toggle-status')) {
      const partyId = e.target.closest('.toggle-status').dataset.id;
      const currentStatus = e.target.closest('.toggle-status').dataset.status;
      togglePartyStatus(partyId, currentStatus);
    }

    if (e.target.closest('.delete-party')) {
      const partyId = e.target.closest('.delete-party').dataset.id;
      deleteParty(partyId);
    }

    // Edit party from view modal
    if (e.target.closest('.edit-party-from-view') || e.target.closest('#editFromView')) {
      const partyId = e.target.closest('.edit-party-from-view')?.dataset.id ||
                      document.getElementById('viewPartyModal').getAttribute('data-party-id');
      // Close view modal
      bootstrap.Modal.getInstance(document.getElementById('viewPartyModal')).hide();
      // Open edit modal
      setTimeout(() => {
        editParty(partyId);
      }, 300);
    }

    // Toggle status from view modal
    if (e.target.closest('#toggleFromView')) {
      const partyId = document.getElementById('viewPartyModal').getAttribute('data-party-id');
      const currentStatus = getCurrentPartyStatus();
      if (partyId) {
        bootstrap.Modal.getInstance(document.getElementById('viewPartyModal')).hide();
        setTimeout(() => {
          togglePartyStatus(partyId, currentStatus);
        }, 300);
      }
    }

    // Duplicate party from view modal
    if (e.target.closest('#duplicateFromView')) {
      const partyId = document.getElementById('viewPartyModal').getAttribute('data-party-id');
      if (partyId) {
        bootstrap.Modal.getInstance(document.getElementById('viewPartyModal')).hide();
        setTimeout(() => {
          duplicateParty(partyId);
        }, 300);
      }
    }

    // Delete party from view modal
    if (e.target.closest('#deleteFromView')) {
      const partyId = document.getElementById('viewPartyModal').getAttribute('data-party-id');
      if (partyId) {
        bootstrap.Modal.getInstance(document.getElementById('viewPartyModal')).hide();
        setTimeout(() => {
          deleteParty(partyId);
        }, 300);
      }
    }
  });

  // Get current party status from view modal
  function getCurrentPartyStatus() {
    const viewModal = document.getElementById('viewPartyModal');
    return viewModal.getAttribute('data-party-status');
  }

  // Duplicate Party Function
  function duplicateParty(partyId) {
    fetch(`/api/parties/${partyId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const party = data.data;

          // Populate add form with existing data (excluding ID)
          document.getElementById('partyTitle').value = party.title + ' (Copy)';
          document.getElementById('partyShortName').value = party.short_name + 'C';
          document.getElementById('partyAddress').value = party.address;
          document.getElementById('partyContact').value = party.contact;
          document.getElementById('partyEmail').value = party.email;
          document.getElementById('partyContactPerson').value = party.contact_person;
          document.getElementById('partyBankName').value = party.bank_name || '';
          document.getElementById('partyIBAN').value = party.iban || '';
          document.getElementById('partyNTN').value = party.ntn;

          // Set party type
          if (party.party_type === 'customer') {
            document.getElementById('customerType').checked = true;
          } else {
            document.getElementById('vendorType').checked = true;
          }

          // Show add modal
          const addModal = new bootstrap.Modal(document.getElementById('addPartyModal'));
          addModal.show();

          showToast('Info', 'Party data loaded for duplication. Please review and save.', 'info');
        } else {
          showToast('Error', data.message || 'Failed to load party details for duplication', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading party details for duplication', 'error');
      });
  }

  // View Party Function
  function viewParty(partyId) {
    fetch(`/api/parties/${partyId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const party = data.data;

          // Set party type icon and color
          const partyIcon = party.party_type === 'customer' ? 'users' : 'building-store';
          const partyColor = party.party_type === 'customer' ? 'primary' : 'success';

          // Update modal content
          document.getElementById('viewPartyAvatar').innerHTML = `<i class="ti tabler-${partyIcon} fs-2"></i>`;
          document.getElementById('viewPartyAvatar').className = `avatar-initial rounded-circle bg-label-${partyColor}`;

          // Basic information
          document.getElementById('viewPartyTitle').textContent = party.title;
          document.getElementById('viewPartyType').textContent = party.party_type.charAt(0).toUpperCase() + party.party_type.slice(1);
          document.getElementById('viewPartyType').className = `badge bg-label-${partyColor}`;

          // Status
          const statusClass = party.status === 'active' ? 'success' : 'secondary';
          document.getElementById('viewPartyStatus').textContent = party.status.charAt(0).toUpperCase() + party.status.slice(1);
          document.getElementById('viewPartyStatus').className = `badge bg-label-${statusClass} ms-1`;

          // Details
          document.getElementById('viewPartyShortName').textContent = party.short_name || '-';
          document.getElementById('viewPartyNTN').textContent = party.ntn || '-';
          document.getElementById('viewPartyCreated').textContent = party.created_at ? new Date(party.created_at).toLocaleDateString() : '-';

          // Contact information
          document.getElementById('viewPartyContactPerson').textContent = party.contact_person || '-';
          document.getElementById('viewPartyContact').textContent = party.contact || '-';
          document.getElementById('viewPartyContact').href = party.contact ? `tel:${party.contact}` : '#';
          document.getElementById('viewPartyEmail').textContent = party.email || '-';
          document.getElementById('viewPartyEmail').href = party.email ? `mailto:${party.email}` : '#';

          // Address
          document.getElementById('viewPartyAddress').textContent = party.address || '-';

          // Banking information
          document.getElementById('viewPartyBankName').textContent = party.bank_name || 'Not provided';
          document.getElementById('viewPartyIBAN').textContent = party.iban || 'Not provided';

          // Store party ID for edit button
          document.getElementById('editFromViewBtn')?.setAttribute('data-id', party.id);

          // Store party data for other buttons
          const viewModalElement = document.getElementById('viewPartyModal');
          viewModalElement.setAttribute('data-party-id', party.id);
          viewModalElement.setAttribute('data-party-status', party.status);
          viewModalElement.setAttribute('data-party-type', party.party_type);

          // Show modal
          const viewModal = new bootstrap.Modal(viewModalElement);
          viewModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load party details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading party details', 'error');
      });
  }

  // Edit Party Function
  function editParty(partyId) {
    fetch(`/api/parties/${partyId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const party = data.data;

          // Populate edit form
          document.getElementById('editPartyId').value = party.id;
          document.getElementById('editPartyTitle').value = party.title;
          document.getElementById('editPartyShortName').value = party.short_name;
          document.getElementById('editPartyAddress').value = party.address;
          document.getElementById('editPartyContact').value = party.contact;
          document.getElementById('editPartyEmail').value = party.email;
          document.getElementById('editPartyContactPerson').value = party.contact_person;
          document.getElementById('editPartyBankName').value = party.bank_name || '';
          document.getElementById('editPartyIBAN').value = party.iban || '';
          document.getElementById('editPartyNTN').value = party.ntn;

          // Set party type
          if (party.party_type === 'customer') {
            document.getElementById('editCustomerType').checked = true;
          } else {
            document.getElementById('editVendorType').checked = true;
          }

          // Show edit modal
          const editModal = new bootstrap.Modal(document.getElementById('editPartyModal'));
          editModal.show();
        } else {
          showToast('Error', data.message || 'Failed to load party details', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while loading party details', 'error');
      });
  }

  // Toggle Party Status Function
  function togglePartyStatus(partyId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const action = newStatus === 'active' ? 'activate' : 'deactivate';

    fetch(`/api/parties/${partyId}/toggle`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reload both tables
        dt_customers.ajax.reload();
        dt_vendors.ajax.reload();

        showToast('Success', data.message || `Party ${action}d successfully!`, 'success');
      } else {
        showToast('Error', data.message || `Failed to ${action} party`, 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showToast('Error', `An error occurred while ${action}ing the party`, 'error');
    });
  }

  // Delete Party Function
  function deleteParty(partyId) {
    if (confirm('Are you sure you want to delete this party? This action cannot be undone.')) {
      fetch(`/api/parties/${partyId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload both tables
          dt_customers.ajax.reload();
          dt_vendors.ajax.reload();

          showToast('Success', data.message || 'Party deleted successfully!', 'success');
        } else {
          showToast('Error', data.message || 'Failed to delete party', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred while deleting the party', 'error');
      });
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
</style>
@endsection
