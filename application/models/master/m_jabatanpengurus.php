<?php

class m_jabatanpengurus extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list jabatanpengurus
    function get_list_jabatanpengurus($params) {
        $sql = "SELECT * from jabatan_pengurus WHERE jabatan_nama LIKE ? AND jabatan_induk LIKE ?
		ORDER BY jabatan_nama ASC 
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

    function get_all_jabatanpengurus() {
        $sql = "SELECT jabatan_id, jabatan_nama from jabatan_pengurus";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total jabatanpengurus
    function get_total_jabatanpengurus($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM jabatan_pengurus WHERE jabatan_nama LIKE ? AND jabatan_induk LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_jabatanpengurus_by_id($param) {
        $sql = "SELECT * FROM jabatan_pengurus WHERE jabatan_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_jabatanpengurus_except_id($param) {
        $sql = "SELECT * FROM jabatan_pengurus WHERE jabatan_id != ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function insert_jabatanpengurus($params) {
        $sql = "INSERT INTO jabatan_pengurus (jabatan_nama, jabatan_induk, mdb, mdd) 
		VALUES (?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update jabatanpengurus
    function update_jabatanpengurus($params) {
        $sql = "UPDATE jabatan_pengurus SET jabatan_nama = ?, jabatan_induk = ?,mdb=?, mdd=NOW() WHERE jabatan_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete jabatanpengurus
    function delete_jabatanpengurus($params) {
        $sql = "DELETE FROM jabatan_pengurus WHERE jabatan_id = ?";
        return $this->db->query($sql, $params);
    }

    function is_induk_used($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM jabatan_pengurus WHERE jabatan_induk = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

}
