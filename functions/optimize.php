<?php
/* DISABLE LOGIN BY EMAIL */
/* ALLOW SVG FILES */
/* DISABLE GUTENBERG */
/* DISABLE XML-RPC */
/* REMOVE QUERY STRING FOR STATIC FILES */
/* REMOVE WP VERSION */
/* HIDE WP UPDATE */
/* REMOVE WP EMOJI SCRIPT */
/* REMOVE HEAD LINKS */
/* AUTO ASSIGN ALT TITLE TO MEDIA ON UPLOAD */
/* AVOID SPECIAL CHARACTERS FOR MEDIA ON UPLOAD */
/* DISABLE JSON REST API */
/* DISABLE JQUERY MIGRATE */
/* DEFER SOME SCRIPTS TO LOAD AT BOTTOM AND LEAVE BANWIDTH FOR LOADING */



/* DISABLE LOGIN BY EMAIL */
remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );

/* ALLOW SVG FILES */
function add_svg_to_upload_mimes( $upload_mimes ) {
	$upload_mimes['svg'] = 'image/svg+xml';
	$upload_mimes['svgz'] = 'image/svg+xml';
	return $upload_mimes;
}
add_filter( 'upload_mimes', 'add_svg_to_upload_mimes', 10, 1 );

/* DISABLE GUTENBERG */
add_filter('use_block_editor_for_post', '__return_false');

/* DISABLE XML-RPC */
add_filter('xmlrpc_enabled', '__return_false');

/* REMOVE QUERY STRING FOR STATIC FILES */
function remove_cssjs_ver( $src ) {
if( strpos( $src, '?ver=' ) )
$src = remove_query_arg( 'ver', $src );
return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );


/* REMOVE WP VERSION */
function wpb_remove_version() {
return '';
}
add_filter('the_generator', 'wpb_remove_version');

/* HIDE WP UPDATE */
function hide_update_notice_to_all_but_admin() {
    if ( !current_user_can( 'update_core' ) ) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}
add_action( 'admin_head', 'hide_update_notice_to_all_but_admin', 1 );


/* REMOVE WP EMOJI SCRIPT */
function ah_disable_emoji_dequeue_script() {
    wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'ah_disable_emoji_dequeue_script', 100 );


/* REMOVE HEAD LINKS */
add_action('init', 'ah_remheadlink');
function ah_remheadlink()
	{
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	//remove_action('wp_head', 'feed_links', 2);
	//remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}

/* AUTO ASSIGN ALT TITLE TO MEDIA ON UPLOAD */
function dcwd_title_to_words( $title ) {
	// Sanitize the title:  remove hyphens, underscores & extra spaces:
	$title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $title );
	// Sanitize the title:  capitalize first letter of every word (other letters lower case):
	$title = ucwords( strtolower( $title ) );
	return $title;
}
// Copied from: https://brutalbusiness.com/automatically-set-the-wordpress-image-title-alt-text-other-meta/
add_action( 'add_attachment', 'dcwd_set_image_meta_upon_image_upload' );
function dcwd_set_image_meta_upon_image_upload( $post_ID ) {
	// Check if uploaded file is an image, else do nothing
	if ( wp_attachment_is_image( $post_ID ) ) {
		$my_image_title = get_post( $post_ID )->post_title;
		$my_image_title = dcwd_title_to_words( $my_image_title );
		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID' => $post_ID,			// Specify the image (ID) to be updated
			'post_title' => $my_image_title,		// Set image Title to sanitized title
			// Damien: Omit setting the caption as I rarely use captions when I insert images.
			//'post_excerpt' => $my_image_title,		// Set image Caption (Excerpt) to sanitized title
			'post_content' => $my_image_title,		// Set image Description (Content) to sanitized title
		);
		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );
		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );
	}
}
// Enhanced version of: https://wordpress.org/plugins/automatic-image-alt-attributes/
add_filter('image_send_to_editor', 'dcwd_auto_alt_fix_1', 10, 2);
function dcwd_auto_alt_fix_1($html, $id) {
	$image_title = get_the_title( $id );
	$image_title = dcwd_title_to_words( $image_title );
	return str_replace('alt=""','alt="' . $image_title . '"',$html);
}
add_filter('wp_get_attachment_image_attributes', 'dcwd_auto_alt_fix_2', 10, 2);
function dcwd_auto_alt_fix_2($attributes, $attachment){
	if ( !isset( $attributes['alt'] ) || '' === $attributes['alt'] ) {
		$attributes['alt'] = dcwd_title_to_words( get_the_title( $attachment->ID ) );
	}
	return $attributes;
}
// From: https://mekshq.com/change-image-alt-tag-in-wordpress/
/* Replace alt attribute of post thumbnail with post title */
add_filter( 'post_thumbnail_html', 'meks_post_thumbnail_alt_change', 10, 5 );
function meks_post_thumbnail_alt_change( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	$post_title = get_the_title();
	$html = preg_replace( '/(alt=")(.*?)(")/i', '$1'.esc_attr( $post_title ).'$3', $html );
	return $html;

}

/* AVOID SPECIAL CHARACTERS FOR MEDIA ON UPLOAD */
function wpartisan_sanitize_file_name( $filename ) {

    $sanitized_filename = remove_accents( $filename ); // Convert to ASCII
    // Standard replacements
    $invalid = array(
        ' '   => '-',
        '%20' => '-',
        '_'   => '-',
    );
    $sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );

    $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
    $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
    $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
    $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
    $sanitized_filename = strtolower( $sanitized_filename ); // Lowercase

    return $sanitized_filename;
}

add_filter( 'sanitize_file_name', 'wpartisan_sanitize_file_name', 10, 1 );


/* DISABLE JSON REST API */
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');


/* DISABLE JQUERY MIGRATE */
add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});



/* DEFER SOME SCRIPTS TO LOAD AT BOTTOM AND LEAVE BANWIDTH FOR LOADING */
/*
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);
function add_defer_attribute($tag, $handle) {
   // add script handles to the array below
   $scripts_to_defer = array('aos-css','fancybox-css','fontawesome-5-css', 'swiper-css','swiper-js','aos-js','custom-script');

   foreach($scripts_to_defer as $defer_script) {
      if ($defer_script === $handle) {
         return str_replace(' src', ' defer="defer" src', $tag);
      }
   }
   return $tag;
}
*/


//REMOVE COMMENTS
function comments_clean_header_hook(){
 wp_deregister_script( 'comment-reply' );
 }
add_action('init','comments_clean_header_hook');
