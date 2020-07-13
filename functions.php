<?php

require_once( __DIR__ . '/functions/admin.php');
require_once( __DIR__ . '/functions/optimize.php');
//require_once( __DIR__ . '/functions/google.php');
require_once( __DIR__ . '/functions/acf.php');
//require_once( __DIR__ . '/functions/woocommerce.php');





/***  LOAD SCRIPTS  ****/
function theme_enqueue_scripts() {

	$template_url = get_stylesheet_directory_uri();

	//jQuery.
	wp_enqueue_script( 'jquery' );
	//De-register WP Jquery and use latest from CDN
	//wp_deregister_script('jquery');
	//wp_register_script('jquery', 'https://code.jquery.com/jquery-3.2.1.min.js', false, '3.2.1');
	//wp_enqueue_script('jquery');

	//Main Style
	wp_enqueue_style( 'main-style', get_stylesheet_uri() );

	wp_enqueue_style( 'gulp-stylesheet', $template_url . '/dist/css/bundle.css', array(), '1.0.0', 'all' );


	//CSS
	//wp_enqueue_style( 'materialize-css', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css' );
	//wp_enqueue_style( 'swiper-css',$template_url . '/css/swiper.css' );
	//wp_enqueue_style( 'fontawesome-5-css', $template_url . '/css/fontawesome_5_pro_all.min.css' );
    //wp_enqueue_style( 'fancybox-css', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css' );
	//wp_enqueue_style( 'aos-css', $template_url . '/css/aos.css' );
	//wp_enqueue_style( 'burger-menu', $template_url . '/css/burger_menu.css' );
	//wp_enqueue_style( 'media_query', $template_url . '/css/media_query.css' );

    //JS
	//wp_enqueue_script( 'fontawesome-5-js',  $template_url .'/js/fontawesome_5_pro_light.min.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'fancybox-script', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js', array( 'jquery' ), null, false );
	//p_enqueue_script( 'nicescroll-js',  $template_url .'/js/jquery.nicescroll.min.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'sticky-header', $template_url . '/js/sticky.header.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'scroll-reveal', $template_url . '/js/scrollReveal.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'materialize-js', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js', array( 'jquery' ), null, true );

	//wp_enqueue_script( 'swiper-js', $template_url . '/js/swiper.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'aos-js', $template_url . '/js/aos.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'custom-gmap', $template_url . '/js/custom-gmap.js', array( 'jquery' ), null, true );
	//wp_enqueue_script( 'custom-script', $template_url . '/js/custom.js', array( 'jquery' ), null, true );



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



//Increase php limits
@ini_set( 'upload_max_size' , '128M' );
@ini_set( 'post_max_size', '128M');
@ini_set( 'max_execution_time', '300' );


/* REGISTER MENU */
function register_my_menus() {
  register_nav_menus(
    array(
      'main_menu' => __( 'Main Menu' ),
      'footer_menu' => __( 'Footer Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );


/*EXTEND MENU WALKER CLASS */
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




/* DISABLE SEARCH FUNCTION AT ALL */
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



//Prevent default media uploads by year/month
function media_library_upload_option() {
  update_option('uploads_use_yearmonth_folders', 0);

}

add_action('after_setup_theme', 'media_library_upload_option');




