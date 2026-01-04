<?php
// Memulai sesi
session_start();

// Menghapus semua data session yang tersimpan (logout)
session_destroy();

// Redirect user kembali ke halaman pilihan role (pilihan.php)
header("Location: pilihan.php");
exit;
