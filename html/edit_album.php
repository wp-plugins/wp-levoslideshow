<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
/** Load WordPress Administration Bootstrap */
$path = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once $path . '/wp-admin/admin.php';
//require_once $path . '/wp-admin/includes/screen.php';
//require_once $path . '/wp-admin/includes/plugin.php';
//require_once $path . '/wp-admin/includes/template.php';
//require_once $path . '/wp-admin/admin-header.php';
$album_id = (int)$_REQUEST['album_id'];
global $wpdb;
$query = "SELECT * FROM {$wpdb->prefix}lvo_albums WHERE album_id = $album_id LIMIT 1";
if( !($album = $wpdb->get_row($query, ARRAY_A)) )
{
	die('Album does not exists');
}
//print_r($album);
?>
<?php if( function_exists('_wp_admin_html_begin') ): _wp_admin_html_begin(); else:?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<?php endif;?>
	<title></title>
	<?php
	wp_enqueue_style( 'colors' );
	wp_enqueue_style( 'ie' );
	wp_enqueue_script('utils');
	wp_enqueue_script('jquery');
	do_action('admin_enqueue_scripts', $hook_suffix);
	do_action("admin_print_styles-$hook_suffix");
	do_action('admin_print_styles');
	do_action("admin_print_scripts-$hook_suffix");
	do_action('admin_print_scripts');
	do_action("lvo_admin_head-$hook_suffix");
	do_action('lvo_admin_head'); 
	?>
</head>
<body>
<div id="wpwrap">
	<!-- <div id="wpcontent">  -->
		<?php do_action('in_admin_header'); ?>		
		<div id="wpbody"><!-- <div id="wpbody-content">  -->
			<div class="wrap" style="padding: 12px;">
				<form id="edit_album_form" action="<?php print admin_url() ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="task" value="lvo_add_new_album" />
					<input type="hidden" name="TB_iframe" value="1" />
					<input type="hidden" name="album_id" value="<?php print $album['album_id']; ?>" />
					<table>
					<tr>
						<td><label><?php _e('Category Name'); ?></label></td>
						<td><input type="text" id="album_name" name="album_name" value="<?php print $album['name']; ?>" /></td>
					</tr>
					<tr>
						<td><label><?php _e('Category Description'); ?></label></td>
						<td><textarea id="album_desc" name="album_desc"><?php print $album['description']; ?></textarea></td>
					</tr>
					<tr>
						<td><label><?php _e('Category Image'); ?></label></td>
						<td>
							<input type="file" name="album_img" value="" />
							<img src="<?php print lvo_get_album_url($album['album_id']) . '/thumb/' . $album['thumb']; ?>" />
						</td>
					</tr>
					</table>
					<p>
						<button type="submit" class="button-primary"><?php _e('Save'); ?></button>
					</p>
					
				</form>
			</div>
		</div><!-- </div>  -->
		
	<!-- </div>  -->
</div>
<?php //include $path . '/wp-admin/admin-footer.php'; ?>
</body>
</html>