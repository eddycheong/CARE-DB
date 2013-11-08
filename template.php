<?php
include 'db.php';
include 'links.php';

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
<title>TEMPLATE</title>
<body>
</body>
</html>
