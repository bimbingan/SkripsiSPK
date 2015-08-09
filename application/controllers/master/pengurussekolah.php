<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class pengurussekolah extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_pengurussekolah');
        $this->load->model('master/m_jabatanpengurus');
        $this->load->model('master/m_jenispengurus');
        $this->load->model('master/m_agama');
        $this->load->model('pengaturan/m_preference');
        $this->load->model('master/m_unitkerja');
        $this->load->model('master/m_mapel_group');
        $this->load->model('master/m_statuspengurus');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/pengurussekolah/list.html");

        // session
        $search = $this->tsession->userdata('search_pengurussekolah');
        $this->smarty->assign('search', $search);

        // params
        $guru_nik = !empty($search['guru_nik']) ? "%" . $search['guru_nik'] . "%" : "%";
        $guru_nama = !empty($search['guru_nama']) ? "%" . $search['guru_nama'] . "%" : "%";
        $jabatan_id = !empty($search['jabatan_id']) ? "%" . $search['jabatan_id'] . "%" : "%";
        $guru_st = (!empty($search['guru_st'])) ? $search['guru_st'] : "%";

        $params = array($guru_nik, $guru_nama, $jabatan_id, $guru_st);

        $config['base_url'] = site_url("master/pengurussekolah/index/");
        $config['total_rows'] = $this->m_pengurussekolah->get_total_pengurussekolah($params);

        $config['uri_segment'] = 4;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();

        // pagination attribute
        $start = $this->uri->segment(4, 0) + 1;
        $end = $this->uri->segment(4, 0) + $config['per_page'];
        $end = (($end > $config['total_rows']) ? $config['total_rows'] : $end);
        $pagination['start'] = ($config['total_rows'] == 0) ? 0 : $start;
        $pagination['end'] = $end;
        $pagination['total'] = $config['total_rows'];

        // pagination assign value
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);

        // /* end of pagination ---------------------- */
        // get list data
        $params = array($guru_nik, $guru_nama, $jabatan_id, $guru_st, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_pengurussekolah->get_list_pengurussekolah($params));
        // jabatan
        $this->smarty->assign('rs_jabatan', $this->m_jabatanpengurus->get_all_jabatanpengurus());
        // jenis
        $this->smarty->assign('rs_jenis', $this->m_jenispengurus->get_all_jenispengurus());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pencarian
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "guru_nik" => $this->input->post('guru_nik'),
                "guru_nama" => $this->input->post('guru_nama'),
                "jabatan_id" => $this->input->post('jabatan_id'),
                "guru_st" => $this->input->post('guru_st')
            );

            // set
            $this->tsession->set_userdata('search_pengurussekolah', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_pengurussekolah');
        }
        //--
        redirect('master/pengurussekolah');
    }

    // form tambah jabatan pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/pengurussekolah/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // jabatan
        $this->smarty->assign('rs_jabatan', $this->m_jabatanpengurus->get_all_jabatanpengurus());
        // tingkat pendidikan
        $this->smarty->assign('rs_tingkat', $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'pendidikan')));
        // unit kerja
        $this->smarty->assign('rs_unit', $this->m_unitkerja->get_all_unitkerja());
        // agama
        $this->smarty->assign('rs_agama', $this->m_agama->get_all_agama());
        // jenis
        $this->smarty->assign('rs_jenis', $this->m_jenispengurus->get_all_jenispengurus());
        // mapel group
        $this->smarty->assign("rs_mapelgroup", $this->m_mapel_group->get_all_mapel_group());
        // status pengurus
        $this->smarty->assign("rs_statuspengurus", $this->m_statuspengurus->get_all_statuspengurus());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah sekolah
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('guru_nik', 'NIK', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('guru_nama', 'Nama', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('tempat_lahir', 'Tempat Lahir', 'trim');
        $this->tnotification->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim');
        $this->tnotification->set_rules('alamat_asal', 'Alamat Asal', 'trim');
        $this->tnotification->set_rules('alamat_tinggal', 'Alamat Tinggal', 'trim');
        $this->tnotification->set_rules('guru_notelp', 'No Telp.', 'trim');
        $this->tnotification->set_rules('guru_st', 'Status', 'trim|required');
        $this->tnotification->set_rules('jenis_id', 'Jenis Pengurus', 'trim');
        $this->tnotification->set_rules('agama_cd', 'Agama', 'trim|maxlength[1]');
        $this->tnotification->set_rules('nikah_st', 'Status Pernikahan', 'trim|maxlength[100]');
        
        // Data Administrasi
        $this->tnotification->set_rules('no_sk_pns', 'No. SK PNS', 'trim|maxlength[100]');
        $this->tnotification->set_rules('tgl_sk_pns', 'Tanggal SK PNS', 'trim');
        $this->tnotification->set_rules('tgl_pensiun', 'Tanggal Pensiunn', 'trim');
        $this->tnotification->set_rules('umur_pensiun', 'Umur Pensiun', 'trim');
        $this->tnotification->set_rules('nomor_askes', 'Nomor Askes', 'trim|maxlength[150]');
        $this->tnotification->set_rules('nomor_taspen', 'Nomor Taspen', 'trim|maxlength[150]');
        $this->tnotification->set_rules('tmt_cpns', 'Tamat CPNS', 'trim');
        $this->tnotification->set_rules('tmt_pns', 'Tamat PNS', 'trim');
        $this->tnotification->set_rules('tingkat_pendidikan', 'Tamat PNS', 'trim|maxlength[50]');
        $this->tnotification->set_rules('ket_pendidikan', 'Keterangan Pendidikan', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ket_pendidikan', 'Keterangan Pendidikan', 'trim|maxlength[100]');
        $this->tnotification->set_rules('unit_id', 'Unit Kerja', 'trim|number');
        $this->tnotification->set_rules('unit_id', 'Unit Kerja', 'trim|number');
        $this->tnotification->set_rules('kontrak_mulai', 'Tanggal Mulai Kontrak', 'trim');
        $this->tnotification->set_rules('kontrak_berakhir', 'Tanggal Kontrak Berakhir', 'trim');
        $this->tnotification->set_rules('kartu_pegawai', 'Kartu Pegawai', 'trim');
        $this->tnotification->set_rules('mapelgroup_id', 'Kelompok Mapel', 'trim');
        $this->tnotification->set_rules('gender', 'Jenis Kelamin', 'trim|required|maxlength[1]');
		        

        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'guru_nik' 				=> $this->input->post('guru_nik'),
                'guru_nama' 			=> $this->input->post('guru_nama'),
                'tempat_lahir' 			=> $this->input->post('tempat_lahir'),
                'tanggal_lahir' 		=> $this->input->post('tanggal_lahir'),
                'alamat_asal' 			=> $this->input->post('alamat_asal'),
                'alamat_tinggal' 		=> $this->input->post('alamat_tinggal'),
                'guru_notelp' 			=> $this->input->post('guru_notelp'),
                'guru_st' 				=> $this->input->post('guru_st'),
                'jenis_id' 				=> $this->input->post('jenis_id'),
                'agama_cd' 				=> $this->input->post('agama_cd'),
                'nikah_st' 				=> $this->input->post('nikah_st'),
                'no_sk_pns' 			=> $this->input->post('no_sk_pns'),
                'tgl_sk_pns' 			=> $this->input->post('tgl_sk_pns'),
                'tgl_pensiun' 			=> $this->input->post('tgl_pensiun'),
                'umur_pensiun' 			=> $this->input->post('umur_pensiun'),
                'nomor_askes' 			=> $this->input->post('nomor_askes'),
                'nomor_taspen' 			=> $this->input->post('nomor_taspen'),
                'tmt_cpns' 				=> $this->input->post('tmt_cpns'),
                'tmt_pns' 				=> $this->input->post('tmt_pns'),
                'tingkat_pendidikan' 	=> $this->input->post('tingkat_pendidikan'),
                'ket_pendidikan' 		=> $this->input->post('ket_pendidikan'),
                'unit_id' 				=> $this->input->post('unit_id'),
                'gender' 				=> $this->input->post('gender'),
                'kontrak_mulai' 		=> $this->input->post('kontrak_mulai'),
                'kontrak_berakhir' 		=> $this->input->post('kontrak_berakhir'),
                'mapelgroup_id' 		=> $this->input->post('mapelgroup_id'),
                'status_id' 			=> $this->input->post('status_id'),
                'mdb' 					=> $this->com_user['user_id'],
                'mdd' 					=> date('Y-m-d')
            );
            // insert
            if ($this->m_pengurussekolah->insert_pengurussekolah($params)) {
            	$guru_id = $this->db->insert_id();

            	// upload foto
            	if (!empty($_FILES['foto']['tmp_name'])) {
	                // load
            		$this->load->library('tupload');
	                // delete
            		$filepath = 'resource/pengurus/foto/' . "FOTO" . date('YmdHisu');
            		if (is_file($filepath)) {
            			unlink($filepath);
            		}
	                // upload config
            		$config['upload_path'] = 'resource/pengurus/foto';
            		$config['allowed_types'] = 'gif|jpg|png';
            		$config['file_name'] = "FOTO" . date('YmdHisu');
            		$this->tupload->initialize($config);
	                // process upload images
            		if ($this->tupload->do_upload_image('foto', false, 160)) {
            			$data = $this->tupload->data();
            			$params = array(
            				'foto' => $data['file_name']
            				);
            			$where = array(
            				'guru_id' => $guru_id
            				);
            			$this->m_pengurussekolah->update_pengurussekolah($params, $where);
            		} else {
	                    // jika gagal
            			$this->tnotification->set_error_message($this->tupload->display_errors());
            		}
            	}

            	// upload kartu pegawai
            	if (!empty($_FILES['kartu_pegawai']['tmp_name'])) {
	                // load
            		$this->load->library('tupload');
	                // delete
            		$filepath = 'resource/pengurus/kartu_pegawai/' . "KARTU" . date('YmdHisu');
            		if (is_file($filepath)) {
            			unlink($filepath);
            		}
	                // upload config
            		$config['upload_path'] = 'resource/pengurus/kartu_pegawai';
            		$config['allowed_types'] = 'gif|jpg|png';
            		$config['file_name'] = "KARTU" . date('YmdHisu');
            		$this->tupload->initialize($config);
	                // process upload images
            		if ($this->tupload->do_upload_image('kartu_pegawai', false, 160)) {
            			$data = $this->tupload->data();
            			$params = array(
            				'kartu_pegawai' => $data['file_name']
            				);
            			$where = array(
            				'guru_id' => $guru_id
            				);
            			$this->m_pengurussekolah->update_pengurussekolah($params, $where);
            		} else {
	                    // jika gagal
            			$this->tnotification->set_error_message($this->tupload->display_errors());
            		}
            	}
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/pengurussekolah/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_pengurussekolah->get_pengurussekolah_by_nik($param);

        // set template content
        $this->smarty->assign("template_content", "master/pengurussekolah/edit.html");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // send data
        $this->smarty->assign("result", $result);
        // jabatan
        $this->smarty->assign('rs_jabatan', $this->m_jabatanpengurus->get_all_jabatanpengurus());
        // tingkat pendidikan
        $this->smarty->assign('rs_tingkat', $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'pendidikan')));
        // unit kerja
        $this->smarty->assign('rs_unit', $this->m_unitkerja->get_all_unitkerja());
        // agama
        $this->smarty->assign('rs_agama', $this->m_agama->get_all_agama());
        // jenis
        $this->smarty->assign('rs_jenis', $this->m_jenispengurus->get_all_jenispengurus());
        // mapel group
        $this->smarty->assign("rs_mapelgroup", $this->m_mapel_group->get_all_mapel_group());
        // status pengurus
        $this->smarty->assign("rs_statuspengurus", $this->m_statuspengurus->get_all_statuspengurus());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    function process_edit() {
        // set page rules
        $this->_set_page_rule("U");
       	// cek input
        $this->tnotification->set_rules('guru_nik', 'NIK', 'trim|required|max_length[50]');
        $this->tnotification->set_rules('guru_nama', 'Nama', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('tempat_lahir', 'Tempat Lahir', 'trim');
        $this->tnotification->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim');
        $this->tnotification->set_rules('alamat_asal', 'Alamat Asal', 'trim');
        $this->tnotification->set_rules('alamat_tinggal', 'Alamat Tinggal', 'trim');
        $this->tnotification->set_rules('guru_notelp', 'No Telp.', 'trim');
        $this->tnotification->set_rules('guru_st', 'Status', 'trim|required');
        $this->tnotification->set_rules('jenis_id', 'Jenis Pengurus', 'trim');
        $this->tnotification->set_rules('agama_cd', 'Agama', 'trim|maxlength[1]');
        $this->tnotification->set_rules('nikah_st', 'Status Pernikahan', 'trim|maxlength[100]');
        
        // Data Administrasi
        $this->tnotification->set_rules('no_sk_pns', 'No. SK PNS', 'trim|maxlength[100]');
        $this->tnotification->set_rules('tgl_sk_pns', 'Tanggal SK PNS', 'trim');
        $this->tnotification->set_rules('tgl_pensiun', 'Tanggal Pensiunn', 'trim');
        $this->tnotification->set_rules('umur_pensiun', 'Umur Pensiun', 'trim');
        $this->tnotification->set_rules('nomor_askes', 'Nomor Askes', 'trim|maxlength[150]');
        $this->tnotification->set_rules('nomor_taspen', 'Nomor Taspen', 'trim|maxlength[150]');
        $this->tnotification->set_rules('tmt_cpns', 'Tamat CPNS', 'trim');
        $this->tnotification->set_rules('tmt_pns', 'Tamat PNS', 'trim');
        $this->tnotification->set_rules('tingkat_pendidikan', 'Tamat PNS', 'trim|maxlength[50]');
        $this->tnotification->set_rules('ket_pendidikan', 'Keterangan Pendidikan', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ket_pendidikan', 'Keterangan Pendidikan', 'trim|maxlength[100]');
        $this->tnotification->set_rules('unit_id', 'Unit Kerja', 'trim|number');
        $this->tnotification->set_rules('unit_id', 'Unit Kerja', 'trim|number');
        $this->tnotification->set_rules('kontrak_mulai', 'Tanggal Mulai Kontrak', 'trim');
        $this->tnotification->set_rules('kontrak_berakhir', 'Tanggal Kontrak Berakhir', 'trim');
        $this->tnotification->set_rules('kartu_pegawai', 'Kartu Pegawai', 'trim');
        $this->tnotification->set_rules('mapelgroup_id', 'Kelompok Mapel', 'trim');
        $this->tnotification->set_rules('gender', 'Jenis Kelamin', 'trim|required|maxlength[1]');


        // process
        if ($this->tnotification->run() !== FALSE) {
        	$params = array(
        		'guru_nik' 				=> $this->input->post('guru_nik'),
        		'guru_nama' 			=> $this->input->post('guru_nama'),
        		'tempat_lahir' 			=> $this->input->post('tempat_lahir'),
        		'tanggal_lahir' 		=> $this->input->post('tanggal_lahir'),
        		'alamat_asal' 			=> $this->input->post('alamat_asal'),
        		'alamat_tinggal' 		=> $this->input->post('alamat_tinggal'),
        		'guru_notelp' 			=> $this->input->post('guru_notelp'),
        		'guru_st' 				=> $this->input->post('guru_st'),
        		'jenis_id' 				=> $this->input->post('jenis_id'),
        		'agama_cd' 				=> $this->input->post('agama_cd'),
        		'nikah_st' 				=> $this->input->post('nikah_st'),
        		'no_sk_pns' 			=> $this->input->post('no_sk_pns'),
        		'tgl_sk_pns' 			=> $this->input->post('tgl_sk_pns'),
        		'tgl_pensiun' 			=> $this->input->post('tgl_pensiun'),
        		'umur_pensiun' 			=> $this->input->post('umur_pensiun'),
        		'nomor_askes' 			=> $this->input->post('nomor_askes'),
        		'nomor_taspen' 			=> $this->input->post('nomor_taspen'),
        		'tmt_cpns' 				=> $this->input->post('tmt_cpns'),
        		'tmt_pns' 				=> $this->input->post('tmt_pns'),
        		'tingkat_pendidikan' 	=> $this->input->post('tingkat_pendidikan'),
        		'ket_pendidikan' 		=> $this->input->post('ket_pendidikan'),
        		'unit_id' 				=> $this->input->post('unit_id'),
        		'gender' 				=> $this->input->post('gender'),
        		'kontrak_mulai' 		=> $this->input->post('kontrak_mulai'),
        		'kontrak_berakhir' 		=> $this->input->post('kontrak_berakhir'),
        		'mapelgroup_id' 		=> $this->input->post('mapelgroup_id'),
        		'status_id' 			=> $this->input->post('status_id'),
        		'mdb' 					=> $this->com_user['user_id'],
        		'mdd' 					=> date('Y-m-d')
            );
            // update
            if ($this->m_pengurussekolah->update_pengurussekolah($params)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/pengurussekolah/edit/" . $this->input->post('guru_nik'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_pengurussekolah->delete_pengurussekolah($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("master/pengurussekolah/");
    }

}
