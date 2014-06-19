<link rel="stylesheet" type="text/css" media="screen" href="lib/css/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/jquery-te-1.4.0.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/jquery.dataTables.css" />
		<link rel="stylesheet" href="lib/css/jquery-ui.css">

		<script src="lib/js/jquery.js"></script>
		<script src="lib/js/jquery.ui.js"></script>
		<script src="lib/js/jquery-te-1.4.0.min.js"></script>
		<script src="lib/js/jquery.form.js"></script>
		<script src="lib/js/jquery.dataTables.js"></script>
		<script>
			$(document).ready(function(){

				$(".editor").jqte();
				$('.form').ajaxForm({
					success : function (response) {
						if(response.match(/dosen/)){
							var split = response.split("_");
							var matkul=split[1];
							alert("Data Berhasil Disimpan");
							window.location='dosen.php?matkul='+matkul;
						}else if(response.match(/mhs/)){
							var split = response.split("_");
							var matkul=split[1];
							var kelas=split[2];
							alert("Data Berhasil Disimpan");
							window.location='mhs.php?matkul='+matkul+'&kelas='+kelas;
						}else{
							alert(response);
						}
					}
				});

				$( ".komentar_tugas" ).keypress(function( event ) {
				  if ( event.which == 13 ) {
					var kode= $(this).attr("id");
					var komen=$(this).val();
					 $.post("simpan.php?jenis=simpan_komentar_tugas",{kode:kode, komentar:komen}, function(data){
						if(data.match(/sukses/)){
							var split = data.split("_");
							var matkul=split[1];
							window.location='mhs.php?matkul='+matkul;
						}
					 });
				  };
				});

			});
		</script>
	<div style='padding:5px 10px 0; '>
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<form class="form form-horizontal" role="form" action="simpan.php?jenis=tugas_upload" method="post" enctype='multipart/form-data' style='padding-bottom:10px;'>
		<?php
			include("connect.php");
			session_start();
			$nrp=$_SESSION['nrp'];
			$kode=$_GET['kode'];
			$matkul=$_GET['matkul'];
			$kelas=$_GET['kelas'];

			$sqlTugas=mysql_query("select * from tugas where tugas_kode='$kode'");
			$barisTugas=mysql_fetch_assoc($sqlTugas);
			$judul=$barisTugas['tugas_judul'];
			$keterangan=$barisTugas['tugas_keterangan'];
			$pertemuan=$barisTugas['tugas_pertemuan'];
			$deadline=$barisTugas['tugas_deadline'];
			$now=date("Y-m-d");

			echo "<div class='form-group'>
				<div class='col-sm-12'>
					<div class='alert alert-info'>
						<h4> $judul [Pertemuan Ke-$pertemuan]</h4>
						<h4><small>$keterangan</small></h4>
						<p><b>Deadline</b> $deadline</p>
					</div>
				</div>
			</div>";

		if($now<=$deadline){
		?>

			<input type='hidden' value='<?php echo $pertemuan?>' name='pertemuan'/>
			<input type='hidden' value='<?php echo $kode?>' name='kode'/>
			<input type='hidden' value='<?php echo $matkul?>' name='matkul'/>
			<input type='hidden' value='<?php echo $kelas?>' name='kelas'/>

			<div class="form-group">
				<label for="upload" class="col-sm-2 control-label">Upload Tugas</label>
				<div class="col-sm-8">
					<input type='file' name='tugas_file' id='tugas_file' />
				</div>
			</div>

			<div class="form-group">
				<label for="pertemuan" class="col-sm-2 control-label">Keterangan</label>
				<div class="col-sm-8">
					<textarea class='form-control editor' id='materi_ket' name='keterangan'  required autofocus></textarea>
				</div>
			</div>
			<div class="col-sm-11">
				<input type='submit' name='submit' id='submit_materi' class='btn btn-primary pull-right' value='Masukkan'/>
			</div>
			<div style='clear:both;'></div>
		<?php

		}else{
			echo "Anda tidak dapat mengupload tugas, karena telah sampai pada batas dedline pengumpulan Tugas";
		}
		?>
	</form>
	<div class="col-sm-12" style='margin-top:20px;padding-top:10px;padding-bottom:20px;border-top:1px solid #d3d3d3;'>
		<label div class='col-sm-12'> Daftar Tugas yang telah dikumpulkan : </label>
		<?php
			$sqlAllTugas=mysql_query("select mhs_nrp, tugas_tanggal from tugas_detail WHERE mhs_nrp='$nrp' AND tugas_kode='$kode' ORDER BY tugas_nilai DESC");
			$countTugas=mysql_num_rows($sqlAllTugas);

			if($countTugas>=1){
				while($rows=mysql_fetch_assoc($sqlAllTugas)){
					echo "<div class='col-sm-4'><a href='Tugas/$matkul/Pertemuan_$pertemuan/".$nrp."_".$countTugas.".pdf' target='_blank'>".$rows['mhs_nrp']." v.".$countTugas."</a></div>";
					echo "<div class='col-sm-8'><small>".$rows['tugas_tanggal']."</small></div>";
					$countTugas--;
				}
			}else{
				echo "Belum ada Tugas yang diupload";
			}
		?>
	</div>
	<div style='clear:both;'></div>
	</div>
