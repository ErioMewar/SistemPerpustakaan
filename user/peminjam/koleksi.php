<?php
session_start();
require '../../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];
$user_id = $_SESSION['UserID'];

$query = "SELECT b.BukuID, b.Judul 
            FROM buku b
            JOIN koleksipribadi k ON b.BukuID = k.BukuID
            JOIN user u ON k.UserID = u.UserID
            WHERE u.Username = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Koleksi</title>
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
        <h2>Koleksi Buku</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    <th>Favorit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $buku_id = $row['BukuID'];
                    $judul_buku = $row['Judul'];
                    $check_koleksi_query = "SELECT * FROM koleksipribadi WHERE UserID = '$user_id' AND BukuID = '$buku_id'";
                    $check_koleksi_result = $db->query($check_koleksi_query);
                    $has_data_in_koleksi = ($check_koleksi_result && mysqli_num_rows($check_koleksi_result) > 0);
                    ?>
                    <tr>
                        <td><?php echo $counter; ?></td>
                        <td><?php echo $judul_buku; ?></td>
                        <td>
                        <?php
                            if ($has_data_in_koleksi) {
                                ?>
                                <form method="POST" action="../../function/hapusMark.php?from=koleksi" class="d-inline-block">
                                <input type="hidden" name="BukuID" value="<?php echo $buku_id; ?>">
                                <button type="submit" id="mark1" class="btn btn-warning"><i class="bi bi-bookmark-fill text-light"></i></button>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form method="POST" action="../../function/tambahMark.php" class="d-inline-block">
                                    <input type="hidden" name="BukuID" value="<?php echo $buku_id; ?>">
                                    <button type="submit" id="mark2" class="btn btn-warning"><i class="bi bi-bookmark text-light"></i></button>
                                </form>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $counter++;
                }
                $stmt->close();
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
