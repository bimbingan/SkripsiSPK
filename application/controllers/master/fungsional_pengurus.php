<?php

// if (!defined('BASEPATH'))
// 	exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class fungsional_pengurus extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_fungsional_pengurus');
        $this->load->model('master/m_jabatanfungsional');
        $this->load->model('master/m_pengurussekolah');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/fungsional_pengurus/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");

        // session
        $search = $this->tsession->userdata('search_fungsional_pengurus');
        $this->smarty->assign('search', $search);

        // params
        $guru_id = !empty($search['guru_id']) ? "%" . $search['guru_id'] . "%" : "%";
        $fungsional_id = !empty($search['fungsional_id']) ? "%" . $search['fungsional_id'] . "%" : "%";
        $fp_st = isset($search['fp_st']) ? $search['fp_st'] : "%";
        $params = array($guru_id, $fungsional_id, $fp_st);


        // pagination
        $config['base_url'] = site_url("master/fungsional_pengurus/index/");
        $config['total_rows'] = $this->m_fungsional_pengurus->get_total_fungsional_pengurus($params);

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
        $params = array($guru_id, $fungsional_id, $fp_st, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_fungsional_pengurus->get_list_fungsional_pengurus($params));
        // get data pengurus sekolah
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah());
        // get data jabatan fungsional	
        $this->smarty->assign("rs_fungsional", $this->m_jabatanfungsional->get_all_jabatanfungsional());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // set localization
        setlocale(LC_TIME, 'id');
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
                "guru_id" => $this->input->post('guru_id'),
                "fungsional_id" => $this->input->post('fungsional_id'),
                "fp_st" => $this->input->post('fp_st')
            );
            // set
            $this->tsession->set_userdata('search_fungsional_pengurus', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_fungsional_pengurus');
        }
        //--
        redirect('master/fungsional_pengurus');
    }

    // form tambah fungsional pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/fungsional_pengurus/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // get list data
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah());
        $this->smarty->assign("rs_fungsional", $this->m_jabatanfungsional->get_all_jabatanfungsional());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah fungsional pengurus
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('guru_id', 'Pengurus', 'trim|required');
        $this->tnotification->set_rules('fungsional_id', 'Jabatan Fungsional', 'trim|required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('fp_st', 'Status', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('guru_id'),
                $this->input->post('fungsional_id'),
                $this->input->post('tanggal_mulai'),
                $this->input->post('tanggal_selesai'),
                $this->input->post('fp_st'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_fungsional_pengurus->insert_fungsional_pengurus($params)) {
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
        redirect("master/fungsional_pengurus/add");
    }

    // form fungsional pengurus
    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // user data
        $result = $this->m_fungsional_pengurus->get_fungsional_pengurus_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/fungsional_pengurus/edit.html");
        // send data
        $this->smarty->assign("result", $result);
        // get list data
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah());
        $this->smarty->assign("rs_fungsional", $this->m_jabatanfungsional->get_all_jabatanfungsional());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process edit fungsional pengurus
    function process_edit() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('fp_id', 'ID', 'trim|required');
        $this->tnotification->set_rules('guru_id', 'Pengurus', 'trim|required');
        $this->tnotification->set_rules('fungsional_id', 'Jabatan Fungsional', 'trim|required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('fp_st', 'Status', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('guru_id'),
                $this->input->post('fungsional_id'),
                $this->input->post('tanggal_mulai'),
                $this->input->post('tanggal_selesai'),
                $this->input->post('fp_st'),
                $this->com_user['user_id'],
                $this->input->post('fp_id')
            );
            // insert
            if ($this->m_fungsional_pengurus->update_fungsional_pengurus($params)) {
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
        redirect("master/fungsional_pengurus/edit/" . $this->input->post('fp_id'));
    }

    // delete fungsional pengurus
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_fungsional_pengurus->delete_fungsional_pengurus($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/fungsional_pengurus/");
    }
}