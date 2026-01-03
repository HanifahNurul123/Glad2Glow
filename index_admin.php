<?php

session_start();
include 'function.php';

if (!isset($_SESSION['admin'])) {
    header("Location: pilihan.php");
}

$produk = query("
    SELECT produk.*, kategori.name AS nama_kategori
    FROM produk
    JOIN kategori ON produk.id_kategori = kategori.id
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin | Data Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3 class="mb-3">Data Produk</h3>

        <a href="tambah_data_admin.php" class="btn btn-primary mb-3">
            + Tambah Produk
        </a>
        <a href="logout_admin.php" class="btn btn-danger mb-3 ms-2">
            Logout
        </a>

        <table class="table table-bordered table-hover bg-white text-center">
            <thead class="table-danger">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1;
                foreach ($produk as $p): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $p['name']; ?></td>
                        <td><?= $p['nama_kategori']; ?></td>
                        <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                        <td><?= $p['stok']; ?></td>
                        <td>
                            <img src="img/<?= $p['gambar']; ?>" width="60">
                        </td>
                        <td>
                            <a href="edit_data_admin.php?id=<?= $p['id']; ?>" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <a href="hapus_data_admin.php?id=<?= $p['id']; ?>"
                                onclick="return confirm('Yakin hapus data?')"
                                class="btn btn-danger btn-sm">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>