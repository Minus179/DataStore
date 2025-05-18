<?php
header('Content-Type: application/json');

// Cấu hình kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datastore_food";

// Kết nối DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối database thất bại']);
    exit;
}

// Lấy dữ liệu từ POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? intval($_POST['price']) : 0;
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';

// Validate dữ liệu
if ($id <= 0 || $name === '' || $price < 0 || !in_array($type, ['food', 'drink'])) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$imageUrl = null;

// Xử lý upload ảnh nếu có
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileMimeType = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($fileMimeType, $allowedMimeTypes)) {
        echo json_encode(['success' => false, 'message' => 'Ảnh phải là JPG, PNG hoặc GIF']);
        exit;
    }

    // Tạo thư mục upload nếu chưa tồn tại
    $uploadDir = __DIR__ . '/../assets/uploads/menu_images/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Không tạo được thư mục lưu ảnh']);
        exit;
    }

    // Đổi tên file ảnh tránh trùng
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid('menu_', true) . '.' . $ext;
    $uploadFilePath = $uploadDir . $newFileName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFilePath)) {
        echo json_encode(['success' => false, 'message' => 'Không lưu được ảnh']);
        exit;
    }

    // Đường dẫn URL ảnh dùng trong frontend
    $imageUrl = '/assets/uploads/menu_images/' . $newFileName;
}

// Câu lệnh SQL cập nhật
if ($imageUrl !== null) {
    $stmt = $conn->prepare("UPDATE menu SET name=?, price=?, type=?, description=?, image_url=? WHERE id=?");
    $stmt->bind_param("sisssi", $name, $price, $type, $description, $imageUrl, $id);
} else {
    $stmt = $conn->prepare("UPDATE menu SET name=?, price=?, type=?, description=? WHERE id=?");
    $stmt->bind_param("sissi", $name, $price, $type, $description, $id);
}

// Thực thi câu lệnh và trả kết quả
if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Cập nhật thành công',
        'imageUrl' => $imageUrl
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi cập nhật: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
