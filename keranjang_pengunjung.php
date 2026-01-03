<?php
session_start();
include 'function.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_pengunjung.php");
    exit;
}

$user_id = $_SESSION['id_user'];
$dataUser = query("SELECT * FROM user WHERE id = $user_id")[0];

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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            padding-bottom: 100px;
            /* Space for sticky bar */
        }

        .navbar-shopee {
            background: linear-gradient(-180deg, #f53d2d, #f63);
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            max-width: 900px;
            margin: auto;
        }

        /* CARD HEADER (Nama Toko style) */
        .cart-header {
            background: white;
            border-radius: 3px;
            padding: 15px 20px;
            margin-top: 20px;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
        }

        /* CARD ITEM */
        .cart-item {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px;
            display: flex;
            align-items: center;
            transition: background 0.2s;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-checkbox {
            margin-right: 15px;
            transform: scale(1.3);
            accent-color: #ee4d2d;
            cursor: pointer;
        }

        .item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #e0e0e0;
            margin-right: 15px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-title {
            font-size: 14px;
            margin-bottom: 5px;
            color: #222;
            line-height: 1.4;
        }

        .item-price {
            color: #ee4d2d;
            font-weight: 500;
        }

        /* QUANTITY */
        .qty-wrapper {
            display: flex;
            align-items: center;
        }

        .qty-btn {
            border: 1px solid rgba(0, 0, 0, .09);
            background: transparent;
            width: 25px;
            height: 25px;
            text-align: center;
            cursor: pointer;
        }

        .qty-input {
            width: 40px;
            height: 25px;
            border: 1px solid rgba(0, 0, 0, .09);
            border-left: 0;
            border-right: 0;
            text-align: center;
            font-size: 14px;
        }

        /* DELETE BUTTON */
        .btn-delete {
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-delete:hover {
            color: #ee4d2d;
        }

        /* STICKY FOOTER */
        .checkout-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 0;
            z-index: 999;
        }

        .bar-content {
            max-width: 900px;
            margin: auto;
            display: flex;
            justify-content: flex-end;
            /* Align right like Shopee */
            align-items: center;
            height: 70px;
        }

        .select-all-wrapper {
            margin-right: auto;
            padding-left: 20px;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
        }

        .total-section {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .total-label {
            font-size: 14px;
            color: #222;
            margin-right: 10px;
        }

        .total-price {
            color: #ee4d2d;
            font-size: 20px;
            font-weight: bold;
        }

        .btn-checkout {
            background: #ee4d2d;
            color: white;
            border: none;
            height: 100%;
            width: 180px;
            font-size: 16px;
            font-weight: 500;
        }

        .btn-checkout:hover {
            background: #d73211;
        }

        .empty-cart {
            text-align: center;
            padding: 50px;
            color: #888;
        }

        .back-link {
            text-decoration: none;
            color: white;
            margin-right: 15px;
            font-size: 1.2rem;
        }
    </style>
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
            <?php if (empty($items)): ?>
                <div class="empty-cart">
                    <i class="fa-solid fa-cart-shopping fa-3x mb-3 text-muted"></i>
                    <p>Keranjangmu masi kosong nih.</p>
                    <a href="index_pengunjung.php" class="btn btn-outline-danger btn-sm mt-2">Belanja Sekarang</a>
                </div>
            <?php else: ?>

                <?php foreach ($items as $item):
                    $subtotal = $item['harga'] * $item['kuantitas'];
                ?>
                    <div class="cart-item" id="item-<?= $item['id_detail']; ?>">
                        <!-- Checkbox -->
                        <input type="checkbox"
                            class="item-checkbox item-check"
                            data-id="<?= $item['id_detail']; ?>"
                            data-price="<?= $subtotal; ?>"
                            data-qty="<?= $item['kuantitas']; ?>"
                            onchange="calculateTotal()">

                        <!-- Gambar -->
                        <img src="img/<?= $item['gambar']; ?>" class="item-img" alt="Produk">

                        <!-- Detail Porduk -->
                        <div class="item-details">
                            <div class="item-title"><?= htmlspecialchars($item['name']); ?></div>
                            <div class="item-price">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></div>
                        </div>

                        <!-- Quantity -->
                        <div class="qty-wrapper me-4">
                            <span class="text-muted small me-2">x <?= $item['kuantitas']; ?></span>
                        </div>

                        <!-- Delete -->
                        <a href="hapus_data_pengunjung.php?id=<?= $item['id_detail']; ?>"
                            class="btn-delete"
                            onclick="return confirm('Hapus produk ini?')">Ubah</a>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

    </div>

    <!-- STICKY BOTTOM BAR -->
    <?php if (!empty($items)): ?>
        <div class="checkout-bar">
            <div class="bar-content">
                <div class="select-all-wrapper">

                    <label for="checkAllBottom" style="cursor: pointer;">Pilih Semua (<?= count($items); ?>)</label>
                </div>

                <div class="total-section">
                    <span class="total-label">Total Pembayaran:</span>
                    <span class="total-price" id="totalDisplay">Rp 0</span>
                </div>

                <button class="btn-checkout" onclick="processCheckout()">Checkout (<span id="countDisplay">0</span>)</button>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // FUNCTION CHECK ALL
        function toggleAll(source) {
            let checkboxes = document.querySelectorAll('.item-check');
            checkboxes.forEach(cb => cb.checked = source.checked);
            calculateTotal();
        }

        // CALCULATE TOTAL
        function calculateTotal() {
            let checkboxes = document.querySelectorAll('.item-check:checked');
            let total = 0;
            let count = 0;

            checkboxes.forEach(cb => {
                total += parseFloat(cb.getAttribute('data-price'));
                count++;
            });

            // Update UI
            document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('countDisplay').innerText = count;

            // Check All Sync
            let allCheckboxes = document.querySelectorAll('.item-check');
            document.getElementById('checkAllTop').checked = checkboxes.length === allCheckboxes.length;
        }

        // PROCESS CHECKOUT
        function processCheckout() {
            let selected = [];
            document.querySelectorAll('.item-check:checked').forEach(cb => {
                selected.push(cb.getAttribute('data-id'));
            });

            if (selected.length === 0) {
                Swal.fire('Ups!', 'Pilih minimal satu produk untuk di-checkout', 'warning');
                return;
            }

            // AJAX POST
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