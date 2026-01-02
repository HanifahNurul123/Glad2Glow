<?php
include 'function.php';

/*
|--------------------------------------------------------------------------
| UPDATE JUMLAH PRODUK DI KERANJANG
|--------------------------------------------------------------------------
*/

$id_detail = $_POST['id_detail'];
$kuantitas = $_POST['kuantitas'];

mysqli_query($conn, "
    UPDATE detail_keranjang 
    SET kuantitas = $kuantitas
    WHERE id = $id_detail
");

header("Location: keranjang_pengunjung.php");
exit;
