<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

echo "\n--- userconfigrules (limit 20) ---\n";
print_r(DB::select("SELECT * FROM userconfigrules LIMIT 20"));

echo "\n--- users (limit 5) ---\n";
print_r(DB::select("SELECT id, name, description FROM users LIMIT 5"));

echo "\n--- usersconfig (limit 20) ---\n";
print_r(DB::select("SELECT * FROM usersconfig LIMIT 20"));
