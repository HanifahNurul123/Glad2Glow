<?php
session_start();
include 'function.php';


if (!isset($_SESSION['id_user'])) {
    header("Location: pilihan.php");
}

// ====PROFILE===
$id_user = $_SESSION['id_user'];

$dataUser = query("SELECT * FROM user WHERE id = $id_user")[0];


// ambil data produk
if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
    $judulProduk = "Hasil pencarian: " . htmlspecialchars($_GET['keyword']);
    $produk = search_data($_GET['keyword']);
} else {
    $judulProduk = "✨ All Produk ✨";
    $produk = query("
        SELECT produk.*, kategori.name AS nama_kategori
        FROM produk
        JOIN kategori ON produk.id_kategori = kategori.id
    ");
}

// ======KERANJANGGNGG==== 
$id_user = $_SESSION['id_user'] ?? null;
$totalItem = 0;

if ($id_user !== null) {
    $dataKeranjang = query("
        SELECT SUM(detail_keranjang.kuantitas) AS total_item
FROM detail_keranjang
JOIN keranjang ON keranjang.id = detail_keranjang.keranjang_id
WHERE keranjang.user_id = $id_user
    ");

    $totalItem = $dataKeranjang[0]['total_item'] ?? 0;
}

// SEACRHHHHH
if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
    $keyword = $_GET['keyword'];

    // JIKA SEARCH → SEMUA PRODUK
    $lip       = search_data($keyword);
    $powder    = search_data($keyword);
    $facewash  = search_data($keyword);
} else {
    // JIKA TIDAK SEARCH → PER KATEGORI
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        :root {
            --pink-gelap: #880e4f;
            --pink-medium: #f8bbd0;



            --pink-terang: #fce4ec;
            --teks-pink: #ad1457;
        }

        /* Header Styles */
        .top-header {
            background-color: var(--pink-medium);
            color: var(--pink-gelap);
        }

        .navigation-bar {
            background-color: var(--pink-terang) !important;
        }

        .nav-link {
            color: var(--teks-pink) !important;
        }

        .nav-link:hover {
            color: var(--pink-gelap) !important;
        }

        .nav-icons i {
            color: var(--teks-pink);
            font-size: 1.2rem;
        }

        /* Untuk layar Desktop (Layar Lebar) */
        .banner-img {
            width: 100%;
            height: 600px;
            /* Tinggi di laptop/desktop */
            object-fit: cover;
            object-position: center;
        }

        /* Untuk layar Tablet (Lebar di bawah 992px) */
        @media (max-width: 992px) {
            .banner-img {
                height: 300px;
                /* Tinggi berkurang sedikit */
            }
        }

        /* Untuk layar HP (Lebar di bawah 576px) */
        @media (max-width: 576px) {
            .banner-img {
                height: 200px;
                /* Tinggi mengecil agar pas di layar HP */
            }
        }

        /* Custom Arrows yang Hampir Memenuhi Samping Layar */
        .custom-control {
            width: 10% !important;
            /* Area hover lebar */
            transition: background-color 0.3s ease;
        }

        .custom-control:hover {
            background-color: rgba(248, 187, 208, 0.4);
            /* Efek pink transparan */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1) sepia(1) saturate(5) hue-rotate(300deg);
            /* Membuat icon panah jadi pink/gelap */
        }

        body {
            background-color: #fff5f8;
            font-family: 'Poppins', sans-serif;
        }

        .section-title {
            text-align: center;
            color: #d63384;
            font-weight: 600;
            margin-bottom: 40px;
        }

        /* AREA PRODUK DI TENGAH */
        .produk-area {
            max-width: 1100px;
            margin: auto;
        }

        .produk-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(214, 51, 132, 0.15);
            transition: all 0.3s ease;
        }

        .produk-card img {
            height: 200px;
            object-fit: cover;
            border-radius: 20px 20px 0 0;
            object-fit: cover;

        }

        .produk-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 35px rgba(255, 105, 180, 0.35);
        }

        .harga {
            color: #d63384;
            font-weight: 600;
        }

        .btn-pink {
            background: #d63384;
            color: white;
            border-radius: 20px;
            padding: 6px 18px;
        }

        .btn-pink:hover {
            background: #b02a6b;
            transform: scale(1.05);
        }


        .section {
            max-width: 1100px;
            margin: auto;
        }

        .pink-text {
            color: #d63384;
        }

        .card-custom {
            border-radius: 20px;
            box-shadow: 0 6px 18px rgba(199, 85, 170, 0.2);
            border: none;
            transition: 0.3s;
            cursor: pointer;
        }

        .card-custom:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(199, 85, 170, 0.3);
        }
    </style>
</head>

<body>



    <header class="sticky-top shadow-sm">
        <div class="top-header text-center py-3">
            <h1 class="m-0 fw-bold">GLAD2GLOW</h1>
        </div>
        <!-- NAVBARRRRR -->
        <nav class="navbar navbar-expand-lg navbar-light navigation-bar">
            <div class="container-fluid px-lg-5">
                <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
                    <ul class="navbar-nav gap-3">
                        <li class="nav-item">
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

                <div class="nav-icons d-flex gap-4">
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


                    <a href="keranjang_pengunjung.php" class="text-decoration-none position-relative">
                        <i class="fa-solid fa-cart-shopping"></i>

                        <?php if ($totalItem > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $totalItem; ?>
                            </span>
                        <?php endif; ?>
                    </a>

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
                            <!-- HEADER PROFILE -->
                            <li class="px-3 py-3 text-center" style="background:#0d6efd; color:white;">
                                <i class="fa-solid fa-circle-user fa-3x mb-2"></i>
                                <h6 class="mb-0"><?= htmlspecialchars($dataUser['nama']); ?></h6>
                                <small>Pengunjung</small>
                            </li>

                            <!-- MENU -->
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



    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="img/img2.jpg" class="d-block w-100 img-fluid banner-img" alt="Promo 2">

            </div>

            <div class="carousel-item">
                <img src="img/img1.jpg" class="d-block w-100 img-fluid banner-img" alt="Promo 2">

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


        <!-- AREA PRODUK -->

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
                                <img src="img/<?= $p['gambar']; ?>" alt="<?= $p['name']; ?>">
                                <div class="card-body">
                                    <h5><?= $p['name']; ?></h5>
                                    <p class="harga">
                                        Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                    </p>
                                    <a href="tambah_data_pengunjung.php?id=<?= $p['id']; ?>"
                                        class="btn btn-pink btn-sm">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>



        <!-- <div class="container my-5"> -->
        <!-- ===== BAGIAN VIDEO + DESKRIPSI ===== -->
        <div class="section mb-5">
            <div class="row align-items-center">

                <!-- VIDEO -->
                <div class="col-md-7">
                    <video width="100%" controls style="border-radius:20px;">
                        <source src="vidio/vidio1.mp4" type="video/mp4">
                    </video>
                </div>

                <!-- DESKRIPSI + BUTTON -->
                <div class="col-md-5">
                    <div class="card card-custom p-4">
                        <h4 class="pink-text fw-bold">lippp</h4>
                        <p class="mt-2">
                            Lip tint dengan tekstur lembut, ringan,
                            tahan lama dan bikin bibir terlihat fresh
                            sepanjang hari.
                        </p>

                        <h5 class="pink-text fw-bold">Rp 59.000</h5>

                        <a href="#lips" class="btn btn-pink mt-3">
                            Buy Now
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <h3 class="section-title" id="lips">✨ Get Your Favorite Lips ✨</h3>

        <div class="produk-area">
            <div class="row justify-content-center">


                <!-- PRODUK 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card produk-card text-center">
                        <img src="img/lip1.jpeg">
                        <div class="card-body">
                            <h5>Lip Serum-Peach Pie</h5>
                            <p class="harga">Rp 45.000</p>
                            <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                        </div>
                    </div>
                </div>

                <!-- PRODUK 2 -->
                <div class="col-md-4 mb-4">
                    <div class="card produk-card text-center">
                        <img src="img/lip2.jpeg">
                        <div class="card-body">
                            <h5>Lip Serum-Strawberry Glaze</h5>
                            <p class="harga">Rp 55.000</p>
                            <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                        </div>
                    </div>
                </div>

                <!-- PRODUK 3 -->
                <div class="col-md-4 mb-4">
                    <div class="card produk-card text-center">
                        <img src="img/lip3.jpeg">
                        <div class="card-body">
                            <h5>Lip Serum-Berry Soda</h5>
                            <p class="harga">Rp 60.000</p>
                            <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <h3 class="section-title" id=powder>✨Perfect Blurring Powder Foundation✨</h3>


    <div class="row justify-content-center">

        <div class="col-md-3 mb-4">
            <div class="card produk-card text-center">
                <img src="img/powder0.jpeg">
                <div class="card-body">
                    <h6>Blurring Powder 00</h6>
                    <p class="pink-text fw-bold">Rp 59.000</p>
                    <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card produk-card text-center">
                <img src="img/powder1.jpeg">
                <div class="card-body">
                    <h6>Blurring Powder 01</h6>
                    <p class="pink-text fw-bold">Rp 59.000</p>
                    <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card produk-card text-center">
                <img src="img/powder2.jpeg">
                <div class="card-body">
                    <h6>Blurring Powder 02</h6>
                    <p class="pink-text fw-bold">Rp 59.0000</p>
                    <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                </div>
            </div>
        </div>

    </div>

    <div class="row justify-content-center">

        <div class="col-md-3 mb-4">
            <div class="card produk-card text-center">
                <img src="img/powder3.jpeg">
                <div class="card-body">
                    <h6>Blurring Powder 03</h6>
                    <p class="pink-text fw-bold">Rp 59.000</p>
                    <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card produk-card text-center">
                <img src="img/powder4.jpeg">
                <div class="card-body">
                    <h6>Blurring Powder 04</h6>
                    <p class="pink-text fw-bold">Rp 59.000</p>
                    <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card produk-card text-center">
                <img src="img/powder5.jpeg">
                <div class="card-body">
                    <h6>Blurring Powder 05</h6>
                    <p class="pink-text fw-bold">Rp 59.000</p>
                    <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                </div>
            </div>
        </div>
    </div>


    <!-- ===== BAGIAN VIDEO + DESKRIPSI ===== -->
    <div class="section mb-5">
        <div class="row align-items-center">

            <!-- VIDEO -->
            <div class="col-md-7">
                <video width="100%" controls style="border-radius:20px;">
                    <source src="vidio/vidio2.mp4" type="video/mp4">
                </video>
            </div>

            <!-- DESKRIPSI + BUTTON -->
            <div class="col-md-5">
                <div class="card card-custom p-4">
                    <h4 class="pink-text fw-bold">lippp</h4>
                    <p class="mt-2">
                        Lip tint dengan tekstur lembut, ringan,
                        tahan lama dan bikin bibir terlihat fresh
                        sepanjang hari.
                    </p>

                    <h5 class="pink-text fw-bold">Rp 59.000</h5>

                    <a href="#lips" class="btn btn-pink mt-3">
                        Buy Now
                    </a>
                </div>
            </div>

        </div>

        <h3 class="section-title" id=facewash>✨Face Wash✨</h3>


        <div class="row justify-content-center">

            <div class="col-md-3 mb-4">
                <div class="card produk-card text-center">
                    <img src="img/cumuk1.jpeg">
                    <div class="card-body">
                        <h6>Low PH Gel Cleanser</h6>
                        <p class="pink-text fw-bold">Rp 59.000</p>
                        <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card produk-card text-center">
                    <img src="img/cumuk2.jpeg">
                    <div class="card-body">
                        <h6>Blackhead Cleanser</h6>
                        <p class="pink-text fw-bold">Rp 59.000</p>
                        <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card produk-card text-center">
                    <img src="img/cumuk3.jpeg">
                    <div class="card-body">
                        <h6>Sensitive Calming Cleanser</h6>
                        <p class="pink-text fw-bold">Rp 59.0000</p>
                        <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card produk-card text-center">
                    <img src="img/cumuk4.jpeg">
                    <div class="card-body">
                        <h6>Acne Gel Cleanser</h6>
                        <p class="pink-text fw-bold">Rp 59.0000</p>
                        <button class="btn btn-pink btn-sm"><a href="tambah_data_pengunjung.php?id=1" class="text-decoration-none"><i class="fa-solid fa-cart-shopping"></i></a></button>
                    </div>
                </div>
            </div>

        </div>




        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>