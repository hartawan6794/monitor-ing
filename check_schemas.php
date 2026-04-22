<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

echo "STRUCT inventoryadjust:\n";
print_r(DB::select("SHOW COLUMNS FROM inventoryadjust"));

echo "\nSTRUCT inventorymoving:\n";
print_r(DB::select("SHOW COLUMNS FROM inventorymoving"));
