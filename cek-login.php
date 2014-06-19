<?php
	session_start();
	if (isset($_REQUEST["user"]) && isset($_REQUEST["pass"])){
		$uname = $_REQUEST["user"];
		$upass = $_REQUEST["pass"];
		if($uname == "" || $upass == ""){
			echo "Pastikan semua field terisi";
		}else{
			include("connect.php");
			$sqlMhs=mysql_query("SELECT * FROM mhs where mhs_nrp='$uname'",$con);
			$hitMhs=mysql_num_rows($sqlMhs);
			if($hitMhs>=1){
				$barisMhs=mysql_fetch_assoc($sqlMhs);
				if($barisMhs['mhs_password']!="$upass"){
					echo "Password Salah";
				}else{
					$nama=$barisMhs['mhs_nama'];
					$nrp=$barisMhs['mhs_nrp'];
					$_SESSION['username']="$uname";
					$_SESSION['nrp']="$nrp";
					$_SESSION['nama']="$nama";
					$_SESSION['jenis']="mhs";
					echo "mhs";
				}
			}else{
				$sqlDosen=mysql_query("SELECT * FROM dosen where dosen_username ='$uname'",$con);
				$hitDosen=mysql_num_rows($sqlDosen);

				if($hitDosen>=1){
					$barisDosen=mysql_fetch_assoc($sqlDosen);
					if($barisDosen['dosen_password']!="$upass"){
						echo "Password Salah";
					}else{
						$nip=$barisDosen['dosen_nip'];
						$nama=$barisDosen['dosen_nama'];
						$_SESSION['username']="$uname";
						$_SESSION['nip']="$nip";
						$_SESSION['nama']="$nama";
						$_SESSION['jenis']="dosen";
						echo "dosen";
					}
				}else{
					echo "Username belum terdaftar";
				}
			}
		}
	}
?>
