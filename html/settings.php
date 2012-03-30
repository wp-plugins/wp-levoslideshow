<?php
global $wpdb, $glvo;
$ops = get_option('lvo_settings', array());
//$ops = array_merge($lvo_settings, $ops);
?>
<div class="wrap">
	<h2><?php _e('Create XML File'); ?></h2>
	<form action="" method="post">
		<input type="hidden" name="task" value="save_lvo_settings" />
		<table>
		<tr>
			<td title="<?php _e('Width of slideshow .'); ?>"><?php _e('Slideshow Width (px)'); ?></td>
			<td><input type="text" name="settings[slideshow_width]"  value="<?php print @$ops['slideshow_width']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Height of slideshow .'); ?>"><?php _e('Slideshow Height (px)'); ?></td>
			<td><input type="text" name="settings[slideshow_height]"  value="<?php print @$ops['slideshow_height']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Enable/Disable autoslide option.'); ?>"><?php _e('Auto Slide'); ?></td>
			<td>
				<input type="radio" name="settings[auto_slide]" value="yes" <?php print (@$ops['auto_slide'] == 'yes') ? 'checked' : ''; ?>><span><?php _e('Yes'); ?></span>
				<input type="radio" name="settings[auto_slide]" value="no" <?php print (@$ops['auto_slide'] == 'no') ? 'checked' : ''; ?>><span><?php _e('no'); ?></span>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Slideshow Transition Time .'); ?>"><?php _e('Slideshow Transition Time'); ?></td>
			<td><input type="text" name="settings[transition_time]"  value="<?php print @$ops['transition_time']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Controls buttons X-Position.'); ?>"><?php _e('Controls X-Position'); ?></td>
			<td><input type="text" name="settings[control_xposition]"  value="<?php print @$ops['control_xposition']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Controls buttons Y-Position.'); ?>"><?php _e('Controls Y-Position'); ?></td>
			<td><input type="text" name="settings[control_yposition]"  value="<?php print @$ops['control_yposition']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Show/hide Image Reflection In Slideshow.'); ?>"><?php _e('Image Reflection'); ?></td>
			<td>
				<input type="radio" name="settings[is_reflection]" value="yes" <?php print (@$ops['is_reflection'] == 'yes') ? 'checked' : ''; ?>><span><?php _e('Yes'); ?></span>
				<input type="radio" name="settings[is_reflection]" value="no" <?php print (@$ops['is_reflection'] == 'no') ? 'checked' : ''; ?>><span><?php _e('no'); ?></span>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Gackground Gradient Color1.'); ?>"><?php _e('Gradient Color1'); ?></td>
			<td><input type="text" name="settings[bg_gradient1]" class="color {hash:false,caps:false}" value="<?php print @$ops['bg_gradient1']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Gackground Gradient Color2.'); ?>"><?php _e('Gradient Color2'); ?></td>
			<td><input type="text" name="settings[bg_gradient2]" class="color {hash:false,caps:false}" value="<?php print @$ops['bg_gradient2']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Header Font Color.'); ?>"><?php _e('Header Text Color'); ?></td>
			<td><input type="text" name="settings[header_fontcolor]" class="color {hash:false,caps:false}" value="<?php print @$ops['header_fontcolor']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Header Font Size.'); ?>"><?php _e('Header Text Size'); ?></td>
			<td><input type="text" name="settings[header_fontsize]"  value="<?php print @$ops['header_fontsize']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Description Text Font Color.'); ?>"><?php _e('Description Text Color'); ?></td>
			<td><input type="text" name="settings[desc_fontcolor]" class="color {hash:false,caps:false}" value="<?php print @$ops['desc_fontcolor']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Description Text Size.'); ?>"><?php _e('Description Text Size'); ?></td>
			<td><input type="text" name="settings[desc_fontsize]"  value="<?php print @$ops['desc_fontsize']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Big Image Width.'); ?>"><?php _e('Big Image Width'); ?></td>
			<td><input type="text" name="settings[bigimg_width]"  value="<?php print @$ops['bigimg_width']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Big Image Height.'); ?>"><?php _e('Big Image Height'); ?></td>
			<td><input type="text" name="settings[bigimg_height]"  value="<?php print @$ops['bigimg_height']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('which defines whether the Big image should be Yes to show its content or No to show it clipped.'); ?>"><?php _e('Big Image Scalling'); ?></td>
			<td>
				<select name="settings[bigimg_scalling]">
					<option value="yes" <?php print (@$ops['bigimg_scalling'] == 'yes') ? 'selected' : ''; ?>><?php _e('Yes'); ?></option>
					<option value="no" <?php print (@$ops['bigimg_scalling'] == 'no') ? 'selected' : ''; ?>><?php _e('No'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('which defines url link of the big image open in same window or in new window.'); ?>"><?php _e('Big Image url target link'); ?></td>
			<td>
				<select name="settings[big_target]">
					<option value="_blank" <?php print (@$ops['big_target'] == '_blank') ? 'selected' : ''; ?>><?php _e('New Window'); ?></option>
					<option value="_self" <?php print (@$ops['big_target'] == '_self') ? 'selected' : ''; ?>><?php _e('Same Window'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Small Image Width.'); ?>"><?php _e('Small Image Width'); ?></td>
			<td><input type="text" name="settings[smallimg_width]"  value="<?php print @$ops['smallimg_width']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Small Image Height.'); ?>"><?php _e('Small Image Height'); ?></td>
			<td><input type="text" name="settings[smallimg_height]"  value="<?php print @$ops['smallimg_height']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Small Image Starting position on horizontal line.'); ?>"><?php _e('Small Image X-Position'); ?></td>
			<td><input type="text" name="settings[smlImg_xposition]"  value="<?php print @$ops['smlImg_xposition']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('Small Image Starting position on vertical line.'); ?>"><?php _e('Small Image Y-Position'); ?></td>
			<td><input type="text" name="settings[smlImg_yposition]"  value="<?php print @$ops['smlImg_yposition']; ?>" /></td>
		</tr>
		<tr>
			<td title="<?php _e('which defines whether the Small image should be Yes to show its content or No to show it clipped.'); ?>"><?php _e('Small Image Scalling'); ?></td>
			<td>
				<select name="settings[smallimg_scalling]">
					<option value="yes" <?php print (@$ops['smallimg_scalling'] == 'yes') ? 'selected' : ''; ?>><?php _e('Yes'); ?></option>
					<option value="no" <?php print (@$ops['smallimg_scalling'] == 'no') ? 'selected' : ''; ?>><?php _e('No'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('which defines url link of the big image open in same window or in new window.'); ?>"><?php _e('Small Image url target link'); ?></td>
			<td>
				<select name="settings[small_target]">
					<option value="_blank" <?php print (@$ops['small_target'] == '_blank') ? 'selected' : ''; ?>><?php _e('New Window'); ?></option>
					<option value="_self" <?php print (@$ops['small_target'] == '_self') ? 'selected' : ''; ?>><?php _e('Same Window'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Wmode.'); ?>"><?php _e('Wmode'); ?></td>
			<td>
				<select name="settings[wmode]">
					<option value="window" <?php print (@$ops['wmode'] == 'window') ? 'selected' : ''; ?>><?php _e('Window'); ?></option>
					<option value="opaque" <?php print (@$ops['wmode'] == 'opaque') ? 'selected' : ''; ?>><?php _e('Opaque'); ?></option>
					<option value="transparent" <?php print (@$ops['wmode'] == 'transparent') ? 'selected' : ''; ?>><?php _e('Transparent'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Show/hide Big Image.'); ?>"><?php _e('Show big image'); ?></td>
			<td>
				<input type="radio" name="settings[show_bigimg]" value="yes" <?php print (@$ops['show_bigimg'] == 'yes') ? 'checked' : ''; ?>><span><?php _e('Yes'); ?></span>
				<input type="radio" name="settings[show_bigimg]" value="no" <?php print (@$ops['show_bigimg'] == 'no') ? 'checked' : ''; ?>><span><?php _e('no'); ?></span>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Show/hide Small Image.'); ?>"><?php _e('Show small image'); ?></td>
			<td>
				<input type="radio" name="settings[show_smlimg]" value="yes" <?php print (@$ops['show_smlimg'] == 'yes') ? 'checked' : ''; ?>><span><?php _e('Yes'); ?></span>
				<input type="radio" name="settings[show_smlimg]" value="no" <?php print (@$ops['show_smlimg'] == 'no') ? 'checked' : ''; ?>><span><?php _e('no'); ?></span>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Show/hide title.'); ?>"><?php _e('Show Title'); ?></td>
			<td>
				<input type="radio" name="settings[show_headertxt]" value="yes" <?php print (@$ops['show_headertxt'] == 'yes') ? 'checked' : ''; ?>><span><?php _e('Yes'); ?></span>
				<input type="radio" name="settings[show_headertxt]" value="no" <?php print (@$ops['show_headertxt'] == 'no') ? 'checked' : ''; ?>><span><?php _e('No'); ?></span>
			</td>
		</tr>
		<tr>
			<td title="<?php _e('Show/hide header text.'); ?>"><?php _e('Show Description'); ?></td>
			<td>
				<input type="radio" name="settings[show_desctxt]" value="yes" <?php print (@$ops['show_desctxt'] == 'yes') ? 'checked' : ''; ?>><span><?php _e('Yes'); ?></span>
				<input type="radio" name="settings[show_desctxt]" value="no" <?php print (@$ops['show_desctxt'] == 'no') ? 'checked' : ''; ?>><span><?php _e('no'); ?></span>
			</td>
		</tr>
		</table>
	<p><button type="submit" class="button-primary"><?php _e('Save Config'); ?></button></p>
	</form>
</div>