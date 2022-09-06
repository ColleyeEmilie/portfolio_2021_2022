<?php get_header(); ?>
    <main class="main main__accueil">
        <section class="wave">
            <div class="accueil__title">
                <h2 class="main__title" aria-level="2 reveal-1">Emilie Colleye</h2>
                <p class="main__subtitle reveal-2">Web Designer</p>
            </div>
            <div class="accueil__down">
                <a class="arrow" href="#about__ancre"></a>
            </div>

            <svg class="main__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,0L48,21.3C96,43,192,85,288,90.7C384,96,480,64,576,69.3C672,75,768,117,864,144C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </section>

        <section  class="title__container about__section">
            <div id="about__ancre"></div>
            <div class="accueil__about">
                <h2 id="About" class="about__title" aria-level="2">Qui suis-je ? </h2>
                <?php if (($presentations = antilope_get_presentations(1))->have_posts()):while ($presentations->have_posts()): $presentations->the_post(); ?>
                    <div class="about__position">
                        <section aria-labelledby="about" class="about__section">
                            <h3 class="hidden" aria-level="3">presentation</h3>
                            <p class="about__content reveal-1">
                                <?=get_field('presentation')?>
                            </p>
                        </section>
                    </div>
                <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <svg class="main__svg about__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,288L34.3,272C68.6,256,137,224,206,186.7C274.3,149,343,107,411,106.7C480,107,549,149,617,176C685.7,203,754,213,823,224C891.4,235,960,245,1029,213.3C1097.1,181,1166,107,1234,80C1302.9,53,1371,75,1406,85.3L1440,96L1440,0L1405.7,0C1371.4,0,1303,0,1234,0C1165.7,0,1097,0,1029,0C960,0,891,0,823,0C754.3,0,686,0,617,0C548.6,0,480,0,411,0C342.9,0,274,0,206,0C137.1,0,69,0,34,0L0,0Z"></path>
            </svg>
        </section>

        <section class="accueil__projets">
            <div class="accueil__projets">
                <h2 id="Projets" class="main__projet__title" aria-level="2">Mes projets </h2>
                <?php if (($projets = portfolio_get_projets(3))->have_posts()):while ($projets->have_posts()): $projets->the_post(); ?>

                    <article aria-labelledby="<?=get_post_field('post_name')?>" class="projets__article ">
                        <h3 class="hidden" aria-level="3"><?= get_the_title(); ?></h3>
                        <a href="<?= get_the_permalink() ?>">
                            <div class="projets__img reveal-1">
                            <figure class="projets__fig accueil__fig">
                                <?= get_the_post_thumbnail(null, 'post-thumbnail', ['class' => 'projets__thumb', 'class' => 'accueil__projets__thumb']); ?>
                            </figure>
                            <div class="overlay overlay--blur">
                                <p class="overlay__title"><?= get_the_title(); ?></p>
                            </div>
                        </div>
                        </a>
                    </article>

                <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
<?php get_footer(); ?>