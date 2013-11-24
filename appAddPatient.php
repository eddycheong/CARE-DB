<?php
include "global.php";
include "globalhelper.php";
include "links.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) || $_SESSION['login'] != '')) {
	header("Location: login.php");
}

//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$pname = $_POST["pname"];
	$address = $_POST["address"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$carecard = $_POST["carecard"];

	$pid = rand(1000, 9999);
	//$pid = getRandomPid();
	
	$compare = $pname != null && $address != null && $phone != null && $email != null;
	if($compare && $carecard != null) {

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		
			
			$query = "insert into patient values (".$pid.", '".$pname."', '".$address."', '".$phone."', '".$email."', '".$carecard."')";
			$s = oci_parse($c, $query);
			oci_execute($s);
			oci_close($c);
			header("Location: appConfirm.php?pname=". $pname."&phone=".$phone);
		
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
	}
}
/* WILL WORK ON THIS LATER
function getRandomPid(){
	$pid = rand(1000, 9999);
	
	for($i=0; $i<$n_rows; $i++){
		if($pids[$i] == $pid)
			getRandomPid();
		}
		echo "working";		
		return $pid;
	}
}
*/
?>

<!--Design the page below-->
<html>
<head>
	<title>Add Patient Information</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<div id="error_msg"></div>
		<?php attachHeader(); ?>
	</div>
	<div id = "menu-nav">
        <?php buildMenuTab(); ?>
	</div>
	<div id = "content">
		<h2> New Patient </h2>
		<form id = "insert" method= "post">
			<table style="margin-right: auto; margin-left: auto;">
				<tr>
					<td><label for="name">Name:</label></td>
					<td><input type="text" name="pname" value "" /></td>
				</tr>
				<tr>
					<td><label for="address">Address:</label></td>   
					<td><input type="text" name="address" value "" size = "30"/></td>
				<tr>
					<td><label for="phone">Phone#:</label></td>   
					<td><input type="text" name="phone" value "" /></td>
				<tr>
					<td><label for="email">Email:</label></td>   
					<td><input type="text" name="email" value "" /></td>
				<tr>
					<td><label for="carecard">Carecard#:</label></td>   
					<td><input type="text" name="carecard" value "" /></td>
			</table>
			<input type = "submit" name = "submit" value = "Submit" style="margin-top:20px;">
		</form>
		
	</div>
	<div id = "footer"></div>
</body>
</html>
