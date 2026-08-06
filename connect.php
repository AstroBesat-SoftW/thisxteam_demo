<?php
require_once __DIR__ . '/config.php';
require_login();
$me = current_user();

$action = $_GET['action'] ?? '';
$back = $_GET['back'] ?? 'explore.php';

if ($action === 'send') {
    $to = (int)($_GET['to'] ?? 0);
    if ($to && $to !== $me['id'] && !connection_between($me['id'], $to)) {
        $conns = read_data('connections');
        $conns[] = [
            'id' => next_id($conns),
            'from_id' => $me['id'],
            'to_id' => $to,
            'status' => 'pending',
            'created_at' => now_ts(),
        ];
        write_data('connections', $conns);
    }
} elseif ($action === 'accept' || $action === 'reject') {
    $cid = (int)($_GET['id'] ?? 0);
    $conns = read_data('connections');
    foreach ($conns as &$c) {
        if ($c['id'] === $cid && $c['to_id'] == $me['id']) {
            $c['status'] = $action === 'accept' ? 'accepted' : 'rejected';
        }
    }
    unset($c);
    write_data('connections', $conns);
} elseif ($action === 'cancel') {
    $to = (int)($_GET['to'] ?? 0);
    $conns = read_data('connections');
    $conns = array_values(array_filter($conns, function($c) use ($me, $to) {
        return !(($c['from_id'] == $me['id'] && $c['to_id'] == $to) || ($c['from_id'] == $to && $c['to_id'] == $me['id']));
    }));
    write_data('connections', $conns);
}

header('Location: ' . $back);
exit;
