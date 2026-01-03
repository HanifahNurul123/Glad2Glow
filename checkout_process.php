<?php
session_start();
include 'function.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$id_user = $_SESSION['id_user'];
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada produk yang dipilih']);
    exit;
}

$selected_items = $data['items']; // Array of detail_keranjang IDs
$total_harga = 0;
$items_to_process = [];

// 1. Validasi item dan hitung total
foreach ($selected_items as $id_detail) {
    // Ambil data detail keranjang
    $query = "SELECT detail_keranjang.*, produk.harga 
              FROM detail_keranjang 
              JOIN produk ON produk.id = detail_keranjang.produk_id 
              JOIN keranjang ON keranjang.id = detail_keranjang.keranjang_id
              WHERE detail_keranjang.id = $id_detail AND keranjang.user_id = $id_user";

    $result = query($query);

    if (!empty($result)) {
        $item = $result[0];
        $subtotal = $item['harga'] * $item['kuantitas'];
        $total_harga += $subtotal;
        $items_to_process[] = [
            'produk_id' => $item['produk_id'],
            'kuantitas' => $item['kuantitas'],
            'subtotal' => $subtotal,
            'id_detail_keranjang' => $item['id']
        ];
    }
}

if (empty($items_to_process)) {
    echo json_encode(['status' => 'error', 'message' => 'Item tidak valid']);
    exit;
}

// 2. Buat Pesanan Baru
$conn->begin_transaction();

try {
    // Insert ke tabel pesanan
    $query_pesanan = "INSERT INTO pesanan (user_id, tanggal_pesanan, total_harga, status) 
                      VALUES ('$id_user', NOW(), '$total_harga', 'Berhasil')";
    mysqli_query($conn, $query_pesanan);
    $id_pesanan = mysqli_insert_id($conn);

    // 3. Pindahkan data ke detail_pesanan & Hapus dari keranjang
    foreach ($items_to_process as $proc) {
        $p_id = $proc['produk_id'];
        $qty = $proc['kuantitas'];
        $sub = $proc['subtotal'];
        $id_detail_lama = $proc['id_detail_keranjang'];

        // Masukkan ke detail pesanan
        $query_detail = "INSERT INTO detail_pesanan (pesanan_id, produk_id, kuantitas, subtotal) 
                         VALUES ('$id_pesanan', '$p_id', '$qty', '$sub')";
        mysqli_query($conn, $query_detail);

        // Hapus dari detail_keranjang
        $query_hapus = "DELETE FROM detail_keranjang WHERE id = $id_detail_lama";
        mysqli_query($conn, $query_hapus);

        // (Opsional) Kurangi stok di tabel produk jika perlu
        // mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id = $p_id");
    }

    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dibuat!']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses pesanan: ' . $e->getMessage()]);
}
