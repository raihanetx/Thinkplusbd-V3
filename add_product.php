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

// Add the category to the categories list if it doesn't exist
$dom = new DOMDocument();
@$dom->loadHTML($indexContent);
$xpath = new DOMXPath($dom);
$categoryElements = $xpath->query('//div[contains(@class, "category")]');
$existingCategories = [];
foreach ($categoryElements as $element) {
    $existingCategories[] = $xpath->query('.//p[contains(@class, "category-name")]', $element)->item(0)->nodeValue;
}

if (!in_array($newProduct['category'], $existingCategories)) {
    $newCategoryHtml = '<div class="category" onclick="navigateTo(\'products\', \'' . strtolower($newProduct['category']) . '\')">
                        <div class="category-icon-wrapper"><i class="fas fa-folder category-icon-fa" aria-hidden="true"></i></div>
                        <p class="category-name">' . $newProduct['category'] . '</p>
                        <p class="category-subtitle" id="category-count-' . strtolower($newProduct['category']) . '">1 Product</p>
                    </div>';

    $section = $xpath->query('//section[contains(@class, "categories") and contains(@class, "container")]')->item(0);
    if ($section) {
        $fragment = $dom->createDocumentFragment();
        $fragment->appendXML($newCategoryHtml);
        $section->appendChild($fragment);

        $productSectionHtml = '<section class="product-listings container">
            <div class="featured-section-header">
                <h2 class="category-title">' . $newProduct['category'] . '</h2>
                <a class="view-all" onclick="navigateTo(\'products\', \'' . strtolower($newProduct['category']) . '\')">View All <i class="fas fa-angle-double-right" style="font-size: 0.8em;"></i></a>
            </div>
            <div class="homepage-products-grid" id="featured' . ucfirst(strtolower($newProduct['category'])) . 'sGrid">
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
}

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
