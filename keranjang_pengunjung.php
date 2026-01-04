<?php
// Memulai sesi untuk mengakses data user yang login
session_start();
include 'function.php';

// Cek apakah user sudah login, jika belum redirect ke halaman login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_pengunjung.php");
    exit;
}

// Ambil ID User dari session
$user_id = $_SESSION['id_user'];
// Ambil info detail user dari database
$dataUser = query("SELECT * FROM user WHERE id = $user_id")[0];

// ============================================================
// AMBIL DATA KERANJANG USER
// ============================================================
// Query join tabel detail_keranjang, keranjang, dan produk 
// untuk menampilkan item apa saja yang ada di keranjang user
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <!-- Bootstrap CSS -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 untuk notifikasi cantik -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS Keranjang -->
    <link rel="stylesheet" href="assets/css/keranjang.css">
</head>

<body>

    <!-- Header Shopee Style -->
    <div class="navbar-shopee py-3 sticky-top">
        <div class="container main-content d-flex align-items-center">
            <a href="index_pengunjung.php" class="back-link"><i class="fa-solid fa-arrow-left"></i></a>
            <h4 class="m-0 mb-0 fw-bold">Keranjang Belanja</h4>
        </div>
    </div>

    <div class="main-content">

        <!-- HEADER SELECT ALL -->
        <div class="cart-header">
            <input type="checkbox" class="item-checkbox" id="checkAllTop" onclick="toggleAll(this)">
            <span class="fs-6 text-muted">Produk</span>
        </div>

        <!-- ITEMS LIST -->
        <div class="bg-white">
            <!-- Jika keranjang kosong -->
            <?php if (empty($items)): ?>
                <div class="empty-cart">
                    <i class="fa-solid fa-cart-shopping fa-3x mb-3 text-muted"></i>
                    <p>Keranjangmu masi kosong nih.</p>
                    <a href="index_pengunjung.php" class="btn btn-outline-danger btn-sm mt-2">Belanja Sekarang</a>
                </div>
            <?php else: ?>

                <!-- Loop item keranjang -->
                <?php foreach ($items as $item):
                    $subtotal = $item['harga'] * $item['kuantitas'];
                ?>
                    <div class="cart-item" id="item-<?= $item['id_detail']; ?>">
                        <!-- Checkbox untuk memilih item yang akan di-checkout -->
                        <input type="checkbox"
                            class="item-checkbox item-check"
                            data-id="<?= $item['id_detail']; ?>"
                            data-price="<?= $subtotal; ?>"
                            data-qty="<?= $item['kuantitas']; ?>"
                            onchange="calculateTotal()">

                        <!-- Gambar Produk -->
                        <img src="assets/img/<?= $item['gambar']; ?>" class="item-img" alt="Produk">

                        <!-- Detail Produk (Nama & Harga) -->
                        <div class="item-details">
                            <div class="item-title"><?= htmlspecialchars($item['name']); ?></div>
                            <div class="item-price">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></div>
                        </div>

                        <!-- Kuantitas -->
                        <div class="qty-wrapper me-4">
                            <span class="text-muted small me-2">x <?= $item['kuantitas']; ?></span>
                        </div>

                        <!-- Tombol Hapus / Ubah -->
                        <a href="hapus_data_pengunjung.php?id=<?= $item['id_detail']; ?>"
                            class="btn-delete"
                            onclick="return confirm('Hapus produk ini?')">Ubah</a>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

    </div>

    <!-- STICKY BOTTOM BAR (Total & Checkout) -->
    <?php if (!empty($items)): ?>
        <div class="checkout-bar">
            <div class="bar-content">
                <div class="select-all-wrapper">
                    <!-- Checkbox Pilih Semua Bawah -->
                    <label for="checkAllBottom" style="cursor: pointer;">Pilih Semua (<?= count($items); ?>)</label>
                </div>

                <div class="total-section">
                    <span class="total-label">Total Pembayaran:</span>
                    <span class="total-price" id="totalDisplay">Rp 0</span>
                </div>

                <!-- Tombol Checkout -->
                <button class="btn-checkout" onclick="processCheckout()">Checkout (<span id="countDisplay">0</span>)</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // FUNCTION: Pilih Semua Checkbox
        function toggleAll(source) {
            let checkboxes = document.querySelectorAll('.item-check');
            checkboxes.forEach(cb => cb.checked = source.checked);
            calculateTotal();
        }

        // FUNCTION: Hitung Total Harga dari item yang dicentang
        function calculateTotal() {
            let checkboxes = document.querySelectorAll('.item-check:checked');
            let total = 0;
            let count = 0;

            checkboxes.forEach(cb => {
                total += parseFloat(cb.getAttribute('data-price'));
                count++;
            });

            // Update Tampilan Total Harga & Jumlah Item
            document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('countDisplay').innerText = count;

            // Sinkronisasi Checkbox "Pilih Semua" di atas
            let allCheckboxes = document.querySelectorAll('.item-check');
            document.getElementById('checkAllTop').checked = (checkboxes.length === allCheckboxes.length) && (allCheckboxes.length > 0);
        }

        // FUNCTION: Proses Checkout via AJAX
        function processCheckout() {
            let selected = [];
            // Kumpulkan ID detail_keranjang yang dipilih
            document.querySelectorAll('.item-check:checked').forEach(cb => {
                selected.push(cb.getAttribute('data-id'));
            });

            // Validasi: harus pilih minimal 1
            if (selected.length === 0) {
                Swal.fire('Ups!', 'Pilih minimal satu produk untuk di-checkout', 'warning');
                return;
            }

            // Kirim data ke checkout_process.php menggunakan Fetch API
            fetch('checkout_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        items: selected
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Jika sukses, tampilkan notifikasi dan reload halaman
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pesanan berhasil dibuat. Terima kasih!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ee4d2d'
                        }).then((result) => {
                            window.location.reload();
                        });
                    } else {
                        // Jika gagal dari server
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                });
        }
    </script>
</body>

</html>