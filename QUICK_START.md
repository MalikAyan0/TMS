# Quick Implementation Guide

## To use the Toast system in any page:

### 1. In your Blade template:
```blade
@extends('layouts/layoutMaster')

@section('content')
<!-- Include the toast container -->
<x-toast-container />

<!-- Your page content -->
<div class="container">
    <button onclick="ToastManager.success('Success!', 'It works!')">Test Toast</button>
</div>
@endsection
```

### 2. In your JavaScript:
```javascript
// Success message
ToastManager.success('Success!', 'Operation completed successfully');

// Error message  
ToastManager.error('Error!', 'Something went wrong');

// Warning message
ToastManager.warning('Warning!', 'Please check your input');

// Info message
ToastManager.info('Info', 'Here is some information');

// Custom duration
ToastManager.success('Quick!', 'Gone in 2 seconds', 2000);
```

### 3. With AJAX/Fetch:
```javascript
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(result => {
    if (result.success) {
        ToastManager.success('Success!', result.message);
    } else {
        ToastManager.error('Error!', result.message);
    }
})
.catch(error => {
    ToastManager.error('Network Error', 'Failed to connect to server');
});
```

## Test URLs:
- User Status Management: http://localhost:8000/user-status
- Toast Demo: http://localhost:8000/toast-demo
