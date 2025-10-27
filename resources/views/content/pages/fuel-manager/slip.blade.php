<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fuel Payment Slip</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      font-size: 12px;
    }
    .slip-container {
      max-width: 400px;
      margin: 0 auto;
      border: 1px solid #ddd;
      padding: 15px;
    }
    .slip-header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
    }
    .slip-header h2 {
      margin: 0;
      font-size: 18px;
      text-transform: uppercase;
    }
    .slip-logo {
      text-align: left;
      margin-bottom: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .slip-logo img {
      max-height: 50px;
    }
    .slip-number {
      font-weight: bold;
      border: 1px solid #000;
      padding: 5px;
      display: inline-block;
    }
    .slip-content {
      margin-bottom: 20px;
    }
    .slip-row {
      display: flex;
      border-bottom: 1px dotted #ddd;
      padding: 5px 0;
    }
    .slip-label {
      flex: 1;
      font-weight: bold;
    }
    .slip-value {
      flex: 2;
    }
    .slip-footer {
      margin-top: 30px;
      text-align: center;
      border-top: 1px solid #ddd;
      padding-top: 10px;
    }
    .signature-area {
      margin-top: 40px;
      border-top: 1px dotted #000;
      text-align: center;
      padding-top: 5px;
      width: 150px;
    }
    @media print {
      @page {
        size: auto;
        margin: 5mm;
      }
      body {
        padding: 0;
        margin: 0;
      }
    }
  </style>
</head>
<body>
  <div class="slip-container">
    <div class="slip-header">
      <div class="slip-logo">
        @if(isset($company->logo) && $company->logo)
        <img src="{{ asset('storage/logo/logo.png') }}" alt="Company Logo">
        @else
        <div style="font-weight: bold; font-size: 16px;">{{ $company->name ?? 'Transport Management System' }}</div>
        @endif
        <div class="slip-number">№ {{ $payment->id }}</div>
      </div>
      <h2>Fuel Payment Slip</h2>
    </div>
    
    <div class="slip-content">
      <div class="slip-row">
        <div class="slip-label">Reference Type:</div>
        <div class="slip-value">{{ ucfirst(str_replace('_', ' ', $payment->reference_type)) }}</div>
      </div>
      
      <div class="slip-row">
        <div class="slip-label">Reference ID:</div>
        <div class="slip-value">{{ $payment->reference_id }}</div>
      </div>
      <div class="slip-row">
        <div class="slip-label">Container:</div>
        <div class="slip-value">{{ $containerNumber ?? 'N/A' }}</div>
      </div>
      
      <div class="slip-row">
        <div class="slip-label">Date:</div>
        <div class="slip-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
      </div>
      
      <div class="slip-row">
        <div class="slip-label">Route:</div>
        <div class="slip-value">{{ $routeName }}</div>
      </div>
      
      <div class="slip-row">
        <div class="slip-label">Fleet:</div>
        <div class="slip-value">{{ $fleetName }}</div>
      </div>
      
      
      
      <div class="slip-row">
        <div class="slip-label">Fuel Tank:</div>
        <div class="slip-value">{{ $tankName }}</div>
      </div>
      
      <div class="slip-row">
        <div class="slip-label">Fuel Quantity:</div>
        <div class="slip-value">{{ $fuelAmount }} L</div>
      </div>
      
      @if(!empty($payment->payment_notes))
      <div class="slip-row">
        <div class="slip-label">Notes:</div>
        <div class="slip-value">{{ $payment->payment_notes }}</div>
      </div>
      @endif
    </div>
    
    <div class="slip-footer">
      <div style="display: flex; justify-content: space-between;">
        <div>
          <div style="text-align: left;">
            <div class="signature-area">Authorized Signature</div>
          </div>
        </div>
        <div>
          <div style="text-align: right;">
            <div class="signature-area">Recipient Signature</div>
          </div>
        </div>
      </div>
      <div style="margin-top: 20px; font-size: 10px;">
        Printed on: {{ now()->format('d/m/Y h:i A') }}
      </div>
    </div>
  </div>

  <script>
    // Automatically trigger print when page loads
    window.onload = function() {
      // Short delay to ensure everything is rendered
      setTimeout(function() {
        window.print();
        
        // Listen for when print dialog is closed
        window.addEventListener('afterprint', function() {
          // Close the window after printing is done or canceled
          setTimeout(function() {
            window.close();
          }, 500);
        });
      }, 500);
    };
  </script>
</body>
</html>
