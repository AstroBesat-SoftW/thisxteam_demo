<?php
$active = 'explore';
$pageTitle = 'Explore';
require_once __DIR__ . '/header.php';

$q = trim($_GET['q'] ?? '');
$users = read_data('users');
$people = array_filter($users, function($u) use ($me, $q) {
    if ($u['id'] == $me['id']) return false;
    if ($q === '') return true;
    $hay = strtolower($u['name'] . ' ' . ($u['headline'] ?? ''));
    return strpos($hay, strtolower($q)) !== false;
});
?>
<h1 class="page-title">Explore</h1>
<?php if (empty($people)): ?>
  <div class="empty-state">Henuz gosterilecek kimse yok.</div>
<?php else: ?>
<div class="grid">
  <?php foreach ($people as $u):
        $conn = connection_between($me['id'], $u['id']);
  ?>
    <div class="people-card">
      <a href="profile.php?id=<?= $u['id'] ?>">
        <img class="avatar" src="<?= h(avatar_of($u)) ?>" alt="">
        <div class="name"><?= h($u['name']) ?><?php if (!empty($u['verified'])): ?><span class="badge-check">✔</span><?php endif; ?></div>
      </a>
      <div class="headline"><?= h($u['headline'] ?: 'Baslik eklenmemis') ?></div>
      <?php if (!$conn): ?>
        <a class="btn btn-primary btn-sm" href="connect.php?action=send&to=<?= $u['id'] ?>&back=explore.php">Baglanti Gonder</a>
      <?php elseif ($conn['status'] === 'pending' && $conn['from_id'] == $me['id']): ?>
        <span class="btn btn-sm conn-pending">Istek Gonderildi</span>
      <?php elseif ($conn['status'] === 'pending' && $conn['to_id'] == $me['id']): ?>
        <a class="btn btn-primary btn-sm" href="requests.php">Isteği Yanitla</a>
      <?php elseif ($conn['status'] === 'accepted'): ?>
        <a class="btn btn-sm conn-accepted" href="chat.php?id=<?= $u['id'] ?>">Mesaj Gonder</a>
      <?php else: ?>
        <a class="btn btn-primary btn-sm" href="connect.php?action=send&to=<?= $u['id'] ?>&back=explore.php">Tekrar Gonder</a>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/footer.php'; ?>
