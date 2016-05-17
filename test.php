<?php
require_once 'support/config.php';
//  echo "<pre>";
// 	var_dump($_FILES);
// echo "</pre>";
// foreach ($_FILES['file'] as $key => $file) {
// 	// $temp_array=array();
// 		$files[]=array();
// 		// foreach ($file as $key2 => $field) {
// 		// 	# code...
// 		// }
// 		array('name','type','tmp_name','error','size');
// 	// echo "<pre>";
// 	// 	var_dump($file);
// 	// echo "</pre>";
// }
$files=reArrayFiles($_FILES['file']);
// echo "<pre>";
// 	var_dump($files);
// echo "</pre>";
foreach ($files as $key => $attachment) {
	
}
?>