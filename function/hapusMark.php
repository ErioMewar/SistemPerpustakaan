<?php
session_start();
require '../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['BukuID'])) {
    $bookID = $_POST['BukuID'];
    $userID = $_SESSION['UserID'];

    $insertQuery = "DELETE FROM koleksipribadi WHERE UserID = '$userID' AND BukuID = '$bookID'";

    if(mysqli_query($db, $insertQuery)) {
        // Check if "from" parameter is set
        if (isset($_GET['from']) && $_GET['from'] === 'koleksi') {
            // If mark is deleted from koleksi.php, redirect back to koleksi.php
            header("Location: ../user/peminjam/koleksi.php");
            exit;
        } else {
            // If mark is deleted from buku.php, redirect back to buku.php
            header("Location: ../user/peminjam/buku.php");
            exit;
        }
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    // If BukuID is not in POST data, redirect back to the appropriate page
    if (isset($_GET['from']) && $_GET['from'] === 'koleksi') {
        header("Location: ../user/peminjam/koleksi.php");
        exit;
    } else {
        header("Location: ../user/peminjam/buku.php");
        exit;
    }
}
?>
