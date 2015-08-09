<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class seleksi extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('psb/m_seleksi');
        $this->load->model('master/m_jalurpsb');
        $this->load->model('master/m_jurusan');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    public function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "psb/seleksi/list.html");

        // session
        $search = $this->tsession->userdata('search_seleksi_psb');
        $this->smarty->assign('search', $search);

        // params
        $jalurpsb_id = !empty($search['jalurpsb_id']) ? "%" . $search['jalurpsb_id'] . "%" : "%";
        $psb_tha = !empty($search['psb_tha']) ? "%" . $search['psb_tha'] . "%" : "%";
        $params = array($psb_tha, $jalurpsb_id);

        // pagination
        $config['base_url'] = site_url("psb/seleksi/index/");
        $config['total_rows'] = $this->m_seleksi->get_total_list($params);

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
        $params = array($jalurpsb_id, $psb_tha, ($start - 1), $config['per_page']);

        $this->smarty->assign("rs_id", $this->m_seleksi->get_list($params));
        $this->smarty->assign("rs_jalur", $this->m_jalurpsb->get_all_jalurpsb($params));

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
                "operator" => $this->input->post('operator'),
                "cs_nilai_un" => $this->input->post('cs_nilai_un'),
                "jurusan_id" => $this->input->post('jurusan_id'),
            );
            // set
            $this->tsession->set_userdata('search_seleksi', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_seleksi');
        }
        //--
        redirect('psb/seleksi/process/' . $this->input->post('psb_id_search'));
    }

    // pencarian
    public function search_psb_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "jalurpsb_id" => $this->input->post('jalurpsb_id'),
                "psb_tha" => $this->input->post('psb_tha'),
            );
            // set
            $this->tsession->set_userdata('search_seleksi_psb', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_seleksi_psb');
        }
        //--
        redirect('psb/seleksi/');
    }

    function process($params) {

        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "psb/seleksi/process.html");

        // session
        $search = $this->tsession->userdata('search_seleksi');
        $this->smarty->assign('search', $search);
        // params
        $start = $this->uri->segment(5, 0) + 1;
        $jurusan_id = !empty($search['jurusan_id']) ? "%" . $search['jurusan_id'] . "%" : "%";

        $config['per_page'] = 10;
        if (!empty($search['operator']) && isset($search['cs_nilai_un']) && !empty($search['jurusan_id'])) {
            $operator = $search['operator'];
            $cs_nilai_un = $search['cs_nilai_un'];
            $param_search = array($params, $cs_nilai_un, $jurusan_id);
            $config['total_rows'] = $this->m_seleksi->get_total_list_seleksi_with_search($param_search, $operator);
            $param_search = array($params, $cs_nilai_un, $jurusan_id, ($start - 1), $config['per_page']);
            $this->smarty->assign("rs_id", $this->m_seleksi->get_cs_by_psb_id_with_search($param_search, $operator));
        } else {
            $config['total_rows'] = $this->m_seleksi->get_total_list_seleksi(array($params, $jurusan_id));
            $params = array($params, $jurusan_id, ($start - 1), $config['per_page']);
            $this->smarty->assign("rs_id", $this->m_seleksi->get_cs_by_psb_id($params));
        }
        $config['base_url'] = site_url("psb/seleksi/process/" . $this->uri->segment(4, 0));

        $config['uri_segment'] = 5;

        $this->pagination->initialize($config);
        $pagination['data'] = $this->pagination->create_links();

        // pagination attribute
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

        $this->smarty->assign("psb_id", $this->uri->segment(4, 0));
        $this->smarty->assign("cs_processed", $this->m_seleksi->get_processed_cs(array($this->uri->segment(4, 0), $jurusan_id)));
        // data jurusan
        $this->smarty->assign("rs_jurusan", $this->m_jurusan->get_all_jurusan());
        // data jumlah
        $this->smarty->assign("rs_jumlah", $this->m_seleksi->get_jumlah(array($this->uri->segment(4, 0), $jurusan_id)));

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    function siswa_process() {
        // set page rules
        $this->_set_page_rule("U");

        if ($this->input->post('process') == 'Diterima') {
            $cs_ids = $this->input->post('cs_id_left');
            if (!empty($cs_ids)) {
                $params = array();
                foreach ($cs_ids as $key => $cs_id) {
                    $params[] = array('DITERIMA', $cs_id);
                }
                if ($this->m_seleksi->update_cs_st($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Tidak ada data yang dipilih");
            }
        } elseif ($this->input->post('process') == 'Ditolak') {
            $cs_ids = $this->input->post('cs_id_left');
            if (!empty($cs_ids)) {
                $params = array();
                foreach ($cs_ids as $key => $cs_id) {
                    $params[] = array('TIDAK DITERIMA', $cs_id);
                }
                if ($this->m_seleksi->update_cs_st($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Tidak ada data yang dipilih");
            }
        } elseif ($this->input->post('process') == 'Dicadangkan') {
            $cs_ids = $this->input->post('cs_id_left');
            if (!empty($cs_ids)) {
                $params = array();
                foreach ($cs_ids as $key => $cs_id) {
                    $params[] = array('CADANGAN', $cs_id);
                }
                if ($this->m_seleksi->update_cs_st($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Tidak ada data yang dipilih");
            }
        } else {
            $cs_ids = $this->input->post('cs_id_right');
            if (!empty($cs_ids)) {
                $params = array();
                foreach ($cs_ids as $key => $cs_id) {
                    $params[] = array('PROSES', $cs_id);
                }
                if ($this->m_seleksi->update_cs_st($params)) {
                    $this->tnotification->delete_last_field();
                    $this->tnotification->sent_notification("success", "Data berhasil disimpan");
                } else {
                    // default error
                    $this->tnotification->sent_notification("error", "Data gagal disimpan");
                }
            } else {
                // default error
                $this->tnotification->sent_notification("error", "Tidak ada data yang dipilih");
            }
        }
        redirect('psb/seleksi/process/' . $this->input->post('psb_id'));
    }

    public function close_psb($params) {
        // set page rules
        $this->_set_page_rule("U");

        if ($this->m_seleksi->set_all_proses_to_reject($params)) {

            $this->tnotification->delete_last_field();
            $this->tnotification->sent_notification("success", "PSB telah ditutup");
            redirect('psb/seleksi/');
        } else {
            // default error
            $this->tnotification->sent_notification("error", "PSB gagal ditutup");
            redirect('psb/seleksi/process/' . $params);
        }
    }

    function export_excel($psb_id) {
        $this->load->library('phpexcel');
        $this->load->model('pengaturan/m_preference');
        $rs_cs = $this->m_seleksi->get_passed_cs($psb_id);
        $params = array(
            'sekolah',
            'nama'
        );
        $sekolah = $this->m_preference->get_preference_by_group('sekolah');
        //activate worksheet number 1
        $this->phpexcel->setActiveSheetIndex(0);
        //name the worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Daftar Peserta Lulus');

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'DAFTAR PESERTA LULUS');
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->mergeCells('A1:C1');


        $this->phpexcel->getActiveSheet()->setCellValue('A2', 'PENERIMAAN SISWA BARU');
        $this->phpexcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(16);
        $this->phpexcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->mergeCells('A2:C2');

        $this->phpexcel->getActiveSheet()->setCellValue('A3', $sekolah[0]['pref_value']);
        $this->phpexcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(16);
        $this->phpexcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->mergeCells('A3:C3');

        $this->phpexcel->getActiveSheet()->setCellValue('A4', "TAHUN AJARAN " . $rs_cs[0]['psb_tha']);
        $this->phpexcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(16);
        $this->phpexcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->mergeCells('A4:C4');

        $this->phpexcel->getActiveSheet()->setCellValue('A7', "No.");
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);

        $this->phpexcel->getActiveSheet()->setCellValue('B7', "No. Pendaftaran");
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(34.29);


        $this->phpexcel->getActiveSheet()->setCellValue('C7', "Nama");
        $this->phpexcel->getActiveSheet()->getStyle('C7')->getFont()->setSize(12);
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(47);

        $this->phpexcel->getActiveSheet()->getRowDimension(7)->setRowHeight(29.25);


        $pos_cell = 7;
        foreach ($rs_cs as $key => $cs) {
            $pos_cell++;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $pos_cell, ++$key);
            $this->phpexcel->getActiveSheet()->getStyle('A' . $pos_cell)->getFont()->setSize(12);

            $this->phpexcel->getActiveSheet()->setCellValueExplicit('B' . $pos_cell, $cs['cs_no_pendaftaran'], PHPExcel_Cell_DataType::TYPE_STRING);
            $this->phpexcel->getActiveSheet()->getStyle('B' . $pos_cell)->getFont()->setSize(12);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $pos_cell, $cs['cs_nama']);
            $this->phpexcel->getActiveSheet()->getStyle('C' . $pos_cell)->getFont()->setSize(12);
        }


        //set aligment to center for that merged cell (A1 to D1)
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $filename = 'PSB_JALUR_REGULER_' . str_replace('-', '_', $rs_cs[0]['psb_tha']) . '.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

}
