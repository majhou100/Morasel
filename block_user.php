<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $blocked_until = date('Y-m-d H:i:s', strtotime('+1 day'));
    $stmt = $conn->prepare("UPDATE users SET blocked_until = ? WHERE id = ?");
    $stmt->bind_param("si", $blocked_until, $id);
    if ($stmt->execute()) {
        echo "<p>تم حظر المستخدم حتى $blocked_until.</p>";
    } else {
        echo "<p>حدث خطأ أثناء حظر المستخدم.</p>";
    }
    $stmt->close();
} else {
    echo "<p>رقم المستخدم غير محدد.</p>";
}

echo "<a href='admin_dashboard.php'>عودة للوحة الأدمن</a>";
include 'includes/footer.php';
?>
