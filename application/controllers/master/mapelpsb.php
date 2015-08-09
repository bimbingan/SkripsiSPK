<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class mapelpsb extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_mapelpsb');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "master/mapelpsb/list.html");
        // session
        $search = $this->tsession->userdata('search_mapelpsb');
        $this->smarty->assign('search', $search);

        // params
        $mapel_nama = !empty($search['mapel_nama']) ? "%" . $search['mapel_nama'] . "%" : "%";
        $params = array($mapel_nama);

        // pagination
        $config['base_url'] = site_url("master/mapelpsb/index/");
        $config['total_rows'] = $this->m_mapelpsb->get_total_mapelpsb($params);

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
        $params = array($mapel_nama, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_mapelpsb->get_list_mapelpsb($params));
        $this->m_mapelpsb->generate_id();
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
            );
            // set
            $this->tsession->set_userdata('search_mapelpsb', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_mapelpsb');
        }
        //--
        redirect('master/mapelpsb');
    }

    // form tambah jenis pengurus
    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "master/mapelpsb/add.html");
        // new id
        $this->smarty->assign("new_id", $this->m_mapelpsb->generate_id());

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
        $this->tnotification->set_rules('mapel_cd', 'Kode Mata Pelajaran PSB', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('mapel_nama', 'Nama Mata Pelajaran PSB', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('mapel_keterangan', 'Keterangan', 'trim');
        // process

        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('mapel_cd'),
                $this->input->post('mapel_nama'),
                $this->input->post('mapel_keterangan'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_mapelpsb->insert_mapelpsb($params)) {
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
        redirect("master/mapelpsb/add");
    }

    function edit($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_mapelpsb->get_mapelpsb_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "master/mapelpsb/edit.html");
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
        $this->tnotification->set_rules('mapel_cd', 'Kode Mata Pelajaran PSB', 'trim|required|max_length[5]');
        $this->tnotification->set_rules('mapel_nama', 'Nama Mata Pelajaran PSB', 'trim|required|max_length[200]');
        $this->tnotification->set_rules('mapel_keterangan', 'Keterangan', 'trim');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                $this->input->post('mapel_nama'),
                $this->input->post('mapel_keterangan'),
                $this->com_user['user_id'],
                $this->input->post('mapel_cd')
            );
            // insert
            if ($this->m_mapelpsb->update_mapelpsb($params)) {
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
        redirect("master/mapelpsb/edit/" . $this->input->post('mapel_cd'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_mapelpsb->delete_mapelpsb($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("master/mapelpsb/");
    }

}
