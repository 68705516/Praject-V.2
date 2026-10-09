<?php
include 'condb.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $conn->query("SELECT * FROM employee");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $data]);
    } elseif ($method === 'DELETE') {
        $data = json_decode(file_get_contents("php://input"), true);
        $employeeId = filter_var($data['emp_id'] ?? null, FILTER_VALIDATE_INT);

        if ($employeeId === false || $employeeId === null || $employeeId < 1) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "รหัสพนักงานไม่ถูกต้อง"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM employee WHERE emp_id = :emp_id");
        $stmt->execute([':emp_id' => $employeeId]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลพนักงาน"]);
        } else {
            echo json_encode(["success" => true, "message" => "ลบข้อมูลพนักงานเรียบร้อย"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Invalid request method"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
