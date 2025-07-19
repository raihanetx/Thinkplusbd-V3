<?php
$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

preg_match('/generateDemoProducts\(\) {\s*return\s*(\[.*?\]);/s', $indexContent, $matches);
$productsJson = $matches[1];
$products = json_decode(str_replace('`', '"', $productsJson), true);

$dom = new DOMDocument();
@$dom->loadHTML($indexContent);
$xpath = new DOMXPath($dom);
$categoryElements = $xpath->query('//div[contains(@class, "category")]');

$categories = [];
foreach ($categoryElements as $element) {
    $name = $xpath->query('.//p[contains(@class, "category-name")]', $element)->item(0)->nodeValue;
    $categories[] = $name;
}

echo json_encode(['products' => $products, 'categories' => $categories]);
?>
