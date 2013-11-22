<?php

function getUserType() {
	//TODO: Get the usertype admin, doctor, or receptionist
	if($_SESSION['login'] == "admin")
		return "admin";
	elseif(isset($_SESSION['doctor']))
		return "doctor";
	elseif(isset($_SESSION['login']))
		return "receptionist";

	//Not a valid user type
	return null;

}

function buildSideLink($array) {
	foreach ($array as $key => $value) {
		echo '<div id = "side-link">';
		echo '<a href = "'. $value .'" class = "fill-link">'. $key .'</a>';
		echo '</div>';
	}
}

function searchByParts($num, $arr, $table, $attr) {
	$ret = "select *
		from ". $table ."
		where ";
		
	for($i = 0; $i < $num; $i++) {
		$ret .= "regexp_like(". $attr .",'" .$arr[$i] ."', 'i')";

		// Prevent the last OR
		if(($i != ($num-1)))
			$ret .= " or ";
	}

	return $ret;
}

function searchPartialName($search, $table, $attr) {

	$split = " ";
	$arr = explode($split, $search);
	$num = sizeof($search);

	return searchByParts($num, $arr, $table,$attr);
}
?>