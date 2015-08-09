<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class kabupaten extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_kabupaten');
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
        $this->smarty->assign("template_content", "master/kabupaten/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // session
        $search = $this->tsession->userdata('search_kabupaten');
        $this->smarty->assign('search', $search);

        // params
        $kabupaten_nm = !empty($search['kabupaten_nm']) ? "%" . $search['kabupaten_nm'] . "%" : "%";
        $provinsi_id = !empty($search['provinsi_id']) ? "%" . $search['provinsi_id'] . "%" : "%";
        $params = array($kabupaten_nm, $provinsi_id);

        // pagination
        $config['base_url'] = site_url("master/kabupaten/index/");
        $config['total_rows'] = $this->m_kabupaten->get_total_kabupaten($params);

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
        $params = array($kabupaten_nm, $provinsi_id, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_kabupaten->get_list_kabupaten($params));
        $this->smarty->assign("rs_provinsi", $this->m_provinsi->get_all_provinsi());
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
                "kabupaten_nm" => $this->input->post('kabupaten_nm'),
                "provinsi_id" => $this->input->post('provinsi_id'),
            );
            // set
            $this->tsession->set_userdata('search_kabupaten', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_kabupaten');
        }
        //--
        redirect('master/kabupaten');
    }

    // form tambah jenis pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/kabupaten/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // get list data
        $this->smarty->assign("rs_provinsi", $this->m_provinsi->get_all_provinsi());
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
        $this->tnotification->set_rules('kab_nm', 'Nama Kabupaten', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('provinsi_id', 'Provinsi', 'trim|required');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('kab_nm'),
                $this->input->post('provinsi_id'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_kabupaten->insert_kabupaten($params)) {
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
        redirect("master/kabupaten/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_kabupaten->get_kabupaten_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/kabupaten/edit.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // get list data
        $this->smarty->assign("rs_provinsi", $this->m_provinsi->get_all_provinsi());
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
        $this->tnotification->set_rules('kab_nm', 'Nama Kabupaten', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('provinsi_id', 'Provinsi', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('kab_nm'),
                $this->input->post('provinsi_id'),
                $this->com_user['user_id'],
                $this->input->post('kab_id')
            );
            // insert
            if ($this->m_kabupaten->update_kabupaten($params)) {
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
        redirect("master/kabupaten/edit/" . $this->input->post('kab_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_kabupaten->delete_kabupaten($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/kabupaten/");
    }

}
