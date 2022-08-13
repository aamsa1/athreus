<?php
	session_start();
	//error_reporting(0);
	include "timeout.php";
	include"inc/fungsi_flash.php";
	include"inc/conf.php";
	
	if($_SESSION['login']==1){
		if(!cek_login()){
			$_SESSION['login'] = 0;
		}
	}
	if($_SESSION['login']==0){
	  header('location:logout.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>:: Print QR Code ::</title>

    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">-->

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/theme-login.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- Fixed navbar -->
    <div class="container">

	<?php
		//cek data login
		if(!isset($_SESSION['usernm']))
		{
			header("location:login.php");
		}

		include"inc/phpqrcode/qrlib.php";

		//proses mencetak gambar qrcode dan create ke folder ./qrcode
		$query = mysql_query("SELECT * FROM qrcode 
							ORDER BY id_qrcode DESC") or die(mysql_error());
		$temukan = mysql_num_rows($query);
		if($temukan > 0)
		{
			$path = "./qrcode/";

			while($d = mysql_fetch_assoc($query))
			{
				$file = $path.$d['code'].".png";
			    // outputs image directly into browser, as PNG stream 
			    QRcode::png($d['code'], $file, QR_ECLEVEL_H, 5);

			}
		}


		$rsqlcode = mysql_query("SELECT * FROM qrcode 
							ORDER BY id_qrcode DESC") or die(mysql_error());
		$found = mysql_num_rows($rsqlcode);
		if($found > 0)
		{
			echo"<div class='row'>";
			while ($c = mysql_fetch_assoc($rsqlcode)) {
				echo"<div class='col-md-2 text-center' style=\"border:1px solid #ccc;\">
					<img src='".$path.$c['code'].".png'><br>
					<small>".$c['code']."</small>

				</div>";
			}

			echo"</div>";
		}

			
	    


	    //echo QRcode::svg('PHP QR Code :)'); 
	    //QRcode::png('PHP QR Code :)');
	    
	?>


    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
    	$(document).ready(function(){
	        $('.table_id').DataTable();
		});
    </script>

    <!--
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugin/qrcodescane/lib/html5-qrcode.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugin/qrcodescane/lib/jsqrcode-combined.min.js"></script>-->

    
</body>
</html>