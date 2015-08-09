<?php

class m_penempatan extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_siswa_baru($params) {
        // load model
        $this->load->model('pengaturan/m_tha');
        // get tha
        $params[0] = (empty($params[0])) ? $this->m_tha->get_active_tha() : $params[0];

        $sql = "SELECT siswa_id, siswa_nis, siswa_nama, siswa_nilai_un, j.`jurusan_nm` 
		FROM siswa s INNER JOIN jurusan j USING(jurusan_id) 
		WHERE siswa_tahun_masuk = ? AND jurusan_id = ? AND kelas_id IS NULL ";


        $sql .= "LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_siswa_baru_with_nilai($params, $operator) {
        // load model
        $this->load->model('pengaturan/m_tha');
        // get tha
        $params[0] = (empty($params[0])) ? $this->m_tha->get_active_tha() : $params[0];

        $sql = "SELECT siswa_id, siswa_nis, siswa_nama, siswa_nilai_un , j.`jurusan_nm` 
		FROM siswa s INNER JOIN jurusan j USING(jurusan_id) 
		WHERE siswa_tahun_masuk = ? AND jurusan_id = ?  AND kelas_id IS NULL AND CAST(siswa_nilai_un AS DECIMAL) " . $operator . " CAST(? AS DECIMAL) ";

        if (empty($params[1])) {
            array_splice($params, 1, 1);
            $sql = str_replace('AND jurusan_id = ?', ' ', $sql);
        }

        $sql .= "LIMIT ?,?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_siswa_baru($params) {
        // load model
        $this->load->model('pengaturan/m_tha');
        // get tha
        $params[0] = (empty($params[0])) ? $this->m_tha->get_active_tha() : $params[0];

        $sql = "SELECT COUNT(*) as 'total' 
		FROM siswa s INNER JOIN jurusan j USING(jurusan_id) 
		WHERE siswa_tahun_masuk = ? AND jurusan_id = ? AND kelas_id IS NULL";

        if (empty($params[1])) {
            array_splice($params, 1, 1);
            $sql = str_replace('AND jurusan_id = ?', ' ', $sql);
        }

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    function get_total_siswa_baru_with_nilai($params) {
        // load model
        $this->load->model('pengaturan/m_tha');
        // get tha
        $params[0] = (empty($params[0])) ? $this->m_tha->get_active_tha() : $params[0];

        $sql = "SELECT COUNT(*) as 'total' 
		FROM siswa s INNER JOIN jurusan j USING(jurusan_id) 
		WHERE siswa_tahun_masuk = ? AND jurusan_id = ?  AND kelas_id IS NULL AND CAST(siswa_nilai_un AS DECIMAL) " . $operator . " CAST(? AS DECIMAL) ";

        if (empty($params[1])) {
            array_splice($params, 1, 1);
            $sql = str_replace('AND jurusan_id = ?', ' ', $sql);
        }

        $sql .= "LIMIT ?,?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_all_tahun_masuk() {
        $sql = "SELECT siswa_tahun_masuk FROM siswa GROUP BY siswa_tahun_masuk DESC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function update_kelas_siswa($params) {
        $this->db->trans_begin();
        foreach ($params as $key => $param) {
            $sql = "UPDATE siswa SET kelas_id = ? WHERE siswa_id = ?";
            $this->db->query($sql, $param);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

}
