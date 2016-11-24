<?php get_header(); ?>

<main>
	<article class="clearfix vertical-section">
		<section class="two-col-main">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<header>
					<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				</header>
				<?php the_content(); ?>
			<?php endwhile; else: ?>
				<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
		  	<?php endif; ?>
		</section>
		<aside id="sidebar" class="two-col-side">
			<?php get_sidebar(); ?>
		</aside>
	</article>
</main>

<?php get_footer(); ?>
