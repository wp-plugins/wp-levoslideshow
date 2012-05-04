<?php
global $wpdb, $glvo;
$ops = get_option('lvo_settings', array());
$query = "SELECT album_id, name, description, image, thumb, status, `order`, creation_date 
			FROM {$wpdb->prefix}lvo_albums
			ORDER BY `order` ASC";
$albums = $wpdb->get_results($query, ARRAY_A);
$cpage = 'admin.php?page='.$_REQUEST['page'];
?>
<div class="wrap">
	<h2><?php _e('Category Management'); ?><a href="javascript:;" id="add_new_album" class="add-new-h2"><?php _e('Add New'); ?></a></h2>
	<form id="add_new_album_form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="task" value="lvo_add_new_album" />
		<table>
		<tr>
			<td><label><?php _e('Category Name'); ?></label></td>
			<td><input type="text" id="album_name" name="album_name" value="" /></td>
		</tr>
		<tr>
			<td><label><?php _e('Category Description'); ?></label></td>
			<td><textarea id="album_desc" name="album_desc"></textarea></td>
		</tr>
		<tr>
			<td><label><?php _e('Category Image'); ?></label></td>
			<td><input type="file" name="album_img" value="" /></td>
		</tr>
		</table>
		<p>
			<button type="submit" class="button-primary"><?php _e('Save'); ?></button>
		</p>
		
	</form>
	<div class="dataTable_wrapper">
		<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
			<div id="album_images_length" class="dataTables_length">
				<label>Show 
					<select name="album_images_length" onclick="set_table_rows('albums_table', this.value, 'albums_pager');">
					<option value="5" selected>5</option>
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select> entries</label>
			</div>&nbsp;&nbsp;&nbsp;
			<?php lvo_get_table_actions(array('lvo_bulk_delete_albums' => 'Delete', 
											'lvo_bulk_disable_albums' => 'Disable', 
											'lvo_bulk_enable_albums' => 'Enable')); ?>
		</div>
		<table id="albums_table" class="widefat data-table">
		<thead>
		<tr>
			<th><input type="checkbox" class="select_all_images" name="select_albums" value="0"  /></th>
			<th><?php _e('ID'); ?></th>
			<th><?php _e('Thumb'); ?></th>
			<th><?php _e('Name'); ?></th>
			<th><?php _e('Description'); ?></th>
			<th><?php _e('Order'); ?></th>
			<th><?php _e('Actions'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if( empty($albums) ): ?>
		<tr><td colspan="7"><h4><?php _e('There are no albums yet'); ?></h4></td></tr>
		<?php else: ?>
		<?php require_once LVO_PLUGIN_DIR . '/html/albums_rows.php'; ?>
		<?php endif; ?>
		</tbody>
		</table>
		<div id="albums_pager" class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"></div>
	</div><!-- end class="dataTable_wrapper" -->
	<?php 
	if( isset($_REQUEST['view']) && isset($_REQUEST['album_id']))
	{
		
		$album_id = (int)$_REQUEST['album_id'];
		$album = $glvo->lvo_get_album($album_id);
		//print_r($album);
		$images = $glvo->lvo_get_album_images($album_id);
		//print_r($images); 
		$album['images'] = $wpdb->get_results($query, ARRAY_A);
		print '<h3>'.__('Manage images ('.@$album['name'].')') .'</h3>';
		if (!(strstr($_REQUEST['view'], '/')) && !(strstr($_REQUEST['view'], "\\"))) {
			require_once LVO_PLUGIN_DIR . '/html/' . $_REQUEST['view'] . '.php';
		}
	} 
	?>
</div>