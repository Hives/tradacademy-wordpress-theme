<?php

$new = 1; // triggers new header, <head> element etc.

?>

<?php get_header(); ?>

<main>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article class="clearfix container">
			<header>
				<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			</header>
			<section class="two-col-left">
				<?php the_content(); ?>
			</section>
			<?php get_sidebar(); ?>
		</article>
	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
  	<?php endif; ?>
</main>

<?php get_footer(); ?>
