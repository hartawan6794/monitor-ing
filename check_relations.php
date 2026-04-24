<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

echo "UNITS:\n"; print_r(DB::table('units')->limit(5)->get()->toArray());
echo "\nGROUPS:\n"; print_r(DB::table('productgroup')->limit(5)->get()->toArray());
echo "\nBRANDS:\n"; print_r(DB::table('productbrand')->limit(5)->get()->toArray());
echo "\nTAXES:\n"; print_r(DB::table('taxes')->limit(5)->get()->toArray());
echo "\nSUPPLIERS:\n"; print_r(DB::table('supplier')->limit(5)->get()->toArray());
