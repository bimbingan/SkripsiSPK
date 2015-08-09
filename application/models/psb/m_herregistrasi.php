<?php

class m_herregistrasi extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_passed_siswa($param) {
        $sql = "SELECT ps.*, p.`psb_tha`, pj.`jalurpsb_nama`, s.`sekolah_nama`, pv.`provinsi_nm`, k.`kab_nm`, a.`agama_nm` 
		FROM psb_siswa ps 
		INNER JOIN psb p USING (psb_id)
		INNER JOIN psb_jalur pj USING(jalurpsb_id)
		INNER JOIN sekolah s USING (sekolah_id)
		INNER JOIN provinsi pv USING (provinsi_id)
		INNER JOIN kabupaten k USING (kab_id)
		INNER JOIN agama a USING (agama_cd) WHERE cs_no_pendaftaran = ? AND cs_st = 'DITERIMA'";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function insert_siswa($params) {
        return $this->db->insert('siswa', $params);
    }

    function update_siswa($params, $where) {
        return $this->db->update('siswa', $params, $where);
    }

}
