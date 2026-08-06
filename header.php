<?php
/**
 * Bu dosya her sayfanin basinda require_once ile cagrilir.
 * $active degiskeni ile hangi menu ogesinin aktif oldugu belirlenir.
 */
require_once __DIR__ . '/config.php';
require_login();
$me = current_user();
if (!isset($active)) $active = '';
$pendingCount = 0;
foreach (read_data('connections') as $c) {
    if ($c['to_id'] == $me['id'] && $c['status'] === 'pending') $pendingCount++;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? h($pageTitle) . ' - ' : '' ?>TeamUpp</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app">
  <div class="sidebar">
    <div>
      <div class="brand"><div class="logo-box">T</div> TeamUpp</div>
      <a class="nav-item <?= $active==='explore'?'active':'' ?>" href="explore.php"><span class="nav-icon">🔍</span> Explore</a>
      <a class="nav-item <?= $active==='posts'?'active':'' ?>" href="posts.php"><span class="nav-icon">📁</span> Posts</a>
      <a class="nav-item <?= $active==='flow'?'active':'' ?>" href="flow.php"><span class="nav-icon">💬</span> Flow</a>
      <a class="nav-item <?= $active==='chats'?'active':'' ?>" href="chats.php"><span class="nav-icon">✈️</span> Chats</a>
      <a class="nav-item <?= $active==='requests'?'active':'' ?>" href="requests.php">
        <span class="nav-icon">🔔</span> Baglanti Istekleri
        <?php if ($pendingCount): ?><span style="margin-left:auto;background:#fff;color:#1450c9;border-radius:10px;padding:2px 8px;font-size:12px;font-weight:800;"><?= $pendingCount ?></span><?php endif; ?>
      </a>
      <hr class="nav-sep">
      <a class="nav-item" href="logout.php"><span class="nav-icon">🚪</span> Cikis Yap</a>
    </div>
    <a class="sidebar-bottom" href="profile.php?id=<?= $me['id'] ?>">
      <img src="<?= h(avatar_of($me)) ?>" alt="">
      <div><?= h($me['name']) ?><small>Profilim</small></div>
    </a>
  </div>
  <div class="mobile-topbar">
    <div class="brand"><div class="logo-box">T</div> TeamUpp</div>
    <div class="mobile-topbar-actions">
      <a class="mobile-search-btn" href="explore.php">🔍</a>
      <a class="mobile-avatar" href="profile.php?id=<?= $me['id'] ?>">
        <img src="<?= h(avatar_of($me)) ?>" alt="">
      </a>
    </div>
  </div>

  <div class="main">
    <div class="topbar">
      <form class="search-box" method="get" action="explore.php">
        <span>🔍</span>
        <input type="text" name="q" placeholder="Ara" value="<?= h($_GET['q'] ?? '') ?>">
      </form>
    </div>
    <div class="content">
