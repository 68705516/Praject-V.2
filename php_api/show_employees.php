<?php
include 'condb.php';

try {
    // ชื่อตารางในฐานข้อมูลคือ employee (ไม่ใช่ employees)
    $stmt = $conn->query("SELECT * FROM employee");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["success" => true, "data" => $data]);
    
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
