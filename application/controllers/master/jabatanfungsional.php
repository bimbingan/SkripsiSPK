<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class jabatanfungsional extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_jabatanfungsional');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/jabatanfungsional/list.html");

        // session
        $search = $this->tsession->userdata('search_jabatanfungsional');
        $this->smarty->assign('search', $search);

        // params
        $fungsional_tingkat = !empty($search['fungsional_tingkat']) ? "%" . $search['fungsional_tingkat'] . "%" : "%";
        $fungsional_nama = !empty($search['fungsional_nama']) ? "%" . $search['fungsional_nama'] . "%" : "%";

        $params = array($fungsional_tingkat, $fungsional_nama);

        $config['base_url'] = site_url("master/jabatanfungsional/index/");
        $config['total_rows'] = $this->m_jabatanfungsional->get_total_jabatanfungsional($params);

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
        $params = array($fungsional_tingkat, $fungsional_nama, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_jabatanfungsional->get_list_jabatanfungsional($params));
        $this->smarty->assign("rs_first_last", $this->m_jabatanfungsional->get_first_and_last_order());
        $this->smarty->assign("rs_tingkat", $this->m_jabatanfungsional->get_all_jabatanfungsional());

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
                "fungsional_tingkat" => $this->input->post('fungsional_tingkat'),
                "fungsional_nama" => $this->input->post('fungsional_nama'),
            );

            // set
            $this->tsession->set_userdata('search_jabatanfungsional', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_jabatanfungsional');
        }
        //--
        redirect('master/jabatanfungsional');
    }

    // form tambah jabatan pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/jabatanfungsional/add.html");

        // jabatan induk
        $this->smarty->assign('rs_fungsional_nama', $this->m_jabatanfungsional->get_all_jabatanfungsional());

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
        $this->tnotification->set_rules('fungsional_tingkat', 'Tingkat Jabatan Fungsional', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('fungsional_nama', 'Nama Jabatan Fungsional', 'trim|required|max_length[200]');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('fungsional_tingkat'),
                $this->input->post('fungsional_nama'),
                $this->m_jabatanfungsional->get_new_order(),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_jabatanfungsional->insert_jabatanfungsional($params)) {
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
        redirect("master/jabatanfungsional/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_jabatanfungsional->get_jabatanfungsional_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/jabatanfungsional/edit.html");
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
        $this->tnotification->set_rules('fungsional_tingkat', 'Tingkat Jabatan Fungsional', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('fungsional_nama', 'Nama Jabatan Fungsional', 'trim|required|max_length[200]');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('fungsional_tingkat'),
                $this->input->post('fungsional_nama'),
                $this->com_user['user_id'],
                $this->input->post('fungsional_id')
            );
            // insert
            if ($this->m_jabatanfungsional->update_jabatanfungsional($params)) {
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
        redirect("master/jabatanfungsional/edit/" . $this->input->post('fungsional_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");

        if ($this->m_jabatanfungsional->delete_jabatanfungsional($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("master/jabatanfungsional/");
    }

    // up
    function up($params) {
        // set page rules
        $this->_set_page_rule("U");
        $operator = '-';
        if ($this->m_jabatanfungsional->update_urutan($params, $operator)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        redirect("master/jabatanfungsional/");
    }

    function down($params) {
        // set page rules
        $this->_set_page_rule("U");
        $operator = '+';
        if ($this->m_jabatanfungsional->update_urutan($params, $operator)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        redirect("master/jabatanfungsional/");
    }

}
