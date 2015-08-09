<?php

class m_jenispengurus extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list jenispengurus
    function get_list_jenispengurus($params) {
        $sql = "SELECT * from jenis_pengurus WHERE jenis_nama LIKE ?
		ORDER BY jenis_nama ASC 
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

    // get total jenispengurus
    function get_total_jenispengurus($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM jenis_pengurus WHERE jenis_nama LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_jenispengurus_by_id($param) {
        $sql = "SELECT * FROM jenis_pengurus WHERE jenis_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_all_jenispengurus() {
        $sql = "SELECT jenis_id, jenis_nama from jenis_pengurus";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_jenispengurus($params) {
        $sql = "INSERT INTO jenis_pengurus (jenis_nama, mdb, mdd) 
		VALUES (?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update jenispengurus
    function update_jenispengurus($params) {
        $sql = "UPDATE jenis_pengurus SET jenis_nama = ?, mdb=?, mdd=NOW() WHERE jenis_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete jenispengurus
    function delete_jenispengurus($params) {
        $sql = "DELETE FROM jenis_pengurus WHERE jenis_id = ?";
        return $this->db->query($sql, $params);
    }

}
