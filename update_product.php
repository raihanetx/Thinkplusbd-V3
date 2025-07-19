<?php
$updatedProduct = json_decode(file_get_contents('php://input'), true);

$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

preg_match('/(generateDemoProducts\(\) {\s*return\s*)(\[.*?\]);/s', $indexContent, $matches);
$productsJson = $matches[2];
$products = json_decode(str_replace('`', '"', $productsJson), true);

if (is_array($products)) {
    foreach ($products as &$p) {
        if (isset($p['id']) && isset($updatedProduct['id']) && $p['id'] == $updatedProduct['id']) {
            $p['name'] = $updatedProduct['name'];
            $p['description'] = $updatedProduct['description'];
            $p['price'] = $updatedProduct['price'];
            $p['image'] = $updatedProduct['image'];
            if (isset($updatedProduct['category'])) {
                $p['category'] = $updatedProduct['category'];
            }
            break;
        }
    }
}

$newProductsJson = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$newProductsJson = str_replace('"', '`', $newProductsJson);

$newContent = $matches[1] . $newProductsJson . ';';
$indexContent = str_replace($matches[0], $newContent, $indexContent);

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
