<?php
include 'function.php';

// ambil data kategori
$kategori = query("SELECT * FROM kategori");

// PROSES SIMPAN DATA
if (isset($_POST['simpan'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama        = $_POST['name'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];

    // upload gambar
    $gambar     = $_FILES['gambar']['name'];
    $tmpGambar  = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmpGambar, "img/" . $gambar);

    mysqli_query($conn, "
        INSERT INTO produk 
        (id_kategori, name, harga, stok, gambar)
        VALUES
        ('$id_kategori', '$nama', '$harga', '$stok', '$gambar')
    ");

    header("Location: index_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Tambah Produk</h4>
        </div>

        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">

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

                <div class="d-flex justify-content-between">
                    <a href="index_admin.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" name="simpan" class="btn btn-danger">
                        Simpan Produk
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>