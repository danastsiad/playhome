<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'e';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit();
}

if ($_POST) {
    $login = $_POST['login'];
    $password = md5($_POST['password']);
    $email = $_POST['email'];
    
    // Проверка на существование логина
    $checkLoginQuery = mysqli_query($link, "SELECT * FROM users WHERE Login='$login'");
    if (mysqli_num_rows($checkLoginQuery) > 0) {
        $errorMessage = 'Данный пользователь уже существует';
    } else {
        // Проверка на существование почты
        $checkEmailQuery = mysqli_query($link, "SELECT * FROM users WHERE Email='$email'");
        if (mysqli_num_rows($checkEmailQuery) > 0) {
            $errorMessage = 'Данный пользователь уже существует';
        } else {
            $query = "INSERT INTO users(Login, Password, Email) VALUES ('$login', '$password', '$email')";
            mysqli_query($link, $query) or die(mysqli_error($link));

            function generatePromoCode()
            {
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $promoCode = '';
                for ($i = 0; $i < 16; $i++) {
                    $index = mt_rand(0, strlen($characters) - 1);
                    $promoCode .= $characters[$index];
                }
                return $promoCode;
            }

            // Получение идентификатора только что зарегистрированного пользователя
            $userId = mysqli_insert_id($link);

            // Получение списка игр
            $gamesQuery = mysqli_query($link, "SELECT * FROM games");
            while ($game = mysqli_fetch_assoc($gamesQuery)) {
                $gameId = $game['ID_Game'];
                $promoCode = generatePromoCode();
                mysqli_query($link, "INSERT INTO players(ID_User, ID_Game, Promocode) VALUES ('$userId', '$gameId', '$promoCode')") or die(mysqli_error($link));
            }

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
            <h1>Добавить пользователя</h1>

            <form method="POST">
                <label for="login">Логин:</label>
                <input type="text" name="login" required><br>

                <label for="email">Почта:</label>
                <input type="email" name="email" required><br>

                <label for="password">Пароль:</label>
                <input type="password" name="password"><br>
                <?php
                if (isset($errorMessage)) {
                    echo '<div class="error-message">' . $errorMessage . '</div>';
                }
                ?>
                <button type="submit" class="btn_add">Добавить</button>
            </form>

            <?php
                echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
            ?>

        </div>
    </div>
</div>