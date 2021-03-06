<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class siswa extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('master/m_siswa');
        $this->load->model('master/m_periode');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        $search = $this->tsession->userdata('search_siswa');
        $this->smarty->assign("search", $search);
        $siswa_nama = !empty($search['siswa_nama']) ? '%'.$search['siswa_nama'].'%' : '%';
        $periode = !empty($search['periode']) ? $search['periode'] : '%';
        // session

        // set template content
        $this->smarty->assign("template_content", "master/siswa/list.html");

        // load data
        $params = array($siswa_nama, $periode);
        $data_siswa = $this->m_siswa->get_list_siswa($params);
        $this->smarty->assign("rs_id", $data_siswa);

        $rs_import = $this->m_periode->get_all_periode();
        $this->smarty->assign("rs_import", $rs_import);

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
                "siswa_nis" => $this->input->post('siswa_nis'),
                "siswa_nama" => $this->input->post('siswa_nama'),
                "periode" => $this->input->post('periode'),
            );

            // set
            $this->tsession->set_userdata('search_siswa', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_siswa');
        }
        //--
        redirect('master/siswa');
    }


    function add(){
      // set page rules
      $this->_set_page_rule("C");

      // set template content
      $this->smarty->assign("template_content", "master/siswa/add.html");
      // load js
      $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
      $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
      // load css
      $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

      // notification
      $this->tnotification->display_notification();
      $this->tnotification->display_last_field();
      // output
      parent::display();
    }

    function process_add(){
      // set page rules
      $this->_set_page_rule("C");

      $this->tnotification->set_rules('nis', 'NIS', 'trim|required|number');
      $this->tnotification->set_rules('nama', 'Nama', 'trim|required|max_length[45]');
      $this->tnotification->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim');
      $this->tnotification->set_rules('tempat_lahir, tgl_lahir', 'TTL', 'trim');
      $this->tnotification->set_rules('asal_sekolah', 'Asal Sekolah', 'trim');
      if($this->tnotification->run()){
          // kalau validasi benar

          $params = array(
            'nis' => $this->input->post('nis'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tgl_lahir' => $this->input->post('tgl_lahir'),
            'asal_sekolah' => $this->input->post('asal_sekolah'),
          );


          if($this->m_siswa->insert_siswa($params)){
            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "Data berhasil disimpan");
          }else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
          }
      }else{
        // kalau validasi salah
        $this->tnotification->sent_notification("error", "Data gagal disimpan");

      }

      redirect('master/siswa/add');
    }

    function delete($params){
        $this->_set_page_rule("D");

        if($this->m_siswa->delete_siswa($params)){
              // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil dihapus");
        }else{
            $this->tnotification->sent_notification("error", "Data gagal dihapus");

        }
        redirect("master/siswa");
    }

    function edit($params){
         $this->_set_page_rule("U");
         $this->smarty->assign("template_content", "master/siswa/edit.html");

         // load js
         $this->smarty->load_javascript('resource/js/datetimepicker/moment.js');
         $this->smarty->load_javascript('resource/js/datetimepicker/bootstrap-datetimepicker.js');
         // load css
         $this->smarty->load_style('datetimepicker/bootstrap-datetimepicker.css');

         $siswa = $this->m_siswa->get_one_siswa($params);
         $this->smarty->assign("result", $siswa);
         // notification
         $this->tnotification->display_notification();
         $this->tnotification->display_last_field();
         // output
         parent::display();
     }

    function process_edit(){
        $this->_set_page_rule("U");
        $this->tnotification->set_rules('nis', 'NIS', 'trim|required|number');
        $this->tnotification->set_rules('nama', 'Nama', 'trim|required|max_length[45]');
        $this->tnotification->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim');
        $this->tnotification->set_rules('tempat_lahir, tgl_lahir', 'TTL', 'trim');
        $this->tnotification->set_rules('asal_sekolah', 'Asal Sekolah', 'trim');
        if($this->tnotification->run() !== FALSE){
            $params = array(

              'nama' => $this->input->post('nama'),
              'jenis_kelamin' => $this->input->post('jenis_kelamin'),
              'tempat_lahir' => $this->input->post('tempat_lahir'),
              'tgl_lahir' => $this->input->post('tgl_lahir'),
              'asal_sekolah' => $this->input->post('asal_sekolah'),
            );
            $where = array(
                'nis' => $this->input->post('nis'),
            );

            if($this->m_siswa->update_siswa($params, $where)){

                 // success
                $this->tnotification->delete_last_field();
                $this->tnotification->sent_notification("success", "Data berhasil disimpan");
            }else{
                // default error
                $this->tnotification->sent_notification("error", "Data gagal disimpan");
            }
        }else{
            // default error
            $this->tnotification->sent_notification("error", "Data gagal disimpan");
        }
        redirect("master/siswa/edit/". $this->input->post('nis'));
    }

}
