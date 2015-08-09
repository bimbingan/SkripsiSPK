<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class siswa extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_siswa');
        $this->load->model('master/m_sekolah');
        $this->load->model('master/m_provinsi');
        $this->load->model('master/m_kabupaten');
        $this->load->model('master/m_agama');
        $this->load->model('master/m_psb');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/siswa/list.html");

        // session
        $search = $this->tsession->userdata('search_siswa');
        $this->smarty->assign('search', $search);

        // params
        $siswa_nis = !empty($search['siswa_nis']) ? "%" . $search['siswa_nis'] . "%" : "%";
        $siswa_nama = !empty($search['siswa_nama']) ? "%" . $search['siswa_nama'] . "%" : "%";

        $params = array($siswa_nis, $siswa_nama);

        $config['base_url'] = site_url("master/siswa/index/");
        $config['total_rows'] = $this->m_siswa->get_total_siswa($params);

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
        $params = array($siswa_nis, $siswa_nama, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_siswa->get_list_siswa($params));

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
                "siswa_nis" => $this->input->post('siswa_nis'),
                "siswa_nama" => $this->input->post('siswa_nama'),
            );

            // set
            $this->tsession->set_userdata('search_siswa', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_siswa');
        }
        //--
        redirect('master/siswa');
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        // set template content
        $this->smarty->assign("template_content", "master/siswa/edit.html");
        // send data
        $result = $this->m_siswa->get_siswa_by_id($param);
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
        $this->tnotification->set_rules('siswa_nis', 'Nomor Induk Siswa', 'trim|required|max_length[15]');
        $this->tnotification->set_rules('psb_id', 'Tahun Ajaran PSB', 'trim|required');
        $this->tnotification->set_rules('jalurpsb_id', 'Jalur PSB', 'trim|required');
        $this->tnotification->set_rules('siswa_tgl_daftar', 'Tanggal Pendaftaran', 'trim');
        $this->tnotification->set_rules('siswa_nama', 'Nama Siswa', 'trim|required|max_length[250]');
        $this->tnotification->set_rules('siswa_jk', 'Jenis Kelamin', 'trim|max_length[1]');
        $this->tnotification->set_rules('siswa_kewarganegaraan', 'Kewarganegaraan', 'trim|required');
        $this->tnotification->set_rules('siswa_negara', 'Negara Asal', 'trim|maxlength[100]');
        $this->tnotification->set_rules('siswa_notelp', 'No. Telp Siswa', 'trim|maxlength[100]');
        $this->tnotification->set_rules('agama_cd', 'Agama Siswa', 'trim|required|maxlength[1]');
        $this->tnotification->set_rules('siswa_alamat', 'Alamat Siswa', 'trim');
        $this->tnotification->set_rules('provinsi_id', 'Provinsi', 'trim');
        $this->tnotification->set_rules('kab_id', 'Kabupaten', 'trim');
        $this->tnotification->set_rules('siswa_st', 'Kabupaten', 'trim');
        $this->tnotification->set_rules('sekolah_id', 'Asal Sekolah', 'trim|required');
        $this->tnotification->set_rules('siswa_nilai_un', 'Nilai UN', 'trim|required|numeric');
        $this->tnotification->set_rules('siswa_tahun_lulus', 'Tahun Lulus', 'trim|numeric|maxlength[4]');
        $this->tnotification->set_rules('siswa_email', 'Email', 'trim|maxlength[200]');
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
                $this->input->post('siswa_nis'),
                $this->input->post('siswa_nama'),
                $this->input->post('sekolah_id'),
                $this->input->post('siswa_tgl_daftar'),
                $this->input->post('siswa_alamat'),
                $this->input->post('siswa_st'),
                $this->input->post('siswa_nilai_un'),
                $this->input->post('siswa_jk'),
                $this->input->post('siswa_kewarganegaraan'),
                $this->input->post('siswa_negara'),
                $this->input->post('provinsi_id'),
                $this->input->post('kab_id'),
                $this->input->post('siswa_notelp'),
                $this->input->post('siswa_email'),
                $this->input->post('siswa_tahun_lulus'),
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
                $this->input->post('siswa_id')
            );
            // insert

            if ($this->m_siswa->update_siswa($params)) {
                // upload foto

                if (!empty($_FILES['siswa_foto']['tmp_name'])) {
                    // load
                    $this->load->library('tupload');
                    // delete
                    $filepath = 'resource/master/photo/' . $this->input->post('siswa_foto_name');
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                    // upload config
                    $config['upload_path'] = 'resource/master/photo';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = "FOTO" . date('YmdHisu');
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('siswa_foto', false, 160)) {
                        $data = $this->tupload->data();
                        $params = array(
                            $data['file_name'],
                            $this->input->post('siswa_id')
                        );
                        $this->m_siswa->update_foto($params);
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
                    $filepath = 'resource/master/doc/' . $this->input->post('siswa_doc_name');
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                    // upload config
                    $config['upload_path'] = 'resource/master/doc';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = "DOC" . date('YmdHisu');
                    $this->tupload->initialize($config);
                    // process upload images
                    if ($this->tupload->do_upload_image('siswa_upload', false, false)) {
                        $data = $this->tupload->data();
                        $params = array(
                            $data['file_name'],
                            $this->input->post('siswa_id')
                        );
                        $this->m_siswa->update_dokumen($params);
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
        redirect("master/siswa/edit/" . $this->input->post('siswa_id'));
    }

    // delete siswa
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_siswa->delete_siswa($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("master/siswa/");
    }

    function get_jalur_available() {
        $result = $this->m_psb->get_psb_by_tha($this->input->post('tha'));
        header('Content-Type: application/json');
        echo json_encode($result);
    }

}
