<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jurusan extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_jurusan');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/jurusan/list.html");
        // session
        $search = $this->tsession->userdata('search_jurusan');
        $this->smarty->assign('search', $search);

        // params
        $jurusan_nm = !empty($search['jurusan_nm']) ? "%" . $search['jurusan_nm'] . "%" : "%";
        $params = array($jurusan_nm);

        // pagination
        $config['base_url'] = site_url("master/jurusan/index/");
        $config['total_rows'] = $this->m_jurusan->get_total_jurusan($params);

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
        $params = array($jurusan_nm, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_jurusan->get_list_jurusan($params));

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
                "jurusan_nm" => $this->input->post('jurusan_nm'),
            );
            // set
            $this->tsession->set_userdata('search_jurusan', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_jurusan');
        }
        //--
        redirect('master/jurusan/');
    }

    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/jurusan/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah jurusan pendaftaran
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('jurusan_nm', 'Nama Tingkat', 'trim|required|max_length[150]');
        $this->tnotification->set_rules('kode_mapel', 'Kode Mapel', 'trim|max_length[3]');
        $this->tnotification->set_rules('kode_kelas', 'Kode Kelas', 'trim|max_length[4]');
        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'jurusan_id' => $this->m_jurusan->get_new_id(),
                'jurusan_nm' => $this->input->post('jurusan_nm'),
                'kode_mapel' => $this->input->post('kode_mapel'),
                'kode_kelas' => $this->input->post('kode_kelas'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            // insert
            if ($this->m_jurusan->insert_jurusan($params)) {
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
        redirect("master/jurusan/add");
    }

    function edit($params) {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "master/jurusan/edit.html");

        // load data jurusan
        $this->smarty->assign("result", $this->m_jurusan->get_jurusan_by_id($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process ubah jurusan
    function process_edit() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('jurusan_nm', 'Nama Tingkat', 'trim|required|max_length[150]');
        $this->tnotification->set_rules('kode_mapel', 'Kode Mapel', 'trim|max_length[3]');
        $this->tnotification->set_rules('kode_kelas', 'Kode Kelas', 'trim|max_length[4]');

        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'jurusan_nm' => $this->input->post('jurusan_nm'),
                'kode_mapel' => $this->input->post('kode_mapel'),
                'kode_kelas' => $this->input->post('kode_kelas'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            $where = array(
                'jurusan_id' => $this->input->post('jurusan_id')
            );
            // update
            if ($this->m_jurusan->update_jurusan($params, $where)) {
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
        redirect("master/jurusan/edit/" . $this->input->post('jurusan_id'));
    }

    // delete mapel group
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_jurusan->delete_jurusan($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/jurusan/");
    }

}
