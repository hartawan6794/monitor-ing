<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

echo "DEPARTEMENT COLS:\n";
print_r(DB::select('SHOW COLUMNS FROM departement'));
echo "\nDIVISION COLS:\n";
print_r(DB::select('SHOW COLUMNS FROM division'));
