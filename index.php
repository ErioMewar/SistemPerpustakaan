<?php
session_start();
require 'database/db.php';

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check username
    $stmt_username = $db->prepare("SELECT * FROM user WHERE BINARY Username = ?");
    $stmt_username->bind_param("s", $username);
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();

    if ($result_username->num_rows === 1) {
        $row = $result_username->fetch_assoc();

        if (password_verify($password, $row["Password"])) {
            $_SESSION["UserID"] = $row["UserID"];
            $_SESSION["Username"] = $row["Username"];
            $_SESSION["Level"] = $row["Level"];
            
            switch ($_SESSION["Level"]) {
                case "administrator":
                    header("Location: user/administrator/index.php");
                    exit;
                case "petugas":
                    header("Location: user/petugas/index.php");
                    exit;
                case "peminjam":
                    header("Location: user/peminjam/index.php");
                    exit;
                default:
                    header("Location: index.php");
                    exit;
            }
        } else {
            $error = true; // Password salah
        }
    } else {
        $error = true; // Username tidak ditemukan
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/feather-icons">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/logoweb.jpeg" type="image/x-icon">
    <script>
        function hideError() {
            var errorElement = document.getElementById("error-message");
            if (errorElement) {
                errorElement.style.display = "none";
            }
        }

        function hideErrorAfterDelay() {
            setTimeout(hideError, 2000);
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
            <h3>Login Akun Perpustakaan Sekolah</h3><br>
                <form action="" method="post">
                    <div class="form-group">
                      <i class="bi bi-person-circle"></i>
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Masukkan username">
                    </div>
                    <div class="form-group">
                        <div class="password-container">
                          <i class="bi bi-lock-fill"></i>
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukkan password">
                                <span class="eye" id="toggle-password">
                                <i id="hide1" class="fa fa-eye eye"></i>
                                <i id="hide2" class="fa fa-eye-slash eye"></i>
                            </span>
                        </div>
                    </div>
                    <?php if (isset($error)) :?>
                        <p id="error-message" style="color: red; font-style: italic;">Username atau password salah!</p>
                        <script>
                            hideErrorAfterDelay();
                        </script>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-login" name="login">Masuk</button>
                    <p class="mt-3">Belum Punya Akun? <a href="register.php">klik Daftar</a></p>
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
