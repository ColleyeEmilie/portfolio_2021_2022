<article class="post post--<?= $modifier; ?>">
    <a href="<?= get_the_permalink(); ?>" class="post__link"><?= str_replace(':title', get_the_title(), __('Lire l’article ":title"', 'dw')); ?></a>
    <div class="post__card">
        <header class="post__head">
            <h3 class="post__title"><?= get_the_title(); ?></h3>
            <p class="post__meta"><?= str_replace(
                    [':author', ':date'],
                    [get_the_author(), '<time class="post__date" datetime="' . get_the_date('c') . '">' . get_the_date() . '</time>'],
                    __('Publie par :author le :date.')
                ); ?></p>
        </header>
        <figure class="post__fig">
            <?= get_the_post_thumbnail(null, 'medium_large', ['class' => 'post__thumb']); ?>
        </figure>
        <div class="post__excerpt">
            <p><?= get_the_excerpt(); ?></p>
        </div>
    </div>
</article>