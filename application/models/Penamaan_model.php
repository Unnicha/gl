<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Penamaan_model extends CI_Model
	{
		protected $db;
		
		public function __construct() 
		{
			$db_name	= $this->session->userdata('db_perusahaan');
			if($db_name) {
				$db_config	= Globals::perusahaan($db_name);
				$this->db	= $this->load->database($db_config, true);
			}
		}
		
		public function getAllPenamaan()
		{
			return  $this->db->get('penamaan')->result_array();
		}
		
		public function tambahPenamaan($data, $tabel)
		{
			$this->db->insert($data, $tabel);
		}
	}