<?php
// ===============================
// KONEKSI DATABASE
// ===============================
$conn = mysqli_connect("localhost", "root", "", "glad2glow");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// ===============================
// FUNCTION QUERY
// ===============================
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// ===============================
// LOGIN (ADMIN / USER)
// ===============================
function login($username, $password, $role) {
    global $conn;

    if ($role == 'admin') {
        $table = 'admin';
        $field = 'username';
    } else {
        $table = 'user';
        $field = 'email';
    }

    $query = mysqli_query($conn,
        "SELECT * FROM $table 
         WHERE $field='$username' AND password='$password'"
    );

    return mysqli_num_rows($query);
}

// ===============================
// CRUD KATEGORI (ADMIN)
// ===============================
function tambahKategori($nama, $deskripsi) {
    global $conn;
    mysqli_query($conn,
        "INSERT INTO kategori VALUES(NULL, '$nama', '$deskripsi')"
    );
}

function hapusKategori($id) {
    global $conn;
    mysqli_query($conn,
        "DELETE FROM kategori WHERE id_kategori='$id'"
    );
}

// ===============================
// CRUD PRODUK (ADMIN)
// ===============================
function tambahProduk($data, $gambar) {
    global $conn;

    $nama       = $data['nama_produk'];
    $kategori   = $data['id_kategori'];
    $harga      = $data['harga'];
    $stok       = $data['stok'];
    $deskripsi  = $data['deskripsi'];

    $namaGambar = $gambar['name'];
    $tmp        = $gambar['tmp_name'];

    move_uploaded_file($tmp, "../img/" . $namaGambar);

    mysqli_query($conn,
        "INSERT INTO produk 
        VALUES(NULL, '$kategori', '$nama', '$harga', '$stok', '$namaGambar', '$deskripsi')"
    );
}

// ===============================
// KERANJANG (CUSTOMER)
// ===============================
function tambahKeranjang($id_user) {
    global $conn;
    mysqli_query($conn,
        "INSERT INTO keranjang VALUES(NULL, '$id_user', NOW())"
    );

    return mysqli_insert_id($conn);
}

function tambahDetailKeranjang($id_keranjang, $id_produk, $jumlah, $subtotal) {
    global $conn;
    mysqli_query($conn,
        "INSERT INTO detail_keranjang 
        VALUES(NULL, '$id_keranjang', '$id_produk', '$jumlah', '$subtotal')"
    );
}

// ===============================
// CHECKOUT & PESANAN
// ===============================
function buatPesanan($id_user, $total) {
    global $conn;

    mysqli_query($conn,
        "INSERT INTO pesanan 
        VALUES(NULL, '$id_user', NOW(), '$total', 'Diproses')"
    );

    return mysqli_insert_id($conn);
}

function detailPesanan($id_pesanan, $id_produk, $jumlah, $subtotal) {
    global $conn;

    mysqli_query($conn,
        "INSERT INTO detail_pesanan 
        VALUES(NULL, '$id_pesanan', '$id_produk', '$jumlah', '$subtotal')"
    );

    mysqli_query($conn,
        "UPDATE produk 
         SET stok = stok - $jumlah 
         WHERE id_produk='$id_produk'"
    );
}


// fungsi untuk mencari data
function search_data($keyword){
    global $conn;
    $keyword = mysqli_real_escape_string($conn, $keyword);

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


// =============ADMIN=========




?>
