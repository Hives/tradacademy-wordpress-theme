<?php get_header(); ?>

<main>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article class="blog-post clearfix">
			<header>
				<?php if ( is_single() ): ?>
					<h1><?php the_title(); ?></h1>
				<?php else: ?>
					<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				<?php endif; ?>
				<div class="blog-meta">
					Posted by <?php the_author(); ?> on <?php the_date(); ?>
				</div>
			</header>
			<?php the_content(); ?>
		</article>
		<hr/ class="blog-divider">
	<?php endwhile; ?>

	<?php if ( is_single() ): ?>
		<div class="navigation">
			<div class="alignleft">
				<?php previous_post('&laquo; %', '', 'yes'); ?>
			</div>
			<div class="alignright">
				<?php next_post('% &raquo; ', '', 'yes'); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php //posts_nav_link(' ','<span class="alignleft">Newer Posts</span>','<span class="alignright">Previous Posts</span>'); ?>
	<div class="navigation"><p><?php posts_nav_link( $sep = '' ); ?></p></div>

	<?php else: ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
  	<?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
