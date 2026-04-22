<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $name = current((array)$table);
    if (preg_match('/inventory|adjust|moving|transfer/i', $name)) {
        echo $name . PHP_EOL;
    }
}
