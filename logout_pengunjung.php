<?php
// Memulai sesi
session_start();

// Menghapus semua data session (logout)
session_destroy();

// Redirect user kembali ke halaman pilihan awal
header("Location: pilihan.php");
exit;
