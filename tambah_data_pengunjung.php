<?php
session_start();
include 'function.php';

/*
|--------------------------------------------------------------------------
| TAMBAH PRODUK KE KERANJANG
|--------------------------------------------------------------------------
| File ini menangani logika saat user mengklik tombol "Beli" / "Keranjang"
| 1. Cek apakah user sudah login
| 2. Cek apakah user sudah punya keranjang aktif
| 3. Cek apakah produk tersebut sudah ada di keranjang
|    - Jika ADA: Update kuantitas (+1)
|    - Jika BELUM: Insert data baru ke detail_keranjang
*/

// 1. Cek Login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_pengunjung.php");
    exit;
}

$user_id   = $_SESSION['id_user'];
$produk_id = $_GET['id']; // Ambil ID produk dari parameter URL

// 2. CEK KERANJANG USER
// Cari apakah user ini sudah memiliki ID keranjang utama
$keranjang = query("SELECT * FROM keranjang WHERE user_id = $user_id");

if (empty($keranjang)) {
    // Jika belum punya keranjang, buat baru
    mysqli_query($conn, "INSERT INTO keranjang (user_id) VALUES ($user_id)");
    $keranjang_id = mysqli_insert_id($conn);
} else {
    // Jika sudah punya, pakai ID keranjang yang ada
    $keranjang_id = $keranjang[0]['id'];
}

// 3. CEK APAKAH PRODUK SUDAH ADA DI DETAIL KERANJANG
$detail = query("
    SELECT * FROM detail_keranjang 
    WHERE keranjang_id = $keranjang_id 
    AND produk_id = $produk_id
");

if ($detail) {
    // JIKA SUDAH ADA → Cukup tambahkan kuantitasnya
    mysqli_query($conn, "
        UPDATE detail_keranjang 
        SET kuantitas = kuantitas + 1 
        WHERE id = {$detail[0]['id']}
    ");
} else {
    // JIKA BELUM ADA → Masukkan sebagai item baru dengan kuantitas 1
    mysqli_query($conn, "
        INSERT INTO detail_keranjang (keranjang_id, produk_id, kuantitas)
        VALUES ($keranjang_id, $produk_id, 1)
    ");
}

// Redirect kembali ke halaman utama setelah berhasil
header("Location: index_pengunjung.php");
exit;
