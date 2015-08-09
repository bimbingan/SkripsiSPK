<?php

class m_seleksi extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // list summary for seleksi
    function get_list($params) {
        $sql = "SELECT p.`psb_id`, pj.`jalurpsb_nama`, p.`psb_tha`, 
		(SELECT COUNT(cs_st) FROM psb_siswa WHERE psb_siswa.`psb_id` = ps.`psb_id`) AS 'jml_terdaftar', 
		(SELECT COUNT(cs_st) FROM psb_siswa WHERE cs_st = 'DITERIMA'  AND psb_siswa.`psb_id` = ps.`psb_id`) AS 'diterima',
		(SELECT COUNT(cs_st) FROM psb_siswa WHERE cs_st = 'TIDAK DITERIMA'  AND psb_siswa.`psb_id` = ps.`psb_id`) AS 'ditolak' 
		FROM psb_siswa ps INNER JOIN psb p USING(psb_id) INNER JOIN psb_jalur pj USING(jalurpsb_id) 
		WHERE jalurpsb_id LIKE ? AND psb_tha LIKE ? 
		GROUP BY psb_id LIMIT ?,?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // list summary for seleksi
    function get_total_list($params = array()) {
        $sql = "SELECT COUNT(*) as 'total' 
		FROM (SELECT psb_id FROM psb_siswa ps 
		INNER JOIN psb p USING(psb_id) 
		INNER JOIN psb_jalur pj USING(jalurpsb_id)
		WHERE jalurpsb_id LIKE ? AND psb_tha LIKE ? 
		GROUP BY psb_id) list";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    function get_total_list_seleksi($params) {
        $sql = "SELECT COUNT(*) as 'total'
		FROM psb_siswa ps 
		INNER JOIN psb p USING(psb_id) 
		INNER JOIN psb_jalur pj USING(jalurpsb_id)
		INNER JOIN jurusan j USING(jurusan_id)
		WHERE psb_id = ? AND cs_st = 'PROSES' AND ps.`jurusan_id` LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get calon siswa by psb
    function get_cs_by_psb_id($params) {
        $sql = "SELECT ps.`cs_id`, ps.`cs_nis`, ps.`cs_nama`, ps.`cs_nilai_un` , ps.`cs_st`, j.`kode_mapel`
		FROM psb_siswa ps 
		INNER JOIN psb p USING(psb_id) 
		INNER JOIN psb_jalur pj USING(jalurpsb_id) 
		INNER JOIN jurusan j USING(jurusan_id)
		WHERE psb_id = ? AND cs_st = 'PROSES' AND ps.`jurusan_id` LIKE ? ORDER BY cs_st ASC LIMIT ?,?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list data of cs with one field on param
    function get_passed_cs($params) {
        $sql = "SELECT ps.`cs_id`, ps.`cs_nis`, ps.`cs_nama`, ps.`cs_nilai_un` , ps.`cs_st`, p.`psb_tha`, ps.`cs_no_pendaftaran`
		FROM psb_siswa ps 
		INNER JOIN psb p USING(psb_id) 
		INNER JOIN psb_jalur pj USING(jalurpsb_id) 
		WHERE psb_id = ? AND (cs_st = 'DITERIMA' OR cs_st = 'CADANGAN')  ORDER BY ps.`cs_no_pendaftaran`, FIELD(cs_st, 'DITERIMA', 'CADANGAN', 'TIDAK DITERIMA')";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get calon siswa by psb with search
    function get_cs_by_psb_id_with_search($params, $operator) {
        $sql = "SELECT ps.`cs_id`, ps.`cs_nis`, ps.`cs_nama`, ps.`cs_nilai_un` , ps.`cs_st`, j.`kode_mapel`
		FROM psb_siswa ps 
		INNER JOIN psb p USING(psb_id) 
		INNER JOIN psb_jalur pj USING(jalurpsb_id) 
		INNER JOIN jurusan j USING(jurusan_id)
		WHERE psb_id = ? AND cs_st = 'PROSES' AND cs_nilai_un " . $operator . " ? AND ps.jurusan_id LIKE ? ORDER BY cs_st ASC LIMIT ?,?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_list_seleksi_with_search($params, $operator) {
        $sql = "SELECT COUNT(*) as 'total'
		FROM psb_siswa ps 
		INNER JOIN psb p USING(psb_id) 
		INNER JOIN psb_jalur pj USING(jalurpsb_id) 
		INNER JOIN jurusan j USING(jurusan_id)
		WHERE psb_id = ? AND cs_st = 'PROSES' AND cs_nilai_un " . $operator . " ? AND ps.jurusan_id LIKE ?";
        $query = $this->db->query($sql, $params);

        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    function get_processed_cs($params) {
        $sql = "SELECT ps.`cs_id`, ps.`cs_nis`, ps.`cs_nama`, ps.`cs_nilai_un` , ps.`cs_st`, j.`kode_mapel`
		FROM psb_siswa ps 
		INNER JOIN psb p USING(psb_id) 
		INNER JOIN psb_jalur pj USING(jalurpsb_id)
		INNER JOIN jurusan j USING(jurusan_id)
		WHERE psb_id = ? AND cs_st != 'PROSES' AND jurusan_id LIKE ? ORDER BY FIELD(cs_st, 'DITERIMA', 'CADANGAN', 'TIDAK DITERIMA')";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_jumlah($params) {
        $sql = "SELECT SUM(IF(cs_st = 'DITERIMA', 1, 0)) AS 'diterima', 
				SUM(IF(cs_st = 'TIDAK DITERIMA', 1, 0)) AS 'tidak_diterima',
				SUM(IF(cs_st = 'PROSES', 1, 0)) AS 'proses',
				SUM(IF(cs_st = 'CADANGAN', 1, 0)) AS 'cadangan' 
				FROM psb_siswa ps 
				INNER JOIN psb p USING(psb_id) 
				INNER JOIN psb_jalur pj USING(jalurpsb_id)
				INNER JOIN jurusan j USING(jurusan_id) 
				WHERE psb_id = ? AND ps.`jurusan_id` LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function set_all_proses_to_reject($params) {
        $sql = "UPDATE psb_siswa SET cs_st = 'TIDAK DITERIMA' WHERE cs_st = 'PROSES' AND psb_id = ?";
        return $this->db->query($sql, $params);
    }

    function update_cs_st($params) {
        $this->db->trans_begin();
        foreach ($params as $key => $param) {
            $sql = "UPDATE psb_siswa SET cs_st = ? WHERE cs_id = ?";
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
