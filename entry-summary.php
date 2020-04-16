<div class="entry-summary">
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"></a>
    <?php endif; ?>
    <?php the_excerpt(); ?>
    <?php if (is_search()) { ?>
        <div class="entry-links"><?php wp_link_pages(); ?></div>
    <?php } ?>
    <footer class="align-center">
        <a class="button alt" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </footer>
</div>