<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class unitkerja extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_unitkerja');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/unitkerja/list.html");
        // session
        $search = $this->tsession->userdata('search_unitkerja');
        $this->smarty->assign('search', $search);

        // params
        $unit_nm = !empty($search['unit_nm']) ? "%" . $search['unit_nm'] . "%" : "%";
        $params = array($unit_nm);

        // pagination
        $config['base_url'] = site_url("master/unitkerja/index/");
        $config['total_rows'] = $this->m_unitkerja->get_total_unitkerja($params);

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
        $params = array($unit_nm, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_unitkerja->get_list_unitkerja($params));
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
                "unit_nm" => $this->input->post('unit_nm'),
            );
            // set
            $this->tsession->set_userdata('search_unitkerja', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_unitkerja');
        }
        //--
        redirect('master/unitkerja');
    }

    // form tambah jenis pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/unitkerja/add.html");

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
        $this->tnotification->set_rules('unit_nm', 'Status Pengurus', 'trim|required|max_length[100]');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('unit_nm'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_unitkerja->insert_unitkerja($params)) {
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
        redirect("master/unitkerja/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_unitkerja->get_unitkerja_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/unitkerja/edit.html");
        // send data
        $this->smarty->assign("result", $result);
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
        $this->tnotification->set_rules('unit_nm', 'Status Pengurus', 'trim|required|max_length[100]');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('unit_nm'),
                $this->com_user['user_id'],
                $this->input->post('unit_id')
            );
            // insert
            if ($this->m_unitkerja->update_unitkerja($params)) {
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
        redirect("master/unitkerja/edit/" . $this->input->post('unit_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_unitkerja->delete_unitkerja($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/unitkerja/");
    }

}
