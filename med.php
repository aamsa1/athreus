<?php
	session_start();
	//error_reporting(0);
	include "timeout.php";
	include"inc/fungsi_flash.php";
	
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

	<title><?php echo (!empty($title)) ? $title : 'Selamat Data di Halaman Administrator'; ?></title>

	<!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">-->

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/theme-user.css" rel="stylesheet">
    <link href="css/bootstrap-datepicker3.css" rel="stylesheet">

    <link rel="stylesheet" href="js/jquery-ui/jquery-ui.css">
	<link href="css/ui/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="js/datatables/datatables.min.css"/>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/jquery-ui/jquery-ui.min.js"></script>

    <script type="text/javascript" src="js/datatables/datatables.js"></script>
    <script type="text/javascript" src="js/datatables/DataTables-1.10.15/js/dataTables.bootstrap.js"></script>

</head>
<body>
	
	<div class="container">
		<!-- Fixed navbar -->
	    <nav class="navbar navbar-default">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	    			<span class="sr-only">Toggle navigation</span>
	    			<span class="icon-bar"></span>
	    			<span class="icon-bar"></span>
	    			<span class="icon-bar"></span>
				</button>
				<a href="?mod=home" class="navbar-brand">QR Code</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Dashboard</a></li>
					<li><a href="med.php?mod=qrcode">Data QR Code</a></li>
					<li><a href="med.php?mod=qrcode&act=print">Print QR Code</a></li>
	      		</ul>

	      		<ul class="nav navbar-nav navbar-right">
		    		<!--<li><a href="#">Pertanyaan <label class="label label-danger">0</label></a></li>-->
		    		<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User, Username <span class="caret"></span></a>
						<ul class="dropdown-menu">
			                <li><a href="logout.php">Logout</a></li>
	          			</ul>
	        		</li>
		    	</ul>
	    	</div><!--/.nav-collapse -->
	      	
	    </nav>

	    <div style="margin:0 ;background-color:#FFF;padding:10px;">
	    	<?php
            	flash('example_message');

            	include"content.php"; 
            ?>
	    </div>
    </div>


    <div class="container">
    	<hr>
    	<div style="margin:0 15px;">
	    	<p>Copyright &copy; All Right Reserved</p>
	    </div>
    </div>

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