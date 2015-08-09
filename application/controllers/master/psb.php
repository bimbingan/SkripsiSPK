<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class psb extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_psb');
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
        $this->smarty->assign("template_content", "master/psb/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // session
        $search = $this->tsession->userdata('search_psb');
        $this->smarty->assign('search', $search);

        // params
        $psb_tha = !empty($search['psb_tha']) ? "%" . $search['psb_tha'] . "%" : "%";
        $jalurpsb_id = !empty($search['jalurpsb_id']) ? "%" . $search['jalurpsb_id'] . "%" : "%";
        $psb_st = !empty($search['psb_st']) ? "%" . $search['psb_st'] . "%" : "%";
        $params = array($psb_tha, $jalurpsb_id, $psb_st);

        // pagination
        $config['base_url'] = site_url("master/psb/index/");
        $config['total_rows'] = $this->m_psb->get_total_psb($params);

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
        $params = array($psb_tha, $jalurpsb_id, $psb_st, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_psb->get_list_psb($params));
        // tingkat
        $this->smarty->assign('rs_tha', $this->m_tha->get_tahun_ajaran_group_by_tha());
        $this->smarty->assign('rs_jalur', $this->m_jalurpsb->get_all_jalurpsb());
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
                "psb_tha" => $this->input->post('psb_tha'),
                "jalurpsb_id" => $this->input->post('jalurpsb_id'),
                "psb_st" => $this->input->post('psb_st')
            );
            // set
            $this->tsession->set_userdata('search_psb', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_psb');
        }
        //--
        redirect('master/psb');
    }

    // form tambah psb 
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/psb/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // tingkat
        $this->smarty->assign('rs_tha', $this->m_tha->get_tahun_ajaran_group_by_tha());
        $this->smarty->assign('rs_jalur', $this->m_jalurpsb->get_all_jalurpsb());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah psb
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('psb_tha', 'Tahun Ajaran', 'trim|required|max_length[9]');
        $this->tnotification->set_rules('jalurpsb_id', 'Jalur PSB', 'trim|required');
        $this->tnotification->set_rules('min_nilai', 'Minimal Nilai', 'trim|numeric');
        $this->tnotification->set_rules('psb_tglmulai', 'Tanggal Mulai', 'trim');
        $this->tnotification->set_rules('psb_tglakhir', 'Tanggal Berakhir', 'trim');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $psb_tha = $this->input->post('psb_tha');
            $jalurpsb_id = $this->input->post('jalurpsb_id');
            if (!$this->m_psb->is_psb_exist(array($psb_tha, $jalurpsb_id))) {

                $params = array(
                    'psb_tha' => $psb_tha,
                    'jalurpsb_id' => $jalurpsb_id,
                    'min_nilai' => $this->input->post('min_nilai'),
                    'psb_st' => 'start',
                    'psb_tglmulai' => $this->input->post('psb_tglmulai'),
                    'psb_tglakhir' => $this->input->post('psb_tglakhir'),
                    'mdb' => $this->com_user['user_id'],
                    'mdd' => date('Y-m-d')
                );

                // insert
                if ($this->m_psb->insert_psb($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data PSB yang anda masukkan sudah ada");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/psb/add");
    }

    // form edit psb
    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");

        // set template content
        $this->smarty->assign("template_content", "master/psb/edit.html");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // user data
        $result = $this->m_psb->get_psb_by_id($param);
        // send data
        $this->smarty->assign("result", $result);

        $this->smarty->assign('rs_tha', $this->m_tha->get_tahun_ajaran_group_by_tha());
        $this->smarty->assign('rs_jalur', $this->m_jalurpsb->get_all_jalurpsb());

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
        $this->tnotification->set_rules('psb_tha', 'Tahun Ajaran', 'trim|required|max_length[9]');
        $this->tnotification->set_rules('jalurpsb_id', 'Jalur PSB', 'trim|required');
        $this->tnotification->set_rules('min_nilai', 'Minimal Nilai', 'trim|numeric');
        $this->tnotification->set_rules('psb_st', 'Status', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $psb_tha = $this->input->post('psb_tha');
            $jalurpsb_id = $this->input->post('jalurpsb_id');
            $psb_id = $this->input->post('psb_id');

            if (!$this->m_psb->is_psb_exist(array($psb_tha, $jalurpsb_id, $psb_id), $psb_id)) {

                $params = array(
                    'psb_tha' => $psb_tha,
                    'jalurpsb_id' => $jalurpsb_id,
                    'min_nilai' => $this->input->post('min_nilai'),
                    'psb_st' => $this->input->post('psb_st'),
                    'psb_tglmulai' => $this->input->post('psb_tglmulai'),
                    'psb_tglakhir' => $this->input->post('psb_tglakhir'),
                    'mdb' => $this->com_user['user_id'],
                    'mdd' => date('Y-m-d')
                );
                $where = array(
                    'psb_id' => $this->input->post('psb_id')
                );
                // insert
                if ($this->m_psb->update_psb($params, $where)) {

                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Data PSB yang anda masukkan sudah ada");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/psb/edit/" . $this->input->post('psb_id'));
    }

    // delete psb
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_psb->delete_psb($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("master/psb/");
    }

}
