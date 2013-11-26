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

$pid = $_POST['pid'];

//===================
// CONNECT TO ORACLE
//===================
$err_msg = '';

if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['update'])) {
	$pname = trim($_POST["pname"]);
	$address = trim($_POST["address"]);
	$phone = trim($_POST["phone"]);
	$email = trim($_POST["email"]);
	$carecard = trim($_POST["carecard"]);

	$cond = true;
	if($carecard != null && strlen($carecard) != 10){
		$err_msg.= "Please enter <b>10 digits</b> of the carecard number.<br>";	
		$cond = false;
	}
	if($phone != null && strlen($phone) != 10){
		$err_msg.="Please enter the proper number.<br>";
		$cond = false;
	}
	if($email != null && strpos($email, '@') == false){
		$err_msg.="Please enter the proper email.<br>";
		$cond = false;
	}
	
	
	if($pname != null && $address != null && $phone != null && $email != null && $carecard != null && $cond){
		$query = "update patient set pname = '".$pname."', address = '".$address."', phone = '".$phone."', email = '".$email."', carecard = '".$carecard."' where pid = ".$pid;
		echo $query;
	//===================
	// CONNECT TO ORACLE
	//===================
		if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

			// Template search query, replace table and attribute	
				
			$s = oci_parse($c, $query);
			$r = oci_execute($s);
			
			oci_commit($c);
			oci_close($c);
			header("Location: viewPatientProfile.php?pid=".$pid);
		
		} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
		}
	}
}

if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

	// Template search query, replace table and attribute	
	$query = "select *
		  from patient
		  where pid = ".$pid;

	$s = oci_parse($c, $query);
	oci_execute($s);
	$res = oci_fetch_array($s, OCI_BOTH);
	
	oci_close($c);		
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}
?>

<!--Design the page below-->
<html>
<head>
	<title>Edit Patient Information</title>
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
	<div id="error_msg" style="height:auto;">
		<?php echo $err_msg; ?>
	</div>

	<!--<div>
	<a href="appConfirm.php"> <input type = "submit" name = "submit" value = "Submit"></a>
	</div>-->
	<div id = "content">
		<form id = "insert" method= "post">
			<table class = "addedit">
				<tr>
					<th><label for="name">Name:</label></th>  
					<td><input type="text" name="pname" value = "<?php echo $res['PNAME'];?>"></td>
				</tr>
				<tr>
					<th><label for="address">Address:</label></th>
					<td><input type="text" name="address" value = "<?php echo $res['ADDRESS'];?>"/></td>
				</tr>
				<tr>
					<th><label for="phone">Phone#:</label></th>		
					<td> <input type="text" name="phone" value = "<?php echo $res['PHONE'];?>"></td>
				</tr>
				<tr>
					<th><label for="email">Email:</label></th>  
					<td><input type="text" name="email" value ="<?php echo $res['EMAIL'];?>" ></td>
				</tr>
				<tr>
					<th><label for="carecard">Carecard#:</label></th>   
					<td><input type="text" name="carecard" value ="<?php echo $res['CARECARD'];?>" ></td>
				</tr>
	</table>
				<input type = "hidden" name = "update" value = true>
				<input type = "hidden" name = "pid" value = "<?php echo $res['PID'];?>">
				<input id = "search" type = "submit" name = "submit" value = "Submit">
		</form>
		
	</div>

	<div id = "footer"></div>
</body>
</html>
