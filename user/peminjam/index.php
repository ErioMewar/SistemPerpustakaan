<?php
session_start();
require '../../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: ../../index.php");
    exit();
}
$username = $_SESSION['Username'];

$query_buku = mysqli_query($db, "SELECT * FROM buku");
$row_buku = mysqli_num_rows($query_buku);
$query_last_buku = mysqli_query($db, "SELECT * FROM peminjaman");
$row_last_buku = mysqli_num_rows($query_last_buku);
$query_kategori = mysqli_query($db, "SELECT * FROM kategoribuku");
$row_kategori = mysqli_num_rows($query_kategori);
$query_pinjam = mysqli_query($db, "SELECT * FROM peminjaman WHERE UserID = (SELECT UserID FROM user WHERE Username = '$username')");
$row_pinjam = mysqli_num_rows($query_pinjam);
$query_jumlah_user = mysqli_query($db, "SELECT Level, COUNT(*) as Jumlah FROM user GROUP BY Level");


$row_peminjam = 0;
while ($row = mysqli_fetch_assoc($query_jumlah_user)) {
    $level = $row['Level'];
    $jumlah = $row['Jumlah'];

    // Gunakan nilai $level dan $jumlah sesuai kebutuhan Anda
    if ($level == 'peminjam') {
        $row_peminjam = $jumlah;
    }
}

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
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="shortcut icon" href="../../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <?php include_once('../../dashboard/navbar.php'); ?>
    <?php include_once('../../dashboard/sidebar.php'); ?>
    <div class="main-content">
        
            <h3>Selamat datang <?php echo $username; ?> (＾▽＾)</h3>
            <div class="card1">
                        <h3 class="card-text"><i class="bi bi-person-circle"></i> Peminjam</h3>
                        <p class="card-title"><?= $row_peminjam; ?></p>
                        <a href="dataUser.php" class="button">Lihat</a>
            </div>
            <div class="card2">
                        <h3 class="card-text"><i class="fa fa-book"></i> Buku Tersedia</h3>
                        <p class="card-title"><?= $row_buku - $row_last_buku; ?></p>
                        <a href="buku.php" class="button">Lihat</a>
            </div>
            <div class="card3">
                        <h3 class="card-text"><i class="fa fa-book"></i> Kategori</h3>
                        <p class="card-title"><?= $row_kategori; ?></p>
                        <a href="kategori.php" class="button">Lihat</a>
            </div>
            <div class="card5">
                        <h3 class="card-text"><i class="fa fa-sign-out"></i> Sedang Dipinjam</h3>
                        <p class="card-title"><?= $row_pinjam; ?></p>
                        <a href="bukuDipinjam.php" class="button">Lihat</a>
            </div>
    </div>
    <?php include_once('../../dashboard/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
    <script src="../../js/script.js"></script>
    <script src="../../js/logout.js"></script>
</body>
</html>
