<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Upload C1</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<?php
$desa = $this->desa->get($data->id_desa);
?>
<div class="container" id="container">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo site_url('hasil'); ?>">HOME</a></li>
			<li class="breadcrumb-item"><a href="<?php echo site_url('hasil/index/provinsi/'.$desa->id_provinsi); ?>">PROV. <?php echo provinsi($desa->id_provinsi); ?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo site_url('hasil/index/kabupaten/'.$desa->id_kabupaten); ?>">KAB(KOTA) <?php echo kabupaten($desa->id_kabupaten); ?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo site_url('hasil/index/kecamatan/'.$desa->id_kecamatan); ?>">KEC. <?php echo kecamatan($desa->id_kecamatan); ?></a></li>
			<li class="breadcrumb-item"><a href="<?php echo site_url('hasil/index/desa/'.$desa->id); ?>"><?php echo $desa->nama; ?></a></li>
			<li class="breadcrumb-item active"><?php echo $data->nama; ?></li>
		</ol>
	</nav>
	<div id="body">
		<?php //test($data); ?>
		<form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
			<div class="form-group row">
			<label class="col-sm-3 col-form-label">Jumlah Suara 01</label>
			<div class="col-sm-9">
			<input type="text" name="p1" id="p1" class="form-control" value="<?php echo $data->p1; ?>" onKeyUp="myFunction()" autocomplete="off">
			</div>
			</div>
			<div class="form-group row">
			<label class="col-sm-3 col-form-label">Jumlah Suara 02</label>
			<div class="col-sm-9">
			<input type="text" name="p2" id="p2" class="form-control" value="<?php echo $data->p2; ?>" onKeyUp="myFunction()" autocomplete="off">
			</div>
			</div>
			<div class="form-group row">			
			<label class="col-sm-3 col-form-label">Jumlah Suara Tidak Sah</label>
			<div class="col-sm-9">
			<input type="text" name="t_sah" class="form-control" value="<?php echo $data->t_sah; ?>" autocomplete="off">
			</div>
			</div>
			<div class="form-group row">
			<label class="col-sm-3 col-form-label">Jumlah Suara Sah</label>
			<div class="col-sm-9">
			<input type="text" name="sah" class="form-control" id="sah" value="<?php echo $data->sah; ?>" autocomplete="off">
			</div>
			</div>
			<div class="form-group row">
			<label class="col-sm-3 col-form-label">C1 Hal. 1</label>
			<div class="col-sm-9">
			<div class="custom-file">
			<input class="custom-file-input" type="file" name="userfile[]" id="customFile" />
			<label class="custom-file-label" for="customFile">Choose file</label>
			</div>
			</div>
			</div>
			<div class="form-group row">
			<label class="col-sm-3 col-form-label">C1 Hal. 2</label>
			<div class="col-sm-9">
			<div class="custom-file">
			<input class="custom-file-input" type="file" name="userfile[]" id="customFile" />
			<label class="custom-file-label" for="customFile">Choose file</label>
			</div>
			</div>
			</div>
			<div class="form-group">
			<input class="btn btn-primary" type="submit" value="Simpan">
			</div>
		</form>
		<?php
		$pindai_c1 = $this->pindai_c1->find_all_by_id_tps($data->id);
		if($pindai_c1){
			foreach($pindai_c1 as $c1){
				echo '<a href="'.$c1->url_file.'" target="_blank"><img src="'.$c1->url_file.'" alt="c1" width="400"></a>';
			}
		} 
		?>
	</div>
	<script>
		function myFunction() {
			var jokowi_amin = document.getElementById("p1").value;
			var prabowo_sandi = document.getElementById("p2").value;
			var jumlah = parseInt(jokowi_amin) + parseInt(prabowo_sandi);
			document.getElementById("sah").value = jumlah; 
		}
	</script>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>