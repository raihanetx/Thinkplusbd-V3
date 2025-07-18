<?php
$newCategory = json_decode(file_get_contents('php://input'), true);

$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

$newCategoryHtml = '<div class="category" onclick="navigateTo(\'products\', \'' . strtolower($newCategory['name']) . '\')">
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

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
