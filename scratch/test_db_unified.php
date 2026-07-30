<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== UNIFIED DATABASE STATUS ===" . PHP_EOL;
echo "Database Name: " . DB::connection()->getDatabaseName() . PHP_EOL;
echo "Tables in Database:" . PHP_EOL;

$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $arr = (array)$t;
    $tableName = reset($arr);
    $count = DB::table($tableName)->count();
    echo " - {$tableName} ({$count} rows)" . PHP_EOL;
}
