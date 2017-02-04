<?php 

$new = 1; // triggers new header, <head> element etc.

?>

<?php get_header(); ?>

<main class="vertical-section">
	<div class="two-col-main clearfix">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="news-item">
				<header>
					<?php if ( is_single() ): ?>
						<h1><?php the_title(); ?></h1>
					<?php else: ?>
						<h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<?php endif; ?>
				</header>
				<?php the_content(); ?>
				<footer>
					<div class="details blog-meta">
						Posted by <strong><?php the_author(); ?></strong> on <strong><?php the_date(); ?></strong>
					</div>					
				</footer>
			</article>
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
		<div class="news-navigation navigation"><?php posts_nav_link( $sep = '' ); ?></div>

		<?php else: ?>
			<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	  	<?php endif; ?>
  	</div>

	<aside id="sidebar" class="two-col-side">
		<?php get_sidebar(); ?>
	</aside>

</main>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
