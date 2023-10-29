<main>
    <?= $nav;?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <!-- <tr class="rates__item rates__item--win">
                <td class="rates__info">
                <div class="rates__img">
                    <img src="../img/rate3.jpg" width="54" height="40" alt="Крепления">
                </div>
                <div>
                    <h3 class="rates__title"><a href="lot.html">Крепления Union Contact Pro 2015 года размер L/XL</a></h3>
                    <p>Телефон +7 900 667-84-48, Скайп: Vlas92. Звонить с 14 до 20</p>
                </div>
                </td>
                <td class="rates__category">
                    Крепления
                </td>
                <td class="rates__timer">
                    <div class="timer timer--win">Ставка выиграла</div>
                </td>
                <td class="rates__price">
                    10 999 р
                </td>
                <td class="rates__time">
                    Час назад
                </td>
            </tr> -->
            <?php foreach ($bets as $bet): ?>
                <?php $timeLeft = timeLeft($bet['lot_date_finished']); 
                    $value = $timeLeft[0] . ':' . $timeLeft[1];
                    $classTr = '';
                    $classTd = '';
                ?>
                <?php if($bet['lot_winner'] === $_SESSION['user_id']): 
                    $classTd = 'timer--win';
                    $classTr = 'rates__item--win';
                    $value = 'Ставка выиграла';
                ?>
                <?php elseif (strtotime($bet['lot_date_finished'] . '+1 day') <= time()): 
                    $classTd = 'timer--end';
                    $classTr = 'rates__item--end';
                    $value = 'Торги окончены';
                ?>              
                <?php elseif($timeLeft[0] < '24'): 
                    $classTd = 'timer--finishing ';
                ?> 
                <?php endif?>
                <tr class="rates__item <?= $classTr; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= htmlspecialchars($bet['lot_img']); ?>" width="54" height="40" alt="<?= htmlspecialchars($bet['lot_name']); ?>">
                        </div>
                        <h3 class="rates__title"><a href="/lot.php?id=<?= $bet['lot_id']?>"><?= htmlspecialchars($bet['lot_name']); ?></a></h3>
                    </td>
                    <td class="rates__category">
                        <?= htmlspecialchars($bet['category_name']); ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer <?= $classTd; ?>">
                            <?= $value; ?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?= htmlspecialchars(format($bet['price'])); ?>
                    </td>
                    <td class="rates__time">
                        <?= htmlspecialchars(getPastTime($bet['created_datetime'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <!-- <tr class="rates__item rates__item--end">
                <td class="rates__info">
                <div class="rates__img">
                    <img src="../img/rate7.jpg" width="54" height="40" alt="Сноуборд">
                </div>
                    <h3 class="rates__title"><a href="lot.html">DC Ply Mens 2016/2017 Snowboard</a></h3>
                </td>
                <td class="rates__category">
                    Доски и лыжи
                </td>
                <td class="rates__timer">
                    <div class="timer timer--end">Торги окончены</div>
                </td>
                <td class="rates__price">
                    10 999 р
                </td>
                <td class="rates__time">
                    19.03.17 в 08:21
                </td>
            </tr> -->
        </table>
    </section>
</main>