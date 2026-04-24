<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

$rules = DB::table('userconfigrules')->where('section', 'Pelanggan')->get();
echo json_encode($rules, JSON_PRETTY_PRINT);
