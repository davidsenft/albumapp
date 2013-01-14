<?php

if (isset($_POST['type'])){
	
	$time = time();
	$type = $_POST['type'];
	$id = $_POST['id'];
	$sess = $_POST['sess'];
	
	$data = $sess."\t".$time."\t".$type."\t".$id."\n";
	file_put_contents('access.log', $data, FILE_APPEND);

}

?>