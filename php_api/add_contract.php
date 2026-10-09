<?php

include 'condb.php';
require_once 'contract_schema.php';
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method ไม่ถูกต้อง"]);
    exit;
}

try {
    ensureContractSchema($conn);
    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ข้อมูลที่ส่งมาไม่ถูกต้อง"]);
        exit;
    }

    $name = trim($data["name"] ?? "");
    $contractType = trim($data["contractType"] ?? "");
    $phone = trim($data["phone"] ?? "");
    $email = trim($data["email"] ?? "");
    $proposal = trim($data["proposal"] ?? "");

    if ($name === "" || $contractType === "" || $phone === "" || $email === "" || $proposal === "") {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "รูปแบบอีเมลไม่ถูกต้อง"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO `contracts` (`name`, `contractType`, `phone`, `email`, `proposal`)
                            VALUES (:name, :contractType, :phone, :email, :proposal)");
    $stmt->execute([
        ":name" => $name,
        ":contractType" => $contractType,
        ":phone" => $phone,
        ":email" => $email,
        ":proposal" => $proposal
    ]);

    echo json_encode(["success" => true, "message" => "เพิ่มข้อมูลเรียบร้อย"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
