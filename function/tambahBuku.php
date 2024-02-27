<?php
session_start();
require '../database/db.php';

// Cek apakah pengguna telah login
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];

if (isset($_POST['cancel'])) {
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
            header("Location: ../login.php");
            exit;
            break;
    }
}

// Proses saat formulir disubmit
if (isset($_POST["submit"])) {
    $judul = $_POST["judul"];
    $penulis = $_POST["penulis"];
    $penerbit = $_POST["penerbit"];
    $tahun = $_POST["tahun"];
    $kategori = $_POST["kategori"];

    // Validasi formulir
    if (empty($judul) || empty($penulis) || empty($penerbit) || empty($tahun) || empty($kategori)) {
        echo "<script>alert('Semua kolom harus diisi. Silakan lengkapi formulir.')</script>";
        header('Refresh: 0; url=tambahBuku.php');
        return false;
    }

    // Cek apakah buku dengan judul yang sama sudah ada
    $checkQuery = "SELECT * FROM buku WHERE Judul = ?";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bind_param("s", $judul);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Buku dengan judul yang sama sudah ada.')</script>";
        $checkStmt->close();
        header('Refresh: 0; url=tambahBuku.php');
        return false;
    }

    // Insert data buku ke tabel 'buku'
    $insertQuery = "INSERT INTO buku (Judul, Penulis, Penerbit, TahunTerbit) VALUES (?, ?, ?, ?)";
    $insertStmt = $db->prepare($insertQuery);
    $insertStmt->bind_param("sssi", $judul, $penulis, $penerbit, $tahun);
    $insertStmt->execute();

    // Dapatkan ID buku yang baru saja dimasukkan
    $bookId = $insertStmt->insert_id;

    // Dapatkan ID kategori yang baru saja dimasukkan
    $kategoriQuery = "SELECT KategoriID FROM kategoribuku WHERE NamaKategori = ?";
    $kategoriStmt = $db->prepare($kategoriQuery);
    $kategoriStmt->bind_param("s", $kategori);
    $kategoriStmt->execute();
    $kategoriStmt->bind_result($kategoriId);
    $kategoriStmt->fetch();
    $kategoriStmt->close();

    // Insert ke dalam tabel 'kategoribuku_relasi'
    $relasiQuery = "INSERT INTO kategoribuku_relasi (BukuID, KategoriID) VALUES (?, ?)";
    $relasiStmt = $db->prepare($relasiQuery);
    $relasiStmt->bind_param("ii", $bookId, $kategoriId);
    $relasiStmt->execute();

    // Tutup statement
    $insertStmt->close();
    $relasiStmt->close();

    // Redirect ke halaman buku setelah berhasil
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
    <title>Tambah Buku</title>
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
    <div class="main-content">
        <div class="card">
            <div class="card-title">Tambah Buku</div>
            <form action="" method="post">
                <div class="form-group">
                    <label for="judul">Judul Buku</label>
                    <input type="text" id="judul" name="judul" placeholder="Masukkan judul buku">
                </div>
                <div class="form-group">
                    <label for="penulis">Nama Penulis</label>
                    <input type="text" id="penulis" name="penulis" placeholder="Masukkan nama penulis">
                </div>
                <div class="form-group">
                    <label for="penerbit">Nama Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" placeholder="Masukkan nama penerbit">
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun Terbit</label>
                    <input type="number" id="tahun" name="tahun" placeholder="Masukkan tahun terbit" min="1" max="9999">
                </div>
                <div class="form-group">
                <label for="kategori">Kategori Buku</label>
                <select id="kategori" name="kategori">
                    <?php
                    $kategoriQuery = "SELECT NamaKategori FROM kategoribuku";
                    $kategoriResult = $db->query($kategoriQuery);
                    while ($row = $kategoriResult->fetch_assoc()) {
                        echo "<option>" . $row['NamaKategori'] . "</option>";
                    }
                    $kategoriResult->close();
                    ?>
                </select>
                </div>
                <button type="submit" class="btn btn-primary" name="submit" onclick="return confirm('Apakah kamu ingin menambahkan buku ini?')"><i class="bi bi-plus-circle" title="Tambah"></i> Tambah</button>
                <button type="submit" class="btn btn-danger" name="cancel"><i class="bi bi-x-circle" title="Batal"></i> Batal</button>
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
