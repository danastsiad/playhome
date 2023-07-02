<?php
include './../header.php';
include('./../config/db.php');
session_start();

if ($_SESSION['admin'] > 0) {
?>
  <div class="container">
    <div class="row">
      <div class="tabs-left">
        <ul class="nav nav-tabs">
          <?php
          $tab = isset($_GET['tab']) ? $_GET['tab'] : 'a'; // Получаем значение вкладки из параметра tab в URL
          ?>
          <li<?php echo ($tab === 'a') ? ' class="active"' : ''; ?>><a href="#a" data-target="a">Игры</a></li>
          <li<?php echo ($tab === 'b') ? ' class="active"' : ''; ?>><a href="#b" data-target="b">Жанры</a></li>
          <li<?php echo ($tab === 'c') ? ' class="active"' : ''; ?>><a href="#c" data-target="c">Разработчики</a></li>
          <li<?php echo ($tab === 'd') ? ' class="active"' : ''; ?>><a href="#d" data-target="d">Платформы</a></li>
          <li<?php echo ($tab === 'e') ? ' class="active"' : ''; ?>><a href="#e" data-target="e">Пользователи</a></li>
          <li<?php echo ($tab === 'f') ? ' class="active"' : ''; ?>><a href="#f" data-target="f">Промокоды</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab<?php echo ($tab === 'a') ? ' active' : ''; ?>" id="a">

            <?php
            $query = "SELECT g.ID_Game, g.Name, g.Picture, g.Description, genres.Name AS Genre, g.Rating, developers.Name AS Developer, g.Release_date, platforms.Name AS Platform, g.Price, g.Remainder 
            FROM games AS g
            LEFT JOIN genres ON g.ID_Genre = genres.ID_Genre
            LEFT JOIN developers ON g.ID_Developer = developers.ID_Developer
            LEFT JOIN platforms ON g.ID_Platform = platforms.ID_Platform";

            $conditions = array(); // Массив для хранения условий фильтрации

            // Обработка формы фильтрации
            if (isset($_POST['filter_submit'])) {
              $conditions = array(); // Массив для условий фильтрации

              // Фильтр по рейтингу
              $minRating = isset($_POST['min_rating']) ? $_POST['min_rating'] : null;
              $maxRating = isset($_POST['max_rating']) ? $_POST['max_rating'] : null;

              if (!empty($minRating) && !empty($maxRating)) {
                $conditions[] = "g.Rating BETWEEN '$minRating' AND '$maxRating'";
              }

              // Фильтр по году выпуска
              $year = isset($_POST['year']) ? $_POST['year'] : null;

              if (!empty($year)) {
                $conditions[] = "YEAR(g.Release_date) = '$year'";
              }

              // Фильтр по максимальной цене
              $maxPrice = isset($_POST['max_price']) ? $_POST['max_price'] : null;

              if (!empty($maxPrice)) {
                $conditions[] = "g.Price <= '$maxPrice'";
              }

              // Фильтр по платформе
              $selectedPlatforms = isset($_POST['platforms']) ? $_POST['platforms'] : array();

              if (!empty($selectedPlatforms)) {
                $platformConditions = array();
                foreach ($selectedPlatforms as $platformID) {
                  $platformConditions[] = "g.ID_Platform = '$platformID'";
                }
                $conditions[] = "(" . implode(" OR ", $platformConditions) . ")";
              }

              // Фильтр по жанру
              $selectedGenres = isset($_POST['genres']) ? $_POST['genres'] : array();

              if (!empty($selectedGenres)) {
                $genreConditions = array();
                foreach ($selectedGenres as $genreID) {
                  $genreConditions[] = "g.ID_Genre = '$genreID'";
                }
                $conditions[] = "(" . implode(" OR ", $genreConditions) . ")";
              }

              // Фильтр по разработчику
              $selectedDevelopers = isset($_POST['developers']) ? $_POST['developers'] : array();

              if (!empty($selectedDevelopers)) {
                $developerConditions = array();
                foreach ($selectedDevelopers as $developerID) {
                  $developerConditions[] = "g.ID_Developer = '$developerID'";
                }
                $conditions[] = "(" . implode(" OR ", $developerConditions) . ")";
              }

              // Фильтр по минимальному остатку
              $minRemainder = isset($_POST['min_remainder']) ? $_POST['min_remainder'] : null;

              if (!empty($minRemainder)) {
                $conditions[] = "g.Remainder <= '$minRemainder'";
              }

              // Собираем условия фильтрации
              if (!empty($conditions)) {
                $conditionQuery = implode(" AND ", $conditions);
                $query .= " WHERE $conditionQuery";
              }
            }
            if (isset($_POST['filter_reset'])) {
              // Сбросить все выбранные значения
              $_POST = array();
              $_GET = array();
              unset($_REQUEST);
            }

            $query .= " ORDER BY g.Release_date DESC";

            $games = mysqli_query($link, $query) or die(mysqli_error($link));

            ?>
            <div class="user-menu">
              <div class="user-name">
                <h2 class="add"><a href="../admin/add_game.php">Добавить новую игру</a> </h2><br>
              </div>
              <div class="user-exit">
                <a href="./../exit.php" class="exit">Выйти</a>
              </div>
            </div>
            <div class="trip">
              <div class="search-form">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <h3>Фильтр</h3>
                  <label for="min_rating">Мин. рейтинг:</label>
                  <input type="number" name="min_rating" id="min_rating" step="0.1" value="<?php echo isset($_POST['min_rating']) ? $_POST['min_rating'] : ''; ?>"> <br>
                  <label for="max_rating">Макс. рейтинг:</label>
                  <input type="number" name="max_rating" id="max_rating" step="0.1" value="<?php echo isset($_POST['max_rating']) ? $_POST['max_rating'] : ''; ?>"> <br>
                  <label for="year">Год выпуска:</label>
                  <input type="number" name="year" id="year" value="<?php echo isset($_POST['year']) ? $_POST['year'] : ''; ?>"> <br>
                  <label for="max_price">Макс. цена:</label>
                  <input type="number" name="max_price" id="max_price" value="<?php echo isset($_POST['max_price']) ? $_POST['max_price'] : ''; ?>"> <br>
                  <label for="min_remainder">Мин. остаток:</label>
                  <input type="number" name="min_remainder" id="min_remainder" value="<?php echo isset($_POST['min_remainder']) ? $_POST['min_remainder'] : ''; ?>"> <br>
                  
                    <?php
                    $queryGenres = "SELECT * FROM genres";
                    $resultGenres = mysqli_query($link, $queryGenres);
                    if (mysqli_num_rows($resultGenres) > 0) {
                      echo '<div class="filter">
                      <div class="">
                      <label>Жанры:</label>
                      </div>
                      <div class="filter-column">';
                      while ($genre = mysqli_fetch_assoc($resultGenres)) {
                        $genreID = $genre['ID_Genre'];
                        $genreName = $genre['Name'];
                        $checked = (isset($_POST['genres']) && in_array($genreID, $_POST['genres'])) ? 'checked' : '';
                        echo "<label for='genre_$genreID' class='deve'><input type='checkbox' name='genres[]' id='genre_$genreID' value='$genreID' $checked>$genreName</label>";
                      }
                      echo '</div>
                      </div>';
                    }
                    $queryPlatforms = "SELECT * FROM platforms";
                    $resultPlatforms = mysqli_query($link, $queryPlatforms);

                    if (mysqli_num_rows($resultPlatforms) > 0) {
                      echo '<div class="filter">
                      <div class="">
                      <label>Платформы:</label>
                      </div>
                      <div class="filter-column">';
                      while ($platform = mysqli_fetch_assoc($resultPlatforms)) {
                        $platformID = $platform['ID_Platform'];
                        $platformName = $platform['Name'];
                        $checked = (isset($_POST['platforms']) && in_array($platformID, $_POST['platforms'])) ? 'checked' : '';
                        echo "<label for='platform_$platformID' class='deve'><input type='checkbox' name='platforms[]' id='platform_$platformID' value='$platformID'$checked>$platformName</label>";
                      }
                      echo '</div>
                      </div>';
                    }
                    $queryDevelopers = "SELECT * FROM developers";
                    $resultDevelopers = mysqli_query($link, $queryDevelopers);

                    if (mysqli_num_rows($resultDevelopers) > 0) {
                      echo '<div class="filter">
                      <div class="">
                      <label>Разработчики:</label>
                      </div>
                      <div class="filter-column">';
                      while ($developer = mysqli_fetch_assoc($resultDevelopers)) {
                        $developerID = $developer['ID_Developer'];
                        $developerName = $developer['Name'];
                        $checked = (isset($_POST['developers']) && in_array($developerID, $_POST['developers'])) ? 'checked' : '';
                        echo "<label for='developer_$developerID' class='deve'><input type='checkbox' name='developers[]' id='developer_$developerID' value='$developerID'$checked>$developerName</label>";
                      }
                      echo '</div>';
                      echo '</div>';
                    }
                    ?>

                  <div class="but">
                    <button type="submit" name="filter_submit">Применить</button>
                    <button type="submit" name="filter_reset" class="filter_reset">Сбросить</button>
                  </div>
                </form>
              </div>
              <div class="admin_tab">
                <table class="admin_table">
                  <thead>
                    <tr>
                      <th>Название</th>
                      <th>Изображение</th>
                      <th class="description">Описание</th>
                      <th>Жанр</th>
                      <th>Рейтинг</th>
                      <th>Разработчик</th>
                      <th>Дата выпуска</th>
                      <th>Платформа</th>
                      <th>Цена</th>
                      <th>Остаток</th>
                      <th>Действия</th>
                    </tr>
                  </thead>

                    <?php while ($game = mysqli_fetch_assoc($games)) { ?>
                      <tbody>
                        <tr>
                          <td><?php echo $game['Name']; ?></td>
                          <td><img src="<?php echo $game['Picture']; ?>" alt="" class="img_table"></td>
                          <td><?php echo $game['Description']; ?></td>
                          <td><?php echo $game['Genre']; ?></td>
                          <td><?php echo $game['Rating']; ?></td>
                          <td><?php echo $game['Developer']; ?></td>
                          <td><?php echo $game['Release_date']; ?></td>
                          <td><?php echo $game['Platform']; ?></td>
                          <td><?php echo $game['Price']; ?></td>
                          <td><?php echo $game['Remainder']; ?></td>
                          <td>
                            <a href="../admin/edit_game.php?id=<?php echo $game['ID_Game']; ?>"><img src="/playhome/img/pens.svg" alt="" class="dey"></a>
                            <a href="../admin/delete_game.php?id=<?php echo $game['ID_Game']; ?>"><img src="/playhome/img/trash_can.svg" alt="" class="dey"></a>
                          </td>
                        </tr>
                      </tbody>
                    <?php } ?>

                </table>
              </div>
            </div>
          </div>
          <div class="tab<?php echo ($tab === 'b') ? ' active' : ''; ?>" id="b">
            <div class="user-menu">
              <div class="user-name">
                <h2 class="add"><a href="../admin/add_genre.php">Добавить новый жанр</a> </h2><br>
              </div>
              <div class="user-exit">
                <a href="./../exit.php" class="exit">Выйти</a>
              </div>
            </div>
            <table class="admin_table">
              <thead>
                <tr>
                  <th>Название</th>
                  <th>Количество игр</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>

                <?php
                $query = "SELECT g.ID_Genre, g.Name, IFNULL(COUNT(gl.ID_Game), 0) AS gameCount
                FROM genres g
                LEFT JOIN games gl ON g.ID_Genre = gl.ID_Genre
                GROUP BY g.ID_Genre, g.Name";
                $genres = mysqli_query($link, $query) or die(mysqli_error($link));

                while ($genre = mysqli_fetch_assoc($genres)) {
                  echo '<tr>
                          <td>' . $genre['Name'] . '</td>
                          <td>' . $genre['gameCount'] . '</td>
                          <td>
                            <a href="../admin/edit_genre.php?id=' . $genre['ID_Genre'] . '"><img src="/playhome/img/pens.svg" alt="" class="dey"></a>
                            <a href="../admin/delete_genre.php?id=' . $genre['ID_Genre'] . '"><img src="/playhome/img/trash_can.svg" alt="" class="dey"></a>
                          </td>
                        </tr>';
                      }
                ?>
              </tbody>
            </table>
          </div>

          <div class="tab<?php echo ($tab === 'c') ? ' active' : ''; ?>" id="c">
            <div class="user-menu">
              <div class="user-name">
                <h2 class="add"><a href="../admin/add_developer.php">Добавить нового разработчика</a> </h2><br>
              </div>
              <div class="user-exit">
                <a href="./../exit.php" class="exit">Выйти</a>
              </div>
            </div>
            <table class="admin_table">
              <thead>
                <tr>
                  <th>Имя</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT * FROM developers";
                $developers = mysqli_query($link, $query) or die(mysqli_error($link));

                while ($developer = mysqli_fetch_assoc($developers)) {
                  echo '<tr>
                          <td>' . $developer['Name'] . '</td>
                          <td>
                            <a href="../admin/edit_developer.php?id=' . $developer['ID_Developer'] . '"><img src="/playhome/img/pens.svg" alt="" class="dey"></a>
                            <a href="../admin/delete_developer.php?id=' . $developer['ID_Developer'] . '"><img src="/playhome/img/trash_can.svg" alt="" class="dey"></a>
                          </td>
                        </tr>';
                }
                ?>
              </tbody>
            </table>

          </div>
          <div class="tab<?php echo ($tab === 'd') ? ' active' : ''; ?>" id="d">
            <div class="user-menu">
              <div class="user-name">
                <h2 class="add"><a href="../admin/add_platform.php">Добавить новую платформу</a> </h2><br>
              </div>
              <div class="user-exit">
                <a href="./../exit.php" class="exit">Выйти</a>
              </div>
            </div>
            <table class="admin_table">
              <thead>
                <tr>
                  <th>Название</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>

                <?php
                $query = "SELECT * FROM platforms";
                $platforms = mysqli_query($link, $query) or die(mysqli_error($link));

                while ($platform = mysqli_fetch_assoc($platforms)) {
                  echo '<tr>
                          <td>' . $platform['Name'] . '</td>
                          <td>
                            <a href="../admin/edit_platform.php?id=' . $platform['ID_Platform'] . '"><img src="/playhome/img/pens.svg" alt="" class="dey"></a>
                            <a href="../admin/delete_platform.php?id=' . $platform['ID_Platform'] . '"><img src="/playhome/img/trash_can.svg" alt="" class="dey"></a>
                          </td>
                        </tr>';
                }
                ?>

              </tbody>
            </table>

          </div>
          <div class="tab<?php echo ($tab === 'e') ? ' active' : ''; ?>" id="e">
            <div class="user-menu">
              <div class="user-name">
                <h2 class="add"><a href="../admin/add_user.php">Добавить нового пользователя</a> </h2><br>
              </div>
              <div class="user-exit">
                <a href="./../exit.php" class="exit">Выйти</a>
              </div>
            </div>
            <table class="admin_table">
              <thead>
                <tr>
                  <th>Логин</th>
                  <th>Почта</th>
                  <th>Статус</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>

                <?php
                $query = "SELECT * FROM users";
                $users = mysqli_query($link, $query) or die(mysqli_error($link));

                while ($user = mysqli_fetch_assoc($users)) {
                  echo '<tr>
                          <td>' . $user['Login'] . '</td>
                          <td>' . $user['Email'] . '</td>
                          <td>' . $user['Status'] . '</td>
                          <td>
                            <a href="../admin/edit_user.php?id=' . $user['ID_User'] . '"><img src="/playhome/img/pens.svg" alt="" class="dey"></a>
                            <a href="../admin/delete_user.php?id=' . $user['ID_User'] . '"><img src="/playhome/img/trash_can.svg" alt="" class="dey"></a>
                          </td>
                        </tr>';
                }
                ?>

              </tbody>
            </table>
          </div>
          <div class="tab<?php echo ($tab === 'f') ? ' active' : ''; ?>" id="f">
            <div class="user-menu">
              <div class="user-name">
                <h2 class="add"><a href="../admin/add_promo.php">Добавить новый промокод</a></h2><br>
              </div>
              <div class="user-exit">
                <a href="./../exit.php" class="exit">Выйти</a>
              </div>
            </div>
            <table class="admin_table">
              <thead>
                <tr>
                  <th>Пользователь</th>
                  <th>Игра</th>
                  <th>Промокод</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>

              <?php
              $query = "SELECT  u.Login AS UserName, g.Name AS GameName , p.Promocode, p.ID_Player
              FROM players AS p
              LEFT JOIN users AS u ON p.ID_User = u.ID_User
              LEFT JOIN games AS g ON p.ID_Game = g.ID_Game";
              $players = mysqli_query($link, $query) or die(mysqli_error($link));

              while ($player = mysqli_fetch_assoc($players)) {
                echo '<tr>
                        <td>' . $player['UserName'] . '</td>
                        <td>' . $player['GameName'] . '</td>
                        <td>' . $player['Promocode'] . '</td>
                        <td>
                          <a href="../admin/edit_promo.php?id=' . $player['ID_Player'] . '"><img src="/playhome/img/pens.svg" alt="" class="dey"></a>
                          <a href="../admin/delete_promo.php?id=' . $player['ID_Player'] . '"><img src="/playhome/img/trash_can.svg" alt="" class="dey"></a>
                        </td>
                      </tr>';
              }
              ?>
              </tbody>
            </table>
          </div>
        </div><!-- /tab-content -->
      </div>
    </div><!-- /row -->
  </div><!-- /container -->
<?php
} else {
  header("Location: ../playhome/index.php");
}
include './../footer.php';
?>
<script src="/playhome/js/script.js"></script>