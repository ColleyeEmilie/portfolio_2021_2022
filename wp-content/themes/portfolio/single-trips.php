<?php get_header()?>
    <?php if(have_posts()):while (have_posts()): the_post();?>
    <main class="layout signeTrip">
        <h2 class="singleTrip__title"><?= get_the_title()?></h2>
        <figure class="trip__fig">
            <?= get_the_post_thumbnail(null, 'thumbnail', ['class'=>'singleTrip__thumb']); ?>
        </figure>
        <div class="singleTrip__content">
            <?= get_the_content()?>
        </div>
        <aside class="singleTrip__details">
            <h3 class="singleTrip__subtiles"> Détails du voyage </h3>
            <dl class="singleTrip__definitions">
                <dt class="singleTrip__label">Date de départ</dt>
                <dd class="singleTrip__date"><time class="singleTrip__time" datetime="<?= date('c', strtotime(get_field('departure_date', false, false))); ?>">
                            <?= ucfirst(date_i18n('F, Y', strtotime(get_field('departure_date', false, false)))); ?>
                        </time></dd>
                <dt class="singleTrip__label">Date de retour</dt>
                <dd class="singleTrip__date">
                    <?php if(get_field('return_date')): ?>
                        <time class="singleTrip__time" datetime="<?= date('c', strtotime(get_field('departure_date', false, false))); ?>">
                            <?= ucfirst(date_i18n('F, Y', strtotime(get_field('departure_date', false, false)))); ?>
                        </time>
                    <?php else: ?>
                    <span class="singleTrip__empty">Aucune date de retour prévue pour le moment</span>
                    <?php endif; ?>
                </dd>
            </dl>
        </aside>
    </main>
    <?php endwhile; endif;?>
<?php get_footer()?>