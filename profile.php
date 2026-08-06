<?php
$active = 'profile';
require_once __DIR__ . '/config.php';
require_login();
$me = current_user();

$id = (int)($_GET['id'] ?? $me['id']);
$user = find_user($id);
if (!$user) { header('Location: explore.php'); exit; }
$isMe = ($user['id'] == $me['id']);
$conn = $isMe ? null : connection_between($me['id'], $user['id']);

$pageTitle = $user['name'];
require_once __DIR__ . '/header.php';

$posts = array_filter(read_data('posts'), function($p) use ($user) { return $p['user_id'] == $user['id']; });
usort($posts, function($a,$b){ return strtotime($b['created_at']) <=> strtotime($a['created_at']); });
?>
<div class="profile-cover"></div>
<div class="profile-head">
  <div class="profile-avatar-wrap">
    <img src="<?= h(avatar_of($user)) ?>" alt="">
  </div>
  <div class="profile-name"><?= h($user['name']) ?><?php if (!empty($user['verified'])): ?><span class="badge-check">✔</span><?php endif; ?></div>
  <div class="profile-headline"><?= h($user['headline'] ?: 'Baslik eklenmemis') ?><?php if ($user['location_live']): ?> &middot; <?= h($user['location_live']) ?><?php endif; ?></div>

  <?php if ($isMe): ?>
    <a class="btn btn-primary btn-sm" href="edit_profile.php">Profili Duzenle</a>
  <?php elseif (!$conn): ?>
    <a class="btn btn-primary btn-sm" href="connect.php?action=send&to=<?= $user['id'] ?>&back=profile.php?id=<?= $user['id'] ?>">Baglanti Gonder</a>
  <?php elseif ($conn['status'] === 'pending' && $conn['from_id'] == $me['id']): ?>
    <span class="btn btn-sm conn-pending">Istek Gonderildi</span>
  <?php elseif ($conn['status'] === 'pending' && $conn['to_id'] == $me['id']): ?>
    <a class="btn btn-primary btn-sm" href="connect.php?action=accept&id=<?= $conn['id'] ?>&back=profile.php?id=<?= $user['id'] ?>">Istegi Kabul Et</a>
    <a class="btn btn-gray btn-sm" href="connect.php?action=reject&id=<?= $conn['id'] ?>&back=profile.php?id=<?= $user['id'] ?>">Reddet</a>
  <?php elseif ($conn['status'] === 'accepted'): ?>
    <a class="btn btn-sm conn-accepted" href="chat.php?id=<?= $user['id'] ?>">✉ Mesaj Gonder</a>
  <?php endif; ?>
</div>

<div class="about-box">
  <h2>Hakkinda</h2>
  <p><?= nl2br(h($user['bio'] ?: 'Henuz bir aciklama eklenmemis.')) ?></p>
  <div class="about-grid">
    <div class="about-item"><div class="label">Her Zaman Gitmek Istedigim Yer</div><div class="value"><?= h($user['location_want'] ?: 'yok') ?></div></div>
    <div class="about-item"><div class="label">Yasadigi Yer</div><div class="value"><?= h($user['location_live'] ?: 'yok') ?></div></div>
    <div class="about-item"><div class="label">Okul</div><div class="value"><?= h($user['school'] ?: 'yok') ?></div></div>
    <div class="about-item"><div class="label">Diller</div><div class="value"><?= h($user['languages'] ?: 'yok') ?></div></div>
    <div class="about-item"><div class="label">Calisma Yerleri</div><div class="value"><?= h($user['workplaces'] ?: 'yok') ?></div></div>
    <div class="about-item"><div class="label">Iletisim</div><div class="value"><?= h($user['contact'] ?: 'yok') ?></div></div>
  </div>
</div>

<div class="section-title">Gonderiler</div>
<?php if (empty($posts)): ?>
  <div class="empty-state">Henuz gonderi yok.</div>
<?php else: foreach ($posts as $p): ?>
  <div class="post">
    <div class="post-header">
      <img src="<?= h(avatar_of($user)) ?>">
      <div>
        <div class="pname"><?= h($user['name']) ?></div>
        <div class="ptime"><?= time_ago($p['created_at']) ?></div>
      </div>
    </div>
    <?php if ($p['text']): ?><div class="post-text"><?= nl2br(h($p['text'])) ?></div><?php endif; ?>
    <?php if ($p['image']): ?><img class="post-image" src="<?= POST_IMG_URL . '/' . h($p['image']) ?>"><?php endif; ?>
  </div>
<?php endforeach; endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
