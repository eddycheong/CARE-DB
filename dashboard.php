<?php
include 'db.php';
include 'links.php';
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
	header("Location: login.php");
}

//===================
// CONNECT TO ORACLE
//===================
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

	// TEMPLATE
	
	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

?>

<html>
<head>
	<title>dashboard</title>
	<link rel="stylesheet" type="text/css" href="./styles/styling.css">
</head>
<body>
<div id = "header">
	<h1 style = "margin-bottom:0;" >Dashboard</h1>
</div>

<div id = "side-panel">
	<div id = "side-link">
		<a href = "template.php" class = "fill-link">Appointment</a>
	</div>
	<div id = "side-link">
		<a href = "template.php" class = "fill-link">Patients</a>
	</div>
	<div id = "side-link"> 
		StuffC
	</div>
</div>

<div id = "leftMargin">
</div>

<div id = "content">
Content appears here
</div>

<div id = "footer">
</div>

</body>
</html>
