<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<header class="header">
    <h1 class="header__title"><?= get_bloginfo('name')?></h1>
    <p class="header__tagline"><?= get_bloginfo('description')?></p>
    <nav class="header__nav nav">
        <h2 class="nav__title">Navigation principale</h2>

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
</header>

