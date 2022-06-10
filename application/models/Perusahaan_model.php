<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Perusahaan_model extends CI_Model 
	{
		public function getAll($cari="", $offset=0, $limit='', $order='') 
		{
			if($cari) {
				$this->db->like('kode_perusahaan', $cari)
						->or_like('nama_perusahaan', $cari)
						->or_like('tgl_mulai', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->get('perusahaan')
							->result_array();
		}
		
		public function countAll($cari='') 
		{
			if($cari) {
				$this->db->like('kode_perusahaan', $cari)
						->or_like('nama_perusahaan', $cari)
						->or_like('tgl_mulai', $cari);
			}
			return $this->db->from('perusahaan')->count_all_results();
		}
		
		public function getById($kode_perusahaan) 
		{
			if(is_array($kode_perusahaan)) {
				return $this->db->where_in('kode_perusahaan' , $kode_perusahaan)->get('perusahaan')->result_array();
			} else {
				return $this->db->get_where('perusahaan', ['kode_perusahaan' => $kode_perusahaan])->row_array();
			}
		}
		
		public function add() 
		{
			$data = [
				'kode_perusahaan'	=> $this->input->post('kode_perusahaan', true),
				'nama_perusahaan'	=> $this->input->post('nama_perusahaan', true),
				'alamat'			=> $this->input->post('alamat', true),
				'tlp'				=> $this->input->post('tlp', true),
				'fax'				=> $this->input->post('fax', true),
				'npwp'				=> $this->input->post('npwp', true),
				'kode_pajak'		=> $this->input->post('kode_pajak', true),
				'tgl_pkp'			=> date('Y-m-d', strtotime($this->input->post('tgl_pkp', true)) ),
			];
			$this->db->insert('perusahaan', $data);
			return $this->db->affected_rows();
		}
		
		public function update()
		{
			$kode = $this->input->post('kode_perusahaan', true);
			$data = [
				'nama_perusahaan'	=> $this->input->post('nama_perusahaan', true),
				'alamat'			=> $this->input->post('alamat', true),
				'tlp'				=> $this->input->post('tlp', true),
				'fax'				=> $this->input->post('fax', true),
				'npwp'				=> $this->input->post('npwp', true),
				'kode_pajak'		=> $this->input->post('kode_pajak', true),
				'tgl_pkp'			=> date('Y-m-d', strtotime($this->input->post('tgl_pkp', true)) ),
			];
			$this->db->update('perusahaan', $data, ['kode_perusahaan' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function delete($id) 
		{
			$this->db->delete('perusahaan', ['kode_perusahaan' => $id]);
			return $this->db->affected_rows();
		}
		
		// =========================================================================================== //
		
		public function getSetup($cari="", $offset=0, $limit='', $order='') 
		{
			if($cari) {
				$this->db->like('kode_perusahaan', $cari)
						->or_like('nama_perusahaan', $cari)
						->or_like('tgl_mulai', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->join('perusahaan', 'perusahaan.kode_perusahaan = setup_perusahaan.kode', 'left')
							->get('setup_perusahaan')->result_array();
		}
		
		public function countSetup($cari='') 
		{
			if($cari) {
				$this->db->like('kode_perusahaan', $cari)
						->or_like('nama_perusahaan', $cari)
						->or_like('tgl_mulai', $cari);
			}
			return $this->db->join('perusahaan', 'perusahaan.kode_perusahaan = setup_perusahaan.kode', 'left')
							->from('setup_perusahaan')->count_all_results();
		}
		
		public function getSetupById($id_setup) 
		{
			if(is_array($id_setup)) {
				return $this->db->where_in('id_setup' , $id_setup)
								->join('perusahaan', 'perusahaan.kode_perusahaan = setup_perusahaan.kode', 'left')
								->get('setup_perusahaan')->result_array();
			} else {
				return $this->db->where('id_setup', $id_setup)
								->join('perusahaan', 'perusahaan.kode_perusahaan = setup_perusahaan.kode', 'left')
								->get('setup_perusahaan')->row_array();
			}
		}
		
		public function getSetupByPerusahaan($id_perusahaan)
		{
			return $this->db->where_in('kode', $id_perusahaan)
							->join('perusahaan', 'perusahaan.kode_perusahaan = setup_perusahaan.kode', 'left')
							->get('setup_perusahaan')->result_array();
		}
		
		public function addSetup() 
		{
			$kode	= $this->input->post('perusahaan', true);
			$tahun	= $this->input->post('tahun', true);
			
			$data = [
				'id_setup'	=> $kode.$tahun,
				'kode'		=> $kode,
				'tahun'		=> $tahun,
				'tgl_mulai'	=> date('Y-m-d', strtotime($this->input->post('tgl_mulai', true)) ),
				'database'	=> $kode.$tahun
			];
			$this->db->insert('setup_perusahaan', $data);
			return $this->db->affected_rows();
		}
		
		public function updateSetup()
		{
			$kode = $this->input->post('perusahaan', true);
			$data = [
				'tahun'		=> $this->input->post('tahun', true),
				'tgl_mulai'	=> date('Y-m-d', strtotime($this->input->post('tgl_mulai', true)) ),
			];
			$this->db->update('setup_perusahaan', $data, ['id_setup' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function deleteSetup($id) 
		{
			$this->db->delete('setup_perusahaan', ['id_setup' => $id]);
			return $this->db->affected_rows();
		}
	}
?>
