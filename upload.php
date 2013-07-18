<?php

function redirect($section) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'atos.php?x=' . $section;
	header("Location: http://$host$uri/$extra");	
}

if (!isset($_FILES['userfile'])) {
	echo "ERROR!";
} else {
	$file = $_FILES['userfile'];
	var_dump($_FILES);
	var_dump($_POST);
	$filename = str_replace(' ','_',$file['name']);
	$tmp_path = $file['tmp_name'];

	//check the extension
	$file_parts = pathinfo($filename);
	$filename = $file_parts['filename'];

	switch($file_parts['extension']) {
		case "m4a":
			error_log("m4a");
			$target="/srv/http/writeable/audio/" . $filename;
			error_log($filename . " " . $tmp_path . " " . $target);
			exec('/usr/bin/faad -w ' . $tmp_path . " | /usr/bin/ffmpeg -i - " . $target . ".mp3");
			exec('/usr/bin/ls /srv/http/sounds/*.mp3 /srv/http/writeable/audio/*.mp3 > /srv/http/sounds/sounds_list');
		break;
	
		case "mp3":
			$file_parts_b = pathinfo($_POST["name"]);
			//TODO: THIS IS BAD!!! SHOULD not use input from user here
			$target="/srv/http/writeable/audio/" . $file_parts_b["filename"] . ".mp3";
			error_log("mp3 " . $tmp_path . " " . $target);
			move_uploaded_file( $tmp_path, $target);
			exec('/usr/bin/ls /srv/http/sounds/*.mp3 /srv/http/writeable/audio/*.mp3 > /srv/http/sounds/sounds_list');
		break;
		
		case "":
		case NULL:
		break;
	}
	redirect("live");

}


?>
