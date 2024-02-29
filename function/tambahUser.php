<?php 
require '../database/db.php';
session_start();

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];


if (isset($_POST['cancel'])) {
    switch ($_SESSION["Level"]) {
        case "administrator":
            header("Location: ../user/administrator/dataUser.php#{$level}");
            exit;
            break;
        case "petugas":
            header("Location: ../user/petugas/dataUser.php#{$level}");
            exit;
            break;
        default:
            header("Location: ../login.php");
            exit;
            break;
    }
}
$level = strtolower($_GET['Level']);

// Check if the form is submitted
if (isset($_POST['tambah'])) {
    $username = trim($_POST["username"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);
    $password2 = mysqli_real_escape_string($db, $_POST["confirm-password"]);
    $email = trim($_POST["email"]);
    $fullname = trim($_POST["fullname"]);
    $alamat = trim($_POST["alamat"]);
    $umur = trim($_POST["umur"]);
    $jk = trim($_POST["jenis_kelamin"]);

    // Validate empty fields
    if (empty($username) || empty($password) || empty($password2) || empty($email) || empty($fullname) || empty($alamat) || empty($umur) || empty($jk) || empty($level)) {
        echo "<script>alert('Semua kolom harus diisi. Silakan lengkapi formulir.')</script>";
        $level = strtolower($_GET['Level']);
        header("Refresh: 0; url=tambahUser.php?Level=$level");
        exit;
    }    

    // Validate username length
    if (strlen($username) > 12) {
        echo "<script>alert('Maaf, nama pengguna terlalu panjang. Silakan coba lagi.')</script>";
        $level = strtolower($_GET['Level']);
        header("Refresh: 0; url=tambahUser.php?Level=$level");
        exit;
    } elseif (strlen($password) < 8) {
        echo "<script>alert('Minimum karakter password adalah 8. Silakan coba lagi.')</script>";
        $level = strtolower($_GET['Level']);
        header("Refresh: 0; url=tambahUser.php?Level=$level");
        exit;
    }

    // Check if passwords match
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak sesuai. Silakan coba lagi.')</script>";
        $level = strtolower($_GET['Level']);
        header("Refresh: 0; url=tambahUser.php?Level=$level");
        exit;
    }

    $checkQuery = "SELECT * FROM user WHERE Username = '$username'";
    $result = mysqli_query($db, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Maaf, username sudah digunakan. Silakan pilih username lain.')</script>";
        header('Refresh: 0; url=tambahUser.php?Level=$level');
        exit;
    }

    // Encrypt the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Add the new user to the database
    $query = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Umur, JenisKelamin, Level) VALUES ('$username', '$password', '$email', '$fullname', '$alamat','$umur', '$jk','$level')";
    mysqli_query($db, $query);

    $userLevel = ucfirst($level);
    if (mysqli_affected_rows($db) > 0) {
        echo "<script>
        var userLevel = '" . $userLevel . "';
        alert(userLevel + ' berhasil ditambahkan!');</script>";
        switch ($_SESSION["Level"]) {
            case "administrator":
                header("Location: ../user/administrator/dataUser.php#$Level");
                exit;
                break;
            case "petugas":
                header("Location: ../user/petugas/dataUser.php#$Level");
                exit;
                break;
            default:
                header("Location: ../login.php");
                exit;
                break;
        }
    } else {
        echo "<script>
        var userLevel = '" . $userLevel . "';
        alert('Gagal menambahkan ' + userLevel + '! Silahkan coba lagi.');</script>";
        header('Refresh: 0; url=tambahUser.php?Level=$level');
        exit;
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah <?php echo $level = ucfirst($_GET['Level']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/user.css">
    <link rel="shortcut icon" href="../img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <?php include_once('../dashboard/navbarFunction.php'); ?>
    <?php include_once('../dashboard/sidebarFunction.php'); ?>
    <div class="main-content">
    <h2>Tambah <?php echo $level = ucfirst($_GET['Level']); ?></h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan Username (maks.12)">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan Email">
            </div>
            <div class="form-group">
                <label for="fullname">Nama Lengkap:</label>
                <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Masukkan Nama Lengkap">
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Masukkan Alamat">
            </div>
            <div class="form-group">
            <label for="umur">Umur:</label>
            <input type="number" class="form-control" id="umur" name="umur"  placeholder="Masukkan Umur">
            </div>
            <div class="form-group">
            <label>Jenis Kelamin:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_laki" value="Laki-Laki">
                <label class="form-check-label" for="jk_laki">Laki-Laki</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_perempuan" value="Perempuan">
                <label class="form-check-label" for="jk_perempuan">Perempuan</label>
            </div>
            </div>
            <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password (min.8)">
                        <span class="eye" id="toggle-password">
                            <i id="hide1" class="fa fa-eye eye"></i>
                            <i id="hide2" class="fa fa-eye-slash eye"></i>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Konfirmasi Password:</label>
                        <input type="password" class="form-control" name="confirm-password" id="confirm-password" placeholder="Konfirmasi password (min.8)">
                        <span class="eye" id="toggle-confirm-password">
                            <i id="hide3" class="fa fa-eye eye"></i>
                            <i id="hide4" class="fa fa-eye-slash eye"></i>
                        </span>
                    </div>
                    <input type="hidden" name="level" value="<?php echo $level = strtolower($_GET['Level']); ?> ?>">
            <button type="submit" class="btn btn-primary" name="tambah" onclick="return confirm('Yakin ingin menambah <?php echo $level = ucfirst($_GET['Level']); ?>?');"><i class="bi bi-plus-circle" title="Simpan"></i> Tambah</button>
            <button type="submit" class="btn btn-danger" name="cancel"><i class="bi bi-x-circle" title="Batal"></i> Batal</button>
        </form>
    </div>
    <?php include_once('../dashboard/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
    <script src="../js/script.js"></script>
    <script src="../js/logout.js"></script>
    <script src="..js/toggle.js"></script>
</body>
</html>
