<div class="sidebar" id="sidebar">
        <a href="../user/<?php echo $_SESSION['Level']; ?>/index.php"><i class="bi bi-house-door"></i> Beranda</a>
        <?php if ($_SESSION['Level'] != 'peminjam'): ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/dataUser.php"><i class="bi bi-person"></i> Data User</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'administrator' && $_SESSION['Level'] != 'petugas'): ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/dataUser.php"><i class="bi bi-person"></i> Data Peminjam</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'administrator' && $_SESSION['Level'] != 'petugas'): ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/bukuDipinjam.php"><i class="fa fa-sign-out"></i> Sedang Dipinjam</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'peminjam'): ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/peminjaman.php"><i class="bi bi-list"></i> Laporan Peminjaman</a>
        <?php endif; ?>
        <?php if ($_SESSION['Level'] != 'peminjam'): ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/pengembalian.php"><i class="bi bi-list-check"></i> Laporan Pengembalian</a>
        <?php endif; ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/buku.php"><i class="bi bi-book"></i> Buku</a>
        <?php if ($_SESSION['Level'] != 'peminjam'): ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/kategori.php"><i class="bi bi-list-task"></i> Kategori</a>
        <?php endif; ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/ulasan.php"><i class="bi bi-chat-dots"></i> Ulasan</a>
        <?php if ($_SESSION['Level'] != 'administrator' && $_SESSION['Level'] != 'petugas'): ?>
        <a href="../user/<?php echo $_SESSION['Level']; ?>/koleksi.php"><i class="bi bi-bookmarks"></i> Koleksi</a>
        <?php endif; ?>
    </div>