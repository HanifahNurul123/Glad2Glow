<?php
// ============================================================
// KONEKSI DATABASE
// ============================================================
// Menghubungkan aplikasi ke database MySQL "glad2glow"
// Host: localhost, User: root, Password: (kosong)
$conn = mysqli_connect("localhost", "root", "", "glad2glow");

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// ============================================================
// FUNCTION QUERY (Mengambil Data)
// ============================================================
// Fungsi serbaguna untuk mengeksekusi query SQL dan mengembalikan hasilnya dalam bentuk array
function query($query)
{
    global $conn; // Menggunakan variabel koneksi global
    $result = mysqli_query($conn, $query); // Eksekusi query
    $rows = []; // Siapkan array kosong untuk menampung data

    // Ambil setiap baris data sebagai array asosiatif
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows; // Kembalikan array data
}

// ============================================================
// LOGIN (ADMIN / USER)
// ============================================================
// Fungsi untuk mengecek kredensial login (username/email dan password)
// $role bisa 'admin' atau 'user'
function login($username, $password, $role)
{
    global $conn;

    // Tentukan tabel dan kolom berdasarkan role yang login
    if ($role == 'admin') {
        $table = 'admin';
        $field = 'username';
    } else {
        $table = 'user';
        $field = 'email';
    }

    // Query cek user di database
    // Catatan: Sebaiknya gunakan prepared statement untuk mencegah SQL Injection
    $query = mysqli_query(
        $conn,
        "SELECT * FROM $table 
         WHERE $field='$username' AND password='$password'"
    );

    // Kembalikan jumlah baris yang ditemukan (1 jika sukses, 0 jika gagal)
    return mysqli_num_rows($query);
}

// ============================================================
// CRUD KATEGORI (ADMIN)
// ============================================================
// Fungsi untuk menambah kategori baru ke database
function tambahKategori($nama, $deskripsi)
{
    global $conn;
    mysqli_query(
        $conn,
        "INSERT INTO kategori VALUES(NULL, '$nama', '$deskripsi')"
    );
}

// Fungsi untuk menghapus kategori berdasarkan ID
function hapusKategori($id)
{
    global $conn;
    mysqli_query(
        $conn,
        "DELETE FROM kategori WHERE id_kategori='$id'"
    );
}

// ============================================================
// CRUD PRODUK (ADMIN)
// ============================================================
// Fungsi untuk menambah produk baru beserta upload gambarnya
function tambahProduk($data, $gambar)
{
    global $conn;

    // Ambil data dari array $data (biasanya dari $_POST)
    $nama       = $data['nama_produk'];
    $kategori   = $data['id_kategori'];
    $harga      = $data['harga'];
    $stok       = $data['stok'];
    $deskripsi  = $data['deskripsi'];

    // Ambil detail file gambar
    $namaGambar = $gambar['name'];
    $tmp        = $gambar['tmp_name'];

    // Pindahkan file gambar ke folder assets/img
    move_uploaded_file($tmp, "../assets/img/" . $namaGambar);

    // Masukkan data produk ke database
    mysqli_query(
        $conn,
        "INSERT INTO produk 
        VALUES(NULL, '$kategori', '$nama', '$harga', '$stok', '$namaGambar', '$deskripsi')"
    );
}

// ============================================================
// KERANJANG (CUSTOMER)
// ============================================================
// Fungsi untuk membuat keranjang baru untuk user
function tambahKeranjang($id_user)
{
    global $conn;
    // Buat keranjang baru dengan timestamp sekarang
    mysqli_query(
        $conn,
        "INSERT INTO keranjang VALUES(NULL, '$id_user', NOW())"
    );

    // Kembalikan ID keranjang yang baru dibuat
    return mysqli_insert_id($conn);
}

// Fungsi untuk menambahkan detail item ke dalam keranjang
function tambahDetailKeranjang($id_keranjang, $id_produk, $jumlah, $subtotal)
{
    global $conn;
    mysqli_query(
        $conn,
        "INSERT INTO detail_keranjang 
        VALUES(NULL, '$id_keranjang', '$id_produk', '$jumlah', '$subtotal')"
    );
}

// ============================================================
// CHECKOUT & PESANAN
// ============================================================
// Fungsi untuk membuat record pesanan baru
function buatPesanan($id_user, $total)
{
    global $conn;

    // Default status pesanan: 'Diproses'
    mysqli_query(
        $conn,
        "INSERT INTO pesanan 
        VALUES(NULL, '$id_user', NOW(), '$total', 'Diproses')"
    );

    // Kembalikan ID pesanan baru
    return mysqli_insert_id($conn);
}

// Fungsi untuk memasukkan detail produk yang dipesan
function detailPesanan($id_pesanan, $id_produk, $jumlah, $subtotal)
{
    global $conn;

    // Masukkan detail ke tabel detail_pesanan
    mysqli_query(
        $conn,
        "INSERT INTO detail_pesanan 
        VALUES(NULL, '$id_pesanan', '$id_produk', '$jumlah', '$subtotal')"
    );

    // Kurangi stok produk di tabel produk
    mysqli_query(
        $conn,
        "UPDATE produk 
         SET stok = stok - $jumlah 
         WHERE id_produk='$id_produk'"
    );
}


// ============================================================
// SEARCH DATA
// ============================================================
// Fungsi untuk mencari produk berdasarkan keyword nama produk atau kategori
function search_data($keyword)
{
    global $conn;
    // Bersihkan keyword dari karakter berbahaya
    $keyword = mysqli_real_escape_string($conn, $keyword);

    // Query pencarian dengan JOIN table kategori
    $query = "
        SELECT produk.*, kategori.name AS nama_kategori
        FROM produk
        JOIN kategori ON produk.id_kategori = kategori.id
        WHERE 
            produk.name LIKE '%$keyword%'
            OR kategori.name LIKE '%$keyword%'
    ";

    return query($query);
}
