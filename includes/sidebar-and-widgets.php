<?php
/**
*   -----------------SIDEBAR AND WIDGETS----------------------
*/

// Register our sidebars and widgetized areas.
// copy-pasted from http://codex.wordpress.org/Widgetizing_Themes
function arphabet_widgets_init() {

    register_sidebar( array(
        'name' => 'Home right sidebar',
        'id' => 'home_right_1',
        'before_widget' => '<section class="widget %2$s clearfix">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="rounded">',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'arphabet_widgets_init' );


/**
 * Adds TA_Calendar widget.
 */
class TA_Calendar extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'ta_calendar', // Base ID
            'Trad Academy Upcoming Courses', // Name
            array( 'description' => __( 'Displays all courses scheduled in next 2 weeks', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        // $courses = get_courses_for_sidebar();
        // $courses_filtered = array();
        // $now = time();
        // $then = strtotime('+2 weeks');

        // foreach ($courses as $course) {
        //     if ($course['timestamp'] > $now && $course['timestamp'] < $then) {
        //         $courses_filtered[] = $course;
        //     }
        // }

        $courses = get_forthcoming_courses_and_metadata();

        if (count($courses) !== 0) {

            echo $before_widget;

            if ( ! empty( $title ) )
                echo $before_title . $title . $after_title;

            foreach ($courses as $course) {
                $course_title = $course->post_title;
                $course_url = get_permalink( $course->ID );
                $course_next_event_date = get_next_event_date( $course );
                $thumbnail = get_the_post_thumbnail( $course->ID, array(390,0) );

                $location = get_short_address( $course->meta['_cmb_location'][0] );
                ?>

                <section class="course-summary">
                    <h4><a href="<?php echo $course_url; ?>"><?php echo $course_title; ?></a></h4>
                    <p>
                        <?= $course_next_event_date->format('jS F, g:ia'); ?>, at <?= $location; ?>
                    </p>
                    <?= $thumbnail; ?>
                </section>

            <?php }
            echo $after_widget;
        }
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Upcoming courses', 'text_domain' );
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

} // class Foo_Widget
add_action( 'widgets_init', create_function( '', 'register_widget( "TA_Calendar" );' ) );


/*
 * Adds TA_Social_Media widget.
 */
class TA_Social_Media extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'ta_social_media', // Base ID
            'Trad Academy Social Media links', // Name
            array( 'description' => __( 'Facebook something blah', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        // $fb = json_decode(file_get_contents('http://graph.facebook.com/197522990346703'));

        // $url = $fb->link;
        // $name = $fb->name;

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        // dump($fb);
        ?>
        <div class="fb-container">
            <a class="fb-link" href="https://www.facebook.com/tradacademy">The Trad Academy</a>
            <div class="fb-like" data-href="https://facebook.com/tradacademy" data-width="200" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
        </div>
        <?php echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Stay in touch', 'text_domain' );
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

} // class Foo_Widget
add_action( 'widgets_init', create_function( '', 'register_widget( "TA_Social_Media" );' ) );
