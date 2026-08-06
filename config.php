<?php
/**
 * TeamUpp - Core config & helper functions
 * Veritabani olarak JSON dosyalari kullanilir (data/ klasoru).
 * MySQL gerekmez, sadece PHP calisir bir sunucu (ornek: `php -S localhost:8000`) yeterlidir.
 */
session_start();
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

define('ROOT_PATH', __DIR__);
define('DATA_PATH', ROOT_PATH . '/data');
define('AVATAR_DIR', ROOT_PATH . '/images/avatars');
define('POST_IMG_DIR', ROOT_PATH . '/images/posts');
define('AVATAR_URL', 'images/avatars');
define('POST_IMG_URL', 'images/posts');

if (!is_dir(DATA_PATH)) mkdir(DATA_PATH, 0777, true);
if (!is_dir(AVATAR_DIR)) mkdir(AVATAR_DIR, 0777, true);
if (!is_dir(POST_IMG_DIR)) mkdir(POST_IMG_DIR, 0777, true);

/* ---------- Basit JSON "veritabani" okuma/yazma (dosya kilitli) ---------- */
function data_file($name) {
    return DATA_PATH . '/' . $name . '.json';
}

function read_data($name) {
    $file = data_file($name);
    if (!file_exists($file)) return [];
    $fp = fopen($file, 'r');
    if (!$fp) return [];
    flock($fp, LOCK_SH);
    $content = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function write_data($name, $data) {
    $file = data_file($name);
    $fp = fopen($file, 'c');
    if (!$fp) return false;
    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

function next_id($rows) {
    $max = 0;
    foreach ($rows as $r) if (isset($r['id']) && $r['id'] > $max) $max = $r['id'];
    return $max + 1;
}

/* ---------- Kullanicilar ---------- */
function find_user($id) {
    foreach (read_data('users') as $u) if ($u['id'] == $id) return $u;
    return null;
}
function find_user_by_email($email) {
    foreach (read_data('users') as $u) if (strtolower($u['email']) === strtolower($email)) return $u;
    return null;
}
function update_user($id, $fields) {
    $users = read_data('users');
    foreach ($users as &$u) {
        if ($u['id'] == $id) {
            foreach ($fields as $k => $v) $u[$k] = $v;
        }
    }
    unset($u);
    write_data('users', $users);
}
function current_user() {
    if (!isset($_SESSION['user_id'])) return null;
    return find_user($_SESSION['user_id']);
}
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}

/* ---------- Baglantilar (connections) ---------- */
function connection_between($a, $b) {
    foreach (read_data('connections') as $c) {
        if (($c['from_id'] == $a && $c['to_id'] == $b) || ($c['from_id'] == $b && $c['to_id'] == $a)) {
            return $c;
        }
    }
    return null;
}
function are_connected($a, $b) {
    $c = connection_between($a, $b);
    return $c && $c['status'] === 'accepted';
}
function accepted_connections_of($user_id) {
    $ids = [];
    foreach (read_data('connections') as $c) {
        if ($c['status'] !== 'accepted') continue;
        if ($c['from_id'] == $user_id) $ids[] = $c['to_id'];
        if ($c['to_id'] == $user_id) $ids[] = $c['from_id'];
    }
    return $ids;
}

/* ---------- Kucuk yardimcilar ---------- */
function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function now_ts() { return date('Y-m-d H:i:s'); }
function avatar_of($user) {
    if (!empty($user['avatar'])) return AVATAR_URL . '/' . $user['avatar'];
    return 'images/default-avatar.svg';
}
function time_ago($ts) {
    $diff = time() - strtotime($ts);
    if ($diff < 60) return 'az once';
    if ($diff < 3600) return floor($diff/60) . ' dk once';
    if ($diff < 86400) return floor($diff/3600) . ' saat once';
    return floor($diff/86400) . ' gun once';
}

/* Yukleme sirasinda benzersiz dosya adi uret */
function save_uploaded_image($field, $dir, $urlPrefix) {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    $newName = uniqid('img_', true) . '.' . $ext;
    $dest = $dir . '/' . $newName;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
        return $newName;
    }
    return null;
}
