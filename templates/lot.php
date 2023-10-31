<main>
    <?= $nav;?>
    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['name']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= htmlspecialchars($lot['img']); ?>" width="730" height="548" alt="<?= htmlspecialchars($lot['name']); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category_name']); ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <?php $timeLeft = timeLeft($lot['date_finished']); ?>
                    <div class="lot-item__timer timer <?php if($timeLeft[0] < '24'): ?>timer--finishing<?php endif?>">
                        <?=$timeLeft[0]?>:<?=$timeLeft[1]?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= htmlspecialchars(format($lastBet === null ? $lot['start_price'] : $lastBet['price'])); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= htmlspecialchars(format($lastBet === null ? $lot['start_price'] : $lastBet['price'] + $lot['step_price'])); ?></span>
                        </div>
                    </div>
                    <?php $style_add = isset($_SESSION['user_id']) ? "flex" : "none"; ?>
                    <form class="lot-item__form" action="lot.php?id=<?= $lot["id"]; ?>" method="post" autocomplete="off" style="display: <?= $style_add; ?>">
                        <?php $classname = $error !== '' ? "form__item--invalid" : ""; ?>
                        <p class="lot-item__form-item form__item <?= $classname; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?= htmlspecialchars(format($lastBet === null ? $lot['start_price'] : $lastBet['price'] + $lot['step_price'])); ?>">
                            <span class="form__error"><?= $error; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <div class="history">
                    <h3>История ставок (<span><?= count($bets); ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($bet['user_name']); ?></td>
                                <td class="history__price"><?= htmlspecialchars(format($bet['price'])); ?></td>
                                <td class="history__time"><?= htmlspecialchars(getPastTime($bet['created_datetime'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>