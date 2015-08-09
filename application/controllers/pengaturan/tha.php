<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --
class tha extends ApplicationBase {

    function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('pengaturan/m_tha');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/tha/list.html");

        // session
        $search = $this->tsession->userdata('search_tha');
        $this->smarty->assign('search', $search);

        // params
        $tahun_ajaran = !empty($search['tha_tahun_ajaran']) ? "%" . $search['tha_tahun_ajaran'] . "%" : "%";
        $params = array($tahun_ajaran);

        // pagination
        $config['base_url'] = site_url("pengaturan/tha/index/");
        $config['total_rows'] = $this->m_tha->get_total_tahun_ajaran($params);

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
        $params = array($tahun_ajaran, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_tha->get_list_tahun_ajaran($params));
        // notification

        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // form tambah tahun ajaran 
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/tha/add.html");

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah tahun ajaran
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('first_year', 'Tahun Ajaran Pertama', 'trim|required|max_length[4]|numeric');
        $this->tnotification->set_rules('second_year', 'Tahun Ajaran Kedua', 'trim|required|max_length[4]|numeric');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $strTahun = $this->input->post('first_year') . "-" . $this->input->post('second_year');
            $params = array(
                $strTahun
            );
            // insert
            if ($this->m_tha->insert_tahun_ajaran($params)) {
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
        redirect("pengaturan/tha/add");
    }

    // pencarian
    public function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "tha_tahun_ajaran" => $this->input->post('tha_tahun_ajaran')
            );
            // set
            $this->tsession->set_userdata('search_tha', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_tha');
        }
        //--
        redirect('pengaturan/tha');
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_tha->get_tahun_ajaran_by_id($param);
        $arrTahun = explode('-', $result['tha_tahun_ajaran']);

        // set template content
        $this->smarty->assign("template_content", "pengaturan/tha/edit.html");
        // send data
        $this->smarty->assign("result", $result);
        $this->smarty->assign("tahun", $arrTahun);
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
        $this->tnotification->set_rules('first_year', 'Tahun Ajaran Pertama', 'trim|required|max_length[4]|numeric');
        $this->tnotification->set_rules('second_year', 'Tahun Ajaran Kedua', 'trim|required|max_length[4]|numeric');
        $this->tnotification->set_rules('tha_aktif', 'Status Tahun Ajaran', 'trim|required');
        $this->tnotification->set_rules('tha_semester', 'Semester', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $strTahun = $this->input->post('first_year') . "-" . $this->input->post('second_year');
            $params = array(
                $strTahun,
                $this->input->post('tha_semester'),
                $this->input->post('tha_aktif'),
                $this->input->post('tha_id')
            );
            // insert
            if ($this->m_tha->update_tahun_ajaran($params)) {
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
        redirect("pengaturan/tha/edit/" . $this->input->post('tha_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_tha->delete_tahun_ajaran($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("pengaturan/tha/");
    }

    function get_jalur_available() {
        $result = $this->m_tha->get_tahun_ajaran_group_by_tha($this->input->post('tha'));
        header('Content-Type: application/json');
        echo json_encode($result);
    }

}
