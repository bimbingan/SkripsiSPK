<?php

class m_grade extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_list_grade($params) {
        $sql = "SELECT * FROM grade WHERE grade_nm LIKE ? LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_grade($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM grade WHERE grade_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_all_grade($params) {
        $sql = "SELECT grade_id, grade_nm FROM grade";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_grade_by_id($params) {
        $sql = "SELECT * FROM grade WHERE grade_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function is_grade_exist($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM grade WHERE grade_nm = ?";
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

    function get_least_grade(){
    	$sql = "SELECT MIN(grade_nm) as 'least', grade_id  FROM grade";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['grade_id'];
        } else {
            return 0;
        }
    }

    function insert_grade($params) {
        return $this->db->insert('grade', $params);
    }

    function update_grade($params, $where) {
        return $this->db->update('grade', $params, $where);
    }

    function delete_grade($params) {
        $sql = "DELETE FROM grade WHERE grade_id = ?";
        return $this->db->query($sql, $params);
    }

}
