<?php

class m_statuspengurus extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list statuspengurus
    function get_list_statuspengurus($params) {
        $sql = "SELECT * FROM status_pengurus WHERE status_nm LIKE ?
		ORDER BY status_nm ASC 
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

    // get total status_pengurus
    function get_total_statuspengurus($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM status_pengurus WHERE status_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get status_pengurus by id
    function get_statuspengurus_by_id($param) {
        $sql = "SELECT * FROM status_pengurus WHERE status_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all statuspengurus
    function get_all_statuspengurus($params) {
        $sql = "SELECT status_id, status_nm FROM status_pengurus ";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }


    // insert statuspengurus
    function insert_statuspengurus($params) {
        $sql = "INSERT INTO status_pengurus (status_nm, mdb, mdd) 
		VALUES (?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update statuspengurus
    function update_statuspengurus($params) {
        $sql = "UPDATE status_pengurus SET status_nm = ?, mdb=?, mdd=NOW()
		WHERE status_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete statuspengurus
    function delete_statuspengurus($params) {
        $sql = "DELETE FROM status_pengurus WHERE status_id = ?";
        return $this->db->query($sql, $params);
    }

}
