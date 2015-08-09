<?php

class m_jabatanstruktural extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list jabatanstruktural
    function get_list_jabatanstruktural($params) {
        $sql = "SELECT * FROM jabatan_struktural WHERE struktural_nm LIKE ?
		ORDER BY struktural_nm ASC 
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

    // get all jabatanstruktural
    function get_all_jabatanstruktural($params) {
        $sql = "SELECT * FROM jabatan_struktural";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total jabatan_struktural
    function get_total_jabatanstruktural($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM jabatan_struktural WHERE struktural_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get jabatan_struktural by id
    function get_jabatanstruktural_by_id($param) {
        $sql = "SELECT * FROM jabatan_struktural WHERE struktural_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert jabatanstruktural
    function insert_jabatanstruktural($params) {
        $sql = "INSERT INTO jabatan_struktural (struktural_nm, mdb, mdd) 
		VALUES (?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update jabatanstruktural
    function update_jabatanstruktural($params) {
        $sql = "UPDATE jabatan_struktural SET struktural_nm = ?, mdb=?, mdd=NOW()
		WHERE struktural_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete jabatanstruktural
    function delete_jabatanstruktural($params) {
        $sql = "DELETE FROM jabatan_struktural WHERE struktural_id = ?";
        return $this->db->query($sql, $params);
    }

}
