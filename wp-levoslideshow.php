<?php
 /**
 * @package LEVO Slidehsow Plugin for WordPress
 * @version 1.1
 */
/*
Plugin Name: LEVO Slidehsow
plugin uri: http://wpslideshow.com/levo-slideshow/
Description: Yet Another Simple Slideshow Plugin for WordPress is a plugin that allows you to display a flash gallery on your site.It is also allows to use it as a widget. You can also enable this flash gallery on your wordpress site by placing code snippet in your template php file.
Author: wpslideshow.com
Version: 1.1
Author URI: http://wpslideshow.com
*/

require_once (ABSPATH .'wp-content/plugins/wp-levoslideshow/noimage_functions.php');

define("levoplugin_name","wp-levoslideshow");
function load_levoslideshow() {
	ob_start();
	$levo_pluginurl = get_bloginfo('wpurl') . '/wp-content/plugins/'.constant("levoplugin_name").'/';
	$xmlUrl = str_replace("themes","plugins",get_theme_root()).'/'.constant("levoplugin_name").'/wp-levoslideshow.xml'; // XML feed file/URL
	$xmlStr = file_get_contents($xmlUrl);
	$xmlObj = simplexml_load_string($xmlStr, null, LIBXML_NOCDATA);
	$arrXml = objectsIntoArray_levoslideshow($xmlObj);
	$temp=FloatBar_read_config_levoslideshow();
?>

<script type="text/javascript" src="<?php echo $levo_pluginurl;  ?>js/swfobject.js"></script>
<script type="text/javascript">

		var flashvars = {
			xmlID: "<?php echo $levo_pluginurl;  ?>wp-levoslideshow.xml"
		};
		var params = {
			bgcolor: "<?php echo $temp['bgcolor']; ?>",
			wmode: "<?php echo $temp['wmode']; ?>"
		};
		
		var attributes = {
			id: "myFlash"
		};
		
		
		swfobject.embedSWF("<?php echo $levo_pluginurl;  ?>wp-levoslideshow.swf", "levoSlideshow", "<?php echo $temp['slideshow_width']; ?>", "<?php echo $temp['slideshow_height']; ?>", "9,0,0,0", false, flashvars, params, attributes);

</script>
			<div id="levoSlideshow"></div>

		<noscript>
	<object data="<?php echo $levo_pluginurl;  ?>levoslideshow.swf" type="application/x-shockwave-flash" width="<?php echo $temp['slideshow_width']; ?>" height="<?php echo $temp['slideshow_height']; ?>" align="middle" id="myFlash">
		<param name="movie" value="<?php echo $levo_pluginurl;  ?>wp-levoslideshow.swf" />
		<param name="allowFullScreen" value="true" />	
		<param name="quality" value="high" />
		<param name="bgcolor" value="<?php echo $temp['bgcolor']; ?>" />
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="wmode" value="<?php echo $temp['wmode']; ?>" />
		<param name="flashvars" value="xmlID=<?php echo $levo_pluginurl;  ?>wp-levoslideshow.xml" />
	</object>
</noscript>




<?php

$o = ob_get_contents();
ob_end_clean();
return $o;

}


add_shortcode('levo_slideshow', 'load_levoslideshow');

function my_plugin_menu_levoslideshow() {
  add_menu_page('My Plugin Options', 'LEVO Slideshow Settings', 'administrator', 'your-unique-identifier_levoslideshow', 'my_plugin_options_levoslideshow');
}

function uploadPic_levoslideshow($a){
	$filetype = $a['type'];
	if(strpos($filetype,"jpeg")!==false){
	$type = '.jpg';
	}else if(strpos($filetype,"gif")!==false){
	$type = '.gif';
	}else if(strpos($filetype,"png")!==false){
	$type = '.png';
	}
	else {
	echo "Please upload only valid JPG, GIF or PNG files";
	return false;

	}
	$upfile = str_replace("themes","plugins",get_theme_root()).'/'.constant("levoplugin_name").'/images/'.$a['name'];

	move_uploaded_file($a['tmp_name'],$upfile);
	$b="images/".$a['name'];

	return $b;

}

function uploadThumb_levoslideshow($a){
	$filetype = $a['type'];
	if(strpos($filetype,"jpeg")!==false){
	$type = '.jpg';
	}else if(strpos($filetype,"gif")!==false){
	$type = '.gif';
	}else if(strpos($filetype,"png")!==false){
	$type = '.png';
	}
	else {
	echo "Please upload only valid JPG, GIF or PNG files";
	return false;

	}
	$upfile = str_replace("themes","plugins",get_theme_root()).'/'.constant("levoplugin_name").'/thumbs/'.$a['name'];

	move_uploaded_file($a['tmp_name'],$upfile);
	$b="thumbs/".$a['name'];

	return $b;

}



function my_plugin_options_levoslideshow() {

$temp=get_option("product_list_url"); 
preg_match("/\?[^=]*=\d+/",$temp,$b);

	if ($_POST["wmode"]!="")
	{
		$configxml="<items><default>
		<slideshow_width>".$_POST["slideshow_width"]."</slideshow_width>
		<slideshow_height>".$_POST["slideshow_height"]."</slideshow_height>
		<bgcolor>".$_POST["bg_color"]."</bgcolor>

		<auto_slide>".$_POST["auto_slide"]."</auto_slide>
		<slide_time>".$_POST["transition_time"]."</slide_time>
		<timer_x>".$_POST["control_xposition"]."</timer_x>
		<timer_y>".$_POST["control_yposition"]."</timer_y>
		<isReflection>".$_POST["is_reflection"]."</isReflection>

		<backColor_1>".$_POST["bg_gradient1"]."</backColor_1>
		<backColor_2>".$_POST["bg_gradient2"]."</backColor_2>
		<headerFontColor>".$_POST["header_fontcolor"]."</headerFontColor>
		<headerFontSize>".$_POST["header_fontsize"]."</headerFontSize>
		<descFontColor>".$_POST["desc_fontcolor"]."</descFontColor>

		<descFontSize>".$_POST["desc_fontsize"]."</descFontSize>
		<bigImgWidth>".$_POST["bigimg_width"]."</bigImgWidth>
		<bigImgHeight>".$_POST["bigimg_height"]."</bigImgHeight>
		<smallImgWidth>".$_POST["smallimg_width"]."</smallImgWidth>
		<smallImgHeight>".$_POST["smallimg_height"]."</smallImgHeight>
		<wmode>".$_POST["wmode"]."</wmode>
		</default>";

$exist_url = get_bloginfo('wpurl');
$server_path = getCurUrl($exist_url);

//////////////////////////////////////////


	$jsondata=preg_split("/,,/",stripslashes($_POST["picStorage"]));
	$thumb_image = '';
	for($a=0;$a<count($jsondata);$a++)
	{

		//echo $jsondata[$a];
		$www=json_decode($jsondata[$a],true);
		//echo $www["Pic"];
	
		
		if($www["filethumb"]!="")
		{
		$imagesPath_thumb=uploadThumb_levoslideshow($_FILES[$www["filethumb"]]);
		$www["thumb"]=$imagesPath_thumb;

		$imagesPath_image=uploadPic_levoslideshow($_FILES[$www["fileimage"]]);
		$www["Pic"]=$imagesPath_image;

		}

//// Thumb Image url


$substr_cnt_thumb = substr_count($www["thumb"], 'http://');

if($substr_cnt_thumb > 0){
	if(substr_count($server_path, 'www'))
		{
		  if(substr_count($www["thumb"], 'www'))
				{
					$thumb_image = $www["thumb"];
				}else{
					$thumb_image = str_replace("http://","http://www.",$www["thumb"]);
				}
		}else{
		$thumb_image = $www["thumb"];
		}
}else{
	if($www["thumb"]){
		if (false === strpos($www["thumb"], 'wp-content')) {
			$thumb_image = get_bloginfo('wpurl')."/wp-content/plugins/wp-levoslideshow/".$www["thumb"];
		}else{
			$thumb_image = get_bloginfo('wpurl')."/".$www["thumb"];
		}
	}
}


//// Main Image url
$substr_cnt_main = substr_count($www["Pic"], 'http://');

if($substr_cnt_main > 0){
	if(substr_count($server_path, 'www'))
		{
		  if(substr_count($www["Pic"], 'www'))
				{
					$main_image = $www["Pic"];
				}else{
					$main_image = str_replace("http://","http://www.",$www["Pic"]);
				}
		}else{

		$main_image = $www["Pic"];
		}
}else{
	if (false === strpos($www["Pic"], 'wp-content')) {
		$main_image = get_bloginfo('wpurl')."/wp-content/plugins/wp-levoslideshow/".$www["Pic"];
	}else{
		$main_image = get_bloginfo('wpurl')."/".$www["Pic"];
	}
}

	$configxml.= "<item> ";
	$configxml.= "<bigImg>".$main_image."</bigImg>";
	$configxml.= "<bigUrl>".$www["url"]."</bigUrl>";
	$configxml.= "<bigUrlOpenWindow>".$_POST['big_target']."</bigUrlOpenWindow>";
	$configxml.= "<bigIsScaled>".$_POST['bigImg_scalling']."</bigIsScaled>";
    if($www["thumb"]!=""){
		$configxml.= "<smallImg>".$thumb_image."</smallImg>";}
		$configxml.= "<smallUrl>".$www["small_url"]."</smallUrl>";
		$configxml.= "<smallUrlOpenWindow>".$_POST['small_target']."</smallUrlOpenWindow>";
		$configxml.= "<smallX>".$www["smallx"]."</smallX>";
		$configxml.= "<smallY>".$www["smally"]."</smallY>";
		$configxml.= "<smallIsScaled>".$_POST['smlImg_scalling']."</smallIsScaled>";
	
	$configxml.= "<header><![CDATA[".$www['title']."]]></header>";
	$configxml.= "<desc><![CDATA[".$www['Desc']."]]></desc>";
	$configxml.= "</item>";

}
$configxml.="</items>";
	

   if(!$file = @fopen(str_replace("themes","plugins",get_theme_root()).'/'.constant("levoplugin_name").'/wp-levoslideshow.xml', 'w')){
            	echo "Can't open the file!";
            } else {
	            fwrite($file, $configxml);
	            fclose($file);
				echo "Successfully made the config.xml.";
            }	

	}

	$temp=FloatBar_read_config_levoslideshow();
?>

<h2>Making XML File:</h2>

<form name="form1" method="post" action="" enctype="multipart/form-data">
Slideshow Width: <input type="text" name="slideshow_width" value="<?php echo $temp["slideshow_width"];?>" /><br>

Slideshow Height: <input type="text" name="slideshow_height" value="<?php echo $temp["slideshow_height"];?>" /><br>

Background Color: <input type="text" name="bg_color" value="<?php echo $temp["bgcolor"];?>" /><br>

Auto Slide: <select name="auto_slide">
<option value="true" <?php if($temp["auto_slide"]=="true")echo "selected=\"selected\""; ?>>YES</option>
<option value="false" <?php if($temp["auto_slide"]=="false")echo "selected=\"selected\""; ?>>NO</option>
</select><br>

Autoslide Time: <input type="text" name="transition_time" value="<?php echo $temp["slide_time"];?>" /><br>

Controls X-Position: <input type="text" name="control_xposition" value="<?php echo $temp["timer_x"];?>" /><br>

Controls Y-Position: <input type="text" name="control_yposition" value="<?php echo $temp["timer_y"];?>" /><br>

Image Reflection: <select name="is_reflection">
<option value="true" <?php if($temp["isReflection"]=="true")echo "selected=\"selected\""; ?>>YES</option>
<option value="false" <?php if($temp["isReflection"]=="false")echo "selected=\"selected\""; ?>>NO</option>
</select><br>

Gradient Color1: <input type="text" name="bg_gradient1" value="<?php echo $temp["backColor_1"];?>" /><br>

Gradient Color2: <input type="text" name="bg_gradient2" value="<?php echo $temp["backColor_2"];?>" /><br>

Title Color: <input type="text" name="header_fontcolor" value="<?php echo $temp["headerFontColor"];?>" /><br>

Title Size: <input type="text" name="header_fontsize" value="<?php echo $temp["headerFontSize"];?>" /><br>

Description Color: <input type="text" name="desc_fontcolor" value="<?php echo $temp["descFontColor"];?>" /><br>

Description Size: <input type="text" name="desc_fontsize" value="<?php echo $temp["descFontSize"];?>" /><br>

Big Image Width: <input type="text" name="bigimg_width" value="<?php echo $temp["bigImgWidth"];?>" /><br>

Big Image Height: <input type="text" name="bigimg_height" value="<?php echo $temp["bigImgHeight"];?>" /><br>

Big Image Scalling: <select name="bigImg_scalling">
<option value="true" <?php if($temp["bigIsScaled"]=="true")echo "selected=\"selected\""; ?>>YES</option>
<option value="false" <?php if($temp["bigIsScaled"]=="false")echo "selected=\"selected\""; ?>>NO</option>
</select><br>

Big Image Link Target: <select name="big_target">
<option value="_self" <?php if($temp["bigUrlOpenWindow"]=="_self")echo "selected=\"selected\""; ?>>Same Window</option>
<option value="_blank" <?php if($temp["bigUrlOpenWindow"]=="_blank")echo "selected=\"selected\""; ?>>New Window</option>
</select><br>

Small Image Width: <input type="text" name="smallimg_width" value="<?php echo $temp["smallImgWidth"];?>" /><br>

Small Image Height : <input type="text" name="smallimg_height" value="<?php echo $temp["smallImgHeight"];?>" /><br>

small Image Scalling: <select name="smlImg_scalling">
<option value="true" <?php if($temp["smallIsScaled"]=="true")echo "selected=\"selected\""; ?>>YES</option>
<option value="false" <?php if($temp["smallIsScaled"]=="false")echo "selected=\"selected\""; ?>>NO</option>
</select><br>

Small Image Link Target: <select name="small_target">
<option value="_self" <?php if($temp["smallUrlOpenWindow"]=="_self")echo "selected=\"selected\""; ?>>Same Window</option>
<option value="_blank" <?php if($temp["smallUrlOpenWindow"]=="_blank")echo "selected=\"selected\""; ?>>New Window</option>
</select><br>


Wmode: <select name="wmode">
<option value="opaque" <?php if($temp["wmode"]=="opaque")echo "selected=\"selected\""; ?>>Opaque</option>
<option value="transparent" <?php if($temp["wmode"]=="transparent")echo "selected=\"selected\""; ?>>Transparent</option>
<option value="window" <?php if($temp["wmode"]=="window")echo "selected=\"selected\""; ?>>Window</option>
</select><br>

<div id="slidePics">
Main Image Directory: wp-content/plugins/wp-levoslideshow/images/<br>
Small Image Directory: wp-content/plugins/wp-levoslideshow/thumbs/<br><br>

<fieldset  style="border:1px solid #000000;padding:10px;">
    <legend>Images & Data </legend>


<?php

for ($i=0;$i<count($temp["picStorage"]);$i++)
{
?>
<fieldset  style="border:1px solid #000000;padding:10px;">
<legend>Slideshow images <?php echo $i+1?></legend>
<span>Big Image Link:<input type="text" value="<?php echo $temp["picStorage"][$i]["url"]; ?>" /> Small Image Link:<input type="text" value="<?php echo $temp["picStorage"][$i]["small_url"]; ?>" /><br> <select onchange="handleSelectChange(this)"><option value="YES" <?php if($temp["picStorage"][$i]["Pic"]==""){"selected=\"selected\"";} ?>>Upload Image</option><option value="NO" <?php if($temp["picStorage"][$i]["Pic"]!=""){echo "selected=\"selected\"";} ?>>Use image path</option></select> <div style="display:inline;"><div style="display:inline;<?php if($temp["picStorage"][$i]["Pic"]==""){echo "display:none";} ?>">Small Image:<input type="text" value="<?php echo $temp["picStorage"][$i]["thumb"]; ?>" />Full Image:<input type="text" value="<?php echo $temp["picStorage"][$i]["Pic"]; ?>" /></div> <div style="<?php if($temp["picStorage"][$i]["Pic"]!=""){echo "display:none";} ?>">Small Image&nbsp;: <input type='file' name='file<?php echo $i; ?>' />image: <input type='file' name='file_image<?php echo $i; ?>' /></div></div><br><input type="text" style="display:none" value="<?php echo $temp["picStorage"][$i]["video"]; ?>" /> Description&nbsp;:<input type="text" value="<?php echo $temp["picStorage"][$i]["Desc"]; ?>" /> Title&nbsp;:<input type="text" value="<?php echo $temp["picStorage"][$i]["title"]; ?>" /><input type="text" style="display:none" value="<?php echo $temp["picStorage"][$i]["Title"]; ?>" /><br>
Small Image x-Position&nbsp;:<input type="text" value="<?php echo $temp["picStorage"][$i]["smallx"]; ?>" />
Small Image Y-Position&nbsp;:<input type="text" value="<?php echo $temp["picStorage"][$i]["smally"]; ?>" />
<input type="button" value="+" onclick="slidePicsAdd(this)" /><input type="button" value="-" onclick="slidePicsDelete(this)" /></span><br>

</fieldset>

<?php
}

?>
</div>
</fieldset>
<p>To display this slideshow on your blog/page, type in <code>[levo_slideshow]</code> on any page.</p>

<input type="hidden" name="picStorage" id="picStorage" value="aaaaaaaa" /><br>
<input type="submit" value="Make Config.xml" onclick="slidePicsStorage()" />
</form>
<script>
//add to support sting parse
String.prototype.toJSON = function(){  
    return '"' +  
        this.replace(/(\\|\")/g,"\\$1")  
        .replace(/\n|\r|\t/g,function(){  
            var a = arguments[0];  
            return  (a == '\n') ? '\\n':  
                    (a == '\r') ? '\\r':  
                    (a == '\t') ? '\\t': "" 
        }) +  
        '"' 
}   

var countImages=<?php echo count($temp["picStorage"]);?>;
	
function handleSelectChange(a){
if(a.value=="YES")
{
jQuery(a).next().children().first().hide()
jQuery(a).next().children().last().show()

}
else
{
jQuery(a).next().children().first().show()
jQuery(a).next().children().last().hide()

}
}
function slidePicsAdd(a){
jQuery(a).parent().parent().append('<span>Big Image Link:<input type="text" /> Small Image Link:<input type="text" /><br><select onchange="handleSelectChange(this)"><option value="YES">Upload Image</option><option value="NO">Use image path</option></select> <div style="display:inline;"><div style="display:inline;display:none;">Small Image: <input type="text" />Full Image:<input type="text" /></div> <div style="<?php if($temp["picStorage"][$i]["pic"]!=""){echo "display:none";} ?>">Small Image&nbsp;: <input type=\'file\' name=\'file'+(++countImages)+'\' /> image:<input type=\'file\' name=\'file_image'+(countImages)+'\' /></div></div><br> <input type="text" style="display:none;" /> Description&nbsp;:<input type="text" /> Title&nbsp;:<input type="text" /><input style="display:none" type="text" /> <br> Small Image X-Position&nbsp;:<input type="text" />Small Image Y-Position&nbsp;<input type="text" /><input type="button" value="+" onclick="slidePicsAdd(this)" /><input type="button" value="-" onclick="slidePicsDelete(this)" /></span><br>');

}	
function slidePicsDelete(a){
jQuery(a).parent().remove();
}	
function slidePicsStorage(){
	var temp="";
	var limit=jQuery("#slidePics span").length;
	for (var i=0;i<limit ;i++ )
	{
		temp+="{\"url\":\""+jQuery("#slidePics span:eq("+i+") input[type=text]")[0].value+"\",\"small_url\":"+jQuery("#slidePics span:eq("+i+") input[type=text]")[1].value.toJSON()+",";

		if(jQuery("#slidePics span:eq("+i+") select").val()=="YES")
		{
		temp+="\"filethumb\":\""+jQuery("#slidePics span:eq("+i+") input[type=file]:eq(0)").attr("name")+"\",";
		temp+="\"fileimage\":\""+jQuery("#slidePics span:eq("+i+") input[type=file]:eq(1)").attr("name")+"\",";
		}
		else
		{
		temp+="\"thumb\":\""+jQuery("#slidePics span:eq("+i+") input[type=text]")[2].value+"\",\"Pic\":\""+jQuery("#slidePics span:eq("+i+") input[type=text]")[3].value+"\",";		
		}
		
		temp+="\"Video\":"+jQuery("#slidePics span:eq("+i+") input[type=text]")[4].value.toJSON()+",\"Desc\":"+jQuery("#slidePics span:eq("+i+") input[type=text]")[5].value.toJSON()+",\"title\":"+jQuery("#slidePics span:eq("+i+") input[type=text]")[6].value.toJSON()+",\"Title\":"+jQuery("#slidePics span:eq("+i+") input[type=text]")[7].value.toJSON()+",\"smallx\":"+jQuery("#slidePics span:eq("+i+") input[type=text]")[8].value.toJSON()+",\"smally\":"+jQuery("#slidePics span:eq("+i+") input[type=text]")[9].value.toJSON()+"},,";
	}
	temp=temp.slice(0,temp.length-2);
	jQuery("#picStorage").val(temp);
}


</script>

<?php

}

function objectsIntoArray_levoslideshow($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray_levoslideshow($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}



function FloatBar_read_config_levoslideshow(){
	
$xmlUrl = str_replace("themes","plugins",get_theme_root()).'/wp-levoslideshow/wp-levoslideshow.xml'; 
$xmlStr = file_get_contents($xmlUrl);
$xmlObj = simplexml_load_string($xmlStr, null, LIBXML_NOCDATA);

$arrXml = objectsIntoArray_levoslideshow($xmlObj);

$a["slideshow_width"]=$xmlObj->default->slideshow_width;
$a["slideshow_height"]=$xmlObj->default->slideshow_height;
$a["bgcolor"]=$xmlObj->default->bgcolor;
$a["auto_slide"]=$xmlObj->default->auto_slide;
$a["slide_time"]=$xmlObj->default->slide_time;

$a["timer_x"]=$xmlObj->default->timer_x;
$a["timer_y"]=$xmlObj->default->timer_y;
$a["isReflection"]=$xmlObj->default->isReflection;

$a["backColor_1"]=$xmlObj->default->backColor_1;
$a["backColor_2"]=$xmlObj->default->backColor_2;

$a["headerFontColor"]=$xmlObj->default->headerFontColor;
$a["headerFontSize"]=$xmlObj->default->headerFontSize;

$a["descFontColor"]=$xmlObj->default->descFontColor;
$a["descFontSize"]=$xmlObj->default->descFontSize;

$a["bigImgWidth"]=$xmlObj->default->bigImgWidth;
$a["bigImgHeight"]=$xmlObj->default->bigImgHeight;
$a["bigIsScaled"]=$xmlObj->item->bigIsScaled;
$a["bigUrlOpenWindow"]=$xmlObj->item->bigUrlOpenWindow;
$a["bigUrl"]=$xmlObj->item->bigUrl;

$a["smallImgWidth"]=$xmlObj->default->smallImgWidth;
$a["smallImgHeight"]=$xmlObj->default->smallImgHeight;
$a["smallX"]=$xmlObj->item->smallX;
$a["smallY"]=$xmlObj->item->smallY;
$a["smallIsScaled"]=$xmlObj->item->smallIsScaled;
$a["smallUrlOpenWindow"]=$xmlObj->item->smallUrlOpenWindow;

$a["wmode"]=$xmlObj->default->wmode;


$a["smallUrl"]=$xmlObj->item->smallUrl;


$a["picStorage"]=array();
if($xmlObj->item)
{
	$num=count($xmlObj->item);
	for ($i=0;$i<$num;$i++)
	{
	$a["picStorage"][$i]=array();

    $a["picStorage"][$i]["Pic"]=$xmlObj->item[$i]->bigImg;
	$a["picStorage"][$i]["thumb"]=$xmlObj->item[$i]->smallImg;
	$a["picStorage"][$i]["Desc"]=$xmlObj->item[$i]->desc;
	$a["picStorage"][$i]["url"]=$xmlObj->item[$i]->bigUrl;
	$a["picStorage"][$i]["small_url"]=$xmlObj->item[$i]->smallUrl;
	$a["picStorage"][$i]["title"]=$xmlObj->item[$i]->header;
	$a["picStorage"][$i]["smallx"]=$xmlObj->item[$i]->smallX;
	$a["picStorage"][$i]["smally"]=$xmlObj->item[$i]->smallY;

	}
}
else
{
	$num=1;
	$a["picStorage"][0]=array();

	$a["picStorage"][0]["Pic"]=$xmlObj->item[0]->bigImg;
	$a["picStorage"][0]["thumb"]=$xmlObj->item[0]->smallImg;
	$a["picStorage"][0]["Desc"]=$xmlObj->item[0]->desc;
	$a["picStorage"][0]["url"]=$xmlObj->item[0]->bigUrl;
	$a["picStorage"][0]["small_url"]=$xmlObj->item[0]->smallUrl;
	$a["picStorage"][0]["title"]=$xmlObj->item[0]->header;
	$a["picStorage"][0]["smallx"]=$xmlObj->item[0]->smallX;
	$a["picStorage"][0]["smally"]=$xmlObj->item[0]->smallY;

}

return $a;
}

add_action('admin_menu', 'my_plugin_menu_levoslideshow');

///////// widget initialising code

function widget_levoslideshow($args) {
  extract($args);
  echo $before_widget;
  echo do_shortcode('[levo_slideshow]');
  echo $after_widget;
}
 
function myLevoslideshow_init()
{
  register_sidebar_widget(__('LEVO Slideshow'), 'widget_levoslideshow');
}
add_action("plugins_loaded", "myLevoslideshow_init");

?>
