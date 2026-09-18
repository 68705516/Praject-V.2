<?php

include 'condb.php';

try {
    $conn->exec("CREATE TABLE IF NOT EXISTS `contracts` (
        `contract_id` INT(11) NOT NULL AUTO_INCREMENT,
        `studentId` VARCHAR(50) NOT NULL,
        `contractType` VARCHAR(100) NOT NULL,
        `startDate` DATE DEFAULT NULL,
        `endDate` DATE DEFAULT NULL,
        `phone` VARCHAR(20) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        PRIMARY KEY (`contract_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $conn->prepare("SELECT * FROM `contracts` ORDER BY `contract_id` DESC");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid request method"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
