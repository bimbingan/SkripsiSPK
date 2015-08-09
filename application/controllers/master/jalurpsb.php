<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jalurpsb extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_jalurpsb');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/jalurpsb/list.html");
        // session
        $search = $this->tsession->userdata('search_jalurpsb');
        $this->smarty->assign('search', $search);

        // params
        $jalurpsb_nama = !empty($search['jalurpsb_nama']) ? "%" . $search['jalurpsb_nama'] . "%" : "%";
        $params = array($jalurpsb_nama);

        // pagination
        $config['base_url'] = site_url("master/jalurpsb/index/");
        $config['total_rows'] = $this->m_jalurpsb->get_total_jalurpsb($params);

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
        $params = array($jalurpsb_nama, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_jalurpsb->get_list_jalurpsb($params));
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
                "jalurpsb_nama" => $this->input->post('jalurpsb_nama'),
            );
            // set
            $this->tsession->set_userdata('search_jalurpsb', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_jalurpsb');
        }
        //--
        redirect('master/jalurpsb');
    }

    // form tambah jalur psb
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/jalurpsb/add.html");

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah jaluir psb
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('jalurpsb_nama', 'Nama Jalur PSB', 'trim|required|max_length[255]');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('jalurpsb_nama'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_jalurpsb->insert_jalurpsb($params)) {
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
        redirect("master/jalurpsb/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_jalurpsb->get_jalurpsb_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/jalurpsb/edit.html");
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
        $this->tnotification->set_rules('jalurpsb_nama', 'Nama Jalur PSB', 'trim|required|max_length[255]');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('jalurpsb_nama'),
                $this->com_user['user_id'],
                $this->input->post('jalurpsb_id')
            );
            // insert
            if ($this->m_jalurpsb->update_jalurpsb($params)) {
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
        redirect("master/jalurpsb/edit/" . $this->input->post('jalurpsb_id'));
    }

    // delete jalur psb
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_jalurpsb->delete_jalurpsb($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/jalurpsb/");
    }

}
