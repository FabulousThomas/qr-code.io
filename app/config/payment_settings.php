<?php

/**
 * Payment Configuration Settings
 * Fine-tune payment behavior and options
 */

// Payment gateway settings
define('PAYMENT_GATEWAY', 'paystack'); // Currently only paystack supported
define('PAYMENT_TIMEOUT', 30); // Payment timeout in seconds
define('PAYMENT_RETRY_ATTEMPTS', 3); // Max retry attempts for failed payments

// Bulk payment settings
define('BULK_PAYMENT_ENABLED', true); // Enable/disable bulk payments
define('BULK_MIN_QUANTITY', 1); // Minimum quantity for bulk pricing
define('BULK_MAX_QUANTITY', 10000); // Maximum quantity per bulk order

// Discount settings
define('BULK_VOLUME_DISCOUNT_ENABLED', true); // Enable volume discounts
define('BULK_LOYALTY_DISCOUNT', 0.05); // 5% discount for repeat customers
define('BULK_PROMO_DISCOUNT', 0.10); // 10% discount for promotional codes

// Payment flow settings
define('PAYMENT_REDIRECT_DELAY', 2000); // Delay before redirect after payment (ms)
define('PAYMENT_POPUP_WIDTH', 400); // Payment popup width
define('PAYMENT_POPUP_HEIGHT', 600); // Payment popup height

// Security settings
define('PAYMENT_WEBHOOK_SECRET', ''); // Webhook secret for verification
define('PAYMENT_REFERENCE_PREFIX', 'QRHUB'); // Prefix for transaction references

// Notification settings
define('PAYMENT_EMAIL_NOTIFICATIONS', true); // Send payment confirmation emails
define('PAYMENT_SMS_NOTIFICATIONS', false); // Send SMS notifications (if enabled)

// Testing settings
define('PAYMENT_DEBUG_MODE', true); // Enable detailed payment logging
define('PAYMENT_SIMULATE_SUCCESS', true); // Simulate successful payments in test mode

?>
