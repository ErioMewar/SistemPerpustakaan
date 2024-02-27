<?php
session_start();
require '../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['buku_id']) && is_numeric($_GET['buku_id'])) {
    $buku_id = (int)$_GET['buku_id'];

    // Hapus buku dari tabel buku berdasarkan BukuID
    $delete_buku = "DELETE FROM buku WHERE BukuID = $buku_id";

    if ($db->query($delete_buku) === TRUE) {
        // Redirect ke halaman kategori setelah penghapusan berhasil
        switch ($_SESSION["Level"]) {
            case "administrator":
                header("Location: ../user/administrator/buku.php");
                exit;
                break;
            case "petugas":
                header("Location: ../user/petugas/buku.php");
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
} else {
    // Jika tidak ada parameter buku_id yang valid, kembali ke halaman kategori
    switch ($_SESSION["Level"]) {
        case "administrator":
            header("Location: ../user/administrator/buku.php");
            exit;
            break;
        case "petugas":
            header("Location: ../user/petugas/buku.php");
            exit;
            break;
        default:
            header("Location: login.php");
            exit;
            break;
    }
}

$db->close();
?>
