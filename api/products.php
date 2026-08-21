<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

$response = ['success' => false, 'data' => []];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'list') {
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $products = getAllProducts($pdo, $limit, $offset);
        $response = ['success' => true, 'data' => $products];
    } 
    elseif ($action === 'search') {
        $search = $_GET['q'] ?? '';
        $products = searchProducts($pdo, $search);
        $response = ['success' => true, 'data' => $products];
    }
    elseif ($action === 'detail') {
        $productId = $_GET['id'] ?? 0;
        $product = getProductById($pdo, $productId);
        $images = $product ? getProductImages($pdo, $productId) : [];
        $variants = $product ? getProductVariants($pdo, $productId) : [];
        
        if ($product) {
            $product['images'] = $images;
            $product['variants'] = $variants;
            $response = ['success' => true, 'data' => $product];
        }
    }
    elseif ($action === 'categories') {
        $categories = getCategories($pdo);
        $response = ['success' => true, 'data' => $categories];
    }
}

echo json_encode($response);
?>