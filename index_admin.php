<?php
// Memulai sesi untuk manajemen login
session_start();

// Menghubungkan file function.php untuk akses database dan fungsi bantuan
include 'function.php';

// ============================================================
// CEK SESSION LOGIN
// ============================================================
// Jika belum login sebagai admin (session 'admin' tidak ada),
// maka paksa redirect ke halaman pilihan.php
if (!isset($_SESSION['admin'])) {
    header("Location: pilihan.php");
    exit; // Hentikan eksekusi script setelah redirect
}

// ============================================================
// LOGIK: TAMBAH DATA PRODUK
// ============================================================
// Mengecek apakah tombol 'simpan' pada modal tambah ditekan
if (isset($_POST['simpan'])) {
    // Ambil data dari form
    $id_kategori = $_POST['id_kategori'];
    $nama        = $_POST['name'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];

    // Ambil detail file gambar yang diupload
    $gambar     = $_FILES['gambar']['name'];
    $tmpGambar  = $_FILES['gambar']['tmp_name'];

    // Cek jika ada gambar yang diupload
    if (!empty($gambar)) {
        // Pindahkan file ke folder assets/img
        move_uploaded_file($tmpGambar, "assets/img/" . $gambar);
    } else {
        $gambar = ""; // Default jika tidak ada gambar
    }

    // Query INSERT untuk menyimpan data produk baru
    mysqli_query($conn, "
        INSERT INTO produk 
        (id_kategori, name, harga, stok, gambar)
        VALUES
        ('$id_kategori', '$nama', '$harga', '$stok', '$gambar')
    ");

    // Redirect kembali ke index_admin.php agar data ter-refresh
    header("Location: index_admin.php");
    exit;
}

// ============================================================
// LOGIK: UBAH DATA PRODUK
// ============================================================
// Mengecek apakah tombol 'update' pada modal edit ditekan
if (isset($_POST['update'])) {
    // Ambil data dari form update
    $id          = $_POST['id_produk'];
    $id_kategori = $_POST['id_kategori'];
    $nama        = $_POST['name'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $gambarLama  = $_POST['gambarLama']; // Gambar lama untuk backup jika tidak diganti

    // Cek apakah user mengupload gambar baru
    $gambar     = $_FILES['gambar']['name'];
    $tmpGambar  = $_FILES['gambar']['tmp_name'];

    if (!empty($gambar)) {
        // Jika ada gambar baru, upload gambar tersebut
        move_uploaded_file($tmpGambar, "assets/img/" . $gambar);
    } else {
        // Jika tidak ada gambar baru, tetap pakai gambar lama
        $gambar = $gambarLama;
    }

    // Query UPDATE untuk memperbarui data produk
    mysqli_query($conn, "
        UPDATE produk 
        SET 
            id_kategori = '$id_kategori',
            name        = '$nama',
            harga       = '$harga',
            stok        = '$stok',
            gambar      = '$gambar'
        WHERE id = '$id'
    ");

    // Redirect setelah update berhasil
    header("Location: index_admin.php");
    exit;
}

// ============================================================
// QUERY DATA UTAMA
// ============================================================
// Mengambil semua data produk digabungkan (JOIN) dengan nama kategorinya
$produk = query("
    SELECT produk.*, kategori.name AS nama_kategori
    FROM produk
    JOIN kategori ON produk.id_kategori = kategori.id
");

// Ambil semua data kategori untuk opsi di Dropdown (Select Option)
$kategori = query("SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin | Data Produk</title>
    <!-- Meload Bootstrap CSS dari folder assets lokal -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3 class="mb-3">Data Produk</h3>

        <!-- 
            TOMBOL TAMBAH PRODUK
            Atribut data-bs-toggle="modal" dan data-bs-target="#modalTambah"
            digunakan untuk memicu munculnya modal dengan ID 'modalTambah'
        -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Produk
        </button>

        <!-- Tombol Logout -->
        <a href="logout_admin.php" class="btn btn-danger mb-3 ms-2">
            Logout
        </a>

        <!-- TABEL DATA PRODUK -->
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
                <!-- Looping menampilkan setiap baris data produk -->
                <?php $no = 1;
                foreach ($produk as $p): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $p['name']; ?></td>
                        <td><?= $p['nama_kategori']; ?></td>
                        <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                        <td><?= $p['stok']; ?></td>
                        <td>
                            <!-- Menampilkan gambar produk -->
                            <img src="assets/img/<?= $p['gambar']; ?>" width="60">
                        </td>
                        <td>
                            <!-- 
                                TOMBOL EDIT
                                Memicu modal yang spesifik untuk ID produk ini: #modalUbah<?= $p['id']; ?>
                            -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbah<?= $p['id']; ?>">
                                Edit
                            </button>

                            <!-- 
                                TOMBOL HAPUS
                                Mengirim ID produk ke file hapus_data_admin.php
                                javascript onclick confirm untuk konfirmasi sebelum menghapus
                            -->
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

    <!-- 
        ===========================================================
        MODAL TAMBAH PRODUK
        =========================================================== 
    -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form menggunakan enctype="multipart/form-data" karena ada upload file -->
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
                                <!-- Loop Data Kategori untuk Dropdown -->
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

    <!-- 
        ===========================================================
        MODAL UBAH PRODUK (DILOOPING)
        ===========================================================
        Kita meloop modal ini sebanyak jumlah produk agar setiap produk
        memiliki modal editnya sendiri dengan ID unik (modalUbah{ID}).
        Value inputan diisi dengan data produk saat ini
    -->
    <?php foreach ($produk as $p): ?>
        <div class="modal fade" id="modalUbah<?= $p['id']; ?>" tabindex="-1" aria-labelledby="modalUbahLabel<?= $p['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="modalUbahLabel<?= $p['id']; ?>">Ubah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <!-- Input Hidden untuk menyimpan ID produk yang sedang diedit -->
                            <input type="hidden" name="id_produk" value="<?= $p['id']; ?>">
                            <!-- Input Hidden untuk menyimpan nama file gambar lama -->
                            <input type="hidden" name="gambarLama" value="<?= $p['gambar']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="name" class="form-control" value="<?= $p['name']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="id_kategori" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($kategori as $k): ?>
                                        <!-- Cek jika ID kategori sama dengan kategori produk saat ini, maka tambahkan 'selected' -->
                                        <option value="<?= $k['id']; ?>" <?= ($k['id'] == $p['id_kategori']) ? 'selected' : ''; ?>>
                                            <?= $k['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" value="<?= $p['harga']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" value="<?= $p['stok']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gambar Produk</label>
                                <br>
                                <!-- Tampilkan preview gambar lama -->
                                <img src="assets/img/<?= $p['gambar']; ?>" width="100" class="mb-2">
                                <input type="file" name="gambar" class="form-control">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="update" class="btn btn-warning">Ubah Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Load JavaScript Bootstrap Lokal -->
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>