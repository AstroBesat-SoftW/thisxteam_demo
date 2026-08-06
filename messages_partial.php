<?php
// Bu dosya hem chat.php icinden include edilir hem de AJAX ile dogrudan cagrilir.
if (!isset($me)) {
    require_once __DIR__ . '/config.php';
    require_login();
    $me = current_user();
    $friendId = (int)($_GET['id'] ?? 0);
    $friend = find_user($friendId);
    if (!$friend || !are_connected($me['id'], $friendId)) { exit; }
}

$messages = array_filter(read_data('messages'), function($m) use ($me, $friendId) {
    return ($m['from_id'] == $me['id'] && $m['to_id'] == $friendId) || ($m['from_id'] == $friendId && $m['to_id'] == $me['id']);
});
usort($messages, function($a,$b){ return strtotime($a['created_at']) <=> strtotime($b['created_at']); });

foreach ($messages as $m):
    $mine = $m['from_id'] == $me['id'];
?>
  <div class="msg-bubble <?= $mine ? 'msg-mine' : 'msg-theirs' ?>">
    <?= nl2br(h($m['text'])) ?>
    <span class="msg-time"><?= date('H:i', strtotime($m['created_at'])) ?></span>
  </div>
<?php endforeach; ?>
