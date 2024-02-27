<?php
session_start();

// Hapus cookie 'remember_user'
if (isset($_COOKIE['user_level'])) {
    setcookie("user_level", "", time() - 3600);
}

if (isset($_COOKIE['user_id'])) {
    setcookie("user_id", "", time() - 3600);
}

// Hapus semua data sesi
$_SESSION = array();
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: index.php");
exit;
?>
