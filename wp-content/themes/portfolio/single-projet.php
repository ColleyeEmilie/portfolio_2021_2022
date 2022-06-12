<?php get_header(); ?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="main__projet">
        <section class="projet__header" aria-labelledby="singleProject">
            <h2 class="projet__title" id="projet" aria-level="2"><?= get_the_title(); ?></h2>
        </section>
        <div class="projet__container">
            <div class="projet__content reveal-1">
                <?php the_content(); ?>
            </div>
            <figure class="projet__fig reveal-2">
                <?= get_the_post_thumbnail(null, 'medium_large', ['class' => 'projet__thumb']); ?>
            </figure>
        </div>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>