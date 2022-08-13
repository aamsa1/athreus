<?php
	session_start();
	include"../../inc/conf.php";
	include"../../inc/all_function.php";
	include"../../inc/fungsi_flash.php";


	if(!isset($_SESSION['usernm'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	$mod = @$_GET['mod'];
	$act = @$_GET['act'];

	if($mod == "qrcode" AND $act == "simpan")
	{
		$code = anti_inject($_POST['code']);
		$nama = anti_inject($_POST['nama']);
		$keterangan = anti_inject($_POST['keterangan']);
		$status = !empty($_POST['status']) ? $_POST['status'] : 'N';


		mysql_query("INSERT INTO qrcode(code,
										nama, 
										keterangan, 
										status) 
									VALUES('$code', 
											'$nama', 
											'$keterangan', 
											'$status')") or die(mysql_error());
		flash('example_message', '<strong>Informasi!</strong><p>Berhasil menambahkan qrcode.</p>');
		header("location:../../med.php?mod=qrcode");

	}

	elseif ($mod == "qrcode" AND $act == "edit") 
	{
		$code = anti_inject($_POST['code']);
		$nama = anti_inject($_POST['nama']);
		$keterangan = anti_inject($_POST['keterangan']);
		$status = !empty($_POST['status']) ? $_POST['status'] : 'N';

		mysql_query("UPDATE qrcode SET code = '$code', 
										nama = '$nama', 
										keterangan =  '$keterangan', 
										status = '$status' 
					WHERE id_qrcode = '$_POST[id]'") or die(mysql_error());


		flash('example_message', '<strong>Informasi!</strong><p>Berhasil mengubah qrcode.</p>');
		header("location:../../med.php?mod=qrcode");
	}

	elseif ($mod == "qrcode" AND $act == "hapus") 
	{
		mysql_query("DELETE FROM qrcode WHERE id_qrcode = '$_GET[id]'") or die(mysql_error());
		
		flash('example_message', '<strong>Informasi!</strong><p>Berhasil menghapus qrcode.</p>');
		header("location:../../med.php?mod=qrcode");
	}

?>