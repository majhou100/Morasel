<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<section class="chat-section">
  <h2>صفحة الدردشة</h2>
  <div class="chat-box">
    <p>واجهة الدردشة (تحديث لاحقاً باستخدام WebSocket أو AJAX).</p>
  </div>
  <form action="chat.php" method="post" class="chat-form">
    <input type="text" name="message" placeholder="اكتب رسالتك هنا..." required>
    <button type="submit" class="btn">إرسال</button>
  </form>
</section>
<?php include 'includes/footer.php'; ?>
