<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class kalender extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('akademik/m_kalender');
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
        $this->smarty->assign("template_content", "akademik/kalender/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // session
        $search = $this->tsession->userdata('search_kalender_akademik');
        $this->smarty->assign('search', $search);

        // params
        $kalender_type = !empty($search['kalender_type']) ? "%" . $search['kalender_type'] . "%" : "%";
        $tgl_start = !empty($search['tgl_start']) ? $search['tgl_start'] : "%";
        $tgl_end = !empty($search['tgl_end']) ? $search['tgl_end'] : "%";
        $params = array($kalender_type, $tgl_start, $tgl_end);

        // pagination
        $config['base_url'] = site_url("akademik/kalender/index/");
        $config['total_rows'] = $this->m_kalender->get_total_kalender($params);

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
        $params = array($kalender_type, $tgl_start, $tgl_end, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_kalender->get_list_kalender($params));
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
                "tgl_start" => $this->input->post('tgl_start'),
                "tgl_end" => $this->input->post('tgl_end'),
                "kalender_type" => $this->input->post('kalender_type')
            );
            // set
            $this->tsession->set_userdata('search_kalender_akademik', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_kalender_akademik');
        }
        //--
        redirect('akademik/kalender');
    }

    // form tambah kalender
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "akademik/kalender/add.html");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah kalender
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('tgl_start', 'Tanggal Awal', 'trim|required');
        $this->tnotification->set_rules('tgl_end', 'Tanggal Akhir', 'trim|required');
        $this->tnotification->set_rules('kalender_keterangan', 'Keterangan', 'trim|required');
        $this->tnotification->set_rules('kalender_type', 'Jenis Kalender', 'trim|required');
        // process

        if ($this->tnotification->run() !== FALSE) {
            // pre process data
            $tgl_start = date_create($this->input->post('tgl_start'));
            $tgl_end = date_create($this->input->post('tgl_end'));
            $datediff = date_diff($tgl_end, $tgl_start);
            $count = $datediff->days;
            // -- end of process data
            $params = array(
                $this->input->post('tgl_start'),
                $this->input->post('kalender_keterangan'),
                $this->input->post('kalender_type'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_kalender->insert_kalender($params, $count)) {
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
        redirect("akademik/kalender/add");
    }

    // form edit kalender
    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // kalender data
        $result = $this->m_kalender->get_kalender_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "akademik/kalender/edit.html");
        // send data
        $this->smarty->assign("result", $result);
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process edit kalender
    function process_edit() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('tgl_start', 'Tanggal Awal', 'trim|required');
        $this->tnotification->set_rules('tgl_end', 'Tanggal Akhir', 'trim|required');
        $this->tnotification->set_rules('kalender_keterangan', 'Keterangan', 'trim|required');
        $this->tnotification->set_rules('kalender_type', 'Jenis Kalender', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            // pre process data
            $tgl_start = date_create($this->input->post('tgl_start'));
            $tgl_end = date_create($this->input->post('tgl_end'));
            $datediff = date_diff($tgl_end, $tgl_start);
            $count = $datediff->days;
            // -- end of process data

            $params = array(
                $this->input->post('tgl_start'),
                $this->input->post('kalender_keterangan'),
                $this->input->post('kalender_type'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_kalender->update_kalender($params, $this->input->post('old_keterangan'), $count)) {

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
        redirect("akademik/kalender/");
    }

    // process delete kalender
    function delete($params) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_kalender->delete_kalender($params)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("akademik/kalender/");
    }

    // view as kalender
    function view() {
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
        $this->smarty->assign("template_content", "akademik/kalender/kalender.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // get json data for fullcalendar
    function get_json_data() {
        $result = $this->m_kalender->get_all_for_kalender();
        $arrResult = array();
        foreach ($result as $key => $rs) {
            $arrResult[$key] = array(
                'id' => $rs['kalender_id'],
                'title' => $rs['kalender_keterangan'],
                'start' => $rs['tgl_start'],
                'end' => $rs['tgl_end'],
                'editable' => false
            );
            switch ($rs['kalender_type']) {
                case 'akademik':
                    $arrResult[$key]['className'] = '';
                    break;
                case 'nasional':
                    $arrResult[$key]['className'] = 'red';
                    break;
                case 'other':
                    $arrResult[$key]['className'] = 'green';
                    break;

                default:

                    break;
            }
        }
        echo json_encode($arrResult);
    }
}