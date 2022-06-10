<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Uang_model extends CI_Model 
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
		
		public function getAll() 
		{
			return $this->db->get('mata_uang')->result_array();
		}

		public function countAll() 
		{
			return $this->db->from('mata_uang')->count_all_results();
		}
		
		public function getById($id) 
		{
			return $this->db->get_where('mata_uang', ['kode_mu' => $id])->row_array();
		}

		public function add() 
		{
			$data = [
				'kode_mu'	=> $this->input->post('kode_mu'),
				'nama_mu'	=> $this->input->post('nama_mu'),
			];
			$this->db->insert('mata_uang', $data);
			return $this->db->affected_rows();
		}
		
		public function edit() 
		{
			$kode = $this->input->post('kode_mu');
			$data = [
				'nama_mu'	=> $this->input->post('nama_mu'),
			];
			$this->db->update('mata_uang', $data, ['kode_mu' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function delete($kode_mu) 
		{
			$this->db->delete('mata_uang', ['kode_mu' => $kode_mu]);
			return $this->db->affected_rows();
		}
		
		public function daftarMataUang() 
		{
			return [
				['kode' =>'AFA', 'nama' => 'Afghani Afganistan',],
				['kode' =>'MGA', 'nama' => 'Ariary Madagaskar',],
				['kode' =>'THB', 'nama' => 'Baht Thailand',],
				['kode' =>'PAB', 'nama' => 'Balboa Panama',],
				['kode' =>'ETB', 'nama' => 'Birr Ethiopia',],
				['kode' =>'VEB', 'nama' => 'Bolivar Venezuela',],
				['kode' =>'BOB', 'nama' => 'Boliviano Bolivia',],
				['kode' =>'GHC', 'nama' => 'Cedi Ghana',],
				['kode' =>'SVC', 'nama' => 'Colon El Salvador',],
				['kode' =>'CRC', 'nama' => 'Colone Kosta Rika',],
				['kode' =>'NIO', 'nama' => 'Cordoba Nikaragua',],
				['kode' =>'GMD', 'nama' => 'Dalasi Gambia',],
				['kode' =>'MKD', 'nama' => 'Denar Makedonia',],
				['kode' =>'DZD', 'nama' => 'Dinar Aljazair',],
				['kode' =>'BHD', 'nama' => 'Dinar Bahrain',],
				['kode' =>'IQD', 'nama' => 'Dinar Irak',],
				['kode' =>'KD', 'nama' => 'Dinar Kuwait',],
				['kode' =>'LYD', 'nama' => 'Dinar Libya',],
				['kode' =>'RSD', 'nama' => 'Dinar Serbia',],
				['kode' =>'TND', 'nama' => 'Dinar Tunisia',],
				['kode' =>'JOD', 'nama' => 'Dinar Yordania',],
				['kode' =>'MAD', 'nama' => 'Dirham Maroko',],
				['kode' =>'AED', 'nama' => 'Dirham Uni Emirat Arab',],
				['kode' =>'USD', 'nama' => 'Dollar Amerika Serikat',],
				['kode' =>'AUD', 'nama' => 'Dollar Australia',],
				['kode' =>'BSD', 'nama' => 'Dollar Bahama',],
				['kode' =>'BBD', 'nama' => 'Dollar Barbados',],
				['kode' =>'BZD', 'nama' => 'Dollar Belize',],
				['kode' =>'BMD', 'nama' => 'Dollar Bermuda',],
				['kode' =>'BND', 'nama' => 'Dollar Brunei Darussalam',],
				['kode' =>'FJD', 'nama' => 'Dollar Fiji',],
				['kode' =>'GYD', 'nama' => 'Dollar Guyana',],
				['kode' =>'HKD', 'nama' => 'Dollar Hong Kong',],
				['kode' =>'JMD', 'nama' => 'Dollar Jamaika',],
				['kode' =>'CAD', 'nama' => 'Dollar Kanada',],
				['kode' =>'XCD', 'nama' => 'Dollar Karibia Timur',],
				['kode' =>'KYD', 'nama' => 'Dollar Kepulauan Cayman',],
				['kode' =>'SBD', 'nama' => 'Dollar Kepulauan Solomon',],
				['kode' =>'LRD', 'nama' => 'Dollar Liberia',],
				['kode' =>'NAD', 'nama' => 'Dollar Namibia',],
				['kode' =>'NZD', 'nama' => 'Dollar Selandia Baru',],
				['kode' =>'SGD', 'nama' => 'Dollar Singapura',],
				['kode' =>'SRD', 'nama' => 'Dollar Suriname',],
				['kode' =>'TWD', 'nama' => 'Dollar Taiwan',],
				['kode' =>'TTD', 'nama' => 'Dollar Trinidad and Tobago',],
				['kode' =>'VND', 'nama' => 'Dong Vietnam',],
				['kode' =>'AMD', 'nama' => 'Dram Armenia',],
				['kode' =>'CVE', 'nama' => 'Escudo Tanjung Verde',],
				['kode' =>'EUR', 'nama' => 'Euro ',],
				['kode' =>'AWG', 'nama' => 'Forint Aruba',],
				['kode' =>'HUF', 'nama' => 'Forint Hongaria',],
				['kode' =>'XOF', 'nama' => 'Franc Afrika Barat',],
				['kode' =>'XAF', 'nama' => 'Franc Afrika Tengah',],
				['kode' =>'BIF', 'nama' => 'Franc Burundi',],
				['kode' =>'XPF', 'nama' => 'Franc CFP',],
				['kode' =>'DJF', 'nama' => 'Franc Djibouti',],
				['kode' =>'GNF', 'nama' => 'Franc Guinea',],
				['kode' =>'KMF', 'nama' => 'Franc Komoro',],
				['kode' =>'CFD', 'nama' => 'Franc Republik Demokratik Kongo',],
				['kode' =>'RWF', 'nama' => 'Franc Rwanda',],
				['kode' =>'CHF', 'nama' => 'Franc Swiss',],
				['kode' =>'HTG', 'nama' => 'Gourde Haiti',],
				['kode' =>'PYG', 'nama' => 'Guarani Paraguay',],
				['kode' =>'UAH', 'nama' => 'Hryvnia Ukraina',],
				['kode' =>'PGK', 'nama' => 'Kina Papua Nugini',],
				['kode' =>'LAK', 'nama' => 'Kip Laos',],
				['kode' =>'CZK', 'nama' => 'Koruny Ceko (Republik Ceko)',],
				['kode' =>'DKK', 'nama' => 'Krone Denmark',],
				['kode' =>'NOK', 'nama' => 'Krone Norwegia',],
				['kode' =>'SEK', 'nama' => 'Krono Swedia',],
				['kode' =>'ISK', 'nama' => 'Kronu Islandia',],
				['kode' =>'HRK', 'nama' => 'Kuna Kroasia',],
				['kode' =>'MWK', 'nama' => 'Kwacha Malawi',],
				['kode' =>'ZMK', 'nama' => 'Kwacha Zambia',],
				['kode' =>'AOA', 'nama' => 'Kwanza Angola',],
				['kode' =>'MMK', 'nama' => 'Kyat Myanmar (Burma)',],
				['kode' =>'GEL', 'nama' => 'Lari Georgia',],
				['kode' =>'ALL', 'nama' => 'Lekë Albania',],
				['kode' =>'HNL', 'nama' => 'Lempira Honduras',],
				['kode' =>'SLL', 'nama' => 'Leone Sierra Leone',],
				['kode' =>'MDL', 'nama' => 'Leu Moldova',],
				['kode' =>'RON', 'nama' => 'Leu Rumania',],
				['kode' =>'BGN', 'nama' => 'Lev Bulgaria',],
				['kode' =>'SZL', 'nama' => 'Lilageni Swaziland',],
				['kode' =>'TRY', 'nama' => 'Lira Turki',],
				['kode' =>'LSL', 'nama' => 'Maloti Lesotho',],
				['kode' =>'AZN', 'nama' => 'Manat Azerbaijan',],
				['kode' =>'TMM', 'nama' => 'Manat Turkmenistan',],
				['kode' =>'BAM', 'nama' => 'Mark Bosnia dan Herzegovina',],
				['kode' =>'MZM', 'nama' => 'Meticai Mozambik',],
				['kode' =>'NGN', 'nama' => 'Naira Nigeria',],
				['kode' =>'BTN', 'nama' => 'Ngultrum Bhutan',],
				['kode' =>'MRO', 'nama' => 'Ouguiya Mauritania',],
				['kode' =>'TOP', 'nama' => 'Pa anga	Tonga',],
				['kode' =>'MOP', 'nama' => 'Pataca Makau',],
				['kode' =>'ARS', 'nama' => 'Peso Argentina',],
				['kode' =>'CLP', 'nama' => 'Peso Chili',],
				['kode' =>'DOP', 'nama' => 'Peso Republik Dominika',],
				['kode' =>'PHP', 'nama' => 'Peso Filipina',],
				['kode' =>'COP', 'nama' => 'Peso Kolombia',],
				['kode' =>'CUP', 'nama' => 'Peso Kuba',],
				['kode' =>'MXN', 'nama' => 'Peso Meksiko',],
				['kode' =>'UYU', 'nama' => 'Peso Uruguay',],
				['kode' =>'GBP', 'nama' => 'Pound Britania Raya',],
				['kode' =>'LBP', 'nama' => 'Pound Lebanon',],
				['kode' =>'EGP', 'nama' => 'Pound Mesir',],
				['kode' =>'SDG', 'nama' => 'Pound Sudan',],
				['kode' =>'BWP', 'nama' => 'Pula Botswana',],
				['kode' =>'GTQ', 'nama' => 'Quetzal Guatemala',],
				['kode' =>'ZAR', 'nama' => 'Rand Afrika Selatan',],
				['kode' =>'BRL', 'nama' => 'Real Brasil',],
				['kode' =>'IRR', 'nama' => 'Rial Iran',],
				['kode' =>'OMR', 'nama' => 'Rial Oman',],
				['kode' =>'YER', 'nama' => 'Rial Yaman',],
				['kode' =>'KHR', 'nama' => 'Riel Kamboja',],
				['kode' =>'MYR', 'nama' => 'Ringgit Malaysia',],
				['kode' =>'SAR', 'nama' => 'Riyal Arab Saudi',],
				['kode' =>'QAR', 'nama' => 'Riyal Qatar',],
				['kode' =>'BYR', 'nama' => 'Ruble Belarus',],
				['kode' =>'RUB', 'nama' => 'Ruble Rusia',],
				['kode' =>'MVR', 'nama' => 'Rufiyaa Maladewa',],
				['kode' =>'INR', 'nama' => 'Rupee India',],
				['kode' =>'MUR', 'nama' => 'Rupee Mauritius',],
				['kode' =>'NPR', 'nama' => 'Rupee Nepal',],
				['kode' =>'PKR', 'nama' => 'Rupee Pakistan',],
				['kode' =>'SCR', 'nama' => 'Rupee Seychelles',],
				['kode' =>'LKR', 'nama' => 'Rupee Sri Lanka',],
				['kode' =>'IDR', 'nama' => 'Rupiah Indonesia',],
				['kode' =>'ILS', 'nama' => 'Shekel Israel',],
				['kode' =>'KES', 'nama' => 'Shilling Kenya',],
				['kode' =>'SOS', 'nama' => 'Shilling Somalia',],
				['kode' =>'TZS', 'nama' => 'Shilling Tanzania',],
				['kode' =>'UGX', 'nama' => 'Shilling Uganda',],
				['kode' =>'PEN', 'nama' => 'Sole Peru',],
				['kode' =>'KGS', 'nama' => 'Som Kirgizstan',],
				['kode' =>'TJS', 'nama' => 'Somoni Tajikistan',],
				['kode' =>'UZS', 'nama' => 'Som Uzbekistan',],
				['kode' =>'BDT', 'nama' => 'Taka Bangladesh',],
				['kode' =>'KZT', 'nama' => 'Tenge Kazakhstan',],
				['kode' =>'KRW', 'nama' => 'Won Korea Selatan',],
				['kode' =>'KPW', 'nama' => 'Won Korea Utara',],
				['kode' =>'JPY', 'nama' => 'Yen Jepang',],
				['kode' =>'RMB', 'nama' => 'Yuan Tiongkok',],
				['kode' =>'PLN', 'nama' => 'Zloty Polandia',],
			];
		}
	}
