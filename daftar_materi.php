<script>
			$(document).ready(function(){
				$(".komentar_materi").keyup(function(event) {
				  if ( event.which == 13 ) {
					event.preventDefault();
					var kode= $(this).attr("id");
					var komen=$(this).val();
					 $.post("simpan.php?jenis=simpan_komentar_materi",{kode:kode, komentar:komen}, function(data){
						$("ol#update"+kode).append(data);
						$("ol#update"+kode+" li:last").fadeIn("slow");
						$('textarea').val('');
					 });
				  };
				});

				$('#view_mhs').appendTo("body");
				$( ".komentar_tambah" ).click(function(){
				  	var materiId=$(this).attr("id");
					$( "."+materiId).show();
				});
			});
		</script>


<?php
	include("connect.php");
	$kelasKode=$_GET['kelas'];
	$sql=mysql_query("SELECT * FROM materi m
										JOIN kelas k
										on k.kelas_kode = m.kelas_kode
										WHERE k.kelas_kode='".$kelasKode."' ORDER BY materi_tanggal Desc");
	// $sql=mysql_query("SELECT * FROM materi WHERE kelas_kode='".$_GET['kelas']."' ORDER BY materi_tanggal Desc");
	// $sql=mysql_query("SELECT * FROM materi JOIN kelas on kelas_kode.matkul_kode = matkul_kode WHERE kelas_kode='".$_GET['kelas']."' ORDER BY materi_tanggal Desc");
	$countMateri=mysql_num_rows($sql);
	if(mysql_num_rows($sql)>=1){
		while($baris=mysql_fetch_assoc($sql)){
			$judul=preg_replace("/\s/","_",$baris['materi_judul']);
			$materiKode=$baris['materi_kode'];
			$pertemuan=$baris['materi_pertemuan'];
			$keterangan=$baris['materi_keterangan'];
			$tanggal=$baris['materi_tanggal'];
			$matkulKode=$baris['matkul_kode'];
			echo "<div class='panel panel-primary'style='width:100%;'>
					<div class='panel-heading'><h3 class='panel-title'>".$baris['materi_judul']." - [Pertemuan Ke-".$pertemuan."]</h3></div>
					<div class='panel-body'>
						<p class=''><blockquote><small>".$keterangan."</small></blockquote>
						<small><a href='Materi/$matkulKode/Pertemuan_".$pertemuan."_".$judul.".pdf' target='_blank'>Download File</a></small>
						<small class='glyphicon glyphicon-star'></small>
						<small><a class='komentar_tambah' style='cursor:pointer;' id='materi_$materiKode'>Komentar</a></small>
						<small class='glyphicon glyphicon-star'></small>
						<small><a class='materi_ubah' style='cursor:pointer;' id='ubah_$pertemuan'>Ubah Materi</a></small>
						<i><small class='pull-right'>". $tanggal."</small></i>
					</div>
					<div class='panel-footer materi_$materiKode' style='display:none;'>";
						echo "<ol id='update".$baris['materi_kode']."' class='timeline' style='padding:0;overflow:auto; height:300px;'>";
						//Menampilkan komentar dari materi tertentu
						$sqlKomentar=mysql_query("select * from komentar where komentar_kode=".$baris['materi_kode']." AND komentar_jenis='0'");
						while($barisKomentar=mysql_fetch_assoc($sqlKomentar)){
							$sqlNama=mysql_query("SELECT * FROM dosen where dosen_nip='".$barisKomentar['komentar_user']."'");
							if(mysql_num_rows($sqlNama)==0){
								$sqlNama=mysql_query("SELECT * FROM mhs where mhs_nrp='".$barisKomentar['komentar_user']."'");
							}
							$barisNama=mysql_fetch_assoc($sqlNama);
							$dosenNama=$barisNama['dosen_nama'];
							echo "<li class='panel-body' style='padding:0'><div style='border-bottom:1px solid #d3d3d3;padding:2px 0;'><b>".$dosenNama."</b> ";
							echo $barisKomentar['komentar_isi']."<br/><p style='font-size:9px;'><i>".$barisKomentar['komentar_tgl']."</i></div></li>";
						}

						echo "</ol>";
						echo "<textarea style='margin:0;width:100%' placeholder='Komentar' class='form-control komentar_materi' id='".$baris['materi_kode']."'></textarea>
					</div>
				</div>";
		}
		$src="./Materi/$matkulKode/";
		echo "<button class='unduh btn btn-primary pull-right' id='$src'>Download Semua Tugas</button>

		<div style='clear:both'></div>";
	}else{
		echo "Belum ada tugas yang dimasukkan";
	}
	?>
