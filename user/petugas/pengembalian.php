<?php
session_start();
require '../../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];

// Pagination configuration
$results_per_page = 10; // Number of results per page
$query = "SELECT u.Username, b.Judul, p.TanggalPeminjaman, p.TanggalPengembalian, p.StatusPeminjaman 
            FROM pengembalian p 
            JOIN user u ON p.UserID = u.UserID 
            JOIN buku b ON p.BukuID = b.BukuID
            ORDER BY p.UserID, p.TanggalPeminjaman, p.TanggalPengembalian";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Error: " . mysqli_error($db));
}

$total_results = mysqli_num_rows($result);
$total_pages = ceil($total_results / $results_per_page);

// Get current page or set default page to 1
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting index for the results based on the current page
$starting_index = ($current_page - 1) * $results_per_page;

// Modify the query to include the LIMIT clause
$query .= " LIMIT $starting_index, $results_per_page";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengembalian</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/feather-icons">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/table.css">
    <link rel="shortcut icon" href="../../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <?php include_once('../../dashboard/navbar.php'); ?>
    <?php include_once('../../dashboard/sidebar.php'); ?>

    <div class="main-content">
        <h2>Laporan Pengembalian</h2>

        <table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tanggal Peminjaman</th>
            <th>Waktu Tenggat</th>
            <th>Status Pengembalian</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $counter = $starting_index + 1;
        while ($peminjaman = mysqli_fetch_assoc($result)) {
            $status = $peminjaman['StatusPeminjaman'];
            $deDate = strtotime($peminjaman['TanggalPeminjaman']);
            $dueDate = strtotime($peminjaman['TanggalPengembalian']);
            $currentDate = time();
            ?>
            <tr>
                <th scope="row"><?php echo $counter; ?></th>
                <td><?php echo $peminjaman['Username']; ?></td>
                <td><?php echo $peminjaman['Judul']; ?></td>
                <td><?php echo date('d-M-Y', strtotime($peminjaman['TanggalPeminjaman'])); ?></td>
                <td><?php echo date('d-M-Y', strtotime($peminjaman['TanggalPengembalian'])); ?></td>
                <td><?php echo $peminjaman['StatusPeminjaman']; ?></td>
            </tr>
            <?php
            $counter++;
        }
        ?>
    </tbody>
</table>
<ul class="pagination">
            <?php
            for ($page = 1; $page <= $total_pages; $page++) {
                echo '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
            }
            ?>
        </ul>
    </div>
    <?php include_once('../../dashboard/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
    <script src="../../js/script.js"></script>
    <script src="../../js/logout.js"></script>
    <?php
    mysqli_close($db);
    ?>
</body>
</html>
