<?php
$newProduct = json_decode(file_get_contents('php://input'), true);

$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

// Add the product to the products array
preg_match('/(generateDemoProducts\(\) {\s*return\s*)(\[.*?\]);/s', $indexContent, $matches);
$productsJson = $matches[2];
$products = json_decode(str_replace('`', '"', $productsJson), true);

$newProduct['id'] = uniqid();
$products[] = $newProduct;

$newProductsJson = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$newProductsJson = str_replace('"', '`', $newProductsJson);

$newContent = $matches[1] . $newProductsJson . ';';
$indexContent = str_replace($matches[0], $newContent, $indexContent);

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
