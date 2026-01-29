# Security Remediation Plan: fiefelstein.ch

**Date**: 2026-01-16
**Last Updated**: 2026-01-29

---

## Completed

- [x] Update livewire to 3.6.4+ (now v3.7.4)
- [x] Update laravel/framework to 10.48.29+ (now v10.50.0)
- [x] Update filament to 3.2.123+ (now v3.3.47)
- [x] All 13 CVEs patched (`composer audit` clean)
- [x] Database password rotated
- [x] FTP password rotated
- [x] Debug mode disabled (local)
- [x] Stripe API keys rotated
- [x] Stripe checked - no fraudulent activity
- [x] Test routes removed

---

## Pending: Immediate Actions

### 1. Verify Production .env
Confirm on production server:
```
APP_DEBUG=false
APP_ENV=production
SESSION_SECURE_COOKIE=true
```

---

## Completed: Code Fixes

### 2. Fix Payment Bypass (CRITICAL) ✓

**File**: `app/Http/Controllers/OrderController.php`

**Problem**: Payment success endpoint (`/bestellung/zahlung-erfolgreich`) is a GET request that marks orders as paid without Stripe verification.

**Solution**: Implement Stripe webhook verification.

1. Create webhook controller:
```php
// app/Http/Controllers/StripeWebhookController.php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            // Process the successful payment
            // Mark order as paid in database
        }

        return response('OK', 200);
    }
}
```

2. Update routes (`routes/web.php`):
```php
// Remove or protect the GET endpoint
// Route::get('/bestellung/zahlung-erfolgreich', ...);

// Add webhook route
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
```

3. Add to CSRF exceptions (`app/Http/Middleware/VerifyCsrfToken.php`):
```php
protected $except = [
    'stripe/webhook',
];
```

4. Add webhook secret to `.env`:
```
STRIPE_WEBHOOK_SECRET=whsec_...
```

### 3. Fix Price Manipulation (CRITICAL) ✓

**Status**: FIXED - Prices are now fetched from database in both `OrderController::finalize()` and `HandleOrder::create()`.

### 4. Enable Session Encryption ✓

**Status**: FIXED - `config/session.php` has `'encrypt' => true` and `'same_site' => 'strict'`.

---

## Pending: Security Hardening

### 5. Add Authorization Checks

**Files**: `app/Http/Requests/*.php`

Example for `OrderStoreRequest.php`:
```php
public function authorize()
{
    $cart = session()->get('cart', []);
    return !empty($cart['items']);
}
```

### 6. Secure Session Configuration ✓

**Status**: FIXED - `config/session.php` already has `'same_site' => 'strict'`. The `'secure'` option uses `env('SESSION_SECURE_COOKIE')` which should be set to `true` in production .env.

### 7. Add Path Validation to ImageController ✓

**Status**: FIXED - Path traversal protection added to `ImageController::show()`.

---

## Blocked

### Laravel 11 Upgrade
**Blocked by**: `intervention/imagecache` is abandoned and needs replacement first.

---

## Long-Term Improvements

- [ ] Replace `intervention/imagecache` with alternative
- [ ] Upgrade to Laravel 11
- [ ] Move cart from session to database
- [ ] Add rate limiting
- [ ] Add security headers middleware
- [ ] Implement 2FA for admin panel
- [ ] Set up error tracking (Sentry/Bugsnag)
- [ ] Configure regular `composer audit` in CI/CD

---

## Summary Checklist

### Before Going Live
- [x] Rotate Stripe API keys
- [ ] Verify production .env settings
- [x] Fix payment bypass with Stripe webhooks
- [x] Fix price manipulation (validate from DB)
- [x] Enable session encryption

### Within 1 Week
- [x] Remove test routes
- [ ] Add authorization checks
- [x] Secure session configuration
- [x] Add ImageController path validation

### Within 1 Month
- [ ] Replace intervention/imagecache
- [ ] Laravel 11 upgrade
- [ ] Move cart to database
