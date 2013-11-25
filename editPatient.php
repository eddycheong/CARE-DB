<?php
include "global.php";
include "globalhelper.php";

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

$pid = $_REQUEST['pid'];
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
			
			//header("Location: appConfirm.php");
		
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
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
		<?php attachHeader(); ?>
	</div>

	<div id = "menu-nav">
		<?php buildMenuTab(); ?>
	</div>
	<h3 id = "pagetitle">Edit Patient's Profile</h3>
	<br><br><br>

	<!--<div>
	<a href="appConfirm.php"> <input type = "submit" name = "submit" value = "Submit"></a>
	</div>-->
	<div id = "content">
		<form id = "insert" method= "post">
			<table class = "addedit">
				<tr>
					<th><label for="name">Name:</label></th>  
					<td><input type="text" name="pname" value "" /></td>
				</tr>
				<tr>
					<th><label for="address">Address:</label></th>
					<td><input type="text" name="address" value "" size = "30"/></td>
				</tr>
				<tr>
					<th><label for="phone">Phone#:</label></th>		
					<td> <input type="text" name="phone" value "" /></td>
				</tr>
				<tr>
					<th><label for="email">Email:</label></th>  
					<td><input type="text" name="email" value "" /></td>
				</tr>
				<tr>
					<th><label for="carecard">Carecard#:</label></th>   
					<td><input type="text" name="carecard" value "" /></td>
				</tr>
	</table>
				<input id = "search" type = "submit" name = "submit" value = "Submit">
		</form>
		
	</div>

	<div id = "footer"></div>
</body>
</html>
