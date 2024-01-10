<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$understrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/jetpack.php',                         // Load Jetpack compatibility file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker.
	'/woocommerce.php',                     // Load WooCommerce functions.
	'/editor.php',                          // Load Editor functions.
);

foreach ( $understrap_includes as $file ) {
	$filepath = locate_template( 'inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}

	
register_nav_menu( 'secondary', __('Footer Navigation') );

// Deactivate Update of VSEL
function op_remove_update_vc($value) {
    unset($value->response['very-simple-event-list/vsel.php']);
	return $value;
}
add_filter('site_transient_update_plugins', 'op_remove_update_vc');


// Blende Theme updates aus.
function hide_theme_updates($a) {
    global $wp_version;
    return (object) array ('last_checked' => time(), 'version_checked' => $wp_version, );
}
add_filter ('pre_site_transient_update_themes', 'hide_theme_updates');


// Welches Template wird auf der Seite benutzt:
/*
add_filter('template_include', 'debug_template_use' );
function debug_template_use($template) {
 
  var_dump($template);
 
  // Important: return the default setting  
  return $template;
}
*/



// Remove Wordpress Version
function no_generator() { return ''; } 
add_filter( 'the_generator', 'no_generator' ); 



// Remove Dashboard Menus
function remove_menus () { 
    global $menu;
    $restricted = array(__('Comments'), __('Posts'), __('Links')); 
    end ($menu); 
     
    while (prev($menu)){ 
        $value = explode(' ',$menu[key($menu)][0]); 
        if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);} 
    } 
} 
add_action('admin_menu', 'remove_menus');



// Styling für die Loginseite
add_action( 'login_enqueue_scripts', 'login_logo' );
function login_logo() { 
    ?>
    <style type="text/css">
    #login h1 a, .login h1 a {background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/logo3.png);background-size: 100%;height: 110px;width: 120px;}
    .login .message {border-left: 4px solid #77B92C!important;}
    .login.wp-core-ui .button-primary {background: #77B92C!important; border-color: #92e039 #65a021 #6bad21!important; box-shadow: 0 1px 0 #65a021!important;text-shadow: 0 -1px 1px #65a021, 1px 0 1px #65a021, 0 1px 1px #65a021, -1px 0 1px #65a021!important;}
    .login input[type=checkbox]:checked:before, .login a:hover {color: #77B92C!important;}
    .login input:focus,.login h1 a:focus {border-color: #77B92C!important; box-shadow: 0 0 2px rgba(255,153,0,.8)!important;}
    .login a:focus {box-shadow: none!important;}
    </style>
   <?php 
}


// Neue Bildgröße hinzufügen
if ( ! function_exists( 'theme_slug_setup' ) ) :
    function theme_slug_setup() {
        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );

        // Add custom image size for single posts.
        add_image_size( 'custom-thumbnail', 80, 80, array( 'center', 'top' ) );
    }
endif;
add_action( 'after_setup_theme', 'theme_slug_setup' );


// Remove Emoji Script
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Remove WP oEmbed
function my_deregister_scripts(){
	wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

// Remove the wp-block-library.css
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'vsel_style' );
});

remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );

// Rest API wird für ContactForm7 benötigt, darum nicht entfernen
// add_filter( 'rest_endpoints', 'remove_default_endpoints' );
//function remove_default_endpoints( $endpoints ) {
//  return array( );
//}
