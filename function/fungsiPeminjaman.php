<?php
session_start();
require '../database/db.php';
date_default_timezone_set('Asia/Jayapura');

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

// Use the $username and $userID variables directly
$username = $_SESSION['Username'];
$userID = $_SESSION['UserID'];

if (isset($_POST['pinjam-button'])) {
    $bukuID = $_GET['buku_id'];
    $judul_buku = $_GET['judul'];
    $tanggal_kembali_input = $_POST['tanggal_kembali'];
    // Perform the borrowing process
    $tanggal_pinjam = date("Y-m-d");
    $tanggal_kembali =  date('Y-m-d', strtotime($tanggal_kembali_input));

    $insert_peminjaman = "INSERT INTO peminjaman (UserID, BukuID, TanggalPeminjaman, TanggalPengembalian, StatusPeminjaman) 
                          VALUES ('$userID', '$bukuID', '$tanggal_pinjam', '$tanggal_kembali', 'Dipinjam')";

    if ($db->query($insert_peminjaman) === TRUE) {
        echo "<script>alert('Peminjaman berhasil!')</script>";
        header('Refresh: 0; url=../user/peminjam/buku.php');
        exit;
    } else {
        echo "Error: " . $insert_peminjaman . "<br>" . $db->error;
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beranda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/feather-icons">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/card.css">
    <link rel="shortcut icon" href="../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <?php include_once('../dashboard/navbarFunction.php'); ?>
    <?php include_once('../dashboard/sidebarFunction.php'); ?>

    <div class="content">
        <div class="card">
            <div class="card-title">Pinjam Buku</div>
            <form method="POST" action="">
            <label type="text" name="judul" value=""><?php echo ($_GET['judul']);  ?></label>
    <label for="tanggal_pinjam">Tanggal Pinjam:</label>
    <input type="text" id="tanggal_pinjam" name="tanggal_pinjam" value="<?php echo date('d-M-Y'); ?>" readonly>

    <label for="tanggal_kembali">Tanggal Pengembalian:</label>
    <input type="date" id="tanggal_kembali" name="tanggal_kembali" required>

    <button type="submit" name="pinjam-button" class="btn btn-primary" onclick="return confirm('Apakah kamu ingin meminjam buku ini?')"><i class="fa fa-sign-out"></i> Pinjam</button>
    <button type="button" class="btn btn-danger" onclick="window.location.href='../user/peminjam/buku.php'"><i class="bi bi-x-circle" title="Batal"></i> Batal</button>
</form>
        </div>
    </div>
    <?php include_once('../dashboard/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
    <script src="../js/script.js"></script>
    <script src="../js/logout.js"></script>
</body>
</html>
