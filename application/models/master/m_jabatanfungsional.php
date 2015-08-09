<?php

class m_jabatanfungsional extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list jabatanfungsional
    function get_list_jabatanfungsional($params) {
        $sql = "SELECT * from jabatan_fungsional WHERE fungsional_tingkat LIKE ? AND fungsional_nama LIKE ?
		ORDER BY fungsional_order ASC 
		LIMIT ?, ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();

            return $result;
        } else {
            // echo $this->db->last_query();
            // die();
            return array();
        }
    }

    function get_all_jabatanfungsional() {
        $sql = "SELECT fungsional_id, fungsional_tingkat, fungsional_nama, fungsional_order from jabatan_fungsional";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total jabatanfungsional
    function get_total_jabatanfungsional($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM jabatan_fungsional WHERE fungsional_tingkat LIKE ? AND fungsional_nama LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_jabatanfungsional_by_id($param) {
        $sql = "SELECT * FROM jabatan_fungsional WHERE fungsional_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_new_order() {
        $sql = "SELECT MAX(fungsional_order) AS 'max_order' FROM jabatan_fungsional";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return ((int) $result['max_order']) + 1;
        } else {
            return 0;
        }
    }

    function get_first_and_last_order() {
        $sql = "SELECT MAX(fungsional_order) AS 'max_order', MIN(fungsional_order) AS 'min_order' FROM jabatan_fungsional";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function insert_jabatanfungsional($params) {
        $sql = "INSERT INTO jabatan_fungsional (fungsional_tingkat, fungsional_nama, fungsional_order, mdb, mdd) 
		VALUES (?,?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update jabatanfungsional
    function update_jabatanfungsional($params) {
        $sql = "UPDATE jabatan_fungsional SET fungsional_tingkat = ?, fungsional_nama = ?, mdb=?, mdd=NOW() WHERE fungsional_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete jabatanfungsional
    function delete_jabatanfungsional($params) {
        $sql = "DELETE FROM jabatan_fungsional WHERE fungsional_id = ?";
        return $this->db->query($sql, $params);
    }

    // up urutan
    function update_urutan($params, $operator) {
        $sql = "   UPDATE jabatan_fungsional
		SET fungsional_order = 
		CASE 
		WHEN fungsional_order = " . $params . " THEN (" . $params . " " . $operator . " 1)";
        // $operator = ($operator == "+") ? "-" : "+";
        $sql .= "WHEN fungsional_order = (" . $params . " " . $operator . " 1) THEN " . $params . "
		END
		WHERE fungsional_order  IN (" . $params . "," . $params . " " . $operator . " 1)";
        return $this->db->query($sql, $params);
    }

}
