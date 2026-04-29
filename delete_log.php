<?php
session_start();
include 'koneksi.php';

if(isset($_SESSION['user_id']) && isset($_GET['id']) && isset($_GET['type'])) {
    $user_id = $_SESSION['user_id'];
    $id = (int)$_GET['id'];
    $type = $_GET['type'];

    if($type === 'food') {
        $sql = "DELETE FROM food_logs WHERE id=$id AND user_id=$user_id";
        mysqli_query($conn, $sql);
    } elseif($type === 'activity') {
        $sql = "DELETE FROM activity_logs WHERE id=$id AND user_id=$user_id";
        mysqli_query($conn, $sql);
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>