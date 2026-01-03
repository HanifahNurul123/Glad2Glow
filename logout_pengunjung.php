<?php
session_start();
session_destroy();

header("Location: pilihan.php");
exit;
