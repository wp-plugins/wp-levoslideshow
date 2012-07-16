<?php
/*
This is an upload script for SWFUpload that attempts to properly handle uploaded files
in a secure way.

Notes:
	
	SWFUpload doesn't send a MIME-TYPE. In my opinion this is ok since MIME-TYPE is no better than
	 file extension and is probably worse because it can vary from OS to OS and browser to browser (for the same file).
	 The best thing to do is content sniff the file but this can be resource intensive, is difficult, and can still be fooled or inaccurate.
	 Accepting uploads can never be 100% secure.
	 
	You can't guarantee that SWFUpload is really the source of the upload.  A malicious user
	 will probably be uploading from a tool that sends invalid or false metadata about the file.
	 The script should properly handle this.
	 
	The script should not over-write existing files.
	
	The script should strip away invalid characters from the file name or reject the file.
	
	The script should not allow files to be saved that could then be executed on the webserver (such as .php files).
	 To keep things simple we will use an extension whitelist for allowed file extensions.  Which files should be allowed
	 depends on your server configuration. The extension white-list is _not_ tied your SWFUpload file_types setting
	
	For better security uploaded files should be stored outside the webserver's document root.  Downloaded files
	 should be accessed via a download script that proxies from the file system to the webserver.  This prevents
	 users from executing malicious uploaded files.  It also gives the developer control over the outgoing mime-type,
	 access restrictions, etc.  This, however, is outside the scope of this script.
	
	SWFUpload sends each file as a separate POST rather than several files in a single post. This is a better
	 method in my opinions since it better handles file size limits, e.g., if post_max_size is 100 MB and I post two 60 MB files then
	 the post would fail (2x60MB = 120MB). In SWFupload each 60 MB is posted as separate post and we stay within the limits. This
	 also simplifies the upload script since we only have to handle a single file.
	
	The script should properly handle situations where the post was too large or the posted file is larger than
	 our defined max.  These values are not tied to your SWFUpload file_size_limit setting.
	
*/

// Code for Session Cookie workaround
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	} else if (isset($_GET["PHPSESSID"])) {
		session_id($_GET["PHPSESSID"]);
	}

	session_start();

	$conf_content = file_get_contents('../../../../../../wp-config.php');

$p_dbname = '#define\s*\(\s*[\'"]DB_NAME[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]#i';
if (preg_match($p_dbname, $conf_content, $res_db)) {
	$db_name = $res_db[1];
} else {
	HandleError("DB name error.");
	exit(0);
}

$p_dbuser = '#define\s*\(\s*[\'"]DB_USER[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]#i';
if (preg_match($p_dbuser, $conf_content, $res_dbu)) {
	$db_user = $res_dbu[1];
} else {
	HandleError("DB user error.");
	exit(0);
}

$p_dbpass = '#define\s*\(\s*[\'"]DB_PASSWORD[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]#i';
if (preg_match($p_dbpass, $conf_content, $res_dbp)) {
	$db_pass = $res_dbp[1];
} else {
	HandleError("DB password error.");
	exit(0);
}

$p_dbhost = '#define\s*\(\s*[\'"]DB_HOST[\'"]\s*,\s*[\'"]([^\'"]+)[\'"]#i';
if (preg_match($p_dbhost, $conf_content, $res_dbh)) {
	$db_host = $res_dbh[1];
} else {
	HandleError("DB host error.");
	exit(0);
}

$p_dbprefix = '#\$table_prefix\s*=\s*[\'"]([^\'"]*)[\'"]#i';
if (preg_match($p_dbprefix, $conf_content, $res_dbpref)) {
	$db_prefix = $res_dbpref[1];
} else {
	HandleError("DB prefix error.");
	exit(0);
}

$q_secw = "SELECT `txt` FROM `".$db_prefix."lvo_misc` WHERE `ione`=1 AND `itwo`=1 AND `ithree`=1 LIMIT 1";
$dbconn = mysql_connect($db_host, $db_user, $db_pass);
if (!$dbconn) {
    HandleError("Unable to connect to DB: " . mysql_error());
	exit(0);
}
if (!mysql_select_db($db_name)) {
	HandleError("Unable to select database: " . mysql_error());
	exit(0);
}
$secw_res = mysql_query($q_secw);
if (!$secw_res) {
	HandleError("Security word error.");
	exit(0);
}
$secw_obj = mysql_fetch_object($secw_res);
$sec_word_site = $secw_obj->txt;
mysql_free_result($secw_res);

//

if (!isset($_POST['secw']) || trim($_POST['secw']) == "" || $sec_word_site != $_POST['secw']) {
	HandleError("Security word error.");
	exit(0);
}
	
// Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
		header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
		echo "POST exceeded maximum allowed size.";
		exit(0);
	}

// Settings
	//$save_path = getcwd() . "/uploads/";				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
	$save_path = $_REQUEST['folder'] . '/';
	$upload_name = "Filedata";
	$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
	$extension_whitelist = array("jpg", "gif", "png");	// Allowed file extensions
	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)
	
// Other variables	
	$MAX_FILENAME_LENGTH = 260;
	$file_name = "";
	$file_extension = "";
	$uploadErrors = array(
        0=>"There is no error, the file uploaded with success",
        1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3=>"The uploaded file was only partially uploaded",
        4=>"No file was uploaded",
        6=>"Missing a temporary folder"
	);


// Validate the upload
	if (!isset($_FILES[$upload_name])) {
		HandleError("No upload found in \$_FILES for " . $upload_name);
		exit(0);
	} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
		HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
		exit(0);
	} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
		HandleError("Upload failed is_uploaded_file test.");
		exit(0);
	} else if (!isset($_FILES[$upload_name]['name'])) {
		HandleError("File has no name.");
		exit(0);
	}
	
// Validate the file size (Warning: the largest files supported by this code is 2GB)
	$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
	if (!$file_size || $file_size > $max_file_size_in_bytes) {
		HandleError("File exceeds the maximum allowed size");
		exit(0);
	}
	
	if ($file_size <= 0) {
		HandleError("File size outside allowed lower bound");
		exit(0);
	}


// Validate file name (for our purposes we'll just remove invalid characters)
	$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
		HandleError("Invalid file name");
		exit(0);
	}


// Validate that we won't over-write an existing file
	if (file_exists($save_path . $file_name)) {
		HandleError("File with this name already exists");
		exit(0);
	}

// Validate file extension
	$path_info = pathinfo($_FILES[$upload_name]['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	foreach ($extension_whitelist as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;
			break;
		}
	}
	if (!$is_valid_extension) {
		HandleError("Invalid file extension");
		exit(0);
	}

// Validate file contents (extension and mime-type can't be trusted)
	/*
		Validating the file contents is OS and web server configuration dependant.  Also, it may not be reliable.
		See the comments on this page: http://us2.php.net/fileinfo
		
		Also see http://72.14.253.104/search?q=cache:3YGZfcnKDrYJ:www.scanit.be/uploads/php-file-upload.pdf+php+file+command&hl=en&ct=clnk&cd=8&gl=us&client=firefox-a
		 which describes how a PHP script can be embedded within a GIF image file.
		
		Therefore, no sample code will be provided here.  Research the issue, decide how much security is
		 needed, and implement a solution that meets the needs.
	*/


// Process the file
	/*
		At this point we are ready to process the valid file. This sample code shows how to save the file. Other tasks
		 could be done such as creating an entry in a database or generating a thumbnail.
		 
		Depending on your server OS and needs you may need to set the Security Permissions on the file after it has
		been saved.
	*/

	$tmp_filename = md5(rand() . 'a' . rand() . 'b' . time() . 'c' . rand());
	if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$tmp_filename)) {
		HandleError("File could not be saved.");
		exit(0);
	} else {
		// check image file
		$allow_mime = array ('image/gif', 'image/jpeg', 'image/png');
		$sz_info = getimagesize($save_path.$tmp_filename);
		if (empty($sz_info) || !isset($sz_info[0]) || !is_numeric($sz_info[0]) || !isset($sz_info[1]) || !is_numeric($sz_info[1]) || !isset($sz_info['mime']) || !in_array($sz_info['mime'], $allow_mime)) {
			unlink($save_path.$tmp_filename);
			HandleError("Invalid file type");
			exit(0);
		} else {
			rename ($save_path.$tmp_filename, $save_path.$file_name);
		}
	}


	exit(0);


/* Handles the error output. This error message will be sent to the uploadSuccess event handler.  The event handler
will have to check for any error messages and react as needed. */
function HandleError($message) {
	echo $message;
}
?>