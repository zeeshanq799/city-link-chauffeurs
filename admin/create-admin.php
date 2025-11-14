<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create admin user
$user = User::create([
    'name' => 'Admin User',
    'email' => 'admin@citylinkdrivers.com',
    'password' => Hash::make('Admin@123'),
    'email_verified_at' => now(),
]);

echo "✅ Admin user created successfully!\n";
echo "📧 Email: admin@citylinkdrivers.com\n";
echo "🔑 Password: Admin@123\n";
echo "\nYou can login at: http://localhost:8000/admin\n";
