<?php
// Test file for product management functionalities

// Test get_products.php
echo "<h1>Testing get_products.php</h1>";
include 'get_products.php';
echo "<hr>";

// Test get_product.php
echo "<h1>Testing get_product.php</h1>";
$_GET['id'] = 1;
include 'get_product.php';
echo "<hr>";

// Test add_product.php
echo "<h1>Testing add_product.php</h1>";
$newProduct = [
    'name' => 'Test Product',
    'description' => 'Test Description',
    'price' => 123,
    'image' => 'test.jpg',
    'category' => 'Test'
];
file_put_contents('php://input', json_encode($newProduct));
include 'add_product.php';
echo "<hr>";

// Test update_product.php
echo "<h1>Testing update_product.php</h1>";
$updatedProduct = [
    'id' => '687aeb22a0f59',
    'name' => 'Updated Test Product',
    'description' => 'Updated Test Description',
    'price' => 456,
    'image' => 'updated_test.jpg',
    'category' => 'Test'
];
file_put_contents('php://input', json_encode($updatedProduct));
include 'update_product.php';
echo "<hr>";

// Test delete_product.php
echo "<h1>Testing delete_product.php</h1>";
$_GET['id'] = '687aeb22a0f59';
include 'delete_product.php';
echo "<hr>";

?>
