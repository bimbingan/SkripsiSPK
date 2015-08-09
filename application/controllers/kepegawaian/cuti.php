<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class cuti extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('kepegawaian/m_cuti');
        $this->load->model('master/m_pengurussekolah');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // session
        $search = $this->tsession->userdata('search_cuti');
        $this->smarty->assign('search', $search);

        // params
        $guru_id = !empty($search['guru_id']) ? "%" . $search['guru_id'] . "%" : "%";
        $params = array($guru_id);

        // pagination
        $config['base_url'] = site_url("kepegawaian/cuti/index/");
        $config['total_rows'] = $this->m_cuti->get_total_cuti($params);

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
        $params = array($guru_id, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_cuti->get_list_cuti($params));
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah($params));
        // set localization
        setlocale(LC_TIME, 'id');
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
                "guru_id" => $this->input->post('guru_id')
            );
            // set
            $this->tsession->set_userdata('search_cuti', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_cuti');
        }
        //--
        redirect('kepegawaian/cuti');
    }

    // form tambah cuti
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // get list data
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah($params));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah cuti
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('guru_id', 'Pengurus', 'trim|required|number');
        $this->tnotification->set_rules('cuti_start', 'Tanggal Mulai Cuti', 'trim|required');
        $this->tnotification->set_rules('cuti_end', 'Tanggal Selesai Cuti', 'trim|required');
        $this->tnotification->set_rules('cuti_keterangan', 'Keterangan Cuti', 'trim');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'cuti_start' => $this->input->post('cuti_start'),
                'cuti_end' => $this->input->post('cuti_end'),
                'cuti_keterangan' => $this->input->post('cuti_keterangan'),
                'guru_id' => $this->input->post('guru_id'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date("Y-m-d H:i:s")
            );
            // insert
            if ($this->m_cuti->insert_cuti($params)) {
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
        redirect("kepegawaian/cuti/add");
    }

    // form edit cuti
    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // cuti data
        $result = $this->m_cuti->get_cuti_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/edit.html");
        // send data
        $this->smarty->assign("result", $result);
        // get list data
        $this->smarty->assign("rs_pengurus", $this->m_pengurussekolah->get_all_pengurussekolah());
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process edit cuti
    function process_edit() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('guru_id', 'Pengurus', 'trim|required|number');
        $this->tnotification->set_rules('cuti_start', 'Tanggal Mulai Cuti', 'trim|required');
        $this->tnotification->set_rules('cuti_end', 'Tanggal Selesai Cuti', 'trim|required');
        $this->tnotification->set_rules('cuti_keterangan', 'Keterangan Cuti', 'trim');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'cuti_start' => $this->input->post('cuti_start'),
                'cuti_end' => $this->input->post('cuti_end'),
                'cuti_keterangan' => $this->input->post('cuti_keterangan'),
                'guru_id' => $this->input->post('guru_id'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date("Y-m-d H:i:s")
            );
            $where = array(
                'cuti_id' => $this->input->post('cuti_id')
            );
            // insert
            if ($this->m_cuti->update_cuti($params, $where)) {

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
        redirect("kepegawaian/cuti/edit/" . $this->input->post('cuti_id'));
    }

    // process delete cuti
    function delete($params) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_cuti->delete_cuti($params)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("kepegawaian/cuti/");
    }

    // view cuti as calendar
    function kalender() {
        // set page rules
        $this->_set_page_rule("R");
        // load js
        $this->smarty->load_javascript("resource/js/fullcalendar/lib/moment.min.js");
        $this->smarty->load_javascript("resource/js/fullcalendar/fullcalendar.min.js");
        $this->smarty->load_javascript("resource/js/fullcalendar/lang/id.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        $this->smarty->load_style("fullcalendar/fullcalendar.css");
        $this->smarty->load_style("fullcalendar/fullcalendar.print.css", "print");
        // set template content
        $this->smarty->assign("template_content", "kepegawaian/cuti/kalender.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // get json data for fullcalendar
    function get_json_data() {
        $result = $this->m_cuti->get_all_cuti_for_kalender();
        $arrResult = array();
        foreach ($result as $rs) {
            $arrResult[] = array(
                'id' => $rs['cuti_id'],
                'title' => $rs['guru_nama'],
                'start' => $rs['cuti_start'],
                'end' => $rs['cuti_end_fixed'],
                'editable' => false
            );
        }
        echo json_encode($arrResult);
    }
}