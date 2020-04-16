<div>
    <div class="box">
    <?php $is_product = false; if ( class_exists( 'woocommerce' )) { $is_product = is_product(); } ?>
        <?php if (has_post_thumbnail() && !$is_product) : ?>
            <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
            <div class="image fit">
                <img src="<?php echo $image[0]; ?>" alt="" title="<?php the_title_attribute(); ?>">
            </div>
        <?php endif; ?>
        <div class="content">
            <header class="align-center">
                <p> <?php
                    $i = 0;
                    $value = get_the_category();
                    $len = count($value);
                    foreach ($value as $category) {
                        if ($i == $len - 1) {
                            echo $category->name . "";
                        } else {
                            echo $category->name . " | ";
                        }
                        ++$i;
                    }    ?> </p>
                <?php if (is_singular()) {
                    echo '<h1 class="entry-title">';
                } else {
                    echo '<h2 class="entry-title">';
                } ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
                <?php if (is_singular()) {
                    echo '</h1>';
                } else {
                    echo '</h2>';
                } ?>
            </header>
            <?php edit_post_link(); ?>
            <?php if (!is_search()) {
                get_template_part('entry', 'meta');
            } ?>
            <div class="entry-summary">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"></a>
                <?php endif; ?>
                <?php if (is_search()) { ?>
                    <div class="entry-links"><?php wp_link_pages(); ?></div>
                <?php } ?>
            </div>
            <?php get_template_part('entry', (is_front_page() || is_home() || is_front_page() && is_home() || is_archive() || is_search() ? 'summary' : 'content')); ?>
            <?php if (is_singular()) {
                get_template_part('entry-footer');
            } ?>
        </div>
    </div>
</div>