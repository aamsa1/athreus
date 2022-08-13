<?php
	if (empty($_SESSION['usernm'])){
	  echo "<link href='style.css' rel='stylesheet' type='text/css'>
	 <center>Untuk mengakses modul, Anda harus login <br>";
	  echo "<a href=index.php><b>LOGIN</b></a></center>";
	}
	else{
		$aksi = "modul/qrcode/act_qrcode.php";
		$act = @$_GET['act'];

		switch ($act) {
			case 'print' :
				echo"<a href='cetak_qrcode.php' target='_blank'>Cetak QR Code</a>";

				echo"<form action='cetak_pdf.php' method='GET'>
					<table width='100%'>
						<tbody>
						<tr>
							<td width='220px'><label for=\"code\" class=\"control-label\">Code</label></td>
							<td>
								<div class=\"col-sm-4\">
									<select name='id' class='form-control input-sm' required>
										<option value=''>- Silahkan Pilih -</option>";

										$rsqlcode = mysql_query("SELECT * FROM qrcode ORDER BY id_qrcode DESC") or die(mysql_error());
										while ($q = mysql_fetch_assoc($rsqlcode)) {
											echo"<option value='".$q['id_qrcode']."'>".$q['code']." - ".$q['nama']."</option>";
										}
									echo"</select>


								</div>
								<input type='submit' value='Cetak' class='btn btn-default btn-sm'>
							</td>

						</tr>
						</tbody>
					</table>
				</form>";

				break;
			case 'form':
				if(!empty($_GET['id']))
				{
					$aks = "$aksi?mod=qrcode&act=edit";
					$query = mysql_query("SELECT * FROM qrcode WHERE id_qrcode = '$_GET[id]'");
					$temukan = mysql_num_rows($query);
					if($temukan > 0)
					{
						$c = mysql_fetch_assoc($query);
					}
					else
					{
						header("location:med.php?mod=qrcode");
					}

				}
				else
				{
					$aks = "$aksi?mod=qrcode&act=simpan";
				}

				echo"<h3>Form QR Code</h3><hr style=\"margin-bottom: 10px;\">";

				echo"<form action='$aks' method='post'>
					<input type=\"hidden\" name=\"id\" value='"?><?php echo isset($c['id_qrcode']) ? $c['id_qrcode'] : '';?><?php echo"' "?><?php echo isset($c['id_qrcode']) ? 'readonly' : '';?><?php echo" required>

					<table class='table table-bordered table-striped'>
						<tbody>
						<tr>
							<td width='220px'><label for=\"code\" class=\"control-label\">Code</label></td>
							<td>
								<div class=\"col-sm-4\">
									<input type=\"text\" class=\"form-control input-sm\" id=\"code\" name=\"code\" placeholder=\"Ketik code...\" value='"?><?php echo isset($c['code']) ? $c['code'] : '';?><?php echo"' "?><?php echo" required>
								</div>
							</td>
						</tr>
						<tr>
							<td><label for=\"nama\" class=\"control-label\">Nama</label></td>
							<td>
								<div class=\"col-sm-8\">
									<input type=\"text\" class=\"form-control input-sm\" id=\"nama\" name=\"nama\" placeholder=\"Ketik nama...\"  value='"?><?php echo isset($c['nama']) ? $c['nama'] : '';?><?php echo"'  required>
								</div>
							</td>
						</tr>
						<tr>
							<td><label for=\"keterangan\" class=\"control-label\">Keterangan</label></td>
							<td>
								<div class=\"col-sm-8\">
									<textarea class=\"form-control input-sm\" id=\"keterangan\" name=\"keterangan\" placeholder=\"ketik keterangan...\">"?><?php echo isset($c['keterangan']) ? $c['keterangan'] : '';?><?php echo"</textarea>
								</div>
							</td>
						</tr>
						<tr>
							<td><label for=\"status\" class=\"control-label\">Status</label></td>
							<td>
								<div class=\"col-sm-12\">
									<label class=\"radio-inline\">
										<input type='radio' name='status' value='N' ";?><?php echo (isset($c['status']) && $c['status'] == 'N') ? 'checked' : ''; ?><?php echo" > Tidak Dipakai
									</label>

									<label class=\"radio-inline\">
										<input type='radio' name='status' value='Y' ";?><?php echo (isset($c['status']) && $c['status'] == 'Y') ? 'checked' : ''; ?><?php echo" > Dipakai
									</label>
									
								</div>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<div class=\"col-sm-12\">
									<button class='btn btn-primary btn-sm'>Simpan</button>
									<a href=\"javascript:void(0);\" onclick=\"window.history.back();\" class='btn btn-warning btn-sm'>Kembali</a>
								</div>
							</td>
						</tr>
						</tbody>
					</table>

				</form>";

				break;
			
			default:
				echo"<h3>Data QR Code</h3><hr style=\"margin-bottom: 10px;\">

				<div class=\"row\">
					<div class=\"col-md-12\">
						<a href=\"med.php?mod=qrcode&act=form\" class=\"btn btn-primary btn-sm\">Tambah QR Code</a>

					</div>

				</div><hr style=\"margin-top: 10px;\">


				<table class=\"table table-light-dark table-bordered table-striped table-hover table_id\">
					<thead>
					<tr>
						<td>No</td>
						<td>Code</td>
						<td>Nama</td>
						<td>Keterangan</td>
						<td>Status</td>
						<td>Aksi</td>
					</tr>
					</thead>
					<tbody>";
					
						$no = 1;
						$rsql = mysql_query("SELECT * FROM qrcode ORDER BY id_qrcode ASC") or die(mysql_error());
						while ($a = mysql_fetch_assoc($rsql)) {
							echo"<tr>
								<td>".$no."</td>
								<td>".$a['code']."</td>
								<td>".$a['nama']."</td>
								<td>".$a['keterangan']."</td>
								<td align='center'>".$a['status']."</td>
								<td align='center'><a href='med.php?mod=qrcode&act=form&id=".$a['id_qrcode']."' class='btn btn-info btn-sm'>Edit</a> 
									<a href='$aksi?mod=qrcode&act=hapus&id=".$a['id_qrcode']."' onclick=\"return confirm('Yakin ingin menghapus data?');\" class='btn btn-danger btn-sm'>Hapus</a></td>
							</tr>";

							$no++;
						}
					echo"</tbody>
				</table>";

				break;
		}
	}
?>

				