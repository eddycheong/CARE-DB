<?php
include "db.php"; // Oracle login information

// Server Code Implementation goes here
// eg. Oracle login

//===================
// CONNECT TO ORACLE
//===================

if($c = oci_connect($ora_usr, $ora_pwd, "ug")) {
	// Successfully connected to oracle
	echo "Connected to Oracle\n";

	// Testing Oracle Queries
	$s = oci_parse($c, 'select * from players');
	oci_execute($s);
	oci_fetch_all($s, $res);
	//echo "<pre>\n";
	var_dump($res);
	//echo "</pre>\n";	

	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
} 
?>
<html>
<head>
<title>Login</title>
</head>

<body>

<center>
	<form name = "" method = "post" action ="template.php">
		<input type = "text" name ="usr" value"">
		<br>
		<input type = "password" name = "pwd" value "" maxlength="16">
		<br>
	<input type = "submit" name = "slogin" value = "Login">
	</form>
</center>

</body>
	
</html>
