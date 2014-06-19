<?php
	session_start();
	include("connect.php");
	include("function/cDatabase.php");
	$cDB = new cDatabase();

	if(isset($_POST)){
		$jenis=$_GET['jenis'];
		if($jenis=="materi_simpan"){
			$judul=$_POST['judul'];
			$kelas=$_POST['kelas'];
			$ket=$_POST['keterangan'];
			$pertemuan=$_POST['pertemuan'];
			$dosen=$_SESSION['nip'];
			$matkul=$_POST['matkul'];
			$sql=mysql_query("insert INTO materi VALUES ('','$kelas','$judul','$pertemuan','$ket', now())");
			if($sql){
					echo "insert INTO materi VALUES ('','$kelas','$judul','$pertemuan','$ket', now())";
					if(isset($_FILES["materi_file"])){
						$filename = $_FILES["materi_file"]["name"];
						$source = $_FILES["materi_file"]["tmp_name"];
						$type = $_FILES["materi_file"]["type"];
						if(!is_dir("./Materi/$matkul/")){
							mkdir("Materi/$matkul/",0777, true);
						}
						$filtarget_path = "./Materi/$matkul/".$filename;
						if(move_uploaded_file($source, $filtarget_path))
						{
							$judul=preg_replace("/\s/","_", $judul);
							rename( "$filtarget_path", "./Materi/$matkul/Pertemuan_".$pertemuan."_$judul.pdf" );
							echo "materi";
						}else{

							echo "Data Tidak Berhasil Dimasukkan";
						}
					}else{
						echo "materi";
					}
			}else{
				echo "insert INTO materi VALUES ('','$kelas','$judul','$pertemuan','$ket', now())";
				echo "Data Tidak Berhasil Dimasukkan";
			}

		}
	else if($jenis=="tugas_simpan"){
			$judul=$_POST['judul'];
			$keterangan=$_POST['keterangan'];
			$deadline=$_POST['deadline'];
			$kelas=$_POST['kelas'];
			$matkul=$_POST['matkul'];
			$pertemuan=$_POST['pertemuan'];
			$sql=mysql_query("insert INTO tugas VALUES ('','$kelas','$judul','$pertemuan','$keterangan','$deadline', now())");

			if($sql){

					if(isset($_FILES["tugas_file"])){
						$filename = $_FILES["tugas_file"]["name"];
						$source = $_FILES["tugas_file"]["tmp_name"];
						$type = $_FILES["tugas_file"]["type"];
						if(!is_dir("./Tugas/$matkul/Tugas/")){
							mkdir("./Tugas/$matkul/Tugas/",0777, true);
						}
						$filtarget_path = "./Tugas/$matkul/Tugas/".$filename;
						if(move_uploaded_file($source, $filtarget_path))
						{
							rename( "$filtarget_path", "./Tugas/$matkul/Tugas/Pertemuan_".$pertemuan."_$judul.pdf" );
							echo "tugas";
						}else{
							echo "Data Tidak Berhasil Dimasukkan";
						}
					}else{
						echo  "tugas";
					}
			}else{
				echo "Data Tidak Berhasil Dimasukkan";
			}

		}else if($jenis=="simpan_komentar_materi"){
			if(isset($_POST)){
				$kode=$_POST['kode'];
				$komentar = $_POST['komentar'];

				$sql=mysql_query("SELECT k.matkul_kode as matkul_kode, k.kelas_kode as kelas_kode FROM materi m Inner join kelas k ON m.kelas_kode=k.kelas_kode WHERE materi_kode='$kode'");
				$barisSql=mysql_fetch_Assoc($sql);
				$matkulKode=$barisSql['matkul_kode'];
				$kelasKode=$barisSql['kelas_kode'];

				if(isset($_SESSION['nip'])){
					$user=$_SESSION['nip'];
					$nama=$_SESSION['nama'];
				}else if(isset($_SESSION['nrp'])){
					$user=$_SESSION['nrp'];
					$nama=$_SESSION['nama'];
				}

				$date=date("Y-m-d H:i:s", time());

				$sql=mysql_query("INSERT INTO komentar VALUES ('$kode','$user','0','$komentar',now())");
				if($sql){
					echo "<li class='panel-body' style='padding:0'><div style='border-bottom:1px solid #d3d3d3;padding:2px 0;'><b>".$nama."</b> ";
					echo $komentar."<br/><p style='font-size:9px;'><i>$date</i></div></li>";
				}
			}
		}else if($jenis=="simpan_komentar_tugas"){
			$kode=$_POST['kode'];
			$komentar = $_POST['komentar'];

			$sql=mysql_query("SELECT * FROM tugas WHERE tugas_kode='$kode'");
			$barisSql=mysql_fetch_Assoc($sql);
			$matkulKode=$barisSql['matkul_kode'];

			if(isset($_SESSION['nip'])){
				$user=$_SESSION['nip'];
				$nama=$_SESSION['nama'];
			}else if(isset($_SESSION['nrp'])){
				$user=$_SESSION['nrp'];
				$nama=$_SESSION['nama'];
			}

			$date=date("Y-m-d H:i:s", time());

			$sql=mysql_query("INSERT INTO komentar VALUES ('$kode','$user','1','$komentar',now())");
			if($sql){
				echo "<li class='panel-body' style='padding:0'><div style='border-bottom:1px solid #d3d3d3;padding:2px 0;'><b>".$nama."</b> ";
				echo $komentar."<br/><p style='font-size:9px;'><i>$date</i></div></li>";
			}
		}else if($jenis=="tugas_upload"){
			$kode=$_POST['kode'];
			$keterangan = $_POST['keterangan'];
			$matkul=$_POST['matkul'];
			$kelas=$_POST['kelas'];
			$pertemuan = $_POST['pertemuan'];
			$nrp=$_SESSION['nrp'];
			$sqlTugasDetail=mysql_query("SELECT mhs_nrp FROM tugas_detail WHERE mhs_nrp='$nrp' AND tugas_kode='$kode'");
			$countNRP=mysql_num_rows($sqlTugasDetail);
			$countFile=($countNRP+1);

			$sql=mysql_query("INSERT INTO tugas_detail VALUES ('$kode','$nrp','$keterangan','',now())");
			if($sql){
				if(isset($_FILES["tugas_file"])){
						$filename = $_FILES["tugas_file"]["name"];
						$source = $_FILES["tugas_file"]["tmp_name"];
						$type = $_FILES["tugas_file"]["type"];
						if(!is_dir("./Tugas/$matkul/Pertemuan_$pertemuan/")){
							mkdir("./Tugas/$matkul/Pertemuan_$pertemuan/",0777, true);
						}
						$filtarget_path = "./Tugas/$matkul/Pertemuan_$pertemuan/".$filename;
						if(move_uploaded_file($source, $filtarget_path))
						{
							rename( "$filtarget_path", "./Tugas/$matkul/Pertemuan_".$pertemuan."/".$nrp."_".$countFile.".pdf" );
							echo "mhs_".$matkul."_".$kelas;
						}else{
							echo "Data Tidak Berhasil Dimasukkan";
						}
			}else{
				echo $kode;
			}
		}
	}
}
?>
