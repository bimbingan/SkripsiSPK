<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class penempatan extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('psb/m_penempatan');
        $this->load->model('master/m_jurusan');
        $this->load->model('master/m_grade');
        $this->load->model('master/m_kelas');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "psb/penempatan/list.html");
        // session
        $search = $this->tsession->userdata('search_penempatan_psb');
        $this->smarty->assign('search', $search);

        // params
        $siswa_tahun_masuk = $search['siswa_tahun_masuk'];
        $jurusan_id = !empty($search['jurusan_id']) ? $search['jurusan_id'] : $this->m_jurusan->get_top_jurusan();
        $operator = $search['operator'];
        $siswa_nilai_un = $search['siswa_nilai_un'];
        $params = array($siswa_tahun_masuk, $jurusan_id);

        // pagination
        $config['base_url'] = site_url("psb/penempatan/index/");

        $config['uri_segment'] = 4;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();

        // pagination attribute
        $start = $this->uri->segment(4, 0) + 1;

        // process
        if (!empty($search['operator']) && isset($search['siswa_nilai_un'])) {
            $params[] = $siswa_nilai_un;
            $config['total_rows'] = $this->m_penempatan->get_total_siswa_baru_with_nilai($params);
            $params = array($siswa_tahun_masuk, $jurusan_id, $siswa_nilai_un, ($start - 1), $config['per_page']);
            $siswa_baru = $this->m_penempatan->get_siswa_baru_with_nilai($params, $operator);
        } else {
            $config['total_rows'] = $this->m_penempatan->get_total_siswa_baru($params);
            $params = array($siswa_tahun_masuk, $jurusan_id, ($start - 1), $config['per_page']);
            $siswa_baru = $this->m_penempatan->get_siswa_baru($params);
        }

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


        $this->smarty->assign("rs_jurusan", $this->m_jurusan->get_all_jurusan());
        $this->smarty->assign("rs_tahun_masuk", $this->m_penempatan->get_all_tahun_masuk());
        $this->smarty->assign("rs_grade", $this->m_grade->get_all_grade());
        $this->smarty->assign("rs_id", $siswa_baru);

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
                "operator" => $this->input->post('operator'),
                "siswa_nilai_un" => $this->input->post('siswa_nilai_un'),
                "siswa_tahun_masuk" => $this->input->post('siswa_tahun_masuk'),
                "jurusan_id" => $this->input->post('jurusan_id'),
            );
            // set
            $this->tsession->set_userdata('search_penempatan_psb', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_penempatan_psb');
        }
        //--
        redirect('psb/penempatan/');
    }

    function process() {
        // set page rules
        $this->_set_page_rule("U");
        $this->tnotification->set_rules('kelas_id', 'Kelas', 'trim|required');
        if ($this->tnotification->run() !== FALSE) {
            $siswa_ids = $this->input->post('siswa_id');
            if (!empty($siswa_ids)) {
                $params = array();
                foreach ($siswa_ids as $key => $siswa_id) {
                    $params[] = array($this->input->post('kelas_id'), $siswa_id);
                }
                if ($this->m_penempatan->update_kelas_siswa($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Tidak ada data yang dipilih");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }

        redirect('psb/penempatan/');
    }

    public function ajax_get_kelas() {
        $grade_id = $this->input->post('grade_id');
        $jurusan_id = $this->input->post('jurusan_id');
        $rs_kelas = $this->m_kelas->get_kelas_teori_active_with_grade_and_jurusan(array($grade_id, $jurusan_id));
        header('Content-Type: application/json');
        echo json_encode($rs_kelas);
    }

}
