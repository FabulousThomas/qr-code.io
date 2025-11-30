<?php

return [
    // Security Headers
    'headers' => [
        'x-frame-options' => 'SAMEORIGIN',
        'x-content-type-options' => 'nosniff',
        'x-xss-protection' => '1; mode=block',
        'referrer-policy' => 'strict-origin-when-cross-origin',
        'permissions-policy' => 'geolocation=(), microphone=(), camera=()',
        'strict-transport-security' => 'max-age=31536000; includeSubDomains; preload',
    ],
    
    // Content Security Policy (CSP)
    'csp' => [
        'default-src' => ["'self'"],
        'script-src' => [
            "'self'",
            "'unsafe-inline'",
            "'unsafe-eval'",
            'https://js.paystack.co',  // Paystack JS
            'https://checkout.paystack.com',
        ],
        'style-src' => [
            "'self'",
            "'unsafe-inline'",
            'https://fonts.googleapis.com',
        ],
        'img-src' => [
            "'self'",
            'data:',
            'blob:',
            'https://*.paystack.com',
        ],
        'font-src' => [
            "'self'",
            'data:',
            'https://fonts.gstatic.com',
        ],
        'connect-src' => [
            "'self'",
            'https://api.paystack.co',
        ],
        'frame-src' => [
            'https://js.paystack.co',
            'https://checkout.paystack.com',
        ],
        'form-action' => ["'self'"],
    ],
    
    // Session Configuration
    'session' => [
        'name' => 'qr_code_io',
        'cookie_lifetime' => 0, // Until browser closes
        'cookie_path' => '/',
        'cookie_domain' => '',
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
        'use_only_cookies' => true,
        'gc_maxlifetime' => 1440, // 24 minutes
        'sid_length' => 128,
        'sid_bits_per_character' => 6,
    ],
    
    // Rate Limiting
    'rate_limiting' => [
        'enabled' => true,
        'requests_per_minute' => 60,
        'ip_whitelist' => [
            '127.0.0.1',
            '::1',
        ],
    ],
    
    // CSRF Protection
    'csrf' => [
        'key' => 'csrf_token',
        'expire' => 3600, // 1 hour
        'regenerate' => true,
    ],
    
    // File Uploads
    'uploads' => [
        'max_file_size' => 5 * 1024 * 1024, // 5MB
        'allowed_mime_types' => [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/svg+xml' => 'svg',
        ],
        'upload_dir' => __DIR__ . '/../../public/uploads/',
        'require_authentication' => true,
    ],
];
