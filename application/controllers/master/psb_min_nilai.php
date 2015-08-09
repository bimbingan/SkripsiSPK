<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class psb_min_nilai extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_psb_min_nilai');
        $this->load->model('master/m_jalurpsb');
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
        $this->smarty->assign("template_content", "master/psb_min_nilai/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css                                                             
        $this->smarty->load_style("select2/select2.css");
        // session
        $search = $this->tsession->userdata('search_psb_min_nilai');
        $this->smarty->assign('search', $search);

        // params
        $psb_min_nilai_tha = !empty($search['psb_min_nilai_tha']) ? "%" . $search['psb_min_nilai_tha'] . "%" : "%";
        $jalurpsb_min_nilai_id = !empty($search['jalurpsb_min_nilai_id']) ? "%" . $search['jalurpsb_min_nilai_id'] . "%" : "%";
        $psb_min_nilai_st = !empty($search['psb_min_nilai_st']) ? "%" . $search['psb_min_nilai_st'] . "%" : "%";
        $params = array($psb_min_nilai_tha, $jalurpsb_min_nilai_id, $psb_min_nilai_st);

        // pagination
        $config['base_url'] = site_url("master/psb_min_nilai/index/");
        $config['total_rows'] = $this->m_psb_min_nilai->get_total_psb_min_nilai($params);

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
        $params = array($psb_min_nilai_tha, $jalurpsb_min_nilai_id, $psb_min_nilai_st, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_psb_min_nilai->get_list_psb_min_nilai($params));
        // tingkat
        $this->smarty->assign('rs_tha', $this->m_tha->get_tahun_ajaran_group_by_tha());
        $this->smarty->assign('rs_jalur', $this->m_jalurpsb_min_nilai->get_all_jalurpsb_min_nilai());
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
                "psb_min_nilai_tha" => $this->input->post('psb_min_nilai_tha'),
                "jalurpsb_min_nilai_id" => $this->input->post('jalurpsb_min_nilai_id'),
                "psb_min_nilai_st" => $this->input->post('psb_min_nilai_st')
            );
            // set
            $this->tsession->set_userdata('search_psb_min_nilai', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_psb_min_nilai');
        }
        //--
        redirect('master/psb_min_nilai');
    }

    // form tambah psb_min_nilai 
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/psb_min_nilai/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // tingkat
        $this->smarty->assign('rs_tha', $this->m_tha->get_tahun_ajaran_group_by_tha());
        $this->smarty->assign('rs_jalur', $this->m_jalurpsb_min_nilai->get_all_jalurpsb_min_nilai());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah psb_min_nilai
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('psb_min_nilai_tha', 'Tahun Ajaran', 'trim|required|max_length[9]');
        $this->tnotification->set_rules('jalurpsb_min_nilai_id', 'Jalur PSB', 'trim|required');
        $this->tnotification->set_rules('min_nilai', 'Minimal Nilai', 'trim|numeric');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('psb_min_nilai_tha'),
                $this->input->post('jalurpsb_min_nilai_id'),
                $this->input->post('min_nilai'),
                'start',
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_psb_min_nilai->insert_psb_min_nilai($params)) {
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
        redirect("master/psb_min_nilai/add");
    }

    // form edit psb_min_nilai
    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");

        // set template content
        $this->smarty->assign("template_content", "master/psb_min_nilai/edit.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // user data
        $result = $this->m_psb_min_nilai->get_psb_min_nilai_by_id($param);
        // send data
        $this->smarty->assign("result", $result);

        $this->smarty->assign('rs_tha', $this->m_tha->get_tahun_ajaran_group_by_tha());
        $this->smarty->assign('rs_jalur', $this->m_jalurpsb_min_nilai->get_all_jalurpsb_min_nilai());

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
        $this->tnotification->set_rules('psb_min_nilai_tha', 'Tahun Ajaran', 'trim|required|max_length[9]');
        $this->tnotification->set_rules('jalurpsb_min_nilai_id', 'Jalur PSB', 'trim|required');
        $this->tnotification->set_rules('min_nilai', 'Minimal Nilai', 'trim|numeric');
        $this->tnotification->set_rules('psb_min_nilai_st', 'Status', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('psb_min_nilai_tha'),
                $this->input->post('jalurpsb_min_nilai_id'),
                $this->input->post('min_nilai'),
                $this->input->post('psb_min_nilai_st'),
                $this->input->post('psb_min_nilai_id')
            );
            // insert
            if ($this->m_psb_min_nilai->update_psb_min_nilai($params)) {

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
        redirect("master/psb_min_nilai/edit/" . $this->input->post('psb_min_nilai_id'));
    }

    // delete psb_min_nilai
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_psb_min_nilai->delete_psb_min_nilai($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/psb_min_nilai/");
    }

}
