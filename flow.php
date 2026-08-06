<?php
$active = 'flow';
$pageTitle = 'Flow';
require_once __DIR__ . '/header.php';

$posts = read_data('posts');
usort($posts, function($a,$b){ return strtotime($b['created_at']) <=> strtotime($a['created_at']); });
?>
<h1 class="page-title">Flow</h1>
<?php if (empty($posts)): ?>
  <div class="empty-state">Akista henuz gonderi yok.</div>
<?php else: foreach ($posts as $p):
    $author = find_user($p['user_id']);
    if (!$author) continue;
?>
  <div class="post">
    <div class="post-header">
      <a href="profile.php?id=<?= $author['id'] ?>"><img src="<?= h(avatar_of($author)) ?>"></a>
      <div>
        <a href="profile.php?id=<?= $author['id'] ?>" class="pname"><?= h($author['name']) ?></a>
        <div class="ptime"><?= time_ago($p['created_at']) ?></div>
      </div>
    </div>
    <?php if ($p['text']): ?><div class="post-text"><?= nl2br(h($p['text'])) ?></div><?php endif; ?>
    <?php if ($p['image']): ?><img class="post-image" src="<?= POST_IMG_URL . '/' . h($p['image']) ?>"><?php endif; ?>
  </div>
<?php endforeach; endif; ?>
<?php require_once __DIR__ . '/footer.php'; ?>
