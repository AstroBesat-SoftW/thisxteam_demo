<?php
$active = 'requests';
$pageTitle = 'Baglanti Istekleri';
require_once __DIR__ . '/header.php';

$incoming = array_filter(read_data('connections'), function($c) use ($me) {
    return $c['to_id'] == $me['id'] && $c['status'] === 'pending';
});
?>
<h1 class="page-title">Baglanti Istekleri</h1>
<?php if (empty($incoming)): ?>
  <div class="empty-state">Bekleyen baglanti istegin yok.</div>
<?php else: ?>
  <?php foreach ($incoming as $c): $u = find_user($c['from_id']); if (!$u) continue; ?>
    <div class="post" style="display:flex;align-items:center;gap:14px;">
      <a href="profile.php?id=<?= $u['id'] ?>"><img src="<?= h(avatar_of($u)) ?>" style="width:50px;height:50px;border-radius:50%;object-fit:cover;"></a>
      <div style="flex:1;">
        <a href="profile.php?id=<?= $u['id'] ?>" style="font-weight:700;"><?= h($u['name']) ?></a>
        <div style="color:var(--text-gray);font-size:13px;"><?= h($u['headline'] ?: '') ?></div>
      </div>
      <a class="btn btn-primary btn-sm" href="connect.php?action=accept&id=<?= $c['id'] ?>&back=requests.php">Kabul Et</a>
      <a class="btn btn-gray btn-sm" href="connect.php?action=reject&id=<?= $c['id'] ?>&back=requests.php">Reddet</a>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
<?php require_once __DIR__ . '/footer.php'; ?>
