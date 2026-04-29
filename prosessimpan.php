<?php
session_start();
$conn = null;
include 'koneksi.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah terdaftar! Silakan gunakan email lain.'); window.location='pendaftaran.php';</script>";
        exit();
    }

    $sql = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
    
    if(mysqli_query($conn, $sql)) {
    $user_id = mysqli_insert_id($conn);
    
    unset($_SESSION['tdee']);
    unset($_SESSION['bmr']);
    unset($_SESSION['goal']);
    unset($_SESSION['tema_user']); 

    $_SESSION['user_id'] = $user_id;
    $_SESSION['nama_user'] = $nama;

    header("Location: personalmatriks.php");
    exit();
}
}
?>