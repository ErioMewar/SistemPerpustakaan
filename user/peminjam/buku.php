<?php
session_start();
require '../../database/db.php';

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['Username'];
$user_id = $_SESSION['UserID'];

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

// Inisialisasi variabel $buku_per_kategori
$buku_per_kategori = [];

if (isset($_GET['kategori_id'])) {
    $kategori_id = $_GET['kategori_id'];

    // Ambil nama kategori untuk judul
    $query_kategori = "SELECT * FROM kategoribuku WHERE KategoriID = $kategori_id";
    $result_kategori = $db->query($query_kategori);
    if ($result_kategori) {
        $row_kategori = $result_kategori->fetch_assoc();
        $nama_kategori = $row_kategori['NamaKategori'];
    }

    // Ambil buku berdasarkan kategori
    $buku_per_kategori = fetchBooksByCategory($db, $kategori_id, $user_id);
}

function fetchBooksByCategory($db, $id_kategori, $user_id) {
    $sql_buku = "SELECT buku.*, peminjaman.StatusPeminjaman
                    FROM buku
                    LEFT JOIN kategoribuku_relasi ON buku.BukuID = kategoribuku_relasi.BukuID 
                    LEFT JOIN peminjaman ON buku.BukuID = peminjaman.BukuID
                    WHERE kategoribuku_relasi.KategoriID = ? 
                    AND (peminjaman.UserID = ? OR peminjaman.UserID IS NULL)
                    ORDER BY buku.Judul ASC";

    $stmt = $db->prepare($sql_buku);
    $stmt->bind_param("ii", $id_kategori, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $books;
}

function executeSearchQuery($db, $search_keyword, $user_id) {
    $search_query = "SELECT buku.*, peminjaman.StatusPeminjaman, kategoribuku.NamaKategori
                    FROM buku
                    LEFT JOIN kategoribuku_relasi ON buku.BukuID = kategoribuku_relasi.BukuID
                    LEFT JOIN kategoribuku ON kategoribuku_relasi.KategoriID = kategoribuku.KategoriID
                    LEFT JOIN peminjaman ON buku.BukuID = peminjaman.BukuID
                    WHERE (peminjaman.UserID = ? OR peminjaman.UserID IS NULL)
                    AND (Judul LIKE ? OR Penulis LIKE ? OR Penerbit LIKE ? OR TahunTerbit LIKE ? OR kategoribuku.NamaKategori LIKE ?)
                    ORDER BY kategoribuku_relasi.KategoriID ASC, buku.Judul ASC";

    $search_keyword = "%$search_keyword%";
    $stmt = $db->prepare($search_query);
    $stmt->bind_param("isssss", $user_id, $search_keyword, $search_keyword, $search_keyword, $search_keyword, $search_keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    $search_results = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $search_results;
}

$kategori_dengan_buku = array();

$sql_kategori = "SELECT * FROM kategoribuku ORDER BY NamaKategori ASC";
$hasil_kategori = $db->query($sql_kategori);

while ($kategori = $hasil_kategori->fetch_assoc()) {
    $id_kategori = $kategori['KategoriID'];
    $nama_kategori = $kategori['NamaKategori'];
    $buku = fetchBooksByCategory($db, $id_kategori, $user_id);

    $kategori_dengan_buku[] = array(
        'id_kategori' => $id_kategori,
        'nama_kategori' => $nama_kategori,
        'buku' => $buku
    );
}

if (isset($_GET['submit_search'])) {
    $search_keyword = trim($_GET['search']);

    if (!empty($search_keyword)) {
        $search_results = executeSearchQuery($db, $search_keyword, $user_id);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/feather-icons">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/table.css">
    <link rel="shortcut icon" href="../../img/logoweb.jpeg" type="image/x-icon">
    <style>
        .bi-bookmark,
        .bi-bookmark-fill{
        cursor: pointer;
}
    </style>
</head>
<body>
    <?php include_once('../../dashboard/navbar.php'); ?>
    <?php include_once('../../dashboard/sidebar.php'); ?>
    <h2>Daftar Buku</h2>
    <div class="main-content">
        <form method="GET" action="">
            <input type="text" id="search" name="search" placeholder="Cari Buku" style="border-radius: 10px; border: none; padding: 8px">
            <button type="submit" name="submit_search" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
            <button type="submit" class="btn btn btn-primary" style="color: #ffffff;"><i class="bi bi-arrow-clockwise"></i></button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Judul Buku</th>
                    <th scope="col">Nama Penulis</th>
                    <th scope="col">Nama Penerbit</th>
                    <th scope="col">Tahun Terbit</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Pinjam</th>
                    <th scope="col">Favorit</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php if (!empty($buku_per_kategori)) : ?>
                    <?php foreach ($buku_per_kategori as $buku) : ?>
                    <tr>
                        <th scope="row"><?php echo $counter; ?></th>
                        <td><?php echo $buku['Judul']; ?></td>
                        <td><?php echo $buku['Penulis']; ?></td>
                        <td><?php echo $buku['Penerbit']; ?></td>
                        <td><?php echo $buku['TahunTerbit']; ?></td>
                        <td><?php echo $nama_kategori = $row_kategori['NamaKategori']; ?></td>
                        <td>
                            <?php
                            $buku_id = $buku['BukuID'];
                            $judul_buku = $buku['Judul'];
                            $status_peminjaman = $buku['StatusPeminjaman'];
                            $is_pemilik_peminjaman = ($_SESSION['Username'] === $username && $status_peminjaman === 'Dipinjam');
                            $button_text = ($is_pemilik_peminjaman) ? 'Balikan Buku <i class="fa fa-sign-in"></i>' : 'Pinjam Buku <i class="fa fa-sign-out"></i>';
                            $button_class = $is_pemilik_peminjaman ? 'btn btn-danger' : 'btn btn-primary';
                            $check_koleksi_query = "SELECT * FROM koleksipribadi WHERE UserID = '$user_id' AND BukuID = '$buku_id'";
                            $check_koleksi_result = $db->query($check_koleksi_query);
                            $has_data_in_koleksi = ($check_koleksi_result && mysqli_num_rows($check_koleksi_result) > 0);
                            ?>
                            <?php if ($is_pemilik_peminjaman) : ?>
                                <!-- Display a form for "Kembalikan Buku" action -->
                                <form method="post" action="../../function/fungsiKembalikan.php" class="d-inline-block">
                                    <input type="hidden" name="buku_id" value="<?php echo $buku_id; ?>">
                                    <button type="submit" name="pinjam-button" class="pinjam-button <?php echo $button_class; ?>"><?php echo $button_text; ?></button>
                                </form>
                            <?php else : ?>
                                <!-- Display a link for "Pinjam" action -->
                                <?php
                                $redirect_url = ($status_peminjaman === 'Dipinjam') ? '../../function/fungsiKembalikan.php' : '../../function/fungsiPeminjaman.php';
                                ?>
                                <a href="<?php echo $redirect_url . '?buku_id=' . $buku_id . '&judul=' . $judul_buku; ?>" class="pinjam-button <?php echo $button_class; ?>"><?php echo $button_text; ?></a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            if ($has_data_in_koleksi) {
                                ?>
                                <form method="POST" action="../../function/hapusMark.php?from=buku" class="d-inline-block">
                                    <input type="hidden" name="BukuID" value="<?php echo $buku_id; ?>">
                                    <button type="submit" id="mark1" class="btn btn-warning"><i class="bi bi-bookmark-fill text-light"></i></button>
                                </form>
                                <?php
                            }
                            if (!$has_data_in_koleksi) {
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
                    <?php $counter++; ?>
                <?php endforeach; ?>
                <?php elseif (isset($search_results) && !empty($search_results)) : ?>
                    <?php foreach ($search_results as $result) : ?>
                        <tr>
                            <th scope="row"><?php echo $counter; ?></th>
                            <td><?php echo $result['Judul']; ?></td>
                            <td><?php echo $result['Penulis']; ?></td>
                            <td><?php echo $result['Penerbit']; ?></td>
                            <td><?php echo $result['TahunTerbit']; ?></td>
                            <td><?php echo $result['NamaKategori']; ?></td>
                            <td>
                                <?php
                                $buku_id = $result['BukuID'];
                                $judul_buku = $result['Judul'];
                                $status_peminjaman = $result['StatusPeminjaman'];
                                $is_pemilik_peminjaman = ($_SESSION['Username'] === $username && $status_peminjaman === 'Dipinjam');
                                $button_text = ($is_pemilik_peminjaman) ? 'Balikan Buku <i class="fa fa-sign-in"></i>' : 'Pinjam Buku <i class="fa fa-sign-out"></i>';
                                $button_class = $is_pemilik_peminjaman ? 'btn btn-danger' : 'btn btn-primary';
                                $check_koleksi_query = "SELECT * FROM koleksipribadi WHERE UserID = '$user_id' AND BukuID = '$buku_id'";
                                $check_koleksi_result = $db->query($check_koleksi_query);
                                $has_data_in_koleksi = ($check_koleksi_result && mysqli_num_rows($check_koleksi_result) > 0);
                                ?>
                                <?php if ($is_pemilik_peminjaman) : ?>
                                    <!-- Display a form for "Kembalikan Buku" action -->
                                    <form method="post" action="../../function/fungsiKembalikan.php" class="d-inline-block">
                                        <input type="hidden" name="buku_id" value="<?php echo $buku_id; ?>">
                                        <button type="submit" name="pinjam-button" class="pinjam-button <?php echo $button_class; ?>"><?php echo $button_text; ?></button>
                                    </form>
                                <?php else : ?>
                                    <!-- Display a link for "Pinjam" action -->
                                    <?php
                                    $redirect_url = ($status_peminjaman === 'Dipinjam') ? '../../function/fungsiKembalikan.php' : '../../function/fungsiPeminjaman.php';
                                    ?>
                                    <a href="<?php echo $redirect_url . '?buku_id=' . $buku_id . '&judul=' . $judul_buku; ?>" class="pinjam-button <?php echo $button_class; ?>"><?php echo $button_text; ?></a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                if ($has_data_in_koleksi) {
                                    ?>
                                    <form method="POST" action="../../function/hapusMark.php?from=buku" class="d-inline-block">
                                        <input type="hidden" name="BukuID" value="<?php echo $buku_id; ?>">
                                        <button type="submit" id="mark1" class="btn btn-warning"><i class="bi bi-bookmark-fill text-light"></i></button>
                                    </form>
                                    <?php
                                }
                                if (!$has_data_in_koleksi) {
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
                        <?php $counter++; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach ($kategori_dengan_buku as $kategori) : ?>
                        <?php foreach ($kategori['buku'] as $buku) : ?>
                            <tr>
                                <th scope="row"><?php echo $counter; ?></th>
                                <td><?php echo $buku['Judul']; ?></td>
                                <td><?php echo $buku['Penulis']; ?></td>
                                <td><?php echo $buku['Penerbit']; ?></td>
                                <td><?php echo $buku['TahunTerbit']; ?></td>
                                <td><?php echo $kategori['nama_kategori']; ?></td>
                                <td>
                                    <?php
                                    $buku_id = $buku['BukuID'];
                                    $judul_buku = $buku['Judul'];
                                    $status_peminjaman = $buku['StatusPeminjaman'];
                                    $is_pemilik_peminjaman = ($_SESSION['Username'] === $username && $status_peminjaman === 'Dipinjam');
                                    $button_text = ($is_pemilik_peminjaman) ? 'Balikan Buku <i class="fa fa-sign-in"></i>' : 'Pinjam Buku <i class="fa fa-sign-out"></i>';
                                    $button_class = $is_pemilik_peminjaman ? 'btn btn-danger' : 'btn btn-primary';
                                    $check_koleksi_query = "SELECT * FROM koleksipribadi WHERE UserID = '$user_id' AND BukuID = '$buku_id'";
                                    $check_koleksi_result = $db->query($check_koleksi_query);
                                    $has_data_in_koleksi = ($check_koleksi_result && mysqli_num_rows($check_koleksi_result) > 0);
                                    ?>
                                    <?php if ($is_pemilik_peminjaman) : ?>
                                        <!-- Display a form for "Kembalikan Buku" action -->
                                        <form method="post" action="../../function/fungsiKembalikan.php" class="d-inline-block">
                                            <input type="hidden" name="buku_id" value="<?php echo $buku_id; ?>">
                                            <button type="submit" name="pinjam-button" class="pinjam-button <?php echo $button_class; ?>"><?php echo $button_text; ?></button>
                                        </form>
                                    <?php else : ?>
                                        <!-- Display a link for "Pinjam" action -->
                                        <?php
                                        $redirect_url = ($status_peminjaman === 'Dipinjam') ? '../../function/fungsiKembalikan.php' : '../../function/fungsiPeminjaman.php';
                                        ?>
                                        <a href="<?php echo $redirect_url . '?buku_id=' . $buku_id . '&judul=' . $judul_buku; ?>" class="pinjam-button <?php echo $button_class; ?>"><?php echo $button_text; ?></a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($has_data_in_koleksi) {
                                        ?>
                                        <form method="POST" action="../../function/hapusMark.php?from=buku" class="d-inline-block">
                                            <input type="hidden" name="BukuID" value="<?php echo $buku_id; ?>">
                                            <button type="submit" id="mark1" class="btn btn-warning"><i class="bi bi-bookmark-fill text-light"></i></button>
                                        </form>
                                        <?php
                                    }
                                    if (!$has_data_in_koleksi) {
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
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
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
