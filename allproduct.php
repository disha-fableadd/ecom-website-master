<?php
include 'db.php'; 


$priceFilter = isset($_GET['price']) ? $_GET['price'] : null;


$sql = "SELECT * FROM products";


if (!empty($priceFilter) && $priceFilter !== 'all') {
    switch ($priceFilter) {
        case '0-100':
            $sql .= " WHERE price BETWEEN 0 AND 100";
            break;
        case '100-200':
            $sql .= " WHERE price BETWEEN 100 AND 200";
            break;
        case '200-300':
            $sql .= " WHERE price BETWEEN 200 AND 300";
            break;
        case '300-400':
            $sql .= " WHERE price BETWEEN 300 AND 400";
            break;
    }
}

$result = $conn->query($sql);
if (!$result) {
    die("Query Error: " . $conn->error);
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
