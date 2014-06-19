<?php
	session_start();
	include("connect.php");
	if ($_SESSION['jenis']!="mhs"){
		echo "<script> alert('Anda tidak memiliki hak akses')</script>";
		echo "<script> window.location='index.php'</script>";
	}else{
		$nama=$_SESSION['nama'];
		$nrp=$_SESSION['nrp'];
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
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/jquery-te-1.4.0.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="lib/css/jquery.dataTables.css" />
		<link rel="stylesheet" href="lib/css/jquery.ui.datepicker.css"/>

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
				$( ".tanggal" ).datepicker({ dateFormat: 'yy-mm-dd', showAnim: "bounce" });
				$('.form').ajaxForm({
					success : function (response) {
						alert("Data Berhasil Disimpan")
						if(response='dosen'){
							window.location='dosen.php';
						}else{
							window.location='mhs.php';
						}
					}
				});

				$( ".komentar" ).keypress(function( event ) {
				  if ( event.which == 13 ) {
					var kode= $(this).attr("id");
					var komen=$(this).val();
					 $.post("simpan.php?jenis=simpan_komentar_materi",{kode:kode, komentar:komen}, function(data){
						if(data=='sukses'){
							window.location='mhs.php';
						}
					 });
				  };
				});

				$( ".komentar_tambah" ).click(function(){
				  	var materiId=$(this).attr("id");
					$( "."+materiId).show();
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

				$('#tugas_detail').appendTo("body");
				$(".unduh").click(function(){
					var source=$(this).attr('id');
					$.get("download_folder.php",{src:source},function(data){
						$("#hasil").html(data);
					});
				});

			});
		</script>
	</head>
	<body>
		<div class="banner-shell">
			<div class="banner-outer">
				<a href="#"><div class="banner-logo"></div></a>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class='panel panel-primary'>
						<?php
							echo '<div class="panel-heading">';
								echo '<h3 class="panel-title">';
									echo "Hallo: ".$nama;
								echo '</h3>';
                            echo '</div>';

							echo '<div class="panel-body">';
								echo "<p>".$nrp."</p>";
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
									$sql=mysql_query("SELECT m.matkul_kode as matkul_kode, m.matkul_nama as matkul_nama, ma.kelas_kode FROM mhs_kelas ma
									INNER JOIN kelas k
									ON ma.kelas_kode=k.kelas_kode
									INNER JOIN matkul m
									ON m.matkul_kode=k.matkul_kode
									WHERE ma.mhs_nrp='$nrp' AND ma.periode_kode='20132'");
									$countMatkul=mysql_num_rows($sql);
									if($countMatkul>=1){
										while($barisMatkul=mysql_fetch_assoc($sql)){
											$kodeKelas=$barisMatkul['kelas_kode'];
											$kodeMatkul=$barisMatkul['matkul_kode'];
											$namaMatkul=$barisMatkul['matkul_nama'];
											echo "<li><a href='?matkul=$kodeMatkul&kelas=$kodeKelas'>$namaMatkul</a></li>";
										}
									}else{
										echo "Anda Belum terdaftar pada kelas apapun. Untuk lebih jelasnya, silahkan menghubungi pihak Admin";
									}

								?>
							</ul>
						</div>
					</div>
				</div>
				<div class='col-sm-8'>
					<?php
						if(isset($_GET['matkul'])){
							echo '<ul id="tabs">
								<li><a href="#" name="tab1">Materi</a></li>
								<li><a href="#" name="tab2">Tugas</a></li>
							</ul>';
						}
						echo '<div id="content">';
						if(isset($_GET['matkul'])){
							$matkul=$_GET['matkul'];
							$kelas=$_GET['kelas'];

							echo '<div id="tab1">';
							$sql=mysql_query("select matkul_nama FROM matkul WHERE matkul_kode='$matkul'");
							$namaMatkul=mysql_fetch_assoc($sql);
							$namaMatkul=$namaMatkul['matkul_nama'];
							echo "<h2 style='text-align:center;margin-bottom:50px;'>Mata Kuliah $namaMatkul</h2>";

							echo "<div id='materi_daftar' style='margin-top:20px;'>";
							$sql=mysql_query("select * FROM materi WHERE kelas_kode='$kelas' ORDER BY materi_tanggal Desc");
							$countMateri=mysql_num_rows($sql);
							if($countMateri>=1){
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
											<small><a href='Materi/$matkul/Pertemuan_".$pertemuan."_".$judul.".pdf' target='_blank'>Download File</a></small>
											<small class='glyphicon glyphicon-star'></small>
											<small><a class='komentar_tambah' style='cursor:pointer;' id='materi_$materiKode'>Komentar</a></small>
											<i><small class='pull-right'>". $tanggal."</small></i>
										</div>
										<div class='panel-footer materi_$materiKode' style='display:none;'>";
											echo "<ol id='update".$baris['materi_kode']."' class='timeline' style='padding:0;overflow:auto; height:300px;'>";
											$sqlKomentar=mysql_query("select * from komentar where komentar_kode=".$baris['materi_kode']." AND komentar_jenis='0'");
											while($barisKomentar=mysql_fetch_assoc($sqlKomentar)){
												$sqlNama=mysql_query("SELECT * FROM dosen where dosen_nip='".$barisKomentar['komentar_user']."'");
												if(mysql_num_rows($sqlNama)<=0){
													$sqlNama=mysql_query("SELECT * FROM mhs where mhs_nrp='".$barisKomentar['komentar_user']."'");
													$barisNama=mysql_fetch_assoc($sqlNama);
													$userNama=$barisNama['mhs_nama'];
												}else{
													$barisNama=mysql_fetch_assoc($sqlNama);
													$userNama=$barisNama['dosen_nama'];
												}

												echo "<li class='panel-body' style='padding:0'><div style='border-bottom:1px solid #d3d3d3;padding:2px 0;'><b>".$userNama."</b> ";
												echo $barisKomentar['komentar_isi']."<br/><p style='font-size:9px;'><i>".$barisKomentar['komentar_tgl']."</i></div></li>";
											}

											echo "</ol>";
											echo "<textarea style='margin:0;width:100%' placeholder='Komentar' class='form-control komentar_materi' id='".$baris['materi_kode']."'></textarea>
										</div>
									</div>";
								}
								$src="./Materi/$matkul/";
								echo"<a href='#' class='unduh btn btn-primary pull-right' id='$src'>Download Semua Materi</a>";
								echo "<div style='clear:both'></div>";
							}else{
								echo "Belum ada materi yang diposting";
							}

						?>

						</div>
					</div>
						<div id="tab2">
							<h2 style='text-align:center;margin-bottom:50px;'>Mata Kuliah <?php echo $namaMatkul?></h2>
							<div id='tugas_daftar'>

								<?php
									$sql=mysql_query("select * FROM tugas WHERE kelas_kode='$kelas' ORDER BY tugas_tanggal Desc");
									while($baris=mysql_fetch_assoc($sql)){
										$judul=preg_replace("/\s/","_",$baris['tugas_judul']);
												$pertemuan=$baris['tugas_pertemuan'];
												$keterangan=$baris['tugas_keterangan'];
												$tanggal=$baris['tugas_tanggal'];
												$deadline=$baris['tugas_deadline'];

												echo "<div class='panel panel-primary' style='width:100%;'>
													<div class='panel-heading'><h3 class='panel-title'>".$baris['tugas_judul']." - [Pertemuan Ke-".$pertemuan."]</h3></div>
													<div class='panel-body'>
														<p class=''><blockquote><small>".$keterangan."</small></blockquote>
														<p><b>Deadline : </b><i><small>". $deadline."</small></i>
															<b><a class='tugas_detail_btn pull-right' data-toggle='modal' data-target='#tugas_detail'  style='cursor:pointer;' href='tugas_detail.php?kode=".$baris['tugas_kode']."&matkul=".$matkul."&kelas=".$kelas."'>Upload Tugas</a></b>
														</p>
														<small><a href='Tugas/$matkul/Tugas/Pertemuan_".$pertemuan."_".$judul.".pdf' target='_blank'>Download File</a></small>
														<small class='glyphicon glyphicon-star'></small>
														<small><a class='komentar_tambah' style='cursor:pointer;' id='tugas_$pertemuan'>Komentar</a></small>
														<i><small class='pull-right'>". $tanggal."</small></i>
													</div>

													<div class='panel-footer tugas_$pertemuan' style='display:none;'>";
														echo "<ol id='update".$baris['tugas_kode']."' class='timeline' style='padding:0;overflow:auto; height:300px;'>";
														$sqlKomentar=mysql_query("select * from komentar where komentar_kode=".$baris['tugas_kode']." AND komentar_jenis='1'");
														while($barisKomentar=mysql_fetch_assoc($sqlKomentar)){
															$sqlNama=mysql_query("SELECT * FROM dosen where dosen_nip='".$barisKomentar['komentar_user']."'");
															if(mysql_num_rows($sqlNama)==0){
																$sqlNama=mysql_query("SELECT * FROM mhs where mhs_nrp='".$barisKomentar['komentar_user']."'");
																$barisNama=mysql_fetch_assoc($sqlNama);
																$userNama=$barisNama['mhs_nama'];
															}else{
																$barisNama=mysql_fetch_assoc($sqlNama);
																$userNama=$barisNama['dosen_nama'];
															}

															echo "<li class='panel-body' style='padding:0'><div style='border-bottom:1px solid #d3d3d3;padding:2px 0;'><b>".$userNama."</b> ";
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
								?>

							</div>

							<div class="modal fade bs-example-modal-lg" id="tugas_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
						</div>
					</div>
					</div>
				</div>

			<?php
				}else{
					echo "<center><h3>Silakan Pilih Mata Kuliah</h3></center>";
					echo "<br/>";
					echo "<ul>";
					$sql=mysql_query("SELECT m.matkul_kode as matkul_kode, m.matkul_nama as matkul_nama, ma.kelas_kode FROM mhs_kelas ma
					INNER JOIN kelas k
					ON ma.kelas_kode=k.kelas_kode
					INNER JOIN matkul m
					ON m.matkul_kode=k.matkul_kode
					WHERE ma.mhs_nrp='$nrp' AND ma.periode_kode='20132'");
					$countMatkul=mysql_num_rows($sql);
					if($countMatkul>=1){
						while($barisMatkul=mysql_fetch_assoc($sql)){
							$kodeKelas=$barisMatkul['kelas_kode'];
							$kodeMatkul=$barisMatkul['matkul_kode'];
							$namaMatkul=$barisMatkul['matkul_nama'];
							echo "<li><a href='?matkul=$kodeMatkul&kelas=$kodeKelas'>$namaMatkul</a></li>";
						}
					}else{
						echo "Anda Belum terdaftar pada kelas apapun. Untuk lebih jelasnya, silahkan menghubungi pihak Admin";
					}
					echo "</ul>";
				}
				?>
			</div>
		</div>
		<div id='hasil'></div>
	</body>
</html>
