<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class mapel_group extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_mapel_group');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/mapel_group/list.html");
        // session
        $search = $this->tsession->userdata('search_mapel_group');
        $this->smarty->assign('search', $search);

        // params
        $mapelgroup_nm = !empty($search['mapelgroup_nm']) ? "%" . $search['mapelgroup_nm'] . "%" : "%";
        $params = array($mapelgroup_nm);

        // pagination
        $config['base_url'] = site_url("master/mapel_group/index/");
        $config['total_rows'] = $this->m_mapel_group->get_total_mapel_group($params);

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
        $params = array($mapelgroup_nm, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_mapel_group->get_list_mapel_group($params));

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
                "mapelgroup_nm" => $this->input->post('mapelgroup_nm'),
            );
            // set
            $this->tsession->set_userdata('search_mapel_group', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_mapel_group');
        }
        //--
        redirect('master/mapel_group/');
    }

    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/mapel_group/add.html");
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah mapel_group pendaftaran
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('mapelgroup_nm', 'Nama Kelompok Mata Pelajaran', 'trim|required|max_length[200]');
        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'mapelgroup_nm' => $this->input->post('mapelgroup_nm'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            // insert
            if (!$this->m_mapel_group->is_mapel_group_exist($params['mapelgroup_nm'])) {
                if ($this->m_mapel_group->insert_mapel_group($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                $this->tnotification->sent_notification("error", "Data gagal disimpan, Kelompok Mapel Sudah ada");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/mapel_group/add");
    }

    function edit($params) {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "master/mapel_group/edit.html");

        // load data kelompok mapel
        $this->smarty->assign("result", $this->m_mapel_group->get_mapel_group_by_id($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process ubah mapel_group pendaftaran
    function process_edit() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('mapelgroup_id', 'ID', 'trim|required');
        $this->tnotification->set_rules('mapelgroup_nm', 'Nama Kelompok Mata Pelajaran', 'trim|required|max_length[200]');

        // process
        if ($this->tnotification->run() !== FALSE) {

            $params = array(
                'mapelgroup_nm' => $this->input->post('mapelgroup_nm'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            $where = array(
                'mapelgroup_id' => $this->input->post('mapelgroup_id')
            );
            // update
            if (!$this->m_mapel_group->is_mapel_group_exist($params['mapelgroup_nm'])) {
                if ($this->m_mapel_group->update_mapel_group($params, $where)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                $this->tnotification->sent_notification("error", "Data gagal disimpan, Kelompok Mapel Sudah ada");
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("master/mapel_group/edit/" . $this->input->post('mapelgroup_id'));
    }

    // delete mapel group
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_mapel_group->delete_mapel_group($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/mapel_group/");
    }

}
