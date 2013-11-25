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

//===================
// CONNECT TO ORACLE
//===================
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

function createButton(){
	echo '<table id= "appTypeTable" style="padding-top:40px;">';
	echo '<tr>';
	echo '<td><a class="appTypeButton" style="margin-right:20px;" href="appAddPatientSearch.php?i='.$_GET['i'].'&dn='.$_GET['dn'].'&y='. $_GET['y'] . '&m=' . $_GET['m'] . '&d=' . $_GET['d'] . '&h='.$_GET['h'].'"><br>Appointment<br>for<br>Patients</a></td>';
	echo '<td><a class="appTypeButton" style="margin-left:20px;" href="appDoctorConfirm.php?i='.$_GET['i'].'&dn='.$_GET['dn'].'&y='. $_GET['y'] . '&m=' . $_GET['m'] . '&d=' . $_GET['d'] . '&h='.$_GET['h'].'"><br>Appointment<br>for<br>Doctors</a></td>';
	echo '</tr>';
	echo '</table>';
}

?>

<!--Design the page below-->
<html>
<head>
	<title>Template</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<!-- <div id="error_msg"></div> -->
		<?php attachHeader(); ?>
	</div>

	<div id = "menu-nav">
		<?php buildMenuTab(); ?>
	</div>
	<div id = "content">
		<?php createButton(); ?> 
	</div>

	<div id = "footer"></div>
</body>
</html>
