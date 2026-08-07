
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
