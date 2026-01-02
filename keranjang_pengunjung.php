<?php
session_start();
include 'function.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_pengunjung.php");
    exit;
}

$user_id = $_SESSION['id_user'];

// AMBIL DATA KERANJANG USER
$items = query("
    SELECT 
        detail_keranjang.id AS id_detail,
        produk.name,
        produk.harga,
        produk.gambar,
        detail_keranjang.kuantitas
    FROM detail_keranjang
    JOIN keranjang ON keranjang.id = detail_keranjang.keranjang_id
    JOIN produk ON produk.id = detail_keranjang.produk_id
    WHERE keranjang.user_id = $user_id
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang | Glad2Glow</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#fff5f8; }
.btn-pink { background:#d63384; color:white; }
.btn-pink:hover { background:#b02a6b; }
</style>
</head>

<body>
<div class="container mt-5">

<h3 class="text-center text-danger mb-4">🛒 Keranjang Belanja</h3>

<?php if (empty($items)): ?>
    <div class="alert alert-warning text-center">
        Keranjang kamu masih kosong 😢
    </div>
<?php else: ?>

<table class="table table-bordered bg-white text-center align-middle">
<thead class="table-danger">
<tr>
    <th>Produk</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php foreach ($items as $item): 
    $subtotal = $item['harga'] * $item['kuantitas'];
    $total += $subtotal;
?>
<tr>
    <td>
        <img src="img/<?= $item['gambar']; ?>" width="60"><br>
        <?= $item['name']; ?>
    </td>

    <td>Rp <?= number_format($item['harga']); ?></td>

    <!-- UPDATE QTY -->
    <td>
        <form action="update_data_pengunjung.php" method="POST" class="d-flex justify-content-center">
            <input type="hidden" name="id_detail" value="<?= $item['id_detail']; ?>">
            <input type="number" name="kuantitas" value="<?= $item['kuantitas']; ?>" min="1" class="form-control w-50">
            <button class="btn btn-sm btn-pink ms-2">✔</button>
        </form>
    </td>

    <td>Rp <?= number_format($subtotal); ?></td>

    <td>
        <a href="hapus_data_pengunjung.php?id=<?= $item['id_detail']; ?>"
           onclick="return confirm('Hapus produk ini?')"
           class="btn btn-danger btn-sm">
           Hapus
        </a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h4 class="text-end text-danger">
Total: Rp <?= number_format($total); ?>
</h4>

<a href="index_pengunjung.php" class="btn btn-secondary mt-3">← Lanjut Belanja</a>

<?php endif; ?>

</div>
</body>
</html>
