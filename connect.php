<?php
	$con=mysql_connect("localhost","root","root");
	if (!$con){
		die("gagal connection: ".mysql_error());
	}else{
		if(!mysql_select_db("kelas", $con)){
			die("gagal load db: ".mysql_error());
		}		
	}
?>