<?php
include 'condb.php';

$data = json_decode(file_get_contents("php://input"), true) ?? [];
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode([
        "success" => false,
        "message" => "กรุณากรอกชื่อผู้ใช้และรหัสผ่าน"
    ]);
    exit;
}

try {
    $stmt = $conn->prepare(
        "SELECT emp_id, firstName, lastName, username, password
         FROM employee
         WHERE username = :username"
    );
    $stmt->execute([":username" => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            "success" => false,
            "message" => "ไม่พบชื่อผู้ใช้นี้"
        ]);
        exit;
    }

    $passwordInfo = password_get_info($user['password']);
    $isLegacyPassword = $passwordInfo['algoName'] === 'unknown';
    $passwordIsValid = $isLegacyPassword
        ? hash_equals($user['password'], $password)
        : password_verify($password, $user['password']);

    if (!$passwordIsValid) {
        echo json_encode([
            "success" => false,
            "message" => "รหัสผ่านไม่ถูกต้อง"
        ]);
        exit;
    }

    if ($isLegacyPassword) {
        $update = $conn->prepare(
            "UPDATE employee SET password = :password WHERE emp_id = :id"
        );
        $update->execute([
            ":password" => password_hash($password, PASSWORD_DEFAULT),
            ":id" => $user['emp_id']
        ]);
    }

    echo json_encode([
        "success" => true,
        "message" => "เข้าสู่ระบบสำเร็จ",
        "user" => [
            "id" => $user['emp_id'],
            "name" => $user['firstName'] . " " . $user['lastName'],
            "username" => $user['username']
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "เกิดข้อผิดพลาดในการเข้าสู่ระบบ"
    ]);
}