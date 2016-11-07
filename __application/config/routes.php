<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'frontend';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['backoffice'] = 'backend';
$route['backoffice-masuk'] = 'login';
$route['backoffice-keluar'] = 'login/logout';
$route['backoffice-grid/(:any)'] = 'backend/get_grid/$1';
$route['backoffice-form/(:any)'] = 'backend/get_form/$1';
$route['backoffice-combo'] = 'backend/get_combo';
$route['backoffice-simpan/(:any)/(:any)'] = 'backend/simpandata/$1/$2';
$route['backoffice-delete'] = 'backend/simpandata';
$route['backoffice-upload'] = 'backend/upload';
$route['backoffice-hapusFile'] = 'backend/hapus_file';
$route['backoffice-GetDetil'] = 'backend/get_konten';
$route['backoffice-Cetak'] = 'backend/cetak';
$route['backoffice-SetFlag'] = 'backend/set_flag';
$route['backoffice-Dashboard'] = 'backend/get_konten';
$route['backoffice-GetDataChart'] = 'backend/get_chart';
$route['backoffice-laporan/(:any)'] = 'backend/get_form/$1';


/* Routes Front End Routes */
$route['beranda'] = 'frontend/getdisplay/main_page/beranda';
$route['loading-beranda'] = 'frontend/getdisplay/loading_page/beranda';

$route['tracking'] = 'frontend/getdisplay/main_page/tracking';
$route['loading-tracking'] = 'frontend/getdisplay/loading_page/tracking';

$route['histori'] = 'frontend/getdisplay/main_page/histori';
$route['loading-histori'] = 'frontend/getdisplay/loading_page/histori';

$route['kontak'] = 'frontend/getdisplay/main_page/kontak';
$route['loading-kontak'] = 'frontend/getdisplay/loading_page/kontak';

$route['katalog'] = 'frontend/getdisplay/main_page/katalog';
$route['loading-katalog'] = 'frontend/getdisplay/loading_page/katalog';
$route['paginationdata'] = 'frontend/getdisplay/loading_page/datapaging';
$route['filtering-data'] = 'frontend/getdisplay/loading_page/filterdt';
$route['cari-data'] = 'frontend/getdisplay/loading_page/crdt';

$route['kategori/(:any)'] = 'frontend/getdisplay/main_page/kategori/$1';
$route['loading-kategori/(:any)'] = 'frontend/getdisplay/loading_page/kategori/$1';

$route['detil-produk'] = 'frontend/getdisplay/loading_page/detail_produk';
$route['harga-per-zona'] = 'frontend/getdisplay/loading_page/zona_pengiriman';

$route['session-zona'] = 'frontend/getdisplay/loading_page/combo_zona';
$route['pilih-zona'] = 'frontend/cruddata/session_zona';

$route['keranjang-belanja'] = 'frontend/getdisplay/loading_page/keranjangnya';
$route['banyak-belanja'] = 'frontend/getdisplay/loading_page/total_item';
$route['keranjang-belanja-masuk'] = 'frontend/keranjang_belanja/add';
$route['perbaharui-keranjang'] = 'frontend/getdisplay/loading_page/update_keranjang';
$route['hapus-keranjang'] = 'frontend/getdisplay/loading_page/hapusitem_keranjang';

$route['selesai-belanja'] = 'frontend/getdisplay/main_page/selesaibelanja';
$route['loading-selesaibelanja'] = 'frontend/getdisplay/loading_page/checkout_belanja';
$route['loading-formcheckout'] = 'frontend/getdisplay/loading_page/form_isian_checkout';
$route['combo-kab-kota'] = 'frontend/getdisplay/loading_page/combo_kab_kota';
$route['combo-kecamatan'] = 'frontend/getdisplay/loading_page/combo_kecamatan';
$route['submit-transaksi'] = 'frontend/cruddata/form/checkout';
$route['finish'] = 'frontend/getdisplay/loading_page/finish_semuanya';

$route['konfirmasi'] = 'frontend/getdisplay/main_page/konfirmasi';
$route['loading-konfirmasi'] = 'frontend/getdisplay/loading_page/konfirmasi_pemb';
$route['loading-formkonf'] = 'frontend/getdisplay/loading_page/konfrom';
$route['submit-konfirmasi'] = 'frontend/cruddata/form/konf';
$route['cetak-bast'] = 'frontend/generatepdf/bastnya';
$route['cetak-kwitansi'] = 'frontend/generatepdf/kwitansinya';

$route['lacakpesanan'] = 'frontend/getdisplay/main_page/lacakpesanan';
$route['loading-lacakpesanan'] = 'frontend/getdisplay/loading_page/lacakpesan';
$route['loading-lacakform'] = 'frontend/getdisplay/loading_page/lacakform';

$route['riwayatpesanan'] = 'frontend/getdisplay/main_page/riwayat';
$route['loading-riwayat'] = 'frontend/getdisplay/loading_page/pesananriwayat';
$route['loading-riwayatform'] = 'frontend/getdisplay/loading_page/formriwayat';

$route['komplain'] = 'frontend/getdisplay/main_page/komplain';
$route['loading-komplain'] = 'frontend/getdisplay/loading_page/laykomplainbro';
$route['submit-komplain'] = 'frontend/cruddata/form/komp';

$route['caraberbelanja'] = 'frontend/getdisplay/main_page/carabelanja';
$route['loading-carabelanja'] = 'frontend/getdisplay/loading_page/belanjanyacara';

$route['pembatalan'] = 'frontend/getdisplay/main_page/pembatalan';
$route['loading-pembatalan'] = 'frontend/getdisplay/loading_page/pembatalanpesan';
$route['loading-formpemb'] = 'frontend/getdisplay/loading_page/formpembatalancuy';
$route['submit-pembatalan'] = 'frontend/cruddata/form/pembss';






