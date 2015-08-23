<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class grade extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_grade');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/grade/list.html");
        // session
        $search = $this->tsession->userdata('search_grade');
        $this->smarty->assign('search', $search);

        // params
        $grade_nm = !empty($search['grade_nm']) ? "%" . $search['grade_nm'] . "%" : "%";
        $params = array($grade_nm);

        // pagination
        $config['base_url'] = site_url("master/grade/index/");
        $config['total_rows'] = $this->m_grade->get_total_grade($params);

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
        $params = array($grade_nm, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_grade->get_list_grade($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pencarian grade 
    function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "grade_nm" => $this->input->post('grade_nm'),
            );
            // set
            $this->tsession->set_userdata('search_grade', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_grade');
        }
        //--
        redirect('master/grade/');
    }

    // form tambah grade
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/grade/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process tambah grade 
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('grade_nm', 'Nama Tingkat', 'trim|max_length[2]');
        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'grade_nm' => $this->input->post('grade_nm'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            // insert
            if (!$this->m_grade->is_grade_exist($params['grade_nm'])) {
                if ($this->m_grade->insert_grade($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                $this->tnotification->sent_notification("error", "Data gagal disimpan, Kelompok Mapel Sudah ada");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/grade/add");
    }

    // form edit grade 
    function edit($params) {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "master/grade/edit.html");

        // load data kelompok mapel
        $this->smarty->assign("result", $this->m_grade->get_grade_by_id($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process ubah grade pendaftaran
    function process_edit() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('grade_id', 'ID', 'trim|required');
        $this->tnotification->set_rules('grade_nm', 'Nama Tingkat', 'trim|max_length[2]');

        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'grade_nm' => $this->input->post('grade_nm'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            $where = array(
                'grade_id' => $this->input->post('grade_id')
            );
            // update
            if (!$this->m_grade->is_grade_exist($params['grade_nm'])) {
                if ($this->m_grade->update_grade($params, $where)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                $this->tnotification->sent_notification("error", "Data gagal disimpan, Kelompok Mapel Sudah ada");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/grade/edit/" . $this->input->post('grade_id'));
    }

    // delete mapel group
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_grade->delete_grade($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/grade/");
    }

}
