<?php
include 'condb.php';
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

try {
    $method = $_SERVER['REQUEST_METHOD'];

    // ✅ ดึงข้อมูลลูกค้าทั้งหมด
    if ($method === "GET") {
        $stmt = $conn->prepare("SELECT customer_id, firstName, lastName, phone, username FROM customers ORDER BY customer_id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $result]);
    }

    // ✅ เพิ่มข้อมูลลูกค้า
    elseif ($method === "POST") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "ข้อมูลที่ส่งมาไม่ถูกต้อง"]);
            exit;
        }

        if (empty($data["firstName"]) || empty($data["lastName"]) || empty($data["phone"]) || empty($data["username"]) || empty($data["password"])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
            exit;
        }

        $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO customers (firstName, lastName, phone, username, password)
                                VALUES (:firstName, :lastName, :phone, :username, :password)");
        $stmt->execute([
            ":firstName" => $data["firstName"],
            ":lastName" => $data["lastName"],
            ":phone" => $data["phone"],
            ":username" => $data["username"],
            ":password" => $password_hash
        ]);
        echo json_encode(["success" => true, "message" => "เพิ่มข้อมูลลูกค้าเรียบร้อย"]);
    }

    // ✅ แก้ไขข้อมูล
    elseif ($method === "PUT") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data) || empty($data["customer_id"])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "ไม่พบค่า customer_id"]);
            exit;
        }

        $customer_id = intval($data["customer_id"]);

        if (empty($data["firstName"]) || empty($data["lastName"]) || empty($data["phone"]) || empty($data["username"])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "กรุณากรอกข้อมูลให้ครบ"]);
            exit;
        }

        if (!empty($data["password"])) {
            $password_hash = password_hash($data["password"], PASSWORD_BCRYPT);
            $sql = "UPDATE customers
                    SET firstName = :firstName,
                        lastName = :lastName,
                        phone = :phone,
                        username = :username,
                        password = :password
                    WHERE customer_id = :id";
        } else {
            $sql = "UPDATE customers
                    SET firstName = :firstName,
                        lastName = :lastName,
                        phone = :phone,
                        username = :username
                    WHERE customer_id = :id";
        }

        $params = [
            ":firstName" => $data["firstName"],
            ":lastName" => $data["lastName"],
            ":phone" => $data["phone"],
            ":username" => $data["username"],
            ":id" => $customer_id
        ];
        if (!empty($data["password"])) {
            $params[":password"] = $password_hash;
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        echo json_encode(["success" => true, "message" => "แก้ไขข้อมูลเรียบร้อย"]);
    }

    // ✅ ลบข้อมูล
    elseif ($method === "DELETE") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["customer_id"])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "ไม่พบค่า customer_id"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = :id");
        $stmt->bindParam(":id", $data["customer_id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "ลบข้อมูลเรียบร้อย"]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถลบข้อมูลได้"]);
        }
    }

    else {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Method ไม่ถูกต้อง"]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
