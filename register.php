<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ตรวจสอบว่าชื่อผู้ใช้มีอยู่แล้วหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว!";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // แฮชรหัสผ่านก่อนบันทึก
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // บันทึกผู้ใช้ใหม่
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "สมัครสมาชิกสำเร็จ! <a href='login.html'>เข้าสู่ระบบ</a>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
