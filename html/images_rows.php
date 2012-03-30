<?php if( count($images) <= 0 ): ?>
<tr>
	<td colspan="8"><h4><?php _e('No images found'); ?></h4></td>
</tr>
<?php else: foreach($images as $key => $img): ?>
<tr>
	<td><input type="checkbox" name="<?php print $album['album_id'] ?>" value="<?php print $img['image_id']; ?>" rel="" /></td>
	<td><?php print $img['image_id']; ?></td>
	<td><?php print $img['title']; ?></td>
	<td><img src="<?php print lvo_get_album_url($album['album_id']) . '/thumb/' . $img['thumb']; ?>" alt="" /></td>
	<td><?php print $img['description']; ?></td>
	<td><?php print $img['price']; ?></td>
	<td>
		<form action="" method="post" class="order_form">
			<input type="hidden" name="task" value="lvo_reorder_image" />
			<input type="hidden" name="album_id" value="<?php print $album['album_id']; ?>" />
			<input type="hidden" name="image_id" value="<?php print $img['image_id']; ?>" />
			<input type="text" name="img_order" value="<?php print $img['order']; ?>" class="image_order textbox_order" />
			<button type="submit" class="button-secondary"><?php _e('Save'); ?></button>
		</form>
	</td>
	<td>
		<?php if( @$img['status'] == 1): ?>
		<a class="disable image" href="<?php print $cpage; ?>&view=<?php print $_REQUEST['view']; ?>&task=lvo_disable_image&album_id=<?php print $album['album_id']; ?>&lvo_image_id=<?php print $img['image_id']; ?>">
			<?php _e('Disable'); ?>
		</a>&nbsp;
		<?php else: ?>
		<a class="enable image" href="<?php print $cpage; ?>&view=<?php print $_REQUEST['view']; ?>&task=lvo_enable_image&album_id=<?php print $album['album_id']; ?>&lvo_image_id=<?php print $img['image_id']; ?>">
			<?php _e('Enable'); ?>
		</a>&nbsp;
		<?php endif; ?>
		<a class="thickbox" 
			href="<?php print LVO_PLUGIN_URL; ?>/html/edit_image.php?album_id=<?php print $album['album_id']; ?>&lvo_image_id=<?php print $img['image_id']; ?>&KeepThis=true&TB_iframe=true&height=400&width=600">
			<?php _e('Edit'); ?>
		</a>&nbsp;
		<a href="<?php print $cpage; ?>&view=<?php print $_REQUEST['view']; ?>&task=lvo_delete_image&album_id=<?php print $album['album_id']; ?>&lvo_image_id=<?php print $img['image_id']; ?>" class="confirmation"><?php _e('Delete'); ?></a>&nbsp;
	</td>
</tr>
<?php endforeach; endif; ?>

