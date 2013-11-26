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
	
	// Obtaining Info
	
	$patientID = $_POST["addpatient"];
	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		//Queries
		$queryMedRec = "select *
						from patient
						where pid = $patientID";
		$sMedRec = oci_parse($c, $queryMedRec);
		oci_execute($sMedRec);
		
		$rowsMedRec = oci_fetch_all($sMedRec, $resultMedRec, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		$patient = $resultMedRec[0]['PNAME'];
		
		
		
		
		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}

// Helper Functions
function buildMedRecList($patient, $patientID) {

	echo '<table class = "med">';
	echo '<tr>';
	echo '<td>Add Medical Record for ' . $patient . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th>Enter Allergies</th>';
	echo '<th>Enter Emergency Contact</th>';
	echo '</tr>';
	
		echo '<tr>';
		echo '<form method = "post" action = appMedicalRecords.php>';
	echo '<INPUT TYPE ="hidden" NAME ="pname" value ="'.$patient.'">';
	echo '<td><INPUT TYPE="text" NAME="addallergies" SIZE="60" ></td>';
	echo '<td><INPUT TYPE="text" NAME="addemercontact" SIZE="60" ></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><button type = "submit" name = "addpatient" value ="'. $patientID .'">Enter</button></td>';
	echo '</form>';			

	echo '</tr>';
	
	echo '</table>';
	
}

?>

<!--Design the page below-->
<html>
<head>
	<title>Add Medical Records</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<?php attachHeader(); ?>
	</div>	
	<div id = "menu-nav">
                <?php buildMenuTab(); ?>
	</div>

	<div id = "content">
	<div id = "Medical Record">
		<?php 
		buildMedRecList($patient, $patientID);
		?>	

	</div>
	<div id = "Family History">
	
	<?php 
		
		?>	
		
	
	</div>
	<div id = "Patient History">
		<?php
	
	?>
	</div>
	
	
	
	
</div>
	<div id = "footer"></div>
</body>
</html>
