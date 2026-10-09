<?php

  // เชื่อมต่อฐานข้อมูล
include 'condb.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  exit;
}

try {
    $method = $_SERVER['REQUEST_METHOD'];

  if ($method === 'GET') {
        // ดึงข้อมูลลูกค้าทั้งหมด
        $stmt = $conn->prepare("SELECT * FROM customers");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $data]);
  } elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $customerId = filter_var($data['customer_id'] ?? null, FILTER_VALIDATE_INT);

    if ($customerId === false || $customerId === null || $customerId < 1) {
      http_response_code(400);
      echo json_encode(["success" => false, "message" => "รหัสลูกค้าไม่ถูกต้อง"]);
      exit;
    }

    $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = :customer_id");
    $stmt->execute([':customer_id' => $customerId]);

    if ($stmt->rowCount() === 0) {
      http_response_code(404);
      echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลลูกค้า"]);
    } else {
      echo json_encode(["success" => true, "message" => "ลบข้อมูลลูกค้าเรียบร้อย"]);
    }
  } else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
  }

}catch (PDOException $e) {
  http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
