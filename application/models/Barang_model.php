<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Barang_model extends CI_Model 
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
		
		public function getAll($cari='', $offset=0, $limit='', $order='') 
		{
			if($cari) {
				$this->db->like('kode_barang', $cari)
						->or_like('nama_barang', $cari)
						->or_like('proses', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->get('barang')
							->result_array();
		}
		
		public function countAll($cari='') 
		{
			if($cari) {
				$this->db->like('kode_barang', $cari)
						->or_like('nama_barang', $cari)
						->or_like('proses', $cari);
			}
			return $this->db->from('barang')->count_all_results();
		}
		
		//masukan data yang berhasil di input tiap-tiap field
		public function getById($kode_barang) 
		{
			return $this->db->get_where('barang', ['kode_barang' => $kode_barang])->row_array();
		}
		
		public function getStock($kode_barang) 
		{
			$jual = $this->db->query('SELECT SUM(qty_produk) AS stok_jual FROM penjualan_produk WHERE kode_produk = "'.$kode_barang.'"')->row_array();
			$beli = $this->db->query('SELECT SUM(qty_produk) AS stok_beli FROM pembelian_produk WHERE kode_produk = "'.$kode_barang.'"')->row_array();
			$awal = $this->db->query('SELECT stok_awal FROM barang WHERE kode_barang = "'.$kode_barang.'"')->row_array();
			return $awal['stok_awal'] - $jual['stok_jual'] + $beli['stok_beli'];
		}
		
		public function add() 
		{
			$data = [
				'kode_barang'	=> $this->input->post('kode_barang', true),
				'nama_barang'	=> $this->input->post('nama_barang', true),
				'satuan'	    => $this->input->post('satuan', true),
				'stok_awal'		=> $this->input->post('stock_awal', true),
				'nilai_awal'	=> $this->input->post('nilai_awal', true),
				'proses'		=> $this->input->post('proses', true),
			];
			$this->db->insert('barang', $data);
			return $this->db->affected_rows();
		}
		
		public function edit() 
		{
			$kode = $this->input->post('kode_barang', true);
			$data = [
				"nama_barang"	=> $this->input->post('nama_barang',  true),
				"satuan"		=> $this->input->post('satuan', true),
				"stok_awal"		=> $this->input->post('stock_awal', true),
				"nilai_awal"	=> $this->input->post('nilai_awal', true),
				"proses"		=> $this->input->post('proses', true)
			];
			$this->db->update('barang', $data, ['kode_barang' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function delete($kode_barang) 
		{
			$this->db->delete('barang', ['kode_barang' => $kode_barang]);
			return $this->db->affected_rows();
		}
	}

   
