<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Emilie Colleye">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Emilie Colleye">
    <meta name="description" content="Projet Portfolio">
    <meta name="keywords" content="Portfolio, Web, Designer">
    <title><?= get_bloginfo('name'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?= portfolio_mix('css/style.css'); ?>" />
    <script type="text/javascript" src="<?= portfolio_mix('js/script.js'); ?>"></script>
    <?php wp_head(); ?>
</head>
<body aria-labelledby="projet-portfolio">
<header>
    <h1 id="projet__portfolio" class="header__title hidden" aria-level="1"><?= get_bloginfo('name'); ?></h1>
    <p class="header__placeholder hidden"><?= get_bloginfo('description'); ?></p>
    <nav aria-labelledby="navigation" class="header__nav">
        <div class="navbar">
            <div class="container nav-container">
                <input class="checkbox" type="checkbox" name="" id="" />
                <div class="hamburger-lines">
                    <span class="line line1"></span>
                    <span class="line line2"></span>
                    <span class="line line3"></span>
                </div>
                <h2 id="navigation" class="nav__title hidden" aria-level="2">Navigation</h2>
                <div class="nav__logo logo">
                    <a href="<?= home_url() ?>" class="logo__link">Accueil</a>
                </div>
                    <ul class="nav__navigation menu-items">
                        <?php foreach (portfolio_get_menu_items('primary') as $link): ?>
                            <li class="<?= $link->getBemClasses('nav__item'); ?>">
                                <a href="<?= $link->url; ?>"
                                    <?= $link->title ? 'title = "' . $link->title . '"' : ''; ?>
                                   class="nav__link"><?= $link->label; ?></a>
                                <?php if ($link->hasSubItems()): ?>
                                    <ul class="nav__subcontainer">
                                        <?php foreach ($link->subitems as $sub): ?>
                                            <li class="<?= $link->getBemClasses('nav__item') ?>">
                                                <a href="<?= $sub->url; ?>"
                                                    <?= $sub->title ? 'title = "' . $sub->title . '"' : ''; ?>
                                                   class="nav__link"><?= $sub->label; ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
            </div>
            </div>


    </nav>
</header>