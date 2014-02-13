<?php
// pidiblog - minimalistic blog (txt version)
// Copyright (C) 2008  Jindrich Skacel
//
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.

// include config and languages file
require_once("config.php");

// login
session_start();
	if ($_SESSION['auth'] OR $_COOKIE["login"]==md5($password)) {
		if (Time()<$_SESSION['auth']['time'] OR $_COOKIE["login"]==md5($password)) {
				$_SESSION['auth']['time']=Time()+3600;
		} else {
			Session_Destroy();
			// login time expired
			Header('Location: ?menu=login&err=1');
		}
	}
if ($_POST["submit"]==$lang["submit_login"]) {
		if ($_POST["password"]==$password) {
			if($_POST["permanent"]=="1") {
				$login=md5($password);
				setcookie("login", $login, time()+(60*60*24*365));
			}
			$_SESSION['auth'] = Array (
				'time' => Time()+3600,
			);
			// if logged
			Header('Location: ?' . SID);
		} else {
			Header('Location: ?menu=login&err=2');
		}
	}
if ($_GET["menu"]=="logout"){
	Session_Destroy();
	setcookie("login", "", time()+(60*60*24*365));
	Header('Location: ?msg=1');
}

// include functions
require_once("functions.php");

// check api
if($_GET["menu"]=="api") {
	Actions($lang);
}

// if we want insert message
if($_POST["submit"]==$lang["submit_insert"]) {
	Insert($lang);
}
if($_POST["submit"]==$lang["submit_preview"]) {
	$msg=Messages("preview",$lang,$msg,$err);
} else {
	$msg=Messages("",$lang,$msg,$err);
}

$action=Actions($lang);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php if($title) { echo $title; } else { echo $blogname; } ?></title>
	<link rel="stylesheet" href="stylesheets/style.php" type="text/css">
	<link rel="alternate" type="application/rss+xml" title="RSS" href="./rss.php">
	<!--[if IE 7]>
			<link rel="stylesheet" type="text/css" href="stylesheets/ie7hacks.css" />
			<link rel="stylesheet" type="text/css" href="stylesheets/iehacks.css" />
	<![endif]-->
	<!--[if lt IE 7]>
			<link rel="stylesheet" type="text/css" href="stylesheets/iehacks.css" />
	<![endif]-->
	<script language="JavaScript" type="text/javascript">
		function delText(idname) {
			document.getElementById(''+idname+'').value='';
		}
	</script>
</head>
<body>
<?php if($showmenu) { ?>
	<div id="top">
		<div id="page_prev"><?php echo Pages($lang,"left"); ?></div>
		<div class="lefttop"></div>
		<div class="centertop"> 
			<?php if($_SESSION["auth"]!="") {
					echo '<a href="?">'.$lang["menu_insert"].'</a> | ';
					echo '<a href="?menu=logout">'.$lang["menu_logout"].'</a>';
				 } else {
					echo '<a href="?menu=login">'.$lang["menu_login"].'</a>';
				 }
			?> |
			<?php	if($_GET["menu"]!="about" AND !isset($_GET["cmt"]) AND !isset($_GET["date"])) {
						echo '<a href="?menu=about">'.$lang["menu_about"].' ';
						if($about) { 
							echo $about; 
						} else { 
							echo $blogname;
						} 
					} elseif(isset($_GET["cmt"]) OR $_GET["menu"]=="about") {
						echo '<a href="?">'.$lang["menu_back"].'</a>';
					} elseif(isset($_GET["date"])) {
						echo '<a href="?">'.$lang["current_month"].'</a>';
					}
			?></a>
			<?php if($showrss) {
					echo ' | <a href="./rss.php">'.$lang["menu_rss"].'</a>';
				 }
			?>
			</div>
		<div class="righttop"></div>
		<div id="page_next"><?php echo Pages($lang,"right"); ?></div>
	</div>
<?php } ?>
	<div class="cleaner"></div>
	<div id="main">
		<?php echo $msg; echo $action; ?>
		<h1><?php 
				if($_GET["menu"]=="about") {
					echo $lang["menu_about"].' '.$about;
				 } else {
					echo $blogname;
				 } ?></h1>
			<?php
				if(IsSet($_GET["cmt"])) {
					include("./comments.php");
				}
				else {
					if($_GET["menu"]=="about") {
						echo About($lang);
					 } else {
						echo Show($lang);
					 } 
				}
			?>
	</div>
</body>
</html>
