<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT id, full_name, email, phone, age, created_at FROM users");
?>
<section class="admin-section">
  <h2>لوحة تحكم الأدمن</h2>
  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>الاسم الكامل</th>
        <th>البريد الإلكتروني</th>
        <th>رقم الهاتف</th>
        <th>العمر</th>
        <th>تاريخ التسجيل</th>
        <th>إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['full_name']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td><?php echo $user['phone']; ?></td>
        <td><?php echo $user['age']; ?></td>
        <td><?php echo $user['created_at']; ?></td>
        <td>
          <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a> |
          <a href="impersonate.php?id=<?php echo $user['id']; ?>">تسجيل الدخول المباشر</a> |
          <a href="block_user.php?id=<?php echo $user['id']; ?>">حظر</a> |
          <a href="edit_role.php?id=<?php echo $user['id']; ?>">تعديل الصلاحيات</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>
<?php include 'includes/footer.php'; ?>
