<?php
include 'function.php';

/*
|--------------------------------------------------------------------------
| HAPUS PRODUK DARI KERANJANG
|--------------------------------------------------------------------------
| Menghapus item spesifik dari tabel detail_keranjang berdasarkan ID-nya.
*/

$id = $_GET['id'];

// Hapus data dari database
mysqli_query($conn, "DELETE FROM detail_keranjang WHERE id = $id");

// Redirect kembali ke halaman keranjang
header("Location: keranjang_pengunjung.php");
exit;
