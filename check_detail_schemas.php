<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

echo "STRUCT inventoryadjustdetail:\n";
print_r(DB::select("SHOW COLUMNS FROM inventoryadjustdetail"));

echo "\nSTRUCT inventorymovingdetail:\n";
print_r(DB::select("SHOW COLUMNS FROM inventorymovingdetail"));
