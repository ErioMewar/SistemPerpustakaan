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
            header("Location: ../user/administrator/kategori.php");
            exit;
            break;
        case "petugas":
            header("Location: ../user/petugas/kategori.php");
            exit;
            break;
        default:
            header("Location: ../login.php");
            exit;
            break;
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Error: Kategori ID tidak valid.";
    exit();
}

$kategori_id = (int)$_GET['id'];

// Ambil data kategori yang akan diedit
$retrieve_kategori = "SELECT * FROM kategoribuku WHERE KategoriID = ?";
$stmt = $db->prepare($retrieve_kategori);
$stmt->bind_param("i", $kategori_id);
$stmt->execute();
$result_kategori = $stmt->get_result();

if ($result_kategori->num_rows > 0) {
    // Jika kategori ditemukan, ambil data
    $kategori_data = $result_kategori->fetch_assoc();
} else {
    // Jika kategori tidak ditemukan, tampilkan pesan error dan keluar
    echo "Error: Kategori tidak ditemukan.";
    exit();
}

if (isset($_POST['submit'])) {
    // Handle aksi edit (update database, dll.)
    $edited_kategori_id = (int)$_POST['kategori_id'];
    $edited_nama_kategori = $_POST['edited_nama_kategori'];

    // Lakukan update di database menggunakan prepared statement
    $update_query = "UPDATE kategoribuku SET NamaKategori = ? WHERE KategoriID = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("si", $edited_nama_kategori, $edited_kategori_id);

    if ($stmt->execute()) {
        switch ($_SESSION["Level"]) {
            case "administrator":
                header("Location: ../user/administrator/kategori.php");
                exit;
                break;
            case "petugas":
                header("Location: ../user/petugas/kategori.php");
                exit;
                break;
            default:
                header("Location: ../login.php");
                exit;
                break;
        }
    } else {
        echo "Error updating record: " . $stmt->error;
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
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
            <div class="card-title">Edit Kategori</div>
            <form action="" method="post">
                <div class="form-group">
                    <input type="hidden" name="kategori_id" value="<?php echo $kategori_id; ?>">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="edited_nama_kategori" value="<?php echo $kategori_data['NamaKategori']; ?>" placeholder="Ubah nama kategori">
                </div>
                <button type="submit" class="btn btn-primary" name="submit" onclick="return confirm('Yakin ingin menyimpan perubahan ini?')"><i class="bi bi-floppy" title="Simpan"></i> Simpan</button>
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
