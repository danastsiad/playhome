<?php
session_start();
include 'header.php';
include('config/db.php');

if (!isset($_SESSION['username'])) {

    if (!empty($_POST['avt'])) {
        $login = $_POST['login'];
        $pass = md5($_POST['pass']);
        $result = mysqli_query($link, "SELECT * FROM users WHERE Login='$login' AND Password='$pass' ") or die(mysqli_error($link));

        if (mysqli_num_rows($result) > 0) {
            foreach ($result as $row) {
                $_SESSION['username'] = $row['Login'];
                $_SESSION['id'] = $row['ID_User'];
                $_SESSION['admin'] = $row['Status'];

                $usedsdd = $_SESSION['id'];

                $playersQuery = mysqli_query($link, "SELECT players.*, games.Name FROM players INNER JOIN games ON players.ID_Game = games.ID_Game WHERE ID_User = '$usedsdd'");
                if (mysqli_num_rows($playersQuery) > 0) {
                    require_once('components/user.php');
                } else {
                }
                if ($_SESSION['admin'] > 0) {
                    header("Location: admin/ad.php");
                }
            }
        } else {
            require_once('components/loginCheck.php');
        }
    } else {
        require_once('components/login.php');
    }
} else {

    $usedsdd = $_SESSION['id'];

    $playersQuery = mysqli_query($link, "SELECT players.*, games.Name FROM players INNER JOIN games ON players.ID_Game = games.ID_Game WHERE ID_User = '$usedsdd'");
    require_once('components/user.php');
    if ($_SESSION['admin'] > 0) {
        header("Location: ./admin/ad.php");
    }
}
require_once("footer.php"); ?>

<script src="/playhome/js/script.js"></script>