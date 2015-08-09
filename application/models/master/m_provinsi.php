<?php

class m_provinsi extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list provinsi
    function get_list_provinsi($params) {
        $sql = "SELECT * from provinsi WHERE provinsi_nm LIKE ?
		ORDER BY provinsi_nm ASC 
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

    // get total provinsi
    function get_total_provinsi($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM provinsi WHERE provinsi_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get provinsi by id
    function get_provinsi_by_id($param) {
        $sql = "SELECT * FROM provinsi WHERE provinsi_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all provinsi
    function get_all_provinsi($param) {
        $sql = "SELECT * FROM provinsi";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert provinsi
    function insert_provinsi($params) {
        $sql = "INSERT INTO provinsi (provinsi_nm, mdb, mdd) 
		VALUES (?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update provinsi
    function update_provinsi($params) {
        $sql = "UPDATE provinsi SET provinsi_nm = ?, mdb=?, mdd=NOW()
		WHERE provinsi_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete provinsi
    function delete_provinsi($params) {
        $sql = "DELETE FROM provinsi WHERE provinsi_id = ?";
        return $this->db->query($sql, $params);
    }

}
