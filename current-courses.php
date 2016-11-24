<?php
/*
Template Name: Courses (current)
*/
?>
<?php get_header(); ?>

<main class="clearfix">

	<article class="vertical-section clearfix">
		<section class="two-col-main">
			<?php // First loop displays page content ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
			<?php endwhile; else: ?>
				<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
		  	<?php endif; ?>

			<?php // Second loop displays list of courses ?>
			<?php

			global $more;
			$more = 0;
			$args=array(
				'post_type' => 'course',
				'post_status' => 'publish',
				'paged' => $paged,
				'posts_per_page' => 0,
				'ignore_sticky_posts'=> 1,
			);
			$temp = $wp_query; // assign ordinal query to temp variable for later use
			$wp_query = null;
			$wp_query = new WP_Query($args);
			$counter = "even";

			if ( have_posts() ) : while ( have_posts() ) : the_post();
				// filter out all courses which are in the past
				$meta = get_post_meta( $post->ID, $key = '', $single = false );
				$initial_date = $meta['_ta_initial_date'][0];
				$num_weeks = $meta['_ta_num_weeks'][0];
				$final_date = strtotime("+" . ($num_weeks - 1) . " weeks", $initial_date);

				if ( $final_date > time() ) :
					$counter = $counter == "even" ? "odd" : "even"; ?>
				
					<section class="course brief <?php echo $counter; ?>">
						<h3><a href='<?php the_permalink(); ?>' title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
						<p>
							<a href='<?php the_permalink(); ?>' title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						</p>
						<div class="course-info">
							<?php print_course_info($meta, true); ?>
						</div>
					</section>

				<?php endif; ?>
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