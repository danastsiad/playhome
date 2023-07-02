<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'f';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit();
}



if (isset($_GET['id'])) {
    $promoId = $_GET['id'];

    $promoQuery = mysqli_query($link, "SELECT * FROM players WHERE ID_Player = '$promoId'") or die(mysqli_error($link));
    $promo = mysqli_fetch_assoc($promoQuery);

    if ($promo) {
        $gameResult = mysqli_query($link, "SELECT * FROM games");

        $promoUserId = $promo['ID_User'];
                $userQuery = mysqli_query($link, "SELECT Login FROM users WHERE ID_User = '$promoUserId' ");
                $user = mysqli_fetch_assoc($userQuery);
                $userName = $user['Login'];

        if ($_POST) {
            $login = $_POST['login'];
            $game = $_POST['game'];
            $promocode = $_POST['promocode'];
            mysqli_query($link, "UPDATE players SET ID_Game = '$game',  Promocode = '$promocode'  WHERE ID_Player = '$promoId'") or die(mysqli_error($link));

            header("Location: ../admin/ad.php?tab={$tab}");
            exit();
        }
    }
}
?>

<link rel="stylesheet" href="/playhome/css/style.css">
<div class="container">
    <div class="add-edit_form">
        <div class="add-edit_form-content">
            <h1>Редактирование промокода</h1>

            <form method="post">
                <label for="login">Логин:</label>
                <input type="text" name="login" value="<?php echo $userName; ?>" readonly required><br>

                <label for="game">Игра:</label>
                <select name="game" required>
                        <?php while ($game = mysqli_fetch_assoc($gameResult)) : ?>
                            <option value="<?php echo $game['ID_Game']; ?>" <?php if ($game['ID_Game'] == $promo['ID_Game']) echo 'selected'; ?>><?php echo $game['Name']; ?></option>
                        <?php endwhile; ?>
                </select><br>
                
                <label for="promocode">Промокод:</label>
                <input type="text" name="promocode" value="<?php echo $promo['Promocode']; ?>" required><br>

                <button type="submit" class="btn_add">Сохранить</button>
            </form>

            <?php
                echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
            ?>

        </div>
    </div>
</div>