<?php
// ── Database connection
include '../connect.php';

header('Content-Type: application/json');

sleep(3);  

// ── Validate input
$min = isset($_GET['min']) ? (int)$_GET['min'] : null;
$max = isset($_GET['max']) ? (int)$_GET['max'] : null;

if ($min === null || $max === null || $min > $max || $min < 0) {
    echo json_encode(['error' => 'Invalid min/max parameters.']);
    exit;
}

try {
    $sql  = 'SELECT Name, CountryCode, District, Population
             FROM City
             WHERE Population BETWEEN :min AND :max
             ORDER BY Population DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':min' => $min, ':max' => $max]);

    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cities);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Query failed.']);
}