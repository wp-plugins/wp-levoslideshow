<?php
function lvo_get_def_settings()
{
	$lvo_settings = array('slideshow_width' => '650',
			'slideshow_height' => '350',
			'auto_slide' => 'yes',
			'transition_time' => '5',
			'control_xposition' => '450',
			'control_yposition' => '250',
			'is_reflection' => 'yes',
			'bg_gradient1' => '000000',
			'bg_gradient2' => 'CCCCCC',
			'header_fontcolor' => 'FFFFFF',
			'header_fontsize' => '17',
			'desc_fontcolor' => 'FFFFFF',
			'desc_fontsize' => '12',
			'bigimg_width' => '370',
			'bigimg_height' => '250',
			'bigimg_scalling' => 'no',
			'big_target' => '_blank',
			'smallimg_width' => '225',
			'smallimg_height' => '150',
			'smlImg_xposition' => '100',
			'smlImg_yposition' => '150',
			'smallimg_scalling' => 'no',
			'small_target' => '_self',
			'wmode' => 'transparent',
			'show_bigimg' => 'yes',
			'show_smlimg' => 'yes',
			'show_headertxt' => 'yes',
			'show_desctxt' => 'yes'		
			);
	return $lvo_settings;
}
function __get_lvo_xml_settings()
{
	// (($ops['auto_play'] == 'yes') ? 'true' : 'false')
	//LVO_PLUGIN_URL.'/price_images/'.$ops['pricebtn_img']
	$ops = get_option('lvo_settings', array());
	$blendMode	= 'normal';
	$titleFont = '';
	$isHeightQuality = ($ops['isHeightQuality'] == "yes") ? 'true' : 'false';
	$isShowBtn = ($ops['isShowBtn'] == "yes") ? 'true' : 'false';
	$isShowTitle = ($ops['isShowTitle'] == "yes") ? 'true' : 'false';
	$randomorder = ($ops['randomorder'] == "yes") ? 'true' : 'false';	
	$isShowAbout = ($ops['isShowAbout'] == "yes") ? 'true' : 'false';
	
	$xml_settings = '
<default>
	<auto_slide>'.(($ops['auto_slide'] == 'yes') ? 'true' : 'false').'</auto_slide>
	<slide_time>'.$ops['transition_time'].'</slide_time>
	<timer_x>'.$ops['control_xposition'].'</timer_x>
	<timer_y>'.$ops['control_yposition'].'</timer_y>
	<isReflection>'.(($ops['is_reflection'] == 'yes') ? 'true' : 'false').'</isReflection>
	<backColor_1>0x'.$ops['bg_gradient1'].'</backColor_1>
	<backColor_2>0x'.$ops['bg_gradient2'].'</backColor_2>
	<headerFontColor>0x'.$ops['header_fontcolor'].'</headerFontColor>
	<headerFontSize>'.$ops['header_fontsize'].'</headerFontSize>
	<descFontColor>0x'.$ops['desc_fontcolor'].'</descFontColor>
	<descFontSize>'.$ops['desc_fontsize'].'</descFontSize>
	<bigImgWidth>'.$ops['bigimg_width'].'</bigImgWidth>
	<bigImgHeight>'.$ops['bigimg_height'].'</bigImgHeight>
	<smallImgWidth>'.$ops['smallimg_width'].'</smallImgWidth>
	<smallImgHeight>'.$ops['smallimg_height'].'</smallImgHeight>
</default>';
	return $xml_settings;
}
function lvo_get_album_dir($album_id)
{
	global $glvo;
	$album_dir = LVO_PLUGIN_UPLOADS_DIR . "/{$album_id}_uploadfolder";
	return $album_dir;
}
/**
 * Get album url
 * @param $album_id
 * @return unknown_type
 */
function lvo_get_album_url($album_id)
{
	global $glvo;
	$album_url = LVO_PLUGIN_UPLOADS_URL . "/{$album_id}_uploadfolder";
	return $album_url;
}
function lvo_get_table_actions(array $tasks)
{
	?>
	<div class="bulk_actions">
		<form action="" method="post" class="bulk_form">Bulk action
			<select name="task">
				<?php foreach($tasks as $t => $label): ?>
				<option value="<?php print $t; ?>"><?php print $label; ?></option>
				<?php endforeach; ?>
			</select>
			<button class="button-secondary do_bulk_actions" type="submit">Do</button>
		</form>
	</div>
	<?php 
}
function shortcode_display_lvo_gallery($atts)
{
	$vars = shortcode_atts( array(
									'cats' => '',
									'imgs' => '',
								), 
							$atts );
	//extract( $vars );
	
	$ret = display_lvo_gallery($vars);
	return $ret;
}
function display_lvo_gallery($vars)
{
	global $wpdb, $glvo;
	$ops = get_option('lvo_settings', array());
	//print_r($ops);
	$albums = null;
	$images = null;
	$cids = trim($vars['cats']);
	if (strlen($cids) != strspn($cids, "0123456789,")) {
		$cids = '';
		$vars['cats'] = '';
	}
	$imgs = trim($vars['imgs']);
	if (strlen($imgs) != strspn($imgs, "0123456789,")) {
		$imgs = '';
		$vars['imgs'] = '';
	}
	$categories = '';
	$xml_filename = '';
	if( !empty($cids) && $cids{strlen($cids)-1} == ',')
	{
		$cids = substr($cids, 0, -1);
	}
	if( !empty($imgs) && $imgs{strlen($imgs)-1} == ',')
	{
		$imgs = substr($imgs, 0, -1);
	}
	//check for xml file
	if( !empty($vars['cats']) )
	{
		$xml_filename = "cat_".str_replace(',', '', $cids) . '.xml';	
	}
	elseif( !empty($vars['imgs']))
	{
		$xml_filename = "image_".str_replace(',', '', $imgs) . '.xml';
	}
	else
	{
		$xml_filename = "lvo_all.xml";
	}
	//die(LVO_PLUGIN_XML_DIR . '/' . $xml_filename);


	
	if( !empty($vars['cats']) )
	{
		$query = "SELECT * FROM {$wpdb->prefix}lvo_albums WHERE album_id IN($cids) AND status = 1 ORDER BY `order` ASC";
		//print $query;
		$albums = $wpdb->get_results($query, ARRAY_A);
		foreach($albums as $key => $album)
		{
			$images = $glvo->lvo_get_album_images($album['album_id']);
			if ($images && !empty($images) && is_array($images)) {
				$album_dir = lvo_get_album_url($album['album_id']);//LVO_PLUGIN_UPLOADS_URL . '/' . $album['album_id']."_".$album['name'];
				foreach($images as $key => $img)
				{
					if( $img['status'] == 0 ) continue;
			
					$categories .= '<item> ';
					if ($ops['show_bigimg'] == 'yes') {
						$categories .= '<bigImg>'.$album_dir."/big/".$img['image'].'</bigImg>';
					} else {
						$categories .= '<bigImg></bigImg>';
					}

					$categories .= '<bigUrl>'.trim($img['link']).'</bigUrl>
						<bigUrlOpenWindow>'.$ops['big_target'].'</bigUrlOpenWindow>
						<bigIsScaled>'.(($ops['bigimg_scalling'] == 'yes') ? 'true' : 'false').'</bigIsScaled>';

					if ($ops['show_smlimg'] == 'yes') {
						$categories .= ' <smallImg>'.$album_dir."/thumb/".$img['thumb'].'</smallImg>';
					}else{
						$categories .= ' <smallImg></smallImg>';
					}

					$categories .= '<smallUrl>'.trim($img['thumb_link']).'</smallUrl> 
					<smallUrlOpenWindow>'.$ops['small_target'].'</smallUrlOpenWindow>
					
					<smallX>'.$ops['smlImg_xposition'].'</smallX>
					<smallY>'.$ops['smlImg_yposition'].'</smallY>

					<smallIsScaled>'.(($ops['smallimg_scalling'] == 'yes') ? 'true' : 'false').'</smallIsScaled>';

					if ($ops['show_headertxt'] == 'yes') {
						$categories .= '<header><![CDATA['.trim($img['title']).']]></header>';
					}else{
						$categories .= '<header><![CDATA[]]></header>';
					}

					if ($ops['show_desctxt'] == 'yes') {
						$categories .= '<desc><![CDATA['.trim($img['description']).']]></desc>';
					}else{
						$categories .= '<desc><![CDATA[]]></desc>';
					}

					$categories .= '</item>';	
				}
			}
		}
		//$xml_filename = "cat_".str_replace(',', '', $cids) . '.xml';
	}
	elseif( !empty($vars['imgs']))
	{
		$query = "SELECT * FROM {$wpdb->prefix}lvo_images WHERE image_id IN($imgs) AND status = 1 ORDER BY `order` ASC";
		$images = $wpdb->get_results($query, ARRAY_A);
		if ($images && !empty($images) && is_array($images)) {
			foreach($images as $key => $img)
			{
				$album = $glvo->lvo_get_album($img['category_id']);
				$album_dir = lvo_get_album_url($album['album_id']);//LVO_PLUGIN_UPLOADS_URL . '/' . $album['album_id']."_".$album['name'];
				if( $img['status'] == 0 ) continue;
				
				$categories .= '<item> ';
					if ($ops['show_bigimg'] == 'yes') {
						$categories .= '<bigImg>'.$album_dir."/big/".$img['image'].'</bigImg>';
					} else {
						$categories .= '<bigImg></bigImg>';
					}

					$categories .= '<bigUrl>'.trim($img['link']).'</bigUrl>
						<bigUrlOpenWindow>'.$ops['big_target'].'</bigUrlOpenWindow>
						<bigIsScaled>'.(($ops['bigimg_scalling'] == 'yes') ? 'true' : 'false').'</bigIsScaled>';

					if ($ops['show_smlimg'] == 'yes') {
						$categories .= ' <smallImg>'.$album_dir."/thumb/".$img['thumb'].'</smallImg>';
					}else{
						$categories .= ' <smallImg></smallImg>';
					}

					$categories .= '<smallUrl>'.trim($img['thumb_link']).'</smallUrl> 
					<smallUrlOpenWindow>'.$ops['small_target'].'</smallUrlOpenWindow>
					
					<smallX>'.$ops['smlImg_xposition'].'</smallX>
					<smallY>'.$ops['smlImg_yposition'].'</smallY>

					<smallIsScaled>'.(($ops['smallimg_scalling'] == 'yes') ? 'true' : 'false').'</smallIsScaled>';

					if ($ops['show_headertxt'] == 'yes') {
						$categories .= '<header><![CDATA['.trim($img['title']).']]></header>';
					}else{
						$categories .= '<header><![CDATA[]]></header>';
					}

					if ($ops['show_desctxt'] == 'yes') {
						$categories .= '<desc><![CDATA['.trim($img['description']).']]></desc>';
					}else{
						$categories .= '<desc><![CDATA[]]></desc>';
					}

					$categories .= '</item>';
			}
		}
	}
	//no values paremeters setted
	else//( empty($vars['cats']) && empty($vars['imgs']))
	{
		$query = "SELECT * FROM {$wpdb->prefix}lvo_albums WHERE status = 1 ORDER BY `order` ASC";
		$albums = $wpdb->get_results($query, ARRAY_A);
		foreach($albums as $key => $album)
		{
			$images = $glvo->lvo_get_album_images($album['album_id']);
			$album_dir = lvo_get_album_url($album['album_id']);//LVO_PLUGIN_UPLOADS_URL . '/' . $album['album_id']."_".$album['name'];
			if ($images && !empty($images) && is_array($images)) {
				foreach($images as $key => $img)
				{
					if($img['status'] == 0 ) continue;
					
					$categories .= '<item> ';
					if ($ops['show_bigimg'] == 'yes') {
						$categories .= '<bigImg>'.$album_dir."/big/".$img['image'].'</bigImg>';
					} else {
						$categories .= '<bigImg></bigImg>';
					}

					$categories .= '<bigUrl>'.trim($img['link']).'</bigUrl>
						<bigUrlOpenWindow>'.$ops['big_target'].'</bigUrlOpenWindow>
						<bigIsScaled>'.(($ops['bigimg_scalling'] == 'yes') ? 'true' : 'false').'</bigIsScaled>';

					if ($ops['show_smlimg'] == 'yes') {
						$categories .= ' <smallImg>'.$album_dir."/thumb/".$img['thumb'].'</smallImg>';
					}else{
						$categories .= ' <smallImg></smallImg>';
					}

					$categories .= '<smallUrl>'.trim($img['thumb_link']).'</smallUrl> 
					<smallUrlOpenWindow>'.$ops['small_target'].'</smallUrlOpenWindow>
					
					<smallX>'.$ops['smlImg_xposition'].'</smallX>
					<smallY>'.$ops['smlImg_yposition'].'</smallY>

					<smallIsScaled>'.(($ops['smallimg_scalling'] == 'yes') ? 'true' : 'false').'</smallIsScaled>';

					if ($ops['show_headertxt'] == 'yes') {
						$categories .= '<header><![CDATA['.trim($img['title']).']]></header>';
					}else{
						$categories .= '<header><![CDATA[]]></header>';
					}

					if ($ops['show_desctxt'] == 'yes') {
						$categories .= '<desc><![CDATA['.trim($img['description']).']]></desc>';
					}else{
						$categories .= '<desc><![CDATA[]]></desc>';
					}

					$categories .= '</item>';
				}
			}
		}
		//$xml_filename = "lvo_all.xml";
	}
	
	$xml_tpl = __get_lvo_xml_template();
	$settings = __get_lvo_xml_settings();
	$xml = str_replace(array('{settings}', '{categories}'), 
						array($settings, $categories), $xml_tpl);
	//write new xml file
	$fh = fopen(LVO_PLUGIN_XML_DIR . '/' . $xml_filename, 'w+');
	fwrite($fh, $xml);
	fclose($fh);
	//print "<h3>Generated filename: $xml_filename</h3>";
	//print $xml;
	if( file_exists(LVO_PLUGIN_XML_DIR . '/' . $xml_filename ) )
	{
		$fh = fopen(LVO_PLUGIN_XML_DIR . '/' . $xml_filename, 'r');
		$xml = fread($fh, filesize(LVO_PLUGIN_XML_DIR . '/' . $xml_filename));
		fclose($fh);
		//print "<h3>Getting xml file from cache: $xml_filename</h3>";
		$ret_str = "
		<script language=\"javascript\">AC_FL_RunContent = 0;</script>
<script src=\"".LVO_PLUGIN_URL."/js/AC_RunActiveContent.js\" language=\"javascript\"></script>

		<script language=\"javascript\"> 
	if (AC_FL_RunContent == 0) {
		alert(\"This page requires AC_RunActiveContent.js.\");
	} else {
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
			'width', '".$ops['slideshow_width']."',
			'height', '".$ops['slideshow_height']."',
			'src', '".LVO_PLUGIN_URL."/js/wp_levoslideshow',
			'quality', 'high',
			'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', '".$ops['wmode']."',
			'devicefont', 'false',
			'id', 'xmlswf_vmlvo',
			'name', 'xmlswf_vmlvo',
			'menu', 'true',
			'allowFullScreen', 'true',
			'allowScriptAccess','sameDomain',
			'movie', '".LVO_PLUGIN_URL."/js/wp_levoslideshow',
			'salign', '',
			'flashVars','xmlID=".LVO_PLUGIN_URL."/xml/$xml_filename'
			); //end AC code
	}
</script>
";
//echo LVO_PLUGIN_UPLOADS_URL."<hr>";
//		print $xml;
		return $ret_str;
	}
	return true;
}
function __get_lvo_xml_template()
{
	$xml_tpl = '<?xml version="1.0" encoding="iso-8859-1"?><items>
	{settings}
	{categories}
	</items>';
	return $xml_tpl;
}
?>