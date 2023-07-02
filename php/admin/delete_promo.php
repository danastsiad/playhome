<?php
session_start();
include_once('../config/db.php');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0)  {
    header("Location: ../avt.php");
    exit;
}

if (isset($_GET['id'])) {
    $promoId = $_GET['id'];

    $query = "DELETE FROM players WHERE ID_Player='$promoId'";
    mysqli_query($link, $query) or die(mysqli_error($link));

    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'f'; 
    header("Location: ../admin/ad.php?tab={$tab}");
    exit();
}
?>