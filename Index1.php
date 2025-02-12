<?php
// تفعيل عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

// بدء الجلسة
session_start();

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "messaging_app";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// تحديد الصفحة المطلوب عرضها بناءً على معامل GET "page"
$page = isset($_GET['page']) ? $_GET['page'] : 'index';
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>مراسل</title>
  <!-- تضمين الخطوط من Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    /* إعادة تعيين الأنماط */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #fff;
      color: #000;
      line-height: 1.6;
    }
    /* الترويسة */
    .header {
      background-color: #fff;
      border-bottom: 1px solid #ddd;
      padding: 10px 20px;
    }
    .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #000;
      text-decoration: none;
    }
    .nav-links {
      list-style: none;
      display: flex;
    }
    .nav-links li {
      margin-left: 20px;
    }
    .nav-links a {
      text-decoration: none;
      color: #000;
      transition: color 0.3s ease;
    }
    .nav-links a:hover {
      color: #c62828;
    }
    /* قسم البطل (Hero Section) */
    .hero {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 80vh;
      /* يمكنك استبدال الخلفية بصورة من Webstock، تأكد من تحميلها على الخادم */
      background: linear-gradient(rgba(198, 40, 40, 0.7), rgba(198, 40, 40, 0.7)),
                  url('images/hero-bg.jpg') center/cover no-repeat;
      text-align: center;
      color: #fff;
    }
    .hero-content h1 {
      font-size: 48px;
      margin-bottom: 20px;
    }
    .hero-content p {
      font-size: 20px;
      margin-bottom: 30px;
    }
    .btn {
      background-color: #c62828;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .btn:hover {
      background-color: #b71c1c;
    }
    /* أقسام النماذج */
    .form-section {
      max-width: 500px;
      margin: 40px auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .form-section h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #c62828;
    }
    .form-section label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .form-section input,
    .form-section textarea,
    .form-section select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }
    .form-section .error {
      color: #b71c1c;
      margin-bottom: 10px;
    }
    .form-section .success {
      color: #2e7d32;
      margin-bottom: 10px;
    }
    /* الملف الشخصي */
    .profile-section {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
    }
    .profile-card {
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 5px;
    }
    .badge.verified {
      background-color: #c62828;
      color: #fff;
      padding: 5px 10px;
      border-radius: 3px;
    }
    /* لوحة الأدمن */
    .admin-section {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
    }
    .admin-table {
      width: 100%;
      border-collapse: collapse;
    }
    .admin-table th,
    .admin-table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    .admin-table th {
      background-color: #c62828;
      color: #fff;
    }
    /* قسم الدردشة */
    .chat-section {
      max-width: 700px;
      margin: 40px auto;
      padding: 20px;
    }
    .chat-box {
      border: 1px solid #ddd;
      height: 300px;
      overflow-y: scroll;
      padding: 10px;
      margin-bottom: 15px;
    }
    /* قسم استقبال الرسائل */
    .message-section {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
    }
    /* ذيل الصفحة */
    .footer {
      background-color: #fff;
      border-top: 1px solid #ddd;
      text-align: center;
      padding: 10px 0;
      margin-top: 40px;
    }
    /* دعم الوضع الليلي (اختياري) */
    .dark-mode {
      background-color: #121212;
      color: #fff;
    }
    .dark-mode .header,
    .dark-mode .footer {
      background-color: #1e1e1e;
    }
    .dark-mode .nav-links a {
      color: #fff;
    }
    .dark-mode .nav-links a:hover {
      color: #c62828;
    }
  </style>
</head>
<body>
  <!-- ترويسة الموقع -->
  <header class="header">
    <nav class="navbar">
      <a href="index.php" class="logo">مراسل</a>
      <ul class="nav-links">
        <li><a href="index.php?page=index">الرئيسية</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="index.php?page=profile">الملف الشخصي</a></li>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <li><a href="index.php?page=admin_dashboard">لوحة الأدمن</a></li>
          <?php endif; ?>
          <li><a href="index.php?page=logout">تسجيل الخروج</a></li>
        <?php else: ?>
          <li><a href="index.php?page=login">تسجيل الدخول</a></li>
          <li><a href="index.php?page=signup">إنشاء حساب</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <main>
    <?php
    // عرض المحتوى بناءً على قيمة المتغير $page
    switch ($page) {
      // الصفحة الرئيسية (الانترو)
      case 'index':
      default:
        ?>
        <section class="hero">
          <div class="hero-content">
            <h1>مرحباً بك في مراسل</h1>
            <p>منصة الرسائل والمحادثات الحديثة.</p>
            <a href="index.php?page=signup" class="btn">ابدأ الآن</a>
          </div>
        </section>
        <?php
        break;

      // صفحة تسجيل الدخول
      case 'login':
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
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
              header("Location: index.php?page=profile");
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
          <form action="index.php?page=login" method="post">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" required>
            <label for="password">كلمة المرور</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" name="login" class="btn">دخول</button>
          </form>
        </section>
        <?php
        break;

      // صفحة إنشاء الحساب
      case 'signup':
        $error = '';
        $success = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
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
              $success = "تم إنشاء الحساب بنجاح. يمكنك الآن <a href='index.php?page=login'>تسجيل الدخول</a>.";
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
            <form action="index.php?page=signup" method="post">
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
              <button type="submit" name="signup" class="btn">إنشاء الحساب</button>
            </form>
          <?php endif; ?>
        </section>
        <?php
        break;

      // صفحة الملف الشخصي
      case 'profile':
        if (!isset($_SESSION['user_id'])) {
          header("Location: index.php?page=login");
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
              <a href="index.php?page=message&u=<?php echo $user['unique_link']; ?>" target="_blank">اضغط هنا</a>
            </p>
            <?php if ($user['is_verified']): ?>
              <p><span class="badge verified">موثق</span></p>
            <?php endif; ?>
          </div>
        </section>
        <?php
        break;

      // لوحة تحكم الأدمن
      case 'admin_dashboard':
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
          header("Location: index.php?page=login");
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
                  <a href="index.php?page=delete_user&id=<?php echo $user['id']; ?>" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a> |
                  <a href="index.php?page=impersonate&id=<?php echo $user['id']; ?>">تسجيل الدخول المباشر</a> |
                  <a href="index.php?page=block_user&id=<?php echo $user['id']; ?>">حظر</a> |
                  <a href="index.php?page=edit_role&id=<?php echo $user['id']; ?>">تعديل الصلاحيات</a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </section>
        <?php
        break;

      // صفحة الدردشة
      case 'chat':
        if (!isset($_SESSION['user_id'])) {
          header("Location: index.php?page=login");
          exit;
        }
        ?>
        <section class="chat-section">
          <h2>صفحة الدردشة</h2>
          <div class="chat-box">
            <p>هذه واجهة الدردشة. سيتم تحديثها لاحقاً باستخدام WebSocket أو AJAX.</p>
          </div>
          <form action="index.php?page=chat" method="post" class="chat-form">
            <input type="text" name="message" placeholder="اكتب رسالتك هنا..." required>
            <button type="submit" class="btn">إرسال</button>
          </form>
        </section>
        <?php
        break;

      // صفحة استقبال الرسائل المجهولة
      case 'message':
        $unique_link = isset($_GET['u']) ? $_GET['u'] : '';
        $errorMsg = '';
        $successMsg = '';
        if (!$unique_link) {
          echo "<p>رابط غير صالح.</p>";
          break;
        }
        $stmt = $conn->prepare("SELECT id, full_name, unique_link FROM users WHERE unique_link = ?");
        $stmt->bind_param("s", $unique_link);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        if (!$user) {
          echo "<p>المستخدم غير موجود.</p>";
          break;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
          $message = trim($_POST['message']);
          if ($message) {
            $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
            $stmt->bind_param("is", $user['id'], $message);
            if ($stmt->execute()) {
              $successMsg = "تم إرسال الرسالة بنجاح.";
            } else {
              $errorMsg = "حدث خطأ أثناء إرسال الرسالة.";
            }
            $stmt->close();
          } else {
            $errorMsg = "الرجاء كتابة الرسالة.";
          }
        }
        ?>
        <section class="message-section">
          <h2>إرسال رسالة مجهولة لـ <?php echo htmlspecialchars($user['full_name']); ?></h2>
          <?php if ($errorMsg): ?>
            <p class="error"><?php echo $errorMsg; ?></p>
          <?php endif; ?>
          <?php if ($successMsg): ?>
            <p class="success"><?php echo $successMsg; ?></p>
          <?php endif; ?>
          <form action="index.php?page=message&u=<?php echo $unique_link; ?>" method="post" enctype="multipart/form-data">
            <textarea name="message" placeholder="اكتب رسالتك هنا..." required></textarea>
            <button type="submit" name="send_message" class="btn">إرسال الرسالة</button>
          </form>
        </section>
        <?php
        break;

      // تسجيل الخروج
      case 'logout':
        session_destroy();
        header("Location: index.php?page=index");
        exit;
        break;

      // تسجيل الدخول المباشر (Impersonation)
      case 'impersonate':
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
          header("Location: index.php?page=login");
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
            header("Location: index.php?page=profile");
            exit;
          } else {
            echo "<p>المستخدم غير موجود.</p>";
          }
        } else {
          echo "<p>رقم المستخدم غير محدد.</p>";
        }
        echo "<a href='index.php?page=admin_dashboard'>عودة للوحة الأدمن</a>";
        break;

      // حظر المستخدم
      case 'block_user':
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
          header("Location: index.php?page=login");
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
        echo "<a href='index.php?page=admin_dashboard'>عودة للوحة الأدمن</a>";
        break;

      // تعديل صلاحيات المستخدم
      case 'edit_role':
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
          header("Location: index.php?page=login");
          exit;
        }
        $errorRole = '';
        $successRole = '';
        if (isset($_GET['id'])) {
          $user_id = intval($_GET['id']);
          $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $userData = $result->fetch_assoc();
          $stmt->close();
          if (!$userData) {
            echo "<p>المستخدم غير موجود.</p>";
            break;
          }
          if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
            $new_role = intval($_POST['role']);
            $stmt = $conn->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_role, $user_id);
            if ($stmt->execute()) {
              $successRole = "تم تعديل الصلاحيات بنجاح.";
            } else {
              $errorRole = "حدث خطأ أثناء تعديل الصلاحيات.";
            }
            $stmt->close();
          }
        } else {
          echo "<p>رقم المستخدم غير محدد.</p>";
          break;
        }
        ?>
        <section class="form-section">
          <h2>تعديل صلاحيات المستخدم</h2>
          <?php if ($errorRole): ?>
            <p class="error"><?php echo $errorRole; ?></p>
          <?php endif; ?>
          <?php if ($successRole): ?>
            <p class="success"><?php echo $successRole; ?></p>
          <?php endif; ?>
          <form action="index.php?page=edit_role&id=<?php echo $user_id; ?>" method="post">
            <label for="role">اختر الدور:</label>
            <select name="role" id="role" required>
              <option value="0" <?php if($userData['is_admin'] == 0) echo 'selected'; ?>>مستخدم</option>
              <option value="1" <?php if($userData['is_admin'] == 1) echo 'selected'; ?>>مدير</option>
              <option value="2" <?php if($userData['is_admin'] == 2) echo 'selected'; ?>>مشرف</option>
              <option value="3" <?php if($userData['is_admin'] == 3) echo 'selected'; ?>>مساعد</option>
            </select>
            <button type="submit" name="update_role" class="btn">تحديث</button>
          </form>
        </section>
        <a href="index.php?page=admin_dashboard">عودة للوحة الأدمن</a>
        <?php
        break;
    }
    ?>
  </main>
  <footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> مراسل. جميع الحقوق محفوظة.</p>
  </footer>
  <script>
    // مثال على تفعيل الوضع الليلي (إذا أضفت زر لتبديله)
    document.addEventListener("DOMContentLoaded", function () {
      const toggleButton = document.getElementById('toggle-dark-mode');
      if (toggleButton) {
        toggleButton.addEventListener('click', function () {
          document.body.classList.toggle('dark-mode');
        });
      }
    });
  </script>
</body>
</html>
<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
