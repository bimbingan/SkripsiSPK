<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class kelas extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_kelas');
        $this->load->model('master/m_grade');
        $this->load->model('master/m_jurusan');
        $this->load->model('master/m_pengurussekolah');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/kelas/list.html");
        // session
        $search = $this->tsession->userdata('search_kelas');
        $this->smarty->assign('search', $search);

        // params
        $kelas_nm = !empty($search['kelas_nm']) ? "%" . $search['kelas_nm'] . "%" : "%";
        $grade_id = !empty($search['grade_id']) ? "%" . $search['grade_id'] . "%" : "%";
        $jurusan_id = !empty($search['jurusan_id']) ? "%" . $search['jurusan_id'] . "%" : "%";
        $kelas_st = !empty($search['kelas_st']) ? "%" . $search['kelas_st'] . "%" : "%";
        $params = array($kelas_nm, $grade_id, $jurusan_id, $kelas_st);

        // pagination
        $config['base_url'] = site_url("master/kelas/index/");
        $config['total_rows'] = $this->m_kelas->get_total_kelas($params);

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
        $params = array($kelas_nm, $grade_id, $jurusan_id, $kelas_st, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_kelas->get_list_kelas($params));
        $this->smarty->assign("rs_grade", $this->m_grade->get_all_grade());
        $this->smarty->assign("rs_jurusan", $this->m_jurusan->get_all_jurusan());

        // notification
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
                "kelas_nm" => $this->input->post('kelas_nm'),
                "grade_id" => $this->input->post('grade_id'),
                "jurusan_id" => $this->input->post('jurusan_id'),
                "kelas_st" => $this->input->post('kelas_st')
            );
            // set
            $this->tsession->set_userdata('search_kelas', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_kelas');
        }
        //--
        redirect('master/kelas/');
    }

    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // set template content
        $this->smarty->assign("template_content", "master/kelas/add.html");
        $this->smarty->assign("rs_grade", $this->m_grade->get_all_grade());
        $this->smarty->assign("rs_jurusan", $this->m_jurusan->get_all_jurusan());
        $this->smarty->assign("rs_guru", $this->m_pengurussekolah->get_all_pengurussekolah());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah kelas pendaftaran
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('kelas_nm', 'Nama Kelas', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('grade_id', 'Tingkat', 'trim|required|integer');
        $this->tnotification->set_rules('jurusan_id', 'Jurusan', 'trim|max_length[2]');
        $this->tnotification->set_rules('kuota', 'Kuota', 'trim|required|integer');
        $this->tnotification->set_rules('kelas_st', 'Status', 'trim|required');
        $this->tnotification->set_rules('kelas_jenis', 'Jenis Kelas', 'trim|required');
        $this->tnotification->set_rules('wali_kelas', 'Jenis Kelas', 'trim|integer');
        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'kelas_nm' => $this->input->post('kelas_nm'),
                'grade_id' => $this->input->post('grade_id'),
                'jurusan_id' => $this->input->post('jurusan_id'),
                'kuota' => $this->input->post('kuota'),
                'kelas_st' => $this->input->post('kelas_st'),
                'kelas_jenis' => $this->input->post('kelas_jenis'),
                'wali_kelas' => $this->input->post('wali_kelas'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );

            // insert
            if ($this->m_kelas->insert_kelas($params)) {
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
        redirect("master/kelas/add");
    }

    function edit($params) {
        // set page rules
        $this->_set_page_rule("U");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // set template content
        $this->smarty->assign("template_content", "master/kelas/edit.html");
        $this->smarty->assign("rs_grade", $this->m_grade->get_all_grade());
        $this->smarty->assign("rs_jurusan", $this->m_jurusan->get_all_jurusan());
        $this->smarty->assign("rs_guru", $this->m_pengurussekolah->get_all_pengurussekolah());
        // load data kelompok mapel
        $this->smarty->assign("result", $this->m_kelas->get_kelas_by_id($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process ubah kelas pendaftaran
    function process_edit() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('kelas_nm', 'Nama Kelas', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('grade_id', 'Tingkat', 'trim|required|integer');
        $this->tnotification->set_rules('jurusan_id', 'Jurusan', 'trim|max_length[2]');
        $this->tnotification->set_rules('kuota', 'Kuota', 'trim|required|integer');
        $this->tnotification->set_rules('kelas_st', 'Status', 'trim|required');
        $this->tnotification->set_rules('kelas_jenis', 'Jenis Kelas', 'trim|required');
        $this->tnotification->set_rules('wali_kelas', 'Jenis Kelas', 'trim|integer');

        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'kelas_nm' => $this->input->post('kelas_nm'),
                'grade_id' => $this->input->post('grade_id'),
                'jurusan_id' => $this->input->post('jurusan_id'),
                'kuota' => $this->input->post('kuota'),
                'kelas_st' => $this->input->post('kelas_st'),
                'kelas_jenis' => $this->input->post('kelas_jenis'),
                'wali_kelas' => $this->input->post('wali_kelas'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            $where = array(
                'kelas_id' => $this->input->post('kelas_id')
            );
            // update

            if ($this->m_kelas->update_kelas($params, $where)) {
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
        redirect("master/kelas/edit/" . $this->input->post('kelas_id'));
    }

    // delete mapel group
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_kelas->delete_kelas($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/kelas/");
    }

}
