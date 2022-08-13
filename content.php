<?php
	include"inc/conf.php";

	$mod = @$_GET['mod'];

	if ($mod == "dashboard") {
		include"dashboard.php";
	} elseif ($mod == "qrcode") {
		include"modul/qrcode/qrcode.php";
	}



?>