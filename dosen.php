<?php
	session_start();

	include("connect.php");
	if ($_SESSION['jenis']!="dosen"){
		echo "<script> alert('Anda tidak memiliki hak akses')</script>";
		echo "<script> window.location='index.php'</script>";
	}else{
		$nama=$_SESSION['nama'];
		$nip=$_SESSION['nip'];
	}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
		<link rel="shortcut icon" href="images/stts.ico">
		<title>Learning Management System</title>
        <link rel="stylesheet" href="lib/css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="lib/css/bootstrap.css" media="screen">
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/jquery-te-1.4.0.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/jquery.dataTables.css" />
		<link rel="stylesheet" href="lib/css/jquery-ui.css">

		<script src="lib/js/jquery.js"></script>
		<script src="lib/js/jquery.ui.js"></script>
		<script src="lib/js/jquery-te-1.4.0.min.js"></script>
		<script src="lib/js/jquery.form.js"></script>
		<script src="lib/js/jquery.dataTables.js"></script>
		<script src="lib/js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#content").find("[id^='tab']").hide(); // Hide all content
				$("#tabs li:first").attr("id","current"); // Activate the first tab
				$("#content #tab1").fadeIn(); // Show first tab's content

				$('#tabs a').click(function(e) {
					e.preventDefault();
					if ($(this).closest("li").attr("id") == "current"){ //detection for current tab
					 return;
					}
					else{
					  $("#content").find("[id^='tab']").hide(); // Hide all content
					  $("#tabs li").attr("id",""); //Reset id's
					  $(this).parent().attr("id","current"); // Activate this
					  $('#' + $(this).attr('name')).fadeIn(); // Show content for the current tab
					}
				});

				$(".editor").jqte();
				$( ".tanggal" ).datepicker({ dateFormat: 'yy-mm-dd', showAnim: "fold" });
				$("#tugas_daftar").load("daftar_tugas.php?kelas="+$("#kelas").val());
				$('.form').ajaxForm({
					success : function (response) {
						if(response=="tugas"){
							$("#tugas_daftar").load("daftar_tugas.php?kelas="+$("#kelas").val());
							alert("Tugas Berhasil Dimasukkan");
							$("#formtugas")[0].reset();
							$(".editor").jqteVal("");
						}else{
							$("#materi_daftar").load("daftar_materi.php?kelas="+$("#kelas").val());
							alert("Materi Berhasil Dimasukkan");
							$("#formateri")[0].reset();
							$(".editor").jqteVal("");
						}
					}
				});

				$("#materi_daftar").load("daftar_materi.php?kelas="+$("#kelas").val());
				$(".unduh").click(function(){
					var source=$(this).attr('id');
					$.get("download_folder.php",{src:source},function(data){
						$("#hasil").html(data);
					});
				});


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

				$(".komentar_tugas").keyup(function(event) {
				  if ( event.which == 13 ) {
					event.preventDefault();
					var kode= $(this).attr("id");
					var komen=$(this).val();
					 $.post("simpan.php?jenis=simpan_komentar_tugas",{kode:kode, komentar:komen}, function(data){
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

				$(function() {
					$( ".accordion" ).accordion({
						collapsible:true,
						active: false,
						heightStyle: "content"
					});
				});


			});
		</script>
	</head>
	<body>
		<div class="container" >
			<div class="row" >
				<!-- KIRI -->
				<div class="col-sm-4">
					<div class='panel panel-primary'>

						<?php
                            echo '<div class="panel-heading">';
                            echo '<h3 class="panel-title">';
							echo "Hallo : ".$nama;
                            echo '</h3>';
                            echo '</div>';

                            echo '<div class="panel-body">';
							echo "<p>".$nip."</p>";
							echo "<p><a href='logout.php'>Logout</a></p>";
                            echo '</div>';
						?>

					</div>
					<div class='panel panel-primary'>
                        <div class="panel-heading">
							<h3 class="panel-title">Mata Kuliah</h3>
                        </div>
						<div class="panel-body">
							<ul>
								<?php
									$sqlMatkul=mysql_query("SELECT m.matkul_nama as matkul_nama, m.matkul_kode as matkul_kode, ma.kelas_kode as kelas_kode
															FROM kelas ma
															INNER JOIN matkul m
															ON ma.matkul_kode=m.matkul_kode
															WHERE ma.dosen_nip='$nip'
															Group by ma.matkul_kode");
									$countMatkul=mysql_num_rows($sqlMatkul);
									if($countMatkul>=1){
										while($barisMatkul=mysql_fetch_assoc($sqlMatkul)){
											$kodeMatkul=$barisMatkul['matkul_kode'];
											$namaMatkul=$barisMatkul['matkul_nama'];
											$kelas=$barisMatkul['kelas_kode'];
											echo "<li><a href='?matkul=$kodeMatkul&kelas=$kelas'>$namaMatkul</a></li>";
										}
										echo "</div>";
									}else{
										echo "<p style='padding:5px; text-align:center'>Saat ini tidak ada mata kuliah yang Sedang Anda ajar,
										<br/> Untuk lebih jelasnya silahkan hubungi pihak admin</p>";
									}

								?>
							</ul>
						</div>
                    </div>
				<!-- KANAN -->
				<div class='col-sm-8'>
					<?php
					if(isset($_GET['matkul'])){
						$kelasKode=$_GET['kelas'];
						$matkulKode=$_GET['matkul'];
						$matkulNama=mysql_query("select * from matkul where matkul_kode='".$matkulKode."'");
						$matkulNama=mysql_fetch_assoc($matkulNama);
						$matkulNama=$matkulNama['matkul_nama'];
					?>
						<ul id="tabs">
							<li><a href="#" name="tab1" >Materi</a></li>
							<li><a href="#" name="tab2">Tugas</a></li>
						</ul>
					<?php
					}
					?>

					<div id="content">
						<?php
						if(isset($_GET['matkul'])){
						?>
							<div id="tab1" >
								<?php
									echo "<h2 style='text-align:center;margin-bottom:50px;'> Mata Kuliah  $matkulNama </h2>";
								?>

									<div id='materi_tambah' class='accordion' style='padding-bottom:20px;border-bottom:1px solid #d3d3d3;' >
										<h3>Tambah Materi</h3>
										<div>
											<form class="form form-horizontal" id="formateri" role="form" action="simpan.php?jenis=materi_simpan" method="post" enctype='multipart/form-data'>
												<input type='hidden' name='matkul' value='<?php echo $_GET['matkul']?>'/>
												<input type='hidden' name='kelas' id='kelas' value='<?php echo $_GET['kelas']?>'/>
												<div class="form-group">
													<label for="judulMateri" class="col-sm-2 control-label" required autofocus>Judul Materi</label>
													<div class="col-sm-10">
													  <input type="text" class="form-control" name='judul' id="judulMateri" placeholder="Judul Materi">
													</div>
												</div>
												<div class="form-group">
													<label for="pertemuan" class="col-sm-2 control-label">Pertemuan</label>
													<div class="col-xs-2">
													  <select id='materi_pertemuan' name='pertemuan' class='form-control input-sm '>
															<option value='-'>-</option>
															<?php
																for($i=1;$i<=16;$i++){
																	echo "<option value='$i'>$i</option>";
																}
															?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label for="pertemuan" class="col-sm-2 control-label">Keterangan</label>
													<div class="col-sm-10">
														<textarea class='form-control editor' name='keterangan'  required autofocus></textarea>
													</div>
												</div>
												<div class="form-group">
													<label for="pertemuan" class="col-sm-2 control-label">Upload File</label>
													<div class="col-sm-10">
														<input type='file' name='materi_file' id='materi_file' />
													</div>
												</div>
													<input type='submit' name='submit' id='submit_materi' class='btn btn-primary pull-right' value='Masukkan'/>
													<div style='clear:both;'></div>

											</form>
										</div>
									</div>

									<div  id='materi_daftar' style='margin-top:20px;'>
									</div>

									</div>
									<div  style='clear:both'></div>

							<div id="tab2">
								<h2 style='text-align:center;margin-bottom:50px;'>Mata Kuliah <?php echo $matkulNama;?></h2>

								<div id='tugas_tambah' class='accordion' style='border-bottom:1px solid #d3d3d3;padding-bottom:20px;'>
									<h3>Tambah Tugas</h3>
									<div>
										<form class="form form-horizontal" id="formtugas" role="form" action="simpan.php?jenis=tugas_simpan" method="post" enctype='multipart/form-data'>
											<input type='hidden' name='matkul'  id='matkul' value='<?php echo $_GET['matkul']?>'/>
											<input type='hidden' name='kelas'  id='kelas' value='<?php echo $_GET['kelas']?>'/>

											<div class="form-group">
												<label for="judul" class="col-sm-2 control-label" required autofocus>Judul Tugas</label>
												<div class="col-md-10">
													 <input type="text" class="form-control" name='judul' required autofocus placeholder="Judul Tugas">
												</div>
											</div>

											<div class="form-group">
												<label for="deadline" class="col-sm-2 control-label " required autofocus>Deadline</label>
												<div class="col-xs-3">
													 <input type="text" class="form-control tanggal" name='deadline' required autofocus placeholder="Tanggal Deadline">
												</div>
											</div>

											<div class="form-group">
												<label for="pertemuan" class="col-sm-2 control-label">Pertemuan</label>
												<div class="col-xs-2">
												  <select id='materi_pertemuan' name='pertemuan' class='form-control input-sm '>
														<option value='-'>-</option>
														<?php
															for($i=1;$i<=16;$i++){
																echo "<option value='$i'>$i</option>";
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="pertemuan" class="col-sm-2 control-label">Keterangan</label>
												<div class="col-sm-10">
													<textarea class='form-control editor'  name='keterangan' required autofocus></textarea>
												</div>
											</div>

											<div class="form-group">
												<label for="pertemuan" class="col-sm-2 control-label">Upload File</label>
												<div class="col-sm-10">
													<input type='file' name='tugas_file' id='tugas_file' />
												</div>
											</div>

											<input type='submit' name='submit' id='submit_materi' class='btn btn-primary pull-right' value='Masukkan'/>
											<div style='clear:both;'></div>
										</form>
									</div>
								</div>

								<div id='tugas_daftar' style='margin-top:20px;'>

								</div>

								<div class="modal fade bs-example-modal-lg" id="view_mhs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								  <div class="modal-dialog modal-lg">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Detail Tugas</h4>
									  </div>
									  <div class="modal-body">
										...
									  </div>
									  <div class="modal-footer">

									  </div>
									</div>
								  </div>
								</div>



								<!-- Daftar Tugas dalam bentuk tabel menggunakan datatable jquery plugin
								<div id='tugas_daftar' style='margin-top:20px; display:none;'>
									<button class='kanan' id='tugas_tambah_btn'> Tambah tugas </button><p style='clear:both;'></p>
									<table cellpadding="0" cellspacing="0" border="0" id="tugas_daftar_tbl" border="1px">
										<thead>
											<tr class='s'>
												<th>Judul</th>
												<th>Keterangan</th>
												<th width="10%">File</th>
												<th width="20%"></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="5" class="dataTables_empty"><center>Mengunduh Data ...</center></td>
											</tr>
										</tbody>
									</table>
									<?php
										//alamat yang akan diunduh
										$src="./Tugas/000001/Tugas";
									?>
									 Tombol untuk mengunduh file Tugas Semua pada matkul tertentu

									</div>
							</div>-->
					<?php

				}else{
							echo "<center><h3>Silakan Pilih Mata Kuliah</h3></center>";
							echo "<br/>";
							echo "<ul>";
									$sqlMatkul=mysql_query("SELECT m.matkul_nama as matkul_nama, m.matkul_kode as matkul_kode, ma.kelas_kode as kelas_kode
															FROM kelas ma
															INNER JOIN matkul m
															ON ma.matkul_kode=m.matkul_kode
															WHERE ma.dosen_nip='$nip'
															Group by ma.matkul_kode");

									$countMatkul=mysql_num_rows($sqlMatkul);

									if($countMatkul>=1){
										while($barisMatkul=mysql_fetch_assoc($sqlMatkul)){
											$kodeMatkul=$barisMatkul['matkul_kode'];
											$namaMatkul=$barisMatkul['matkul_nama'];
											$kelas=$barisMatkul['kelas_kode'];
											echo "<li><a href='?matkul=$kodeMatkul&kelas=$kelas'>$namaMatkul</a></li>";
										}
										echo "</div>";
									}else{
										echo "<p style='padding:5px; text-align:center'>Saat ini tidak ada mata kuliah yang Sedang Anda ajar,
										<br/> Untuk lebih jelasnya silahkan hubungi pihak admin</p>";
									}
							echo "</ul>";
						}
					?>
				</div>
			</div>
		</div>
		<div id='hasil'></div>

	</body>
</html>
