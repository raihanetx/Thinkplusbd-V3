<?php
$newCategory = json_decode(file_get_contents('php://input'), true);

$indexFile = 'index.html';
$indexContent = file_get_contents($indexFile);

$newCategoryHtml = '<div class="category" onclick="navigateTo(\'products\', \'' . strtolower($newCategory['name']) . '\')">
                    <div class="category-icon-wrapper"><i class="' . $newCategory['icon'] . ' category-icon-fa" aria-hidden="true"></i></div>
                    <p class="category-name">' . $newCategory['name'] . '</p>
                    <p class="category-subtitle" id="category-count-' . strtolower($newCategory['name']) . '">0 ' . $newCategory['subtitle'] . '</p>
                </div>';

$indexContent = preg_replace(
    '/(<section class="categories container">)/',
    '$1' . $newCategoryHtml,
    $indexContent
);

file_put_contents($indexFile, $indexContent);

echo json_encode(['success' => true]);
?>
