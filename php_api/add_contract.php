<?php

include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['studentId']) ||
    !isset($data['contractType']) ||
    !isset($data['startDate']) ||
    !isset($data['phone']) ||
    !isset($data['email'])
) {
    echo json_encode([
        "success" => false,
        "message" => "ข้อมูลไม่ครบ"
    ]);
    exit;
}

try {
    $conn->exec("CREATE TABLE IF NOT EXISTS `contracts` (
        `contract_id` INT(11) NOT NULL AUTO_INCREMENT,
        `studentId` VARCHAR(50) NOT NULL,
        `contractType` VARCHAR(100) NOT NULL,
        `startDate` DATE DEFAULT NULL,
        `phone` VARCHAR(20) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        PRIMARY KEY (`contract_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $sql = "INSERT INTO `contracts`
            (`studentId`, `contractType`, `phone`, `email`)
            VALUES
            (:studentId, :contractType,, :phone, :email)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':studentId' => $data['studentId'],
        ':contractType' => $data['contractType'],
        ':phone' => $data['phone'],
        ':email' => $data['email']
    ]);

    echo json_encode([
        "success" => true,
        "message" => "เพิ่มข้อมูลเรียบร้อย"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
