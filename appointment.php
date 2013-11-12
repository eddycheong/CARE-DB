<?php
include "global.php";
include "globalhelper.php";
include "links.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
	header("Location: login.php");
}

//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php

//===================
// CONNECT TO ORACLE
//===================
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

	// TEMPLATE
	// Implement any sql queries you desire to obtain from oracle

	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

?>

<!--Design the page below-->
<html>
<head>
	<title>Appointment</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body>
	<div id = "header">
		<h1 style = "margin-bottom: 0;"> Appointment </h1>
	</div>

	<div id = "side-panel">
	<?php
		// assign arr based on user type
		$arr = $rArr;
		
		buildSideLink($arr);
	?>
	</div>

	<div id = "content">
		Content appears here
	</div>
<!-- Need to learn divs, work on UI later-->
<!--	<div id = "leftMargin">
	</div>

	<div id = "footer">
	</div>
-->
</body>
</html>
