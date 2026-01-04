<?php
// Include koneksi database
include 'function.php';

// Ambil ID dari parameter URL
$id = $_GET['id'];

// Jalankan query DELETE untuk menghapus produk berdasarkan ID
mysqli_query($conn, "DELETE FROM produk WHERE id=$id");

// Redirect kembali ke halaman index admin
header("Location: index_admin.php");
