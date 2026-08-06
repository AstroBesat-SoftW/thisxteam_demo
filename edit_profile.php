<?php
$active = 'profile';
$pageTitle = 'Profili Duzenle';
require_once __DIR__ . '/header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'name' => trim($_POST['name'] ?? $me['name']),
        'headline' => trim($_POST['headline'] ?? ''),
        'location_want' => trim($_POST['location_want'] ?? ''),
        'location_live' => trim($_POST['location_live'] ?? ''),
        'school' => trim($_POST['school'] ?? ''),
        'languages' => trim($_POST['languages'] ?? ''),
        'workplaces' => trim($_POST['workplaces'] ?? ''),
        'bio' => trim($_POST['bio'] ?? ''),
        'contact' => trim($_POST['contact'] ?? ''),
        'app_language' => trim($_POST['app_language'] ?? 'Turkce'),
    ];
    $avatarName = save_uploaded_image('avatar', AVATAR_DIR, AVATAR_URL);
    if ($avatarName) $fields['avatar'] = $avatarName;

    update_user($me['id'], $fields);
    header('Location: profile.php?id=' . $me['id']);
    exit;
}
?>
<h1 class="page-title">Profili Duzenle</h1>
<div class="card" style="max-width:640px;">
  <form method="post" enctype="multipart/form-data">
    <div class="form-field">
      <label>Profil Fotografi</label>
      <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px;">
        <img src="<?= h(avatar_of($me)) ?>" style="width:64px;height:64px;border-radius:50%;object-fit:cover;">
        <input type="file" name="avatar" accept="image/*">
      </div>
    </div>
    <div class="form-field">
      <label>Ad Soyad</label>
      <input type="text" name="name" value="<?= h($me['name']) ?>">
    </div>
    <div class="form-field">
      <label>Baslik (Unvan)</label>
      <input type="text" name="headline" value="<?= h($me['headline'] ?? '') ?>" placeholder="Orn: TEKNOFEST Team Captain & Project Developer">
    </div>
    <div class="form-field">
      <label>Her Zaman Gitmek Istedigim Yer</label>
      <input type="text" name="location_want" value="<?= h($me['location_want'] ?? '') ?>">
    </div>
    <div class="form-field">
      <label>Yasadigim Yer</label>
      <input type="text" name="location_live" value="<?= h($me['location_live'] ?? '') ?>">
    </div>
    <div class="form-field">
      <label>Hangi Okula Gittin</label>
      <input type="text" name="school" value="<?= h($me['school'] ?? '') ?>">
    </div>
    <div class="form-field">
      <label>Hangi Dilleri Konusuyorsun</label>
      <input type="text" name="languages" value="<?= h($me['languages'] ?? '') ?>" placeholder="Turkce, Ingilizce ...">
    </div>
    <div class="form-field">
      <label>Calistigin Yerler / Calisma Yerin</label>
      <input type="text" name="workplaces" value="<?= h($me['workplaces'] ?? '') ?>">
    </div>
    <div class="form-field">
      <label>Biyografinizde Ne Olurdu?</label>
      <textarea name="bio" rows="5"><?= h($me['bio'] ?? '') ?></textarea>
    </div>
    <div class="form-field">
      <label>Iletisim Bilgileri</label>
      <input type="text" name="contact" value="<?= h($me['contact'] ?? '') ?>">
    </div>
    <div class="form-field">
      <label>Uygulama Dili</label>
      <select name="app_language">
        <option value="Turkce" <?= ($me['app_language'] ?? '')==='Turkce'?'selected':'' ?>>Turkce</option>
        <option value="English" <?= ($me['app_language'] ?? '')==='English'?'selected':'' ?>>English</option>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">Kaydet</button>
    <a class="btn btn-gray" href="profile.php?id=<?= $me['id'] ?>">Iptal</a>
  </form>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
