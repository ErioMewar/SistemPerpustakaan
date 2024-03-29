<?php
session_start();
require '../../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$level = $_SESSION['Level'];
$username = $_SESSION['Username'];

$queryPetugas = "SELECT * FROM user WHERE Level = 'petugas' ORDER BY NamaLengkap";
$stmtPetugas = $db->prepare($queryPetugas);
$stmtPetugas->execute();
$resultPetugas = $stmtPetugas->get_result();

$queryPeminjam = "SELECT * FROM user WHERE Level = 'peminjam' ORDER BY NamaLengkap";
$stmtPeminjam = $db->prepare($queryPeminjam);
$stmtPeminjam->execute();
$resultPeminjam = $stmtPeminjam->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/table.css">
    <link rel="shortcut icon" href="../../img/logoweb.jpeg" type="image/x-icon">
    
</head>
<body>
    <?php include_once('../../dashboard/navbar.php'); ?>
    <?php include_once('../../dashboard/sidebar.php'); ?>
    <div class="main-content">
        <h3>Petugas</h3>
        <a href="../../function/tambahUser.php?Level=petugas" class="btn btn-primary"><i class="bi bi-plus-circle" title="Tambah"></i> Tambah Petugas</a>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat</th>
                    <th>Umur</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                while ($row = $resultPetugas->fetch_assoc()) {
                    ?>
                    <tr>
                        <th><?php echo $counter; ?></th>
                        <td class="break"><?php echo $row['Username']; ?></td>
                        <td class="break"><?php echo $row['Email']; ?></td>
                        <td class="break"><?php echo $row['NamaLengkap']; ?></td>
                        <td class="break"><?php echo $row['Alamat']; ?></td>
                        <td class="break"><?php echo $row['Umur']; ?></td>
                        <td class="break"><?php echo $row['JenisKelamin']; ?></td>
                        <td>
                            <a href="../../function/editUser.php?UserID=<?php echo $row['UserID']; ?>&Level=<?php echo strtolower($row['Level']); ?>" class="btn btn-primary"><i class="bi bi-pencil" title="Edit"></i> Edit</a>
                            <a href="../../function/hapusUser.php?UserID=<?php echo $row['UserID']; ?>&Level=<?php echo strtolower($row['Level']); ?>" onclick="return confirm('Yakin ingin menghapus Petugas ini?');" class="btn btn-danger"><i class="bi bi-trash" title="Hapus"></i> Hapus</a>
                        </td>
                    </tr>
                    <?php
                    $counter++;
                }
                ?>
            </tbody>
        </table>
        <h3 id="peminjam">Peminjam</h3>
        <a href="../../function/tambahUser.php?Level=peminjam" class="btn btn-primary"><i class="bi bi-plus-circle" title="Tambah"></i> Tambah Peminjam</a>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat</th>
                    <th>Umur</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                while ($row = $resultPeminjam->fetch_assoc()) {
                    ?>
                    <tr>
                        <th><?php echo $counter; ?></th>
                        <td class="break"><?php echo $row['Username']; ?></td>
                        <td class="break"><?php echo $row['Email']; ?></td>
                        <td class="break"><?php echo $row['NamaLengkap']; ?></td>
                        <td class="break"><?php echo $row['Alamat']; ?></td>
                        <td class="break"><?php echo $row['Umur']; ?></td>
                        <td class="break"><?php echo $row['JenisKelamin']; ?></td>
                        <td>
                            <a href="../../function/editUser.php?UserID=<?php echo $row['UserID']; ?>&Level=<?php echo strtolower($row['Level']); ?>" class="btn btn-primary"><i class="bi bi-pencil" title="Edit"></i> Edit</a>
                            <a href="../../function/hapusUser.php?UserID=<?php echo $row['UserID']; ?>&Level=<?php echo strtolower($row['Level']); ?>" onclick="return confirm('Yakin ingin menghapus Peminjam ini?');" class="btn btn-danger"><i class="bi bi-trash" title="Hapus"></i> Hapus</a>
                        </td>
                    </tr>
                    <?php
                    $counter++;
                }
                ?>
            </tbody>
        </table>
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
