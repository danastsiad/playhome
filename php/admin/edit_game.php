<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'a';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit();
}

if (isset($_GET['id'])) {
    $gameId = $_GET['id'];

    $gameQuery = mysqli_query($link, "SELECT * FROM games WHERE ID_Game = '$gameId'") or die(mysqli_error($link));
    $game = mysqli_fetch_assoc($gameQuery);

    if ($game) {

        $genresQuery = mysqli_query($link, "SELECT * FROM genres");
        $developersQuery = mysqli_query($link, "SELECT * FROM developers");
        $platformsQuery = mysqli_query($link, "SELECT * FROM platforms");

        if ($_POST) {
            $name = $_POST['name'];
            $picture = $_POST['picture'];
            $description = $_POST['description'];
            $rating = $_POST['rating'];
            $genre = $_POST['genre'];
            $release_date = $_POST['release_date'];
            $developer = $_POST['developer'];
            $platform = $_POST['platform'];
            $price = $_POST['price'];
            $remainder = $_POST['remainder'];

            mysqli_query($link, "UPDATE games SET Name = '$name', Picture = '$picture', Description = '$description', Rating = '$rating', ID_Genre = '$genre', ID_Developer = '$developer', ID_Platform = '$platform', Price = '$price', Remainder = '$remainder' WHERE ID_Game = '$gameId'") or die(mysqli_error($link));

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
            <h1>Редактирование игры</h1>

            <form method="post">
                <label for="name">Название:</label>
                <input type="text" name="name" value="<?php echo $game['Name']; ?>" required><br>

                <label for="picture">Изображение:</label>
                <input type="text" name="picture" value="<?php echo $game['Picture']; ?>" required><br>

                <label for="description">Описание:</label>
                <textarea name="description" rows="5" required><?php echo $game['Description']; ?></textarea><br>

                <label for="genre">Жанр:</label>
                <select name="genre" required>
                    <?php while ($genre = mysqli_fetch_assoc($genresQuery)) : ?>
                        <option value="<?php echo $genre['ID_Genre']; ?>" <?php if ($genre['ID_Genre'] == $game['ID_Genre']) echo 'selected'; ?>><?php echo $genre['Name']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="rating">Рейтинг:</label>
                <input type="number" name="rating" min="1" max="10" step="0.1" value="<?php echo $game['Rating']; ?>" required><br>

                <label for="developer">Разработчик:</label>
                <select name="developer" required>
                    <?php while ($developer = mysqli_fetch_assoc($developersQuery)) : ?>
                        <option value="<?php echo $developer['ID_Developer']; ?>" <?php if ($developer['ID_Developer'] == $game['ID_Developer']) echo 'selected'; ?>><?php echo $developer['Name']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="release_date">Дата выпуска:</label>
                <input type="date" name="release_date" value="<?php echo $game['Release_date']; ?>" required><br>

                <label for="platform">Платформа:</label>
                <select name="platform" required>
                    <?php while ($platform = mysqli_fetch_assoc($platformsQuery)) : ?>
                        <option value="<?php echo $platform['ID_Platform']; ?>" <?php if ($platform['ID_Platform'] == $game['ID_Platform']) echo 'selected'; ?>><?php echo $platform['Name']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="price">Цена:</label>
                <input type="number" name="price" min="0" value="<?php echo $game['Price']; ?>" required><br>

                <label for="remainder">Остаток:</label>
                <input type="number" name="remainder" min="0" value="<?php echo $game['Remainder']; ?>" required><br>

                <button type="submit" class="btn_add">Сохранить</button>
            </form>
            <?php
                echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
            ?>
        </div>
    </div>
</div>