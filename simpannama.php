<?php
session_start();

if($_SERVER ["REQUEST_METHOD"] == "POST") {
    $_SESSION['nama_user'] = $_POST['nama'];

    header("Location: welcome.php");
    exit();
}
?>