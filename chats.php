<?php
$active = 'chats';
$pageTitle = 'Chats';
require_once __DIR__ . '/header.php';

$friendIds = accepted_connections_of($me['id']);
$messages = read_data('messages');

$rows = [];
foreach ($friendIds as $fid) {
    $friend = find_user($fid);
    if (!$friend) continue;
    $last = null;
    foreach ($messages as $m) {
        if (($m['from_id'] == $me['id'] && $m['to_id'] == $fid) || ($m['from_id'] == $fid && $m['to_id'] == $me['id'])) {
            if (!$last || strtotime($m['created_at']) > strtotime($last['created_at'])) $last = $m;
        }
    }
    $rows[] = ['friend' => $friend, 'last' => $last];
}
usort($rows, function($a,$b) {
    $ta = $a['last'] ? strtotime($a['last']['created_at']) : 0;
    $tb = $b['last'] ? strtotime($b['last']['created_at']) : 0;
    return $tb <=> $ta;
});
?>
<h1 class="page-title">Sohbetler</h1>
<div class="card" style="padding:0;">
<?php if (empty($rows)): ?>
  <div class="empty-state">Henuz kimseyle baglantin yok. Explore'dan baglanti isteği gonder.</div>
<?php else: foreach ($rows as $r): $f = $r['friend']; $l = $r['last']; ?>
  <a class="chat-list-item" href="chat.php?id=<?= $f['id'] ?>">
    <img src="<?= h(avatar_of($f)) ?>">
    <div>
      <div class="cname"><?= h($f['name']) ?></div>
      <div class="clast"><?= $l ? h(strlen($l['text']) > 40 ? substr($l['text'], 0, 40) . '...' : $l['text']) : 'Sohbete basla' ?></div>
    </div>
    <div class="ctime"><?= $l ? time_ago($l['created_at']) : '' ?></div>
  </a>
<?php endforeach; endif; ?>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
