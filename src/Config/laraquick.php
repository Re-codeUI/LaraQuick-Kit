<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global Dependencies (Wajib untuk Semua Modul)
    |--------------------------------------------------------------------------
    |
    | Ini adalah package yang harus diinstal terlepas dari modul apa yang dipilih.
    | Contohnya: Spatie Permission untuk role & permission, Laravel UI untuk auth.
    |
    */

    'global_dependencies' => [
        'spatie/laravel-permission', // Role & Permission Management
        'laravel/ui', // UI Auth Scaffolding
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Configuration (Daftar Modul & Dependensi Khusus)
    |--------------------------------------------------------------------------
    |
    | Setiap modul memiliki daftar dependency yang dibutuhkan secara spesifik.
    |
    */

    'modules' => [

        'inventory' => [
            'enabled' => false,
            'dependencies' => [
                'maatwebsite/excel', // Export & Import data Inventory
                'barryvdh/laravel-dompdf', // Cetak laporan stok dalam bentuk PDF
            ],
        ],

        'sales' => [
            'enabled' => false,
            'dependencies' => [
                'mpdf/mpdf', // Alternatif untuk generate PDF invoice
                'omnipay/omnipay', // Payment Gateway Support
                'barryvdh/laravel-dompdf', // PDF Invoice & Laporan Penjualan
            ],
        ],

        'hr' => [
            'enabled' => false,
            'dependencies' => [
                'spatie/laravel-activitylog', // Logging aktivitas karyawan
                'spatie/laravel-permission', // Manajemen Role & Permission untuk HR
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Roles & Permissions
    |--------------------------------------------------------------------------
    |
    | Saat instalasi, role & permission berikut akan dibuat berdasarkan modul
    | yang dipilih oleh user.
    |
    */
    'roles' => [
        'admin' => [
            'name' => 'Admin',
            'permissions' => [
                'manage_users',
                'manage_roles',
                'view_reports',
            ],
        ],

        // Roles untuk Modul Inventory
        'inventory_manager' => [
            'name' => 'Inventory Manager',
            'permissions' => [
                'view_inventory',
                'manage_inventory',
                'export_inventory',
                'approve_requests',
            ],
        ],
        'inventory_staff' => [
            'name' => 'Inventory Staff',
            'permissions' => [
                'view_inventory',
                'add_inventory',
            ],
        ],
        'warehouse_supervisor' => [
            'name' => 'Warehouse Supervisor',
            'permissions' => [
                'approve_inventory_requests',
                'view_inventory_reports',
            ],
        ],

        // Roles untuk Modul Sales
        'sales_manager' => [
            'name' => 'Sales Manager',
            'permissions' => [
                'view_sales',
                'process_sales',
                'generate_invoice',
                'approve_discounts',
            ],
        ],
        'sales_staff' => [
            'name' => 'Sales Staff',
            'permissions' => [
                'view_sales',
                'process_sales',
            ],
        ],
        'billing_admin' => [
            'name' => 'Billing Admin',
            'permissions' => [
                'process_refunds',
                'manage_invoices',
            ],
        ],

        // Roles untuk Modul HR
        'hr_manager' => [
            'name' => 'HR Manager',
            'permissions' => [
                'view_employees',
                'manage_employees',
                'generate_payslip',
                'approve_leave_requests',
            ],
        ],
        'recruitment_officer' => [
            'name' => 'Recruitment Officer',
            'permissions' => [
                'post_job_openings',
                'process_applications',
            ],
        ],
        'payroll_admin' => [
            'name' => 'Payroll Admin',
            'permissions' => [
                'process_salaries',
                'generate_payslip',
            ],
        ],
    ],

    

    /*
    |--------------------------------------------------------------------------
    | UI & Layout Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi tampilan dan layout yang digunakan oleh LaraQuickKit.
    |
    */

    'ui' => [
        'layout' => 'layouts.app', // Layout utama
        'theme' => 'default', // Bisa disesuaikan dengan light/dark mode
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration & Seeder Configuration
    |--------------------------------------------------------------------------
    |
    | Mengatur apakah migration dan seeder dijalankan otomatis saat instalasi.
    |
    */

    'database' => [
        'run_migrations' => true,
        'run_seeders' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Publishing Configuration
    |--------------------------------------------------------------------------
    |
    | Menentukan apakah file config, views, dan assets harus dipublish.
    |
    */

    'publishing' => [
        'config' => true,
        'views' => true,
        'assets' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | User Notifications Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk notifikasi pengguna setelah registrasi.
    | Bisa diaktifkan atau dinonaktifkan berdasarkan kebutuhan.
    |
    */

    'notifications' => [
        'email' => [
            'enabled' => true, // Kirim notifikasi email saat user terdaftar
            'template' => 'emails.user_registered', // Template email yang digunakan
        ],
        'whatsapp' => [
            'enabled' => false, // Kirim notifikasi WhatsApp (butuh integrasi tambahan)
            'provider' => 'twilio', // Bisa diubah ke provider lain jika diperlukan
        ],
        'sms' => [
            'enabled' => false, // Kirim notifikasi SMS
            'provider' => 'nexmo', // Bisa diubah sesuai layanan SMS gateway
        ],
    ],

];