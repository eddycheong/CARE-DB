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

$err_msg = '';

//Only when submit button is clicked
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
	$pname = $_POST["pname"];
	$address = $_POST["address"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$carecard = $_POST["carecard"];

	$pid = rand(1000, 9999);
	//$pid = getRandomPid();
	
	//Typechecking
	$err_msg = '';
	if($pname == null)  $err_msg.= "Please enter the name.<br>";
	if($address == null) $err_msg.="Please enter the address.<br>";
    if($phone == null) $err_msg.="Please enter the phone number.<br>";
    else{  if(strlen($phone) != 10) $err_msg.="Please enter the proper number.<br>";}
    if($email == null)  $err_msg.="Please enter the email.<br>";
    else{  if(strpos($email, '@') == false) $err_msg.="Please enter the proper email.<br>";}
    if($carecard == null) $err_msg.="Please enter the carecard number.<br>";
    else{  if(strlen($carecard) !=10) $err_msg.= "Please enter <b>10 digits</b> of the carecard number.<br>";}

    $compare = $pname != null && $address != null && $phone != null && $email != null;
    if($compare && $carecard != null && strlen($phone) == 10 && strpos($email, '@') == true && strlen($carecard) ==9) {

		//===================
		// CONNECT TO ORACLE
		//===================
		if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

			// Template search query, replace table and attribute
				$query = "insert into patient values (".$pid.", '".$pname."', '".$address."', '".$phone."', '".$email."', '".$carecard."')";
				$s = oci_parse($c, $query);
				oci_execute($s);
				oci_close($c);
				header("Location: appConfirm.php?pid=".$pid."&pname=". $pname."&phone=".$phone);
			
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
		<?php attachHeader(); ?>
	</div>
	<div id = "menu-nav">
        <?php buildMenuTab(); ?>
	</div>
	<div id = "content">
		<h3 id = "pagetitle"> New Patient </h3>
		<br><br><br>
		<div id="error_msg" style="height:auto;">
			<?php echo $err_msg; ?>
		</div>
		<form method= "post">
			<table class = "addedit">
				<tr>
					<th><label for="name">Name:</label></th>
					<td><input type="text" name="pname" value "" placeholder="Your name here"/></td>
				</tr>
				<tr>
					<th><label for="address">Address:</label></th>   
					<td><input type="text" name="address" value "" placeholder="eg. 1234 Your St."/></td>
				<tr>
					<th><label for="phone">Phone#:</label></th>   
					<td><input type="text" name="phone" value "" placeholder="eg. 7781234567"/></td>
				<tr>
					<th><label for="email">Email:</label></th>   
					<td><input type="text" name="email" value ""  placeholder="sample@gmail.com"/></td>
				<tr>
					<th><label for="carecard">Carecard#:</label></th>   
					<td><input type="text" name="carecard" value "" placeholder="eg. 012345678"/></td>
			</table>
			
			<input id = "search" type = "submit" name = "submit" value = "Submit">
		</form>
		
	</div>
	<div id = "footer"></div>
</body>
</html>
