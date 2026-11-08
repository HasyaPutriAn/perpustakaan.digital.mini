<?php
session_start();

// kosongkan dan hancurkan session
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(session_name(), "", time() - 3600, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
}
session_destroy();

// hapus cookie remember me
setcookie("remember_username", "", time() - 3600, "/");

header("Location: index.php?pesan=logout");
exit;
