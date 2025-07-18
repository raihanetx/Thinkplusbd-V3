<?php
$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

preg_match('/generateDemoProducts\(\) {\s*return\s*(\[.*?\]);/s', $indexContent, $matches);
$productsJson = $matches[1];
$products = json_decode(str_replace('`', '"', $productsJson), true);

echo json_encode($products);
?>
