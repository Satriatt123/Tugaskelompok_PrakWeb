<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['nama_user'] = $row['nama'];
            $_SESSION['tema_user'] = $row['jk'] ?? 'netral';
            $_SESSION['berat']     = $row['berat'] ?? 0;
            $_SESSION['tdee']      = $row['tdee'] ?? 2000;
            $_SESSION['bmr']       = $row['bmr'] ?? 1500;
            $_SESSION['goal']      = $row['goal'] ?? 'maintain';

            if (!empty($row['tdee']) && $row['tdee'] > 0) {
                header("Location: tracking.php?page=dashboard");
            } else {
                header("Location: welcome.php");
            }
            exit();

        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Email belum terdaftar!'); window.location='login.php';</script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>