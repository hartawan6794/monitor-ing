<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

echo "STRUCT inventory:\n";
print_r(DB::select("SHOW COLUMNS FROM inventory"));

echo "\nSTRUCT departement:\n";
print_r(DB::select("SHOW COLUMNS FROM departement"));

echo "\nSTRUCT supplier:\n";
print_r(DB::select("SHOW COLUMNS FROM supplier"));

echo "\nSTRUCT product:\n";
print_r(DB::select("SHOW COLUMNS FROM product"));
