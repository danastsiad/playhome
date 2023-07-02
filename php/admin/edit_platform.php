<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'd'; 

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit;
}

if (isset($_GET['id'])) {
    $platform_id = $_GET['id'];

    $query = "SELECT * FROM platforms WHERE ID_Platform = '$platform_id'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $platform = mysqli_fetch_assoc($result);

    if ($_POST) {
        $name = $_POST['name'];

        if (!empty($name)) {
            $query = "UPDATE platforms  SET Name = '$name' WHERE ID_Platform = '$platform_id'";
            mysqli_query($link, $query) or die(mysqli_error($link));

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
            <h1>Редактирование платформы</h1>

            <form method="post">
                <label for="name">Название:</label>
                <input type="text" name="name" value="<?php echo $platform['Name']; ?>" required>
                <button type="submit" class="btn_add">Сохранить</button>
            </form>

            <?php
                echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
            ?>

        </div>
    </div>
</div>