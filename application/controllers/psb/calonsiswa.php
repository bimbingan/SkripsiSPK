<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class calonsiswa extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('psb/m_calonsiswa');
        $this->load->model('master/m_psb');
        $this->load->model('master/m_sekolah');
        $this->load->model('master/m_provinsi');
        $this->load->model('master/m_kabupaten');
        $this->load->model('master/m_agama');
        $this->load->model('master/m_jalurpsb');
        $this->load->model('master/m_jurusan');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "psb/calonsiswa/list.html");

        // session
        $search = $this->tsession->userdata('search_calonsiswa');
        $this->smarty->assign('search', $search);

        // params
        $cs_no_pendaftaran = !empty($search['cs_no_pendaftaran']) ? "%" . $search['cs_no_pendaftaran'] . "%" : "%";
        $cs_nama = !empty($search['cs_nama']) ? "%" . $search['cs_nama'] . "%" : "%";
        $jalurpsb_id = !empty($search['jalurpsb_id']) ? "%" . $search['jalurpsb_id'] . "%" : "%";

        $params = array($cs_no_pendaftaran, $cs_nama, $jalurpsb_id);

        $config['base_url'] = site_url("psb/calonsiswa/index/");
        $config['total_rows'] = $this->m_calonsiswa->get_total_calonsiswa($params);

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
        $params = array($cs_no_pendaftaran, $cs_nama, $jalurpsb_id, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_calonsiswa->get_list_calonsiswa($params));
        $this->smarty->assign("rs_jalur", $this->m_jalurpsb->get_all_jalurpsb());

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
                "cs_no_pendaftaran" => $this->input->post('cs_no_pendaftaran'),
                "cs_nama" => $this->input->post('cs_nama'),
                "jalurpsb_id" => $this->input->post('jalurpsb_id')
            );

            // set
            $this->tsession->set_userdata('search_calonsiswa', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_calonsiswa');
        }
        //--
        redirect('psb/calonsiswa');
    }

    // form tambah jabatan pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "psb/calonsiswa/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // psb
        $this->smarty->assign("rs_psb", $this->m_psb->get_psb_group_by_tha());
        // sekolah
        $this->smarty->assign("rs_sekolah", $this->m_sekolah->get_all_sekolah());
        // provinsi
        $this->smarty->assign("rs_provinsi", $this->m_provinsi->get_all_provinsi());
        // kabupaten
        $this->smarty->assign("rs_kab", $this->m_kabupaten->get_all_kabupaten());
        // agama
        $this->smarty->assign("rs_agama", $this->m_agama->get_all_agama());
        // jurusan
        $this->smarty->assign("rs_jurusan", $this->m_jurusan->get_all_jurusan());

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
        // load model
        $this->load->model('psb/m_pembayaran');
        $this->load->model('pengaturan/m_preference');
        // cek input
        $this->tnotification->set_rules('cs_nis', 'Nomor Induk Siswa', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('psb_id', 'Tahun Ajaran PSB', 'trim|required');
        $this->tnotification->set_rules('jalurpsb_id', 'Jalur PSB', 'trim|required');
        $this->tnotification->set_rules('cs_tgl_daftar', 'Tanggal Pendaftaran', 'trim');
        $this->tnotification->set_rules('cs_nama', 'Nama Siswa', 'trim|required|max_length[250]');
        $this->tnotification->set_rules('cs_jk', 'Jenis Kelamin', 'trim|max_length[1]');
        $this->tnotification->set_rules('cs_kewarganegaraan', 'Kewarganegaraan', 'trim');
        $this->tnotification->set_rules('cs_negara', 'Negara Asal', 'trim|maxlength[100]');
        $this->tnotification->set_rules('cs_notelp', 'No. Telp Siswa', 'trim|maxlength[100]');
        $this->tnotification->set_rules('agama_cd', 'Agama Siswa', 'trim|required|maxlength[1]');
        $this->tnotification->set_rules('cs_alamat', 'Alamat Siswa', 'trim');
        $this->tnotification->set_rules('provinsi_id', 'Provinsi', 'trim');
        $this->tnotification->set_rules('kab_id', 'Kabupaten', 'trim');
        $this->tnotification->set_rules('sekolah_id', 'Asal Sekolah', 'trim');
        $this->tnotification->set_rules('cs_nilai_un', 'Nilai UN', 'trim|required|numeric');
        $this->tnotification->set_rules('cs_tahun_lulus', 'Tahun Lulus', 'trim|numeric|maxlength[4]');
        $this->tnotification->set_rules('cs_email', 'Email', 'trim|maxlength[200]');
        $this->tnotification->set_rules('jurusan_id', 'Jurusan', 'trim|maxlength[2]');
        // cek input ayah
        $this->tnotification->set_rules('ayah_nm', 'Nama Ayah', 'trim|max_length[200]');
        $this->tnotification->set_rules('ayah_tempatlahir', 'Tempat Lahir Ayah', 'trim|max_length[150]');
        $this->tnotification->set_rules('ayah_tgllahir', 'Tanggal Lahir Ayah', 'trim|max_length[150]');
        $this->tnotification->set_rules('ayah_kewarganegaraan', 'Kewarganegaraan Ayah', 'trim');
        $this->tnotification->set_rules('ayah_alamat', 'Alamat Ayah', 'trim');
        $this->tnotification->set_rules('ayah_pendidikan', 'Pendidikan Ayah', 'trim|maxlength[255]');
        $this->tnotification->set_rules('ayah_pekerjaan', 'Pekerjaan Ayah', 'trim|maxlength[200]');
        $this->tnotification->set_rules('ayah_notelp', 'No Telp. Ayah', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ayah_agama', 'Agama Ayah', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ayah_st', 'Status Ayah', 'trim');
        // cek input ibu
        $this->tnotification->set_rules('ibu_nm', 'Nama Ibu', 'trim|max_length[200]');
        $this->tnotification->set_rules('ibu_tempatlahir', 'Tempat Lahir Ibu', 'trim|max_length[150]');
        $this->tnotification->set_rules('ibu_tgllahir', 'Tanggal Lahir Ibu', 'trim|max_length[150]');
        $this->tnotification->set_rules('ibu_kewarganegaraan', 'Kewarganegaraan Ibu', 'trim');
        $this->tnotification->set_rules('ibu_alamat', 'Alamat Ibu', 'trim');
        $this->tnotification->set_rules('ibu_pendidikan', 'Pendidikan Ibu', 'trim|maxlength[255]');
        $this->tnotification->set_rules('ibu_pekerjaan', 'Pekerjaan Ibu', 'trim|maxlength[200]');
        $this->tnotification->set_rules('ibu_notelp', 'No Telp. Ibu', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ibu_agama', 'Agama Ibu', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ibu_st', 'Status Ibu', 'trim');
        // cek input wali
        $this->tnotification->set_rules('wali_nm', 'Nama Wali', 'trim|max_length[200]');
        $this->tnotification->set_rules('wali_alamat', 'Alamat Wali', 'trim');
        $this->tnotification->set_rules('wali_pekerjaan', 'Pekerjaan Wali', 'trim|maxlength[200]');
        $this->tnotification->set_rules('wali_notelp', 'No Telp. Wali', 'trim|maxlength[100]');
        $this->tnotification->set_rules('wali_agama', 'Agama Wali', 'trim|maxlength[100]');
        $this->tnotification->set_rules('cs_upload', 'Dokumen Siswa', 'trim');

        // process

        if ($this->tnotification->run() !== FALSE) {
            $cs_no_pendaftaran = date('Ymd') . $this->m_calonsiswa->get_new_id();
            $params = array(
				$this->input->post('cs_nis'),
				$this->input->post('sekolah_id'),
            );
            // check if siswa not registered
            if(!$this->m_calonsiswa->is_registered($params)){
	            $params = array(
	                $this->input->post('jalurpsb_id'),
	                $cs_no_pendaftaran,
	                $this->input->post('cs_nis'),
	                $this->input->post('cs_nama'),
	                $this->input->post('sekolah_id'),
	                $this->input->post('cs_tgl_daftar'),
	                $this->input->post('cs_alamat'),
	                'PROSES',
	                $this->input->post('cs_nilai_un'),
	                $this->input->post('cs_jk'),
	                $this->input->post('cs_kewarganegaraan'),
	                $this->input->post('cs_negara'),
	                $this->input->post('jurusan_id'),
	                $this->input->post('provinsi_id'),
	                $this->input->post('kab_id'),
	                $this->input->post('cs_notelp'),
	                $this->input->post('cs_email'),
	                $this->input->post('cs_tahun_lulus'),
	                $this->input->post('agama_cd'),
	                $this->input->post('ayah_nm'),
	                $this->input->post('ayah_tempatlahir'),
	                $this->input->post('ayah_tgllahir'),
	                $this->input->post('ayah_kewarganegaraan'),
	                $this->input->post('ayah_pendidikan'),
	                $this->input->post('ayah_pekerjaan'),
	                $this->input->post('ayah_alamat'),
	                $this->input->post('ayah_notelp'),
	                $this->input->post('ayah_agama'),
	                $this->input->post('ayah_st'),
	                $this->input->post('ibu_nm'),
	                $this->input->post('ibu_tempatlahir'),
	                $this->input->post('ibu_tgllahir'),
	                $this->input->post('ibu_kewarganegaraan'),
	                $this->input->post('ibu_pendidikan'),
	                $this->input->post('ibu_pekerjaan'),
	                $this->input->post('ibu_alamat'),
	                $this->input->post('ibu_notelp'),
	                $this->input->post('ibu_agama'),
	                $this->input->post('ibu_st'),
	                $this->input->post('wali_nm'),
	                $this->input->post('wali_pekerjaan'),
	                $this->input->post('wali_alamat'),
	                $this->input->post('wali_notelp'),
	                $this->input->post('wali_agama'),
	                $this->input->post('wali_st'),
	                $this->com_user['user_id']
	            );

	            // insert
	            if ($this->m_calonsiswa->insert_calonsiswa($params)) {
	                $cs_id = $this->db->insert_id();

	                // insert pembayaran pendaftaran
	                $biaya_daftar = $this->m_preference->get_preference_by_group_and_name(array('biaya', 'pendaftaran'));
	                $params = array(
	                    'kas_no' => 'DB-PSB-' . $this->m_pembayaran->get_new_kas_no(),
	                    'cs_id' => $cs_id,
	                    'trx_date' => date('Y-m-d'),
	                    'trx_st' => 'wait',
	                    'debit' => $biaya_daftar['0']['pref_value'],
	                    'mdb' => $this->com_user['user_id'],
	                    'mdd' => date('Y-m-d')
	                );
	                if (!$this->m_pembayaran->insert_pembayaran($params)) {
	                    // jika gagal
	                    $this->tnotification->set_error_message("Terjadi Kegagalan");
	                }
	                // upload foto
	                if (!empty($_FILES['cs_foto']['tmp_name'])) {
	                    // load
	                    $this->load->library('tupload');
	                    // delete
	                    $filepath = 'resource/psb/photo/' . "FOTO" . date('YmdHisu');
	                    if (is_file($filepath)) {
	                        unlink($filepath);
	                    }
	                    // upload config
	                    $config['upload_path'] = 'resource/psb/photo';
	                    $config['allowed_types'] = 'gif|jpg|png';
	                    $config['file_name'] = "FOTO" . date('YmdHisu');
	                    $this->tupload->initialize($config);
	                    // process upload images
	                    if ($this->tupload->do_upload_image('cs_foto', false, 160)) {
	                        $data = $this->tupload->data();
	                        $params = array(
	                            $data['file_name'],
	                            $cs_id
	                        );
	                        $this->m_calonsiswa->update_foto($params);
	                    } else {
	                        // jika gagal
	                        $this->tnotification->set_error_message($this->tupload->display_errors());
	                    }
	                }
	                // upload dokument
	                if (!empty($_FILES['cs_upload']['tmp_name'])) {
	                    // load
	                    $this->load->library('tupload');
	                    // delete
	                    $filepath = 'resource/psb/doc/' . "DOC" . date('YmdHisu');
	                    if (is_file($filepath)) {
	                        unlink($filepath);
	                    }
	                    // upload config
	                    $config['upload_path'] = 'resource/psb/doc';
	                    $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|xls|xslsx';
	                    $config['file_name'] = "DOC" . date('YmdHisu');
	                    $this->tupload->initialize($config);
	                    // process upload images
	                    if ($this->tupload->do_upload('cs_upload')) {
	                        $data = $this->tupload->data();
	                        $params = array(
	                            $data['file_name'],
	                            $cs_id
	                        );
	                        $this->m_calonsiswa->update_dokumen($params);
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
            }else{
            	// default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan, calon siswa telah terdaftar");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("psb/calonsiswa/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        // set template content
        $this->smarty->assign("template_content", "psb/calonsiswa/edit.html");
        // send data
        $result = $this->m_calonsiswa->get_calonsiswa_by_id($param);
        $this->smarty->assign("result", $result);
        // psb
        $this->smarty->assign("rs_psb", $this->m_psb->get_psb_group_by_tha());
        // sekolah
        $this->smarty->assign("rs_sekolah", $this->m_sekolah->get_all_sekolah());
        // provinsi
        $this->smarty->assign("rs_provinsi", $this->m_provinsi->get_all_provinsi());
        // kabupaten
        $this->smarty->assign("rs_kab", $this->m_kabupaten->get_all_kabupaten());
        // agama
        $this->smarty->assign("rs_agama", $this->m_agama->get_all_agama());
        // jalur psb
        $this->smarty->assign("rs_jalur", $this->m_psb->get_psb_by_tha($result['psb_tha']));
        // jurusan
        $this->smarty->assign("rs_jurusan", $this->m_jurusan->get_all_jurusan());
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
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
        $this->tnotification->set_rules('cs_nis', 'Nomor Induk Siswa', 'trim|required|max_length[15]');
        $this->tnotification->set_rules('psb_id', 'Tahun Ajaran PSB', 'trim|required');
        $this->tnotification->set_rules('jalurpsb_id', 'Jalur PSB', 'trim|required');
        $this->tnotification->set_rules('cs_tgl_daftar', 'Tanggal Pendaftaran', 'trim');
        $this->tnotification->set_rules('cs_nama', 'Nama Siswa', 'trim|required|max_length[250]');
        $this->tnotification->set_rules('cs_jk', 'Jenis Kelamin', 'trim|max_length[1]');
        $this->tnotification->set_rules('cs_kewarganegaraan', 'Kewarganegaraan', 'trim|required');
        $this->tnotification->set_rules('cs_negara', 'Negara Asal', 'trim|maxlength[100]');
        $this->tnotification->set_rules('cs_notelp', 'No. Telp Siswa', 'trim|maxlength[100]');
        $this->tnotification->set_rules('agama_cd', 'Agama Siswa', 'trim|required|maxlength[1]');
        $this->tnotification->set_rules('cs_alamat', 'Alamat Siswa', 'trim');
        $this->tnotification->set_rules('provinsi_id', 'Provinsi', 'trim');
        $this->tnotification->set_rules('kab_id', 'Kabupaten', 'trim');
        $this->tnotification->set_rules('cs_st', 'Kabupaten', 'trim');
        $this->tnotification->set_rules('sekolah_id', 'Asal Sekolah', 'trim|required');
        $this->tnotification->set_rules('cs_nilai_un', 'Nilai UN', 'trim|required|numeric');
        $this->tnotification->set_rules('cs_tahun_lulus', 'Tahun Lulus', 'trim|numeric|maxlength[4]');
        $this->tnotification->set_rules('cs_email', 'Email', 'trim|maxlength[200]');
        $this->tnotification->set_rules('jurusan_id', 'Jurusan', 'trim|maxlength[2]');
        // cek input ayah
        $this->tnotification->set_rules('ayah_nm', 'Nama Ayah', 'trim|max_length[200]');
        $this->tnotification->set_rules('ayah_tempatlahir', 'Tempat Lahir Ayah', 'trim|max_length[150]');
        $this->tnotification->set_rules('ayah_tgllahir', 'Tanggal Lahir Ayah', 'trim|max_length[150]');
        $this->tnotification->set_rules('ayah_kewarganegaraan', 'Kewarganegaraan Ayah', 'trim|required');
        $this->tnotification->set_rules('ayah_alamat', 'Alamat Ayah', 'trim');
        $this->tnotification->set_rules('ayah_pendidikan', 'Pendidikan Ayah', 'trim|maxlength[255]');
        $this->tnotification->set_rules('ayah_pekerjaan', 'Pekerjaan Ayah', 'trim|maxlength[200]');
        $this->tnotification->set_rules('ayah_notelp', 'No Telp. Ayah', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ayah_agama', 'Agama Ayah', 'trim|maxlength[100]');
        // cek input ibu
        $this->tnotification->set_rules('ibu_nm', 'Nama Ibu', 'trim|max_length[200]');
        $this->tnotification->set_rules('ibu_tempatlahir', 'Tempat Lahir Ibu', 'trim|max_length[150]');
        $this->tnotification->set_rules('ibu_tgllahir', 'Tanggal Lahir Ibu', 'trim|max_length[150]');
        $this->tnotification->set_rules('ibu_kewarganegaraan', 'Kewarganegaraan Ibu', 'trim|required');
        $this->tnotification->set_rules('ibu_alamat', 'Alamat Ibu', 'trim');
        $this->tnotification->set_rules('ibu_pendidikan', 'Pendidikan Ibu', 'trim|maxlength[255]');
        $this->tnotification->set_rules('ibu_pekerjaan', 'Pekerjaan Ibu', 'trim|maxlength[200]');
        $this->tnotification->set_rules('ibu_notelp', 'No Telp. Ibu', 'trim|maxlength[100]');
        $this->tnotification->set_rules('ibu_agama', 'Agama Ibu', 'trim|maxlength[100]');
        // cek input wali
        $this->tnotification->set_rules('wali_nm', 'Nama Wali', 'trim|max_length[200]');
        $this->tnotification->set_rules('wali_alamat', 'Alamat Wali', 'trim');
        $this->tnotification->set_rules('wali_pekerjaan', 'Pekerjaan Wali', 'trim|maxlength[200]');
        $this->tnotification->set_rules('wali_notelp', 'No Telp. Wali', 'trim|maxlength[100]');
        $this->tnotification->set_rules('wali_agama', 'Agama Wali', 'trim|maxlength[100]');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('jalurpsb_id'),
                $this->input->post('cs_nis'),
                $this->input->post('cs_nama'),
                $this->input->post('sekolah_id'),
                $this->input->post('cs_tgl_daftar'),
                $this->input->post('cs_alamat'),
                $this->input->post('cs_st'),
                $this->input->post('cs_nilai_un'),
                $this->input->post('cs_jk'),
                $this->input->post('cs_kewarganegaraan'),
                $this->input->post('cs_negara'),
                $this->input->post('jurusan_id'),
                $this->input->post('provinsi_id'),
                $this->input->post('kab_id'),
                $this->input->post('cs_notelp'),
                $this->input->post('cs_email'),
                $this->input->post('cs_tahun_lulus'),
                $this->input->post('agama_cd'),
                $this->input->post('ayah_nm'),
                $this->input->post('ayah_tempatlahir'),
                $this->input->post('ayah_tgllahir'),
                $this->input->post('ayah_kewarganegaraan'),
                $this->input->post('ayah_pendidikan'),
                $this->input->post('ayah_pekerjaan'),
                $this->input->post('ayah_alamat'),
                $this->input->post('ayah_notelp'),
                $this->input->post('ayah_agama'),
                $this->input->post('ayah_st'),
                $this->input->post('ibu_nm'),
                $this->input->post('ibu_tempatlahir'),
                $this->input->post('ibu_tgllahir'),
                $this->input->post('ibu_kewarganegaraan'),
                $this->input->post('ibu_pendidikan'),
                $this->input->post('ibu_pekerjaan'),
                $this->input->post('ibu_alamat'),
                $this->input->post('ibu_notelp'),
                $this->input->post('ibu_agama'),
                $this->input->post('ibu_st'),
                $this->input->post('wali_nm'),
                $this->input->post('wali_pekerjaan'),
                $this->input->post('wali_alamat'),
                $this->input->post('wali_notelp'),
                $this->input->post('wali_agama'),
                $this->com_user['user_id'],
                $this->input->post('cs_id')
            );
            // insert

            if ($this->m_calonsiswa->update_calonsiswa($params)) {
                // upload foto

                if (!empty($_FILES['cs_foto']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // delete
                    $filepath = 'resource/psb/photo/' . $this->input->post('cs_foto_name');
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                    // upload config
                    $config['upload_path'] = 'resource/psb/photo';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = "FOTO" . date('YmdHisu');
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('cs_foto', false, 160)) {
                        $data = $this->tupload->data();
                        $params = array(
                            $data['file_name'],
                            $this->input->post('cs_id')
                        );
                        $this->m_calonsiswa->update_foto($params);
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                }
                // upload dokument
                if (!empty($_FILES['cs_upload']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // delete
                    $filepath = 'resource/psb/doc/' . $this->input->post('cs_doc_name');
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                    // upload config
                    $config['upload_path'] = 'resource/psb/doc';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = "DOC" . date('YmdHisu');
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('cs_upload', false, false)) {
                        $data = $this->tupload->data();
                        $params = array(
                            $data['file_name'],
                            $this->input->post('cs_id')
                        );
                        $this->m_calonsiswa->update_dokumen($params);
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
        redirect("psb/calonsiswa/edit/" . $this->input->post('cs_id'));
    }

    // delete calonsiswa
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_calonsiswa->delete_calonsiswa($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("psb/calonsiswa/");
    }

    function get_jalur_available() {
        $result = $this->m_psb->get_psb_by_tha($this->input->post('tha'));
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    function get_kab() {
        $result = $this->m_kabupaten->get_kabupaten_by_prov($this->input->get('prov'));
        header('Content-Type: application/json');
        echo json_encode($result);
    }

}
