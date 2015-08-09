<?php

class m_sekolah extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list sekolah
    function get_list_sekolah($params) {
        $sql = "SELECT * from sekolah WHERE sekolah_nama LIKE ?
		ORDER BY sekolah_nama ASC 
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

    // get total sekolah
    function get_total_sekolah($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM sekolah WHERE sekolah_nama LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_sekolah_by_id($param) {
        $sql = "SELECT * FROM sekolah WHERE sekolah_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_all_sekolah($param) {
        $sql = "SELECT * FROM sekolah";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function insert_sekolah($params) {
        $sql = "INSERT INTO sekolah (sekolah_nama, sekolah_alamat, mdb, mdd) 
		VALUES (?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update sekolah
    function update_sekolah($params) {
        $sql = "UPDATE sekolah SET sekolah_nama = ?, sekolah_alamat = ?, mdb=?, mdb=NOW()  WHERE sekolah_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete sekolah
    function delete_sekolah($params) {
        $sql = "DELETE FROM sekolah WHERE sekolah_id = ?";
        return $this->db->query($sql, $params);
    }

}
