<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class sekolah extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_sekolah');
        $this->load->model('pengaturan/m_preference');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/sekolah/list.html");

        // session
        $search = $this->tsession->userdata('search_sekolah');
        $this->smarty->assign('search', $search);

        // params
        $sekolah_nama = !empty($search['sekolah_nama']) ? "%" . $search['sekolah_nama'] . "%" : "%";
        $params = array($sekolah_nama);

        // pagination
        $config['base_url'] = site_url("master/sekolah/index/");
        $config['total_rows'] = $this->m_sekolah->get_total_sekolah($params);

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
        $params = array($sekolah_nama, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_sekolah->get_list_sekolah($params));
        // tingkat
        $this->smarty->assign('rs_jenjang', $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'pendidikan')));
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
                "sekolah_nama" => $this->input->post('sekolah_nama')
            );
            // set
            $this->tsession->set_userdata('search_sekolah', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_sekolah');
        }
        //--
        redirect('master/sekolah');
    }

    // form tambah sekolah 
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/sekolah/add.html");
        // tingkat
        $this->smarty->assign('rs_jenjang', $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'pendidikan')));
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
        $this->tnotification->set_rules('sekolah_nama', 'Nama Sekolah', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('sekolah_alamat', 'Alamat Sekolah', 'trim');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('sekolah_nama'),
                $this->input->post('sekolah_alamat'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_sekolah->insert_sekolah($params)) {
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
        redirect("master/sekolah/add");
    }

    // form edit sekolah
    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_sekolah->get_sekolah_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/sekolah/edit.html");
        // send data
        $this->smarty->assign("result", $result);
        // jenjang
        $this->smarty->assign('rs_jenjang', $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'pendidikan')));
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
        $this->tnotification->set_rules('sekolah_nama', 'Nama Sekolah', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('sekolah_alamat', 'Alamat Sekolah', 'trim');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('sekolah_nama'),
                $this->input->post('sekolah_alamat'),
                $this->com_user['user_id'],
                $this->input->post('sekolah_id')
            );
            // insert
            if ($this->m_sekolah->update_sekolah($params)) {
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
        redirect("master/sekolah/edit/" . $this->input->post('sekolah_id'));
    }

    // delete sekolah
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_sekolah->delete_sekolah($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/sekolah/");
    }

}
