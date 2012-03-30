<?php
if( !isset($cpage) )
	$cpage = 'admin.php?page=levoslideshow_manage';
?>
<?php $i = 1; foreach($albums as $key => $album): ?>
<tr>
	<td><input type="checkbox" id="album_<?php print $album['album_id']; ?>" name="albums[]" value="<?php print $album['album_id']; ?>" /></td>
	<td><?php print $album['album_id']; ?></td>
	<td>
		<img src="<?php print lvo_get_album_url($album['album_id']) . '/thumb/' . $album['thumb'] ?>" alt="" />
	</td>
	<td>
		<?php print $album['name']; ?>
	</td>
	<td>
		<?php print $album['description']; ?>
	</td>
	<td>
		<form action="" method="post" class="order_form">
			<input type="hidden" name="task" value="lvo_reorder_album" />
			<input type="hidden" name="album_id" value="<?php print $album['album_id']; ?>" />
			<input type="text" name="album_order" value="<?php print $album['order']; ?>" class="image_order textbox_order" />
			<button type="submit" class="button-secondary"><?php _e('Save'); ?></button>
		</form>
	</td>
	<td>
		<?php if( $album['status'] == 1): ?>
		<a href="<?php print $cpage; ?>&task=lvo_disable_album&album_id=<?php print $album['album_id']; ?>" class="disable album"><?php _e('Disable'); ?></a>&nbsp;
		<?php else: ?>
		<a href="<?php print $cpage; ?>&task=lvo_enable_album&album_id=<?php print $album['album_id']; ?>" class="enable album"><?php _e('Enable'); ?></a>&nbsp;
		<?php endif; ?>
		<a class="thickbox" href="<?php print LVO_PLUGIN_URL; ?>/html/edit_album.php?album_id=<?php print $album['album_id']; ?>&KeepThis=true&TB_iframe=true&height=400&width=600"><?php _e('Edit'); ?></a>&nbsp;
		<a href="<?php print $cpage; ?>&view=manage_album&album_id=<?php print $album['album_id']; ?>"><?php _e('Manage Images'); ?></a>&nbsp;
		<a href="<?php print $cpage; ?>&task=lvo_delete_album&album_id=<?php print $album['album_id']; ?>" class="lvo_delete_album"><?php _e('Delete'); ?></a>&nbsp;
	</td>
</tr>
<?php $i++; endforeach; ?>