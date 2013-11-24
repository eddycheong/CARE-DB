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

// usertype test
$utype = getUserType();
echo $utype;
//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php

$pid = $_REQUEST['pid'];
//$pid = 3954;
//GLOBAL

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$pname = $_POST["pname"];
	$address = $_POST["address"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$carecard = $_POST["carecard"];

	$query = "update patient";
	$i = 0;
	if($pname != null){
		if($i == 0){
			$query .=" set ";
			$i += 1;
		}
		$query .= "pname = '".$pname."' ";
		//echo "add pname<br>";
	}
	if($address != null){
		if($i == 0){
			$query .=" set ";
			$i += 1;
		} else { $query .= ", ";}
		$query .= "address = '".$address."' ";
		//echo "add address<br>";
	}
	if($phone != null){
		if($i == 0){
			$query .=" set ";
			$i += 1;
		} else { $query .= ", ";}
		$query .= "phone = '".$phone."' ";
		//echo "add phone<br>";
	}
	if($email != null){
		if($i == 0){
			$query .=" set ";
			$i += 1;
		} else { $query .= ", ";}
		$query .= "email = '".$email."' ";
		//echo "add email<br>";
	}
	if($carecard != null){
		if($i == 0){
			$query .=" set ";
		} else { $query .= ", ";}
		$query .= "carecard = '".$carecard."' ";
		//echo "add carecard<br>";
	}
		
	$query .= "where pid =".$pid;

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute	
			echo $query."<br>";
			$s = oci_parse($c, $query);
			oci_execute($s);
			oci_close($c);
			
			header("Location: viewPatientProfile.php");
		
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}
?>

<!--Design the page below-->
<html>
<head>
	<title>Add Patient Information</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<h1 style = "margin-bottom: 10;"> Change Patient Info</h1>
	</div>
	<!--<div>
	<a href="appConfirm.php"> <input type = "submit" name = "submit" value = "Submit"></a>
	</div>-->
	<div id = "content">
		<form id = "insert" method= "post">
			<label for="name">Name:</label>   <input type="text" name="pname" value "" /><br/>
			<label for="address">Address:</label>   <input type="text" name="address" value "" size = "30"/><br/>
			<label for="phone">Phone#:</label>   <input type="text" name="phone" value "" /><br/>
			<label for="email">Email:</label>   <input type="text" name="email" value "" /><br/>
			<label for="carecard">Carecard#:</label>   <input type="text" name="carecard" value "" /><br/>
			
			<input type = "submit" name = "submit" value = "Submit">
		</form>
		
	</div>
	
<!-- Need to learn divs, work on UI later-->
<!--	<div id = "leftMargin">
	</div>

	<div id = "footer">
	</div>
-->
</body>
</html>