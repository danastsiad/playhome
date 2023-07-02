<?php
session_start();
include_once('../config/db.php');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0)  {
    header("Location: ../avt.php");
    exit;
}

if (isset($_GET['id'])) {
    $platformId = $_GET['id'];

    $query = "DELETE FROM platforms WHERE ID_Platform='$platformId'";
    mysqli_query($link, $query) or die(mysqli_error($link));

    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'd'; 
    header("Location: ../admin/ad.php?tab={$tab}");
    exit();
}
?>