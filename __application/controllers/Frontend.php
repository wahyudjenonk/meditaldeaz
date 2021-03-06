<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends JINGGA_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('cart');
	}
	
	function index(){
		//$data_tingkatan = $this->mfrontend->getdata('cl_tingkatan', 'result_array');
		//$this->nsmarty->assign('data_tingkatan', $data_tingkatan);
		$this->nsmarty->assign( 'judulbesar', "www.aldeaz.id" );
		$this->nsmarty->assign( 'judulkecil', "Klik -->katalog -->pilih zona -->cari buku -->beli" );
				
		$this->nsmarty->assign('konten', 'beranda');		
		$this->nsmarty->display( 'frontend/main-index.html');		
	}
	
	function getdisplay($type="", $p1="", $p2="", $p3=""){
		$zona_pilihan = $this->session->userdata('zonaxtreme');
		switch($type){
			case "main_page":				
				$data_cart = $this->cart->contents();
				$jumlah_item = count($data_cart);
				
				if($p1 == "beranda"){
					$this->nsmarty->assign( 'judulbesar', "www.aldeaz.id" );
					$this->nsmarty->assign( 'judulkecil', "Klik -->katalog -->pilih zona -->cari buku -->beli" );
				}elseif($p1 == "carabelanja"){
					$this->nsmarty->assign( 'judulbesar', "Cara Berbelanja" );
					$this->nsmarty->assign( 'judulkecil', "Petunjuk Singkat Berbelanja di www.aldeaz.id" );
				}elseif($p1 == "katalog"){
					$this->nsmarty->assign( 'judulbesar', "Katalog Buku" );
					$this->nsmarty->assign( 'judulkecil', "Temukan Buku Yang Anda Butuhkan" );
				}elseif($p1 == "lacakpesanan"){
					$this->nsmarty->assign( 'judulbesar', "Lacak Pesanan" );
					$this->nsmarty->assign( 'judulkecil', "Lacak & Ketahui Data Pesanan Anda" );
				}elseif($p1 == "konfirmasi"){
					$this->nsmarty->assign( 'judulbesar', "Konfirmasi Pembayaran" );
					$this->nsmarty->assign( 'judulkecil', "Konfirmasikan Pembayaran Yang Sudah Anda Lakukan." );
				}elseif($p1 == "riwayat"){
					$this->nsmarty->assign( 'judulbesar', "Riwayat Pesanan" );
					$this->nsmarty->assign( 'judulkecil', "Lacak & Ketahui Data Riwayat Pesanan Anda di www.aldeaz.id" );
				}elseif($p1 == "komplain"){
					$this->nsmarty->assign( 'judulbesar', "Layanan Komplain" );
					$this->nsmarty->assign( 'judulkecil', "Tuliskan Komplain Anda." );
				}elseif($p1 == "pembatalan"){
					$this->nsmarty->assign( 'judulbesar', "Pembatalan Pesanan" );
					$this->nsmarty->assign( 'judulkecil', "Layanan Pembatalan Pesanan Anda." );
				}
				
				if($zona_pilihan){
					$this->nsmarty->assign('zona_pilihan', "Anda Berada Dalam Zona Harga ".$zona_pilihan['zona_pilihan']);
				}
				$this->nsmarty->assign( 'tot_item', $jumlah_item );
				$this->nsmarty->assign( 'konten', $p1);
				if(isset($p2)){
					$this->nsmarty->assign('param2', $p2);	
				}
				$this->nsmarty->display( 'frontend/main-index.html');	
			break;
			case "loading_page":
				switch($p1){
					case "beranda":
						$temp = "frontend/modul/beranda-page.html";
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', '', 0, 16);
						foreach($data_buku as $k=>$v){
							if($data_buku[$k]['foto_produk'] != null){
								$data_buku[$k]['foto_produk'] = $this->host."__repository/produk/".$v['foto_produk'];
							}else{
								$data_buku[$k]['foto_produk'] = $this->host."__repository/no-image.jpeg";
							}
						} 
						//print_r($data_buku);exit;
						
						$this->nsmarty->assign('data_buku', $data_buku);
					break;
					case "tentangkami":
						$temp = "frontend/modul/tentangkami-page.html";
					break;
					case "kontak":
						$temp = "frontend/modul/kontak-page.html";
					break;
					case "katalog":
						if(isset($zona_pilihan)){
							$flag_window = "oye"; // kgk nongol maning
						}else{
							$flag_window = "uye"; // nongol window pilihan zona nya
						}
						
						$temp = "frontend/modul/katalog-page.html";
						$id_tingkatan = $this->db->get_where('cl_tingkatan', array('tingkatan'=>strtoupper($p2)))->row_array();
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', $id_tingkatan['id'], 0, 9);

						foreach($data_buku as $k=>$v){
							if(isset($zona_pilihan)){
								$data_buku[$k]['harga_buku_bener'] = number_format($v['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							}else{
								$data_buku[$k]['harga_buku_bener'] = "-";
							}
							if($data_buku[$k]['foto_produk'] != null){
								$data_buku[$k]['foto_produk'] = $this->host."__repository/produk/".$v['foto_produk'];
							}else{
								$data_buku[$k]['foto_produk'] = $this->host."__repository/no-image.jpeg";
							}
						}
						$data_tingkatan = $this->db->get('cl_tingkatan')->result_array();
						$data_kategori = $this->db->get('cl_kategori')->result_array();
						
						/*
						$array_tingkatan = array();
						foreach($data_tingkatan as $k => $v){
							$array_tingkatan[$k]['tingkatan'] = $v['tingkatan'];
							$array_tingkatan[$k]['detil'] = array();
							$getkelas = $this->db->get_where('cl_kelas', array('cl_tingkatan_id'=>$v['id']))->result_array();
							foreach($getkelas as $t => $p){
								$array_tingkatan[$k]['detil'][$t]['id'] = $p['id'];
								$array_tingkatan[$k]['detil'][$t]['nama_kelas'] = "Kelas ".$p['kelas'];
							}
						}
						$data_pengguna = $this->db->get('cl_group_sekolah')->result_array();
						$this->nsmarty->assign('data_pengguna', $data_pengguna);
						*/
						
						$total_data = $this->db->count_all("tbl_produk");
						$limit = 9;
						$total_paging = $total_data / $limit;
						$total_paging = floor($total_paging);
						$array_paging = array();
						for($i=0; $i <= $total_paging; $i++){
							$j = ($i + 1);
							if(isset($mulai)){
								$mulai = $mulai + $limit;
							}else{
								$mulai = 0;
							}
							
							$array_paging[$i]['angka'] = $j;
							$array_paging[$i]['limitnya'] = $mulai."-".$limit;
						}
						
						$this->nsmarty->assign('array_paging', $array_paging);
						$this->nsmarty->assign('data_tingkatan', $data_tingkatan);
						$this->nsmarty->assign('data_kategori', $data_kategori);
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('flag_window', $flag_window);
					break;
					case "datapaging":
						$temp = "frontend/modul/katalogpaging-page.html";
						$type = $this->input->post('dtype');
						$limit = $this->input->post('lmt');
						$explim = explode("-",$limit);
						
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', "", $explim[0], $explim[1]);
						foreach($data_buku as $k=>$v){
							$data_buku[$k]['harga_buku_bener'] = number_format($v['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							if($data_buku[$k]['foto_produk'] != null){
								$data_buku[$k]['foto_produk'] = $this->host."__repository/produk/".$v['foto_produk'];
							}else{
								$data_buku[$k]['foto_produk'] = $this->host."__repository/no-image.jpeg";
							}
							
						}
						
						$this->nsmarty->assign('type', $type);
						$this->nsmarty->assign('data_buku', $data_buku);
					break;
					case "filterdt":
					case "crdt":					
						$temp = "frontend/modul/katalogfilterdata-page.html";
						$data_buku = $this->mfrontend->getdata('data_buku', 'result_array', "", 0, 9);
						foreach($data_buku as $k=>$v){
							$data_buku[$k]['harga_buku_bener'] = number_format($v['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
							if($data_buku[$k]['foto_produk'] != null){
								$data_buku[$k]['foto_produk'] = $this->host."__repository/produk/".$v['foto_produk'];
							}else{
								$data_buku[$k]['foto_produk'] = $this->host."__repository/no-image.jpeg";
							}
						}
						
						$total_data = $this->mfrontend->getdata('hitung_data_filter', 'num_rows');
						$limit = 9;
						$total_paging = $total_data / $limit;
						$total_paging = floor($total_paging);
						$array_paging = array();
						for($i=0; $i <= $total_paging; $i++){
							$j = ($i + 1);
							if(isset($mulai)){
								$mulai = $mulai + $limit;
							}else{
								$mulai = 0;
							}
							
							$array_paging[$i]['angka'] = $j;
							$array_paging[$i]['limitnya'] = $mulai."-".$limit;
						}
						$typefilter = $this->input->post('ty');
						$idfilter = $this->input->post('isd');
						$crfilter = $this->input->post('cr');
						
						$this->nsmarty->assign('array_paging', $array_paging);						
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('typefilter', $typefilter);
						$this->nsmarty->assign('idfilter', $idfilter);
						$this->nsmarty->assign('crfilter', $crfilter);
					break;						
					case "combo_zona":
						$temp = "frontend/modul/combozona-page.html";
						$this->nsmarty->assign('combo_zona', $this->lib->fillcombo('cl_provinsi', 'return'));
					break;					
					case "kategori":
						$temp = "frontend/modul/kategori-page.html";
						$id_tingkatan = $this->db->get_where('cl_tingkatan', array('tingkatan'=>strtoupper($p2)))->row_array();
						$data_buku = $this->mfrontend->getdata('data_buku_tingkatan', 'result_array', $id_tingkatan['id']);
						foreach($data_buku as $k=>$v){
							$data_buku[$k]['judul_buku'] = $this->lib->cutstring($v['judul_buku'], 50);
						}
						
						$data_tingkatan = $this->db->get('cl_tingkatan')->result_array();
						$data_kategori = $this->db->get('cl_kategori')->result_array();
						$data_pengguna = $this->db->get('cl_group_sekolah')->result_array();
						
						$this->nsmarty->assign('data_tingkatan', $data_tingkatan);
						$this->nsmarty->assign('data_kategori', $data_kategori);
						$this->nsmarty->assign('data_pengguna', $data_pengguna);
						$this->nsmarty->assign('nama_kategori', strtoupper($p2));
						$this->nsmarty->assign('data_buku', $data_buku);
					break;
					case "detail_produk":
						$id = $this->input->post('isds');
						$temp = "frontend/modul/produkwindow-page.html";
						$data_buku = $this->mfrontend->getdata('data_buku_detail', 'row_array', $id);
						$data_fotobuku = $this->db->get_where('tbl_foto_produk', array('tbl_produk_id'=>$id))->result_array();
						$estimasi = $this->db->get_where('cl_provinsi', array('kode_prov'=>$zona_pilihan['kode_provinsi']))->row_array();
						$harga_buku = number_format($data_buku['harga_zona_'.$zona_pilihan['zona_pilihan']],0,",",".");
						
						for($i = 1; $i <= 5; $i++){
							$data_buku['harga_zona_'.$i] = "Rp. ".number_format($data_buku['harga_zona_'.$i],0,",",".");
							$data_buku['harga_pemerintah_zona_'.$i] = "Rp. ".number_format($data_buku['harga_pemerintah_zona_'.$i],0,",",".");
						}
						foreach($data_fotobuku as $k=>$v){
							if($data_fotobuku[$k]['foto_produk'] != null){
								$data_fotobuku[$k]['foto_produk'] = $this->host."__repository/produk/".$v['foto_produk'];
							}
						}
						
						//print_r($data_buku);exit;
						
						$this->nsmarty->assign('zona', $zona_pilihan['zona_pilihan']);
						$this->nsmarty->assign('estimasi', $estimasi['estimasi_lama_pengiriman']);
						$this->nsmarty->assign('provinsi', $zona_pilihan['provinsi']);
						$this->nsmarty->assign('harga_buku', $harga_buku);
						$this->nsmarty->assign('data_buku', $data_buku);
						$this->nsmarty->assign('data_fotobuku', $data_fotobuku);
					break;
					case "zona_pengiriman":
						$zona = $this->input->post('znpn');
						if($zona == ''){
							echo "Pilih Zona Pengiriman";
							exit;
						}
						
						$id = $this->input->post('iix');
						$data_harga = $this->mfrontend->getdata('zona_pengiriman', 'row_array', $id, $zona);
						$word = "Rp. ".number_format($data_harga['harga_zona_'.$zona],0,",",".");
						
						echo $word;
						exit;
					break;
					case "keranjangnya":
						$temp = "frontend/modul/keranjangbelanja-page.html";
						$data_cart = $this->cart->contents();
						foreach($data_cart as $key => $v){
							$datafoto = $this->db->get_where('tbl_foto_produk', array('tbl_produk_id'=>$v['id']) )->row_array();
							if($datafoto['foto_produk'] != null){
								$data_cart[$key]['foto_produk'] = $this->host."__repository/produk/".$datafoto['foto_produk'];
							}else{
								$data_cart[$key]['foto_produk'] = $this->host."__repository/no-image.jpeg";
							}
							$data_cart[$key]['price'] = number_format($v['price'],0,",",".");
							$data_cart[$key]['subtotal'] = number_format($v['subtotal'],0,",",".");
						}
						$this->nsmarty->assign('data_cart', $data_cart);
					break;
					case "update_keranjang":
					case "hapusitem_keranjang":
						$temp = "frontend/modul/keranjangbelanjaupdate-page.html";						
						$ck = $this->input->post('ck');
						if($p1 == "update_keranjang"){
							$qty = $this->input->post('qt');
							$rowid = $this->input->post('rws');					
							$this->keranjang_belanja('update', $qty, $rowid);
						}elseif($p1 == "hapusitem_keranjang"){
							$rowid = $this->input->post('rws');
							$this->keranjang_belanja('delete', $rowid);
						}
						
						$data_cart = $this->cart->contents();
						foreach($data_cart as $key => $v){
							$datafoto = $this->db->get_where('tbl_foto_produk', array('tbl_produk_id'=>$v['id']) )->row_array();
							if($datafoto['foto_produk'] != null){
								$data_cart[$key]['foto_produk'] = $this->host."__repository/produk/".$datafoto['foto_produk'];
							}else{
								$data_cart[$key]['foto_produk'] = $this->host."__repository/no-image.jpeg";
							}						
							$data_cart[$key]['price'] = number_format($v['price'],0,",",".");
							$data_cart[$key]['subtotal'] = number_format($v['subtotal'],0,",",".");
						}
						$this->nsmarty->assign('ck', $ck);
						$this->nsmarty->assign('data_cart', $data_cart);
					break;
					case "total_item";
						$data_cart = $this->cart->contents();
						$jumlah_item = count($data_cart);
						echo $jumlah_item;
						exit;
					break;
					case "checkout_belanja":
						$temp = "frontend/modul/checkout-page.html";
						$data_cart = $this->cart->contents();
						foreach($data_cart as $key => $v){
							$datafoto = $this->db->get_where('tbl_foto_produk', array('tbl_produk_id'=>$v['id']) )->row_array();
							if($datafoto['foto_produk'] != null){
								$data_cart[$key]['foto_produk'] = $this->host."__repository/produk/".$datafoto['foto_produk'];
							}else{
								$data_cart[$key]['foto_produk'] = $this->host."__repository/no-image.jpeg";
							}						
							$data_cart[$key]['price'] = number_format($v['price'],0,",",".");
							$data_cart[$key]['subtotal'] = number_format($v['subtotal'],0,",",".");
						}
						$this->nsmarty->assign('combo_jasa_pengiriman', $this->lib->fillcombo('cl_jasa_pengiriman', 'return') );
						$this->nsmarty->assign('combo_metode_pembayaran', $this->lib->fillcombo('cl_metode_pembayaran', 'return') );						
						$this->nsmarty->assign('data_cart', $data_cart);
					break;
					case "form_isian_checkout":
						$temp = "frontend/modul/isian_checkout-page.html";
						$type_form = trim($this->input->post('tpefrm'));
						
						if($type_form == 'skull'){
							$npsn = $this->input->post('npp');
							$cek_data = $this->db->get_where('tbl_registrasi', array('npsn'=>$npsn, 'jenis_pembeli'=>'SEKOLAH') )->row_array();
						}elseif($type_form == 'umu'){
							$email = $this->input->post('em');
							$cek_data = $this->db->get_where('tbl_registrasi', array('email'=>$email, 'jenis_pembeli'=>'UMUM') )->row_array();
						}
						
						if($cek_data){
							$this->nsmarty->assign('data', $cek_data);
							$this->nsmarty->assign('status_data', "ADA");
						}
						$this->nsmarty->assign('combo_prov', $this->lib->fillcombo('cl_provinsi', 'return', (isset($cek_data) ? $cek_data['cl_provinsi_kode'] : $zona_pilihan['kode_provinsi'] ) ));
						$this->nsmarty->assign('combo_kab', $this->lib->fillcombo('cl_kab_kota', 'return', (isset($cek_data) ? $cek_data['cl_kab_kota_kode'] : "" ), (isset($cek_data) ? $cek_data['cl_provinsi_kode'] : $zona_pilihan['kode_provinsi'] ) ));
						$this->nsmarty->assign('combo_kec', $this->lib->fillcombo('cl_kecamatan', 'return', (isset($cek_data) ? $cek_data['cl_kecamatan_kode'] : "" ), (isset($cek_data) ? $cek_data['cl_kab_kota_kode'] : "" ) ));						
						$this->nsmarty->assign('type_form', $type_form);
					break;
					case "combo_kab_kota":
						$v2 = $this->input->post('v2');
						echo $this->lib->fillcombo('cl_kab_kota', 'return', '', $v2);
						exit;
					break;
					case "combo_kecamatan":
						$v2 = $this->input->post('v2');
						echo $this->lib->fillcombo('cl_kecamatan', 'return', '', $v2);
						exit;
					break;
					case "finish_semuanya":
						$temp = "frontend/modul/finish-page.html";
					break;
					
					case "konfirmasi_pemb":
						$temp = "frontend/modul/konfirmasi-page.html";
					break;
					case "konfrom":
						$temp = "frontend/modul/konfirmasiform-page.html";
						$invno = $this->input->post('inv');
						$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $invno);
						if($data_invoice){
							$data_customer = $this->db->get_where('tbl_registrasi', array('id'=>$data_invoice['tbl_registrasi_id']))->row_array();
							$this->nsmarty->assign('data_customer', $data_customer);
							if($data_invoice['status'] == 'F'){
								$data_konfirmasi = $this->db->get_where('tbl_konfirmasi', array('tbl_h_pemesanan_id'=>$data_invoice['id']))->row_array();
								$data_konfirmasi['total_pembayaran'] = number_format($data_konfirmasi['total_pembayaran'],0,",",".");
								$this->nsmarty->assign('data_konfirmasi', $data_konfirmasi);
							}
							$data_invoice['sub_total'] = number_format($data_invoice['sub_total'],0,",",".");
							$data_invoice['pajak'] = number_format($data_invoice['pajak'],0,",",".");
							$data_invoice['grand_total'] = number_format($data_invoice['grand_total'],0,",",".");
						}
						$this->nsmarty->assign('data_inv', $data_invoice);
					break;
					
					case "lacakpesan":
						$temp = "frontend/modul/lacakpesan-page.html";
					break;
					case "lacakform":
						$temp = "frontend/modul/lacakpesananform-page.html";
						$invno = $this->input->post('inv');
						$data_tracking = $this->mfrontend->getdata('tracking_pesanan', 'row_array', $invno);
						$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $invno);
						if($data_invoice){
							$data_customer = $this->db->get_where('tbl_registrasi', array('id'=>$data_invoice['tbl_registrasi_id']))->row_array();
							$this->nsmarty->assign('data_customer', $data_customer);
							
							$data_invoice['sub_total'] = number_format($data_invoice['sub_total'],0,",",".");
							$data_invoice['pajak'] = number_format($data_invoice['pajak'],0,",",".");
							$data_invoice['grand_total'] = number_format($data_invoice['grand_total'],0,",",".");
						}
						
						$this->nsmarty->assign('data_inv', $data_invoice);
						$this->nsmarty->assign('data_tracking', $data_tracking);
					break;
					
					case "pesananriwayat":
						$temp = "frontend/modul/riwayat-page.html";
					break;
					case "formriwayat":
						$temp = "frontend/modul/formriwayat-page.html";
						$typ_cust = $this->input->post('typ');
						if($typ_cust == 'skull'){
							$npsn = $this->input->post('np');
							$datacust = $this->mfrontend->getdata('datacustomer', 'row_array', $npsn, 'SEKOLAH', 'riwayat');
						}elseif($typ_cust == 'umu'){
							$email = $this->input->post('em');
							$datacust = $this->mfrontend->getdata('datacustomer', 'row_array', $email, 'UMUM', 'riwayat');
						}
						
						if($datacust){
							$data_pesanan = $this->mfrontend->getdata('riwayat_pesanan', 'result_array', $datacust['id']);
							foreach($data_pesanan as $k=>$v){
								$data_pesanan[$k]['sub_total'] = number_format($v['sub_total'],0,",",".");
								$data_pesanan[$k]['pajak'] = number_format($v['pajak'],0,",",".");
								$data_pesanan[$k]['grand_total'] = number_format($v['grand_total'],0,",",".");
							}
							$this->nsmarty->assign('datapesanan', $data_pesanan);
							$this->nsmarty->assign('datacust', $datacust);
						}
					break;
					
					case "laykomplainbro":
						$temp = "frontend/modul/komplainnya-page.html";
					break;
					case "belanjanyacara":
						$temp = "frontend/modul/carabelonjo-page.html";
					break;
					
					case "pembatalanpesan":
						$temp = "frontend/modul/pembpesan-page.html";
					break;
					case "formpembatalancuy":
						$invno = $this->input->post('inv');
						$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $invno);
						if($data_invoice){
							if($data_invoice['status'] == 'F'){
								$array_page = array(
									'loadbalancedt' => md5('Ymd'),
									'loadbalancetm' => md5('H:i:s'),
									'loadtmr' => md5('YmdHis'),
									'page' => "<center>Pesanan Sudah Diproses, Tidak Bisa Dibatalkan</center>"
								);
								
								echo json_encode($array_page);
								exit;
							}else{
								$temp = "frontend/modul/formpembatalancuy-page.html";
								$kodepemb = $this->lib->randomString(8, 'angkahuruf');
								$this->db->update('tbl_h_pemesanan', array('kode_pembatalan'=>$kodepemb) ,array('no_order'=>$invno));
								$this->lib->kirimemail('email_pembatalan', $data_invoice['email'], $kodepemb, $invno);
							}
						}else{
							$array_page = array(
								'loadbalancedt' => md5('Ymd'),
								'loadbalancetm' => md5('H:i:s'),
								'loadtmr' => md5('YmdHis'),
								'page' => "<center>Data Invoice Tidak Ditemukan</center>"
							);
							
							echo json_encode($array_page);
							exit;
						}
					break;
				}		
				
				if(isset($temp)){
					$template = $this->nsmarty->fetch($temp);
				}else{
					$template = $this->nsmarty->fetch("konstruksi.html");
				}
				
				//echo $template;exit;
				
				$array_page = array(
					'loadbalancedt' => md5('Ymd'),
					'loadbalancetm' => md5('H:i:s'),
					'loadtmr' => md5('YmdHis'),
					'page' => $template 
				);
				
				echo json_encode($array_page);
			break;
		}
	}
	
	function generatepdf($type){
		$this->load->library('mlpdf');	
		switch($type){
			case "bastnya":
				$inv = $this->input->post('invo');
				if(!$inv){
					echo "tutup tab browser ini, dan generate kembali melalui tombol di web www.aldeaz.id";
					exit;
				}
				$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $inv);
				if($data_invoice){
					$no_bast = $data_invoice['no_order']."/ASP/BAST/".date('Y');
					$datacust = $this->mfrontend->getdata('datacustomer', 'row_array', $data_invoice['tbl_registrasi_id'], '', 'cetak_bast');
					$datakonfirmasi = $this->db->get_where('tbl_konfirmasi', array('tbl_h_pemesanan_id'=>$data_invoice['id']) )->row_array();
					$datadetailpesanan = $this->mfrontend->getdata('detail_pesanan', 'result_array', $data_invoice['id']);
					$totqty = 0;
					$tottotal = 0;
					foreach($datadetailpesanan as $k => $v){
						$totqty += $v['qty'];
						$tottotal += $v['subtotal'];
						
						$datadetailpesanan[$k]['harga'] = number_format($v['harga'],0,",",".");
						$datadetailpesanan[$k]['subtotal'] = number_format($v['subtotal'],0,",",".");
						$datadetailpesanan[$k]['nama_group'] = strtoupper(substr($v['nama_group'], 0,1));
					}
					
					$cekdatabast = $this->db->get_where('tbl_bast', array('tbl_konfirmasi_id'=>$datakonfirmasi['id']) )->row_array();
					if(!$cekdatabast){
						$array_insert_bast = array(
							'tbl_konfirmasi_id' => $datakonfirmasi['id'],
							'no_bast' => $no_bast,
							'create_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('tbl_bast', $array_insert_bast);
					}
					
					$tgl = $this->lib->konversi_tgl(date('Y-m-d'));
					
					$this->nsmarty->assign('datainvoice', $data_invoice);
					$this->nsmarty->assign('datakonfirmasi', $datakonfirmasi);
					$this->nsmarty->assign('datacust', $datacust);
					$this->nsmarty->assign('datadetailpesanan', $datadetailpesanan);
					$this->nsmarty->assign('totqty', $totqty);
					$this->nsmarty->assign('tgl', $tgl);
					$this->nsmarty->assign('no_bast', $no_bast);
					$this->nsmarty->assign('tottotal', number_format($tottotal,0,",","."));
				}
				
				$filename = "DOCBAST-";
				$htmlcontent = $this->nsmarty->fetch('frontend/modul/bast_pdf.html');
				
				$pdf = $this->mlpdf->load();
				$spdf = new mPDF('', 'A4', 0, '', 12.7, 12.7, 15, 20, 5, 2, 'P');
				$spdf->ignore_invalid_utf8 = true;
				$spdf->allow_charset_conversion = true;     // which is already true by default
				$spdf->charset_in = 'iso-8859-2';  // set content encoding to iso
				$spdf->SetDisplayMode('fullpage');		
				$spdf->SetProtection(array('print'));				
				$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
				//$spdf->Output($general_path.$subgroup."/".$io_number."/"."PARTIAL-".$partial_no."/LOA/".$filename.'.pdf', 'F'); // save to file because we can
				$spdf->Output($filename.'.pdf', 'I'); // view file
			break;
			case "kwitansinya":
				$this->load->helper('terbilang');
				$inv = $this->input->post('invo');
				if(!$inv){
					echo "tutup tab browser ini, dan generate kembali melalui tombol di web www.aldeaz.id";
					exit;
				}				
				$data_invoice = $this->mfrontend->getdata('header_pesanan', 'row_array', $inv);
				if($data_invoice){
					$no_kwitansi = $data_invoice['no_order']."/ASP/K/".date('Y');
					$datacust = $this->mfrontend->getdata('datacustomer', 'row_array', $data_invoice['tbl_registrasi_id'], '', 'cetak_bast');
					$jumlah = number_to_words($data_invoice['grand_total']);
					$datakonfirmasi = $this->db->get_where('tbl_konfirmasi', array('tbl_h_pemesanan_id'=>$data_invoice['id']) )->row_array();
					
					$cekdatakwitansi = $this->db->get_where('tbl_kwitansi', array('tbl_konfirmasi_id'=>$datakonfirmasi['id']) )->row_array();
					if(!$cekdatakwitansi){
						$array_insert_kwitansi = array(
							'tbl_konfirmasi_id' => $datakonfirmasi['id'],
							'no_kwitansi' => $no_kwitansi,
							'create_date' => date('Y-m-d H:i:s')
						);
						$this->db->insert('tbl_kwitansi', $array_insert_kwitansi);
					}
					
					$this->nsmarty->assign('datakonfirmasi', $datakonfirmasi);
					$this->nsmarty->assign('datainvoice', $data_invoice);
					$this->nsmarty->assign('datacust', $datacust);
					$this->nsmarty->assign('jumlah', $jumlah);
					$this->nsmarty->assign('no_kwitansi', $no_kwitansi);
					$this->nsmarty->assign('grandtotal', number_format($data_invoice['grand_total'],0,",",".") );
				}
				
				$filename = "DOCKWITANSI-";
				$htmlcontent = $this->nsmarty->fetch('frontend/modul/kwitansi_pdf.html');
				
				$pdf = $this->mlpdf->load();
				//$spdf = new mPDF('', 'A5', 0, '', 12.7, 12.7, 15, 20, 5, 2, 'L');
				$spdf = new mPDF('', 'A5-L', 0, '', 5, 5, 5, 5, 0, 0);
				$spdf->ignore_invalid_utf8 = true;
				$spdf->allow_charset_conversion = true;     // which is already true by default
				$spdf->charset_in = 'iso-8859-2';  // set content encoding to iso
				$spdf->SetDisplayMode('fullpage');		
				$spdf->SetProtection(array('print'));				
				$spdf->WriteHTML($htmlcontent); // write the HTML into the PDF
				//$spdf->Output($general_path.$subgroup."/".$io_number."/"."PARTIAL-".$partial_no."/LOA/".$filename.'.pdf', 'F'); // save to file because we can
				$spdf->Output($filename.'.pdf', 'I'); // view file
			break;
		}
	}
	
	function keranjang_belanja($type, $p1="", $p2=""){
		//$zona_pilihan = $this->session->userdata("zonaxtreme");
		switch($type){
			case "add":
				$id = $this->input->post('iipx');
				$zona = $this->input->post('zn');
				$qty = $this->input->post('yqt');
				$harga = $this->mfrontend->getdata('zona_pengiriman', 'row_array', $id, $zona);
				$data_buku = $this->db->get_where('tbl_produk', array('id'=>$id) )->row_array();
				$data_cart = $this->cart->contents();
				$flag = true;
				
				if($data_cart){
					foreach ($data_cart as $item) {
						if ($item['id'] == $id) {
							$qtyd = $item['qty'] + $qty;
							$data_update = array(
								'rowid' => $item['rowid'],
								'qty' => $qtyd,
								'price' => $harga['harga_zona_'.$zona],
							);
							echo $this->cart->update($data_update);
							$flag = false;
						}
					}
				}
				
				if($flag){
					$data_cart = array(
						'id' => $id,
						'qty' => $qty,
						'price' => $harga['harga_zona_'.$zona],
						'name' => $data_buku['judul_produk'],
						'options' => array('berat' => $data_buku['berat'])
					);
					echo $this->cart->insert($data_cart);
				}
			break;
			case "update":
				$data = array(
					'rowid' => $p2,
					'qty'   => $p1
				);
				$this->cart->update($data);
			break;
			case "delete":
				$this->cart->remove($p1);
			break;
			case "view":
				$kontent = $this->cart->contents();
				
				echo "<pre>";
				print_r($kontent);exit;
			break;
			case "destroy":
				$this->cart->destroy();
			break;
			
		}
	}
	
	function cruddata($type, $p1="", $p2=""){
		$post = array();
        foreach($_POST as $k=>$v){
			if($this->input->post($k)!=""){
				$post[$k] = $this->db->escape_str($this->input->post($k));
			}else{
				$post[$k] = null;
			}
		}
		
		if($type == 'session_zona'){
			$data_zona = $this->db->get_where('cl_provinsi', array('kode_prov'=>$post['kdprv']))->row_array();
			$sess = array();
			$sess['zona_pilihan'] = $data_zona['zona'];
			$sess['provinsi'] = $data_zona['provinsi'];
			$sess['kode_provinsi'] = $data_zona['kode_prov'];
			$this->session->set_userdata("zonaxtreme", $sess);
			echo 1;
		}else{
			if(isset($post['editstatus'])){$editstatus = $post['editstatus'];unset($post['editstatus']);}
			else $editstatus = null;
			
			echo $this->mfrontend->simpansavedata($p1, $post, $editstatus);
		}
	}
	
	function test(){			
		//$this->session->sess_destroy();
		//$sess = $this->session->userdata("zonaxtreme");
		//echo $sess['zona_pilihan'];
		//$this->session->sess_destroy();
		/*
		$data_cart = $this->cart->contents();
		$array_batch = array();
		foreach($data_cart as $s => $r){
			$array_insert = array(
				'tbl_h_pemesanan_id' => 1,
				'tbl_produk_id' => $r['id'],
				'harga' => $r['price'],
				'qty' => $r['qty'],
				'subtotal' => $r['subtotal'],
				'create_date' => date('Y-m-d H:i:s'),
			);
			array_push($array_batch, $array_insert);	
			
			$data_cart[$s]['price'] = number_format($r['price'],0,",",".");
			$data_cart[$s]['subtotal'] = number_format($r['subtotal'],0,",",".");
		}
				
		$array_email = array(
			'pemesan' => "SD XXXX",
			'no_order' => "ORD-87978SSS",
			'tot' => "999",
			'pajak' => "888",
			'grand_total' => "777",
		);
		
		$this->nsmarty->assign('data_cart', $data_cart);
		$this->nsmarty->assign('penunjang', $array_email);
		$this->nsmarty->display('frontend/modul/email_invoice.html');
		
		
		$data_cart = $this->cart->contents();
		$array_email = array(
			'pemesan' => "SD XXXX",
			'no_order' => "ORD-87978SSS",
			'tot' => "700.000.000",
			'pajak' => "70.000.000",
			'grand_total' => "770.000.000",
		);
		foreach($data_cart as $s => $r){
			$data_cart[$s]['price'] = number_format($r['price'],0,",",".");
			$data_cart[$s]['subtotal'] = number_format($r['subtotal'],0,",",".");
		}
		
		*/
		
		echo $this->lib->kirimemail('email_konfirmasi', "triwahyunugroho11@gmail.com");
		
		//$string = "T1 Diriku";
		//$isi = substr($string, 0, 20);
		//echo $isi;
	}
	
}
