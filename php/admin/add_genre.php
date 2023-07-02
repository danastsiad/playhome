<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'b';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit();
}

if ($_POST) {
    $name = $_POST['name'];

    if (!empty($name)) {
        $query = "INSERT INTO genres (Name) VALUES ('$name')";
        mysqli_query($link, $query) or die(mysqli_error($link));

        header("Location: ../admin/ad.php?tab={$tab}");
        exit();
    }
}
?>

<link rel="stylesheet" href="/playhome/css/style.css">
<div class="container">
    <div class="add-edit_form">
        <div class="add-edit_form-content">
            <h1>Добавить жанр</h1>

            <form method="POST">
                <label for="name">Название:</label>
                <input type="text" name="name" required>

                <button type="submit" class="btn_add">Добавить</button>
            </form>

            <?php
            echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
            ?>

        </div>
    </div>
</div>