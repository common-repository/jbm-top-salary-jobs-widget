<?php
/*
Plugin Name: Job Board Manager - Top Salary Jobs Widget
Plugin URI: 
Description: Adds a widget that displays the highest salary jobs. You can choose between minimum, maximum and fixed salary types and the number of jobs to display. This plugin will only work with the <a href="https://wordpress.org/plugins/job-board-manager/">"Job Board Manager" plugin</a> by pickplugins.
Version: 1.0
Author: Danail Emandiev
Author URI: 
License: GPL3
*/

// Register and load the widget
function top_salary_widget_load() {
    register_widget( 'top_salary_widget' );
}
add_action( 'widgets_init', 'top_salary_widget_load' );
 
// Creating the widget 
class top_salary_widget extends WP_Widget {
function __construct() {
parent::__construct(
'top_salary_widget',
__('Highest Paying Jobs', 'top_paid_widget_domain'),
array( 'description' => __( 'Displays the highest salary jobs.', 'top_paid_widget_domain' ), ) 
);
}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$posts_per_page = apply_filters( 'widget_posts_per_page', $instance['posts_per_page'] );
$salary_type = apply_filters( 'widget_salary_type', $instance['salary_type'] );
$salary_currency = apply_filters( 'widget_salary_currency', $instance['salary_currency'] );
$link_text = apply_filters( 'widget_link_text', $instance['link_text'] );
$link_url = apply_filters( 'widget_link_url', $instance['link_url'] );
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
echo'<ul>';
$the_query = new WP_Query(
				array (
					'post_type' => 'job',
					'post_status' => 'publish',
					's' => $keywords,
					'orderby' => 'meta_value_num',
					'meta_key' => $salary_type,
					'order' => 'DESC',
					'posts_per_page' => $posts_per_page,
				) );
	while ($the_query -> have_posts()) : $the_query -> the_post();
	$job_bm_salary_min = get_post_meta(get_the_ID(), 'job_bm_salary_min', true);
	$job_bm_salary_max = get_post_meta(get_the_ID(), 'job_bm_salary_max', true);
	$job_bm_salary_fixed = get_post_meta(get_the_ID(), 'job_bm_salary_fixed', true);
?>
<li><a href="<?php the_permalink() ?>"><?php the_title();
	if ($salary_type == 'job_bm_salary_fixed')
		echo '<div>'.$salary_currency.$$salary_type.'</div></a></li>';
	else
	echo '<div>'.$salary_currency.$job_bm_salary_min.' - '.$salary_currency.$job_bm_salary_max.'</div></a></li>';
endwhile;
wp_reset_postdata();
?>
</ul>
<?php if ($link_text != '' && $link_url != '') {
echo '<a id="jbm-top-salary-link" href="'.$link_url.'">'.$link_text.'</a>';
}
echo $args['after_widget'];
}
         
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Top Paid Jobs', 'top_paid_widget_domain' );
}
if ( isset( $instance[ 'posts_per_page' ] ) )
$posts_per_page = $instance['posts_per_page'];
else
$posts_per_page = 5;
if ( isset( $instance[ 'salary_type' ] ) )
$salary_type = $instance['salary_type'];
else
$salary_type = 'job_bm_salary_min';
if ( isset( $instance[ 'salary_currency' ] ) )
$salary_currency = $instance['salary_currency'];
if ( isset( $instance[ 'link_text' ] ) )
$link_text = $instance['link_text'];
if ( isset( $instance[ 'link_url' ] ) )
$link_url = $instance['link_url'];


?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e( 'Number of jobs to display:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" />
</p>
<p>
<p><?php echo 'Salary Type:'; ?>
<select class="widefat" id="<?php echo $this->get_field_id( 'salary_type' ); ?>" name="<?php echo $this->get_field_name( 'salary_type' ); ?>">
    <option value="job_bm_salary_min" <?php if ($salary_type == 'job_bm_salary_min') echo 'selected="selected"';?>>Minimum</option>
    <option value="job_bm_salary_max" <?php if ($salary_type == 'job_bm_salary_max') echo 'selected="selected"';?>>Maximum</option>
    <option value="job_bm_salary_fixed" <?php if ($salary_type == 'job_bm_salary_fixed') echo 'selected="selected"';?>>Fixed</option>
</select>
</p>
<p>
<label for="<?php echo $this->get_field_id( 'salary_currency' ); ?>"><?php _e( 'Salary currency:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'salary_currency' ); ?>" name="<?php echo $this->get_field_name( 'salary_currency' ); ?>" type="text" value="<?php echo esc_attr( $salary_currency ); ?>" />
</p>
<p>
If you want you can add a link after the jobs.
<br>
For example a link to your jobs archive.
</p>
<p>
<label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e( 'Text for link:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" type="text" value="<?php echo esc_attr( $link_text ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php _e( 'URL for link:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'link_url' ); ?>" name="<?php echo $this->get_field_name( 'link_url' ); ?>" type="text" value="<?php echo esc_url( $link_url ); ?>" />
</p>
<?php 
}
     
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['posts_per_page'] = ( ! empty( $new_instance['posts_per_page'] ) ) ? strip_tags( $new_instance['posts_per_page'] ) : '';
$instance['salary_type'] = ( ! empty( $new_instance['salary_type'] ) ) ? strip_tags( $new_instance['salary_type'] ) : '';
$instance['salary_currency'] = ( ! empty( $new_instance['salary_currency'] ) ) ? strip_tags( $new_instance['salary_currency'] ) : '';
$instance['link_text'] = ( ! empty( $new_instance['link_text'] ) ) ? strip_tags( $new_instance['link_text'] ) : '';
$instance['link_url'] = ( ! empty( $new_instance['link_url'] ) ) ? strip_tags( $new_instance['link_url'] ) : '';
return $instance;
}
} // Class wpb_widget ends here