<?php
include "global.php";
include "globalhelper.php";
include "links.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) || $_SESSION['login'] == '')) {
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Obtain the search statement
	$search = $_POST['search'];
	//echo $search;

	// Break down the string into pieces
	$pieces = explode(" ", $search);
	$n_pieces = sizeof($pieces);

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		$query = searchByParts($n_pieces, $pieces);
		$s = oci_parse($c, $query);
		oci_execute($s);
		
		//Oracle Fetches
		$n_rows = oci_fetch_all($s, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}
?>

<!--Design the page below-->
<html>
<head>
	<title>Template</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<h1 style = "margin-bottom: 0;"> Template </h1>
	</div>
	<!--
	<div id = "side-panel">
	<?php
		// assign arr based on user type
		
		//buildSideLink($arr);
	?>
	</div>
	-->

	<div id = "content">
		Content appears here
		<form id = "search" name ="" method= "post">
			<input type = text name = "search" value "">
		</form>

		<?php
		// sample code to use result from search
			echo '<center>';
			echo '<table border="1">';
			echo '<tr>';
			echo '<th>EID</th>';
			echo '<th>Employee Name</th>';
			echo '</tr>';
			for($i = 0; $i < $n_rows; $i++) {
				echo '<tr>';
				echo '<th>'. $res[$i]['EID'] .'</th>';
				echo '<th>'. $res[$i]['ENAME'] .'</th>';
				echo '</tr>';
			}
			echo '</table>';
			echo '</center>';
		?>

	</div>
<!-- Need to learn divs, work on UI later-->
<!--	<div id = "leftMargin">
	</div>

	<div id = "footer">
	</div>
-->
</body>
</html>
