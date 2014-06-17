<?php
	session_start();
	if(isset($_SESSION['jenis'])){
		if ($_SESSION['jenis']=="dosen"){
			echo "<script> window.location='dosen.php'</script>";
		}else if($_SESSION['jenis']=="mhs"){
			echo "<script> window.location='mhs.php'</script>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="images/stts.ico">
		<title>Learning Management System</title>
        <link rel="stylesheet" href="lib/css/bootstrap.css" media="screen">
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/style.css" />
		<script src="lib/js/jquery.js"></script>
		<script src="lib/js/jquery.form.js"></script>
		<script>
			$(document).ready(function(){
				$("#form").ajaxForm(function(){
					$.post("cek-login.php",{user:$("#nrp").val(),pass:$("#pass").val()},function(data){
						if(data=='mhs'){
							window.location='mhs.php';
						}else if(data=='dosen'){
							window.location='dosen.php';
						}else{
							alert(data);
						}
					});
				});
			});
		</script>
	</head>
	<body>
        <div class="container">
            <form  id="form" action="#" method="post" role="form" class="form-signin">
                <h2 class="form-signin-heading">Login</h2>
                <input type="username" id="nrp" class=" form-control" id="nrp" name="user" required autofocus placeholder="Username" >
                <input type="password" class="form-control" id="pass" name="pass" required autofocus placeholder="Password" >
                <button class="btn-primary btn btn-block" type="submit" id="login">Masuk</button>
            </form>
        </div>
	</body>
</html>
