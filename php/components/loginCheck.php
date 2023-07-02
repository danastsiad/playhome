<div class="signed">
    <div class="signed-left"></div>
    <div class="signed-right">
        <div class="signed-right-content">
            <div class="text">
                <h2 class="tit">Войти</h2>
                <p class="green-to-red">Неверный логин или пароль</p>
            </div>
            <form action="" method="post">
                <div class="">
                    <div class="">
                        <input type="text" placeholder="Логин" name="login">
                    </div>
                    <div class="input_password">
                        <input type="password" placeholder="Пароль" name="pass">
                    </div>
                </div>
                <button class="send" name="avt" type="submit" value="Вход">Вход</button>
                <p class="btn" data-modal="2"><a href="#" class="js-modal-close">Нет аккаунта? <span>Зарегистрируйтесь</span></a></p>
            </form>
            <?php
            $modalClass = ''; // Переменная для определения класса модального окна

            if (isset($_POST['reg'])) {
                $login = $_POST['login'];
                $pass = md5($_POST['pass']);
                $email = $_POST['email'];
                $symbol = '@';

                if (!empty($login) && !empty($email)) {
                    // Проверка на существование логина
                    $checkLoginQuery = mysqli_query($link, "SELECT * FROM users WHERE Login='$login'");
                    if (mysqli_num_rows($checkLoginQuery) > 0) {
                        require_once('registerCheck.php');
                    } else {
                        // Проверка на существование почты
                        $checkEmailQuery = mysqli_query($link, "SELECT * FROM users WHERE Email='$email'");
                        if (mysqli_num_rows($checkEmailQuery) > 0) {
                            require_once('registerCheck.php');
                        } else {
                            if (strpos($email, $symbol) && strlen($_POST['pass']) >= 6) {
                                mysqli_query($link, "INSERT INTO users(Login, Password, Email) VALUES ('$login', '$pass', '$email')") or die(mysqli_error($link));

                                $user_id = mysqli_insert_id($link);
                                $_SESSION['username'] = $login;
                                $_SESSION['admin'] = 0;
                                $_SESSION['id'] = $user_id;
                                echo '<br>';
                                header("Refresh: 0");

                                // Генерация промокода
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

                                $gamesQuery = mysqli_query($link, "SELECT * FROM games");
                                while ($game = mysqli_fetch_assoc($gamesQuery)) {
                                    $gameId = $game['ID_Game'];
                                    $promoCode = generatePromoCode();
                                    mysqli_query($link, "INSERT INTO players(ID_User, ID_Game, Promocode) VALUES ('$userId', '$gameId', '$promoCode')") or die(mysqli_error($link));
                                }
                            } else {
                                require_once('register.php');
                                header("Refresh: 0");
                            }
                        }
                    }
                }
            } else {
                echo '<div class="modal modal_modall' . $modalClass . '"  data-modal="2">
                        <div class="text">
                            <h2 class="tit">Регистрация</h2>
                        </div>
                        <div>
                        <form method="POST">
                            <div>
                                <input type="text" placeholder="Логин" name="login">
                            </div>
                            <div class="input_email">
                            <input name="email" type="email" id="email" placeholder="Почта" required >
                            </div>
                            <div class="input_password">
                                <input type="password" placeholder="Пароль" name="pass">
                            </div>
                        </div>
                        <button class="send" name="reg" type="submit" value="Зарегистрироваться">Зарегистрироваться</button>
                        </form>
                        <p class="btn"><a href="#" class="js-modal-close">Уже есть аккаунт? <span>Войти</span> </a></p>
                    </div>';
            }
            ?>
        </div>
    </div>
</div>
<!--Подвал-->
<?php require_once("footer.php"); ?>
<!--Конец подвала-->
</body>

</html>