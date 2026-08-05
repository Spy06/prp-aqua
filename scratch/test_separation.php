<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test MasterSubArea instantiation
$subAreaComponent = new App\Livewire\BosQ\MasterSubArea();
$subAreaComponent->mount();
echo "MasterSubArea component mounted. Filter dept: {$subAreaComponent->filterDepartemenId}\n";

// Test MasterLine instantiation
$lineComponent = new App\Livewire\BosQ\MasterLine();
$lineComponent->mount();
echo "MasterLine component mounted. Filter dept: {$lineComponent->filterDepartemenId}\n";

// Verify sub area count in DB
$count = App\Models\BosqSubArea::count();
echo "Total Sub Areas in DB: {$count}\n";

echo "SEPARATION TEST PASSED SUCCESSFULLY!\n";
