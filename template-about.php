<?php /* Template Name: About page template */; ?>
<?php get_header(); ?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="main__about">
        <section aria-labelledby="About" class="about">
            <h2 id="About" class="about__title" aria-level="2">Qui suis-je ? </h2>
        </section>
    <?php if (($presentations = antilope_get_presentations(1))->have_posts()):while ($presentations->have_posts()): $presentations->the_post(); ?>
        <div class="about__position">
            <section aria-labelledby="about" class="about__section">
                <p class="about__content reveal-1">
                    <?=get_field('presentation')?>
                </p>
            </section>
        </div>
        <div class="about__button reveal-2">
            <a href="<?= get_the_permalink(portfolio_get_template_page('template-contact')) ?>"         class="about__contact">Me contacter</a>
        </div>
    <?php endwhile; ?>
    <?php endif; ?>
    </main>
<?php endwhile; ?>
<?php endif; ?>
<?php get_footer() ?>