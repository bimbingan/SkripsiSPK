<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class provinsi extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_provinsi');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/provinsi/list.html");
        // session
        $search = $this->tsession->userdata('search_provinsi');
        $this->smarty->assign('search', $search);

        // params
        $provinsi_nm = !empty($search['provinsi_nm']) ? "%" . $search['provinsi_nm'] . "%" : "%";
        $params = array($provinsi_nm);

        // pagination
        $config['base_url'] = site_url("master/provinsi/index/");
        $config['total_rows'] = $this->m_provinsi->get_total_provinsi($params);

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
        $params = array($provinsi_nm, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_provinsi->get_list_provinsi($params));
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
                "provinsi_nm" => $this->input->post('provinsi_nm'),
            );
            // set
            $this->tsession->set_userdata('search_provinsi', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_provinsi');
        }
        //--
        redirect('master/provinsi');
    }

    // form tambah jenis pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/provinsi/add.html");

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
        $this->tnotification->set_rules('provinsi_nm', 'Status Pengurus', 'trim|required|max_length[100]');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('provinsi_nm'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_provinsi->insert_provinsi($params)) {
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
        redirect("master/provinsi/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_provinsi->get_provinsi_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/provinsi/edit.html");
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
        $this->tnotification->set_rules('provinsi_nm', 'Status Pengurus', 'trim|required|max_length[100]');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('provinsi_nm'),
                $this->com_user['user_id'],
                $this->input->post('provinsi_id')
            );
            // insert
            if ($this->m_provinsi->update_provinsi($params)) {
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
        redirect("master/provinsi/edit/" . $this->input->post('provinsi_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_provinsi->delete_provinsi($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/provinsi/");
    }

}
