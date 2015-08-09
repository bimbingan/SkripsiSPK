<?php

class m_unitkerja extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list unitkerja
    function get_list_unitkerja($params) {
        $sql = "SELECT * FROM unit_kerja WHERE unit_nm LIKE ?
		ORDER BY unit_nm ASC 
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

    // get total unit_kerja
    function get_total_unitkerja($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM unit_kerja WHERE unit_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get unit_kerja by id
    function get_unitkerja_by_id($param) {
        $sql = "SELECT * FROM unit_kerja WHERE unit_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all unitkerja
    function get_all_unitkerja($params) {
        $sql = "SELECT * FROM unit_kerja";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert unitkerja
    function insert_unitkerja($params) {
        $sql = "INSERT INTO unit_kerja (unit_nm, mdb, mdd) 
		VALUES (?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update unitkerja
    function update_unitkerja($params) {
        $sql = "UPDATE unit_kerja SET unit_nm = ?, mdb=?, mdd=NOW()
		WHERE unit_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete unitkerja
    function delete_unitkerja($params) {
        $sql = "DELETE FROM unit_kerja WHERE unit_id = ?";
        return $this->db->query($sql, $params);
    }

}
