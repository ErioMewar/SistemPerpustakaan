<?php
session_start();
require '../database/db.php';

// Memeriksa apakah pengguna telah login
    if (!isset($_SESSION['UserID'])) {
        header("Location: index.php");
        exit();
    }

// Memeriksa apakah formulir telah dikirim
if (isset($_POST['pinjam-button'])) {

    $user_id = $_SESSION['UserID'];
    $buku_id = $_POST['buku_id'];

    // Mengambil data pengguna dan buku terkait
    $get_user_book_query = "SELECT * FROM peminjaman WHERE BukuID = $buku_id AND UserID = $user_id";
    $result_user_book = $db->query($get_user_book_query);

    if ($result_user_book->num_rows > 0) {
        
        $row = $result_user_book->fetch_assoc();
        $tanggal_pinjam = $row['TanggalPeminjaman'];
        $tanggal_kembali = $row['TanggalPengembalian'];

        // Memasukkan data ke dalam tabel pengembalian
        $insert_laporan_query = "INSERT INTO pengembalian (UserID, BukuID, TanggalPeminjaman, TanggalPengembalian, StatusPeminjaman) 
                                VALUES ('$user_id', $buku_id, '$tanggal_pinjam', '$tanggal_kembali', 'Dikembalikan')";
        $db->query($insert_laporan_query);

        // Menghapus data dari tabel peminjaman
        $hapus_peminjaman_query = "DELETE FROM peminjaman WHERE BukuID = $buku_id AND UserID = $user_id";
        $result_hapus_peminjaman = $db->query($hapus_peminjaman_query);

        if ($result_hapus_peminjaman) {
            // Mengarahkan kembali ke halaman yang sama setelah pembaruan berhasil
            echo "<script>alert('Buku berhasil dikembalikan.')</script>";
            header('Refresh: 0; url=../user/peminjam/buku.php');
            exit();
        } 
    } 
}

$db->close();
?>
