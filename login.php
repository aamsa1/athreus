<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>:: Login Administrator ::</title>

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
    <div class="container content">

        <div style="margin:0 15px;">

            <div class="row">
                <div class="col-md-12">

                    <h3 class="text-danger">Login Administrator</h3>
                    
                    <form action="cek_login.php" method="POST">
                        <div class="form-group">
                            <label for="usernm">Username :<br>
                            <span class="text-primary">Username administrator.</span></label>
                            <input type="text" name="usernm" class="form-control" id="usernm" placeholder="ketik username ...">
                            
                        </div>
                        <div class="form-group">
                            <label for="pass">Password :<br>
                            <span class="text-primary">Password Anda.</span></label>
                            <input type="password" name="passwd" class="form-control" id="pass" placeholder="ketik password ...">
                            
                        </div>
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Log In</button>
                        
                    </form> 

                </div>

            </div>
        </div>

        <div style="margin:0 15px;">
            <p>Copyright &copy; All Right Reserved</p>
        </div>
    </div>


    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>