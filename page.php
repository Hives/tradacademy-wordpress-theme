<?php get_header(); ?>

<main>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article class="clearfix">
			<header>
				<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			</header>
			<?php the_content(); ?>
		</article>
	<?php endwhile; else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
  	<?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
