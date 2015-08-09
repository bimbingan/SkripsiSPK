<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class pembayaran extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('psb/m_pembayaran');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "psb/pembayaran/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");
        // session
        $search = $this->tsession->userdata('search_pembayaran_psb');
        $this->smarty->assign('search', $search);

        // params
        $cs_nama = !empty($search['cs_nama']) ? "%" . $search['cs_nama'] . "%" : "%";
        $trx_date = !empty($search['trx_date']) ? "%" . $search['trx_date'] . "%" : "%";
        $trx_st = !empty($search['trx_st']) ? "%" . $search['trx_st'] . "%" : "%";
        $params = array($cs_nama, $trx_date, $trx_st);

        // pagination
        $config['base_url'] = site_url("psb/pembayaran/index/");
        $config['total_rows'] = $this->m_pembayaran->get_total_kas($params);

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
        $params = array($cs_nama, $trx_date, $trx_st, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_pembayaran->get_list_kas($params));

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
                "cs_nama" => $this->input->post('cs_nama'),
                "trx_date" => $this->input->post('trx_date'),
                "trx_st" => $this->input->post('trx_st'),
            );
            // set
            $this->tsession->set_userdata('search_pembayaran_psb', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_pembayaran_psb');
        }
        //--
        redirect('psb/pembayaran/');
    }

    function add() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "psb/pembayaran/add.html");
        // load model
        $this->load->model('psb/m_calonsiswa');
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");

        // load data calon siswa
        $this->smarty->assign("rs_cs", $this->m_calonsiswa->get_all_calonsiswa_for_select());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // tambah pembayaran pendaftaran
    function process_add() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('cs_id', 'Calon Siswa', 'trim|required');
        $this->tnotification->set_rules('trx_date', 'Tanggal Transaksi', 'trim|required');
        $this->tnotification->set_rules('jumlah', 'Jumlah Transaksi', 'trim|required|numeric');
        $this->tnotification->set_rules('trx_jenis', 'Jenis Transaksi', 'trim|required');
        $this->tnotification->set_rules('trx_st', 'Status Transaksi', 'trim|required');
        $this->tnotification->set_rules('keterangan', 'Keterangan', 'trim|required');


        // process
        if ($this->tnotification->run() !== FALSE) {
            // pre process data
            $trx_jenis = $this->input->post('trx_jenis');

            if ($trx_jenis == 'debit') {
                $debit = $this->input->post('jumlah');
            } else {
                $kredit = $this->input->post('jumlah');
            }
            $kas_no = 'DB-PSB-' . $this->m_pembayaran->get_new_kas_no();
            // end of pre process data

            $params = array(
                'kas_no' => $kas_no,
                'cs_id' => $this->input->post('cs_id'),
                'trx_date' => $this->input->post('trx_date'),
                'trx_st' => $this->input->post('trx_st'),
                'debit' => $debit,
                'kredit' => $kredit,
                'keterangan' => $this->input->post('keterangan'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            // insert
            if ($this->m_pembayaran->insert_pembayaran($params)) {
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
        redirect("psb/pembayaran/add");
    }

    function edit($params) {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "psb/pembayaran/edit.html");
        // load model
        $this->load->model('psb/m_calonsiswa');
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        $this->smarty->load_javascript("resource/js/jquery/jquery-ui-1.9.2.custom.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        $this->smarty->load_style("jquery.ui/redmond/jquery-ui-1.8.13.custom.css");

        // load data calon siswa
        $this->smarty->assign("rs_cs", $this->m_calonsiswa->get_all_calonsiswa_for_select());
        // load data kas
        $this->smarty->assign("rs_kas", $this->m_pembayaran->get_kas_by_id($params));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // process ubah pembayaran pendaftaran
    function process_edit() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('cs_id', 'Calon Siswa', 'trim|required');
        $this->tnotification->set_rules('trx_date', 'Tanggal Transaksi', 'trim|required');
        $this->tnotification->set_rules('jumlah', 'Jumlah Transaksi', 'trim|required|numeric');
        $this->tnotification->set_rules('trx_jenis', 'Jenis Transaksi', 'trim|required');
        $this->tnotification->set_rules('trx_st', 'Status Transaksi', 'trim|required');
        $this->tnotification->set_rules('keterangan', 'Keterangan', 'trim|required');


        // process
        if ($this->tnotification->run() !== FALSE) {
            // pre process data
            $trx_jenis = $this->input->post('trx_jenis');

            if ($trx_jenis == 'debit') {
                $debit = $this->input->post('jumlah');
            } else {
                $kredit = $this->input->post('jumlah');
            }
            $kas_no = 'DB-PSB-' . $this->m_pembayaran->get_new_kas_no();
            // end of pre process data

            $params = array(
                'cs_id' => $this->input->post('cs_id'),
                'trx_date' => $this->input->post('trx_date'),
                'trx_st' => $this->input->post('trx_st'),
                'debit' => $debit,
                'kredit' => $kredit,
                'keterangan' => $this->input->post('keterangan'),
                'mdb' => $this->com_user['user_id'],
                'mdd' => date('Y-m-d')
            );
            $where = array(
                'kas_id' => $this->input->post('kas_id')
            );
            // insert
            if ($this->m_pembayaran->update_pembayaran($params, $where)) {
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
        redirect("psb/pembayaran/edit/" . $this->input->post('kas_id'));
    }

    // delete kendaraan
    function delete($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_pembayaran->delete_pembayaran($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("psb/pembayaran/");
    }

}
