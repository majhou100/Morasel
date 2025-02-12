<?php
include 'includes/header.php';
include 'includes/db.php';

$unique_link = $_GET['u'] ?? '';
$error = '';
$success = '';

if (!$unique_link) {
    echo "<p>رابط غير صالح.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT id, full_name, unique_link FROM users WHERE unique_link = ?");
$stmt->bind_param("s", $unique_link);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<p>المستخدم غير موجود.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = trim($_POST['message']);
    if ($message) {
        $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user['id'], $message);
        if ($stmt->execute()) {
            $success = "تم إرسال الرسالة بنجاح.";
        } else {
            $error = "حدث خطأ أثناء إرسال الرسالة.";
        }
        $stmt->close();
    } else {
        $error = "الرجاء كتابة الرسالة.";
    }
}
?>
<section class="message-section">
  <h2>إرسال رسالة مجهولة لـ <?php echo htmlspecialchars($user['full_name']); ?></h2>
  <?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <p class="success"><?php echo $success; ?></p>
  <?php endif; ?>
  <form action="message.php?u=<?php echo $unique_link; ?>" method="post" enctype="multipart/form-data">
    <textarea name="message" placeholder="اكتب رسالتك هنا..." required></textarea>
    <button type="submit" class="btn">إرسال الرسالة</button>
  </form>
</section>
<?php include 'includes/footer.php'; ?>
