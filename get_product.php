<?php
$productId = $_GET['id'];

$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

preg_match('/generateDemoProducts\(\) {\s*return\s*(\[.*?\]);/s', $indexContent, $matches);
$productsJson = $matches[1];
$products = json_decode(str_replace('`', '"', $productsJson), true);

$product = null;
foreach ($products as $p) {
    if ($p['id'] == $productId) {
        $product = $p;
        break;
    }
}

echo json_encode($product);
?>
