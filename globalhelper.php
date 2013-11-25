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
function attachHeader(){
	echo'<div id="clinic_info">';
	echo'<span><b>Welcome to CARE Clinic System</b></span><br>';
	echo'<span>7890 Apple St. Vancouver BC</span><br>';
	echo'<span>604-123-4567</span>';
	echo'</div>';
	echo'<a href="logout.php" id="logout">LogOut</a>';
}
function buildMenuTab() {

	if(getUserType() == "receptionist") {
		$array = array (
			"Home" => "appSchedule.php",
			"Patients" => "appPatientSearch.php",
			"Appointments" => "appCalendar.php",
			"Payment Stats" => "minMaxFee.php"	
		);
	} elseif( getUserType() == "doctor") {
		$array = array (
			"My Schedule" => "appSchedule.php",
			"Patients" => "appPatientSearch.php",
			"Patients Schedule" => "appSchedule.php"
		);
	}

	foreach($array as $key => $value) {
		echo '<div class = "menu-item">';
		echo '<p>'. $key .'</p>';
		echo '<a href = "'. $value .'" class = "fill-link"></a>';
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

function searchPartialName2($search,$table,$attr,$list){

	$split = " ";
	$arr = explode($split, $search);
	$num = sizeof($search);

	return searchByParts2($num, $arr, $table,$attr,$list);
}

function searchByParts2($num, $arr, $table, $attr, $list) {
	$ret = "select $list
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
?>
