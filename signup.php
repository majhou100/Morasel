<?php
include 'includes/header.php';
include 'includes/db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $password  = trim($_POST['password']);
    $age       = trim($_POST['age']);
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = "البريد الإلكتروني مستخدم بالفعل.";
    }
    $stmt->close();
    
    if (!$error) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $unique_link = md5(uniqid(rand(), true));
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, age, unique_link, is_admin) VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("ssssss", $full_name, $email, $phone, $hashed_password, $age, $unique_link);
        if ($stmt->execute()) {
            $success = "تم إنشاء الحساب بنجاح. يمكنك الآن <a href='login.php'>تسجيل الدخول</a>.";
        } else {
            $error = "حدث خطأ أثناء إنشاء الحساب.";
        }
        $stmt->close();
    }
}
?>
<section class="form-section">
  <h2>إنشاء حساب</h2>
  <?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <p class="success"><?php echo $success; ?></p>
  <?php else: ?>
  <form action="signup.php" method="post">
    <label for="full_name">الاسم الكامل</label>
    <input type="text" name="full_name" id="full_name" required>
    <label for="email">البريد الإلكتروني</label>
    <input type="email" name="email" id="email" required>
    <label for="phone">رقم الهاتف</label>
    <input type="text" name="phone" id="phone" required>
    <label for="password">كلمة المرور</label>
    <input type="password" name="password" id="password" required>
    <label for="age">العمر</label>
    <input type="number" name="age" id="age" required>
    <button type="submit" class="btn">إنشاء الحساب</button>
  </form>
  <?php endif; ?>
</section>
<?php include 'includes/footer.php'; ?>
