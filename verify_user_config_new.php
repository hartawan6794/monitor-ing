<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;

$request = new Request(['userid' => 'admin', 'section' => 'Pelanggan']);
$controller = new UserController();
$response = $controller->getUserConfig($request);

echo "RESPONSE FOR ADMIN (Pelanggan):\n";
echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
