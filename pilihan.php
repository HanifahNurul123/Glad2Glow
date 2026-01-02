<?
session_start();
include 'function.php';
if(isset($_SESSION['admin'])) {
    header("Location: index_admin.php");
    exit;
}
if(isset($_SESSION['id_user'])) {
    header("Location: index_pengunjung.php");
    exit;
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Pilih Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 text-center shadow" style="width:350px">
    <h4 class="mb-4">Masuk sebagai</h4>

    <a href="login_pengunjung.php" class="btn btn-pink btn-lg mb-3">
        Pengunjung
    </a>

    <a href="login_admin.php" class="btn btn-outline-danger btn-lg">
        Admin
    </a>
</div>

</body>
</html>