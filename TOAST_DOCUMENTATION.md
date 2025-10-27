# Toast Notification System Documentation

## Overview
A comprehensive, reusable toast notification system for Laravel applications using Bootstrap toasts with animations.

## Features
- ✅ Multiple notification types (success, error, warning, info, primary)
- ✅ Animated toasts with fade-in effects
- ✅ Auto-dismiss with customizable duration
- ✅ Manual dismiss functionality
- ✅ Responsive positioning
- ✅ HTML escaping for security
- ✅ Global availability across all pages

## Setup

### 1. Include the Toast Container Component
Add the toast container to your layout or specific pages:

```blade
<!-- In any Blade template -->
<x-toast-container />
```

### 2. Required Dependencies
The component automatically includes:
- Bootstrap 5 (for toast functionality)
- Animate.css (for animations)
- Tabler Icons (for notification icons)

## Usage

### Basic Usage
```javascript
// Success notification
ToastManager.success('Success!', 'Data saved successfully');

// Error notification
ToastManager.error('Error!', 'Something went wrong');

// Warning notification
ToastManager.warning('Warning!', 'Please check your input');

// Info notification
ToastManager.info('Info', 'Here\'s some information');

// Primary notification
ToastManager.primary('Notice', 'Important update available');
```

### Advanced Usage
```javascript
// Custom duration (in milliseconds)
ToastManager.success('Success!', 'Custom timing', 10000); // 10 seconds

// Generic show method with all options
ToastManager.show('Custom Title', 'Custom message', 'success', 8000);

// Clear all toasts
ToastManager.clear();
```

### Legacy Support
For backward compatibility, the old `showToast` function is still available:
```javascript
showToast('Title', 'Message', 'success');
```

## Notification Types

| Type | Icon | Color | Default Duration |
|------|------|-------|------------------|
| `success` | ✓ check | Green | 5 seconds |
| `error` | ✗ x | Red | 7 seconds |
| `warning` | ⚠ triangle | Yellow | 6 seconds |
| `info` | ℹ circle | Blue | 5 seconds |
| `primary` | 🔔 bell | Primary theme | 5 seconds |

## Implementation Examples

### Laravel Controller Response
```php
// In your controller
public function store(Request $request)
{
    try {
        // Your logic here
        $status = UserStatus::create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'User status created successfully!',
            'data' => $status
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create user status: ' . $e->getMessage()
        ], 500);
    }
}
```

### Frontend JavaScript (AJAX)
```javascript
// Handle form submission
async function submitForm(formData) {
    try {
        const response = await fetch('/api/endpoint', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            ToastManager.success('Success!', result.message);
            // Handle success...
        } else {
            ToastManager.error('Error!', result.message);
        }
    } catch (error) {
        ToastManager.error('Network Error', 'Failed to connect to server');
    }
}
```

### Form Validation
```javascript
// Client-side validation
function validateForm(form) {
    const errors = [];
    
    // Validation logic...
    if (errors.length > 0) {
        ToastManager.warning('Validation Error', errors.join(', '));
        return false;
    }
    
    return true;
}
```

## Styling Customization

### Custom CSS
```css
/* Override toast positioning */
.toast-container {
    top: 20px !important;
    right: 20px !important;
}

/* Custom toast styling */
.bs-toast {
    min-width: 350px;
}

/* Custom animation */
.toast-custom-animation {
    animation: slideInFromTop 0.5s ease-out;
}

@keyframes slideInFromTop {
    0% { transform: translateY(-100%); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}
```

## Best Practices

### 1. Appropriate Duration
- **Success messages**: 3-5 seconds
- **Error messages**: 7-10 seconds (users need time to read)
- **Warnings**: 6-8 seconds
- **Info messages**: 4-6 seconds

### 2. Message Content
- Keep titles short and descriptive
- Use clear, actionable language
- Avoid technical jargon in user-facing messages

### 3. Frequency
- Don't spam users with too many toasts
- Group related notifications when possible
- Use `ToastManager.clear()` before showing critical messages

### 4. Error Handling
```javascript
// Always provide fallback error messages
try {
    // API call
} catch (error) {
    const message = error.response?.data?.message || 'An unexpected error occurred';
    ToastManager.error('Error', message);
}
```

## Global Methods

| Method | Parameters | Description |
|--------|------------|-------------|
| `ToastManager.success(title, message, duration?)` | title, message, duration | Show success toast |
| `ToastManager.error(title, message, duration?)` | title, message, duration | Show error toast |
| `ToastManager.warning(title, message, duration?)` | title, message, duration | Show warning toast |
| `ToastManager.info(title, message, duration?)` | title, message, duration | Show info toast |
| `ToastManager.primary(title, message, duration?)` | title, message, duration | Show primary toast |
| `ToastManager.show(title, message, type, duration?)` | title, message, type, duration | Show custom toast |
| `ToastManager.clear()` | none | Hide all visible toasts |

## Troubleshooting

### Common Issues

1. **Toasts not appearing**
   - Ensure `<x-toast-container />` is included in your template
   - Check browser console for JavaScript errors
   - Verify Bootstrap and animate.css are loaded

2. **Styling issues**
   - Check z-index conflicts
   - Ensure animate.css is properly loaded
   - Verify icon fonts are available

3. **Multiple containers**
   - Only include `<x-toast-container />` once per page
   - The component uses a global singleton pattern

### Debug Mode
```javascript
// Enable debug logging
ToastManager.debug = true;
ToastManager.success('Test', 'Debug message');
```

## Browser Support
- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 12+
- ✅ Edge 79+

## Dependencies
- Bootstrap 5.x
- Animate.css 4.x
- Tabler Icons (or compatible icon set)
