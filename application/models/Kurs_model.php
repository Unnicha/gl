<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Kurs_model extends CI_Model 
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
		
		public function getAll($cari, $offset, $limit, $order)
		{
			if($cari) {
				$this->db->like('kode_mu', $cari)->or_like('nama_mu', $cari)
						->or_like('tanggal', $cari)->or_like('nilai_kurs', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->join('mata_uang', 'mata_uang.kode_mu = kurs.mata_uang')
							->get('kurs')->result_array();
		}
		
		public function countAll($cari)
		{
			if($cari) {
				$this->db->like('kode_mu', $cari)->or_like('nama_mu', $cari)
						->or_like('tanggal', $cari)->or_like('nilai_kurs', $cari);
			}
			return $this->db->join('mata_uang', 'mata_uang.kode_mu = kurs.mata_uang')
							->from('kurs')->count_all_results();
		}
		
		public function getById($id)
		{
			return $this->db->join('mata_uang', 'mata_uang.kode_mu = kurs.mata_uang')
							->where('kode_kurs', $id)
							->get('kurs')->row_array();
		}

		public function getKurs($mataUang, $tanggal)
		{
			return $this->db->where(['mata_uang'=>$mataUang, 'tanggal <= '=>$tanggal])
							->order_by('tanggal', 'desc')
							->get('kurs')->row_array();
		}
		
		public function getMax()
		{
			$max = $this->db->select_max('kode_kurs')
							->get('kurs')->row_array();
			
			$tambah	= $max ? substr($max['kode_kurs'], 5) : 0;
			$baru	= sprintf('%03s', ++$tambah);
			$pre	= 'MU'.date('y');
			return $pre . $baru;
		}

		public function add()
		{
			$data = array(
				'kode_kurs'		=> $this->getMax(),
				'mata_uang'		=> $this->input->post('mata_uang', true),
				'tanggal'		=> Globals::dateFormat($this->input->post('tanggal', true)),
				'nilai_kurs'	=> Globals::moneyFormat($this->input->post('nilai_kurs', true)),
			);
			$this->db->insert('kurs', $data);
			return $this->db->affected_rows();
		}

		public function edit()
		{
			$data = array(
				'mata_uang'		=> $this->input->post('mata_uang', true),
				'tanggal'		=> Globals::dateFormat($this->input->post('tanggal', true)),
				'nilai_kurs'	=> Globals::moneyFormat($this->input->post('nilai_kurs', true)),
			);
			$this->db->update('kurs', $data, ['kode_kurs' => $this->input->post('kode_kurs', true)]);
			return $this->db->affected_rows();
		}

		public function delete($kode)
		{
			$this->db->delete('kurs', ['kode_kurs' => $kode]);
			return $this->db->affected_rows();
		}
	}
?>