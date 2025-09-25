<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Motor;

echo "Available Motors:\n";
$motors = Motor::where('status', 'available')->get(['id','merk','plat_nomor','status']);

foreach ($motors as $motor) {
    echo "ID: {$motor->id} - {$motor->merk} ({$motor->plat_nomor}) - {$motor->status->value}\n";
}

echo "\nTotal available: " . $motors->count() . "\n";