<?php

session_start();
include 'function.php';

if (!isset($_SESSION['admin'])) {
    header("Location: pilihan.php");
}

// PROSES SIMPAN DATA (jika tombol simpan ditekan)
if (isset($_POST['simpan'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama        = $_POST['name'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];

    // upload gambar
    $gambar     = $_FILES['gambar']['name'];
    $tmpGambar  = $_FILES['gambar']['tmp_name'];

    // Cek jika ada gambar yang diupload
    if (!empty($gambar)) {
        move_uploaded_file($tmpGambar, "img/" . $gambar);
    } else {
        $gambar = ""; // Atur default jika tidak ada gambar (opsional)
    }

    mysqli_query($conn, "
        INSERT INTO produk 
        (id_kategori, name, harga, stok, gambar)
        VALUES
        ('$id_kategori', '$nama', '$harga', '$stok', '$gambar')
    ");

    // Refresh halaman agar data baru muncul
    header("Location: index_admin.php");
    exit;
}

$produk = query("
    SELECT produk.*, kategori.name AS nama_kategori
    FROM produk
    JOIN kategori ON produk.id_kategori = kategori.id
");

// Ambil data kategori untuk dropdown modal
$kategori = query("SELECT * FROM kategori");
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

        <!-- Tombol Pemicu Modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Produk
        </button>

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

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k['id']; ?>">
                                        <?= $k['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" name="gambar" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan" class="btn btn-danger">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>