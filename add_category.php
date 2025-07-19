<?php
$newCategory = json_decode(file_get_contents('php://input'), true);

$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

$newCategoryHtml = '<div class="category" data-category="' . strtolower($newCategory['name']) . '" onclick="navigateTo(\'products\', \'' . strtolower($newCategory['name']) . '\')">
                    <div class="category-icon-wrapper"><i class="' . $newCategory['icon'] . ' category-icon-fa" aria-hidden="true"></i></div>
                    <p class="category-name">' . $newCategory['name'] . '</p>
                    <p class="category-subtitle" id="category-count-' . strtolower($newCategory['name']) . '">0 ' . $newCategory['subtitle'] . '</p>
                </div>';

$dom = new DOMDocument();
@$dom->loadHTML($indexContent);

$xpath = new DOMXPath($dom);
$section = $xpath->query('//section[contains(@class, "categories") and contains(@class, "container")]')->item(0);

if ($section) {
    $fragment = $dom->createDocumentFragment();
    $fragment->appendXML($newCategoryHtml);
    $section->appendChild($fragment);

    $productSectionHtml = '<section class="product-listings container">
        <div class="featured-section-header">
            <h2 class="category-title">' . $newCategory['name'] . '</h2>
            <a class="view-all" onclick="navigateTo(\'products\', \'' . strtolower($newCategory['name']) . '\')">View All <i class="fas fa-angle-double-right" style="font-size: 0.8em;"></i></a>
        </div>
        <div class="homepage-products-grid" id="featured' . ucfirst(strtolower($newCategory['name'])) . 'sGrid">
        </div>
    </section>';

    $productFragment = $dom->createDocumentFragment();
    $productFragment->appendXML($productSectionHtml);

    $whyChooseUsSection = $xpath->query('//section[contains(@class, "why-choose-us")]')->item(0);
    if ($whyChooseUsSection) {
        $whyChooseUsSection->parentNode->insertBefore($productFragment, $whyChooseUsSection);
    }

    $indexContent = $dom->saveHTML();
}

// Add the new category to the generateDemoProducts function
preg_match('/(generateDemoProducts\(\) {\s*return\s*)(\[.*?\]);/s', $indexContent, $matches);
$productsJson = $matches[2];
$products = json_decode(str_replace('`', '"', $productsJson), true);

$newProduct = [
    'id' => uniqid(),
    'name' => 'New Product in ' . $newCategory['name'],
    'description' => 'Description for new product in ' . $newCategory['name'],
    'price' => 0,
    'image' => 'path/to/default-product-image.jpg',
    'category' => strtolower($newCategory['name']),
    'isFeatured' => false
];
$products[] = $newProduct;

$newProductsJson = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$newProductsJson = str_replace('"', '`', $newProductsJson);

$newContent = $matches[1] . $newProductsJson . ';';
$indexContent = str_replace($matches[0], $newContent, $indexContent);

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
