<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'e'; 

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $userQuery = mysqli_query($link, "SELECT * FROM users WHERE ID_User = '$userId'") or die(mysqli_error($link));
    $user = mysqli_fetch_assoc($userQuery);

    if ($_POST) {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $status = $_POST['status'];

        mysqli_query($link, "UPDATE users SET Login = '$login', Email = '$email', Status = '$status' WHERE ID_User = '$userId'") or die(mysqli_error($link));

        header("Location: ../admin/ad.php?tab={$tab}");
        exit();
    }
}
    
?>

<link rel="stylesheet" href="/playhome/css/style.css">
    <div class="container">
        <div class="add-edit_form">
            <div class="add-edit_form-content">
                <h1>Редактирование пользователей</h1>

                <form method="post">
                    <label for="login">Логин:</label>
                    <input type="text" name="login" value="<?php echo $user['Login']; ?>" required><br>

                    <label for="email">Почта:</label>
                    <input type="text" name="email" value="<?php echo $user['Email']; ?>" required><br>
                    
                    <label for="status">Статус:</label>
                    <input type="text" name="status" value="<?php echo $user['Status']; ?>" required><br>

                    <button type="submit" class="btn_add">Сохранить</button>
                </form>

                <?php
                    echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
                ?>
        </div>
    </div>
</div>

