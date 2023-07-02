<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'f';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit();
}

if ($_POST) {
    $login = $_POST['login'];
    $game = $_POST['game'];
    $promo = $_POST['promo'];

    $userQuery = mysqli_query($link, "SELECT * FROM users WHERE Login='$login'");
    if (mysqli_num_rows($userQuery) > 0) {
        $user = mysqli_fetch_assoc($userQuery);
        $userId = $user['ID_User'];
        $query = "INSERT INTO players (ID_User, ID_Game, Promocode)
                    VALUES ('$userId', '$game', '$promo')";
        mysqli_query($link, $query) or die(mysqli_error($link));

        header("Location: ../admin/ad.php?tab={$tab}");
        exit();
    }
}

$gameResult = mysqli_query($link, "SELECT * FROM games") or die(mysqli_error($link));

?>
<link rel="stylesheet" href="/playhome/css/style.css">
<div class="container">
    <div class="add-edit_form">
        <div class="add-edit_form-content">
            <h1>Добавить промокод</h1>

            <form method="POST">
                <label for="login">Логин:</label>
                <input type="text" name="login" required><br>

                <label for="game">Игра:</label>
                <select name="game" required>
                    <?php while ($game = mysqli_fetch_assoc($gameResult)) : ?>
                        <option value="<?php echo $game['ID_Game']; ?>"><?php echo $game['Name']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="promo">Промокод:</label>
                <input type="text" name="promo" required><br>

                <button type="submit" class="btn_add">Добавить</button>
            </form>

            <?php
                echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
            ?>

        </div>
    </div>
</div>