-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 27 Feb 2024 pada 01.26
-- Versi server: 5.7.31
-- Versi PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

DROP TABLE IF EXISTS `buku`;
CREATE TABLE IF NOT EXISTS `buku` (
  `BukuID` int(11) NOT NULL AUTO_INCREMENT,
  `Judul` varchar(255) NOT NULL,
  `Penulis` varchar(255) NOT NULL,
  `Penerbit` varchar(255) NOT NULL,
  `TahunTerbit` int(11) NOT NULL,
  PRIMARY KEY (`BukuID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`BukuID`, `Judul`, `Penulis`, `Penerbit`, `TahunTerbit`) VALUES
(27, 'Bahasa Indonesia kelas XII', 'Maman Suryaman, Suherli, dan Istiqomah.', 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud', 2018),
(28, 'Matematika kelas XII', 'Abdur Rahman Asâ€™ari, Tjang Daniel Chandra, Ipung Yuwono, Lathiful Anwar, Syaiful Hamzah Nasution, Dahliatul Hasanah, Makbul Muksar, Vita Kusuma Sari, Nur Atikah.', 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud', 2018),
(29, 'Pulang Sebuah Perjalanan', 'Leila S. Chudori', 'Kepustakaan Populer Gramedia (KPG)', 2012),
(30, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005),
(31, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategoribuku`
--

DROP TABLE IF EXISTS `kategoribuku`;
CREATE TABLE IF NOT EXISTS `kategoribuku` (
  `KategoriID` int(11) NOT NULL AUTO_INCREMENT,
  `NamaKategori` varchar(255) NOT NULL,
  PRIMARY KEY (`KategoriID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kategoribuku`
--

INSERT INTO `kategoribuku` (`KategoriID`, `NamaKategori`) VALUES
(8, 'Non Fiksi'),
(11, 'Fiksi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategoribuku_relasi`
--

DROP TABLE IF EXISTS `kategoribuku_relasi`;
CREATE TABLE IF NOT EXISTS `kategoribuku_relasi` (
  `KategoriBukuID` int(11) NOT NULL AUTO_INCREMENT,
  `BukuID` int(11) NOT NULL,
  `KategoriID` int(11) NOT NULL,
  PRIMARY KEY (`KategoriBukuID`),
  KEY `BukuID` (`BukuID`,`KategoriID`),
  KEY `KategoriID` (`KategoriID`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kategoribuku_relasi`
--

INSERT INTO `kategoribuku_relasi` (`KategoriBukuID`, `BukuID`, `KategoriID`) VALUES
(39, 27, 8),
(40, 28, 8),
(41, 29, 8),
(42, 30, 11),
(43, 31, 11);

-- --------------------------------------------------------

--
-- Struktur dari tabel `koleksipribadi`
--

DROP TABLE IF EXISTS `koleksipribadi`;
CREATE TABLE IF NOT EXISTS `koleksipribadi` (
  `KoleksiID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `BukuID` int(11) NOT NULL,
  PRIMARY KEY (`KoleksiID`),
  KEY `UserID` (`UserID`,`BukuID`),
  KEY `BukuID` (`BukuID`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `PeminjamanID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `BukuID` int(11) NOT NULL,
  `TanggalPeminjaman` date NOT NULL,
  `TanggalPengembalian` date NOT NULL,
  `StatusPeminjaman` varchar(255) NOT NULL,
  PRIMARY KEY (`PeminjamanID`),
  KEY `UserID` (`UserID`),
  KEY `BukuID` (`BukuID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

DROP TABLE IF EXISTS `pengembalian`;
CREATE TABLE IF NOT EXISTS `pengembalian` (
  `LaporanID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `BukuID` int(11) NOT NULL,
  `TanggalPeminjaman` date NOT NULL,
  `TanggalPengembalian` date NOT NULL,
  `StatusPeminjaman` varchar(255) NOT NULL,
  PRIMARY KEY (`LaporanID`),
  KEY `UserID` (`UserID`,`BukuID`),
  KEY `BukuID` (`BukuID`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pengembalian`
--

INSERT INTO `pengembalian` (`LaporanID`, `UserID`, `BukuID`, `TanggalPeminjaman`, `TanggalPengembalian`, `StatusPeminjaman`) VALUES
(57, 32, 31, '2024-02-27', '2024-03-09', 'Dikembalikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasanbuku`
--

DROP TABLE IF EXISTS `ulasanbuku`;
CREATE TABLE IF NOT EXISTS `ulasanbuku` (
  `UlasanID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `BukuID` int(11) NOT NULL,
  `Ulasan` text NOT NULL,
  `Rating` int(11) NOT NULL,
  `TanggalUpload` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UlasanID`),
  KEY `UserID` (`UserID`,`BukuID`),
  KEY `BukuID` (`BukuID`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ulasanbuku`
--

INSERT INTO `ulasanbuku` (`UlasanID`, `UserID`, `BukuID`, `Ulasan`, `Rating`, `TanggalUpload`) VALUES
(56, 1, 31, 'halo', 3, '2024-02-27 00:10:10'),
(57, 32, 27, 'adsf', 5, '2024-02-27 00:18:40'),
(58, 27, 27, 'asdf', 3, '2024-02-27 00:16:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `NamaLengkap` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `Level` enum('administrator','petugas','peminjam') NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `NamaLengkap`, `Alamat`, `Level`) VALUES
(1, 'Erio', '$2y$10$0wV2RaolOHz40mSwYUkIYea.VaPr.LCKRN/f8mQWvHlTv09U6Gv/u', 'eriomewar1357@gmail.com', 'Viergio Vergian Mewar', 'Nania', 'administrator'),
(27, 'budisanto', '$2y$10$VfBVkGmvKrnHxpFTWg86y.z9HHtUU4jP/hRa8V34j6ErN6XLoDRkm', 'budisusanto@email.com', 'Budi Rahmat Susanto', 'Wayame', 'petugas'),
(31, 'sitirahayu', '$2y$10$twTsFHmNit90TSPfzy6a8.J62KYyX8/nB3vCw0OGa4LmwkepLs/iu', 'sitirahayu@emailprovider.com', 'Siti Nur Rahayu', 'Poka', 'petugas'),
(32, 'adipratama', '$2y$10$tyVjcPQnP0BngDjFmiW3nutUVtMInGDAfzDNOualXPu74THvGVWaG', 'adipratama@emailservice.com', 'Adi Wijaya Pratama', 'Waiheru', 'peminjam'),
(39, 'dewikusuma', '$2y$10$b2ZjyD8q7Ieami/kYomF5O6a4RjK.glJ4kYPsDYKOy7jLgi/mHX3G', 'dewikusuma@emailhost.com', 'Dewi Ratna Kusuma', 'Batu Merah', 'peminjam'),
(40, 'irfanhakim', '$2y$10$vcrAtaagT3exSfSb40PleuF1nEcTV3NrqikC6AYdpvWjND25u5pI2', 'irfanhakim@emailprovider.com', 'Irfan Maulana Hakim', 'Tawiri', 'peminjam');

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kategoribuku_relasi`
--
ALTER TABLE `kategoribuku_relasi`
  ADD CONSTRAINT `kategoribuku_relasi_ibfk_2` FOREIGN KEY (`KategoriID`) REFERENCES `kategoribuku` (`KategoriID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kategoribuku_relasi_ibfk_3` FOREIGN KEY (`BukuID`) REFERENCES `buku` (`BukuID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `koleksipribadi`
--
ALTER TABLE `koleksipribadi`
  ADD CONSTRAINT `koleksipribadi_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `koleksipribadi_ibfk_2` FOREIGN KEY (`BukuID`) REFERENCES `buku` (`BukuID`);

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`BukuID`) REFERENCES `buku` (`BukuID`);

--
-- Ketidakleluasaan untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `pengembalian_ibfk_2` FOREIGN KEY (`BukuID`) REFERENCES `buku` (`BukuID`);

--
-- Ketidakleluasaan untuk tabel `ulasanbuku`
--
ALTER TABLE `ulasanbuku`
  ADD CONSTRAINT `ulasanbuku_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `ulasanbuku_ibfk_2` FOREIGN KEY (`BukuID`) REFERENCES `buku` (`BukuID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
