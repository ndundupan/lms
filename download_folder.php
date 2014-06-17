<?php
	include "connect.php";
	include "function/recursive_zip.php";
	$src=$_GET['src'];
	$dst='file_zip';
	$z=new recurseZip();
	$file=$z->compress($src,$dst);

	$data=explode("/",$src);
	$count=count($data);
	$matkulKode=explode(".",$data[$count-2]);
	$matkulKode=$matkulKode[0];
	echo $matkulKode;
	$sql=mysql_query("SELECT matkul_nama FROM matkul WHERE matkul_kode='$matkulKode'");
	$baris=mysql_fetch_assoc($sql);
	$nama=$baris['matkul_nama'];
	$nama=preg_replace("/\s/","_",$nama);
	if($data[3]=='Tugas'){
		rename($file,"./$dst/Tugas_$nama.zip");
		echo "<script>window.location='./$dst/Tugas_$nama.zip'</script>";
	}else{
		rename($file,"./$dst/Materi_$nama.zip");
		echo "<script>window.location='./$dst/Materi_$nama.zip'</script>";
	}
?>
