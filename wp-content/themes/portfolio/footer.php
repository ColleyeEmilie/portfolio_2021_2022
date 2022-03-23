<footer class="footer">
    <nav class="footer__nav nav">
        <h2 class="nav__title">Pied de page</h2>

        <ul class="nav__container">
            <?php foreach(dw_get_menu_items('primary') as $link): ?>
                <li class="<?= $link->getBemClasses('nav__item'); ?>">
                    <a href="<?= $link->url; ?>" class="nav__link"><?= $link->label; ?></a>
                    <?php if($link->hasSubItems()): ?>
                        <ul class="nav__subitems">
                            <?php foreach($link->subitems as $sub): ?>
                                <li class="<?= $link->getBemClasses('nav__subitem'); ?>">
                                    <a href="<?= $sub->url; ?>" class="nav__link"><?= $sub->label; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</footer>
</body>
</html>