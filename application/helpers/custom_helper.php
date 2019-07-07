<?php
date_default_timezone_set('Asia/Jakarta');
ini_set('max_execution_time', 0); 
ini_set('memory_limit', '-1'); 
//ini_set('display_errors', 0);
// function to check if the system has been installed
$CI =& get_instance();
function test($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}
function rupiah($angka){	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
}
function persen($angka, $total){
	if($angka){
		$persen = round($angka/$total * 100,2);
	} else {
		$persen = 0;
	}
	return $persen;
}
function persen_kpu($angka, $total){
	if($angka){
		$persen = round($angka/$total * 100,1);
	} else {
		$persen = 0;
	}
	return $persen;
}
function provinsi($id){
	global $CI;
	$query = $CI->provinsi->get($id);
	$result = ($query) ? $query->nama : '-';
	return $result;
}
function kabupaten($id){
	global $CI;
	$query = $CI->kabupaten->get($id);
	$result = ($query) ? $query->nama : '-';
	return $result;
}
function kecamatan($id){
	global $CI;
	$query = $CI->kecamatan->get($id);
	$result = ($query) ? $query->nama : '-';
	return $result;
}
function get_suara_desa($id_desa){
	global $CI;
	$CI->db->select('sum(p1) as jokowi, sum(p2) as prabowo, sum(sah) as sah, sum(t_sah) as t_sah');
	$CI->db->from('tps');
	$CI->db->where('id_desa', $id_desa);
	$query = $CI->db->get();
	$result = $query->row();
	return $result;
}
function download_old($file){
	$files = explode('/',$file);
	$file_image = array_pop($files);
	$folder = implode('/',$files);
	$path = 'c1/'.$folder;
	if(!is_dir($path)){
		mkdir($path,0755,TRUE);
	} 
	$saveTo = $file_image;
	$downloadFrom = 'https://pemilu2019.kpu.go.id/img/c/'.$file; 
	$curl = curl_init(); 
	$fp = fopen($saveTo, 'w'); 
	curl_setopt($curl, CURLOPT_URL, $downloadFrom); 
	curl_setopt($curl, CURLOPT_FILE, $fp); 
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
	curl_exec ($curl); 
	curl_close ($curl); 
	fclose($fp);
	echo '<br />';
	echo $downloadFrom.'<br />'; 
	echo 'selesai';
}
function download($file){
	$ch = curl_init('https://pemilu2019.kpu.go.id/img/c/'.$file);
	$fp = fopen($file, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	echo 'selesai';
}
function save_image($inPath,$outPath, $folder){ //Download images from remote server
	$path = 'c1/'.$folder;
	if(!is_dir($path)){
		mkdir($path,0755,TRUE);
	}
    $in=    @fopen($inPath, "rb");
    $out=   @fopen($outPath, "wb");
    while ($chunk = @fread($in,8192)){
        @fwrite($out, $chunk, 8192);
    }
    @fclose($in);
    @fclose($out);
	@rename("$outPath", "./c1/$folder/$outPath");
}
function getSSLPage($url) {
    $arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);  
	$response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
	return $response;
}
function get_suara_paslon($query, $id){
	global $CI;
	$CI->db->select('sum(a.p1) as jokowi, sum(a.p2) as prabowo, sum(a.sah) as sah, sum(a.t_sah) as t_sah, count(a.c1) as c1, count(a.id) as tps');
	$CI->db->from('tps as a');
	$CI->db->join('desa as b', 'a.id_desa = b.id');
	if($query == 'all'){
		$CI->db->where('b.id_provinsi', $id);
	} elseif($query == 'provinsi'){
		$CI->db->where('b.id_kabupaten', $id);
	} elseif($query == 'kabupaten'){
		$CI->db->where('b.id_kecamatan', $id);
	} elseif($query == 'kecamatan'){
		$CI->db->where('b.id', $id);
	}
	$query = $CI->db->get();
	return $query->row();
}
function get_suara_paslon_kawal($query, $id){
	global $CI;
	$CI->db->select('sum(a.p1) as jokowi, sum(a.p2) as prabowo, sum(a.sah) as sah, sum(a.t_sah) as t_sah, count(a.c1) as c1, count(a.id) as tps');
	$CI->db->from('kawal_tps as a');
	$CI->db->join('kawal_desa as b', 'a.id_desa = b.id');
	if($query == 'all'){
		$CI->db->where('b.id_provinsi', $id);
	} elseif($query == 'provinsi'){
		$CI->db->where('b.id_kabupaten', $id);
	} elseif($query == 'kabupaten'){
		$CI->db->where('b.id_kecamatan', $id);
	} elseif($query == 'kecamatan'){
		$CI->db->where('b.id', $id);
	}
	$query = $CI->db->get();
	return $query->row();
}
function get_suara_paslon_kpu($query, $id){
	global $CI;
	$CI->db->select('sum(a.p1) as jokowi, sum(a.p2) as prabowo, sum(a.sah) as sah, sum(a.t_sah) as t_sah, count(a.c1) as c1, count(a.id) as tps, sum(a.tps_proses) as j_tps');
	$CI->db->from('kpu_tps as a');
	$CI->db->join('kpu_desa as b', 'a.id_desa = b.id');
	if($query == 'all'){
		$CI->db->where('b.id_provinsi', $id);
	} elseif($query == 'provinsi'){
		$CI->db->where('b.id_kabupaten', $id);
	} elseif($query == 'kabupaten'){
		$CI->db->where('b.id_kecamatan', $id);
	} elseif($query == 'kecamatan'){
		$CI->db->where('b.id', $id);
	} elseif($query == 'desa'){
		$CI->db->where('a.id', $id);
	}
	$query = $CI->db->get();
	return $query->row();
}
function secondsToTime($inputSeconds) {
	$secondsInAMinute = 60;
	$secondsInAnHour  = 60 * $secondsInAMinute;
	$secondsInADay    = 24 * $secondsInAnHour;
	// extract days
	$days = floor($inputSeconds / $secondsInADay);
	// extract hours
	$hourSeconds = $inputSeconds % $secondsInADay;
	$hours = floor($hourSeconds / $secondsInAnHour);
	// extract minutes
	$minuteSeconds = $hourSeconds % $secondsInAnHour;
	$minutes = floor($minuteSeconds / $secondsInAMinute);
	// extract the remaining seconds
	$remainingSeconds = $minuteSeconds % $secondsInAMinute;
	$seconds = ceil($remainingSeconds);
	if($days){
		$return = $days.' hari '.$hours.' jam '.$minutes.' menit '.$seconds.' detik';
	} elseif($hours){
		$return = $hours.' jam '.$minutes.' menit '.$seconds.' detik';
	} elseif($minutes){
		$return = $minutes.' menit '.$seconds.' detik';
	} else {
		$return = $seconds.' detik';
	}
	return $return;
}
function get_start() {
	$start = 0;
	if (isset($_GET['start'])) {
		$start = intval($_GET['start']);
		if ($start < 0)
			$start = 0;
	}
	return $start;
}
function get_rows() {
	$rows = 10;
	if (isset($_GET['length'])) {
		$rows = intval($_GET['length']);
		if ($rows < 5 || $rows > 500) {
			$rows = 10;
		}
	}
	return $rows;
}
function get_sort_dir() {
	$sort_dir = "ASC";
	$sdir = strip_tags($_GET['sSortDir_0']);
	if (isset($sdir)) {
		if ($sdir != "asc" ) {
			$sort_dir = "DESC";
		}
	}
	return $sort_dir;
}