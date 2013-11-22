<?php
include "global.php";
include "globalhelper.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) || $_SESSION['login'] == '')) {
	header("Location: login.php");
}

//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Obtain the patient name
	$patient = trim($_POST['addpatient']);

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		//$query = searchPartialName($search, "employee", "ename");
		/*
		$query = "select *
				from has_medicalrecords
				where pname = '". $patient ."'";
		$s = oci_parse($c, $query);
		oci_execute($s);
		
		
		//Oracle Fetches
		$n_rows = oci_fetch_all($s, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		*/
		$patientID = $_POST["addpatient"];
		//Queries for has_medicalrecord, has_fHistory, contains_pHistory
		// based on pID
		
		$queryMedRec = "select *
						from has_medicalrecords
						where pid = $patientID";
		$sMedRec = oci_parse($c, $queryMedRec);
		oci_execute($sMedRec);
		
		$rowsMedRec = oci_fetch_all($sMedRec, $resultMedRec, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		$patient = $resultMedRec[0]['PNAME'];
		
		$queryFHistory = "select *
						from has_fhistory
						where pid = $patientID";
		$sFHistory = oci_parse($c, $queryFHistory);
		oci_execute($sFHistory);
		
		$rowsFHistory = oci_fetch_all($sFHistory, $resultFHistory, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		
		
		$queryPHistory = "select *
						from contains_phistory
						where pid = $patientID";
		$sPHistory = oci_parse($c, $queryPHistory);
		oci_execute($sPHistory);
		$rowsPHistory = oci_fetch_all($sPHistory, $resultPHistory, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		
		
		
		
		
		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}

// Helper Functions
function buildMedRecList($num, $arr, $patient) {
if($num == 0){
echo '<table class = "center">';
echo '<tr>';
echo '<td>Mecical Record is not Avaliable</td>';
echo '</tr>';
echo '</table>';
}else{

	echo '<table class = "center">';
	echo '<tr>';
	echo '<td>Medical Record for ' . $patient . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th>Allergies</th>';
	echo '<th>Emergency Contacts</th>';
	echo '</tr>';
	for($i = 0; $i < $num; $i++) {
		echo '<tr>';
		echo '<td>'. $arr[$i]['ALLERGIES'] .'</td>';
		echo '<td>'. $arr[$i]['EMERCONTACTS'] .'</td>';
		echo '</tr>';
	}
	echo '</table>';
	}
}

function buildFHistoryList($num, $arr, $patient){
if ($num == 0){
}else{
	echo '<table class = "center">';
	echo '<tr>';
	echo '<td>Family History for ' . $patient . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th>Family Member</th>';
	echo '<th>Relation to Patient</th>';
	echo '<th>Condition</th>';
	echo '</tr>';
	for($i = 0; $i < $num; $i++) {
		echo '<tr>';
		echo '<td>'. $arr[$i]['FNAME'] .'</td>';
		echo '<td>'. $arr[$i]['RELATION'] .'</td>';
		echo '<td>'. $arr[$i]['CONDITION'] .'</td>';
		echo '</tr>';
	}
	echo '</table>';
}
}
?>

<!--Design the page below-->
<html>
<head>
	<title>Template</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header"></div>
	<div id = "content">
	<div id = "Medical Record">
		<?php 
		buildMedRecList($rowsMedRec, $resultMedRec, $patient);
		?>	

	</div>
	<div id = "Family History">
	
	<?php 
		buildFHistoryList($rowsFHistory, $resultFHistory, $patient);
		//echo $queryFHistory;
		?>	
		
	
	</div>
	
	
</div>
	<div id = "footer"></div>
</body>
</html>
