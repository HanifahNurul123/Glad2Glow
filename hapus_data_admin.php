<?php
include 'function.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM produk WHERE id=$id");

header("Location: index_admin.php");