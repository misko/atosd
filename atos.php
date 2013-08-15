<?php

session_start();

$x=$_GET["x"];


function redirect($section) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'atos.php?x=' . $section;
	header("Location: http://$host$uri/$extra");	
}

if (isset($_POST["username"]) || isset($_POST["password"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];
	if ($username=="atos" && $password=="cookies") {
        	$_SESSION["username"] = $username;
		redirect("live");
	}
}


if (!isset($_SESSION['username'])) {
	//not authenticated
	if ($x!="login") {
		redirect("login");
	}
	require('login.php');
} else {
	//authenticated
	if ($x=="logout") {
		session_destroy(); 
		redirect("login");
	} else if ($x=="gallery") {
		require('gallery.php');
	} else if ($x=="upload") {
		require('upload_form.php');
	} else {
		//catch all
		require('live.php');
	}
}
?>
