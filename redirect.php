<?php
include 'db.php';
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

	$query = "select *
		  from doctor
		  where loginid = '". $_SESSION['login'] ."'"; 
	
	$s = oci_parse($c, $query);
	oci_execute($s);
	oci_fetch_all($s, $res);

	if(oci_num_rows($s) == 1)
		$_SESSION['doctor'] = true;

	oci_free_statement($s);

	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}


//TODO: Decide if each page will change view depending on user,
// or each page will be user-specific

// Redirect user depending on their status (or occupation)
if(isset($_SESSION['doctor'])) {
	// The user is a doctor
	header("Location: dashboard.php");
} elseif($_SESSION['login'] == 'admin') {
	// The user is an admin
	//header("Location: admindashboard");
} else {
	// The user is a receptionist
	header("Location: template.php");
}


?>
