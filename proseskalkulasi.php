<?php
session_start();
include 'koneksi.php'; 

$jk  = $_POST['jk'];
$u   = $_POST['usia'];
$t   = $_POST['tinggi'];
$b   = $_POST['berat'];
$act = $_POST['activity_level'];

if ($jk == "Laki-Laki") {
    $bmr = (10 * $b) + (6.25 * $t) - (5 * $u) + 5;
} else {
    $bmr = (10 * $b) + (6.25 * $t) - (5 * $u) - 161;
}

$tdee = $bmr * $act;

$_SESSION['tema_user'] = $jk;
$_SESSION['tdee']      = round($tdee);
$_SESSION['bmr']       = round($bmr);
$_SESSION['berat']     = $b;

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $tdee_round = round($tdee);
    $bmr_round = round($bmr);
    
    $query = "UPDATE users SET jk='$jk', usia=$u, tinggi=$t, berat=$b, activity_level=$act, bmr=$bmr_round, tdee=$tdee_round WHERE id='$user_id'";
    mysqli_query($conn, $query);
}

header("Location: goal.php");
exit();
?>