<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;

// Get the actual password from DB
$user = DB::table('users')->where('id', 'admin')->first();
echo "Admin found: " . ($user ? 'YES' : 'NO') . "\n";

// Try decrypting password
$decrypted = decryptXor($user->userpassword);
echo "Decrypted password (first 5 chars): " . substr($decrypted, 0, 5) . "\n";

$request = new Request(['id' => 'admin', 'userpassword' => $decrypted]);
$controller = new AuthController();
$response = $controller->login($request);

$decoded = json_decode($response->getContent(), true);

echo "=== STATUS: " . $decoded['status'] . "\n";
echo "=== DEFAULT CUSTOMER: " . ($decoded['data']['default_customer'] ?? 'null') . "\n";
echo "=== Pelanggan Section:\n";
echo json_encode($decoded['data']['user_configs']['Pelanggan'] ?? [], JSON_PRETTY_PRINT);
