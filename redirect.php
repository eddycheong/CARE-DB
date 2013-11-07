<?php
include 'db.php';

session_start();
echo "session login below <br>";
echo $_SESSION['login'], "<br>";
echo "session login above <br>";


//if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
//	header("Location: login.php");
//}
/* 
PHP code can be inserted anywhere, it is my practice to
write most of PHP code on top.

replace "TEMPLATE" with the specification for the page.

*/

////////////////////////////////
// MUST DO FOR EVERY NEW FILE //
////////////////////////////////

// Each new file you create (eg. newpage.php), execute this command
// in console: chmod 755 newpage.php

//===================
// CONNECT TO ORACLE
//===================
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {
	echo "Connected to Oracle\n";

	// TEMPLATE
	
	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

?>

<html>
<title>TEMPLATE</title>
<body>
</body>
</html>
