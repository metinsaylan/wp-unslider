<?php 

class WP_Unslider_Admin{
	function WP_Unslider_Admin() {	
	
		$this->pluginname = "WP Unslider";
		$this->shortname = "wp_unslider";
		$this->settings_key = "wp_unslider";
		$this->options_page = "wp-unslider";
		$this->version = "0.1";
		
		// Include options
		//require_once("default-options.php");
		//$this->options = $options;
		//$this->settings = $this->get_plugin_settings();
		
		$this->help_url = "http://shailan.com/wordpress/plugins/wp-unslider/help/";
		
		add_action( 'wp_head', array(&$this, 'header') );
		add_action( 'wp_footer', array(&$this, 'footer') );		
		add_action( 'admin_menu', array( &$this, 'WP_Unslider_admin_menu') );
	}
	
	function get_plugin_settings(){
		$settings = get_option( $this->settings_key );		
		
		if(FALSE === $settings){ // Options doesn't exist, install standard settings
			// Create settings array
			$settings = array();
			// Set default values
			foreach($this->options as $option){
				if( array_key_exists( 'id', $option ) )
					$settings[ $option['id'] ] = $option['std'];
			}
			
			// Move adsense id option to new settings
			$adsid = get_option('shailan_adsense_id');
			if(!empty($adsid)){ $settings['adsense_id'] = $adsid; }
			
			$settings['version'] = $this->version;
			// Save the settings
			update_option( $this->settings_key, $settings );
		} else { // Options exist, update if necessary
			
			if( !empty( $settings['version'] ) ){ $ver = $settings['version']; } 
			else { $ver = ''; }
			
			if($ver != $this->version){ // Update needed
			
				// Move adsense id option to new settings
				$adsid = get_option('shailan_adsense_id');
				if(!empty($adsid)){ $settings['adsense_id'] = $adsid; }
			
				// Add missing keys
				foreach($this->options as $option){
					if( array_key_exists ( 'id' , $option ) && !array_key_exists ( $option['id'] ,$settings ) ){
						$settings[ $option['id'] ] = $option['std'];
					}
				}
				
				update_option( $this->settings_key, $settings );
				
				return $settings; 
			} else { 
			
				// Move adsense id option to new settings
				$adsid = get_option('shailan_adsense_id');
				if( !empty($adsid) ){
					$settings['adsense_id'] = $adsid; 
					update_option( $this->settings_key, $settings );
					delete_option('shailan_adsense_id');
				}
			
				// Everythings gonna be alright. Return.
				return $settings;
			} 
		}		
	}
	
	function update_plugin_setting( $key, $value ){
		$settings = $this->get_plugin_settings();
		$settings[$key] = $value;
		update_option( $this->settings_key, $settings );
	}
	
	function get_plugin_setting( $key, $default = '' ) {
		$settings = $this->get_plugin_settings();
		if( array_key_exists($key, $settings) ){
			return $settings[$key];
		} else {
			return $default;
		}
		
		return FALSE;
	}

	function WP_Unslider_admin_menu(){

		if ( @$_GET['page'] == $this->options_page ) {		
		
			// Enqueue scripts & styles
			wp_enqueue_script( "jquery" );
			wp_enqueue_style( "wp-unslider-admin", plugins_url( '/css/wp-unslider-admin.css' , __FILE__ ), false, "1.0", "all");	
			wp_enqueue_style( "google-droid-sans", "http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold&v1", false, "1.0", "all");	
			
			if ( @$_REQUEST['action'] && 'save' == $_REQUEST['action'] ) {
			
				// Save settings
				// Get settings array
				$settings = $this->get_settings();
				
				// Set updated values
				foreach($this->options as $option){					
					if( $option['type'] == 'checkbox' && empty( $_REQUEST[ $option['id'] ] ) ) {
						$settings[ $option['id'] ] = 'off';
					} else {
						$settings[ $option['id'] ] = $_REQUEST[ $option['id'] ]; 
					}
				}
				
				// Save the settings
				update_option( $this->settings_key, $settings );
				header("Location: admin.php?page=" . $this->options_page . "&saved=true&message=1");
				die;
			} else if( @$_REQUEST['action'] && 'reset' == $_REQUEST['action'] ) {
				
				// Start a new settings array
				$settings = array();
				delete_option( $this->settings_key ); 	
				header("Location: admin.php?page=" . $this->options_page . "&reset=true&message=2");
				die;
			}
			
		}

		$page = add_options_page( 
			__('Settings for WP Unslider', 'wp-unslider'),
			__('WP Unslider', 'wp-unslider'), 
			'edit_themes',
			$this->options_page,
			array( &$this, 'options_page') 
		);
		
		add_action( 'admin_print_styles-' . $page, array( &$this, 'header' ) );
	}
	
	function options_page(){
		global $options, $current;

		$title = "WP Unslider Options";
		
		$options = $this->options;	
		$current = $this->get_plugin_settings();
		
		$messages = array( 
			"1" => __("Dropdown Menu Widget settings saved.", 'wp-unslider'),
			"2" => __("Dropdown Menu Widget settings reset.", 'wp-unslider')
		);
		
		$navigation = '<div id="stf_nav"><a href="http://shailan.com/wordpress/plugins/dropdown-menu/">Plugin page</a> | <a href="http://shailan.com/wordpress/plugins/dropdown-menu/help/">Usage</a> | <a href="http://shailan.com/donate/">Donate</a> | <a href="http://shailan.com/wordpress/plugins/">Get more widgets..</a></div>
		
	<div class="stf_share">
		<div class="share-label">
			Like this plugin? 
		</div>
		<div class="share-button tweet">
			<a href="http://twitter.com/share" class="twitter-share-button" data-url="http://shailan.com/wordpress/plugins/dropdown-menu/" data-text="I am using #dropdownmenu #widget by shailan on my #wordpress blog, Check this out!" data-count="horizontal" data-via="shailancom">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		</div>
		<div class="share-button facebook">
			<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
			<fb:like href="http://shailan.com/wordpress/plugins/dropdown-menu/" ref="plugin_options" show_faces="false" width="300" font="segoe ui"></fb:like>
		</div>
	</div>
		
		';
		
		$footer_text = '<em><a href="http://shailan.com/wordpress/plugins/dropdown-menu/">Dropdown Menu Widget</a> by <a href="http://shailan.com/">SHAILAN</a></em>';
		
		include_once( "stf-page-options.php" );

	}
		
	function header(){
		wp_enqueue_style( "wp-unslider", plugins_url( '/unslider.css' , __FILE__ ) , false, "1.0", "all");	
		
	
	}
	
	function footer(){
		wp_enqueue_script( "jquery" );
		wp_enqueue_script( "jQuery.event.swipe", plugins_url( '/jquery.event.swipe.js' , __FILE__ ), array( "jquery" ) );
		wp_enqueue_script( "wp-unslider", plugins_url( '/unslider.min.js' , __FILE__ ), array( "jquery", "jQuery.event.swipe" ) );
	}
	
	function help_link($key, $text = '(?)'){
		echo '<a href="'.$this->help_url.'#' . $key. '" target="_blank" class="help-link">' . $text . '</a>';
	}
}

function get_wp_unslider_setting( $key, $default = '' ) {
	$settings = get_option('WP_Unslider');
	
	if( array_key_exists($key, $settings) ){
		return $settings[ $key ];
	} else {
		return $default;
	}
	
	return FALSE;
}

// Load translations
$plugin_dir = basename( dirname(__FILE__) );
load_plugin_textdomain( 'wp-unslider', false, $plugin_dir . '/lang');

global $WP_Unslider;
$WP_Unslider = new WP_Unslider_Admin();

// After activation redirect
register_activation_hook(__FILE__, 'wp_unslider_activate');
add_action('admin_init', 'wp_unslider_redirect');

function wp_unslider_activate() {
    add_option('wp_unslider_do_activation_redirect', true);
}

// Redirects to options page on activate
function wp_unslider_redirect() {
    if ( get_option( 'wp_unslider_do_activation_redirect', false ) ) {
        delete_option('wp_unslider_do_activation_redirect');
		$url = admin_url( 'options-general.php?page=wp-unslider' );
        wp_redirect( $url );
    }
}