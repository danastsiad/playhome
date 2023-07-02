<?php
session_start();
include_once('../config/db.php');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0)  {
    header("Location: ../avt.php");
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $deletePlayersQuery = "DELETE FROM players WHERE ID_User='$userId'";
    mysqli_query($link, $deletePlayersQuery) or die(mysqli_error($link));

    $deleteUsersQuery = "DELETE FROM users WHERE ID_User='$userId'";
    mysqli_query($link, $deleteUsersQuery) or die(mysqli_error($link));

    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'e'; 
    header("Location: ../admin/ad.php?tab={$tab}");
    exit();
}
?>