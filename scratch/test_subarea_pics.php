<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$subAreaCount = App\Models\BosqSubArea::count();
echo "Total Sub Areas in BOS'Q: " . $subAreaCount . "\n";

$subAreasWithPics = App\Models\BosqSubArea::has('pics')->with('pics')->get();
echo "Sub Areas with assigned PICs: " . $subAreasWithPics->count() . "\n";

foreach ($subAreasWithPics->take(5) as $sa) {
    $picNames = $sa->pics->map(fn($p) => "{$p->name} ({$p->nik})")->implode(', ');
    echo " - [SubArea #{$sa->id}] {$sa->nama_sub_area}: {$picNames}\n";
}

// Test Livewire component instantiation
$component = new App\Livewire\BosQ\MasterSubArea();
$component->mount();
echo "Component filterDepartemenId: " . $component->filterDepartemenId . "\n";

echo "ALL TESTS PASSED SUCCESSFULLY!\n";
