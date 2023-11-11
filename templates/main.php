<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--<?= htmlspecialchars($category['symbol_code']) ?>">
                    <a class="promo__link" href="/all-lots.php?name=<?= $category['name'] ?>"><?= htmlspecialchars($category['name']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$lot['img']?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=htmlspecialchars($lot['category_name'])?></span>
                        <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id']?>"><?=htmlspecialchars($lot['name'])?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">
                                    <?php if (isset($lot['countBets'])): ?>
                                        <?= $lot['countBets'] === 0 ? 'Стартовая цена' : $lot['countBets'] . get_noun_plural_form($lot['countBets'], " ставка", " ставки", " ставок") ?>
                                    <?php else: ?>
                                        Стартовая цена
                                    <?php endif; ?>    
                                </span>
                                <span class="lot__cost"><?=htmlspecialchars(format($lot['start_price']))?></span>
                            </div>
                            <?php $timeLeft = timeLeft($lot['date_finished']); ?>
                            <div class="lot__timer timer <?php if($timeLeft[0] < '24'): ?>timer--finishing<?php endif?>">
                                <?=$timeLeft[0]?>:<?=$timeLeft[1]?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
