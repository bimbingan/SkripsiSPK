<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class herregistrasi extends ApplicationBase {

    function __construct() {

        parent::__construct();
        //load model
        $this->load->model('master/m_psb');
        $this->load->model('master/m_sekolah');
        $this->load->model('master/m_provinsi');
        $this->load->model('master/m_kabupaten');
        $this->load->model('master/m_agama');
        $this->load->model('psb/m_calonsiswa');
        $this->load->model('psb/m_herregistrasi');
        $this->load->model('master/m_jurusan');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {

        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "psb/herregistrasi/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");

        // send data
        if ($this->input->post('save') == 'Cari') {
            $result = $this->m_herregistrasi->get_passed_siswa($this->input->post('cs_no_pendaftaran'));
            $this->smarty->assign("result", $result);
            // jalur psb
            $this->smarty->assign("rs_jalur", $this->m_psb->get_psb_by_tha($result['psb_tha']));
        }
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
        if (empty($result) && isset($result)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("error", "Data Siswa tidak ditemukan");
        }
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "cs_no_pendaftaran" => $this->input->post('cs_no_pendaftaran'),
            );
            // set
            $this->tsession->set_userdata('search_herregistrasi', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_herregistrasi');
        }
        //--
        redirect('psb/herregistrasi/');
    }

    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // load model
        $this->load->model('master/m_grade');
        // cek input
        $this->tnotification->set_rules('siswa_nis', 'Nomor Induk Siswa', 'trim|required|max_length[100]');
        $this->tnotification->set_rules('siswa_nama', 'Nama Siswa', 'trim|required|max_length[250]');
        $this->tnotification->set_rules('siswa_jk', 'Jenis Kelamin', 'trim|max_length[1]');
        $this->tnotification->set_rules('siswa_kewarganegaraan', 'Kewarganegaraan', 'trim|required');
        $this->tnotification->set_rules('siswa_negara', 'Negara Asal', 'trim|maxlength[100]');
        $this->tnotification->set_rules('siswa_notelp', 'No. Telp Siswa', 'trim|maxlength[100]');
        $this->tnotification->set_rules('agama_cd', 'Agama Siswa', 'trim|required|maxlength[1]');
        $this->tnotification->set_rules('siswa_alamat', 'Alamat Siswa', 'trim');
        $this->tnotification->set_rules('provinsi_id', 'Provinsi', 'trim');
        $this->tnotification->set_rules('kab_id', 'Kabupaten', 'trim');
        $this->tnotification->set_rules('sekolah_id', 'Asal Sekolah', 'trim|required');
        $this->tnotification->set_rules('jurusan_id', 'Jurusan', 'trim|maxlength[2]');
        $this->tnotification->set_rules('siswa_nilai_un', 'Nilai UN', 'trim|required|numeric');
        $this->tnotification->set_rules('siswa_tahun_masuk', 'Tahun Masuk', 'trim|numeric|required|maxlength[4]');
        $this->tnotification->set_rules('siswa_email', 'Email', 'trim|maxlength[200]');
        $this->tnotification->set_rules('siswa_tgl_herreg', 'Tanggal Her-Registrasi', 'trim');
        $this->tnotification->set_rules('siswa_tgl_lulus', 'Tanggal Lulus', 'trim');
        $this->tnotification->set_rules('nomor_ijazah', 'Nomor Ijazah', 'trim|maxlength[100]');
        $this->tnotification->set_rules('tgl_ijazah', 'Tanggal Ijazaj', 'trim');
        // cek input ayah
        $this->tnotification->set_rules('ayah_nm', 'Nama Ayah', 'trim|max_length[200]');
        $this->tnotification->set_rules('ayah_tempatlahir', 'Tempat Lahir Ayah', 'trim|max_length[150]');
        $this->tnotification->set_rules('ayah_tgllahir', 'Tanggal Lahir Ayah', 'trim');
        $this->tnotification->set_rules('ayah_kewarganegaraan', 'Kewarganegaraan Ayah', 'trim|required');
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
        $this->tnotification->set_rules('ibu_kewarganegaraan', 'Kewarganegaraan Ibu', 'trim|required');
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
        $this->tnotification->set_rules('siswa_upload', 'Dokumen Siswa', 'trim');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $nis = date('Ymd') . $this->m_calonsiswa->get_new_id();
            $params = array(
                'siswa_nis' => $this->input->post('siswa_nis'),
                'siswa_nama' => $this->input->post('siswa_nama'),
                'sekolah_id' => $this->input->post('sekolah_id'),
                'siswa_alamat' => $this->input->post('siswa_alamat'),
                'siswa_foto' => $this->input->post('siswa_foto_string'),
                'siswa_upload' => $this->input->post('siswa_upload_string'),
                'siswa_nilai_un' => $this->input->post('siswa_nilai_un'),
                'siswa_jk' => $this->input->post('siswa_jk'),
                'siswa_kewarganegaraan' => $this->input->post('siswa_kewarganegaraan'),
                'siswa_negara' => $this->input->post('siswa_negara'),
                'provinsi_id' => $this->input->post('provinsi_id'),
                'kab_id' => $this->input->post('kab_id'),
                'siswa_notelp' => $this->input->post('siswa_notelp'),
                'siswa_email' => $this->input->post('siswa_email'),
                'siswa_tahun_masuk' => $this->input->post('siswa_tahun_masuk'),
                'siswa_tgl_herreg' => $this->input->post('siswa_tgl_herreg'),
                'siswa_tgl_lulus' => $this->input->post('siswa_tgl_lulus'),
                'nomor_ijazah' => $this->input->post('nomor_ijazah'),
                'grade_id' => $this->m_grade->get_least_grade(),
                'tgl_ijazah' => $this->input->post('tgl_ijazah'),
                'jurusan_id' => $this->input->post('jurusan_id'),
                'agama_cd' => $this->input->post('agama_cd'),
                'ayah_nm' => $this->input->post('ayah_nm'),
                'ayah_tempatlahir' => $this->input->post('ayah_tempatlahir'),
                'ayah_tgllahir' => $this->input->post('ayah_tgllahir'),
                'ayah_kewarganegaraan' => $this->input->post('ayah_kewarganegaraan'),
                'ayah_pendidikan' => $this->input->post('ayah_pendidikan'),
                'ayah_pekerjaan' => $this->input->post('ayah_pekerjaan'),
                'ayah_alamat' => $this->input->post('ayah_alamat'),
                'ayah_notelp' => $this->input->post('ayah_notelp'),
                'ayah_agama' => $this->input->post('ayah_agama'),
                'ayah_st' => $this->input->post('ayah_st'),
                'ibu_nm' => $this->input->post('ibu_nm'),
                'ibu_tempatlahir' => $this->input->post('ibu_tempatlahir'),
                'ibu_tgllahir' => $this->input->post('ibu_tgllahir'),
                'ibu_kewarganegaraan' => $this->input->post('ibu_kewarganegaraan'),
                'ibu_pendidikan' => $this->input->post('ibu_pendidikan'),
                'ibu_pekerjaan' => $this->input->post('ibu_pekerjaan'),
                'ibu_alamat' => $this->input->post('ibu_alamat'),
                'ibu_notelp' => $this->input->post('ibu_notelp'),
                'ibu_agama' => $this->input->post('ibu_agama'),
                'ibu_st' => $this->input->post('ibu_st'),
                'wali_nm' => $this->input->post('wali_nm'),
                'wali_pekerjaan' => $this->input->post('wali_pekerjaan'),
                'wali_alamat' => $this->input->post('wali_alamat'),
                'wali_notelp' => $this->input->post('wali_notelp'),
                'wali_agama' => $this->input->post('wali_agama'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date("Y-m-d H:i:s")
            );

            // insert
            if ($this->m_herregistrasi->insert_siswa($params)) {
                $siswa_id = $this->db->insert_id();
                // upload foto
                if (!empty($_FILES['siswa_foto']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // delete
                    $filepath = 'resource/siswa/photo/' . "FOTO" . date('YmdHisu');
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                    // upload config
                    $config['upload_path'] = 'resource/siswa/photo';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = "FOTO" . date('YmdHisu');
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('cs_foto', false, 160)) {
                        $data = $this->tupload->data();
                        $params = array(
                            'siswa_foto' => $data['file_name']
                        );
                        $where = array(
                            'siswa_id' => $siswa_id
                        );
                        $this->m_herregistrasi->update_siswa($params, $where);
                    } else {
                        // jika gagal
                        $this->tnotification->set_error_message($this->tupload->display_errors());
                    }
                }
                // upload dokument
                if (!empty($_FILES['siswa_upload']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // delete
                    $filepath = 'resource/siswa/doc/' . "DOC" . date('YmdHisu');
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                    // upload config
                    $config['upload_path'] = 'resource/siswa/doc';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = "DOC" . date('YmdHisu');
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('cs_upload', false, 160)) {
                        $data = $this->tupload->data();
                        $params = array(
                            'siswa_foto' => $data['file_name']
                        );
                        $where = array(
                            'siswa_id' => $siswa_id
                        );
                        $this->m_herregistrasi->update_siswa($params, $where);
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
        redirect("psb/herregistrasi/");
    }

    function get_kab() {
        $result = $this->m_kabupaten->get_kabupaten_by_prov($this->input->get('prov'));
        header('Content-Type: application/json');
        echo json_encode($result);
    }

}
