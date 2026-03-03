<?php

// Paystack Configuration
// Replace these with your actual Paystack API keys
define('PAYSTACK_PUBLIC_KEY', $_ENV['PAYSTACK_PUBLIC_KEY'] ?? 'pk_test_d8e1f3a0b2c4d5e6f7a8b9c0d1e2f3a4');
define('PAYSTACK_SECRET_KEY', $_ENV['PAYSTACK_SECRET_KEY'] ?? 'sk_test_d8e1f3a0b2c4d5e6f7a8b9c0d1e2f3a4');

// Test mode - set to true to bypass Paystack API for testing
define('PAYSTACK_TEST_MODE', true);

define('PAYSTACK_API_URL', 'https://api.paystack.co');

// Payment settings - Dynamic pricing tiers
define('BULK_BASE_PRICE', 5.99); // Base price for up to 50 codes
define('BULK_TIER_2_PRICE', 9.99); // For 51-200 codes  
define('BULK_TIER_3_PRICE', 19.99); // For 201-500 codes
define('BULK_TIER_4_PRICE', 39.99); // For 500+ codes
define('BULK_TIER_1_LIMIT', 50);
define('BULK_TIER_2_LIMIT', 200);
define('BULK_TIER_3_LIMIT', 500);
define('CURRENCY', 'NGN'); // Nigerian Naira

// Callback URLs
define('PAYSTACK_CALLBACK_URL', URLROOT . '/payment/callback');

// For testing, you can use these test cards:
// Success: 50606 66666 66666 6666 (any expiry date, any CVV)
// Fail: 50606 66666 66666 6664 (any expiry date, any CVV)

// Environment
define('PAYSTACK_ENVIRONMENT', 'live'); // Change to 'live' for production

?>
