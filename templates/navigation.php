<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item <?php if ($categoryName === $category['name']): ?>nav__item--current<?php endif; ?>">
                <a href="/all-lots.php?name=<?= $category['name'] ?>"><?= htmlspecialchars($category['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>