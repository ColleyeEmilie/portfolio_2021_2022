<article class="projet">
    <a href="<?= get_the_permalink(); ?>" class="projet__link"><?= str_replace(':title', get_the_title(), __('Lire le projet ":title"', 'dw')); ?></a>
    <div class="projet__card">
        <header class="projet__head">
            <h3 class="projet__title"><?= get_the_title(); ?></h3>
            <p class="projet__date"><time class="projet__time" datetime="<?= date('c', strtotime(get_field('departure_date', false, false))); ?>">
                    <?= ucfirst(date_i18n('F, Y', strtotime(get_field('departure_date', false, false)))); ?>
                </time></p>
        </header>
        <figure class="projet__fig">
            <?= get_the_post_thumbnail(null, 'medium_large', ['class' => 'trip__thumb']); ?>
        </figure>
    </div>
</article>