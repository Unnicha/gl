-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2021 at 11:17 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun_perkiraan`
--

CREATE TABLE `akun_perkiraan` (
  `kode_akun` varchar(100) NOT NULL,
  `nama_akun` varchar(100) NOT NULL,
  `golongan` varchar(100) NOT NULL,
  `jenis_akun` varchar(50) DEFAULT NULL,
  `tipe_akun` varchar(50) DEFAULT NULL,
  `tingkat` varchar(10) DEFAULT NULL,
  `induk` varchar(100) DEFAULT NULL,
  `saldo_normal` varchar(20) DEFAULT NULL,
  `saldo_awal` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `akun_perkiraan`
--

INSERT INTO `akun_perkiraan` (`kode_akun`, `nama_akun`, `golongan`, `jenis_akun`, `tipe_akun`, `tingkat`, `induk`, `saldo_normal`, `saldo_awal`) VALUES
('11', 'Aktiva', 'NERACA', '11', 'Induk', '1', '', '-', '-'),
('11.01', 'Kas Besar', 'NERACA', '11', 'Induk', '2', '', '-', '-'),
('11.01.001', 'Kas Kantor Jakarta', 'NERACA', '21', 'Anak', '3', '11.01', 'Debit', '-500000'),
('11.01.002', 'Kas Kantor Bandung', 'NERACA', '21', 'Anak', '3', '11.01', 'Debit', '500000'),
('11.02', 'Kas Kecil', 'NERACA', '11', 'Induk', '2', '', '-', '-'),
('11.03', 'Bank', 'NERACA', '11', 'Induk', '2', '', '-', '-'),
('11.03.001', 'Bank Mandiri', 'NERACA', '22', 'Anak', '3', '11.03', 'Debit', '-5000'),
('11.03.002', 'Bank BCA', 'NERACA', '22', 'Anak', '3', '11.03', 'Debit', '0'),
('11.03.003', 'Bank Mayapada', 'NERACA', '22', 'Anak', '3', '11.03', 'Debit', '0'),
('11.03.004', 'Bank BRI', 'NERACA', '22', 'Anak', '3', '11.03', 'Debit', '-10000000'),
('11.04', 'Piutang Dagang', 'NERACA', '11', 'Induk', '2', '', '-', '-'),
('11.04.001', 'Bapak Tono', 'NERACA', '29', 'Anak', '3', '11.04', 'Debit', '100000'),
('11.04.002', 'Bapak Kevin', 'NERACA', '29', 'Anak', '3', '11.04', 'Debit', '20000'),
('11.04.003', 'CV. Cerdas Mandiri', 'NERACA', '29', 'Anak', '3', '11.04', 'Debit', '5000'),
('31', 'Hutang', 'NERACA', '12', 'Induk', '1', '', '-', '-'),
('31.01', 'Hutang Dagang', 'NERACA', '12', 'Induk', '2', '', '-', '-'),
('31.01.001', 'PT. Andalin', 'NERACA', '30', 'Anak', '3', '31.01', 'Kredit', '0'),
('31.01.002', 'PT. Mandalik', 'NERACA', '30', 'Anak', '3', '31.01', 'Kredit', '0'),
('31.01.003', 'Hutang PT. Cahaya Baru', 'NERACA', '30', 'Anak', '3', '31.01', 'Kredit', '0'),
('31.02', 'Hutang Pajak', 'NERACA', '12', 'Induk', '2', '', '-', '-'),
('31.03', 'Hutang Biaya', 'NERACA', '12', 'Induk', '2', '', '-', '-'),
('31.04', 'Pajak Keluaran', 'NERACA', '12', 'Induk', '2', '', '-', '-'),
('41', 'Modal', 'NERACA', '12', 'Induk', '1', '', '-', '-'),
('41.01', 'Modal Pemilik', 'NERACA', '24', 'Anak', '2', '41', 'Kredit', '500000'),
('41.02', 'Modal Investor', 'NERACA', '12', 'Induk', '2', '', '-', '-'),
('51', 'Penjualan', 'LABARUGI', '', 'Induk', '1', '', '-', '-'),
('51.01', 'Penjualan', 'LABARUGI', '', 'Induk', '2', '', '-', '-'),
('51.01.001', 'Penjualan', 'LABARUGI', '23', 'Anak', '3', '51.01', 'Kredit', '0');

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `kode_transaksi` varchar(50) NOT NULL,
  `status_jurnal` varchar(50) NOT NULL,
  `no_voucher` varchar(50) NOT NULL,
  `tanggal_transaksi` varchar(50) NOT NULL,
  `jenis_saldo` varchar(50) NOT NULL,
  `akun_asal` varchar(50) NOT NULL,
  `akun_lawan` varchar(50) NOT NULL,
  `jumlah` varchar(50) NOT NULL,
  `mata_uang` varchar(50) NOT NULL,
  `nilai_konversi` varchar(50) NOT NULL,
  `ket_transaksi` varchar(255) NOT NULL,
  `no_giro` varchar(100) NOT NULL,
  `tgl_jatuh_tempo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`kode_transaksi`, `status_jurnal`, `no_voucher`, `tanggal_transaksi`, `jenis_saldo`, `akun_asal`, `akun_lawan`, `jumlah`, `mata_uang`, `nilai_konversi`, `ket_transaksi`, `no_giro`, `tgl_jatuh_tempo`) VALUES
('211210002', 'Mutasi', 'B001/21/06/001', '01/12/2021', 'DEBET', '11.03.001', '11.04.001', '10000000', 'IDR', '1', 'Uang Masuk Bapak Tono', '1', '01/01/2022'),
('211210003', 'Mutasi', 'B003/21/12/002', '01/12/2021', 'DEBET', '11.03.003', '11.04.002', '12000000', 'IDR', '1', 'Uang Masuk Bapak Kevin', '1', '01/01/2022');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `kode_barang` varchar(100) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `stok_awal` varchar(100) NOT NULL,
  `nilai_awal` varchar(100) NOT NULL,
  `jumlah` varchar(100) DEFAULT NULL,
  `proses` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`kode_barang`, `nama_barang`, `satuan`, `stok_awal`, `nilai_awal`, `jumlah`, `proses`) VALUES
('BK001', 'Buku Tulis 40 lembar', 'pcs', '100', '7000', NULL, 'HPP'),
('BK002', 'Buku Tulis 100 lembar', 'pcs', '100', '12000', NULL, 'HPP');

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` varchar(10) NOT NULL,
  `nama_jenis` varchar(100) NOT NULL,
  `tipe_jenis` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `nama_jenis`, `tipe_jenis`) VALUES
('11', 'AKTIVA', 'Induk'),
('12', 'PASSIVA', 'Induk'),
('21', 'Akun Kas', 'Anak'),
('22', 'Akun Bank', 'Anak'),
('23', 'Akun Penjualan', 'Anak'),
('24', 'Akun Pembelian', 'Anak'),
('25', 'Uang Muka Pembelian', 'Anak'),
('26', 'Uang Muka Penjualan', 'Anak'),
('27', 'Retur Penjualan', 'Anak'),
('28', 'Retur Pembelian', 'Anak'),
('29', 'Akun Piutang', 'Anak'),
('30', 'Akun Hutang', 'Anak'),
('31', 'Pajak Keluaran', 'Anak'),
('32', 'Pajak Masukan', 'Anak'),
('33', 'Akun Umum', 'Anak');

-- --------------------------------------------------------

--
-- Table structure for table `kas`
--

CREATE TABLE `kas` (
  `kode_transaksi` varchar(50) NOT NULL,
  `status_jurnal` varchar(50) NOT NULL,
  `no_voucher` varchar(50) NOT NULL,
  `tanggal_transaksi` varchar(50) NOT NULL,
  `jenis_saldo` varchar(50) NOT NULL,
  `akun_asal` varchar(50) NOT NULL,
  `akun_lawan` varchar(50) NOT NULL,
  `jumlah` varchar(50) NOT NULL,
  `mata_uang` varchar(50) NOT NULL,
  `nilai_konversi` varchar(50) NOT NULL,
  `ket_transaksi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kas`
--

INSERT INTO `kas` (`kode_transaksi`, `status_jurnal`, `no_voucher`, `tanggal_transaksi`, `jenis_saldo`, `akun_asal`, `akun_lawan`, `jumlah`, `mata_uang`, `nilai_konversi`, `ket_transaksi`) VALUES
('211110001', 'Mutasi', 'K001/21/06/001', '2021-11-29', 'Kredit', '11.01.001', '11.03.001', '15.65', 'SGD', '12150', 'Pembayaran Listrik'),
('211110002', 'Mutasi', 'K001/21/06/002', '2021-11-29', 'DEBET', '11.01.001', '11.04.001', '1000000', 'IDR', '1', 'Dana Bulan Desember');

-- --------------------------------------------------------

--
-- Table structure for table `kurs`
--

CREATE TABLE `kurs` (
  `kode_kurs` varchar(20) NOT NULL,
  `mata_uang` varchar(20) NOT NULL,
  `tanggal` varchar(20) NOT NULL,
  `nilai_kurs` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kurs`
--

INSERT INTO `kurs` (`kode_kurs`, `mata_uang`, `tanggal`, `nilai_kurs`) VALUES
('KMU21003', 'SGD', '13/12/2021', '12150'),
('KMU21004', 'IDR', '01/01/2000', '1');

-- --------------------------------------------------------

--
-- Table structure for table `mata_uang`
--

CREATE TABLE `mata_uang` (
  `kode_mu` varchar(100) NOT NULL,
  `nama_mu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mata_uang`
--

INSERT INTO `mata_uang` (`kode_mu`, `nama_mu`) VALUES
('IDR', 'Rupiah Indonesia'),
('SGD', 'Dollar Singapura'),
('USD', 'Dollar Amerika Serikat');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `kode_pelanggan` varchar(100) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `npwp` varchar(100) NOT NULL,
  `telp` varchar(100) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `akun_pelanggan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`kode_pelanggan`, `nama_pelanggan`, `alamat`, `npwp`, `telp`, `fax`, `akun_pelanggan`) VALUES
('PLG001', 'Bapak Tono', 'Jakarta Barat', '09184914', '09895', '82348', '11.04.001'),
('PLG002', 'Bapak Kevin', 'Jakarta', '9084293842', '02138332', '02138332', '11.04.002'),
('PLG003', 'CV. Cerdas Mandiri', 'Jakarta', '019394923', '02138332', '02138377', '11.04.003');

-- --------------------------------------------------------

--
-- Table structure for table `penamaan`
--

CREATE TABLE `penamaan` (
  `rekening` varchar(100) NOT NULL,
  `nama_rekening` varchar(100) NOT NULL,
  `penamaan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `kode_transaksi` varchar(50) NOT NULL,
  `status_jurnal` varchar(20) NOT NULL,
  `tanggal_transaksi` varchar(20) NOT NULL,
  `jatuh_tempo` varchar(20) NOT NULL,
  `faktur_pajak` varchar(50) NOT NULL,
  `surat_jalan` varchar(50) NOT NULL,
  `kode_pelanggan` varchar(20) NOT NULL,
  `mata_uang` varchar(20) NOT NULL,
  `konversi` varchar(20) NOT NULL,
  `jenis_saldo` varchar(20) NOT NULL,
  `akun_asal` varchar(50) NOT NULL,
  `akun_lawan` varchar(50) NOT NULL,
  `akun_ppn` varchar(50) NOT NULL,
  `ket_transaksi` varchar(255) NOT NULL,
  `no_giro` varchar(50) NOT NULL,
  `jatuh_tempo_giro` varchar(20) NOT NULL,
  `diskon_luar` varchar(50) NOT NULL,
  `jenis_ppn` varchar(50) NOT NULL,
  `besar_ppn` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan2`
--

CREATE TABLE `penjualan2` (
  `kode_transaksi` varchar(50) NOT NULL,
  `status_jurnal` varchar(20) NOT NULL,
  `tanggal_transaksi` varchar(20) NOT NULL,
  `jatuh_tempo` varchar(20) NOT NULL,
  `faktur_jual` varchar(50) NOT NULL,
  `surat_jalan` varchar(50) NOT NULL,
  `kode_pelanggan` varchar(20) NOT NULL,
  `mata_uang` varchar(20) NOT NULL,
  `konversi` varchar(20) NOT NULL,
  `jenis_saldo` varchar(20) NOT NULL,
  `ket_transaksi` varchar(255) NOT NULL,
  `no_giro` varchar(50) NOT NULL,
  `jatuh_tempo_giro` varchar(20) NOT NULL,
  `discount_luar` varchar(50) NOT NULL,
  `jenis_ppn` varchar(50) NOT NULL,
  `besar_ppn` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_jurnal`
--

CREATE TABLE `penjualan_jurnal` (
  `kode_jurnal` varchar(20) NOT NULL,
  `kode_transaksi` varchar(20) NOT NULL,
  `ket_jurnal` varchar(255) NOT NULL,
  `jenis_saldo_jurnal` varchar(20) NOT NULL,
  `kode_akun` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_produk`
--

CREATE TABLE `penjualan_produk` (
  `id_produk` varchar(100) NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `ket_produk` varchar(200) DEFAULT NULL,
  `jenis` varchar(20) NOT NULL,
  `qty` varchar(10) NOT NULL,
  `harga` varchar(50) NOT NULL,
  `diskon` varchar(50) DEFAULT NULL,
  `konv` varchar(20) NOT NULL,
  `jenis_pph` varchar(20) DEFAULT NULL,
  `besar_pph` varchar(20) DEFAULT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `kode_jurnal` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `kode_supplier` varchar(100) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `npwp` varchar(100) NOT NULL,
  `telp` varchar(100) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `akun_supplier` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`kode_supplier`, `nama_supplier`, `alamat`, `npwp`, `telp`, `fax`, `akun_supplier`) VALUES
('SUP001', 'PT. Andalin', 'kota tua', '1221212871', '665657', '979797', '31.01.001'),
('SUP002', 'PT. Mandalik', 'Jakarta Pusat', '709849232', '98328392', '98328392', '31.01.002');

-- --------------------------------------------------------

--
-- Table structure for table `tipe`
--

CREATE TABLE `tipe` (
  `tipe_id` int(11) NOT NULL,
  `tipe_nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tipe`
--

INSERT INTO `tipe` (`tipe_id`, `tipe_nama`) VALUES
(1, 'Induk'),
(2, 'Anak');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun_perkiraan`
--
ALTER TABLE `akun_perkiraan`
  ADD PRIMARY KEY (`kode_akun`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`kode_transaksi`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kode_barang`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `kas`
--
ALTER TABLE `kas`
  ADD PRIMARY KEY (`kode_transaksi`);

--
-- Indexes for table `kurs`
--
ALTER TABLE `kurs`
  ADD PRIMARY KEY (`kode_kurs`);

--
-- Indexes for table `mata_uang`
--
ALTER TABLE `mata_uang`
  ADD PRIMARY KEY (`kode_mu`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`kode_pelanggan`);

--
-- Indexes for table `penamaan`
--
ALTER TABLE `penamaan`
  ADD PRIMARY KEY (`rekening`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`kode_transaksi`);

--
-- Indexes for table `penjualan2`
--
ALTER TABLE `penjualan2`
  ADD PRIMARY KEY (`kode_transaksi`);

--
-- Indexes for table `penjualan_jurnal`
--
ALTER TABLE `penjualan_jurnal`
  ADD PRIMARY KEY (`kode_jurnal`);

--
-- Indexes for table `penjualan_produk`
--
ALTER TABLE `penjualan_produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`kode_supplier`);

--
-- Indexes for table `tipe`
--
ALTER TABLE `tipe`
  ADD PRIMARY KEY (`tipe_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tipe`
--
ALTER TABLE `tipe`
  MODIFY `tipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
