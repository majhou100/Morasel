<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        $_SESSION['impersonated'] = $_SESSION['user_id'];
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: profile.php");
        exit;
    } else {
        echo "<p>المستخدم غير موجود.</p>";
    }
} else {
    echo "<p>رقم المستخدم غير محدد.</p>";
}

echo "<a href='admin_dashboard.php'>عودة للوحة الأدمن</a>";
include 'includes/footer.php';
?>
