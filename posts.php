<?php
$active = 'posts';
$pageTitle = 'Posts';
require_once __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['text'] ?? '');
    $imageName = save_uploaded_image('image', POST_IMG_DIR, POST_IMG_URL);
    if ($text !== '' || $imageName) {
        $posts = read_data('posts');
        $posts[] = [
            'id' => next_id($posts),
            'user_id' => $me['id'],
            'text' => $text,
            'image' => $imageName,
            'created_at' => now_ts(),
        ];
        write_data('posts', $posts);
    }
    header('Location: posts.php');
    exit;
}

$myPosts = array_filter(read_data('posts'), function($p) use ($me) { return $p['user_id'] == $me['id']; });
usort($myPosts, function($a,$b){ return strtotime($b['created_at']) <=> strtotime($a['created_at']); });
?>
<h1 class="page-title">Posts</h1>

<div class="card new-post-box">
  <form method="post" enctype="multipart/form-data">
    <textarea name="text" placeholder="Ne paylasmak istersin, <?= h($me['name']) ?>?"></textarea>
    <div class="new-post-actions">
      <label class="file-label">📷 <input type="file" name="image" accept="image/*" style="color:var(--text-gray);"></label>
      <button class="btn btn-primary btn-sm" type="submit">Paylas</button>
    </div>
  </form>
</div>

<?php if (empty($myPosts)): ?>
  <div class="empty-state">Henuz gonderin yok. Ilk gonderini paylas!</div>
<?php else: foreach ($myPosts as $p): ?>
  <div class="post">
    <div class="post-header">
      <img src="<?= h(avatar_of($me)) ?>">
      <div>
        <div class="pname"><?= h($me['name']) ?></div>
        <div class="ptime"><?= time_ago($p['created_at']) ?></div>
      </div>
    </div>
    <?php if ($p['text']): ?><div class="post-text"><?= nl2br(h($p['text'])) ?></div><?php endif; ?>
    <?php if ($p['image']): ?><img class="post-image" src="<?= POST_IMG_URL . '/' . h($p['image']) ?>"><?php endif; ?>
  </div>
<?php endforeach; endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
