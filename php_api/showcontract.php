<?php

include 'condb.php';
require_once 'contract_schema.php';
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

try {
    ensureContractSchema($conn);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $conn->prepare("SELECT `contract_id`, `name`, `contractType`, `phone`, `email`, `proposal`
                                FROM `contracts` ORDER BY `contract_id` DESC");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
    } elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data) || empty($data['contract_id'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "ไม่พบรหัส Contract"]);
            exit;
        }

        $name = trim($data['name'] ?? '');
        $contractType = trim($data['contractType'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $email = trim($data['email'] ?? '');
        $proposal = trim($data['proposal'] ?? '');

        if ($name === '' || $contractType === '' || $phone === '' || $email === '' || $proposal === '') {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "รูปแบบอีเมลไม่ถูกต้อง"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE `contracts`
                                SET `name` = :name, `contractType` = :contractType, `phone` = :phone,
                                    `email` = :email, `proposal` = :proposal
                                WHERE `contract_id` = :contract_id");
        $stmt->execute([
            ":name" => $name,
            ":contractType" => $contractType,
            ":phone" => $phone,
            ":email" => $email,
            ":proposal" => $proposal,
            ":contract_id" => (int)$data['contract_id']
        ]);

        if ($stmt->rowCount() === 0) {
            $check = $conn->prepare("SELECT 1 FROM `contracts` WHERE `contract_id` = :contract_id");
            $check->execute([":contract_id" => (int)$data['contract_id']]);
            if (!$check->fetchColumn()) {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "ไม่พบข้อมูล Contract"]);
                exit;
            }
        }

        echo json_encode(["success" => true, "message" => "แก้ไขข้อมูลเรียบร้อย"]);
    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data) || empty($data['contract_id'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "ไม่พบรหัส Contract"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM `contracts` WHERE `contract_id` = :contract_id");
        $stmt->execute([":contract_id" => (int)$data['contract_id']]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "ไม่พบข้อมูล Contract"]);
            exit;
        }

        echo json_encode(["success" => true, "message" => "ลบข้อมูลเรียบร้อย"]);
    } else {
        http_response_code(405);
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
