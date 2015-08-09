<?php

// if (!defined('BASEPATH'))
// 	exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class struktural_pengurus extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_struktural_pengurus');
        $this->load->model('master/m_jabatanstruktural');
        $this->load->model('master/m_jabatanpengurus');
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
        $this->smarty->assign("template_content", "master/struktural_pengurus/list.html");
        // session
        $search = $this->tsession->userdata('search_struktural_pengurus');
        $this->smarty->assign('search', $search);

        // params
        $struktural_id = !empty($search['struktural_id']) ? "%" . $search['struktural_id'] . "%" : "%";
        $guru_nama = !empty($search['guru_nama']) ? "%" . $search['guru_nama'] . "%" : "%";
        $jabatan_id = !empty($search['jabatan_id']) ? ((int) $search['jabatan_id']) : "%";
        $sp_st = ($search['sp_st'] === "") ? "%" : $search['sp_st'];
        $params = array($struktural_id, $guru_nama, $jabatan_id, $sp_st);


        // pagination
        $config['base_url'] = site_url("master/struktural_pengurus/index/");
        $config['total_rows'] = $this->m_struktural_pengurus->get_total_struktural_pengurus($params);

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
        $params = array($struktural_id, $guru_nama, $jabatan_id, $sp_st, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_struktural_pengurus->get_list_struktural_pengurus($params));

        $this->smarty->assign("rs_struktural", $this->m_jabatanstruktural->get_all_jabatanstruktural());

        $this->smarty->assign("rs_jabatan", $this->m_jabatanpengurus->get_all_jabatanpengurus());
        // set localization
        setlocale(LC_TIME, 'id');
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
                "struktural_id" => $this->input->post('struktural_id'),
                "guru_nama" => $this->input->post('guru_nama'),
                "jabatan_id" => $this->input->post('jabatan_id'),
                "sp_st" => $this->input->post('sp_st')
            );
            // set
            $this->tsession->set_userdata('search_struktural_pengurus', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_struktural_pengurus');
        }
        //--
        redirect('master/struktural_pengurus');
    }

    // form tambah jenis pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/struktural_pengurus/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // get list data
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah());
        $this->smarty->assign("rs_struktural", $this->m_jabatanstruktural->get_all_jabatanstruktural());
        $this->smarty->assign("rs_jabatan", $this->m_jabatanpengurus->get_all_jabatanpengurus());

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
        $this->tnotification->set_rules('struktural_id', 'Jabatan Struktural', 'trim|required');
        $this->tnotification->set_rules('guru_id', 'Pengurus', 'trim|required');
        $this->tnotification->set_rules('jabatan_id', 'Jabatan Pengurus', 'trim|required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('sp_st', 'Status', 'trim');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('struktural_id'),
                $this->input->post('guru_id'),
                $this->input->post('jabatan_id'),
                $this->input->post('tanggal_mulai'),
                $this->input->post('tanggal_selesai'),
                $this->input->post('sp_st'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_struktural_pengurus->insert_struktural_pengurus($params)) {
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
        redirect("master/struktural_pengurus/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "master/struktural_pengurus/edit.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // user data
        $result = $this->m_struktural_pengurus->get_struktural_pengurus_by_id($param);
        // send data
        $this->smarty->assign("result", $result);
        // get list data
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah());
        $this->smarty->assign("rs_struktural", $this->m_jabatanstruktural->get_all_jabatanstruktural());
        $this->smarty->assign("rs_jabatan", $this->m_jabatanpengurus->get_all_jabatanpengurus());

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
        $this->tnotification->set_rules('struktural_id', 'Jabatan Struktural', 'trim|required');
        $this->tnotification->set_rules('guru_id', 'Pengurus', 'trim|required');
        $this->tnotification->set_rules('jabatan_id', 'Jabatan Pengurus', 'trim|required');
        $this->tnotification->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('tanggal_selesai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('sp_st', 'Status', 'trim');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('struktural_id'),
                $this->input->post('guru_id'),
                $this->input->post('jabatan_id'),
                $this->input->post('tanggal_mulai'),
                $this->input->post('tanggal_selesai'),
                $this->input->post('sp_st'),
                $this->com_user['user_id'],
                $this->input->post('sp_id')
            );
            // insert
            if ($this->m_struktural_pengurus->update_struktural_pengurus($params)) {
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
        redirect("master/struktural_pengurus/edit/" . $this->input->post('sp_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_struktural_pengurus->delete_struktural_pengurus($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/struktural_pengurus/");
    }

}
