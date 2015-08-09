<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class mapel extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_mapel');
        $this->load->model('master/m_mapel_group');
        $this->load->model('master/m_grade');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/mapel/list.html");

        // session
        $search = $this->tsession->userdata('search_mapel');
        $this->smarty->assign('search', $search);

        // params
        $mapel_nama = !empty($search['mapel_nama']) ? "%" . $search['mapel_nama'] . "%" : "%";
        $grade_id = !empty($search['grade_id']) ? "%" . $search['grade_id'] . "%" : "%";
        $mapel_sem = !empty($search['mapel_sem']) ? "%" . $search['mapel_sem'] . "%" : "%";
        $mapel_st = !empty($search['mapel_st']) ? "%" . $search['mapel_st'] . "%" : "%";
        $mapelgroup_id = !empty($search['mapelgroup_id']) ? "%" . $search['mapelgroup_id'] . "%" : "%";
        $params = array($mapel_nama, $grade_id, $mapel_sem, $mapel_st, $mapelgroup_id);

        // pagination
        $config['base_url'] = site_url("master/mapel/index/");
        $config['total_rows'] = $this->m_mapel->get_total_mapel($params);

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
        $params = array($mapel_nama, $grade_id, $mapel_sem, $mapel_st, $mapelgroup_id, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_mapel->get_list_mapel($params));
        $this->smarty->assign("rs_mapelgroup", $this->m_mapel_group->get_all_mapel_group());
        $this->smarty->assign("rs_grade", $this->m_grade->get_all_grade());
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
                "mapel_nama" => $this->input->post('mapel_nama'),
                "grade_id" => $this->input->post('grade_id'),
                "mapel_sem" => $this->input->post('mapel_sem'),
                "mapel_st" => $this->input->post('mapel_st'),
                "mapelgroup_id" => $this->input->post('mapelgroup_id')
            );
            // set
            $this->tsession->set_userdata('search_mapel', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_mapel');
        }
        //--
        redirect('master/mapel');
    }

    // form tambah jenis pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/mapel/add.html");
        // load data
        $this->smarty->assign("rs_mapelgroup", $this->m_mapel_group->get_all_mapel_group());
        $this->smarty->assign("rs_grade", $this->m_grade->get_all_grade());
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
        $this->tnotification->set_rules('mapel_id', 'Kode', 'trim|required|max_length[6]');
        $this->tnotification->set_rules('mapel_nama', 'Nama Mata Pelajaran', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('mapel_keterangan', 'Keterangan', 'trim');
        $this->tnotification->set_rules('grade_id', 'Tingkat Kelas', 'trim|required');
        $this->tnotification->set_rules('mapel_st', 'Status', 'trim|required');
        $this->tnotification->set_rules('mapel_sem', 'Semester Mapel', 'trim|required');
        $this->tnotification->set_rules('mapelgroup_id', 'Kelompok Mapel', 'trim|required');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'mapel_id' => $this->input->post('mapel_id'),
                'mapel_nama' => $this->input->post('mapel_nama'),
                'mapel_keterangan' => $this->input->post('mapel_keterangan'),
                'grade_id' => $this->input->post('grade_id'),
                'mapel_st' => $this->input->post('mapel_st'),
                'mapel_sem' => $this->input->post('mapel_sem'),
                'mapelgroup_id' => $this->input->post('mapelgroup_id'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            // insert
            if ($this->m_mapel->insert_mapel($params)) {
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
        redirect("master/mapel/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_mapel->get_mapel_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/mapel/edit.html");
        // send data
        $this->smarty->assign("result", $result);
        $this->smarty->assign("rs_mapelgroup", $this->m_mapel_group->get_all_mapel_group());
        $this->smarty->assign("rs_grade", $this->m_grade->get_all_grade());
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
        $this->tnotification->set_rules('mapel_id', 'Kode', 'trim|required|max_length[6]');
        $this->tnotification->set_rules('mapel_nama', 'Nama Mata Pelajaran', 'trim|required|max_length[255]');
        $this->tnotification->set_rules('mapel_keterangan', 'Keterangan', 'trim');
        $this->tnotification->set_rules('grade_id', 'Tingkat Kelas', 'trim|required');
        $this->tnotification->set_rules('mapel_st', 'Status', 'trim|required');
        $this->tnotification->set_rules('mapel_sem', 'Semester Mapel', 'trim|required');
        $this->tnotification->set_rules('mapelgroup_id', 'Kelompok Mapel', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'mapel_nama' => $this->input->post('mapel_nama'),
                'mapel_keterangan' => $this->input->post('mapel_keterangan'),
                'grade_id' => $this->input->post('grade_id'),
                'mapel_st' => $this->input->post('mapel_st'),
                'mapel_sem' => $this->input->post('mapel_sem'),
                'mapelgroup_id' => $this->input->post('mapelgroup_id'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            $where = array(
                'mapel_id' => $this->input->post('mapel_id')
            );
            // insert
            if ($this->m_mapel->update_mapel($params, $where)) {
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
        redirect("master/mapel/edit/" . $this->input->post('mapel_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_mapel->delete_mapel($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/mapel/");
    }

}
