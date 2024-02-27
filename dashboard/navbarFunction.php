<?php
// Mendapatkan judul halaman dari URL
$current_page = basename($_SERVER['PHP_SELF']);

// Tetapkan nama sistem berdasarkan judul halaman
if ($current_page === 'index.php') {
    $nama_sistem = 'Beranda';
}elseif ($current_page === 'dataUser.php'){
    $nama_sistem = 'Data User';
}elseif ($current_page === 'editUser.php'){
    $nama_sistem = 'Edit ' . ucwords($level);
}elseif ($current_page === 'tambahUser.php'){
    $nama_sistem = 'Tambah ' . ucwords($level);
} elseif ($current_page === 'peminjaman.php') {
    $nama_sistem = 'Laporan Peminjaman';
}elseif ($current_page === 'pengembalian.php'){
    $nama_sistem = 'Laporan Pengembalian';
}elseif ($current_page === 'buku.php'){
    $nama_sistem = 'Buku';
}elseif ($current_page === 'tambahBuku.php'){
    $nama_sistem = 'Tambah Buku';
}elseif ($current_page === 'editBuku.php'){
    $nama_sistem = 'Edit Buku';
}elseif ($current_page === 'fungsiPeminjaman.php'){
    $nama_sistem = 'Pinjam Buku';
}elseif ($current_page === 'bukuDipinjam.php'){
    $nama_sistem = 'Sedang Dipinjam';
}elseif ($current_page === 'kategori.php'){
    $nama_sistem = 'Kategori';
}elseif ($current_page === 'editKategori.php'){
    $nama_sistem = 'Edit Kategori';
}elseif ($current_page === 'ulasan.php'){
    $nama_sistem = 'Ulasan';
}elseif ($current_page === 'koleksi.php'){
    $nama_sistem = 'Koleksi';
}elseif ($current_page === 'editUlasan.php'){
    $nama_sistem = 'Edit Ulasan';
}else {
    // Default jika tidak cocok dengan halaman manapun
    $nama_sistem = 'Sistem Perpustakaan';
}
?>

<div class="navbar">
    <div class="menu-icon" onclick="toggleSidebar()">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
    <div class="username">
        <i class="bi bi-person-circle"></i>
        <?php echo " $username"; ?>
        <?php if(isset($_SESSION['Level'])): ?>
            (<span class="user-role"><?php echo $_SESSION['Level']; ?></span>)
        <?php endif; ?>
    </div>
    <div class="system-name"><?php echo $nama_sistem; ?></div>
    <div class="logout-button">
        <a href="../logout.php">Logout <i class="bi bi-box-arrow-right"></i></a>
    </div>
</div>
