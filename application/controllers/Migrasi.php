<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrasi extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->dbforge();
	}
	public function index(){
		echo 'proses migrasi<br />';
		if (!$this->db->table_exists('provinsi')){
			$this->load->dbforge();
			$fields = array(
				'id' => array(
					'type' => 'INT',
				),
				'nama'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'created_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'updated_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'deleted_at' => array(
					'type' 		=> 'timestamp(0) without time zone',
					'null'	=> true
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('provinsi',TRUE); 
		}
		if (!$this->db->table_exists('kabupaten')){
			$this->load->dbforge();
			$fields = array(
				'id' => array(
					'type' => 'INT',
				),
				'id_provinsi' => array(
					'type' => 'INT',
				),
				'nama'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'created_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'updated_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'deleted_at' => array(
					'type' 		=> 'timestamp(0) without time zone',
					'null'	=> true
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('kabupaten',TRUE); 
		}
		if (!$this->db->table_exists('kecamatan')){
			$this->load->dbforge();
			$fields = array(
				'id' => array(
					'type' => 'INT',
				),
				'id_kabupaten' => array(
					'type' => 'INT',
				),
				'id_provinsi' => array(
					'type' => 'INT',
				),
				'nama'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'created_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'updated_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'deleted_at' => array(
					'type' 		=> 'timestamp(0) without time zone',
					'null'	=> true
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('kecamatan',TRUE); 
		}
		if (!$this->db->table_exists('desa')){
			$this->load->dbforge();
			$fields = array(
				'id' => array(
					'type' => 'INT',
				),
				'id_kecamatan' => array(
					'type' => 'INT',
				),
				'id_kabupaten' => array(
					'type' => 'INT',
				),
				'id_provinsi' => array(
					'type' => 'INT',
				),
				'nama'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'created_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'updated_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'deleted_at' => array(
					'type' 		=> 'timestamp(0) without time zone',
					'null'	=> true
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('desa',TRUE); 
		}
		if (!$this->db->table_exists('tps')){
			$this->load->dbforge();
			$fields = array(
				'id' => array(
					'type' => 'BIGINT',
				),
				'nama'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'id_desa' => array(
					'type' => 'INT',
				),
				'p1' => array(
					'type' => 'INT',
					'null'	=> true,
				),
				'p2' => array(
					'type' => 'INT',
					'null'	=> true,
				),
				't_sah' => array(
					'type' => 'INT',
					'null'	=> true,
				),
				'sah' => array(
					'type' => 'INT',
					'null'	=> true,
				),
				'c1'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null'	=> true,
				),
				'created_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'updated_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'deleted_at' => array(
					'type' 		=> 'timestamp(0) without time zone',
					'null'	=> true
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('tps',TRUE); 
		}
		if (!$this->db->table_exists('pindai_c1')){
			$this->load->dbforge();
			$fields = array(
				'id' => array(
					'type' => 'BIGINT',
					'unsigned' => TRUE,
                	'auto_increment' => TRUE
				),
				'url_file'	=> array(
					'type' => 'VARCHAR',
					'constraint' => 255
				),
				'id_tps' => array(
					'type' => 'BIGINT',
				),
				'created_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'updated_at' => array(
					'type' => 'timestamp(0) without time zone NOT NULL'
				),
				'deleted_at' => array(
					'type' 		=> 'timestamp(0) without time zone',
					'null'	=> true
				)
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('pindai_c1',TRUE); 
		}
		echo 'selesai';
	}
}
