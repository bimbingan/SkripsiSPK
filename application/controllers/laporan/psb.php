<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// load base class if needed
require_once( APPPATH . 'controllers/base/OperatorBase.php' );

class psb extends ApplicationBase {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('laporan/m_lap_psb');
        $this->load->model('master/m_psb');
        $this->load->model('master/m_sekolah');
        $this->load->model('master/m_provinsi');
        $this->load->model('master/m_kabupaten');
        $this->load->model('master/m_agama');
        $this->load->model('master/m_jalurpsb');
        // load library
        $this->load->library('tnotification');
        // load library
        $this->load->library('pagination');
    }

    function index() {
        // set page rules
        $this->_set_page_rule("R");
        // set template content
        $this->smarty->assign("template_content", "laporan/psb/list.html");
        // load js
        $this->smarty->load_javascript("resource/js/select2-3.4.5/select2.min.js");
        // load css
        $this->smarty->load_style("select2/select2.css");
        // session
        $search = $this->tsession->userdata('search_lap_psb');
        $this->smarty->assign('search', $search);

        // params
        $sekolah_id = !empty($search['sekolah_id']) ? "%" . $search['sekolah_id'] . "%" : "%";
        $provinsi_id = !empty($search['provinsi_id']) ? "%" . $search['provinsi_id'] . "%" : "%";
        $kab_id = !empty($search['kab_id']) ? "%" . $search['kab_id'] . "%" : "%";
        $psb_tha = !empty($search['psb_tha']) ? "%" . $search['psb_tha'] . "%" : "%";
        $jalurpsb_id = !empty($search['jalurpsb_id']) ? "%" . $search['jalurpsb_id'] . "%" : "%";
        $cs_st = !empty($search['cs_st']) ? $search['cs_st'] : "%";

        $params = array($sekolah_id, $provinsi_id, $kab_id, $psb_tha, $jalurpsb_id, $cs_st);

        $config['base_url'] = site_url("laporan/psb/index/");
        $config['total_rows'] = $this->m_lap_psb->get_total_list($params);

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
        $params = array($sekolah_id, $provinsi_id, $kab_id, $psb_tha, $jalurpsb_id, $cs_st, ($start - 1), $config['per_page']);
        $rs_id = $this->m_lap_psb->get_all_list($params);
        $this->tsession->set_userdata('laporan_psb', $rs_id);
        $this->smarty->assign("rs_id", $rs_id);
        $this->smarty->assign("rs_sekolah", $this->m_sekolah->get_all_sekolah());
        $this->smarty->assign("rs_provinsi", $this->m_provinsi->get_all_provinsi());
        $this->smarty->assign("rs_kab", $this->m_kabupaten->get_all_kabupaten());
        $this->smarty->assign("rs_psb_year", $this->m_psb->get_year_psb());
        $this->smarty->assign("rs_jalur", $this->m_jalurpsb->get_all_jalurpsb());

        // notification
        $this->tnotification->display_notification();
        $this->tnotification->display_last_field();
        // output
        parent::display();
    }

    // pencarian
    function search_process() {
        // set page rules
        $this->_set_page_rule("R");
        //--
        if ($this->input->post('save') == 'Cari') {
            $params = array(
                "sekolah_id" => $this->input->post('sekolah_id'),
                "provinsi_id" => $this->input->post('provinsi_id'),
                "kab_id" => $this->input->post('kab_id'),
                "psb_tha" => $this->input->post('psb_tha'),
                "jalurpsb_id" => $this->input->post('jalurpsb_id'),
                "cs_st" => $this->input->post('cs_st')
            );

            // set
            $this->tsession->set_userdata('search_lap_psb', $params);
        } else {
            // unset
            $this->tsession->unset_userdata('search_lap_psb');
        }
        //--
        redirect('laporan/psb');
    }

    // view psb report graph mode
    function grafik() {
        
    }

    // report export to excel
    function export_excel() {
        // set page rules
        $this->_set_page_rule("R");

        $this->load->library('phpexcel');
        $rs_psb = $this->tsession->userdata('laporan_psb');
        // echo "<pre>";
        // print_r($rs_psb);
        // die();
        //activate worksheet number 1
        $this->phpexcel->setActiveSheetIndex(0);
        //name the worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Laporan Penerimaan Siswa Baru');

        // set Page and Orientation
        $this->phpexcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $this->phpexcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        // end of set Page and Orientation
        // set header alignment
        $this->phpexcel->getActiveSheet()->getStyle('A7:H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A7:H7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // set header row height
        $this->phpexcel->getActiveSheet()->getRowDimension(7)->setRowHeight(22, 5);

        // col A
        $this->phpexcel->getActiveSheet()->setCellValue('A7', "No.");
        $this->phpexcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);

        // col B
        $this->phpexcel->getActiveSheet()->setCellValue('B7', "Nama");
        $this->phpexcel->getActiveSheet()->getStyle('B7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(23);

        // col C
        $this->phpexcel->getActiveSheet()->setCellValue('C7', "Sekolah Asal");
        $this->phpexcel->getActiveSheet()->getStyle('C7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);

        // col D
        $this->phpexcel->getActiveSheet()->setCellValue('D7', "Provinsi");
        $this->phpexcel->getActiveSheet()->getStyle('D7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        // col E
        $this->phpexcel->getActiveSheet()->setCellValue('E7', "Kabupaten");
        $this->phpexcel->getActiveSheet()->getStyle('E7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

        // col F
        $this->phpexcel->getActiveSheet()->setCellValue('F7', "Tahun PSB");
        $this->phpexcel->getActiveSheet()->getStyle('F7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

        // col G
        $this->phpexcel->getActiveSheet()->setCellValue('G7', "Jalur");
        $this->phpexcel->getActiveSheet()->getStyle('G7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        // col H
        $this->phpexcel->getActiveSheet()->setCellValue('H7', "Status");
        $this->phpexcel->getActiveSheet()->getStyle('H7')->getFont()->setSize(11);
        $this->phpexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        $pos_cell = 7;
        $counter = array(
            'PROSES' => 0,
            'DITERIMA' => 0,
            'TIDAK DITERIMA' => 0,
            'CADANGAN' => 0
        );

        foreach ($rs_psb as $key => $psb) {
            $pos_cell++;
            // set row alignment
            $this->phpexcel->getActiveSheet()->getStyle("A$pos_cell:H$pos_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle("A$pos_cell:H$pos_cell")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle("A$pos_cell:H$pos_cell")->getAlignment()->setWrapText(true);
            // exception
            $this->phpexcel->getActiveSheet()->getStyle("B$pos_cell")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            // set row height
            $this->phpexcel->getActiveSheet()->getRowDimension($pos_cell)->setRowHeight(44, 25);

            // col A
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $pos_cell, ++$key);
            $this->phpexcel->getActiveSheet()->getStyle('A' . $pos_cell)->getFont()->setSize(11);

            // col B 
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $pos_cell, $psb['cs_nama']);
            $this->phpexcel->getActiveSheet()->getStyle('B' . $pos_cell)->getFont()->setSize(11);

            // col C
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $pos_cell, $psb['sekolah_nama']);
            $this->phpexcel->getActiveSheet()->getStyle('C' . $pos_cell)->getFont()->setSize(11);

            // col D
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $pos_cell, $psb['provinsi_nm']);
            $this->phpexcel->getActiveSheet()->getStyle('D' . $pos_cell)->getFont()->setSize(11);

            // col E
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $pos_cell, $psb['kab_nm']);
            $this->phpexcel->getActiveSheet()->getStyle('E' . $pos_cell)->getFont()->setSize(11);

            // col F
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $pos_cell, $psb['psb_tha']);
            $this->phpexcel->getActiveSheet()->getStyle('F' . $pos_cell)->getFont()->setSize(11);

            // col G
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $pos_cell, $psb['jalurpsb_nama']);
            $this->phpexcel->getActiveSheet()->getStyle('G' . $pos_cell)->getFont()->setSize(11);

            // col H
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $pos_cell, $psb['cs_st']);
            $this->phpexcel->getActiveSheet()->getStyle('H' . $pos_cell)->getFont()->setSize(11);

            // counting status
            $counter[$psb['cs_st']] ++;
        }
        $pos_cell += 3;
        // jumlah

        $this->phpexcel->getActiveSheet()->setCellValue('A' . ($pos_cell++), 'Jumlah Siswa Diterima : ' . $counter['DITERIMA']);
        $this->phpexcel->getActiveSheet()->mergeCells("A$pos_cell:C$pos_cell");

        $this->phpexcel->getActiveSheet()->setCellValue('A' . ($pos_cell++), 'Jumlah Masih Proses : ' . $counter['PROSES']);
        $this->phpexcel->getActiveSheet()->mergeCells("A$pos_cell:C$pos_cell");

        $this->phpexcel->getActiveSheet()->setCellValue('A' . ($pos_cell++), 'Jumlah Siswa Tidak Diterima : ' . $counter['TIDAK DITERIMA']);
        $this->phpexcel->getActiveSheet()->mergeCells("A$pos_cell:C$pos_cell");

        $this->phpexcel->getActiveSheet()->setCellValue('A' . ($pos_cell++), 'Jumlah Siswa CADANGAN : ' . $counter['CADANGAN']);
        $this->phpexcel->getActiveSheet()->mergeCells("A$pos_cell:C$pos_cell");




        //set aligment to center for that merged cell (A1 to D1)
        $filename = 'LAPORAN_PENERIMAAN_SISWA_BARU_' . date("d-M-Y") . '.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        //force user to download the Excel file without writing it to server's HD
        $this->tsession->unset_userdata('laporan_psb');
        $objWriter->save('php://output');
    }

}
