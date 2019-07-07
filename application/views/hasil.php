<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Sedot Real Count KPU</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>

<div class="container-fluid">
	<?php
	$link_wilayah = '';
	if($query == 'all'){
	} elseif($query == 'provinsi'){
		$get_wilayah = $this->provinsi->get($id);
		$link_wilayah .= ($get_wilayah) ? '<li class="breadcrumb-item active">'.$get_wilayah->nama.'</a>' : '<li class="breadcrumb-item active">-</li>';
	} elseif($query == 'kabupaten'){
		$get_wilayah = $this->kabupaten->get($id);
		$get_provinsi = $this->provinsi->get($get_wilayah->id_provinsi);
		$link_wilayah .= ($get_provinsi) ? '<li class="breadcrumb-item"><a href="'.site_url('hasil/index/provinsi/'.$get_wilayah->id_provinsi).'">'.$get_provinsi->nama.'</a></li><li class="breadcrumb-item active">'.$get_wilayah->nama.'</li>' : '<li class="breadcrumb-item active">-</li>';
	} elseif($query == 'kecamatan'){
		$get_wilayah = $this->kecamatan->get($id);
		$get_kabupaten = $this->kabupaten->get($get_wilayah->id_kabupaten);
		$get_provinsi = $this->provinsi->get($get_kabupaten->id_provinsi);
		$link_wilayah .= ($get_provinsi) ? '<li class="breadcrumb-item"><a href="'.site_url('hasil/index/provinsi/'.$get_provinsi->id).'">'.$get_provinsi->nama.'</a></li><li class="breadcrumb-item"><a href="'.site_url('hasil/index/kabupaten/'.$get_kabupaten->id).'">'.$get_kabupaten->nama.'</a><li class="breadcrumb-item active">'.$get_wilayah->nama.'</li>' : '<li class="breadcrumb-item active">-</li>';
	} elseif($query == 'desa'){
		$get_wilayah = $this->desa->get($id);
		$get_kecamatan = $this->kecamatan->get($get_wilayah->id_kecamatan);
		$get_kabupaten = $this->kabupaten->get($get_kecamatan->id_kabupaten);
		$get_provinsi = $this->provinsi->get($get_kabupaten->id_provinsi);
		$link_wilayah .= ($get_provinsi) ? '<li class="breadcrumb-item"><a href="'.site_url('hasil/index/provinsi/'.$get_provinsi->id).'">'.$get_provinsi->nama.'</a></li><li class="breadcrumb-item"><a href="'.site_url('hasil/index/kabupaten/'.$get_kabupaten->id).'">'.$get_kabupaten->nama.'</a></li><li class="breadcrumb-item"><a href="'.site_url('hasil/index/kecamatan/'.$get_kecamatan->id).'">'.$get_kecamatan->nama.'</a></li><li class="breadcrumb-item active">'.$get_wilayah->nama.'</li>' : '<li class="breadcrumb-item active">-</li>';
	}
	$wilayah = '-';
	$this->db->select('sum(a.p1) as jokowi, sum(a.p2) as prabowo, sum(a.sah) as sah, sum(a.t_sah) as t_sah');
	$this->db->from('tps as a');
	$this->db->join('desa as b', 'b.id = a.id_desa');
	if($query == 'provinsi'){
		$this->db->where('b.id_provinsi', $id);
	} elseif($query == 'kabupaten'){
		$this->db->where('b.id_kabupaten', $id);
	} elseif($query == 'kecamatan'){
		$this->db->where('b.id_kecamatan', $id);
	} elseif($query == 'desa'){
		$this->db->where('b.id', $id);
	}
	$query_all = $this->db->get();
	$result_all = $query_all->row();
	$total = $result_all->jokowi + $result_all->prabowo;
	if($query == 'all'){
		$link_lanjut = 'provinsi';
	} elseif($query == 'provinsi'){
		$link_lanjut = 'kabupaten';
	} elseif($query == 'kabupaten'){
		$link_lanjut = 'kecamatan';
	} elseif($query == 'kecamatan'){
		$link_lanjut = 'desa';
	} elseif($query == 'desa'){
		$link_lanjut = 'tps';
	}
	?>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo site_url('hasil'); ?>">HOME</a></li>
			<?php echo $link_wilayah; ?>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<strong>{elapsed_time}</strong>&nbsp;seconds. 
		</ol>
	</nav>
	<div id="body">
		<div class="progress" style="height: 100px; margin-bottom:10px;">
			<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo (persen($result_all->jokowi, $total)) ? persen($result_all->jokowi, $total) : 50;?>%" aria-valuenow="<?php echo (persen($result_all->jokowi, $total)) ? persen($result_all->jokowi, $total) : 50;?>" aria-valuemin="0" aria-valuemax="100">Jokowi-Amin <?php echo persen($result_all->jokowi, $total);?>%</div>
			<div class="progress-bar bg-success" role="progressbar" style="width: <?php echo (persen($result_all->prabowo, $total)) ? persen($result_all->prabowo, $total) : 50;?>%" aria-valuenow="<?php echo (persen($result_all->prabowo, $total)) ? persen($result_all->prabowo, $total) : 50;?>" aria-valuemin="0" aria-valuemax="100">Prabowo-Sandi <?php echo persen($result_all->prabowo, $total);?>%</div>
		</div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="text-center">NO</th>
					<th><?php echo strtoupper($link_lanjut); ?></th>
					<th class="text-center">01</th>
					<th class="text-center">02</th>
					<th class="text-center">SAH</th>
					<th class="text-center">TIDAK SAH</th>
					<th class="text-center">C1</th>
					<th class="text-center">TPS</th>
					<th class="text-center">AKSI</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i=1;
			foreach($all_data as $data){
			?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
			<?php
				if($query == 'desa'){
					$c1 = unserialize($data->c1);
						$total_suara = $data->p1 + $data->p2;
						$class = '';
						if($total_suara != $data->sah){
							$class = ' table-danger';
						}
			?>
					<td><?php echo $data->nama; ?></td>
					<td class="text-center<?php echo $class; ?>"><?php echo rupiah($data->p1); ?></td>
					<td class="text-center<?php echo $class; ?>"><?php echo rupiah($data->p2); ?></td>
					<td class="text-center<?php echo $class; ?>"><?php echo rupiah($data->sah); ?></td>
					<td class="text-center<?php echo $class; ?>"><?php echo rupiah($data->t_sah); ?></td>
					<td class="text-center<?php echo $class; ?>">
					<?php 
					if($c1){
						foreach($c1 as $c){
							echo '<a href="'.base_url().'c1/'.$c.'" target="_blank"><img src="'.base_url().'c1/'.$c.'" alt="c1" width="100"></a>'; 
						}
					}
					?>
					</td>
					<td class="text-center">1</td>
					<td class="text-center">
						<a class="btn btn-sm btn-success" href="<?php echo site_url('hasil/upload/'.$data->id); ?>">upload</a> <a class="btn btn-sm btn-danger" href="<?php echo site_url('hasil/atur_ulang/'.$data->id); ?>">reset</a></td>
			<?php
				} else {
					//test($data);
					//die();
					$total_suara = $data->jokowi + $data->prabowo;
					if($total_suara != $data->sah){
						$class = ' table-danger';
					} else {
						$class = '';
					}
			?>
					<td><?php echo $data->nama; ?></td>
					<td class="text-center"><?php echo rupiah($data->jokowi); ?></td>
					<td class="text-center"><?php echo rupiah($data->prabowo); ?></td>
					<td class="text-center<?php echo $class; ?>"><?php echo rupiah($data->sah); ?></td>
					<td class="text-center<?php echo $class; ?>"><?php echo rupiah($data->t_sah); ?></td>
					<td class="text-center"><?php echo rupiah($data->c1); ?></td>
					<td class="text-center"><?php echo rupiah($data->tps); ?></td>
					<td class="text-center"><a href="<?php echo site_url('hasil/index/'.$link_lanjut.'/'.$data->id); ?>">Detil</a></td>
			<?php } ?>
				</tr>
			<?php
				$i++;
			}
			?>
			</tbody>
		</table>
	</div>
	<p class="footer"><?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
<script>
window.setTimeout(function() { 
	window.location.replace('<?php echo current_url(); ?>');
}, 60000);
</script>
</body>
</html>