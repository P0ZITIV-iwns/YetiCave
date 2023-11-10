<main>
    <?= $nav;?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($bets as $bet): ?>
                <?php $timeLeft = timeLeft($bet['lot_date_finished']); 
                    $value = $timeLeft[0] . ':' . $timeLeft[1];
                    $classTr = '';
                    $classTd = '';
                    $contacts = '';
                    $style_add = 'none';
                ?>
                <?php if($bet['lot_winner'] === $_SESSION['user_id']): 
                    $classTd = 'timer--win';
                    $classTr = 'rates__item--win';
                    $style_add = 'block';
                    $contacts = htmlspecialchars($bet['user_contacts']);
                    $value = 'Ставка выиграла';
                ?>
                <?php elseif (strtotime($bet['lot_date_finished'] . '+1 day') <= time()): 
                    $classTd = 'timer--end';
                    $classTr = 'rates__item--end';
                    $style_add = 'none';
                    $contacts = '';
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
                        <div>
                            <h3 class="rates__title"><a href="/lot.php?id=<?= $bet['lot_id']?>"><?= htmlspecialchars($bet['lot_name']); ?></a></h3>
                            <p><?= $contacts; ?></p>
                        </div>
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
        </table>
    </section>
</main>