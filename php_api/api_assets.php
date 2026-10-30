<?php
include 'condb.php';

function saveAssetImage(): ?string
{
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('อัปโหลดรูปภาพไม่สำเร็จ');
    }

    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
        throw new RuntimeException('รองรับเฉพาะไฟล์ JPG, PNG, GIF และ WEBP');
    }

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('ไม่สามารถสร้างโฟลเดอร์จัดเก็บรูปภาพ');
    }

    $filename = bin2hex(random_bytes(8)) . '.' . $extension;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
        throw new RuntimeException('ไม่สามารถบันทึกรูปภาพได้');
    }

    return $filename;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $conn->query('SELECT * FROM assets ORDER BY asset_id DESC');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'ไม่รองรับคำขอนี้']);
        exit;
    }

    $action = $_POST['action'] ?? '';
    $assetCode = trim($_POST['asset_code'] ?? '');
    $assetName = trim($_POST['asset_name'] ?? '');
    $purchaseDate = ($_POST['purchase_date'] ?? '') ?: null;
    $price = ($_POST['price'] ?? '') !== '' ? $_POST['price'] : null;

    if (in_array($action, ['add', 'update'], true) && ($assetCode === '' || $assetName === '')) {
        http_response_code(400);
        echo json_encode(['error' => 'กรุณากรอกรหัสและชื่อครุภัณฑ์']);
        exit;
    }

    if ($action === 'add') {
        $image = saveAssetImage();
        $stmt = $conn->prepare(
            'INSERT INTO assets (asset_code, asset_name, purchase_date, price, image)
             VALUES (:asset_code, :asset_name, :purchase_date, :price, :image)'
        );
        $stmt->execute([
            ':asset_code' => $assetCode,
            ':asset_name' => $assetName,
            ':purchase_date' => $purchaseDate,
            ':price' => $price,
            ':image' => $image
        ]);
        echo json_encode(['message' => 'เพิ่มข้อมูลครุภัณฑ์สำเร็จ']);
        exit;
    }

    if ($action === 'update') {
        $assetId = (int)($_POST['asset_id'] ?? 0);
        if ($assetId < 1) {
            throw new InvalidArgumentException('ไม่พบรหัสครุภัณฑ์ที่ต้องการแก้ไข');
        }

        $image = saveAssetImage();
        $imageSql = $image !== null ? ', image = :image' : '';
        $stmt = $conn->prepare(
            "UPDATE assets SET asset_code = :asset_code, asset_name = :asset_name,
             purchase_date = :purchase_date, price = :price$imageSql WHERE asset_id = :asset_id"
        );
        $params = [
            ':asset_code' => $assetCode,
            ':asset_name' => $assetName,
            ':purchase_date' => $purchaseDate,
            ':price' => $price,
            ':asset_id' => $assetId
        ];
        if ($image !== null) {
            $params[':image'] = $image;
        }
        $stmt->execute($params);
        echo json_encode(['message' => 'แก้ไขข้อมูลครุภัณฑ์สำเร็จ']);
        exit;
    }

    if ($action === 'delete') {
        $assetId = (int)($_POST['asset_id'] ?? 0);
        $stmt = $conn->prepare('DELETE FROM assets WHERE asset_id = :asset_id');
        $stmt->execute([':asset_id' => $assetId]);
        echo json_encode(['message' => 'ลบข้อมูลครุภัณฑ์สำเร็จ']);
        exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'คำสั่งไม่ถูกต้อง']);
} catch (Throwable $error) {
    http_response_code(500);
    echo json_encode(['error' => $error->getMessage()]);
}
?>
