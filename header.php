<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>مراسل</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header">
    <nav class="navbar">
      <a href="index.php" class="logo">مراسل</a>
      <ul class="nav-links">
        <li><a href="index.php">الرئيسية</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="profile.php">الملف الشخصي</a></li>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <li><a href="admin_dashboard.php">لوحة الأدمن</a></li>
          <?php endif; ?>
          <li><a href="logout.php">تسجيل الخروج</a></li>
        <?php else: ?>
          <li><a href="login.php">تسجيل الدخول</a></li>
          <li><a href="signup.php">إنشاء حساب</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <main>
