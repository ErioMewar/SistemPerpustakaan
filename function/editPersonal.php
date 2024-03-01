<?php
session_start();
require '../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];
$level = $_SESSION['Level'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $email = $_POST['email'];
    $namaLengkap = $_POST['full-name'];
    $alamat = $_POST['alamat'];
    $umur = $_POST['umur'];
    $jk = $_POST['jenis_kelamin'];

    // Validasi form
    if (empty($email) || empty($namaLengkap) || empty($alamat) || empty($umur) || empty($jk)) {
        echo "<script>alert('Semua kolom harus diisi. Silakan lengkapi formulir.')</script>";
        exit;
    }

    // Query untuk update data pengguna
    $query = "UPDATE user SET Email = ?, NamaLengkap = ?, Alamat = ?, Umur = ?, JenisKelamin = ? WHERE Username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssss", $email, $namaLengkap, $alamat, $umur, $jk, $username);
    $stmt->execute();

    // Redirect ke halaman profil setelah update
    switch ($_SESSION["Level"]) {
        case "administrator":
            header("Location: ../user/administrator/dataUser.php#$level");
            exit;
            break;
        case "petugas":
            header("Location: ../user/petugas/dataUser.php#$level");
            exit;
            break;
        default:
            header("Location: ../login.php");
            exit;
            break;
    }
}

// Ambil data pengguna yang sedang login
$query = "SELECT * FROM user WHERE Username = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/user.css">
    <link rel="shortcut icon" href="../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
<body>
<?php include_once('../dashboard/navbarFunction.php'); ?>
<?php include_once('../dashboard/sidebarFunction.php'); ?>
    <div class="container">
        <h2>Edit Profil</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['Email']; ?>">
            </div>
            <div class="form-group">
                <label for="full-name">Nama Lengkap:</label>
                <input type="text" class="form-control" id="full-name" name="full-name" value="<?php echo $row['NamaLengkap']; ?>">
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $row['Alamat']; ?>">
            </div>
            <div class="form-group">
                <label for="umur">Umur:</label>
                <input type="number" class="form-control" id="umur" name="umur" value="<?php echo $row['Umur']; ?>">
            </div>
            <div class="form-group">
                <label>Jenis Kelamin:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_laki" value="Laki-Laki" <?php if ($row['JenisKelamin'] === 'Laki-Laki') echo 'checked'; ?>>
                    <label class="form-check-label" for="jk_laki">Laki-Laki</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_perempuan" value="Perempuan" <?php if ($row['JenisKelamin'] === 'Perempuan') echo 'checked'; ?>>
                    <label class="form-check-label" for="jk_perempuan">Perempuan</label>
                </div>
            </div>
            <button type="submit" onclick="return confirm('Yakin ingin mengubah <?php echo ucfirst($level); ?> ini?');" class="btn btn-primary"><i class="bi bi-floppy" title="Simpan"></i> Simpan</button>
            <button type="submit" class="btn btn-danger" name="cancel"><i class="bi bi-x-circle" title="Batal"></i> Batal</button>
        </form>
    </div>
</body>
<?php include_once('../dashboard/footer.php'); ?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
<script src="../js/script.js"></script>
<script src="../js/logout.js"></script>
</html>
</html>

<?php
} else {
    echo "User not found.";
}
?>
