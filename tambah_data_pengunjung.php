<?php
session_start();
include 'function.php';

/*
|--------------------------------------------------------------------------
| TAMBAH PRODUK KE KERANJANG
|--------------------------------------------------------------------------
| - Cek login
| - Cek / buat keranjang
| - Tambah atau update detail keranjang
*/

if (!isset($_SESSION['id_user'])) {
    header("Location: login_pengunjung.php");
    exit;
}

$user_id   = $_SESSION['id_user'];
$produk_id = $_GET['id']; // id produk dari URL

// CEK KERANJANG USER
$keranjang = query("SELECT * FROM keranjang WHERE user_id = $user_id");

if (empty($keranjang)) {
    mysqli_query($conn, "INSERT INTO keranjang (user_id) VALUES ($user_id)");
    $keranjang_id = mysqli_insert_id($conn);
} else {
    $keranjang_id = $keranjang[0]['id'];
}

// CEK PRODUK SUDAH ADA / BELUM
$detail = query("
    SELECT * FROM detail_keranjang 
    WHERE keranjang_id = $keranjang_id 
    AND produk_id = $produk_id
");

if ($detail) {
    // JIKA SUDAH ADA → TAMBAH QTY
    mysqli_query($conn, "
        UPDATE detail_keranjang 
        SET kuantitas = kuantitas + 1 
        WHERE id = {$detail[0]['id']}
    ");
} else {
    // JIKA BELUM → INSERT BARU
    mysqli_query($conn, "
        INSERT INTO detail_keranjang (keranjang_id, produk_id, kuantitas)
        VALUES ($keranjang_id, $produk_id, 1)
    ");
}

header("Location: index_pengunjung.php");
exit;
