# 🌸 Glad2Glow Distributor Web Application

Selamat datang di repositori Aplikasi Web Distributor Glad2Glow. Aplikasi ini dirancang untuk memudahkan manajemen produk bagi administrator dan memberikan pengalaman belanja yang menyenangkan bagi pelanggan.

---

## 📖 Tentang Aplikasi

Aplikasi ini adalah platform E-Commerce sederhana yang dibangun menggunakan **PHP Native** dan MySQL. Aplikasi ini memiliki dua peran pengguna utama:
1.  **Administrator**: Mengelola data master produk (Tambah, Edit, Hapus).
2.  **Pengunjung (Customer)**: Melihat katalog, mencari produk, menambahkan ke keranjang, dan melakukan pemesanan (Checkout).

## 🚀 Fitur Utama

### 🛡️ Admin Portal
*   **Authentication**: Login aman dengan enkripsi password (Hashing).
*   **Dashboard**: Melihat daftar semua produk secara real-time.
*   **Manajemen Produk**:
    *   **Tambah Produk**: Menambahkan produk baru dengan gambar, harga, stok, dan kategori via Modal.
    *   **Edit Produk**: Mengubah informasi produk yang sudah ada.
    *   **Hapus Produk**: Menghapus produk dari sistem.
*   **Manajemen Aset**: File gambar produk disimpan terpusat di folder `assets/img`.

### 🛍️ Customer Portal
*   **Katalog Produk**: Tampilan produk yang menarik dengan filter kategori (Lips, Powder, Face Wash).
*   **Pencarian**: Fitur pencarian produk berdasarkan nama atau kategori.
*   **Keranjang Belanja**: Menambahkan produk ke keranjang, mengubah jumlah, dan memilih item untuk di-checkout.
*   **Checkout System**: Memproses pesanan yang dipilih dari keranjang.
*   **User Profile**: Update data diri (Nama, Email, Password).
*   **Multimedia**: Banner promo carousel dan video produk embed.

---

## 🛠️ Teknologi yang Digunakan

*   **Backend**: PHP (Native)
*   **Database**: MySQL / MariaDB
*   **Frontend**: 
    *   HTML5 & CSS3
    *   Bootstrap 5 (Framework CSS)
    *   FontAwesome (Icons)
    *   SweetAlert2 (Notifikasi JavaScript)
    *   Google Fonts (Poppins)

---

## 📂 Struktur Folder Project

```text
project_akhir/
├── assets/                 # Folder penyimpanan aset statis
│   ├── bootstrap/          # File CSS & JS Bootstrap lokal
│   ├── css/                # File CSS kustom (style.css, login.css, dll)
│   ├── img/                # Gambar produk dan aset UI
│   └── vidio/              # Video promosi produk
├── function.php            # File koneksi database & fungsi helper global
├── index_admin.php         # Halaman utama dashboard Admin
├── index_pengunjung.php    # Halaman utama toko (Storefront)
├── login_admin.php         # Halaman login Admin
├── login_pengunjung.php    # Halaman login & register Customer
├── keranjang_pengunjung.php# Halaman keranjang belanja
├── checkout_process.php    # Pemroses logika checkout (Backend API)
├── pilihan.php             # Halaman awal pemilihan role (Landing Page)
└── ... (file pendukung lainnya)
```

---

## ⚙️ Cara Instalasi & Menjalankan

1.  **Persiapan Lingkungan**:
    *   Pastikan Anda telah menginstal **XAMPP** atau aplikasi server lokal sejenis.
    *   Pastikan service **Apache** dan **MySQL** sudah berjalan.

2.  **Setup Folder**:
    *   Copy folder `project_akhir` ke dalam folder `htdocs` di instalasi XAMPP Anda (biasanya di `C:\xampp\htdocs\`).

3.  **Setup Database**:
    *   Buka **phpMyAdmin** (http://localhost/phpmyadmin).
    *   Buat database baru dengan nama `glad2glow`.
    *   Import file SQL database (jika ada) ke dalam database `glad2glow`. Pastikan tabel `user`, `admin`, `produk`, `kategori`, `keranjang`, `detail_keranjang`, dan `pesanan` sudah tersedia.

4.  **Akses Aplikasi**:
    *   Buka browser dan kunjungi: `http://localhost/project_akhir/pilihan.php`

---

## 🔒 Catatan Keamanan

*   Password pengguna dienkripsi menggunakan algoritma **Bcrypt** (`password_hash`).
*   Harap perbarui kredensial database di file `function.php` jika Anda menggunakan konfigurasi user/password MySQL yang berbeda dari default (`root` / kosong).

---

**Created by Glad2Glow Team** © 2026
