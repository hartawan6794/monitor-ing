<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
DB::purge('mysql');
config(['database.connections.mysql.database' => 'acosys']);

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProductController;

$sku = "TEST-SKU-" . time();
$data = [
    'id' => $sku,
    'name' => 'Produk Test API',
    'productgroup' => '6197',
    'defunit' => 'PCS',
    'salesprice1' => 150000,
    'initial_stock' => 10,
    'departement_id' => '001101',
    'division_id' => '0011',
    'usercreate' => 'tester'
];

$request = new Request($data);
$controller = new ProductController();
$response = $controller->store($request);

echo "RESPONSE: " . $response->getContent() . "\n";

// Verify product in DB
$product = DB::table('product')->where('id', $sku)->first();
if ($product) {
    echo "Product created successfully!\n";
} else {
    echo "Product NOT found!\n";
}

// Verify inventory in DB
$inventory = DB::table('inventory')->where('productid', $sku)->first();
if ($inventory) {
    echo "Inventory record created! Qty: " . $inventory->invin . "\n";
} else {
    echo "Inventory record NOT found!\n";
}
