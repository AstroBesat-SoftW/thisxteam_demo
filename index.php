<?php
require_once __DIR__ . '/config.php';

if (current_user()) { header('Location: explore.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = find_user_by_email($email);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: explore.php');
        exit;
    } else {
        $error = 'E-posta veya sifre hatali.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Giris Yap - TeamUpp</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-box">
    <div class="brand"><div class="logo-box">T</div> TeamUpp</div>
    <?php if ($error): ?><div class="error-msg"><?= h($error) ?></div><?php endif; ?>
    <?php if (isset($_GET['registered'])): ?><div class="success-msg">Kayit basarili, simdi giris yapabilirsin.</div><?php endif; ?>
    <form method="post">
      <div class="form-field">
        <label>E-posta</label>
        <input type="email" name="email" required placeholder="ornek@mail.com">
      </div>
      <div class="form-field">
        <label>Sifre</label>
        <input type="password" name="password" required placeholder="********">
      </div>
      <button class="btn btn-primary" type="submit">Giris Yap</button>
    </form>
    <div class="auth-switch">Hesabin yok mu? <a href="register.php">Kayit ol</a></div>
  </div>
</div>
</body>
</html>
