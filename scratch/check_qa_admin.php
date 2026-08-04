<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$qaAdmins = ['20663', '14427', '9900001', '14427'];

foreach ($qaAdmins as $nik) {
    $u = User::where('nik', $nik)->first();
    if ($u) {
        $u->update(['role' => 'qa']);
        echo "Updated NIK {$nik} ({$u->name}) to role = 'qa'" . PHP_EOL;
    }
}

// Also check by name
$names = ['Lia Atikah', 'Ruslan Abdul Gani'];
foreach ($names as $name) {
    $u = User::where('name', 'like', "%{$name}%")->first();
    if ($u) {
        $u->update(['role' => 'qa']);
        echo "Updated {$u->name} (NIK: {$u->nik}) to role = 'qa'" . PHP_EOL;
    }
}
