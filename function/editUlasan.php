<?php 
session_start();
require '../database/db.php';
date_default_timezone_set('Asia/Jayapura');

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];
$userID = $_SESSION['UserID'];

if (isset($_POST['cancel'])) {
    switch ($_SESSION["Level"]) {
        case "administrator":
            header("Location: ../user/administrator/ulasan.php");
            exit;
            break;
        case "petugas":
            header("Location: ../user/petugas/ulasan.php");
            exit;
            break;
        case "peminjam":
            header("Location: ../user/peminjam/ulasan.php");
            exit;
            break;
        default:
            header("Location: ../login.php");
            exit;
            break;
    }
}

// Ensure the user owns the review
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ulasanID = $_GET['ulasanID'];
    $userID = $_SESSION['UserID'];

    $query_review = mysqli_query($db, "SELECT * FROM ulasanbuku WHERE UlasanID = '$ulasanID' AND UserID = '$userID'");
    $review = mysqli_fetch_assoc($query_review);
}

if (isset($_POST['save'])) {
    $ulasanID = $_GET['ulasanID'];
    $bukuID = $_POST['bukuID'];
    $tanggalUpload = $_POST['tanggal_upload'];
    $ulasan = $_POST['ulasan'];
    $rating = $_POST['rating'];

    $updateQuery = "UPDATE ulasanbuku SET BukuID='$bukuID', Ulasan='$ulasan', Rating='$rating', TanggalUpload='$tanggalUpload' WHERE UlasanID='$ulasanID' AND UserID='$userID'";
    mysqli_query($db, $updateQuery);

    switch ($_SESSION["Level"]) {
        case "administrator":
            header("Location: ../user/administrator/ulasan.php");
            exit;
            break;
        case "petugas":
            header("Location: ../user/petugas/ulasan.php");
            exit;
            break;
        case "peminjam":
            header("Location: ../user/peminjam/ulasan.php");
            exit;
            break;
        default:
            header("Location: ../login.php");
            exit;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Ulasan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/card.css">
    <link rel="stylesheet" href="../css/ulasan.css">
    <link rel="shortcut icon" href="../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <div class="main-content">
        <?php include_once('../dashboard/navbarFunction.php'); ?>
        <div class="main-content">
            <?php include_once('../dashboard/sidebarFunction.php'); ?>

            <div class="ulasan-form">
                <div class="card">
                <div class="card-title">Edit Ulasan</div>
                    <div class="card-body">
                    <form method="POST" action="">
                    <input type="hidden" name="ulasanID" value="<?= $ulasanID ?>">
                    <input type="hidden" name="tanggal_upload" value="<?php echo date('Y-m-d H:i:s'); ?>">
                <label for="bukuID">Buku:</label>
                <select name="bukuID" required>
                        <?php
                        $query_buku = mysqli_query($db, "SELECT BukuID, Judul FROM buku");

                        while ($buku = mysqli_fetch_assoc($query_buku)) {
                            $selected = ($buku['BukuID'] == $review['BukuID']) ? 'selected' : '';
                            echo "<option value='{$buku['BukuID']}' $selected>{$buku['Judul']}</option>";
                        }
                        ?>
                    </select>
                    
                    <label for="ulasan">Ulasan:</label>
                    <textarea name="ulasan" style="resize: none; height: 120px;" required><?= $review['Ulasan']; ?></textarea>
                    
                    <fieldset class="rating" required>
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        $checked = ($i == $review['Rating']) ? 'checked' : '';
                        echo "<input type='radio' name='rating' id='star{$i}' class='star' value='{$i}' $checked /><label for='star{$i}' title='{$i} stars'>&#9733;</label>";
                    }
                    ?>
                </fieldset><br>
                    <button type="submit" class="btn btn-primary" name="save"><i class="bi bi-floppy" title="Simpan"></i> Simpan</button>
                    <button type="submit" class="btn btn-danger" name="cancel"><i class="bi bi-x-circle" title="Batal"></i> Batal</button>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('../dashboard/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
    <script src="../js/script.js"></script>
    <script src="../js/rating.js"></script>
    <script src="../js/logout.js"></script>
</body>
</html>
