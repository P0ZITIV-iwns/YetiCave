<main>
    <?= $nav;?>
    <?php $classname = empty($errors) ? "" : "form--invalid"; ?>
    <form class="form form--add-lot container <?= $classname ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
        <?php $classname = isset($errors['lot-name']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=getPostVal('lot-name'); ?>">
                <span class="form__error"><?= $errors['lot-name']; ?></span>
            </div>
            <?php $classname = isset($errors['category']) ? "form__item--invalid" : ""; ?>
            <div class="form__item <?= $classname?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option value="" disabled selected>Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?=htmlspecialchars($category['id'])?>"
                            <?php if ($category['id'] === getPostVal('category')) :?>   
                                selected
                            <?php endif;?>>
                            <?=htmlspecialchars($category['name'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?= $errors['category']; ?></span>
            </div>
        </div>
        <?php $classname = isset($errors['message']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--wide <?= $classname?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите описание лота"><?=getPostVal('message'); ?></textarea>
            <span class="form__error"><?= $errors['message']; ?></span>
        </div>
        <?php $classname = isset($errors['lot-img']) ? "form__item--invalid" : ""; ?>
        <div class="form__item form__item--file <?= $classname?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <label for="lot-img">Добавить</label>
                <input class="visually-hidden" type="file" name="lot-img" id="lot-img" value="">
                <span class="form__error"><?= $errors['lot-img']; ?></span>
            </div>
        </div>
        <div class="form__container-three">
            <?php $classname = isset($errors['lot-rate']) ? "form__item--invalid" : ""; ?>
            <div class="form__item form__item--small <?= $classname?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=getPostVal('lot-rate'); ?>">
                <span class="form__error"><?= $errors['lot-rate']; ?></span>
            </div>
            <?php $classname = isset($errors['lot-step']) ? "form__item--invalid" : ""; ?>

            <div class="form__item form__item--small <?= $classname?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=getPostVal('lot-step'); ?>">
                <span class="form__error"><?= $errors['lot-step']; ?></span>
            </div>
            <?php $classname = isset($errors['lot-date']) ? "form__item--invalid" : ""; ?>

            <div class="form__item <?= $classname?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" value="<?=getPostVal('lot-date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <span class="form__error"><?= $errors['lot-date']; ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>  
    </form>
</main>