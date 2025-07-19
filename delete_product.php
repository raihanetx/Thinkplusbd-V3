<?php
$productId = $_GET['id'];

$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

preg_match('/(generateDemoProducts\(\) {\s*return\s*)(\[.*?\]);/s', $indexContent, $matches);
$productsJson = $matches[2];
$products = json_decode(str_replace('`', '"', $productsJson), true);

$updatedProducts = [];
foreach ($products as $p) {
    if ($p['id'] != $productId) {
        $updatedProducts[] = $p;
    }
}

$newProductsJson = json_encode($updatedProducts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$newProductsJson = str_replace('"', '`', $newProductsJson);

$newContent = $matches[1] . $newProductsJson . ';';
$indexContent = str_replace($matches[0], $newContent, $indexContent);

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
