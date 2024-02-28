<div class="sidebar" id="sidebar">
        <a href="index.php"><i class="bi bi-house-door"></i> Beranda</a>
        <?php if ($_SESSION['Level'] != 'peminjam'): ?>
        <a href="dataUser.php"><i class="bi bi-person"></i> Data User</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'administrator' && $_SESSION['Level'] != 'petugas'): ?>
        <a href="dataUser.php"><i class="bi bi-person"></i> Data Peminjam</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'administrator' && $_SESSION['Level'] != 'petugas'): ?>
        <a href="bukuDipinjam.php"><i class="fa fa-sign-out"></i> Sedang Dipinjam</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'peminjam'): ?>
        <a href="peminjaman.php"><i class="bi bi-list"></i> Laporan Peminjaman</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'peminjam'): ?>
        <a href="pengembalian.php"><i class="bi bi-list-check"></i> Laporan Pengembalian</a>
        <?php endif; ?>
        <a href="buku.php"><i class="bi bi-book"></i> Buku</a>
        <a href="kategori.php"><i class="bi bi-list-task"></i> Kategori</a>
        <a href="ulasan.php"><i class="bi bi-chat-dots"></i> Ulasan</a>
        <?php if ($_SESSION['Level'] != 'administrator' && $_SESSION['Level'] != 'petugas'): ?>
        <a href="koleksi.php"><i class="bi bi-bookmarks"></i> Koleksi</a>
        <?php endif; ?>
    </div>