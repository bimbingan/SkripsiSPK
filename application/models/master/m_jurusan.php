<?php

class m_jurusan extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_new_id() {
        $sql = "SELECT CAST(MAX(jurusan_id) AS SIGNED) as 'id' FROM jurusan";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();

            if ($result['id'] < 10) {
                return "0" . ($result['id'] + 1);
            } else if ($result['id'] >= 10 && $result['id'] < 100) {
                return ($result['id'] + 1);
            }
        } else {
            return 0;
        }
    }

    function get_list_jurusan($params) {
        $sql = "SELECT * FROM jurusan WHERE `jurusan_nm` LIKE ? LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_jurusan($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM jurusan WHERE jurusan_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_all_jurusan($params) {
        $sql = "SELECT * FROM jurusan";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_jurusan_by_id($params) {
        $sql = "SELECT * FROM jurusan WHERE jurusan_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function is_jurusan_exist($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM jurusan WHERE jurusan_nm = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if ($result['total'] > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    function get_top_jurusan(){
    	$sql = "SELECT MIN(jurusan_id) as 'jurusan_id' FROM jurusan";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['jurusan_id'];
        } else {
            return null;
        }
    }

    function insert_jurusan($params) {
        return $this->db->insert('jurusan', $params);
    }

    function update_jurusan($params, $where) {
        return $this->db->update('jurusan', $params, $where);
    }

    function delete_jurusan($params) {
        $sql = "DELETE FROM jurusan WHERE jurusan_id = ?";
        return $this->db->query($sql, $params);
    }

}
