<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('file', 'custom', 'url', 'html'));
		$this->load->library(array('curl'));
		$this->load->database();
		$dbs = array('provinsi', 'kabupaten', 'kecamatan', 'desa', 'tps');
		foreach($dbs as $db){
			if (!$this->db->table_exists($db)){
				redirect('migrasi');
			}
		}
		//source : https://github.com/nahid/jsonq
		$this->load->model(array(
			'provinsi_model'	=> 'provinsi',
			'kabupaten_model'	=> 'kabupaten',
			'kecamatan_model'	=> 'kecamatan',
			'desa_model'		=> 'desa',
			'tps_model'			=> 'tps',
			)
		);
	}
	public function index($wilayah = 0){
		$nasional_timestamp_start = time();
		$url_nasional = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$wilayah.'.json';
		$json_data_nasional = getSSLPage($url_nasional);
		$data_nasional = json_decode($json_data_nasional);
		if($data_nasional){
			foreach($data_nasional as $key_provinsi => $value_provinsi){
				$provinsi_timestamp_start = time();
				$find_provinsi = $this->provinsi->get($key_provinsi);
				if($find_provinsi){
					file_put_contents('wilayah_log.txt', 'provinsi '.$value_provinsi->nama.' sudah ada'."\n", FILE_APPEND | LOCK_EX);
				} else {
					$insert_provinsi = array(
						'id'	=> $key_provinsi,
						'nama'	=> $value_provinsi->nama,
					);
					if($this->provinsi->insert($insert_provinsi)){
						file_put_contents('wilayah_log.txt', 'provinsi '.$value_provinsi->nama.' berhasil di entry'."\n", FILE_APPEND | LOCK_EX);
					} else {
						file_put_contents('wilayah_log.txt', 'provinsi '.$value_provinsi->nama.' gagal di entry'."\n", FILE_APPEND | LOCK_EX);
					}
				}
				$url_provinsi = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_provinsi.'.json';
				$json_data_provinsi = getSSLPage($url_provinsi);
				$data_provinsi = json_decode($json_data_provinsi);
				if($data_provinsi){
					foreach($data_provinsi as $key_kabupaten => $value_kabupaten){
						$kabupaten_timestamp_start = time();
						$find_kabupaten = $this->kabupaten->get($key_kabupaten);
						if($find_kabupaten){
							file_put_contents('wilayah_log.txt', 'kabupaten '.$value_kabupaten->nama.' sudah ada'."\n", FILE_APPEND | LOCK_EX);
						} else {
							$insert_kabupaten = array(
								'id'			=> $key_kabupaten,
								'id_provinsi'	=> $key_provinsi,
								'nama'			=> $value_kabupaten->nama,
							);
							if($this->kabupaten->insert($insert_kabupaten)){
								file_put_contents('wilayah_log.txt', 'kabupaten '.$value_kabupaten->nama.' berhasil di entry'."\n", FILE_APPEND | LOCK_EX);
							} else {
								file_put_contents('wilayah_log.txt', 'kabupaten '.$value_kabupaten->nama.' gagal di entry'."\n", FILE_APPEND | LOCK_EX);
							}
						}
						$url_kabupaten = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_provinsi.'/'.$key_kabupaten.'.json';
						$json_data_kabupaten = getSSLPage($url_kabupaten);
						$data_kabupaten = json_decode($json_data_kabupaten);
						if($data_kabupaten){
							foreach($data_kabupaten as $key_kecamatan => $value_kecamatan){
								$kecamatan_timestamp_start = time();
								$find_kecamatan = $this->kecamatan->get($key_kecamatan);
								if($find_kecamatan){
									file_put_contents('wilayah_log.txt', 'kecamatan '.$value_kecamatan->nama.' sudah ada'."\n", FILE_APPEND | LOCK_EX);
								} else {
									$insert_kecamatan = array(
										'id'			=> $key_kecamatan,
										'id_provinsi'	=> $key_provinsi,
										'id_kabupaten'	=> $key_kabupaten,
										'nama'			=> $value_kecamatan->nama,
									);
									if($this->kecamatan->insert($insert_kecamatan)){
										file_put_contents('wilayah_log.txt', 'kecamatan '.$value_kecamatan->nama.' berhasil di entry'."\n", FILE_APPEND | LOCK_EX);
									} else {
										file_put_contents('wilayah_log.txt', 'kecamatan '.$value_kecamatan->nama.' gagal di entry'."\n", FILE_APPEND | LOCK_EX);
									}
								}
								$url_kecamatan = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_provinsi.'/'.$key_kabupaten.'/'.$key_kecamatan.'.json';
								$json_data_kecamatan = getSSLPage($url_kecamatan);
								$data_kecamatan = json_decode($json_data_kecamatan);
								if($data_kecamatan){
									foreach($data_kecamatan as $key_desa => $value_desa){
										$desa_timestamp_start = time();
										$find_desa = $this->desa->get($key_desa);
										if($find_desa){
											file_put_contents('wilayah_log.txt', 'desa '.$value_desa->nama.' sudah ada'."\n", FILE_APPEND | LOCK_EX);
										} else {
											$insert_desa = array(
												'id'			=> $key_desa,
												'id_provinsi'	=> $key_provinsi,
												'id_kabupaten'	=> $key_kabupaten,
												'id_kecamatan'	=> $key_kecamatan,
												'nama'			=> $value_desa->nama,
											);
											if($this->desa->insert($insert_desa)){
												file_put_contents('wilayah_log.txt', 'desa '.$value_desa->nama.' berhasil di entry'."\n", FILE_APPEND | LOCK_EX);
											} else {
												file_put_contents('wilayah_log.txt', 'desa '.$value_desa->nama.' gagal di entry'."\n", FILE_APPEND | LOCK_EX);
											}
										}
										/*$url_desa = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_provinsi.'/'.$key_kabupaten.'/'.$key_kecamatan.'/'.$key_desa.'.json';
										$json_data_desa = getSSLPage($url_desa);
										$data_desa = json_decode($json_data_desa);
										if($data_desa){
											foreach($data_desa as $key_tps => $value_tps){
												$tps_timestamp_start = time();
												$find_tps = $this->tps->get($key_tps);
												if($find_tps){
													file_put_contents('wilayah_log.txt', 'tps '.$value_tps->nama.' sudah ada'."\n", FILE_APPEND | LOCK_EX);
												} else {
													$insert_tps = array(
														'id'		=> $key_tps,
														'id_desa'	=> $key_desa,
														'nama'		=> $value_tps->nama,
													);
													if($this->tps->insert($insert_tps)){
														file_put_contents('wilayah_log.txt', 'tps '.$value_tps->nama.' berhasil di entry'."\n", FILE_APPEND | LOCK_EX);
													} else {
														file_put_contents('wilayah_log.txt', 'tps '.$value_tps->nama.' gagal di entry'."\n", FILE_APPEND | LOCK_EX);
													}
												}
												$url_tps = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$key_provinsi.'/'.$key_kabupaten.'/'.$key_kecamatan.'/'.$key_desa.'/'.$key_tps.'.json';
												$json_data_tps = getSSLPage($url_tps);
												$data_tps = json_decode($json_data_tps);
												if($data_tps){
													$folder_1 = substr($key_tps, 0, 3);
													$folder_2 = substr($key_tps, 3, 3);
													$folder_3 = substr($key_tps, 6, 3);
													$image_insert = array();
													if(isset($data_tps->images)){
														foreach($data_tps->images as $img){
															$file = $folder_1.'/'.$folder_2.'/'.$key_tps.'/'.$img;
															$folder_tujuan = 'kpu/'.$folder_1.'/'.$folder_2.'/'.$key_tps;
															save_image('https://pemilu2019.kpu.go.id/img/c/'.$file, $img, $folder_tujuan);
															$image_insert[] = $file;
														}
													}
													$p1 = (isset($data_tps->chart)) ? $data_tps->chart->{21} : 0;
													$p2 = (isset($data_tps->chart)) ? $data_tps->chart->{22} : 0;
													$suara_sah = $p1 + $p2;
													$image_insert = array();
													$data_update = array(
														'p1'			=> (isset($data_tps->chart)) ? $data_tps->chart->{21} : 0,
														'p2'			=> (isset($data_tps->chart)) ? $data_tps->chart->{22} : 0,
														't_sah'			=> (isset($data_tps->suara_tidak_sah)) ? $data_tps->suara_tidak_sah : 0,
														'sah'			=> $suara_sah,
														'sah_asli'		=> (isset($data_tps->suara_sah)) ? $data_tps->suara_sah : 0,
														'pemilih' 		=> (isset($data_tps->pemilih_j)) ? $data_tps->pemilih_j : 0,
														'pengguna' 		=> (isset($data_tps->pengguna_j)) ? $data_tps->pengguna_j : 0,
														'suara_total' 	=> (isset($data_tps->suara_total)) ? $data_tps->suara_total : 0,
														//'c1'			=> ($image_insert) ? serialize($image_insert) : '',
													);
													if($this->tps->update($key_tps, $data_update)){
														file_put_contents('wilayah_log.txt', 'tps '.$value_tps->nama.' berhasil di perbaharui'."\n", FILE_APPEND | LOCK_EX);
													} else {
														file_put_contents('wilayah_log.txt', 'tps '.$value_tps->nama.' gagal di perbaharui'."\n", FILE_APPEND | LOCK_EX);
													}
												}
												$tps_timestamp_end = time();
												$tps_inputSeconds = ($tps_timestamp_end - $tps_timestamp_start);
												file_put_contents('wilayah_log.txt', 'Proses entry data tps selesai. Waktu proses entry data '. secondsToTime($tps_inputSeconds)."\n", FILE_APPEND | LOCK_EX);
											}//end tps
										}*/
										$desa_timestamp_end = time();
										$desa_inputSeconds = ($desa_timestamp_end - $desa_timestamp_start);
										file_put_contents('wilayah_log.txt', 'Proses entry data desa selesai. Waktu proses entry data '. secondsToTime($desa_inputSeconds)."\n", FILE_APPEND | LOCK_EX);
									}//end desa
								}
								$kecamatan_timestamp_end = time();
								$kecamatan_inputSeconds = ($kecamatan_timestamp_end - $kecamatan_timestamp_start);
								file_put_contents('wilayah_log.txt', 'Proses entry data kecamatan selesai. Waktu proses entry data '. secondsToTime($kecamatan_inputSeconds)."\n", FILE_APPEND | LOCK_EX);
							}//end kecamatan
						}
						$kabupaten_timestamp_end = time();
						$kabupaten_inputSeconds = ($kabupaten_timestamp_end - $kabupaten_timestamp_start);
						file_put_contents('wilayah_log.txt', 'Proses entry data kabupaten selesai. Waktu proses entry data '. secondsToTime($kabupaten_inputSeconds)."\n", FILE_APPEND | LOCK_EX);
					}//end kabupaten
				}
				$provinsi_timestamp_end = time();
				$provinsi_inputSeconds = ($provinsi_timestamp_end - $provinsi_timestamp_start);
				file_put_contents('wilayah_log.txt', 'Proses entry data provinsi selesai. Waktu proses entry data '. secondsToTime($provinsi_inputSeconds)."\n", FILE_APPEND | LOCK_EX);
			}//end provinsi
		}
		$nasional_timestamp_end = time();
		$nasional_inputSeconds = ($nasional_timestamp_end - $nasional_timestamp_start);
		file_put_contents('wilayah_log.txt', 'Proses entry data nasional selesai. Waktu proses entry data '. secondsToTime($nasional_inputSeconds)."\n", FILE_APPEND | LOCK_EX);
	}
	public function lanjut($json){
		$url = 'https://kawal-c1.appspot.com/api/c/'.$json;
		$json_data = file_get_contents($url);
		$data['response'] = json_decode($json_data);
		$data['json'] = $json;
		//$this->load->view('header');
		$this->load->view('new', $data);
	}
	public function upload($id){
		$data_tps = $this->tps->get($id);
		if($_POST){
			$config['upload_path']          = './uploads/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['file_name']        	= $id;
			$config['overwrite']        	= TRUE;
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('userfile')){
				$response = array('error' => $this->upload->display_errors());
				//$this->load->view('upload_form', $error);
			} else {
				$response = array('upload_data' => $this->upload->data());
				//$this->load->view('upload_success', $data);
				$update_data = array(
					'p1'	=> $this->input->post('p1'),
					'p2'	=> $this->input->post('p2'),
					't_sah'	=> $this->input->post('t_sah'),
					'sah'	=> $this->input->post('sah'),
					'c1'	=> $response['upload_data']['file_name'],
				);
				$this->tps->update($id, $update_data);
			}
			redirect(site_url('welcome/lanjut/'.$data_tps->id_desa));
		} else {
			$data['data'] = $data_tps;
			$this->load->view('tambah_new', $data);
		}
	}
}
