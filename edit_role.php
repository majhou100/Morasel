<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        echo "<p>المستخدم غير موجود.</p>";
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_role = intval($_POST['role']);
        $stmt = $conn->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_role, $user_id);
        if ($stmt->execute()) {
            $success = "تم تعديل الصلاحيات بنجاح.";
        } else {
            $error = "حدث خطأ أثناء تعديل الصلاحيات.";
        }
        $stmt->close();
    }
} else {
    echo "<p>رقم المستخدم غير محدد.</p>";
    exit;
}
?>
<section class="form-section">
  <h2>تعديل صلاحيات المستخدم</h2>
  <?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <p class="success"><?php echo $success; ?></p>
  <?php endif; ?>
  <form action="edit_role.php?id=<?php echo $user_id; ?>" method="post">
    <label for="role">اختر الدور:</label>
    <select name="role" id="role" required>
      <option value="0" <?php if($user['is_admin'] == 0) echo 'selected'; ?>>مستخدم</option>
      <option value="1" <?php if($user['is_admin'] == 1) echo 'selected'; ?>>مدير</option>
      <option value="2" <?php if($user['is_admin'] == 2) echo 'selected'; ?>>مشرف</option>
      <option value="3" <?php if($user['is_admin'] == 3) echo 'selected'; ?>>مساعد</option>
    </select>
    <button type="submit" class="btn">تحديث</button>
  </form>
</section>
<a href="admin_dashboard.php">عودة للوحة الأدمن</a>
<?php include 'includes/footer.php'; ?>
