<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jabatanpengurus extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_jabatanpengurus');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/jabatanpengurus/list.html");

        // session
        $search = $this->tsession->userdata('search_jabatanpengurus');
        $this->smarty->assign('search', $search);

        // params
        $jabatan_nama = !empty($search['jabatan_nama']) ? "%" . $search['jabatan_nama'] . "%" : "%";
        $jabatan_induk = isset($search['jabatan_induk']) ? "%" . $search['jabatan_induk'] . "%" : "%";

        $params = array($jabatan_nama, $jabatan_induk);

        // pagination
        $config['base_url'] = site_url("master/jabatanpengurus/index/");
        $config['total_rows'] = $this->m_jabatanpengurus->get_total_jabatanpengurus($params);

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
        $params = array($jabatan_nama, $jabatan_induk, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_jabatanpengurus->get_list_jabatanpengurus($params));

        // jabatan induk
        $this->smarty->assign('rs_jabatan_induk', $this->m_jabatanpengurus->get_all_jabatanpengurus());

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
                "jabatan_nama" => $this->input->post('jabatan_nama'),
                "jabatan_induk" => $this->input->post('jabatan_induk'),
            );
            // set
            $this->tsession->set_userdata('search_jabatanpengurus', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_jabatanpengurus');
        }
        //--
        redirect('master/jabatanpengurus');
    }

    // form tambah jabatan pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/jabatanpengurus/add.html");

        // jabatan induk
        $this->smarty->assign('rs_jabatan_induk', $this->m_jabatanpengurus->get_all_jabatanpengurus());

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
        $this->tnotification->set_rules('jabatan_nama', 'Nama Jabatan Pengurus', 'trim|required|max_length[150]');
        $this->tnotification->set_rules('jabatan_induk', 'Induk Jabatan Pengurus', 'trim');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('jabatan_nama'),
                $this->input->post('jabatan_induk'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_jabatanpengurus->insert_jabatanpengurus($params)) {
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
        redirect("master/jabatanpengurus/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_jabatanpengurus->get_jabatanpengurus_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/jabatanpengurus/edit.html");
        // send data
        $this->smarty->assign("result", $result);
        // jabatan induk
        $this->smarty->assign('rs_jabatan_induk', $this->m_jabatanpengurus->get_jabatanpengurus_except_id($param));
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
        $this->tnotification->set_rules('jabatan_nama', 'Nama Jabatan Pengurus', 'trim|required|max_length[150]');
        $this->tnotification->set_rules('jabatan_induk', 'Induk Jabatan Pengurus', 'trim');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('jabatan_nama'),
                $this->input->post('jabatan_induk'),
                $this->com_user['user_id'],
                $this->input->post('jabatan_id')
            );
            // insert
            if ($this->m_jabatanpengurus->update_jabatanpengurus($params)) {
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
        redirect("master/jabatanpengurus/edit/" . $this->input->post('jabatan_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if (!$this->m_jabatanpengurus->is_induk_used($param)) {
            if ($this->m_jabatanpengurus->delete_jabatanpengurus($param)) {
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data gagal dihapus");
            }
        } else {
            $this->tnotification->sent_notification("error", "Data gagal dihapus, jabatan masih dipakai sebagai induk oleh jabatan lain");
        }
        redirect("master/jabatanpengurus/");
    }

}
