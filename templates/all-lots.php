<main>
    <?= $nav;?>
    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории «<span><?=htmlspecialchars($categoryName)?></span>»</h2>
            <?php if (empty($lots)) : ?>
                <h3>Ничего не найдено по вашему запросу</h3>
            <?php else : ?>
                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?= htmlspecialchars($lot['img']); ?>" width="350" height="260" alt="<?= htmlspecialchars($lot['name']); ?>">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= htmlspecialchars($lot['category_name']); ?></span>
                                <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id']?>"><?=htmlspecialchars($lot['name'])?></a></h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
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
                <?php if (isset($pagination['countPages']) && $pagination['countPages'] > 1) :?>
                    <ul class="pagination-list">
                        <li class="pagination-item pagination-item-prev">
                            <?php if ($pagination['currentPage'] !== min($pagination['pages'])): ?>
                                <a href="<?='/all-lots.php?name=' . htmlspecialchars($categoryName) . '&page=' . htmlspecialchars($pagination['prevPage'])?>">Назад</a>
                            <?php else : ?>
                                <a>Назад</a>
                            <?php endif; ?>
                        </li>
                        <?php foreach ($pagination['pages'] as $numberPage) :?>
                            <?php if ($numberPage === $pagination['currentPage']) :?>
                            <li class="pagination-item pagination-item-active">
                                <a><?=htmlspecialchars($numberPage)?></a>
                            </li>
                            <?php else :?>
                            <li class="pagination-item">
                                <a href="<?='/all-lots.php?name=' . htmlspecialchars($categoryName) . '&page=' . htmlspecialchars($numberPage)?>"><?=htmlspecialchars($numberPage)?></a>
                            </li>
                            <?php endif;?>
                        <?php endforeach;?>
                        <li class="pagination-item pagination-item-next">
                            <?php if ($pagination['currentPage'] !== max($pagination['pages'])): ?>
                                <a href="<?='/all-lots.php?name=' . htmlspecialchars($categoryName) . '&page=' . htmlspecialchars($pagination['nextPage'])?>">Вперед</a>
                            <?php else : ?>
                                <a>Вперед</a>
                            <?php endif; ?>  
                        </li>
                    </ul>
                <?php endif;?>
            <?php endif; ?>
        </section>
    </div>
</main>