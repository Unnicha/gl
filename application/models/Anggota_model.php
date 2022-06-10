<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Anggota_model extends CI_Model 
	{
		public function getAll($cari="", $offset=0, $limit='', $order='') 
		{
			if($cari) {
				$this->db->like('id_user', $cari)
						->or_like('username', $cari)
						->or_like('nama', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->where('tipe','anggota')
							->get('user')->result_array();
		}

		public function getAllUser() 
		{
			return  $this->db->get('user')->result_array();
		}
		
		public function countAll($cari='') 
		{
			if($cari) {
				$this->db->like('id_user', $cari)
						->or_like('username', $cari)
						->or_like('nama', $cari);
			}
			return $this->db->where('tipe','anggota')
							->from('user')->count_all_results();
		}
		
		public function getById($id_user) 
		{
			return $this->db->get_where('user', ['id_user' => $id_user])->row_array();
		}
		
		public function getNewKode() 
		{
			$max = $this->db->select_max('id_user')
							->where('tipe','anggota')
							->get('user')->row_array();
			
			$add	= ($max['id_user'] != null) ? substr($max['id_user'], -3) : 0;
			$baru	= sprintf('%03s', ++$add);
			$pre	= 'U';
			return $pre.$baru;
		
		}

		public function add() 
		{
			$data = [
				'id_user'	=> $this->getNewKode(),
				'nama'		=> $this->input->post('nama', true),
				'username'	=> $this->input->post('username', true),
				'password'	=> $this->input->post('password', true),
				'tipe'		=> 'anggota',
			];
			$this->db->insert('user', $data);
			return $this->db->affected_rows();
		}

		public function update()
		{
			$kode = $this->input->post('id_user', true);
			$data = [
				'nama'		=> $this->input->post('nama', true),
				'username'	=> $this->input->post('username', true),
				'password'	=> $this->input->post('password', true),
				'tipe'		=> 'anggota',
			];
			$this->db->update('user', $data, ['id_user' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function change_password()
		{
			$kode = $this->input->post('id_user', true);
			$data = [
				'password'	=> password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
			];
			$this->db->update('user', $data, ['id_user' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function change_akses()
		{
			$kode = $this->input->post('id_user', true);
			$data = [
				'akses_perusahaan'	=> implode(',', $this->input->post('perusahaan', true)),
			];
			$this->db->update('user', $data, ['id_user' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function delete($id) 
		{
			if($this->countAll() > 1) {
				$this->db->delete('user', ['id_user' => $id]);
				return $this->db->affected_rows();
			} else {
				return -1;
			}
		}
	}
?>
