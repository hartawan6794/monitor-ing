<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

$rule = DB::table('userconfigrules')->where('id', '023002')->first();
echo "RULE 023002:\n";
print_r($rule);

// Also check some values in usersconfig for this rule
$configs = DB::table('usersconfig')->where('userconfigrulesid', '023002')->limit(5)->get();
echo "\nSAMPLES IN USERSCONFIG:\n";
print_r($configs->toArray());
