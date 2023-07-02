<?php
session_start();
include_once('../config/db.php');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'a';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] <= 0) {
    header("Location: ../avt.php");
    exit();
}

if ($_POST) {
    $name = $_POST['name'];
    $picture = $_POST['picture'];
    $description = $_POST['description'];
    $genreId = $_POST['genre'];
    $rating = $_POST['rating'];
    $developerId = $_POST['developer'];
    $releaseDate = $_POST['release_date'];
    $platformId = $_POST['platform'];
    $price = $_POST['price'];
    $remainder = $_POST['remainder'];

    $query = "INSERT INTO games (Name, Picture, Description, ID_Genre, Rating, ID_Developer, Release_date, ID_Platform, Price, Remainder)
                VALUES ('$name', '$picture', '$description', '$genreId', '$rating', '$developerId', '$releaseDate', '$platformId', '$price', '$remainder')";
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

    $gameId = mysqli_insert_id($link);

    $query = "SELECT ID_User FROM users WHERE Status < 1";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));

    while ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['ID_User'];
        $promoCode = generatePromoCode();

        $promoCodeQuery = "INSERT INTO players(ID_User, ID_Game, Promocode) VALUES ('$userId', '$gameId', '$promoCode')";
        mysqli_query($link, $promoCodeQuery) or die(mysqli_error($link));
    }

    header("Location: ../admin/ad.php?tab={$tab}");
    exit();
}

$genresResult = mysqli_query($link, "SELECT * FROM genres") or die(mysqli_error($link));
$developersResult = mysqli_query($link, "SELECT * FROM developers") or die(mysqli_error($link));
$platformsResult = mysqli_query($link, "SELECT * FROM platforms") or die(mysqli_error($link));

?>

<link rel="stylesheet" href="/playhome/css/style.css">
<div class="container">
    <div class="add-edit_form">
        <div class="add-edit_form-content">
            <h1>Добавить игру</h1>

            <form method="POST">
                <label for="name">Название:</label>
                <input type="text" name="name" required><br>

                <label for="picture">Изображение:</label>
                <input type="text" name="picture" required><br>

                <label for="description">Описание:</label>
                <textarea name="description" required></textarea><br>

                <label for="genre">Жанр:</label>
                <select name="genre" required>
                    <?php while ($genre = mysqli_fetch_assoc($genresResult)) : ?>
                        <option value="<?php echo $genre['ID_Genre']; ?>"><?php echo $genre['Name']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="rating">Рейтинг:</label>
                <input type="number" name="rating" min="0" max="10" step="0.1" required><br>

                <label for="developer">Разработчик:</label>
                <select name="developer" required>
                    <?php while ($developer = mysqli_fetch_assoc($developersResult)) : ?>
                        <option value="<?php echo $developer['ID_Developer']; ?>"><?php echo $developer['Name']; ?></option>
                    <?php endwhile; ?>
                </select><br><label for="release_date">Дата выпуска:</label>
                <input type="date" name="release_date" required><br>

                <label for="platform">Платформа:</label>
                <select name="platform" required>
                    <?php while ($platform = mysqli_fetch_assoc($platformsResult)) : ?>
                        <option value="<?php echo $platform['ID_Platform']; ?>"><?php echo $platform['Name']; ?></option>
                    <?php endwhile; ?>
                </select><br>

                <label for="price">Цена:</label>
                <input type="number" name="price" min="0" required><br>

                <label for="remainder">Остаток:</label>
                <input type="number" name="remainder" min="0" required><br>

                <button type="submit" class="btn_add">Добавить</button>
            </form>
            <?php
                echo '<a href="../admin/ad.php?tab=' . $tab . '"  class="back">Вернуться к списку</a>';
            ?>

        </div>
    </div>
</div>