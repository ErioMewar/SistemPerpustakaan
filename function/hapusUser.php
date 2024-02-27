<?php
session_start();
require '../database/db.php';

if (isset($_GET['UserID'], $_GET['Level'])) {
    $userID = $_GET['UserID'];
    $level = strtolower($_GET['Level']);
    
    // Query untuk menghapus pengguna
    $query = "DELETE FROM user WHERE UserID = ? AND Level = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $userID, $level);
    $stmt->execute();

    // Redirect ke halaman daftar pengguna setelah menghapus
    switch ($_SESSION["Level"]) {
        case "administrator":
            header("Location: ../user/administrator/dataUser.php#$level");
            exit;
            break;
        case "petugas":
            header("Location: ../user/petugas/dataUser.php#$level");
            exit;
            break;
        default:
            header("Location: login.php");
            exit;
            break;
    }
} else {
    echo "Error: " . $delete_buku . "<br>" . $db->error;
}
exit();
?>