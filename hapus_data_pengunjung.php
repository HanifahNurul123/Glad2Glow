<?php
include 'function.php';

/*
|--------------------------------------------------------------------------
| HAPUS PRODUK DARI KERANJANG
|--------------------------------------------------------------------------
*/

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM detail_keranjang WHERE id = $id");

header("Location: keranjang_pengunjung.php");
exit;
