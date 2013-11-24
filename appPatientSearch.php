<?php
include "global.php";
include "globalhelper.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();


// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
        header("Location: login.php");
}

//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php



$search = $_GET['search'];
$phone = $_GET['phone'];
$address = $_GET['address'];

//===================
// CONNECT TO ORACLE
//===================
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

$list = 'pid, pname' . $phone . $address . '';


	if(isset($_REQUEST['doc'])){
	$query = 'select '.$list.'
				from patient
				where not exists(
						select eid
						from doctor
						where eid not in(
							select eid
							from appointment
							where patient.pid = appointment.pid))';
        // Template search query, replace table and attribute
        } else {
		if(isset($_GET['search'])&& $search != "")
                $query = searchPartialName2($search, "patient", "pname", $list);
        else
		
                $query = "select $list
                          from patient";     
}
        $s = oci_parse($c, $query);
        oci_execute($s);

        //Oracle Fetches
        $n_rows = oci_fetch_all($s, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        oci_close($c);
} else {
        $err = oci_error();
        echo "Oracle Connect Error " . $err['message'];
}

function buildPatientList($num, $arr, $phone, $address) {

        if($num > 0) {
                echo '<table class = "center">';
                echo '<tr>';
                echo '<th>Patient Name</th>';
				if($address != null){
                echo '<th>Address</th>';
				}
				if ($phone !=null ){
                echo '<th>Phone Number</th>';
				}
                echo '</tr>';
                for($i = 0; $i < $num; $i++) {
                        echo '<tr>';
                        echo '<td>'. $arr[$i]['PNAME'] .'</td>';
						if($address != null){
                        echo '<td>'. $arr[$i]['ADDRESS'] .'</dh>';
						}
						if ($phone !=null ){
                        echo '<td>'. $arr[$i]['PHONE'] .'</td>';
						}
                        if(!(getUserType() == "doctor")) {
						
                                echo '<td>';
                                echo '<form method = "post" action = viewPatientProfile.php>';
                                echo '<button type = "submit" name = "pid" value ="'. $arr[$i]['PID'] .'">View Profile</button>';
                                echo '</form>';
				echo '<form method = "post" action = appPatientAppointments.php>';
                                echo '<button type = "submit" name = "pid" value ="'. $arr[$i]['PID'] .'">View Appointment</button>';
                                echo '</form>';
                                echo '</td>';
                        } else {
                                echo '<td>';
                                echo '<form style = "text-align: center;" method = "post" action = appMedicalRecords.php>';
                                echo '<button type = "submit" name = "addpatient" value ="'. $arr[$i]['PID'] .'">View Medical Record</button>';
                                echo '</form>';
                                echo '</td>';
        
                        }
                        echo '</tr>';
                }
                echo '</table>';
        } else {
                echo 'Search Resulted in No Matches';
        }
}

?>

<!--Design the page below-->
<html>
<head>
        <title>Patient Search</title>
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
                
				<br>
				<br>
				<form method = "get">
				Search: <input type = text name = "search">
				<br> <input type="checkbox" name="address" value=", address">
						View Address
						<input type="checkbox" name="phone" value=", phone">
						View Phone Number
						<input type="submit" value="Submit">
				<br>
				<button type = "submit" name = "doc" value = "all">Patients with appointments with all doctors</button>
			
				<br>
				<input type="submit" value="All Patients"><br>
				</form>
								

                <?php 
			
				buildPatientList($n_rows, $res, $phone, $address); ?>
        </div>
        <div id = "footer"></div>
</body>
</html>
