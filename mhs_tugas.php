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
				$('#view_pdf').appendTo("body");
			});
		</script>


	<div style='height:600px; overflow: auto;padding:5px 10px 0; '>
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	  <?php
	include("connect.php");
	$matkulKode=$_GET['matkul'];
	$tugasKode=$_GET['kode'];

	$sqlDaftar=mysql_query("SELECT a.mhs_nrp,b.mhs_nama FROM tugas_detail a,mhs b where a.tugas_kode='$tugasKode' AND a.mhs_nrp=b.mhs_nrp");
	$countDaftar=mysql_num_rows($sqlDaftar);
	if($countDaftar>=1){

			$sqlTugas=mysql_query("select * from tugas where tugas_kode='$tugasKode'");
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
				<div class='col-sm-12'>
					<table class='table'>
						<thead>
							<tr>
                <th>Nama</th>
								<th>NRP</th>
								<th>Opsi</th>
							</tr>
						</thead>
						<tbody>";
						$tempNRP="";
						$countNRP=1;
						while ($row=mysql_fetch_assoc($sqlDaftar)){

							$nrp=$row['mhs_nrp'];
							if($nrp==$tempNRP){
								$countNRP++;
							}
							$tempNRP=$nrp;
							echo "<tr><td>".$row['mhs_nama']."</td><td>".$row['mhs_nrp']."</td>
								<td><a href='./Tugas/$matkulKode/Pertemuan_$pertemuan/".$nrp."_".$countNRP.".pdf' target='_blank'>Download</a> &nbsp;&nbsp;&nbsp;
								<a class='view_pdf_btn' data-toggle='modal' data-target='#view_pdf'  style='cursor:pointer;' href='view_pdf.php?address=./Tugas/$matkulKode/Pertemuan_$pertemuan/'>Lihat Tugas</a></td></tr>";
						}
				echo "</tbody>
					</table>
				</div>
			</div><div style='clear:both;'></div>";
	}else{
		echo "Belum ada mahasiswa yang upload tugas";
	}
  ?>
</div>
<div class="modal fade bs-example-modal-lg" id="view_pdf" tabindex="-1">
	 <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Detail Tugas</h4>
		  </div>
		  <div class="modal-body">
			...
			kdjasl
			kdas;ld
			kdasdka
			kdpasdpa
			kdasdkapos...
			kdjasl
			kdas;ld
			kdasdka
			kdpasdpa
			kdasdkapos...
			kdjasl
			kdas;ld
			kdasdka
			kdpasdpa
			kdasdkapos...
			kdjasl
			kdas;ld
			kdasdka
			kdpasdpa
			kdasdkapos
		  </div>
		  <div class="modal-footer">

		  </div>
		</div>
	  </div>
</div>
