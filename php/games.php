<?php
include 'header.php';
include('config/db.php');
session_start();

$query = "SELECT games.*, genres.Name AS Genre, platforms.Name AS Platform, developers.Name AS Developer
        FROM games
        JOIN genres ON games.ID_Genre = genres.ID_Genre
        JOIN platforms ON games.ID_Platform = platforms.ID_Platform
        JOIN developers ON games.ID_Developer = developers.ID_Developer
        ORDER BY games.ID_Game ASC";
$result = mysqli_query($link, $query);

$games = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $games[] = $row;
    }
}
?>

<!-- Игры -->
<div class="games">
    <div class="container">
        <div class="games-contnet">
            <?php foreach ($games as $game) : ?>
                <div class="games-cart">
                    <img src="<?php echo $game['Picture']; ?>" alt="">
                    <h3><?php echo $game['Name']; ?></h3>
                    <div class="games-cart-conent">
                        <p> <span><?php echo $game['Name']; ?></span> <?php echo $game['Description']; ?></p>
                        <table>
                            <tr>
                                <th>ЖАНР:</th>
                                <th><?php echo $game['Genre']; ?></th>
                            </tr>
                            <tr>
                                <th>РЕЙТИНГ:</th>
                                <th><?php echo $game['Rating']; ?></th>
                            </tr>
                            <tr>
                                <th>РАЗРАБОТЧИК:</th>
                                <th><?php echo $game['Developer']; ?></th>
                            </tr>
                            <tr>
                                <th>ДАТА ВЫХОДА:</th>
                                <th>
                                    <?php
                                    $release_date = strtotime($game['Release_date']);
                                    $formatted_date = date('j F Y', $release_date);
                                    $formatted_date = str_replace(
                                        ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                        ['янв.', 'фев.', 'марта', 'апр.', 'мая', 'июня', 'июля', 'авг.', 'сент.', 'окт.', 'нояб.', 'дек.'],
                                        $formatted_date
                                    );
                                    echo $formatted_date;
                                    ?>
                                </th>
                            </tr>
                            <tr>
                                <th>ПЛАТФОРМА:</th>
                                <th><?php echo $game['Platform']; ?></th>
                            </tr>
                            <tr>
                                <th>ЦЕНА:</th>
                                <th><?php echo $game['Price']; ?></th>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endforeach;
            mysqli_close($link);
            ?>
        </div>
    </div>
</div>
<!--Конец игр-->

<!--Подвал-->
<?php include "footer.php"; ?>
<!--Конец подвала-->
</body>

</html>