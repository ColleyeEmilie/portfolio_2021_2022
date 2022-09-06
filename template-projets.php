<?php /* Template Name: Projets page template */ ?>
<?php get_header(); ?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="main__projets">
        <section aria-labelledby="Projets" class="projets">
            <h2 id="Projets" class="projets__title" aria-level="2">Projets</h2>
        </section>
        <section aria-labelledby="projets" class="projets__section projets">
            <?php if (($projets = portfolio_get_projets(3))->have_posts()):while ($projets->have_posts()): $projets->the_post(); ?>
                <article aria-labelledby="<?=get_post_field('post_name')?>" class="projets__article ">
                    <div class="projets__img reveal-1">
                        <a href="<?= get_the_permalink() ?>">
                        <figure class="projets__fig">
                            <?= get_the_post_thumbnail(null, 'post-thumbnail', ['class' => 'projets__thumb']); ?>
                        </figure>
                        </a>
                    </div>
                    <div class="projets__cards">
                        <header class="projets__head reveal-1">
                            <h3 id="<?=get_post_field('post_name')?>" class="projets__projet__title2" aria-level="3"><?= get_the_title() ?></h3>
                        </header>
                        <div class="projet__excerpt reveal-1">
                            <?= get_the_excerpt() ?>
                        </div>
                        <a href="<?= get_the_permalink() ?>"
                           class="projet__link reveal-2">Voir le projet</a>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php endif; ?>
        </section>
    </main>

<?php endwhile; endif; ?>
<?php get_footer() ?>