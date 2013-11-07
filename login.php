<?php
include "db.php"; // Oracle login information

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

		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	} 

	// Check if username and password matched with anything from oracle
	if ($check == 1) {
		// A match in the database. Valid login
		// TODO: Save the session
		header ("Location: template.php");
			
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
</head>

<body>

<center>
	<?php
		echo $errmsg, "<br>";
	?>
	<form name = "" method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>" >
		<input type = "text" name ="usr" value"">
		<br>
		<input type = "password" name = "pwd" value "" maxlength="16">
		<br>
	<input type = "submit" name = "slogin" value = "Login">
	</form>
</center>

</body>
	
</html>
