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
    $indexContent = $dom->saveHTML();
}

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
