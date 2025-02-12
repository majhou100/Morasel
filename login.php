<?php 
include 'includes/header.php';
include 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $stmt = $conn->prepare("SELECT id, full_name, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['is_admin']  = $user['is_admin'];
            header("Location: profile.php");
            exit;
        } else {
            $error = "كلمة المرور غير صحيحة.";
        }
    } else {
        $error = "البريد الإلكتروني غير مسجل.";
    }
    $stmt->close();
}
?>
<section class="form-section">
  <h2>تسجيل الدخول</h2>
  <?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>
  <form action="login.php" method="post">
    <label for="email">البريد الإلكتروني</label>
    <input type="email" name="email" id="email" required>
    <label for="password">كلمة المرور</label>
    <input type="password" name="password" id="password" required>
    <button type="submit" class="btn">دخول</button>
  </form>
</section>
<?php include 'includes/footer.php'; ?>
