<?php
/*
Template Name: Courses
*/
?>
<?php get_header(); ?>

<main class="clearfix">

	<article>
		<?php // First loop displays page content ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<section class="clearfix">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</section>

				<?php
				$ID = $post->ID;
				$args=array(
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_parent' => $ID,
					'posts_per_page' => 0,
					'ignore_sticky_posts'=> 1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
				);
				$temp = $wp_query; // assign ordinal query to temp variable for later use
				$wp_query = null;
				$wp_query = new WP_Query($args);

				if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<?php $counter = $counter == "odd" ? "even" : "odd"; ?>

					<section class="course brief <?php echo $counter; ?>">
						<h3><?php the_title(); ?></h3>
						<p>
							<a href='<?php the_permalink(); ?>' title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						</p>
						<?php the_excerpt(); ?>
					</section>

				<?php endwhile; endif; ?>

				<?php $wp_query = $temp; ?>

		<?php endwhile; else: ?>
			<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	  	<?php endif; ?>

	</article>

</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>