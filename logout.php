<?php
// Mengaktifkan session
session_start();

// Menghapus semua session
session_destroy();

// Mengalihkan halaman
header("Location:login.php");
