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

				$("#content").find("[id^='tab']").hide();
				$("#tabs li:first").attr("id","current");
				$("#content #tab1").fadeIn();

				$('#tabs a').click(function(e) {
					e.preventDefault();
					if ($(this).closest("li").attr("id") == "current"){
					 return;
					}
					else{
					  $("#content").find("[id^='tab']").hide();
					  $("#tabs li").attr("id","");
					  $(this).parent().attr("id","current");
					  $('#' + $(this).attr('name')).fadeIn();
					}
				});

				$(".editor").jqte();

				$( ".tanggal" ).datepicker({ dateFormat: 'yy-mm-dd', showAnim: "bounce" });

				$('.form').ajaxForm({
					success : function (response) {
						alert(response);
						if(response='dosen'){
							window.location='dosen.php';
						}else{
							window.location='mhs.php';
						}
					}
				});

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
										echo "";
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

									<div id='materi_tambah' style='padding-bottom:20px;border-bottom:1px solid #d3d3d3;' >
										<form class="form form-horizontal" role="form" action="simpan.php?jenis=materi_simpan" method="post" enctype='multipart/form-data'>
											<input type='hidden' name='matkul' value='<?php echo $_GET['matkul']?>'/>
											<input type='hidden' name='kelas' value='<?php echo $_GET['kelas']?>'/>
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
															for($i=1;$i<=14;$i++){
																echo "<option value='$i'>$i</option>";
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="pertemuan" class="col-sm-2 control-label">Keterangan</label>
												<div class="col-sm-10">
													<textarea class='form-control editor' id='materi_ket' name='keterangan'  required autofocus></textarea>
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

									<div  id='materi_daftar' style='margin-top:20px;'>
										<?php
											$sql=mysql_query("select * FROM materi WHERE kelas_kode='".$_GET['kelas']."' ORDER BY materi_tanggal Desc");
											if(mysql_num_rows($sql)>=1){
												while($baris=mysql_fetch_assoc($sql)){
													$judul=preg_replace("/\s/","_",$baris['materi_judul']);
													$materiKode=$baris['materi_kode'];
													$pertemuan=$baris['materi_pertemuan'];
													$keterangan=$baris['materi_keterangan'];
													$tanggal=$baris['materi_tanggal'];

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
											?>
											<a href='#' class='unduh btn btn-primary pull-right' id="<?php echo $src ?>">Download Semua Materi</a>

										</div>
										<?php
											}else{
												echo "Belum ada Materi yang diupload";
											}
										?>
									</div>
									<div  style='clear:both'></div>

							<div id="tab2">
								<h2 style='text-align:center;margin-bottom:50px;'>Mata Kuliah <?php echo $matkulNama;?></h2>

								<div id='tugas_tambah' style='border-bottom:1px solid #d3d3d3;padding-bottom:20px;'>
									<form class="form form-horizontal" role="form" action="simpan.php?jenis=tugas_simpan" method="post" enctype='multipart/form-data'>
										<input type='hidden' name='matkul' value='<?php echo $_GET['matkul']?>'/>
										<input type='hidden' name='kelas' value='<?php echo $_GET['kelas']?>'/>

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
														for($i=1;$i<=14;$i++){
															echo "<option value='$i'>$i</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="pertemuan" class="col-sm-2 control-label">Keterangan</label>
											<div class="col-sm-10">
												<textarea class='form-control editor' id='materi_ket' name='keterangan'  required autofocus></textarea>
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

								<div id='tugas_daftar' style='margin-top:20px;'>
									<?php
										$sql=mysql_query("select * FROM tugas WHERE kelas_kode='".$kelasKode."' ORDER BY tugas_tanggal Desc");
										$countTugas=mysql_num_rows($sql);
										if($countTugas>=1){
											while($baris=mysql_fetch_assoc($sql)){
												$judul=preg_replace("/\s/","_",$baris['tugas_judul']);
												$pertemuan=$baris['tugas_pertemuan'];
												$keterangan=$baris['tugas_keterangan'];
												$tanggal=$baris['tugas_tanggal'];
												$deadline=$baris['tugas_deadline'];
												$tugasKode=$baris['tugas_kode'];

												echo "<div class='panel panel-primary' style='width:100%;'>
													<div class='panel-heading'><h3 class='panel-title'>".$baris['tugas_judul']." - [Pertemuan Ke-".$pertemuan."]</h3></div>
													<div class='panel-body'>
														<p class=''><blockquote><small>".$keterangan."</small></blockquote>
														<p><b>Deadline : </b><i><small>". $deadline."</small></i>
															<b><a class='view_tugas pull-right' data-toggle='modal' data-target='#view_mhs'  style='cursor:pointer;' href='mhs_tugas.php?matkul=".$matkulKode."&kode=".$tugasKode."' >Daftar Tugas Mahasiswa</a></b>
														</p>
														<small><a href='Tugas/$matkulKode/Tugas/Pertemuan_".$pertemuan."_".$judul.".pdf' target='_blank'>Download File</a></small>
														<small class='glyphicon glyphicon-star'></small>
														<small><a class='komentar_tambah' style='cursor:pointer;' id='tugas_$pertemuan'>Komentar</a></small>
														<small class='glyphicon glyphicon-star'></small>
														<small><a class='tugas_ubah' style='cursor:pointer;' id='ubah_$pertemuan'>Ubah Tugas</a></small>
														<i><small class='pull-right'>". $tanggal."</small></i>
													</div>

													<div class='panel-footer tugas_$pertemuan' style='display:none;'>";
														echo "<ol id='update".$baris['tugas_kode']."' class='timeline' style='padding:0;overflow:auto; height:300px;'>";
														$sqlKomentar=mysql_query("select * from komentar where komentar_kode=".$baris['tugas_kode']." AND komentar_jenis='1'");
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
														echo "<textarea style='margin:0;width:100%' placeholder='Komentar' class='form-control komentar_tugas' id='".$baris['tugas_kode']."'></textarea>
													</div>
												</div>";

												$src="./Tugas/$matkul/Tugas";
												echo "<button class='unduh btn btn-primary pull-right' id='$src'>Download Semua Tugas</button>

												<div style='clear:both'></div>";
											}
										}else{
											echo "Belum ada tugas yang dimasukkan";
										}
									?>

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
										$src="./Tugas/000001/Tugas";
									?>
									<div class='kanan' style='margin-top:60px; width:100%;'>
										<div><button class='unduh' id="<?php echo $src ?>" style='float:right;'>Download Semua Tugas</button></div>
								</div>
									</div>


							</div>
					</div>
					<?php
						}else{
							echo "Silahkan Pilih Matakuliah yang ada di Menu sebelah kiri";
						}
					?>
				</div>
			</div>
		</div>
		<div id='hasil'></div>

	</body>
</html>
