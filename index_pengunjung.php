<?php
// Memulai sesi untuk manajemen user
session_start();

// Menghubungkan file helper functions
include 'function.php';

// ============================================================
// CEK SESSION LOGIN USER
// ============================================================
// Jika user belum login (session id_user tidak ada), redirect ke halaman login/pilihan
if (!isset($_SESSION['id_user'])) {
    header("Location: pilihan.php");
    exit;
}

// ============================================================
// AMBIL DATA USER
// ============================================================
$id_user = $_SESSION['id_user'];
// Ambil data profile user (nama, email, dll) dari database
$dataUser = query("SELECT * FROM user WHERE id = $id_user")[0];


// ============================================================
// LOGIK PENCARIAN & FILTER PRODUK
// ============================================================
// Jika ada parameter 'keyword' di URL (hasil pencarian)
if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
    $judulProduk = "Hasil pencarian: " . htmlspecialchars($_GET['keyword']);
    // Cari produk menggunakan fungsi search_data
    $produk = search_data($_GET['keyword']);
} else {
    // Jika tidak mencari, tampilkan semua produk
    $judulProduk = "✨ All Produk ✨";
    $produk = query("
        SELECT produk.*, kategori.name AS nama_kategori
        FROM produk
        JOIN kategori ON produk.id_kategori = kategori.id
    ");
}

// ============================================================
// HITUNG TOTAL ITEM DI KERANJANG
// ============================================================
$id_user = $_SESSION['id_user'] ?? null;
$totalItem = 0;

if ($id_user !== null) {
    // Hitung sum (total) kuantitas semua item di keranjang user ini
    $dataKeranjang = query("
        SELECT SUM(detail_keranjang.kuantitas) AS total_item
        FROM detail_keranjang
        JOIN keranjang ON keranjang.id = detail_keranjang.keranjang_id
        WHERE keranjang.user_id = $id_user
    ");

    $totalItem = $dataKeranjang[0]['total_item'] ?? 0;
}

// ============================================================
// AMBIL PRODUK PER KATEGORI (UNTUK BAGIAN BAWAH)
// ============================================================
if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
    $keyword = $_GET['keyword'];

    // Jika sedang mode cari, semua section kategori menampilkan hasil pencarian yang sama
    // (Opsional: bisa disesuaikan agar tetap per kategori jika diinginkan)
    $lip       = search_data($keyword);
    $powder    = search_data($keyword);
    $facewash  = search_data($keyword);
} else {
    // Jika mode normal, ambil data spesifik per kategori
    // ID Kategori: 1 = Lips, 2 = Powder, 3 = Face Wash (Asumsi ID di database)
    $lip = query("SELECT * FROM produk WHERE id_kategori = 1");
    $powder = query("SELECT * FROM produk WHERE id_kategori = 2");
    $facewash = query("SELECT * FROM produk WHERE id_kategori = 3");
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glad2Glow</title>

    <!-- Menggunakan Bootstrap & FontAwesome Lokal/CDN -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS Custom Utama -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- Header Sticky -->
    <header class="sticky-top shadow-sm">
        <div class="top-header text-center py-3">
            <h1 class="m-0 fw-bold">GLAD2GLOW</h1>
        </div>

        <!-- Navbar Navigasi -->
        <nav class="navbar navbar-expand-lg navbar-light navigation-bar">
            <div class="container-fluid px-lg-5">
                <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
                    <ul class="navbar-nav gap-3">
                        <li class="nav-item">
                            <!-- Link jangkar ke bagian-bagian halaman -->
                            <a class="nav-link fw-semibold" href="#all-produk">All Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="#lips">Lips</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="#powder">Powder</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="#facewash">Face Wash</a>
                        </li>
                    </ul>
                </div>

                <!-- Bagian Kanan Navbar: Search, Cart, Profile -->
                <div class="nav-icons d-flex gap-4">
                    <!-- Form Pencarian -->
                    <form class="d-flex" method="GET">
                        <input
                            type="text"
                            name="keyword"
                            class="form-control form-control-sm me-2"
                            placeholder="Cari produk...">
                        <button class="btn btn-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <!-- Icon Keranjang -->
                    <a href="keranjang_pengunjung.php" class="text-decoration-none position-relative">
                        <i class="fa-solid fa-cart-shopping"></i>

                        <!-- Badge Jumlah Item -->
                        <?php if ($totalItem > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $totalItem; ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- Dropdown Profile User -->
                    <div class="dropdown">
                        <a class="text-decoration-none dropdown-toggle d-flex align-items-center gap-2"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="fa-solid fa-circle-user fs-4 text-pink"></i>
                            <span class="fw-semibold text-pink">
                                <?= htmlspecialchars($dataUser['nama']); ?>
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow" style="width:260px;">
                            <!-- Header Dropdown -->
                            <li class="px-3 py-3 text-center" style="background:#0d6efd; color:white;">
                                <i class="fa-solid fa-circle-user fa-3x mb-2"></i>
                                <h6 class="mb-0"><?= htmlspecialchars($dataUser['nama']); ?></h6>
                                <small>Pengunjung</small>
                            </li>

                            <!-- Menu Profile -->
                            <li>
                                <a class="dropdown-item" href="profile_pengunjung.php">
                                    <i class="fa-solid fa-user me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="logout_pengunjung.php">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Sign out
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </nav>
    </header>

    <!-- Carousel Banner Promo -->
    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/img/img2.jpg" class="d-block w-100 img-fluid banner-img" alt="Promo 1">
            </div>
            <div class="carousel-item">
                <img src="assets/img/img1.jpg" class="d-block w-100 img-fluid banner-img" alt="Promo 2">
            </div>
        </div>

        <button class="carousel-control-prev custom-control" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon p-4" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next custom-control" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon p-4" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <br>

    <div class="container my-5">

        <!-- ============================================================
             SECTION: ALL PRODUK (ATAU HASIL SEARCH)
             ============================================================ -->
        <div class="container my-5" id="all-produk">
            <h3 class="section-title"><?= $judulProduk ?></h3>

            <div class="produk-area">
                <div class="row justify-content-center">

                    <?php if (empty($produk)): ?>
                        <p class="text-center text-muted">
                            Produk tidak ditemukan 😢
                        </p>
                    <?php endif; ?>

                    <?php foreach ($produk as $p): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card produk-card text-center">
                                <img src="assets/img/<?= $p['gambar']; ?>" alt="<?= $p['name']; ?>">
                                <div class="card-body">
                                    <h5><?= $p['name']; ?></h5>
                                    <p class="harga">
                                        Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                    </p>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <!-- Tombol Tambah ke Keranjang -->
                                        <a href="tambah_data_pengunjung.php?id=<?= $p['id']; ?>"
                                            class="btn btn-pink btn-sm">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>
                                        <small class="text-muted fw-bold">Stok: <?= $p['stok']; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- ============================================================
             SECTION: FEATURED VIDEO 1 (Lip Serum)
             ============================================================ -->
        <div class="section mb-5">
            <div class="row align-items-center">
                <!-- Video Player -->
                <div class="col-md-7">
                    <video width="100%" controls style="border-radius:20px;">
                        <source src="assets/vidio/vidio1.mp4" type="video/mp4">
                    </video>
                </div>

                <!-- Deskripsi Produk Video -->
                <div class="col-md-5">
                    <div class="card card-custom p-4">
                        <h4 class="pink-text fw-bold">Lip Serum</h4>
                        <p class="mt-2">
                            melembapkan, menutrisi, dan mencerahkan bibir, mengatasi bibir kering dan pecah-pecah, serta membuatnya lebih lembut, sehat, kenyal,
                            dan berkilau alami, berkat kandungan seperti Vitamin E, Squalane, dan Shea Butter yang menghidrasi mendalam
                        </p>
                        <h5 class="pink-text fw-bold">Rp 55.000</h5>
                        <a href="#lips" class="btn btn-pink mt-3">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
             SECTION: KATEGORI LIPS
             ============================================================ -->
        <h3 class="section-title" id="lips">✨ Get Your Favorite Lips ✨</h3>

        <div class="produk-area">
            <div class="row justify-content-center">
                <?php if (empty($lip)): ?>
                    <p class="text-center text-muted">Produk Lips tidak ditemukan</p>
                <?php else: ?>
                    <?php foreach ($lip as $l): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card produk-card text-center">
                                <img src="assets/img/<?= $l['gambar']; ?>">
                                <div class="card-body">
                                    <h5><?= $l['name']; ?></h5>
                                    <p class="harga">Rp <?= number_format($l['harga'], 0, ',', '.'); ?></p>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="tambah_data_pengunjung.php?id=<?= $l['id']; ?>" class="btn btn-pink btn-sm text-decoration-none">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>
                                        <small class="text-muted fw-bold">Stok: <?= $l['stok']; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================================
             SECTION: KATEGORI POWDER
             ============================================================ -->
        <h3 class="section-title" id=powder>✨Perfect Blurring Powder Foundation✨</h3>

        <div class="row justify-content-center">
            <?php if (empty($powder)): ?>
                <p class="text-center text-muted">Produk Powder tidak ditemukan</p>
            <?php else: ?>
                <?php foreach ($powder as $p): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card produk-card text-center">
                            <img src="assets/img/<?= $p['gambar']; ?>">
                            <div class="card-body">
                                <h6><?= $p['name']; ?></h6>
                                <p class="pink-text fw-bold">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></p>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="tambah_data_pengunjung.php?id=<?= $p['id']; ?>" class="btn btn-pink btn-sm text-decoration-none">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </a>
                                    <small class="text-muted fw-bold">Stok: <?= $p['stok']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <!-- ============================================================
             SECTION: FEATURED VIDEO 2 (Blurring Powder)
             ============================================================ -->
        <div class="section mb-5">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <video width="100%" controls style="border-radius:20px;">
                        <source src="assets/vidio/vidio2.mp4" type="video/mp4">
                    </video>
                </div>
                <div class="col-md-5">
                    <div class="card card-custom p-4">
                        <h4 class="pink-text fw-bold"> Blurring Powder</h4>
                        <p class="mt-2">
                            memberikan efek blurring instan untuk menyamarkan pori dan noda, mengontrol minyak hingga 8 jam,
                            meratakan warna kulit, serta menutrisi kulit berkat kandungan skincare seperti Vitamin C,
                            Centella Asiatica, dan Squalane, memberikan hasil akhir velvet matte yang halus dan tahan lama
                            tanpa terasa berat atau kering
                        </p>
                        <h5 class="pink-text fw-bold">Rp 59.000</h5>
                        <a href="#powder" class="btn btn-pink mt-3">Buy Now</a>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 SECTION: KATEGORI FACE WASH
                 ============================================================ -->
            <h3 class="section-title" id=facewash>✨Face Wash✨</h3>

            <div class="row justify-content-center">
                <?php if (empty($facewash)): ?>
                    <p class="text-center text-muted">Produk Face Wash tidak ditemukan</p>
                <?php else: ?>
                    <?php foreach ($facewash as $f): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card produk-card text-center">
                                <img src="assets/img/<?= $f['gambar']; ?>">
                                <div class="card-body">
                                    <h6><?= $f['name']; ?></h6>
                                    <p class="pink-text fw-bold">Rp <?= number_format($f['harga'], 0, ',', '.'); ?></p>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="tambah_data_pengunjung.php?id=<?= $f['id']; ?>" class="btn btn-pink btn-sm text-decoration-none">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>
                                        <small class="text-muted fw-bold">Stok: <?= $f['stok']; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div><!-- End of inner container -->
    </div><!-- End of outer container -->

    <!-- ============================================================
         FOOTER
         ============================================================ -->
    <footer style="background-color: #fce4ec; padding: 50px 0; margin-top: auto; width: 100%;">
        <div class="container">
            <div class="row">
                <!-- Contact Us -->
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold mb-3" style="color: #333;">Contact Us</h5>
                    <p class="mb-1" style="color: #666;">customer@glad2glow.id</p>
                    <h2 class="fw-bold" style="color: #444; font-family: 'Poppins', sans-serif;">Glad2Glow Distributor</h2>
                </div>

                <!-- About Us -->
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold mb-3" style="color: #333;">About Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none" style="color: #666;">About Us</a></li>
                        <li class="mb-2"><a href="#lips" class="text-decoration-none" style="color: #666;">Product Catalog</a></li>
                    </ul>

                    <!-- Social Media Links -->
                    <div class="d-flex gap-3 mt-3">
                        <a href="https://www.tiktok.com/@glad2glow.my" target="_blank" style="color: #333; font-size: 1.5rem;">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                        <a href="https://www.instagram.com/glad2glow/" target="_blank" style="color: #333; font-size: 1.5rem;">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>