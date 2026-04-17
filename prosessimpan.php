<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['nama_user'] = $_POST['nama'];
    $_SESSION['tema_user'] = $_POST['jk'];

    header("Location: welcome.php");
    exit();
}
?>