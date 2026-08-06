<?php
$active = 'chats';
require_once __DIR__ . '/config.php';
require_login();
$me = current_user();

$friendId = (int)($_GET['id'] ?? 0);
$friend = find_user($friendId);
if (!$friend || !are_connected($me['id'], $friendId)) {
    header('Location: chats.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['text'] ?? '');
    if ($text !== '') {
        $messages = read_data('messages');
        $messages[] = [
            'id' => next_id($messages),
            'from_id' => $me['id'],
            'to_id' => $friendId,
            'text' => $text,
            'created_at' => now_ts(),
        ];
        write_data('messages', $messages);
    }
    // AJAX ile gonderiliyorsa sayfa yenilenmesin
    if (!empty($_POST['ajax'])) { echo 'ok'; exit; }
    header('Location: chat.php?id=' . $friendId);
    exit;
}

$pageTitle = 'Sohbet - ' . $friend['name'];
require_once __DIR__ . '/header.php';
?>
<h1 class="page-title">Sohbetler</h1>
<div class="chat-window">
  <div class="chat-header">
    <img src="<?= h(avatar_of($friend)) ?>">
    <div><strong><?= h($friend['name']) ?></strong></div>
  </div>
  <div class="chat-messages" id="chat-messages">
    <?php include __DIR__ . '/messages_partial.php'; ?>
  </div>
  <form class="chat-input-bar" id="chat-form">
    <input type="text" name="text" id="chat-text" placeholder="Bir mesaj yaz..." autocomplete="off" required>
    <button class="btn btn-primary btn-sm" type="submit">Gonder</button>
  </form>
</div>

<script>
const friendId = <?= (int)$friendId ?>;
</script>
<script src="js/script.js"></script>
<?php require_once __DIR__ . '/footer.php'; ?>
