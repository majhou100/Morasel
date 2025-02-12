<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT full_name, email, phone, age, unique_link, is_verified FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<section class="profile-section">
  <h2>الملف الشخصي</h2>
  <div class="profile-card">
    <p><strong>الاسم:</strong> <?php echo $user['full_name']; ?></p>
    <p><strong>البريد الإلكتروني:</strong> <?php echo $user['email']; ?></p>
    <p><strong>رقم الهاتف:</strong> <?php echo $user['phone']; ?></p>
    <p><strong>العمر:</strong> <?php echo $user['age']; ?></p>
    <p><strong>رابط استقبال الرسائل المجهولة:</strong> 
      <a href="message.php?u=<?php echo $user['unique_link']; ?>" target="_blank">اضغط هنا</a>
    </p>
    <?php if ($user['is_verified']): ?>
      <p><span class="badge verified">موثق</span></p>
    <?php endif; ?>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
