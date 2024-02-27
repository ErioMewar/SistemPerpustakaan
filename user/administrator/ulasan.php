<?php 
session_start();
require '../../database/db.php';
date_default_timezone_set('Asia/Jayapura');

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];

// Delete Functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_review') {
    $ulasanID = $_POST['ulasanID'];

    $deleteQuery = "DELETE FROM ulasanbuku WHERE UlasanID='$ulasanID'";
    mysqli_query($db, $deleteQuery);

    header("Location: ulasan.php");
    exit();
}

// Add new review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['UserID'];
    $tanggalUpload = $_POST['tanggal_upload'];
    $bukuID = $_POST['bukuID'];
    $ulasan = mysqli_real_escape_string($db, $_POST['ulasan']);
    $rating = $_POST['rating'];

    $insertQuery = "INSERT INTO ulasanbuku (UserID, BukuID, Ulasan, Rating, TanggalUpload) VALUES ('$userID', '$bukuID', '$ulasan', '$rating', '$tanggalUpload')";
    mysqli_query($db, $insertQuery);

    header("Location: ulasan.php");
    exit();
}

$query_reviews = mysqli_query($db, "SELECT * FROM ulasanbuku");
$reviews = mysqli_fetch_all($query_reviews, MYSQLI_ASSOC);

function waktu_berlalu($timestamp) {
    $waktu_sekarang = time();
    $selisih_waktu = $waktu_sekarang - strtotime($timestamp);
    $detik = $selisih_waktu;
    $menit      = round($detik / 60);
    $jam           = round($detik / 3600);
    $hari          = round($detik / 86400);
    $pekan        = round($detik / 604800);
    $bulan     = round($detik / 2629440);
    $tahun         = round($detik / 31553280);

    if ($detik <= 60) {
        return "Baru saja diposting";
    } else if ($menit <= 60) {
        return ($menit == 1) ? "Satu menit yang lalu" : "$menit menit yang lalu";
    } else if ($jam <= 24) {
        return ($jam == 1) ? "Sejam yang lalu" : "$jam jam yang lalu";
    } else if ($hari <= 7) {
        return ($hari == 1) ? "Kemarin" : "$hari hari yang lalu";
    } else if ($pekan <= 4.3) {
        return ($pekan == 1) ? "Sepekan yang lalu" : "$pekan Pekan yang lalu";
    } else if ($bulan <= 12) {
        return ($bulan == 1) ? "Sebulan yang lalu" : "$bulan bulan yang lalu";
    } else {
        return ($tahun == 1) ? "Setahun yang lalu" : "$tahun tahun yang lalu";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ulasan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/card.css">
    <link rel="stylesheet" href="../../css/ulasan.css">
    <link rel="shortcut icon" href="../../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <div class="main-content">
        <?php include_once('../../dashboard/navbar.php'); ?>
        <div class="main-content">
            <?php include_once('../../dashboard/sidebar.php'); ?>

            <div class="ulasan-form">
                <div class="card">
                <div class="card-title">Tambah Ulasan</div>
                    <div class="card-body">
                        <form method="POST" action="">
                        <input type="hidden" name="tanggal_upload" value="<?php echo date('Y-m-d H:i:s'); ?>">
                            <label for="bukuID">Buku:</label>
                            <select name="bukuID" required>
                                <?php
                                $query_buku = mysqli_query($db, "SELECT BukuID, Judul FROM buku");
                                $buku_details = mysqli_fetch_all($query_buku, MYSQLI_ASSOC);

                                foreach ($buku_details as $buku) {
                                    echo "<option value='{$buku['BukuID']}'>{$buku['Judul']}</option>";
                                }
                                ?>
                            </select>

                            <label for="ulasan">Ulasan:</label>
                            <textarea name="ulasan" style="resize: none; height: 120px;" required></textarea>

                            <fieldset class="rating" required>
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo "<input type='radio' id='star{$i}' class='star' name='rating' value='{$i}' /><label for='star{$i}' title='{$i} stars'>&#9733;</label>";
                                }
                                ?>
                            </fieldset><br>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle" title="Tambah"></i> Tambah</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="ulasan-buku">
                <h3>Ulasan Buku</h3>
                <?php foreach ($reviews as $review): ?>
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $userID = $review['UserID'];
                            $query_user = mysqli_query($db, "SELECT Username, Level FROM user WHERE UserID = '$userID'");
                            $user_details = mysqli_fetch_assoc($query_user);

                            $bukuID = $review['BukuID'];
                            $query_book = mysqli_query($db, "SELECT Judul FROM buku WHERE BukuID = '$bukuID'");
                            $book_details = mysqli_fetch_assoc($query_book);

                            echo "<p><i class='bi bi-person-circle'></i> " . ($user_details['Level'] == 'administrator' || $user_details['Level'] == 'petugas' ? ($user_details['Username'] ?? 'Unknown User') . ' (' . ucfirst($user_details['Level']) . ')' : ($user_details['Username'] ?? 'Unknown User')) . "</p>";

                            echo "<span class='card-text' style='font-weight: bold;'>" . ($book_details['Judul'] ?? 'Unknown Book') . "</p>";
                            ?>
                            <p class="card-text" style="font-weight: bold;"><?= waktu_berlalu($review['TanggalUpload']); ?></p>
                            <p class="card-text" style="font-weight: bold;">Ulasan:</p>
                            <p style="font-weight: normal;"><?= $review['Ulasan']; ?></p>

                            <?php 
                            $rating = intval($review['Rating']);

                            // Tampilkan rating dalam bentuk bintang
                            for ($i = 1; $i <= $rating; $i++) {
                                echo "<span style='color: #FFDF00; font-size: 25px; font-weight: bold;'>&#9733;</span>";
                            }
                            if ($_SESSION['UserID'] == $review['UserID']) {
                                echo "<br><a href='../../function/editUlasan.php?ulasanID={$review['UlasanID']}' name='ulasanID' class='btn btn-primary'><i class='bi bi-pencil' title='Edit'></i> Edit</a>
                                <form method='POST' action='' class='d-inline-block'>
                                    <input type='hidden' name='action' value='delete_review'>
                                    <input type='hidden' name='ulasanID' value='{$review['UlasanID']}'>
                                    <button type='submit' class='btn btn-danger' onclick='return confirm(\"Apakah kamu yakin ingin menghapus ulasan ini?\")'><i class='bi bi-trash' title='Hapus'></i> Hapus</button>
                                </form>";
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php include_once('../../dashboard/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
    <script src="../../js/script.js"></script>
    <script src="../../js/rating.js"></script>
    <script src="../../js/logout.js"></script>
</body>
</html>