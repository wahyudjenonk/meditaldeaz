<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/*
	Models Buatan Wahyu Jinggomen
*/

class Mfrontend extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->auth = unserialize(base64_decode($this->session->userdata('4ld33334zzzzzzt')));
	}
	
	function getdata($type="", $balikan="", $p1="", $p2="",$p3="",$p4=""){
		$where = " WHERE 1=1 ";
		$join = "";
		$select = "";
		$order = "";
		
		switch($type){
			case "data_buku":
			case "data_buku_tingkatan":
			case "data_buku_detail":
				if($type == 'data_buku'){
					$select .= " ,E.foto_produk "; 
					$where .= "";
					$join .= " LEFT JOIN (SELECT tbl_produk_id, foto_produk FROM tbl_foto_produk GROUP BY tbl_produk_id) E ON E.tbl_produk_id = A.id ";					
					$typefilter = $this->input->post('ty');
					$idfilter = $this->input->post('isd');
					$crfilter = $this->input->post('cr');
					
					if($typefilter){
						if($typefilter == 'tingk'){
							$where .= " AND A.cl_tingkatan_id = ".$idfilter." ";
						}elseif($typefilter == 'kate'){
							$where .= " AND A.cl_kategori_id = ".$idfilter." ";
						}
					}
					
					if($crfilter){
						$where .= " AND A.judul_produk like '%".$crfilter."%' ";
					}
					
					$order = "
						ORDER BY A.id ASC
						LIMIT $p2, $p3
					";
				}elseif($type == 'data_buku_tingkatan'){
					$where .= " AND C.id = '".$p1."' ";
				}elseif($type == 'data_buku_detail'){
					$where .= " AND A.id = '".$p1."' ";
				}
				
				$sql = "
					SELECT A.*, D.nama_kategori, C.tingkatan
						$select
					FROM tbl_produk A
					LEFT JOIN cl_tingkatan C ON C.id = A.cl_tingkatan_id
					LEFT JOIN cl_kategori D ON D.id = A.cl_kategori_id
					$join
					$where 
					$order
				";
				
				//echo $sql;exit;
			break;
			case 'hitung_data_filter':
				$typefilter = $this->input->post('ty');
				$idfilter = $this->input->post('isd');
				$crfilter = $this->input->post('cr');
				
				if($typefilter){
					if($typefilter == 'tingk'){
						$where .= " AND A.cl_tingkatan_id = ".$idfilter." ";
					}elseif($typefilter == 'kate'){
						$where .= " AND A.cl_kategori_id = ".$idfilter." ";
					}
				}
				
				if($crfilter){
					$where .= " AND A.judul_produk like '%".$crfilter."%' ";
				}
				
				$sql = "
					SELECT A.*
					FROM tbl_produk A
					$where 
				";
				
				$query = $this->db->query($sql);
				return $query->num_rows();
				exit;
			break;
			case "cl_tingkatan":
				$sql = "
					SELECT id, tingkatan
					FROM cl_tingkatan
				";
			break;
			case "zona_pengiriman":
				$sql = "
					SELECT harga_zona_".$p2."
					FROM tbl_produk
					WHERE id = '".$p1."'
				";
			break;
			case "header_pesanan":
				$sql = "
					SELECT A.*,
						B.jasa_pengiriman, C.metode_pembayaran, D.email
					FROM tbl_h_pemesanan A
					LEFT JOIN cl_jasa_pengiriman B ON B.id = A.cl_jasa_pengiriman_id
					LEFT JOIN cl_metode_pembayaran C ON C.id = A.cl_metode_pembayaran_id
					LEFT JOIN tbl_registrasi D ON D.id = A.tbl_registrasi_id
					WHERE A.no_order = '".$p1."'
				";
			break;
			case "detail_pesanan":
				$sql = "
					SELECT A.*,
						B.judul_produk
					FROM tbl_d_pemesanan A
					LEFT JOIN tbl_produk B ON B.id = A.tbl_produk_id
					WHERE A.tbl_h_pemesanan_id = '".$p1."'
				";
			break;
			case "getemail_cust":
				$sql = "
					SELECT B.email
					FROM tbl_h_pemesanan A 
					LEFT JOIN tbl_registrasi B ON B.id = A.tbl_registrasi_id
					WHERE A.no_order = '".$p1."'
				";
			break;
			
			case "tracking_pesanan":
				$sql = "
					SELECT CASE A.status 
							WHEN 'P' THEN 'BELUM DIBAYAR'
							WHEN 'F' THEN 'SUDAH DIBAYAR'
							END
						as status_pembayaran,
						CASE B.flag 
							WHEN 'P' THEN 'MENUNGGU VERIFIKASI'
							WHEN 'F' THEN 'PEMBAYARAN TERKONFIRMASI'
							END
						as status_konfirmasi,
						CASE C.status 
							WHEN 'PK' THEN 'PROSES PACKING'
							WHEN 'F' THEN 'DALAM PENGIRIMAN'
							END
						as status_tracking,
						C.no_resi
					FROM tbl_h_pemesanan A
					LEFT JOIN  tbl_konfirmasi B ON B.tbl_h_pemesanan_id = A.id 
					LEFT JOIN  tbl_tracking_pengiriman C ON C.tbl_h_pemesanan_id = A.id 
					WHERE A.no_order = '".$p1."'
				";
			break;
			
			case "riwayat_pesanan":
				$sql = "
					SELECT A.*, 
						CASE A.status 
							WHEN 'P' THEN 'BELUM DIBAYAR'
							WHEN 'F' THEN 'SUDAH DIBAYAR'
							END
						as status_pembayaran,
						CASE B.flag 
							WHEN 'P' THEN 'MENUNGGU VERIFIKASI'
							WHEN 'F' THEN 'PEMBAYARAN TERKONFIRMASI'
							END
						as status_konfirmasi,
						CASE C.status 
							WHEN 'P' THEN 'PROSES PACKING'
							WHEN 'F' THEN 'DALAM PENGIRIMAN'
							END
						as status_tracking,
						C.no_resi
					FROM tbl_h_pemesanan A
					LEFT JOIN tbl_konfirmasi B ON B.tbl_h_pemesanan_id = A.id 
					LEFT JOIN tbl_tracking_pengiriman C ON C.tbl_h_pemesanan_id = A.id 
					WHERE A.tbl_registrasi_id = '".$p1."'
				";
			break;
			case "datacustomer":
				if($p3 == 'riwayat'){
					if($p2 == 'SEKOLAH'){
						$where = " AND npsn = '".$p1."' ";
					}else{
						$where = " AND email = '".$p1."' ";
					}
					
					$where .= " AND jenis_pembeli = '".$p2."' ";
				}elseif($p3 == 'cetak_bast'){
					$where = " AND A.id = '".$p1."' ";
				}
				$sql = "
					SELECT A.*,
						B.provinsi, C.kab_kota, D.kecamatan
					FROM tbl_registrasi A
					LEFT JOIN cl_provinsi B ON B.kode_prov = A.cl_provinsi_kode
					LEFT JOIN cl_kab_kota C ON C.kode_kab_kota = A.cl_kab_kota_kode
					LEFT JOIN cl_kecamatan D ON D.kode_kecamatan = A.cl_kecamatan_kode
					WHERE 1=1 $where
				";
			break;
		}
		
		if($balikan == 'json'){
			return $this->lib->json_grid($sql);
		}elseif($balikan == 'row_array'){
			return $this->db->query($sql)->row_array();
		}elseif($balikan == 'result'){
			return $this->db->query($sql)->result();
		}elseif($balikan == 'result_array'){
			return $this->db->query($sql)->result_array();
		}
		
	}
	
	function get_combo($type="", $p1="", $p2=""){
		switch($type){
			case "cl_provinsi":
				$sql = "
					SELECT kode_prov as id, provinsi as txt
					FROM cl_provinsi
				";
			break;
			case "cl_kab_kota":
				$sql = "
					SELECT kode_kab_kota as id, kab_kota as txt
					FROM cl_kab_kota
					WHERE cl_provinsi_kode = '".$p2."'
				";
			break;
			case "cl_kecamatan":
				$sql = "
					SELECT kode_kecamatan as id, kecamatan as txt
					FROM cl_kecamatan
					WHERE cl_kab_kota_kode = '".$p2."'
				";
			break;
			case "cl_jasa_pengiriman":
				$sql = "
					SELECT id, jasa_pengiriman as txt
					FROM cl_jasa_pengiriman
				";
			break;
			case "cl_metode_pembayaran":
				$sql = "
					SELECT id, metode_pembayaran as txt
					FROM cl_metode_pembayaran
				";
			break;
			
		}
		
		return $this->db->query($sql)->result_array();
	}
	
	function simpansavedata($table,$data,$sts_crud){  //$sts_crud --> STATUS NYEE INSERT, UPDATE, DELETE
		$this->db->trans_begin();
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}
		
		switch($table){
			case "checkout":
				if($data['typ'] == 'skull'){
					if($data['ckdt'] == 'TIDAK ADA'){
						$data_registrasi = array(
							'jenis_pembeli' => 'SEKOLAH',
							'email' => $data['email'],
							'status' => 1,
							'npsn' => $data['npsn'],
							'nama_sekolah' => $data['nmseko'],
							'nip' => $data['nipkepsek'],
							'nama_kepala_sekolah' => $data['nmkepsek'],
							'nama_bendahara' => $data['nmbend'],
							'no_hp_kepsek' => $data['nohpkepsek'],
							'no_hp_bendahara' => $data['nohpbend'],
							'no_telp_sekolah' => $data['notelp'],
							'cl_provinsi_kode' => $data['prov'],
							'cl_kab_kota_kode' => $data['kab'],
							'cl_kecamatan_kode' => $data['kec'],
							'kode_pos' => $data['kdpos'],
							'alamat_pengiriman' => $data['almt'],
							'reg_date' => date('Y-m-d H:i:s')
						);
						$insert_registrasi = $this->db->insert('tbl_registrasi', $data_registrasi);
						if($insert_registrasi){
							$data_pembeli = $this->db->get_where('tbl_registrasi', array('npsn'=>$data['npsn'], 'jenis_pembeli'=>'SEKOLAH') )->row_array();
							$id = $data_pembeli['id'];
						}else{
							$id = null;
						}
					}elseif($data['ckdt'] == 'ADA'){
						$data_pembeli = $this->db->get_where('tbl_registrasi', array('npsn'=>$data['npsn'], 'jenis_pembeli'=>'SEKOLAH') )->row_array();
						$id = $data_pembeli['id'];
					}
					
				}elseif($data['typ'] == 'umu'){
					if($data['ckdt'] == 'TIDAK ADA'){
						$data_registrasi = array(
							'jenis_pembeli' => 'UMUM',
							'email' => $data['email'],
							'status' => 1,
							'nama_lengkap' => $data['nm_cust'],
							'no_hp_customer' => $data['nhp'],
							'no_telp_customer' => $data['ntlp'],
							'cl_provinsi_kode' => $data['prov'],
							'cl_kab_kota_kode' => $data['kab'],
							'cl_kecamatan_kode' => $data['kec'],
							'kode_pos' => $data['kdpos'],
							'alamat_pengiriman' => $data['pngrmn'],
							'reg_date' => date('Y-m-d H:i:s')
						);
						$insert_registrasi = $this->db->insert('tbl_registrasi', $data_registrasi);
						if($insert_registrasi){
							$data_pembeli = $this->db->get_where('tbl_registrasi', array('email'=>trim($data['email']), 'jenis_pembeli'=>'UMUM' ) )->row_array();
							$id = $data_pembeli['id'];
						}else{
							$id = null;
						}
					}elseif($data['ckdt'] == 'ADA'){
						$data_pembeli = $this->db->get_where('tbl_registrasi', array('email'=>trim($data['email']), 'jenis_pembeli'=>'UMUM' ) )->row_array();
						if($data_pembeli){
							$id = $data_pembeli['id'];
						}else{
							$id = null;
						}
					}
					
				}
				
				if($id != null){
					$data_cart = $this->cart->contents();
					$zona_pilihan = $this->session->userdata("zonaxtreme");
					$tot = 0;
					foreach($data_cart as $k => $v){
						$tot += $v['subtotal'];
					}
					
					$sql_maxord = "
						SELECT no_order as ordered_no
						FROM tbl_h_pemesanan
						WHERE id = (select max(id) from tbl_h_pemesanan)			
					";
					$maxord = $this->db->query($sql_maxord)->row_array();
					if($maxord['ordered_no'] != null){
						$order_urut 	= explode("-", $maxord['ordered_no']);
						$order_urutnya 	= ( $order_urut[1] + 1 ); 
						$acak_string 	= strtoupper($this->lib->randomString(4,"angkahuruf"));
						$acak_no_order 	= "ORD".$acak_string."-".$order_urutnya;
					}else{
						$order_urutnya = 100000;
						$acak_string 	= strtoupper($this->lib->randomString(4,"angkahuruf"));
						$acak_no_order 	= "ORD".$acak_string."-".$order_urutnya;
					}
					
					$pajak = 0 * $tot;
					$grand_total = ($tot + $pajak);
					$data_header_pesanan = array(
						'no_order' => $acak_no_order,
						'tgl_order' => date('Y-m-d H:i:s'),
						'cl_jasa_pengiriman_id' => $data['jasa_pengiriman'],
						'cl_metode_pembayaran_id' => $data['metode_pembayaran'],
						'tbl_registrasi_id' => $id,
						'sub_total' => $tot,
						'pajak' => $pajak,
						'grand_total' => $grand_total,
						'status' => "P",
						'create_date' => date('Y-m-d H:i:s'),
						'zona' => $zona_pilihan['zona_pilihan'],
					);
					$insert_header = $this->db->insert('tbl_h_pemesanan', $data_header_pesanan);
					if($insert_header){
						$cekdt_header = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$acak_no_order) )->row_array();
						$array_batch = array();
						foreach($data_cart as $s => $r){
							$array_insert = array(
								'tbl_h_pemesanan_id' => $cekdt_header['id'],
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
						$this->db->insert_batch('tbl_d_pemesanan', $array_batch);
						
						$array_email = array(
							'pemesan' => $data['nmseko'],
							'no_order' => $acak_no_order,
							'tot' => number_format($tot,0,",","."),
							'pajak' => number_format($pajak,0,",","."),
							'grand_total' => number_format($grand_total,0,",","."),
						);
						$this->lib->kirimemail('email_invoice', $data['email'], $data_cart, $array_email);
						
						$this->cart->destroy();
					}
				}else{
					
					
				}
			break;
			case "konf":
				$sts_crud = 'falseto';
				$data_inv = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$data['inv']) )->row_array();
				if($data_inv){
					$sql_maxkonf = "
						SELECT MAX(no_konfirmasi) as konfirmasi_no
						FROM tbl_konfirmasi
					";
					$maxkonf = $this->db->query($sql_maxkonf)->row_array();
					if($maxord['konfirmasi_no'] != null){
						$acak_no_konf = ($maxkonf['konfirmasi_no'] + 1); 
					}else{
						$acak_no_konf = 1;
					}
					
					$total_pembayaran = trim($data['jml_trf']);
					$total_pembayaran = str_replace(".", "", $total_pembayaran);
					$array_insert = array(
						'no_konfirmasi' => $acak_no_konf,
						'tgl_konfirmasi' => date('Y-m-d'),
						'tbl_h_pemesanan_id' => $data_inv['id'],
						'total_pembayaran' => (int)$total_pembayaran,
						'nama_bank_pengirim' => $data['bank_pengirim'],
						'atas_nama_pengirim' => $data['atas_nama'],
						'tanggal_transfer' => $data['tgl_trf'],
						'nama_bank_penerima' => $data['bank_tujuan'],
						'flag' => 'P',
						'create_date' => date('Y-m-d H:i:s'),
					);
					$insert = $this->db->insert('tbl_konfirmasi', $array_insert);
					if($insert){
						$this->db->update('tbl_h_pemesanan', array('status'=>'F'), array('no_order'=>$data['inv']) );
						
						$email = $this->getdata('getemail_cust', 'row_array', $data['inv']);
						$this->lib->kirimemail('email_konfirmasi', $email['email'], $data['inv']);
					}
				}
			break;
			case "komp":
				$table = 'tbl_komplain';
				$sts_crud = 'add';
				$datainv = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$data['noinv']) )->row_array();
				
				$data['tbl_h_pemesanan_id'] = $datainv['id'];
				$data['no_komplain'] = "KMP-".strtoupper($this->lib->randomString(4,"angkahuruf"));
				$data['komplain'] = $data['komp'];
				
				$cek_dir = "__repository/komplain/";
				if(!is_dir($cek_dir)) {
					mkdir($cek_dir, 0777);         
				}			
				
				if(!empty($_FILES['filepend']['name'])){					
					$upload_path = "./__repository/komplain/";
					$file = "FILEKOMPLAIN-".$data['no_komplain'];
					$filename =  $this->lib->uploadnong($upload_path, 'filepend', $file); //$file.'.'.$extension;
					$data['file_foto'] = $filename;
				}
				$data['create_date'] = date("Y-m-d H:i:s");
				
				unset($data['komp']);
				unset($data['noinv']);
			break;
			case "pembss":
				$cek_data = $this->db->get_where('tbl_h_pemesanan', array('no_order'=>$data['inv'], 'kode_pembatalan'=>$data['kdpemb']) )->row_array();
				if($cek_data){
					$arraypemb = array(
						"status" => 'C',
						'tanggal_pembatalan' => date('Y-m-d H:i:s'),
					);
					$this->db->update('tbl_h_pemesanan', $arraypemb, array('no_order'=>$data['inv']) );
				}else{
					return 0;
					exit;
				}
			break;
		}
		
		switch ($sts_crud){
			case "add":
				$this->db->insert($table,$data);
			break;
			case "edit":
				$this->db->update($table, $data, array('id' => $id) );
			break;
			case "delete":
				$this->db->delete($table, array('id' => $id));
			break;
		}
		
		if($this->db->trans_status() == false){
			$this->db->trans_rollback();
			return 0;
		} else{
			return $this->db->trans_commit();
		}
	
	}
	
}
