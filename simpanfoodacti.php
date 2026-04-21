<?php
session_start();
include 'koneksi.php';

if (isset($_POST['simpan_makan'])) {
    $user_id = $_SESSION['user_id']; // Pastikan sudah login
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $gram    = $_POST['gram'];
    $kalori  = $_POST['kalori'];
    $protein = $_POST['protein'];
    $tanggal = $_POST['tanggal']; // '2026-04-20' atau '2026-04-19'

    $query = "INSERT INTO food_logs (user_id, nama_makanan, jumlah_gram, kalori, protein, tanggal) 
              VALUES ('$user_id', '$nama', '$gram', '$kalori', '$protein', '$tanggal')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php?page=dashboard&msg=success");
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>