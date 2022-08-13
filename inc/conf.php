<?php
	/**
	KONFIGURASI KONEKSI KE DATABASE
	BY 	: ADE INDRA SAPUTRA
	NIM : 8020110115
	*/

	//$host = "127.0.0.1";
	$host = "localhost";
	$user = "root";
	$pass = "";
	$db = "qr";
	
	$connect = new mysqli ($host, $user, $pass, $db) 
							or die("Tidak terkoneksi ke server ...!");

	

?>