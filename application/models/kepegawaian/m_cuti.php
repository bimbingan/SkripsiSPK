<?php

class m_cuti extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list cuti
    function get_list_cuti($params) {
        $sql = "SELECT c.*, ps.`guru_nama` FROM cuti c INNER JOIN pengurus_sekolah ps USING(guru_id) 
		WHERE guru_id LIKE ?
		ORDER BY c.`cuti_start` ASC 
		LIMIT ?, ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total cuti
    function get_total_cuti($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM cuti c INNER JOIN pengurus_sekolah ps USING(guru_id) 
		WHERE guru_id LIKE ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return array();
        }
    }

    // get cuti by id
    function get_cuti_by_id($params) {
        $sql = "SELECT c.*, ps.`guru_nama` 
		FROM cuti c INNER JOIN pengurus_sekolah ps USING(guru_id) 
		WHERE cuti_id = ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();

            return $result;
        } else {
            return array();
        }
    }

    // get all cuti
    function get_all_cuti($params) {
        $newParams = array(
            $params[0]
        );
        $this->db->query("SET @s := STR_TO_DATE(?, '%Y-%m-%d')", $params[1] . '-01-01');
        // echo $this->db->last_query();

        $this->db->query("SET @e := STR_TO_DATE(?, '%Y-%m-%d')", $params[1] . '-12-31');
        // echo $this->db->last_query();

        $sql = "SELECT c.*, ps.`guru_nama`, MONTH(ADDDATE(@s, n.id)) AS bulan, YEAR(ADDDATE(@s, n.id)) AS tahun, COUNT(c.cuti_id) AS jumlah, c.`guru_id`
		FROM nums n
		JOIN cuti c
		ON ((DATE(c.cuti_start) <= ADDDATE(@s, n.id)) AND (DATE(c.cuti_end) >= ADDDATE(@s, n.id))) INNER JOIN pengurus_sekolah ps USING(guru_id) 
		WHERE ADDDATE(@s, n.id) < @e AND ps.guru_nama LIKE ? GROUP BY bulan, c.`guru_id`";

        $query = $this->db->query($sql, $newParams);
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_cuti_by_guru($params, $group_by) {
        $tahun_cuti = $this->get_min_max_tahun_cuti_by_guru($params);
        $this->db->query("SET @s := STR_TO_DATE(?, '%Y-%m-%d')", $tahun_cuti['min'] . '-01-01');
        $this->db->query("SET @e := STR_TO_DATE(?, '%Y-%m-%d')", $tahun_cuti['max'] . '-12-31');

        $sql = "SELECT c.*, ps.`guru_nama`, ps.`guru_nik`, MONTH(ADDDATE(@s, n.id)) AS bulan, YEAR(ADDDATE(@s, n.id)) AS tahun ,  COUNT(c.cuti_id) AS jumlah,  c.`guru_id`
		FROM nums n
		JOIN cuti c
		ON ((DATE(c.cuti_start) <= ADDDATE(@s, n.id)) AND (DATE(c.cuti_end) >= ADDDATE(@s, n.id))) INNER JOIN pengurus_sekolah ps USING(guru_id) 
		WHERE ADDDATE(@s, n.id) < @e AND c.`guru_id` = ? GROUP BY $group_by, c.`guru_id`";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();

            return $result;
        } else {
            return array();
        }
    }

    // get all cuti
    function get_all_cuti_for_kalender($params) {
        $sql = "SELECT c.*, ps.`guru_nama`, DATE_ADD(cuti_end, INTERVAL 1 DAY) AS 'cuti_end_fixed' "
                . "FROM cuti c INNER JOIN pengurus_sekolah ps USING(guru_id)";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_tahun_cuti() {
        $sql = "SELECT YEAR(c.`cuti_start`) AS 'tahun' FROM cuti c 
    	 GROUP BY YEAR(c.`cuti_start`)";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_min_max_tahun_cuti() {
        $sql = "SELECT YEAR(MAX(GREATEST(cuti.`cuti_end`, cuti.`cuti_start`))) as 'max', 
    	 	YEAR(MIN(LEAST(cuti.`cuti_end`, cuti.`cuti_start`))) as 'min' FROM cuti";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_min_max_tahun_cuti_by_guru($params) {
        $sql = "SELECT YEAR(MAX(GREATEST(cuti.`cuti_end`, cuti.`cuti_start`))) as 'max', 
    	 		YEAR(MIN(LEAST(cuti.`cuti_end`, cuti.`cuti_start`))) as 'min' FROM cuti WHERE guru_id = ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_cuti($params) {
        return $this->db->insert('cuti', $params);
    }

    function update_cuti($params, $where) {
        return $this->db->update('cuti', $params, $where);
    }

    function delete_cuti($params) {
        $sql = "DELETE FROM cuti WHERE cuti_id = ?";
        return $this->db->query($sql, $params);
    }

}
