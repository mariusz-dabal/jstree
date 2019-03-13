<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM items");
    $result= $stmt->fetchAll();
    $json = json_encode($result);
    echo $json;
    http_response_code(200);
} catch(Exception $e) {
    echo $e->getMessage();
}


