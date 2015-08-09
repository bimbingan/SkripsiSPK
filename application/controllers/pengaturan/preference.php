<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

// --
class preference extends ApplicationBase {

    function __construct() {
        // parent constructor
        parent::__construct();
        // load model
        $this->load->model('pengaturan/m_preference');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    // index to profil sekolah
    public function index() {
        redirect("pengaturan/preference/profil_sekolah");
    }

    // edit profil sekolah
    public function profil_sekolah() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/profil_sekolah/list.html");
        // tingkat
        $this->smarty->assign('rs_tingkat', $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'sekolah')));
        // nama sekolah

        $this->smarty->assign('rs_nama', $this->m_preference->get_preference_by_group_and_name(array('sekolah', 'nama')));
        // alamat sekolah
        $this->smarty->assign('rs_alamat', $this->m_preference->get_preference_by_group_and_name(array('sekolah', 'alamat')));
        // tingkat sekolah
        $this->smarty->assign('rs_tingkat_sekolah', $this->m_preference->get_preference_by_group_and_name(array('sekolah', 'tingkat')));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // simpan profile sekolah
    public function save_profil_sekolah() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('sekolah_nama', 'Nama Sekolah', 'trim|required');
        $this->tnotification->set_rules('sekolah_alamat', 'Alamat Sekolah', 'trim|required');
        $this->tnotification->set_rules('sekolah_tingkat', 'Tingkat', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {

            // insert or update nama
            switch ($this->input->post('nama_id')) {
                case '':
                    $params = array(
                        'sekolah',
                        'nama',
                        $this->input->post('sekolah_nama'),
                        $this->com_user['user_id']
                    );
                    if ($this->m_preference->insert_preference($params)) {
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    } else {
                        // default error
                        $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    }
                    break;
                default:
                    $params = array(
                        'sekolah',
                        'nama',
                        $this->input->post('sekolah_nama'),
                        $this->com_user['user_id'],
                        $this->input->post('nama_id')
                    );
                    if ($this->m_preference->update_preference($params)) {
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    } else {
                        // default error
                        $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    }
                    break;
            }

            // insert or update alamat
            switch ($this->input->post('alamat_id')) {
                case '':
                    $params = array(
                        'sekolah',
                        'alamat',
                        $this->input->post('sekolah_alamat'),
                        $this->com_user['user_id']
                    );
                    if ($this->m_preference->insert_preference($params)) {
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    } else {
                        // default error
                        $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    }
                    break;
                default:
                    $params = array(
                        'sekolah',
                        'alamat',
                        $this->input->post('sekolah_alamat'),
                        $this->com_user['user_id'],
                        $this->input->post('alamat_id')
                    );
                    if ($this->m_preference->update_preference($params)) {
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    } else {
                        // default error
                        $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    }
                    break;
            }

            // insert or update tingkat
            switch ($this->input->post('tingkat_id')) {
                case '':
                    $params = array(
                        'sekolah',
                        'tingkat',
                        $this->input->post('sekolah_tingkat'),
                        $this->com_user['user_id']
                    );
                    if ($this->m_preference->insert_preference($params)) {
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    } else {
                        // default error
                        $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    }
                    break;
                default:
                    $params = array(
                        'sekolah',
                        'tingkat',
                        $this->input->post('sekolah_tingkat'),
                        $this->com_user['user_id'],
                        $this->input->post('tingkat_id')
                    );
                    if ($this->m_preference->update_preference($params)) {
                        $this->tnotification->delete_last_field();
                        $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                    } else {
                        // default error
                        $this->tnotification->sent_notification("error", "Data gagal disimpan");
                    }
                    break;
            }
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        // default redirect
        redirect("pengaturan/preference/profil_sekolah");
    }

    // list kelas
    public function kelas() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/kelas/list.html");

        // session
        $search = $this->tsession->userdata('search_preference_kelas');
        $this->smarty->assign('search', $search);

        // params
        $tingkat = !empty($search['tingkat']) ? "%" . $search['tingkat'] . "%" : "%";
        $kelas_nm = !empty($search['kelas_nm']) ? "%" . $search['kelas_nm'] . "%" : "%";
        $params = array('kelas', $tingkat, $kelas_nm);

        // pagination
        $config['base_url'] = site_url("pengaturan/preference/kelas/index/");
        $config['total_rows'] = $this->m_preference->get_total_preference_by_group_with_search($params);

        $config['uri_segment'] = 5;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();

        // pagination attribute
        $start = $this->uri->segment(5, 0) + 1;
        $end = $this->uri->segment(5, 0) + $config['per_page'];
        $end = (($end > $config['total_rows']) ? $config['total_rows'] : $end);
        $pagination['start'] = ($config['total_rows'] == 0) ? 0 : $start;
        $pagination['end'] = $end;
        $pagination['total'] = $config['total_rows'];

        // pagination assign value
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);

        // /* end of pagination ---------------------- */
        // get list data
        $params = array('kelas', $tingkat, $kelas_nm, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_preference->get_list_preference_by_group_with_search($params));
        $this->smarty->assign("rs_tingkat", $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'sekolah')));
        // notification

        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pencarian kelas
    public function search_process_kelas() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "tingkat" => $this->input->post('tingkat'),
                "kelas_nm" => $this->input->post('kelas_nm')
            );
            // set
            $this->tsession->set_userdata('search_preference_kelas', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_preference_kelas');
        }
        //--
        redirect('pengaturan/preference/kelas');
    }

    // form tambah kelas
    function add_kelas() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/kelas/add.html");
        // tingkat
        $this->smarty->assign("rs_tingkat", $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'sekolah')));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses tambah kelas
    function process_add_kelas() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('tingkat', 'Tingkat', 'trim|required');
        $this->tnotification->set_rules('kelas_nm', 'Nama Kelas', 'trim|required');

        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'kelas',
                $this->input->post('tingkat'),
                $this->input->post('kelas_nm'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_preference->insert_preference($params)) {
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
        redirect("pengaturan/preference/add_kelas");
    }

    // form edit kelas
    function edit_kelas($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_preference->get_preference_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/kelas/edit.html");
        // send data

        $this->smarty->assign("result", $this->m_preference->get_preference_by_id($param));
        // tingkat
        $this->smarty->assign("rs_tingkat", $this->m_preference->get_preference_by_group_and_name(array('tingkat', 'sekolah')));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses edit kelas
    function process_edit_kelas() {
        // set page rules
        $this->_set_page_rule("U");

        // cek input
        $this->tnotification->set_rules('tingkat', 'Tingkat', 'trim|required');
        $this->tnotification->set_rules('kelas_nm', 'Nama Kelas', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'kelas',
                $this->input->post('tingkat'),
                $this->input->post('kelas_nm'),
                $this->com_user['user_id'],
                $this->input->post('pref_id')
            );
            // update
            if ($this->m_preference->update_preference($params)) {
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
        redirect("pengaturan/preference/edit_kelas/" . $this->input->post('pref_id'));
    }

    // proses hapus kelas
    function delete_kelas($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_preference->delete_preference($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("pengaturan/preference/kelas");
    }

    // list tingkat
    public function tingkat() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/tingkat/list.html");

        // session
        $search = $this->tsession->userdata('search_preference_tingkat');
        $this->smarty->assign('search', $search);

        // params
        $jenis_tingkat = !empty($search['jenis_tingkat']) ? "%" . $search['jenis_tingkat'] . "%" : "%";
        $nama_tingkat = !empty($search['nama_tingkat']) ? "%" . $search['nama_tingkat'] . "%" : "%";
        $params = array('tingkat', $jenis_tingkat, $nama_tingkat);

        // pagination
        $config['base_url'] = site_url("pengaturan/preference/tingkat/index/");
        $config['total_rows'] = $this->m_preference->get_total_preference_by_group_with_search($params);

        $config['uri_segment'] = 5;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();

        // pagination attribute
        $start = $this->uri->segment(5, 0) + 1;
        $end = $this->uri->segment(5, 0) + $config['per_page'];
        $end = (($end > $config['total_rows']) ? $config['total_rows'] : $end);
        $pagination['start'] = ($config['total_rows'] == 0) ? 0 : $start;
        $pagination['end'] = $end;
        $pagination['total'] = $config['total_rows'];

        // pagination assign value
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);

        // /* end of pagination ---------------------- */
        // get list data
        $params = array('tingkat', $jenis_tingkat, $nama_tingkat, ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_preference->get_list_preference_by_group_with_search($params));
        $this->smarty->assign("rs_jenis_tingkat", $this->m_preference->get_preference_group_by_name('tingkat'));


        // notification

        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pencarian kelas
    public function search_process_tingkat() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "jenis_tingkat" => $this->input->post('jenis_tingkat'),
                "nama_tingkat" => $this->input->post('nama_tingkat')
            );
            // set
            $this->tsession->set_userdata('search_preference_tingkat', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_preference_tingkat');
        }
        //--
        redirect('pengaturan/preference/tingkat');
    }

    // form tambah tingkat
    function add_tingkat() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/tingkat/add.html");
        // tingkat
        $this->smarty->assign('rs_tingkat', $this->m_preference->get_preference_group_by_names('tingkat'));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses tambah tingkat
    function process_add_tingkat() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('jenis_tingkat_text', 'Jenis Tingkat', 'trim');
        $this->tnotification->set_rules('nama_tingkat', 'Nama Tingkat', 'trim|required');
        $this->tnotification->set_rules('jenis_tingkat_text', 'Jenis Tingkat Baru', 'trim');

        // process
        if ($this->tnotification->run() !== FALSE) {

            $jenis_tingkat = !empty($this->input->post('jenis_tingkat_select')) ? $this->input->post('jenis_tingkat_select') : $this->input->post('jenis_tingkat_text');

            $params = array(
                'tingkat',
                $jenis_tingkat,
                $this->input->post('nama_tingkat'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_preference->insert_preference($params)) {
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
        redirect("pengaturan/preference/add_tingkat");
    }

    // form edit tingkat
    function edit_tingkat($param) {
        // set page rules
        $this->_set_page_rule("U");
        // user data
        $result = $this->m_preference->get_preference_by_id($param);
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/tingkat/edit.html");
        // send data

        $this->smarty->assign("result", $this->m_preference->get_preference_by_id($param));
        // tingkat
        $this->smarty->assign('rs_tingkat', $this->m_preference->get_preference_group_by_name('tingkat'));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses edit tingkat
    function process_edit_tingkat() {
        // set page rules
        $this->_set_page_rule("U");

        // cek input
        $this->tnotification->set_rules('jenis_tingkat_select', 'Jenis Tingkat', 'trim');
        $this->tnotification->set_rules('jenis_tingkat_text', 'Jenis Tingkat Baru', 'trim');
        $this->tnotification->set_rules('nama_tingkat', 'Nama Tingkat', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $jenis_tingkat = !empty($this->input->post('jenis_tingkat_select')) ? $this->input->post('jenis_tingkat_select') : $this->input->post('jenis_tingkat_text');

            $params = array(
                'tingkat',
                $jenis_tingkat,
                $this->input->post('nama_tingkat'),
                $this->com_user['user_id'],
                $this->input->post('pref_id')
            );
            // update
            if ($this->m_preference->update_preference($params)) {
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
        redirect("pengaturan/preference/edit_tingkat/" . $this->input->post('pref_id'));
    }

    // proses hapus tingkat
    function delete_tingkat($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_preference->delete_preference($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }

        redirect("pengaturan/preference/tingkat");
    }

    // list agama
    public function agama() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/agama/list.html");

        // session
        $search = $this->tsession->userdata('search_preference_agama');
        $this->smarty->assign('search', $search);

        // params
        // $tahun_ajaran = !empty($search['preference_agama']) ? "%" . $search['preference/agama_tahun_ajaran'] . "%" : "%";
        $params = array();

        // pagination
        $config['base_url'] = site_url("pengaturan/preference/agama/index/");
        $config['total_rows'] = $this->m_preference->get_total_preference_by_group('agama');

        $config['uri_segment'] = 5;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();

        // pagination attribute
        $start = $this->uri->segment(5, 0) + 1;
        $end = $this->uri->segment(5, 0) + $config['per_page'];
        $end = (($end > $config['total_rows']) ? $config['total_rows'] : $end);
        $pagination['start'] = ($config['total_rows'] == 0) ? 0 : $start;
        $pagination['end'] = $end;
        $pagination['total'] = $config['total_rows'];

        // pagination assign value
        $this->smarty->assign("pagination", $pagination);
        $this->smarty->assign("no", $start);

        // /* end of pagination ---------------------- */
        // get list data
        $params = array('agama', ($start - 1), $config['per_page']);
        $this->smarty->assign("rs_id", $this->m_preference->get_list_preference_by_group($params));
        // notification

        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // form tambah agama
    function add_agama() {
        // set page rules
        $this->_set_page_rule("C");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/agama/add.html");
        // agama
        $this->smarty->assign('rs_agama', json_encode($this->m_preference->get_preference_by_group('agama')));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses tambah agama
    function process_add_agama() {
        // set page rules
        $this->_set_page_rule("C");
        // cek input
        $this->tnotification->set_rules('agama_nm', 'Agama', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'agama',
                'nama',
                $this->input->post('agama_nm'),
                $this->com_user['user_id']
            );
            // insert
            if ($this->m_preference->insert_preference($params)) {
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
        redirect("pengaturan/preference/add_agama");
    }

    // form edit agama
    function edit_agama($param) {
        // set page rules
        $this->_set_page_rule("U");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/agama/edit.html");
        // send data
        $this->smarty->assign("result", $this->m_preference->get_preference_by_id($param));
        // agama
        $this->smarty->assign('rs_agama', json_encode($this->m_preference->get_preference_by_group('agama')));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // proses ubah agama
    function process_edit_agama() {
        // set page rules
        $this->_set_page_rule("U");
        // cek input
        $this->tnotification->set_rules('agama_nm', 'Agama', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'agama',
                'nama',
                $this->input->post('agama_nm'),
                $this->com_user['user_id'],
                $this->input->post('pref_id')
            );
            // update
            if ($this->m_preference->update_preference($params)) {
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
        redirect("pengaturan/preference/edit_agama/" . $this->input->post('pref_id'));
    }

    // proses hapus agama
    function delete_agama($param) {
        // set page rules
        $this->_set_page_rule("D");
        if ($this->m_preference->delete_preference($param)) {
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        } else {
            // default error
            $this->tnotification->sent_notification("error", "Data gagal dihapus");
        }
        redirect("pengaturan/preference/agama");
    }

    function psb() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "pengaturan/preference/psb/list.html");
        // biaya
        $this->smarty->assign('rs_biaya_daftar', $this->m_preference->get_preference_by_group_and_name(array('biaya', 'pendaftaran')));
        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    function save_psb() {
        // set page rules
        $this->_set_page_rule("U");

        // cek input
        $this->tnotification->set_rules('biaya_daftar_id', 'ID', 'trim|required');
        $this->tnotification->set_rules('biaya_daftar', 'Biaya Pendaftaran', 'trim|required');
        // process
        if ($this->tnotification->run() !== FALSE) {
            $params = array(
                'biaya',
                'pendaftaran',
                $this->input->post('biaya_daftar'),
                $this->com_user['user_id'],
                $this->input->post('biaya_daftar_id')
            );
            // update
            if ($this->m_preference->update_preference($params)) {
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
        redirect("pengaturan/preference/psb/");
    }

}
