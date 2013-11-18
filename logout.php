<?php
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));

session_start();
session_destroy();
header("Location: login.php");
?>
