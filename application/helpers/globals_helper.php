<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Globals 
{
	public static function perusahaan($db_name)
	{
		$db_name = 'gl_'.$db_name;
		
		$config_app['hostname'] = 'localhost';
		$config_app['username'] = 'root';
		$config_app['password'] = '';
		$config_app['database'] = $db_name;
		$config_app['dbdriver'] = 'mysqli';
		$config_app['dbprefix'] = '';
		$config_app['pconnect'] = FALSE;
		$config_app['db_debug'] = TRUE;
		
		return $config_app;
	}
		
	/**
	 * mengubah data ke format tampilan uang
	 */
	public static function moneyView($money)
	{
		return number_format($money,2,',','.');
	}
	
	/**
	 * mengubah inputan ke format angka untuk disimpan
	 */
	public static function moneyFormat($money)
	{
		$money = str_replace(['.', ','], ['', '.'], $money);
		return number_format($money,2,'.','');
	}
	
	/**
	 * mengubah data ke format tampilan tanggal
	 */
	public static function dateView($date)
	{
		$date = explode('/', $date);
		$date = implode('-', $date);
		return ($date) ? date('d/m/Y', strtotime($date)) : '';
	}
	
	/**
	 * mengubah inputan ke format tanggal untuk disimpan
	 */
	public static function dateFormat($date)
	{
		$date = explode('/', $date);
		$date = implode('-', $date);
		return ($date) ? date('Y/m/d', strtotime($date)) : '';
	}
	
	public static function bulan($key='') 
	{
		$bulan_array = [
			['id' => '01', 'nama' => 'Januari'],
			['id' => '02', 'nama' => 'Februari'],
			['id' => '03', 'nama' => 'Maret'],
			['id' => '04', 'nama' => 'April'],
			['id' => '05', 'nama' => 'Mei'],
			['id' => '06', 'nama' => 'Juni'],
			['id' => '07', 'nama' => 'Juli'],
			['id' => '08', 'nama' => 'Agustus'],
			['id' => '09', 'nama' => 'September'],
			['id' => '10', 'nama' => 'Oktober'],
			['id' => '11', 'nama' => 'November'],
			['id' => '12', 'nama' => 'Desember'],
		];
		
		if( $key ) {
			foreach($bulan_array as $bulan) {
				if($key == $bulan['id'] || $key == $bulan['nama'])
				return $bulan;
			}
			return [];
		} else {
			return $bulan_array;
		}
	}
}