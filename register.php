<?php
require_once __DIR__ . '/config.php';

if (current_user()) { header('Location: explore.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$password) {
        $error = 'Lutfen tum alanlari doldur.';
    } elseif (find_user_by_email($email)) {
        $error = 'Bu e-posta zaten kayitli.';
    } else {
        $users = read_data('users');
        $id = next_id($users);
        $users[] = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'headline' => '',
            'location_live' => '',
            'location_want' => '',
            'school' => '',
            'languages' => '',
            'workplaces' => '',
            'bio' => '',
            'contact' => '',
            'app_language' => 'Turkce',
            'verified' => false,
            'avatar' => '',
            'created_at' => now_ts(),
        ];
        write_data('users', $users);
        header('Location: index.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Kayit Ol - TeamUpp</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-box">
    <div class="brand"><div class="logo-box">T</div> TeamUpp</div>
    <?php if ($error): ?><div class="error-msg"><?= h($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="form-field">
        <label>Ad Soyad</label>
        <input type="text" name="name" required placeholder="Ad Soyad">
      </div>
      <div class="form-field">
        <label>E-posta</label>
        <input type="email" name="email" required placeholder="ornek@mail.com">
      </div>
      <div class="form-field">
        <label>Sifre</label>
        <input type="password" name="password" required placeholder="********">
      </div>
      <button class="btn btn-primary" type="submit">Kayit Ol</button>
    </form>
    <div class="auth-switch">Zaten hesabin var mi? <a href="index.php">Giris yap</a></div>
  </div>
</div>
</body>
</html>
