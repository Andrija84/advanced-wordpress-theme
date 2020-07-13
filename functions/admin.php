<?php
/* REMOVES ADMIN TOP FROM FRONT */
/* REMOVES WELCOME DASH PANEL */
/* REMOVE ADMIN DASHBOARD WIDGETS */
/* ADD CUSTOM HELP DASH WIDGET */
/* REMOVE THANK YOU NOTIFICATION FROM DASHBOARD */
/* REMOVE VERSION FROM DASHBOARD */
/* CUSTOM ADMIN LOGIN LOGO */
/* REDIRECT ADMIN LOGO TO HOME */
/* COMPLETE REMOVE COMMENTS */
/* IF NEEDED CRATE ADMIN USER */

/* REMOVES ADMIN TOP FROM FRONT */
show_admin_bar( false );

/* REMOVES WELCOME DASH PANEL */
remove_action('welcome_panel', 'wp_welcome_panel');

/* REMOVE ADMIN DASHBOARD WIDGETS */
add_action( 'admin_init', 'remove_dashboard_meta' );
function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}

/* ADD CUSTOM HELP WIDGET */
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');
}
 function custom_dashboard_help() {
echo '<p>Welcome to OOMPA Design custom Wordpress theme! Need help? Contact the developer <a href="mailto:andrija.nikolic@oompa.de">here</a>. For more details visit: <a href="https://oompa.de" target="_blank">OOMPA Design</a></p>';
}

/* REMOVE THANK YOU NOTIFICATION FROM DASHBOARD */
add_filter('admin_footer_text', 'remove_footer_admin');
function remove_footer_admin (){
    echo '<span id="footer-thankyou">Developed by <a href="https://oompa.de" target="_blank">Andrija Nikolic @ OOMPA Design</a></span>';
}

/* REMOVE VERSION FROM DASHBOARD */
function my_footer_shh() {
    remove_filter( 'update_footer', 'core_update_footer' );
}
add_action( 'admin_menu', 'my_footer_shh' );

/* CUSTOM ADMIN LOGIN LOGO */
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/rhein_agro_pharm_logo.png);
		background-size: 100% 100%;
		background-repeat: no-repeat;
    height:120px;
    width:95px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/* REDIRECT ADMIN LOGO TO HOME */
function custom_loginlogo_url($url) {
	return esc_url( home_url( '/' ) );
}
add_filter( 'login_headerurl', 'custom_loginlogo_url' );


/* COMPLETE REMOVE COMMENTS */
function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'my_remove_admin_menus' );

// Removes from post and pages
function remove_comment_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}
add_action( 'init', 'remove_comment_support', 100 );

// Removes from admin bar
function mytheme_admin_bar_render() {
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu( 'comments' );
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );


/* IF NEEDED CRATE ADMIN USER */
/*
function wpb_admin_account(){
$user = 'Username';
$pass = 'Password';
$email = 'email@domain.com';
if ( !username_exists( $user )  && !email_exists( $email ) ) {
$user_id = wp_create_user( $user, $pass, $email );
$user = new WP_User( $user_id );
$user->set_role( 'administrator' );
} }
add_action('init','wpb_admin_account');
*/
