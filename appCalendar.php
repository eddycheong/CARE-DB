<?php
include "global.php";
include "globalhelper.php";
include "links.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
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

	// TODO: break down the search into substrings
	// needed?

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		// $query = "select *
		// 	 from table
		// 	 where attribute = '". $search ."'";
		// $s = oci_parse($c, $query);
		// oci_execute($s);
		
		//Oracle Fetches

		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}

$monthNames = Array("January", "February", "March", "April", "May", "June", "July",
		"August", "September", "October", "November", "December");

		if (!isset($_REQUEST["m"])) $_REQUEST["m"] = date("n");
		if (!isset($_REQUEST["y"])) $_REQUEST["y"] = date("Y");

		$currentMonth = $_REQUEST["m"];
		$currentYear = $_REQUEST["y"];
		$currentDay = $_REQUEST["d"];
		 
		$p_year = $currentYear;
		$n_year = $currentYear;
		$p_month = $currentMonth-1;
		$n_month = $currentMonth+1;
		 
		if ($p_month == 0 ) {
		    $p_month = 12;
		    $p_year = $currentYear - 1;
		}

		if ($n_month == 13 ) {
		    $n_month = 1;
		    $n_year = $currentYear + 1;
		}
		$days=array('1'=>"S",'2'=>"M",'3'=>"T",'4'=>"W",'5'=>"T",'6'=>"F",'7'=>"S");

		$calendar = '<table width="1000" style="padding:80px;">';
		$calendar .='<tr align="center">';
		$calendar .='<td bgcolor="#7DC3E3" style="color:#FFFFFF">';
		$calendar .='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
		$calendar .='<tr>';
		$calendar .='<td width="50%" align="left">  <a href="'. $_SERVER["PHP_SELF"] . "?m=". $p_month . "&y=" . $p_year. '" style="color:#FFFFFF">Prev</a></td>';
		$calendar .='<td width="50%" align="right"><a href="'. $_SERVER["PHP_SELF"] . "?m=". $n_month . "&y=" . $n_year. '" style="color:#FFFFFF">Next</a></td>';
		$calendar .='</tr>';
		$calendar .='</table>';
		$calendar .='</td>';
		$calendar .='</tr>';
		$calendar .='<tr>';
		$calendar .='<td align="center">';
		$calendar .='<table width="100%"  border="1" cellpadding="2" cellspacing="2">';
		$calendar .='<tr align="center">';
		$calendar .='<td colspan="7" bgcolor="#7DC3E3" style="color:#FFFFFF"><B>'. $monthNames[$currentMonth-1].' '.$currentYear.'</B></td>';
		$calendar .='</tr>';
		$calendar .='<tr >';

		for($i=1;$i<=7;$i++){
			$calendar .='<td align="center" height="100" bgcolor="#7DC3E3" style="color:#FFFFFF"><B>'. $days[$i]. '</B></td>';
		}

		$calendar .= '</tr>';

		$timestamp = mktime(0,0,0,$currentMonth,1,$currentYear);
		$maxday = date("t",$timestamp);
		$thismonth = getdate ($timestamp);
		$startday = $thismonth['wday'];
		for ($i=0; $i<($maxday+$startday); $i++) {
		    if(($i % 7) == 0 ) $calendar .= "<tr>";
		    if($i < $startday) $calendar .= "<td ></td>";
		    else $calendar .= "<td align='center' height='80' valign='middle' bgcolor='#AED5E4' height='20px'><a href='booking.php' class='calendar_days'>". ($i - $startday + 1) ."</a></td>";
		    if(($i % 7) == 6 ) $calendar .= "</tr>";
		}
		$calendar .='</table>';
		$calendar .='</td>';
		$calendar .='</tr>';
		$calendar .='</table>';
?>

<!--Design the page below-->
<html>
<head>
	<title>Appointment</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body>
	<div id = "header">
		<h1 style = "margin-bottom: 0;"> Appointment </h1>
	</div>
	<!--
	<div id = "side-panel">
	<?php
		// assign arr based on user type
		$arr = $rArr;
		
		//buildSideLink($arr);
	?>
	</div>
	-->
	<div id = "content">
		<?php
			echo $calendar;
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