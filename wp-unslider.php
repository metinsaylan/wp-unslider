<?php 
/*
Plugin Name: WP-Unslider
Plugin URI: http://shailan.com/wordpress/plugins/wp-unslider/
Description: Lightweight, responsive, jQuery powered slider for WordPress.
Version: 0.1
Author: Matt Say
Author URI: http://shailan.com/
*/

global $WP_Unslider;

include_once('wp-unslider-plugin.php');
include_once('wp-unslider-custom-post-types.php');
include_once('wp-unslider-metabox.php');

function wp_unslider( $args = '' ){
	
	$defaults = array(
		'slider_id' => 'slider' . rand(30, 150),
		'numberposts' => 10,
		'speed' => 500,
		'delay' => 10000,
		'keys' => true,
		'dots' => true,
		'fluid' => true
	);
	
	$options = wp_parse_args( $args, $defaults );	
	extract( $options, EXTR_SKIP );
	
	if($keys == 1){ $keys = 'true'; } else { $keys = 'false'; }
	if($dots == 1){ $dots = 'true'; } else { $dots = 'false'; }
	if($fluid == 1){ $fluid = 'true'; } else { $fluid = 'false'; }
	
	$template = locate_template( 'wp-unslider-slide.php', false, false );
	if( '' == $template ){
		$template = 'wp-unslider-slide.php';
	}

	?>
	<div class="wp-unslider-container">
		<div id="<?php echo $slider_id; ?>" class="wp-unslider">
			<ul class="wp-unslider-slide-list">
				<!-- Slides -->
				<?php 
				// Get slides
					
				global $post, $pool;
				$pool = (array) $pool;
				$args = array( 
					'post_type' => 'slide', 
					'orderby' => 'menu_order', // This ensures images are in the order set in the page media manager
					'numberposts' => $numberposts 
				);
				
				$slides = get_posts( $args );
				
				foreach( $slides as $post ){
					setup_postdata( $post ); 
					$pool[] = get_the_ID(); 
					include( $template );
				}
				wp_reset_postdata();
				?>			
				<!-- End of Slides -->
			</ul>
			<!-- The HTML -->
			<a href="#" class="wp-unslider-arrow prev" onclick="return false;">Previous slide</a>
			<a href="#" class="wp-unslider-arrow next" onclick="return false;">Next slide</a>
		</div>
	</div>
	<script type="text/javascript">
	jQuery(function($) {		
		var <?php echo $slider_id; ?> = $('#<?php echo $slider_id; ?>').unslider({
			speed: <?php echo $speed; ?>,               //  The speed to animate each slide (in milliseconds)
			delay: <?php echo $delay; ?>,              //  The delay between slide animations (in milliseconds)
			//complete: function() {},  //  A function that gets called after every slide animation
			keys: <?php echo $keys; ?>,               //  Enable keyboard (left, right) arrow shortcuts
			dots: <?php echo $dots; ?>,               //  Display dot navigation
			fluid: <?php echo $fluid; ?>              //  Support responsive design. May break non-responsive designs
		});
		// <?php echo $slider_id; ?>.data('unslider').dots();
    
		$('.wp-unslider-arrow').click(function() {
			var fn = this.className.split(' ')[1];
			$(this).parent().data('unslider')[fn]();
		});
	});
	</script>
	<?php
}

// Settings link
function wp_unslider_add_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=wp-unslider">Settings</a>';
    array_push( $links, $settings_link );
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$plugin", 'wp_unslider_add_settings_link' );