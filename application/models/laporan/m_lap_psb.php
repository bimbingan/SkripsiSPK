<?php

class m_lap_psb extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_list($params) {
        $sql = "SELECT ps.`cs_id`, ps.`cs_nama`, s.`sekolah_nama`, pv.`provinsi_nm`, k.`kab_nm`, p.`psb_tha`, pj.`jalurpsb_nama`, ps.`cs_st`
		FROM psb_siswa ps 
		INNER JOIN psb p USING (psb_id)
		INNER JOIN psb_jalur pj USING(jalurpsb_id)
		INNER JOIN sekolah s USING (sekolah_id)
		INNER JOIN provinsi pv USING (provinsi_id)
		INNER JOIN kabupaten k USING (kab_id)
		INNER JOIN agama a USING (agama_cd) 
		WHERE ps.`sekolah_id` LIKE ?
		AND pv.`provinsi_id` LIKE ? 
		AND k.`kab_id` LIKE ? 
		AND p.`psb_tha` LIKE ?
		AND p.`jalurpsb_id` LIKE ?
		AND cs_st LIKE ?
		ORDER BY p.`psb_tha` DESC LIMIT ?, ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_list($params) {
        $sql = "SELECT COUNT(*) as 'total' 
		FROM psb_siswa ps 
		INNER JOIN psb p USING (psb_id)
		INNER JOIN psb_jalur pj USING(jalurpsb_id)
		INNER JOIN sekolah s USING (sekolah_id)
		INNER JOIN provinsi pv USING (provinsi_id)
		INNER JOIN kabupaten k USING (kab_id)
		INNER JOIN agama a USING (agama_cd) 
		WHERE ps.`sekolah_id` LIKE ?
		AND pv.`provinsi_id` LIKE ? 
		AND k.`kab_id` LIKE ? 
		AND p.`psb_tha` LIKE ?
		AND p.`jalurpsb_id` LIKE ?
		AND cs_st LIKE ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

}
