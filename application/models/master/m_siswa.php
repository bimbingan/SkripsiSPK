<?php

class m_siswa extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list siswa
    function get_list_siswa($params) {
        $sql = "SELECT sw.*, s.sekolah_nama, pv.provinsi_nm, k.kab_nm, a.agama_nm 
		FROM siswa sw 
		INNER JOIN sekolah s USING (sekolah_id)
		INNER JOIN provinsi pv USING (provinsi_id)
		INNER JOIN kabupaten k USING (kab_id)
		INNER JOIN agama a USING (agama_cd) WHERE siswa_nis LIKE ? AND siswa_nama LIKE ? 
		ORDER BY sw.`siswa_nis` DESC LIMIT ?, ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total siswa
    function get_total_siswa($params) {

        $sql = "SELECT COUNT(*) AS 'total' 
		FROM siswa sw
		INNER JOIN sekolah s USING (sekolah_id)
		INNER JOIN provinsi pv USING (provinsi_id)
		INNER JOIN kabupaten k USING (kab_id)
		INNER JOIN agama a USING (agama_cd) WHERE siswa_nis LIKE ? AND siswa_nama LIKE ? 
		ORDER BY sw.`siswa_nis` DESC";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get siswa by id
    function get_siswa_by_id($params) {
        $sql = "SELECT sw.*, s.sekolah_nama, pv.provinsi_nm, k.kab_nm, a.agama_nm 
		FROM siswa sw 
		INNER JOIN sekolah s USING (sekolah_id)
		INNER JOIN provinsi pv USING (provinsi_id)
		INNER JOIN kabupaten k USING (kab_id)
		INNER JOIN agama a USING (agama_cd) WHERE siswa_id = ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

}
