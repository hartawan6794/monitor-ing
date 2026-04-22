<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

echo "DIVISION ROW:\n";
print_r(DB::table('division')->first());
echo "\nDEPARTEMENT ROW:\n";
print_r(DB::table('departement')->first());
