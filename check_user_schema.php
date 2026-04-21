<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

$tables = DB::select("SHOW TABLES LIKE '%user%'");
foreach($tables as $t) {
    $tableName = array_values((array)$t)[0];
    echo "\nTABLE: $tableName\n";
    print_r(array_map(function($c) {
        return $c->Field . ' (' . $c->Type . ')';
    }, DB::select("SHOW COLUMNS FROM `$tableName`")));
}
