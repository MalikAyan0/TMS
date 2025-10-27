@extends('layouts/layoutMaster')

@section('title', 'Fuel Usage Report')

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
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Fuel Management /</span> Fuel Usage Report
</h4>

<!-- Filters Card -->
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">
      <i class='bx bx-filter me-2'></i>Filters
    </h5>
  </div>
  <div class="card-body">
    <form id="filterForm">
      <div class="row g-3">
        <!-- Fleet Filter -->
        <div class="col-md-4">
          <label class="form-label">Fleet</label>
          <select class="form-select select2" id="fleet_filter" name="fleet_id">
            <option value="">Select Fleet</option>
            @foreach($fleets as $fleet)
            <option value="{{ $fleet->id }}">{{ $fleet->name }} ({{ $fleet->registration_number }})</option>
            @endforeach
          </select>
        </div>

        <!-- Date From -->
        <div class="col-md-4">
          <label class="form-label">From Date</label>
          <input type="text" class="form-control vuexy_date" id="date_from" name="date_from" placeholder="Select start date" >
        </div>

        <!-- Date To -->
        <div class="col-md-4">
          <label class="form-label">To Date</label>
          <input type="text" class="form-control vuexy_date" id="date_to" name="date_to" placeholder="Select end date" >
        </div>


      </div>

      <!-- Clear Filter -->
      <div class="row mt-3">
        <div class="col-md-9"></div>
        <!-- Filter Button -->
        <div class="col-md-3 d-flex align-items-end justify-content-end">
          <button type="submit" class="btn btn-primary me-2 ">
            <i class='ti tabler-search me-1'></i>Filter
          </button>
          <button type="button" class="btn btn-outline-secondary " id="clearFilters">
            <i class='ti tabler-refresh me-1'></i>Clear Filters
          </button>
        </div>

      </div>
    </form>
  </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4" id="summaryCards" style="display: none;">
  <div class="col-md-4 col-6">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <i class='ti tabler-route text-primary' style="font-size: 2rem;"></i>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Total Routes</span>
        <h3 class="card-title mb-2" id="totalRoutes">0</h3>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-6">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <i class='ti tabler-gas-station text-warning' style="font-size: 2rem;"></i>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Total Fuel Used</span>
        <h3 class="card-title mb-2" id="totalFuel">0 L</h3>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-6">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <i class='ti tabler-calculator text-success' style="font-size: 2rem;"></i>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Average per Route</span>
        <h3 class="card-title mb-2" id="avgFuel">0 L</h3>
      </div>
    </div>
  </div>
</div>

<!-- Results Table -->
<div class="card" id="resultsCard" style="display: none;">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">
      <i class='bx bx-list-ul me-2'></i>Fuel Usage Details
    </h5>
    <div>
      <button class="btn btn-outline-secondary btn-sm me-2" id="exportBtn">
        <i class='bx bx-export me-1'></i>Export
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-striped" id="fuelTable">
        <thead>
          <tr>
            <th>Date</th>
            <th>Route Name</th>
            <th>Fleet</th>
            <th>Type</th>
            <th>Fuel Used (L)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="fuelTableBody">
          <!-- Data will be populated via AJAX -->
        </tbody>
        <tfoot>
          <tr class="table-dark">
            <th colspan="4">Total</th>
            <th id="totalFuelFooter">0 L</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<!-- No Results Message -->
<div class="card" id="noResultsCard" style="display: none;">
  <div class="card-body text-center py-5">
    <i class='bx bx-search bx-lg text-muted mb-3'></i>
    <h5 class="text-muted">No Data Found</h5>
    <p class="text-muted mb-0">Please select filters and click "Filter" to view fuel usage data.</p>
  </div>
</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">
          <i class="ti tabler-credit-card me-2"></i>Confirm Payment
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="paymentForm">
          <div class="mb-3">
            <label class="form-label">Payment Details</label>
            <div class="card bg-light">
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <small class="text-muted">Date:</small>
                    <div id="modalDate" class="fw-medium">-</div>
                  </div>
                  <div class="col-6">
                    <small class="text-muted">Route:</small>
                    <div id="modalRoute" class="fw-medium">-</div>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-6">
                    <small class="text-muted">Fleet:</small>
                    <div id="modalFleet" class="fw-medium">-</div>
                  </div>
                  <div class="col-6">
                    <small class="text-muted">Type:</small>
                    <div id="modalType" class="fw-medium">-</div>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-6">
                    <small class="text-muted">Fuel Used:</small>
                    <div id="modalFuel" class="fw-medium">-</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tank Selection -->
          <div class="mb-3" id="tankSectionContainer">
            <label for="tankSelect" class="form-label">Fuel Tank</label>
            <select class="form-select" id="tankSelect" name="tank_id" required>
              <option value="">Select Tank</option>
              <!-- Tank options will be populated dynamically -->
            </select>
            <div class="invalid-feedback">Please select a fuel tank.</div>

            <!-- Tank Details Card -->
            <div class="card mt-2 d-none" id="tankDetailsCard">
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-6">
                    <small class="text-muted">Tank Name:</small>
                    <div id="tankName" class="fw-medium">-</div>
                  </div>
                  <div class="col-6">
                    <small class="text-muted">Available Fuel:</small>
                    <div id="availableFuel" class="fw-medium">-</div>
                  </div>
                </div>
                <div class="alert alert-warning d-none mt-2 mb-0" id="insufficientFuelAlert">
                  <i class="ti tabler-alert-triangle me-1"></i>
                  Warning: Insufficient fuel available in this tank.
                </div>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="paymentNotes" class="form-label">Payment Notes</label>
            <textarea class="form-control" id="paymentNotes" name="payment_notes" rows="3"
                      placeholder="Add any notes about this payment (optional)..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmPaymentBtn">
          <i class="ti tabler-check me-1"></i>Confirm Payment
        </button>
      </div>
    </div>
  </div>
</div>

<!-- No Tank Available Modal -->
<div class="modal fade" id="noTankModal" tabindex="-1" aria-labelledby="noTankModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noTankModalLabel">
          <i class="ti tabler-alert-triangle me-2 text-warning"></i>No Fuel Tank Available
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>You do not have any fuel tanks assigned to you. Please contact the administrator to assign a tank before processing fuel payments.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<x-toast-container />
<!-- / Toast Container -->

<script>
  'use strict';

  let fv, offCanvasEl;
  let fuelDataTable;

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

      // Initialize DataTable
      initializeDataTable();

      // Form submission handler
      const filterForm = document.getElementById('filterForm');
      filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchFuelData();
      });

      // Clear filters handler
      const clearFiltersBtn = document.getElementById('clearFilters');
      clearFiltersBtn.addEventListener('click', function() {
        clearFilters();
      });

      // Export button handler
      const exportBtn = document.getElementById('exportBtn');
      exportBtn.addEventListener('click', function() {
        exportData();
      });
    })();
  });

  // Update summary cards
  function updateSummaryCards(summary) {
    document.getElementById('totalRoutes').textContent = summary.total_routes || 0;
    document.getElementById('totalFuel').textContent = (summary.total_fuel || 0) + ' L';
    document.getElementById('avgFuel').textContent = (summary.avg_fuel || 0) + ' L';
  }

  // Update data table
  function updateDataTable(data) {
    fuelDataTable.clear();

    if (data && data.length > 0) {
      data.forEach(function(row) {
        // Action button
        let actionButton = '';
        if (row.payment_status === 'unpaid') {
          actionButton = `<button class="btn btn-sm btn-primary pay-btn"
                           data-id="${row.id}"
                           data-type="${row.reference_type}">
                           <i class="ti tabler-credit-card me-1"></i>Pay
                         </button>`;
        } else {
          actionButton = `<button class="btn btn-sm btn-success print-btn"
                           data-id="${row.id}"
                           data-type="${row.reference_type}">
                           <i class="ti tabler-printer me-1"></i>Print
                         </button>`;
        }

        fuelDataTable.row.add([
          row.date || '-',
          row.route_name || '-',
          row.fleet_name || '-',
          row.type || '-',
          (row.fuel_used || 0) + ' L',
          actionButton
        ]);
      });

      // Add click event for pay buttons
      $('#fuelTable tbody').on('click', '.pay-btn', function() {
        const id = $(this).data('id');
        const type = $(this).data('type');
        markAsPaid(id, type, $(this));
      });

      // Add click event for print buttons
      $('#fuelTable tbody').on('click', '.print-btn', function() {
        const id = $(this).data('id');
        const type = $(this).data('type');
        printSlip(id, type);
      });
    }

    fuelDataTable.draw();
  }

  // Initialize DataTable
  function initializeDataTable() {
    if ($.fn.DataTable.isDataTable('#fuelTable')) {
      $('#fuelTable').DataTable().destroy();
    }

    fuelDataTable = $('#fuelTable').DataTable({
      dom: 'Bfrtip',
      buttons: [],
      responsive: true,
      pageLength: 25,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      order: [[0, 'desc']], // Sort by date descending
      columnDefs: [
        { responsivePriority: 1, targets: [0, 1, 2, 3] }, // Date, Route, Fleet, Type
        { responsivePriority: 2, targets: [4, 5] }, // Fuel Used, Action
        { orderable: false, targets: [5] } // Disable sorting for Action column
      ]
    });
  }

  // Mark payment as paid
  function markAsPaid(referenceId, referenceType, buttonElement) {
    // First fetch available tanks for the user
    fetch('{{ route("fuel-manager.get-tanks") }}', {
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success && data.data && data.data.length > 0) {
        // Store current payment data
        window.currentPayment = {
          referenceId: referenceId,
          referenceType: referenceType,
          buttonElement: buttonElement,
          fuelAmount: 0
        };

        // Get row data for modal
        const row = buttonElement.closest('tr');
        const cells = row.find('td');

        document.getElementById('modalDate').textContent = cells.eq(0).text();
        document.getElementById('modalRoute').textContent = cells.eq(1).text();
        document.getElementById('modalFleet').textContent = cells.eq(2).text();
        document.getElementById('modalType').textContent = cells.eq(3).text();
        document.getElementById('modalFuel').textContent = cells.eq(4).text();

        // Extract fuel amount
        const fuelText = cells.eq(4).text();
        const fuelAmount = parseFloat(fuelText.replace(' L', '')) || 0;
        window.currentPayment.fuelAmount = fuelAmount;

        // Populate tank select
        const tankSelect = document.getElementById('tankSelect');
        tankSelect.innerHTML = '<option value="">Select Tank</option>';

        data.data.forEach(tank => {
          const option = document.createElement('option');
          option.value = tank.id;
          option.textContent = `${tank.name} (${tank.available_fuel} L available)`;
          option.dataset.availableFuel = tank.available_fuel;
          option.dataset.totalFuelAdded = tank.total_fuel_added;
          option.dataset.totalFuelUsed = tank.total_fuel_used;
          tankSelect.appendChild(option);
        });

        // Show tank section and reset state
        document.getElementById('tankSectionContainer').style.display = 'block';
        document.getElementById('tankDetailsCard').classList.add('d-none');
        document.getElementById('confirmPaymentBtn').disabled = true;

        // Clear previous details
        document.getElementById('availableFuel').textContent = '-';
        document.querySelectorAll('.fuel-details-item').forEach(el => el.remove());
        document.getElementById('insufficientFuelAlert').classList.add('d-none');

        // Clear previous notes
        document.getElementById('paymentNotes').value = '';

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
      } else {
        // No tanks available, show error modal
        const noTankModal = new bootstrap.Modal(document.getElementById('noTankModal'));
        noTankModal.show();
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showError('An error occurred while fetching tank information.');
    });
  }

  // Tank select change handler
  document.getElementById('tankSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const tankId = this.value;

    if (tankId) {
      // Show tank details card
      document.getElementById('tankDetailsCard').classList.remove('d-none');

      // Clear previous fuel details to prevent duplication
      const availableFuelElement = document.getElementById('availableFuel');
      availableFuelElement.textContent = 'Loading...';

      // Remove any previously added fuel detail elements
      const existingDetails = document.querySelectorAll('.fuel-details-item');
      existingDetails.forEach(el => el.remove());

      // Fetch tank details
      fetch(`{{ route("fuel-manager.get-tank-details") }}?tank_id=${tankId}`, {
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data) {
          const tank = data.data;
          document.getElementById('tankName').textContent = tank.name;

          // Calculate and display available fuel using total added and used
          const availableFuel = parseFloat(tank.total_fuel_added || 0) - parseFloat(tank.total_fuel_used || 0);
          availableFuelElement.textContent = `${availableFuel.toFixed(2)} L`;

          // Add fuel details with a unique class for easy removal later
          const detailsContainer = document.createElement('div');
          detailsContainer.className = 'fuel-details-item mt-2';
          detailsContainer.innerHTML = `
            <small class="text-muted d-block">Total Added: ${tank.total_fuel_added || 0} L</small>
            <small class="text-muted d-block">Total Used: ${tank.total_fuel_used || 0} L</small>
          `;

          // Insert after availableFuelElement
          availableFuelElement.parentNode.insertBefore(detailsContainer, availableFuelElement.nextSibling);

          // Check if there's enough fuel
          const fuelNeeded = window.currentPayment ? window.currentPayment.fuelAmount : 0;
          if (availableFuel < fuelNeeded) {
            document.getElementById('insufficientFuelAlert').classList.remove('d-none');
            document.getElementById('confirmPaymentBtn').disabled = true;
          } else {
            document.getElementById('insufficientFuelAlert').classList.add('d-none');
            document.getElementById('confirmPaymentBtn').disabled = false;
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showError('An error occurred while fetching tank details.');
      });
    } else {
      // Hide tank details card
      document.getElementById('tankDetailsCard').classList.add('d-none');
      document.getElementById('confirmPaymentBtn').disabled = true;
    }
  });

  // Handle confirm payment button
  document.getElementById('confirmPaymentBtn').addEventListener('click', function() {
    if (!window.currentPayment) return;

    const tankId = document.getElementById('tankSelect').value;
    if (!tankId) {
      showError('Please select a fuel tank.');
      return;
    }

    const { referenceId, referenceType, buttonElement } = window.currentPayment;
    const paymentNotes = document.getElementById('paymentNotes').value;

    const originalText = buttonElement.html();
    const confirmBtn = this;
    const originalConfirmText = confirmBtn.innerHTML;

    // Disable buttons and show loading
    buttonElement.html('<span class="spinner-border spinner-border-sm me-1"></span>Processing...').prop('disabled', true);
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processing...';
    confirmBtn.disabled = true;

    // Use the markAsPaidWithTank function via AJAX
    fetch('{{ route("fuel-manager.mark-paid") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        reference_id: referenceId,
        reference_type: referenceType,
        payment_notes: paymentNotes,
        tank_id: tankId
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update the row
        const row = buttonElement.closest('tr');
        row.find('td:nth-child(5)').next().html('<button class="btn btn-sm btn-success print-btn" data-id="' + referenceId + '" data-type="' + referenceType + '"><i class="ti tabler-printer me-1"></i>Print</button>');

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
        modal.hide();

        showSuccess(data.message || 'Payment marked as paid successfully!');

        // Clear current payment data
        window.currentPayment = null;

        // Reset the button to its original state
        confirmBtn.innerHTML = originalConfirmText;
        confirmBtn.disabled = false;
      } else {
        showError(data.message || 'Failed to mark payment as paid');
        buttonElement.html(originalText).prop('disabled', false);
        confirmBtn.innerHTML = originalConfirmText;
        confirmBtn.disabled = false;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showError('An error occurred while processing the payment');
      buttonElement.html(originalText).prop('disabled', false);
      confirmBtn.innerHTML = originalConfirmText;
      confirmBtn.disabled = false;
    });
  });

  // Add this function to reset the pay buttons
  function resetPayButtons() {
    document.querySelectorAll('.pay-btn').forEach(button => {
      if (button.disabled) {
        button.innerHTML = '<i class="ti tabler-credit-card me-1"></i>Pay';
        button.disabled = false;
      }
    });
  }

  // Add a handler to reset buttons when the modal is hidden
  document.getElementById('paymentModal').addEventListener('hidden.bs.modal', function () {
    resetPayButtons();
  });

  // Show success message
  function showSuccess(message) {
    showToast('Success', message, 'success');
  }

  // Update footer totals
  function updateFooterTotals(totals) {
    document.getElementById('totalFuelFooter').textContent = (totals.total_fuel || 0) + ' L';
  }

  // Fetch fuel data via AJAX
  function fetchFuelData() {
    const formData = new FormData(document.getElementById('filterForm'));

    // Show loading state
    showLoading();

    fetch('{{ route("fuel-manager.data") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
      },
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      hideLoading();

      if (data.success) {
        if (data.data && data.data.length > 0) {
          updateSummaryCards(data.summary);
          updateDataTable(data.data);
          updateFooterTotals(data.totals);

          // Show results
          document.getElementById('summaryCards').style.display = 'flex';
          document.getElementById('resultsCard').style.display = 'block';
          document.getElementById('noResultsCard').style.display = 'none';
        } else {
          // Handle empty data
          showNoResults();
          if (data.message) {
            document.querySelector('#noResultsCard .text-muted:last-child').textContent = data.message;
          }
        }
      } else {
        showNoResults();
        showError(data.message || 'Failed to fetch data');
      }
    })
    .catch(error => {
      hideLoading();
      console.error('Error:', error);
      showError('An error occurred while fetching data. Please try again.');
      showNoResults();
    });
  }

  // Show loading state
  function showLoading() {
    const submitBtn = document.querySelector('#filterForm button[type="submit"]');
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...';
    submitBtn.disabled = true;
  }

  // Hide loading state
  function hideLoading() {
    const submitBtn = document.querySelector('#filterForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="ti tabler-search me-1"></i>Filter';
    submitBtn.disabled = false;
  }

  // Show no results
  function showNoResults() {
    document.getElementById('summaryCards').style.display = 'none';
    document.getElementById('resultsCard').style.display = 'none';
    document.getElementById('noResultsCard').style.display = 'block';
  }

  // Clear all filters
  function clearFilters() {
    // Reset form
    document.getElementById('filterForm').reset();

    // Clear flatpickr dates
    const dateInputs = document.querySelectorAll('.vuexy_date');
    dateInputs.forEach(function(input) {
      input._flatpickr.clear();
    });

    // Reset select2 if used
    const select2Elements = document.querySelectorAll('.select2');
    select2Elements.forEach(function(select) {
      if ($(select).hasClass('select2-hidden-accessible')) {
        $(select).val('').trigger('change');
      }
    });

    // Hide results
    document.getElementById('summaryCards').style.display = 'none';
    document.getElementById('resultsCard').style.display = 'none';
    document.getElementById('noResultsCard').style.display = 'none';

    // Clear table
    if (fuelDataTable) {
      fuelDataTable.clear().draw();
    }
  }

  // Export data
  function exportData() {
    const formData = new FormData(document.getElementById('filterForm'));

    // Create a temporary form for export
    const exportForm = document.createElement('form');
    exportForm.method = 'POST';
    exportForm.action = '{{ route("fuel-manager.export") }}';
    exportForm.style.display = 'none';

    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    exportForm.appendChild(csrfInput);

    // Add form data
    for (let [key, value] of formData.entries()) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = key;
      input.value = value;
      exportForm.appendChild(input);
    }

    document.body.appendChild(exportForm);
    exportForm.submit();
    document.body.removeChild(exportForm);
  }

  // Function to handle printing the slip
  function printSlip(id, type) {
    // Open the print slip in a new tab
    window.open(`{{ route('fuel-manager.print-slip', ['id' => ':id', 'type' => ':type']) }}`.replace(':id', id).replace(':type', type), '_blank');
  }
</script>

@endsection
