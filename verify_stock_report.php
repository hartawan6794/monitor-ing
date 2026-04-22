<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ReportController;

$request = new Request();
$controller = new ReportController();
$response = $controller->stockReport($request);

echo $response->getContent();
