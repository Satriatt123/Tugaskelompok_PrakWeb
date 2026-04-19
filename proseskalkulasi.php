<?php
session_start();

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

header("Location: goal.php");
exit();