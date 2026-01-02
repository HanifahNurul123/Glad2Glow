<?php
include 'function.php';

$id = $_GET['id'];
$produk = query("SELECT * FROM produk WHERE id=$id")[0];

if (isset($_POST['update'])) {
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    mysqli_query($conn, "
        UPDATE produk 
        SET harga='$harga', stok='$stok'
        WHERE id='$id'
    ");

    header("Location: index_admin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3>Restok Produk</h3>

    <form method="POST">
        <input type="text" class="form-control mb-2" value="<?= $produk['name']; ?>" disabled>
        <input type="number" name="harga" class="form-control mb-2" value="<?= $produk['harga']; ?>">
        <input type="number" name="stok" class="form-control mb-3" value="<?= $produk['stok']; ?>">

        <button name="update" class="btn btn-primary">Update</button>
        <a href="index_admin.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>