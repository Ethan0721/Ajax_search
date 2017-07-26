<?php

header ( 'Content-Type: application/json' );
require_once './DataBaseAdaptor.php';


if (isset ( $_GET ['name'] ) && isset ( $_GET ['title'] )) {
	$name = $_GET ['name'];
	$title = $_GET ['title'];
	$array = $movieTitles->get_both ( $title, $name);
} 

echo json_encode ( $array );

?>