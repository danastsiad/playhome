<div class="container">
    <div class="user-content">
        <div class="user-menu">
            <div class="user-name">
                <p class="username"><?php echo  $_SESSION['username']; ?></p>
            </div>
            <div class="user-exit">
                <a href="exit.php" class="exit">Выйти</a>
            </div>
        </div>
        <div class="user-promo">
            <p class="your-promo">Ваши промокоды:</p>
            <table class="player-data">
                <?php while ($player = mysqli_fetch_assoc($playersQuery)) : ?>
                    <tr>
                        <td class="name_gamePromo"><?php echo $player['Name']; ?>:</td>
                        <td class="promo"><?php echo $player['Promocode']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>