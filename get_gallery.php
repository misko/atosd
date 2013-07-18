<?php

$images_array = glob('/srv/http/gallery/*.jpg',GLOB_BRACE);
foreach($images_array as $image) {
	$x=str_replace("/srv/http/gallery/","",$image);
	echo "gallery/" . $x . "\tgallery/thumbs/400_" . $x . "\tgallery/thumbs/50_". $x . "\n";
}
?>
