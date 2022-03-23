<?php /* Template Name: About page template */ ?>
<?php get_header()?>
<?php if(have_posts()):while (have_posts()): the_post();?>
    <main class="layout singleAbout">
        <h2 class="singleAbout__title"><?= get_the_title()?></h2>
        <div class="singleAbout__content">
            <?= get_the_content()?>
        </div>
    </main>
<?php endwhile; endif;?>
<?php get_footer()?>

