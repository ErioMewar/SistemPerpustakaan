<?php
session_start();
require '../database/db.php';

if (!isset($_SESSION['Username'])) {
    // Jika tidak ada sesi pengguna, redirect ke halaman login
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

$buku_id = (int)$_GET['buku_id'];
$kategori = $_GET['kategori'];

$retrieve_categories = "SELECT * FROM kategoribuku";
$result_categories = $db->query($retrieve_categories);
$categories = $result_categories->fetch_all(MYSQLI_ASSOC);

// Ambil data buku yang akan diedit
$retrieve_buku = "SELECT * FROM buku WHERE BukuID = ?";
$stmt = $db->prepare($retrieve_buku);
$stmt->bind_param("i", $buku_id);
$stmt->execute();
$result_buku = $stmt->get_result();

if ($result_buku->num_rows > 0) {
    // Jika buku ditemukan, ambil data
    $buku_data = $result_buku->fetch_assoc();
} else {
    // Jika buku tidak ditemukan, tampilkan pesan error dan keluar
    echo "Error: Buku tidak ditemukan.";
    exit();
}

if (isset($_POST['submit'])) {
    $edited_buku_id = (int)$_POST['buku_id'];
    $edited_judul = $_POST['edited_judul'];
    $edited_penulis = $_POST['edited_penulis'];
    $edited_penerbit = $_POST['edited_penerbit'];
    $edited_tahun = $_POST['edited_tahun'];
    $edited_kategori = $_POST['edited_kategori'];

    $update_relasi_query = "UPDATE kategoribuku_relasi SET KategoriID = ? WHERE BukuID = ?";
    $stmt_relasi = $db->prepare($update_relasi_query);
    $stmt_relasi->bind_param("ii", $edited_kategori, $edited_buku_id);

    if ($stmt_relasi->execute()) {
        $update_query = "UPDATE buku SET Judul = ?, Penulis = ?, Penerbit = ?, TahunTerbit = ? WHERE BukuID = ?";
        $stmt = $db->prepare($update_query);
        $stmt->bind_param("ssssi", $edited_judul, $edited_penulis, $edited_penerbit, $edited_tahun, $edited_buku_id);

        if ($stmt->execute()) {
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
        } else {
            echo "Error updating record: " . $stmt->error;
            exit();
        }
    } else {
        echo "Error updating category relationship: " . $stmt_relasi->error;
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
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
<?php include_once('../dashboard/navbarFunction.php');?>
<?php include_once('../dashboard/sidebarFunction.php');?>
<div class="main-content">
    <div class="card">
        <div class="card-title">Edit Buku</div>
            <form action="" method="post">
                <div class="form-group">
                    <input type="hidden" name="buku_id" value="<?php echo $buku_id; ?>">
                    <label for="judul">Judul Buku</label>
                    <input type="text" id="judul" name="edited_judul" value="<?php echo $buku_data['Judul']; ?>" placeholder="Ubah judul buku">
                </div>
                <div class="form-group">
                    <label for="penulis">Nama Penulis</label>
                    <input type="text" id="penulis" name="edited_penulis" value="<?php echo $buku_data['Penulis']; ?>" placeholder="Ubah nama penulis">
                </div>
                <div class="form-group">
                    <label for="penerbit">Nama Penerbit</label>
                    <input type="text" id="penerbit" name="edited_penerbit" value="<?php echo $buku_data['Penerbit']; ?>" placeholder="Ubah nama penerbit">
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun Terbit</label>
                    <input type="number" id="tahun" name="edited_tahun" value="<?php echo $buku_data['TahunTerbit']; ?>" placeholder="Ubah tahun terbit">
                </div>
                <div class="form-group">
                        <label for="kategori">Kategori Buku</label>
                        <select id="kategori" name="edited_kategori" class="form-control">
                            <?php
                            foreach ($categories as $category) {
                                $selected = ($category['NamaKategori'] == $kategori) ? 'selected' : '';
                                echo "<option value='{$category['KategoriID']}' {$selected}>{$category['NamaKategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                <button type="submit" class="btn btn-primary" name="submit" onclick="return confirm('Yakin ingin menyimpan perubahan ini?')"><i class="bi bi-floppy" title="Simpan"></i> Simpan</button>
                <button type="submit" class="btn btn-danger" name="cancel"><i class="bi bi-x-circle" title="Batal"></i> Batal</button>
            </form>
        </div>
    </div>
    <?php include_once('../dashboard/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
    <script src="../js/script.js"></script>
    <script src="../js/logout.js"></script>
</body>
</html>
