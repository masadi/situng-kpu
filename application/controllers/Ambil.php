<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ambil extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('file', 'custom', 'url', 'html'));
		$this->load->library(array('curl'));
		$this->load->database();
		$dbs = array('provinsi', 'kabupaten', 'kecamatan', 'desa', 'tps', 'pindai_c1');
		foreach($dbs as $db){
			if (!$this->db->table_exists($db)){
				redirect('migrasi');
			}
		}
		$this->load->model(array(
			'provinsi_model'	=> 'provinsi',
			'kabupaten_model'	=> 'kabupaten',
			'kecamatan_model'	=> 'kecamatan',
			'desa_model'		=> 'desa',
			'tps_model'			=> 'tps',
			'pindai_c1_model'	=> 'pindai_c1',
			)
		);
	}
	public function index(){
		$provinsi = $this->provinsi->find_all("id IS NOT NULL", '*', 'id asc');
		foreach($provinsi as $prov){
			$kabupaten = $this->kabupaten->find_all_by_id_provinsi($prov->id);
			foreach($kabupaten as $kab){
				$kecamatan = $this->kecamatan->find_all_by_id_kabupaten($kab->id);
				foreach($kecamatan as $kec){
					$desa = $this->desa->find_all_by_id_kecamatan($kec->id);
					foreach($desa as $des){
						$url_tps = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$des->id_provinsi.'/'.$des->id_kabupaten.'/'.$des->id_kecamatan.'/'.$des->id.'.json';
						$json_data_tps = getSSLPage($url_tps);
						$response_tps = json_decode($json_data_tps);
						$i=1;
						foreach($response_tps->table as $id_tps => $data_tps){
							$find_tps = $this->tps->get($id_tps);
							$url = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$des->id_provinsi.'/'.$des->id_kabupaten.'/'.$des->id_kecamatan.'/'.$des->id.'/'.$id_tps.'.json';
							$json_data = getSSLPage($url);
							$response = json_decode($json_data);
							$suara_tidak_sah = 0;
							$suara_sah = 0;
							if(isset($response->suara_tidak_sah)){
								$suara_tidak_sah = $response->suara_tidak_sah;
								$suara_sah = $response->suara_sah;
							}
							if($find_tps){
								$update_tps = array(
									'id_desa'	=> $des->id,
									'nama'		=> 'TPS '.$i,
									'p1'		=> $data_tps->{21},
									'p2'		=> $data_tps->{22},
									't_sah'		=> $suara_tidak_sah,
									'sah'		=> $suara_sah,
								);
								$this->tps->update($id_tps, $update_tps);
								if($response){
									$folder_1 = substr($id_tps, 0, 3);
									$folder_2 = substr($id_tps, 3, 3);
									$folder_3 = substr($id_tps, 6, 3);
									$image_insert = array();
									if(isset($response->images)){
										foreach($response->images as $img){
											$file = $folder_1.'/'.$folder_2.'/'.$id_tps.'/'.$img;
											$url_file = 'https://pemilu2019.kpu.go.id/img/c/'.$file;
											$find_pindai_c1 = $this->pindai_c1->find("url_file = '$url_file' AND id_tps = $id_tps");
											if($find_pindai_c1){
												$update_pindai_c1 = array(
													'url_file'	=> $url_file,
													'id_tps'	=> $id_tps,
												);
												$this->pindai_c1->update($find_pindai_c1->id, $update_pindai_c1);
											} else {
												$insert_pindai_c1 = array(
													'url_file'	=> $url_file,
													'id_tps'	=> $id_tps,
												);
												$this->pindai_c1->insert($insert_pindai_c1);
											}
										}
									}
								}
							} else {
								$insert_tps = array(
									'id'		=> $id_tps,
									'id_desa'	=> $des->id,
									'nama'		=> 'TPS '.$i,
									'p1'		=> $data_tps->{21},
									'p2'		=> $data_tps->{22},
									't_sah'		=> $suara_tidak_sah,
									'sah'		=> $suara_sah,
								);
								$this->tps->insert($insert_tps);
								if($response){
									$folder_1 = substr($id_tps, 0, 3);
									$folder_2 = substr($id_tps, 3, 3);
									$folder_3 = substr($id_tps, 6, 3);
									$image_insert = array();
									if(isset($response->images)){
										foreach($response->images as $img){
											$file = $folder_1.'/'.$folder_2.'/'.$id_tps.'/'.$img;
											$url_file = 'https://pemilu2019.kpu.go.id/img/c/'.$file;
											$find_pindai_c1 = $this->pindai_c1->find("url_file = '$url_file' AND id_tps = $id_tps");
											if(!$find_pindai_c1){
												$insert_pindai_c1 = array(
													'url_file'	=> $url_file,
													'id_tps'	=> $id_tps,
												);
												$this->pindai_c1->insert($insert_pindai_c1);
											}
										}
									}
								}
							}
							
							$i++;
						}
					}
				}
			}
		}
	}
}
