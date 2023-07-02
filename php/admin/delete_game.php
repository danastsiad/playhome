<?php
session_start();
include_once('../config/db.php');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0)  {
    header("Location: ../avt.php");
    exit;
}

if (isset($_GET['id'])) {
    $gameId = $_GET['id'];

    $deletePlayersQuery = "DELETE FROM players WHERE ID_Game='$gameId'";
    mysqli_query($link, $deletePlayersQuery) or die(mysqli_error($link));

    $deleteGameQuery = "DELETE FROM games WHERE ID_Game='$gameId'";
    mysqli_query($link, $deleteGameQuery) or die(mysqli_error($link));

    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'a'; 
    header("Location: ../admin/ad.php?tab={$tab}");
    exit();
}
