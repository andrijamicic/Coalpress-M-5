<?php get_header(); ?>
<main id="content">
	<div class="grid-style">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php get_template_part('entry'); ?>

				<?php if (comments_open() && !post_password_required()) {
					comments_template('', true);
				} ?>
		<?php endwhile;
		endif; ?>
	</div>
	<?php get_template_part('nav', 'below'); ?>
</main>

<?php if (!is_front_page()) : ?>
	<?php get_sidebar(); ?>
<?php endif; ?>

<?php get_footer(); ?>