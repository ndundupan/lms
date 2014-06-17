<?php
	session_start();
	if (isset($_REQUEST["user"]) && isset($_REQUEST["pass"])){
		//mengisi variabel $uname dengan data pengiriman data user
		$uname = $_REQUEST["user"];
		//mengisi variabel $upass dengan data pengiriman data pass
		$upass = $_REQUEST["pass"];
		if($uname == "" || $upass == ""){
			//jika terdapat field yang tidak terisi
			echo "Pastikan semua field terisi";
		}else{
			include("connect.php");
			//melakukan pencarian pada tabel mahasiswa dimana mhs_nrp=$uname
			$sqlMhs=mysql_query("SELECT * FROM mhs where mhs_nrp='$uname'",$con);
			//menghitung hasil dari $sqlMhs
			$hitMhs=mysql_num_rows($sqlMhs);
			if($hitMhs>=1){
				//jika hasil dari sqlMhs lebih dari satu, kemudian melakukan pengambilan data mahasiswa
				$barisMhs=mysql_fetch_assoc($sqlMhs);
				//melakukan pengecekan pada password mhs apakah sama dengan $upass
				if($barisMhs['mhs_password']!="$upass"){
					//jika tidak sama maka mencetak ini
					echo "Password Salah";
				}else{
					//menyimpan nama mahasiswa
					$nama=$barisMhs['mhs_nama'];
					$nrp=$barisMhs['mhs_nrp'];
					//jika benar, menyimpan pada username, nama, dan jenis ke dalam session
					$_SESSION['username']="$uname";
					$_SESSION['nrp']="$nrp";
					$_SESSION['nama']="$nama";
					$_SESSION['jenis']="mhs";
					//echo mhs untuk dapat mengenali jenis user, agar dapat dialihkan ke halaman yang sesuai
					echo "mhs";
				}
			}else{
				//melakukan pengecekan pada tabel dosen diamana dosen_username=$uname
				$sqlDosen=mysql_query("SELECT * FROM dosen where dosen_username ='$uname'",$con);
				//melakukan jumlah hasil dari sqlDosen
				$hitDosen=mysql_num_rows($sqlDosen);

				if($hitDosen>=1){
					// jika hitDosen lebih sama dengan 1, kemudian mengambil data dosen
					$barisDosen=mysql_fetch_assoc($sqlDosen);
					//melakukan pengcekan pada data dosen_password apakah sama dengan $upass
					if($barisDosen['dosen_password']!="$upass"){
						//jika salah, menampilkan ini
						echo "Password Salah";
					}else{
						//menyimpan nama dosen
						$nip=$barisDosen['dosen_nip'];
						//menyimpan nama dosen
						$nama=$barisDosen['dosen_nama'];
						//jika benar, melakukan penyimpanan username, nama, jenis pada session seperti di bawah ini
						$_SESSION['username']="$uname";
						$_SESSION['nip']="$nip";
						$_SESSION['nama']="$nama";
						$_SESSION['jenis']="dosen";
						//echo dosen untuk dapat mengenali jenis user, agar dapat dialihkan ke halaman yang sesuai
						echo "dosen";
					}
				}
				}else{
					//apabila username tidak ada di tabel mhs dan tabel dosen
					echo "Username belum terdaftar";
				}
			}
		}
	}
?>
