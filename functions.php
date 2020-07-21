<?php

//INCLUDE / REQUIRE
require_once( __DIR__ . '/functions/admin.php');
require_once( __DIR__ . '/functions/optimize.php');
//require_once( __DIR__ . '/functions/google.php');
require_once( __DIR__ . '/functions/acf.php');
//require_once( __DIR__ . '/functions/woocommerce.php');


/***  LOAD SCRIPTS  ****/
function theme_enqueue_scripts() {

	$template_url = get_stylesheet_directory_uri();

	//LOAD NATIVE JQUERY
	wp_enqueue_script( 'jquery' );
	//De-register WP Jquery and use latest from CDN
	//wp_deregister_script('jquery');
	//wp_register_script('jquery', 'https://code.jquery.com/jquery-3.2.1.min.js', false, '3.2.1');
	//wp_enqueue_script('jquery');

	//Main Style
	wp_enqueue_style( 'main-style', get_stylesheet_uri() );

  wp_enqueue_style( 'gulp-stylesheet', $template_url . '/dist/css/bundle.css', array(), '1.0.0', 'all' );
  wp_enqueue_script( 'gulp-javascript', $template_url . '/dist/js/bundle.js', array(), '1.0.0', 'all' );

}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );

/*
// LOAD CSS IN ADMIN AREA
function admin_screen_css() {

  $template_url = get_template_directory_uri();
	wp_enqueue_style( 'admin-css', $template_url . '/css/admin.css' );

}
add_action( 'admin_enqueue_scripts', 'admin_screen_css');
*/

//POST/PAGE tnative humbnails support
//add_theme_support( 'post-thumbnails' );
//add_image_size( 'sidebar-thumb', 120, 120, true ); // Hard Crop Mode
//add_image_size( 'homepage-thumb', 220, 180 ); // Soft Crop Mode
//add_image_size( 'singlepost-thumb', 590, 9999 ); // Unlimited Height Mode
//use it on template  the_post_thumbnail( 'sidebar-thumb' );



//PHP LIMITS
@ini_set( 'upload_max_size' , '128M' );
@ini_set( 'post_max_size', '128M');
@ini_set( 'max_execution_time', '300' );


/* REGISTER MENU */
function register_my_menus() {
  register_nav_menus(
    array(
      'main_menu' => __( 'Main Menu' ),
      'mobile_menu' => __( 'Mobile Menu' ),
      'footer_menu' => __( 'Footer Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );


/* EXTEND MENU WALKER CLASS */
class Main_Menu_Sublevel_Walker extends Walker_Nav_Menu
{
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='sub-menu'>\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

class Mobile_Main_Menu_Sublevel_Walker extends Walker_Nav_Menu
{
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='mobile-sub-menu'>\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}


/* AUTO COPYRIGHT DATE <?php echo wpb_copyright(); ?> */
function wpb_copyright() {
global $wpdb;
 $copyright_dates = $wpdb->get_results("
 SELECT
 YEAR(min(post_date_gmt)) AS firstdate,
 YEAR(max(post_date_gmt)) AS lastdate
 FROM
 $wpdb->posts
 WHERE
 post_status = 'publish'
 ");
    $output = '';
   if($copyright_dates) {
      $copyright = "© " . $copyright_dates[0]->firstdate;
   if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
      $copyright .= '-' . $copyright_dates[0]->lastdate;
    }
    $output = $copyright;
    }
    return $output;
}


// DISABLE SEARCH FUNCTION AT ALL
function fb_filter_query( $query, $error = true ) {

if ( is_search() ) {
$query->is_search = false;
$query->query_vars[s] = false;
$query->query[s] = false;

// to error
if ( $error == true )
$query->is_404 = true;
}
}

add_action( 'parse_query', 'fb_filter_query' );
add_filter( 'get_search_form', create_function( '$a', "return null;" ) );



//PREVENT MEDIA LIBRARY TO ORGANIZE MEDIA FILES INSIDE DATE FOLDERS
//GLUP IS DOING OPTIMIZATION THE IMAGES
function media_library_upload_option() {
  update_option('uploads_use_yearmonth_folders', 0);
}
add_action('after_setup_theme', 'media_library_upload_option');



//HTML COMPRESSION - FUNCTIN CALLED FROM OPTIMIZE.PHP FILE
add_action('get_header', 'wp_html_compression_start');
function wp_html_compression_start()
{
    ob_start('wp_html_compression_finish');
}
function wp_html_compression_finish($html)
{
    return new WP_HTML_Compression($html);
}
