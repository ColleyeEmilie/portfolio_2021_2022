<?php /* Template Name: Contact page template */ ?>
<?php get_header(); ?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="main__contact">
        <h2 id="contact" class="contact__title" aria-level="2"><?= get_the_title(); ?></h2>
        <section aria-labelledby="contact" class="contact">
            <div class="contact__container">
                <section aria-labelledby="coordinates" class="contact__section">
                    <h2 id="coordinates" class="coordinate__title hidden" aria-level="2">Coordonnées</h2>
                    <?php if (($informations = antilope_get_informations(20))->have_posts()):while ($informations->have_posts()): $informations->the_post(); ?>
                        <section aria-labelledby="coordinate" class="coordinates" itemscope itemtype="https://schema.org/Person">
                            <h3 id="coordinate" class="coordinates__title hidden"
                                aria-level="3">Mes coordonnées</h3>
                            <section aria-labelledby="mail" class="coordinate__mail coord">
                                <div class="svg-mail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-contact mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                </div>
                                <h4 id="mail" class="coordinates__title hidden" aria-level="4">Mail</h4>
                                <a class="coordinates__mail mail " href="mailto:<?=get_field('email')?>" itemprop="email"><?=get_field('email')?></a>
                            </section>
                            <section aria-labelledby="telephone" class="coordinate__phone coord ">
                                <div class="svg-phone">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-contact phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                </div>

                                <h4 id="telephone" class="coordinates__title hidden" aria-level="4">Téléphone</h4>
                                <p class="coordinates__mail phone " itemprop="telephone"><?=get_field('phone')?></p>
                            </section>
                            <section aria-labelledby="address" class="coordinates__address coord" itemscope
                                     itemtype="https://schema.org/PostalAddress">
                                <div class="svg-home">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg-contact home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                </div>

                                <h4 id="address" class="adress hidden" aria-level="4">Adresse</h4>
                                <p itemprop="streetAddress" class="coordinates__adress "><?= get_field('adresse')?></p>

                            </section>
                        </section>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </section>

                <section aria-labelledby="contactForm" class="contact__form">
                    <h2 id="contactForm" class="form__title hidden" aria-level="2">Formulaire de contact</h2>
                    <?php echo apply_shortcodes( '[contact-form-7 id="29027" title="Formulaire de contact"]' ); ?>
                </section>
            </div>
        </section>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>