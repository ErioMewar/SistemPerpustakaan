<?php
require '../database/db.php';
session_start();

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];
$level = $_GET['Level'];

if (isset($_POST['cancel'])) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $userID = $_POST['UserID'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $namaLengkap = $_POST['full-name'];
    $alamat = $_POST['alamat'];

    // Query untuk update data pengguna
    $query = "UPDATE user SET Username = ?, Email = ?, NamaLengkap = ?, Alamat = ? WHERE UserID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssi", $username, $email, $namaLengkap, $alamat, $userID);
    $stmt->execute();

    // Redirect ke halaman daftar pengguna setelah update
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

if (isset($_GET['UserID'])) {
    $userID = $_GET['UserID'];

    // Query untuk mengambil data pengguna berdasarkan UserID
    $query = "SELECT * FROM user WHERE UserID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit <?php echo ucfirst($level); ?></title>
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
    <h2>Edit <?php echo ucfirst($level); ?></h2>
    <div class="main-content">
    <form action="" method="POST">
        <input type="hidden" name="UserID" value="<?php echo $row['UserID']; ?>">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['Username']; ?>">
        </div>
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
<?php
    } else {
        echo "User not found.";
    }

    $stmt->close();
}
?>
