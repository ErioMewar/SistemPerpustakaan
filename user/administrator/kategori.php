<?php
require '../../database/db.php';
session_start();

if (!isset($_SESSION['Username'])) {
    // Jika tidak ada sesi pengguna, redirect ke halaman login
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];
$error_message = "";

if (isset($_POST['add-button'])) {
    $new_kategori = trim($_POST['kategori']);

    // Validasi jika nama kategori tidak kosong
    if (empty($new_kategori)) {
        $error_message = "Nama kategori tidak boleh kosong.";
    } else {
        // Cek apakah nama kategori sudah ada sebelumnya
        $existing_kategori = "SELECT * FROM kategoribuku WHERE NamaKategori = '$new_kategori'";
        $result_existing = $db->query($existing_kategori);

        if ($result_existing->num_rows > 0) {
            $error_message = "Nama kategori sudah ada. Silakan gunakan nama kategori lain.";
        } else {
            // Simpan kategori baru ke dalam tabel
            $insert_kategori = "INSERT INTO kategoribuku (NamaKategori) VALUES ('$new_kategori')";
            if ($db->query($insert_kategori) === TRUE) {
                $error_message = "Kategori berhasil ditambahkan.";
            } else {
                $error_message = "Error: " . $insert_kategori . "<br>" . $db->error;
            }
        }
    }
}

// Process edit action
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    // Fetch the category details for editing
    $edit_query = "SELECT * FROM kategoribuku WHERE NamaKategori = $edit_id";
    $edit_result = $db->query($edit_query);

    if (!$edit_result) {
        $error_message = "Error in edit query: " . $db->error;
    } else {
        $edit_data = $edit_result->fetch_assoc();

        if (!$edit_data) {
            $error_message = "ID kategori tidak valid.";
        }
    }
}

// Process delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = $_GET['id'];
    // Delete the category from the database
    $delete_query = "DELETE FROM kategoribuku WHERE KategoriID = $delete_id";
    if ($db->query($delete_query) === TRUE) {
        $error_message = "Kategori berhasil dihapus.";
    } else {
        $error_message = "Error: " . $delete_query . "<br>" . $db->error;
    }
}

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
            <table class="table"  style="color: #ffffff;">
                <thead>
                    <tr>
                    <th scope="col">Nama Kategori</th>
                        <th scope="col" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_kategori = "SELECT * FROM kategoribuku ORDER BY NamaKategori ASC";
                    $result_kategori = $db->query($query_kategori);

                    while ($row = $result_kategori->fetch_assoc()) {
                        echo "<tr>";
                    echo "<td>{$row['NamaKategori']}</td>";
                    echo "<td>
                    <a href='../../function/editKategori.php?id={$row['KategoriID']}' class='btn btn-primary btn-sm'><i class='bi bi-pencil' title='Edit'></i></a>
                            <a href='?action=delete&id={$row['KategoriID']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus kategori ini?');\"'><i class='bi bi-trash' title='Hapus'></i></a>
                        </td>";
                    echo "</tr>";
                }
                    ?>
                </tbody>
            </table>
        </div>
    <div class="main-content">
        <div class="card">
            <div class="card-title">Tambah Kategori</div>
            <form action="" method="post">
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" placeholder="Masukkan kategori" required>
                    <button type="submit" name="add-button" class="btn btn-primary" onclick="return confirm('Apakah kamu ingin menambahkan kategori ini?')"><i class="bi bi-plus-circle" title="Tambah"></i> Tambah</button>
                </div>
            </form>
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
