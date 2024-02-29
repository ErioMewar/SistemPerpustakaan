<?php
require '../../database/db.php';
session_start();

if (!isset($_SESSION['Username'])) {
    // Jika tidak ada sesi pengguna, redirect ke halaman login
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kategori</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/feather-icons">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/card.css">
    <link rel="shortcut icon" href="../../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <?php include_once('../../dashboard/navbar.php'); ?>
    <?php include_once('../../dashboard/sidebar.php'); ?>
    <!-- Tabel untuk menampilkan kategori -->
    <div class="card">
            <div class="card-title">Daftar Kategori</div>
            <table class="table"  style="color: #ffffff; text-align: center">
                <tbody>
                    <?php
                    $query_kategori = "SELECT * FROM kategoribuku ORDER BY NamaKategori ASC";
                    $result_kategori = $db->query($query_kategori);
                    while ($row = $result_kategori->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='buku.php?kategori_id={$row['KategoriID']}' class='btn btn-success'>{$row['NamaKategori']}</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
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
