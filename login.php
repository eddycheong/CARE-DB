<?php
include "global.php"; // Oracle login information
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

//echo $_SESSION['user'], "<br>";

//ini_set('session.gc_probability', 1);
// If the user is already logged in, redirect them their default page

if (isset($_SESSION['login'])) {
	header("Location: appSchedule.php");
}

// Server Code Implementation goes here
// eg. Oracle login

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$uname = $_POST['usr'];
	$pword = $_POST['pwd'];

	$errmsg = "";

	//$uname = htmlspecialchars($uname);
	//$pword = htmlspecialchars($pword);

	//===================
	// CONNECT TO ORACLE
	//===================

	if($c = oci_connect($ora_usr, $ora_pwd, "ug")) {
		// Successfully connected to oracle

		// Check if username and password match in either
		// employee table or doctor table. No duplicates.
		$query = "select loginid
			 from employee
			 where loginid = '". $uname ."' and
			 password = '". $pword ."'
			 union
			 select loginid
	 		 from doctor
			 where loginid = '". $uname ."' and
			 password = '". $pword ."'";
		
		$s = oci_parse($c, $query);
		oci_execute($s);
		oci_fetch_all($s, $res);

		$check = oci_num_rows($s);
		oci_free_statement($s);

		// Check if the valid user is a doctor
		if($check == 1)	{
			$query = "select *
				  from doctor
				  where loginid = '". $uname ."'";
	
			$s = oci_parse($c, $query);
			oci_execute($s);
			$res = oci_fetch_array($s, OCI_BOTH);

			if(oci_num_rows($s) == 1)
				$_SESSION['doctor'] = $res['EID'];
			oci_free_statement($s);
		}

		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	} 

	// Check if username and password matched with anything from oracle
	if ($check == 1) {
		// A match in the database. Valid login
		$_SESSION['login'] = "$uname";
		header ("Location: appSchedule.php");
			
	} else {
		// No match found from the database
		// Incorrect username or password
		$errmsg = "Invalid login. Username or password is incorrect";
	}	
}
?>
<html>
<head>
	<title>Login</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>

<body>

<div id = "header">
	<div id="clinic_info">
	<span><b>Welcome to CARE Clinic System</b></span><br>
	<span>7890 Apple St. Vancouver BC</span><br>
	<span>604-123-4567</span>
	</div>
</div>
<div id = "menu-nav"></div>
<div id = "content">
<center>
	<?php
		echo $errmsg, "<br>";
	?>
	<form name = "" method = "post">
		<span>UserName: </span>
		<input type = "text" name ="usr" value"">
		<br>
		<span>Password: </span>
		<input type = "password" name = "pwd" value "" maxlength="16">
		<br>
	<input type = "submit" name = "slogin" value = "Login">
	</form>
</center>
</div>

<div id = "footer"></div>

</body>
	
</html>
