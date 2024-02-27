<?php 
session_start();
require '../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

    $bookID = $_POST['BukuID'];
    $userID = $_SESSION['UserID'];

    $insertQuery = "INSERT INTO koleksipribadi (UserID, BukuID) VALUES ('$userID', '$bookID')";

    if(mysqli_query($db, $insertQuery)) {
        header("Location: ../user/peminjam/buku.php");
                exit;
    } else {
        echo "Error: " . mysqli_error($db);
    }

?>
