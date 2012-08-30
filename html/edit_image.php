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
global $wpdb, $glvo;
$album_id = (int)$_REQUEST['album_id'];
$image_id = (int)$_REQUEST['lvo_image_id'];
if( !($album = $glvo->lvo_get_album($album_id)) )
{
	die('Album id does not exists');
}
if( !($image = $glvo->get_image($image_id)) )
{
	die('Invalid image id');	
}

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
				<h2 style="margin-left: 5px !important;"><?php _e('Edit Image'); ?></h2>
				<form id="edit_album_form" action="<?php print admin_url() ?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="task" value="lvo_single_image_upload" />
					<input type="hidden" name="TB_iframe" value="1" />
					<input type="hidden" name="album_id" value="<?php print $album_id; ?>" />
					<input type="hidden" name="image_id" value="<?php print $image_id; ?>" />
					<input type="hidden" name="image_thumb" value="generate" />
					<table>
					<tr>
						<td><label><?php _e('Image Title'); ?></label></td>
						<td><input type="text" id="image_title" name="image_title" value="<?php print $image['title']; ?>" /></td>
					</tr>
					<tr>
						<td><label><?php _e('Image Description'); ?></label></td>
						<td><textarea id="image_description" name="image_description"><?php print $image['description']; ?></textarea></td>
					</tr>
					<tr>
						<td><label><?php _e('Image Link'); ?></label></td>
						<td><input type="text" id="image_link" name="image_link" value="<?php print @$image['link']; ?>" /></td>
					</tr>
					<tr>
						<td><label><?php _e('Thumb Link'); ?></label></td>
						<td><input type="text" id="thumb_link" name="thumb_link" value="<?php print @$image['thumb_link']; ?>" /></td>
					</tr>
					<tr>
						<td><label><?php _e('Image file'); ?></label></td>
						<td>
							<input type="file" name="image_file" value="" />
							<img src="<?php print lvo_get_album_url($album_id) . '/thumb/' . $image['thumb']; ?>" />
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