<?php 
require 'database/db.php';

// Check if the form is submitted
if (isset($_POST['register'])) {
    $username = trim($_POST["username"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);
    $password2 = mysqli_real_escape_string($db, $_POST["confirm-password"]);
    $email = trim($_POST["email"]);
    $fullname = trim($_POST["fullname"]);
    $alamat = trim($_POST["alamat"]);
    $umur = trim($_POST["umur"]);
    $jk = trim($_POST["jenis_kelamin"]);
    $level = strtolower(trim($_POST["level"]));

    // Validate empty fields
    if (empty($username) || empty($password) || empty($password2) || empty($email) || empty($fullname) || empty($alamat) || empty($umur) || empty($jk) || empty($level)) {
        echo "<script>alert('Semua kolom harus diisi. Silakan lengkapi formulir.')</script>";
        header('Refresh: 0; url=register.php');
        exit;
    }

    // Validate username length
    if (strlen($username) > 12) {
        echo "<script>alert('Maaf, nama pengguna terlalu panjang. Silakan coba lagi.')</script>";
        header('Refresh: 0; url=register.php');
        exit;
    } elseif (strlen($password) < 8) {
        echo "<script>alert('Minimum karakter password adalah 8. Silakan coba lagi.')</script>";
        header('Refresh: 0; url=register.php');
        exit;
    }

    // Check if passwords match
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak sesuai. Silakan coba lagi.')</script>";
        header('Refresh: 0; url=register.php');
        exit;
    }

    $checkQuery = "SELECT * FROM user WHERE Username = '$username'";
    $result = mysqli_query($db, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Maaf, username sudah digunakan. Silakan pilih username lain.')</script>";
        header('Refresh: 0; url=register.php');
        exit;
    }

    // Encrypt the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Add the new user to the database
    $query = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Umur, JenisKelamin, Level) VALUES ('$username', '$password', '$email', '$fullname', '$alamat', '$umur', '$jk', '$level')";
    mysqli_query($db, $query);

    if (mysqli_affected_rows($db) > 0) {
        echo "<script>
        alert('Registrasi berhasil!');
        window.location.href='index.php';
        </script>";
    } else {
        echo "<script>
        alert('Registrasi gagal! Silahkan coba lagi.');
        window.location.href='register.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/feather-icons">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/logoweb.jpeg" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
            <h3>Daftar Akun Perpustakaan Sekolah</h3><br>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan Username (maks.12)">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan Email">
                    </div>
                    <div class="form-group">
                        <label for="fullname">Nama Lengkap</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Masukkan Nama Lengkap">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Masukkan Alamat">
                    </div>
                    <div class="form-group">
                        <label for="umur">Umur</label>
                        <input type="number" class="form-control" name="umur" id="number" placeholder="Masukkan Umur" min="1" max="999">
                    </div>
                    <div class="form-group">
                    <label>Jenis Kelamin</label><br>
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
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password (min.8)">
                        <span class="eye" id="toggle-password">
                            <i id="hide1" class="fa fa-eye eye"></i>
                            <i id="hide2" class="fa fa-eye-slash eye"></i>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="confirm-password" id="confirm-password" placeholder="Konfirmasi password (min.8)">
                        <span class="eye" id="toggle-confirm-password">
                            <i id="hide3" class="fa fa-eye eye"></i>
                            <i id="hide4" class="fa fa-eye-slash eye"></i>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select name="level" class="form-control">
                            <option value="administrator">Administrator</option>
                            <option value="peminjam">Peminjam</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-register" name="register">Daftar</button>
                    <p class="mt-3">Sudah punya akun? <a href="index.php">klik Masuk</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"></script>
        <script src="js/toggle.js"></script>
</body>
</html>
