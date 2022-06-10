<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Akun_model extends CI_Model
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
		
		// Get selected data (for table / another menu)
		public function getAll($cari='', $offset=0, $limit='', $order='') 
		{
			if($cari) {
				$this->db->like('akun_perkiraan.kode_akun', $cari)->or_like('akun_perkiraan.nama_akun', $cari)
						->or_like('akun_perkiraan.golongan', $cari)->or_like('akun_perkiraan.tingkat', $cari)
						->or_like('akun_perkiraan.tipe_akun', $cari)->or_like('jenis.nama_jenis', $cari)
						->or_like('akun_perkiraan.induk', $cari)->or_like('induk.nama_akun', $cari)
						->or_like('akun_perkiraan.saldo_normal', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->select('akun_perkiraan.*, jenis.*, induk.nama_akun AS nama_induk')
							->from('akun_perkiraan')
							->join('jenis','jenis.id_jenis = akun_perkiraan.jenis_akun','left')
							->join('akun_perkiraan AS induk','akun_perkiraan.induk = induk.kode_akun','left')
							->get()->result_array();
		}
		
		// Count selected data (for table)
		public function countAll($cari) 
		{
			if($cari) {
				$this->db->like('akun_perkiraan.kode_akun', $cari)->or_like('akun_perkiraan.nama_akun', $cari)
						->or_like('akun_perkiraan.golongan', $cari)->or_like('akun_perkiraan.tingkat', $cari)
						->or_like('akun_perkiraan.tipe_akun', $cari)->or_like('jenis.nama_jenis', $cari)
						->or_like('akun_perkiraan.induk', $cari)->or_like('induk.nama_akun', $cari)
						->or_like('akun_perkiraan.saldo_normal', $cari);
			}
			return $this->db->select('akun_perkiraan.*, jenis.*, induk.nama_akun AS nama_induk')
							->from('akun_perkiraan')
							->join('jenis','jenis.id_jenis = akun_perkiraan.jenis_akun','left')
							->join('akun_perkiraan AS induk','akun_perkiraan.induk = induk.kode_akun','left')
							->count_all_results();
		}
		
		// Get data by Id (for update / detail)
		public function getById($id) 
		{
			if (is_array($id)) {
				return $this->db->select('akun_perkiraan.*, jenis.*, induk.nama_akun AS nama_induk')
								->from('akun_perkiraan')
								->join('jenis','jenis.id_jenis = akun_perkiraan.jenis_akun','left')
								->join('akun_perkiraan AS induk','akun_perkiraan.induk = induk.kode_akun','left')
								->where_in('akun_perkiraan.kode_akun', $id)->get()->result_array();
			} else {
				return $this->db->select('akun_perkiraan.*, jenis.*, induk.nama_akun AS nama_induk')
								->from('akun_perkiraan')
								->join('jenis','jenis.id_jenis = akun_perkiraan.jenis_akun','left')
								->join('akun_perkiraan AS induk','akun_perkiraan.induk = induk.kode_akun','left')
								->where('akun_perkiraan.kode_akun', $id)->get()->row_array();
			}
		}
		
		// Get Kode Perkiraan by Tipe (Induk/Anak)
		public function getByTipe($tipe) 
		{
			return $this->db->join('jenis','jenis.id_jenis = akun_perkiraan.jenis_akun','left')
							->where('tipe_akun', $tipe)
							->get('akun_perkiraan')->result_array();
		}
		
		// Get Kode Perkiraan as Induk
		public function getPerkiraanInduk() 
		{
			return $this->db->where('tipe_akun', 'Induk')
							->get('akun_perkiraan')->result_array();
		}
		
		// Get Kode Perkiraan by Jenis (make sure you add the right id_jenis)
		public function getByJenis($id_jenis) 
		{
			return $this->db->join('jenis','jenis.id_jenis = akun_perkiraan.jenis_akun','left')
							->where_in('jenis_akun', $id_jenis)
							->get('akun_perkiraan')->result_array();
		}
		
		// Get data Jenis by Tipe (for add / update)
		public function getJenis($tipe)
		{
			return $this->db->get_where('jenis', ['tipe_jenis' => $tipe])->result_array();
		}
		
		// Get Kode Perkiraan as Induk
		public function getInduk($tingkat)
		{
			return $this->db->where('tingkat', $tingkat)
							->get('akun_perkiraan')->result_array();
		}
		
		// Add data
		public function save()
		{
			$saldo_awal	= $this->input->post('saldo_awal', true);
			if ($_REQUEST['tipe'] != 'Induk') 
			$saldo_awal	= ($this->input->post('nilai_saldo_awal', true) == 'Debit') ? $saldo_awal : '-'.$saldo_awal;
			
			$data = [
				'kode_akun'		=> $this->input->post('kode_akun', true),
				'nama_akun'		=> $this->input->post('nama_akun', true),
				'golongan'		=> $this->input->post('golongan', true),
				'tingkat'		=> $this->input->post('tingkat', true),
				'tipe_akun'		=> $this->input->post('tipe', true),
				'jenis_akun'	=> $this->input->post('jenis', true),
				'induk'			=> $this->input->post('induk', true),
				'saldo_normal'	=> $this->input->post('saldo_normal', true),
				'saldo_awal'	=> $saldo_awal,
				
			];
			$this->db->insert('akun_perkiraan',$data);
			return $this->db->affected_rows();
		}
		
		// Update Selected Data
		public function update() 
		{
			$kode_akun		= $this->input->post('kode_akun', true);
			$jenis_saldo	= ($this->input->post('nilai_saldo_awal', true) == 'Debit') ? '' : '-';
			$jenis_saldo	= ($_REQUEST['tipe'] == 'Induk') ? '' : $jenis_saldo;
			$saldo_awal		= ($_REQUEST['tipe'] == 'Induk') ? '-' : abs($this->input->post('saldo_awal', true));
			$data = [
				'nama_akun'		=> $this->input->post('nama_akun', true),
				'golongan'		=> $this->input->post('golongan', true),
				'tingkat'		=> $this->input->post('tingkat', true),
				'tipe_akun'		=> $this->input->post('tipe', true),
				'jenis_akun'	=> $this->input->post('jenis', true),
				'induk'			=> $this->input->post('induk', true),
				'saldo_normal'	=> $this->input->post('saldo_normal', true),
				'saldo_awal'	=> $jenis_saldo . $saldo_awal,
			];
			$this->db->update('akun_perkiraan', $data, ['kode_akun' => $kode_akun]);
			return $this->db->affected_rows();
		}
		
		//Delete Data
		public function delete($id) 
		{
			$this->db->delete('akun_perkiraan', ['kode_akun' => $id]);
			return $this->db->affected_rows();
		}
	}


	
