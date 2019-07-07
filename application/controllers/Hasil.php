<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil extends CI_Controller {

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
	public function index($query = 'all', $id = NULL){
		$data['query'] = $query;
		$data['id'] = $id;
		if($query == 'all'){
			$this->db->select('a.id, a.nama, sum(c.p1) as jokowi, sum(c.p2) as prabowo, sum(c.sah) as sah, sum(c.t_sah) as t_sah, count(c.id) as tps, count(d.id) as c1');
			$this->db->from('provinsi as a');
			$this->db->join('desa as b', 'b.id_provinsi = a.id', 'left');
			$this->db->join('tps as c', 'b.id = c.id_desa', 'left');
			$this->db->join('pindai_c1 as d', 'd.id_tps = c.id', 'left');
			$this->db->group_by('a.id');
			$this->db->order_by('a.id');
		} elseif($query == 'provinsi'){
			$this->db->select('a.id, a.nama, sum(c.p1) as jokowi, sum(c.p2) as prabowo, sum(c.sah) as sah, sum(c.t_sah) as t_sah, count(c.id) as tps, count(d.id) as c1');
			$this->db->from('kabupaten as a');
			$this->db->join('desa as b', 'b.id_kabupaten = a.id', 'left');
			$this->db->join('tps as c', 'b.id = c.id_desa', 'left');
			$this->db->join('pindai_c1 as d', 'd.id_tps = c.id', 'left');
			$this->db->where('a.id_provinsi', $id);
			$this->db->group_by('a.id');
			$this->db->order_by('a.id');
		} elseif($query == 'kabupaten'){
			$this->db->select('a.id, a.nama, sum(c.p1) as jokowi, sum(c.p2) as prabowo, sum(c.sah) as sah, sum(c.t_sah) as t_sah, count(c.id) as tps, count(d.id) as c1');
			$this->db->from('kecamatan as a');
			$this->db->join('desa as b', 'b.id_kecamatan = a.id', 'left');
			$this->db->join('tps as c', 'b.id = c.id_desa', 'left');
			$this->db->join('pindai_c1 as d', 'd.id_tps = c.id', 'left');
			$this->db->where('a.id_kabupaten', $id);
			$this->db->group_by('a.id');
			$this->db->order_by('a.id');
		} elseif($query == 'kecamatan'){
			$this->db->select('a.id, a.nama, sum(c.p1) as jokowi, sum(c.p2) as prabowo, sum(c.sah) as sah, sum(c.t_sah) as t_sah, count(c.id) as tps, count(d.id) as c1');
			$this->db->from('desa as a');
			$this->db->join('tps as c', 'a.id = c.id_desa', 'left');
			$this->db->join('pindai_c1 as d', 'd.id_tps = c.id', 'left');
			$this->db->where('a.id_kecamatan', $id);
			$this->db->group_by('a.id');
			$this->db->order_by('a.id');
		} elseif($query == 'desa'){
			$this->db->select('*');
			$this->db->from('tps');
			$this->db->where('id_desa', $id);
		}
		$query_all = $this->db->get();
		$data['all_data'] = $query_all->result();
		$this->load->view('hasil', $data);
	}
	public function upload($id){
		$data_tps = $this->tps->get($id);
		if($_POST){
			$files = $_FILES['userfile']['name'];
			$files = array_filter($files);
			if($files){
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
			} else {
				$update_data = array(
					'p1'	=> $this->input->post('p1'),
					'p2'	=> $this->input->post('p2'),
					't_sah'	=> $this->input->post('t_sah'),
					'sah'	=> $this->input->post('sah'),
				);
				$this->tps->update($id, $update_data);
			}
			redirect(site_url('hasil/index/desa/'.$data_tps->id_desa));
		} else {
			$data['data'] = $data_tps;
			$this->load->view('upload', $data);
		}
	}
	public function atur_ulang($id){
		$this->db->select('a.id as id_tps, b.id as id_desa, b.id_kecamatan, b.id_kabupaten, b.id_provinsi');
		$this->db->from('tps as a');
		$this->db->join('desa as b', 'a.id_desa=b.id');
		$this->db->where('a.id', $id);
		$query = $this->db->get();
		$data_tps = $query->row();
		//$data_tps = $this->tps->get($id);
		$url_tps = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$data_tps->id_provinsi.'/'.$data_tps->id_kabupaten.'/'.$data_tps->id_kecamatan.'/'.$data_tps->id_desa.'/'.$id.'.json';
		$json_data_tps = getSSLPage($url_tps);
		$data_tps_kpu = json_decode($json_data_tps);
		$folder_1 = substr($id, 0, 3);
		$folder_2 = substr($id, 3, 3);
		$folder_3 = substr($id, 6, 3);
		$image_insert = array();
		$suara_tidak_sah = 0;
		$suara_sah = 0;
		if(isset($data_tps_kpu->images)){
			foreach($data_tps_kpu->images as $img){
				$file = $folder_1.'/'.$folder_2.'/'.$id.'/'.$img;
				$url_file = 'https://pemilu2019.kpu.go.id/img/c/'.$file;
				$find_pindai_c1 = $this->pindai_c1->find("url_file = '$url_file' AND id_tps = $id");
				if($find_pindai_c1){
					$update_pindai_c1 = array(
						'url_file'	=> $url_file,
						'id_tps'	=> $id,
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
			$suara_tidak_sah = $data_tps_kpu->suara_tidak_sah;
			$suara_sah = $data_tps_kpu->suara_sah;
		}
		$data_update = array(
			'p1'			=> $data_tps_kpu->chart->{21},
			'p2'			=> $data_tps_kpu->chart->{22},
			't_sah'			=> $suara_tidak_sah,
			'sah'			=> $suara_sah,
		);
		$this->tps->update($id, $data_update);
		redirect(site_url('hasil/index/desa/'.$data_tps->id_desa));
	}
	private function upload_files($path, $title, $files){
        $config = array(
            'upload_path'   => $path,
            'allowed_types' => 'gif|jpg|png|jpeg',
            'overwrite'     => 1,                       
        );

        $this->load->library('upload', $config);

        $images = array();

        foreach ($files['name'] as $key => $image) {
            $_FILES['images[]']['name']= $files['name'][$key];
            $_FILES['images[]']['type']= $files['type'][$key];
            $_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['images[]']['error']= $files['error'][$key];
            $_FILES['images[]']['size']= $files['size'][$key];

            $fileName = $title .'_'. $image;

            $images[] = $fileName;

            $config['file_name'] = $fileName;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('images[]')) {
                $this->upload->data();
            } else {
                return false;
            }
        }

        return $images;
    }
}
