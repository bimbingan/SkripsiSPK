<?php

class m_preference extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_list_preference_by_group($params) {
        $sql = "SELECT * from com_preferences WHERE pref_group = ?
		ORDER BY pref_nm ASC 
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

    function get_list_preference_by_group_with_search($params) {
        $sql = "SELECT * from com_preferences WHERE pref_group = ? AND pref_nm LIKE ? AND pref_value LIKE ?
		ORDER BY pref_nm ASC 
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

    function get_preference_by_group($params) {
        $sql = "SELECT `pref_id`, `pref_group`, `pref_nm`, `pref_value` FROM com_preferences WHERE pref_group = ?";
        $query = $this->db->query($sql, $params);

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_preference_group_by_name($params) {
        $sql = "SELECT `pref_id`, `pref_group`, `pref_nm`, `pref_value` FROM com_preferences WHERE pref_group = ? GROUP BY pref_nm";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_preference_by_group_and_name($params) {
        $sql = "SELECT `pref_id`, `pref_group`, `pref_nm`, `pref_value` FROM com_preferences WHERE pref_group = ? AND pref_nm = ?";
        $query = $this->db->query($sql, $params);

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_total_preference_by_group($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM com_preferences WHERE pref_group = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_total_preference_by_group_with_search($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM com_preferences WHERE pref_group = ? AND pref_nm LIKE ? AND pref_value LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_preference_by_id($params) {
        $sql = "SELECT `pref_id`, `pref_group`, `pref_nm`, `pref_value` FROM com_preferences WHERE pref_id = ?";
        $query = $this->db->query($sql, $params);

        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function insert_preference($params) {
        $sql = "INSERT INTO com_preferences (pref_group, pref_nm, pref_value, mdb, mdd) 
		VALUES (?,?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    function update_preference($params) {
        $sql = "UPDATE com_preferences SET pref_group=?, pref_nm = ?, pref_value = ?, mdb = ?, mdd = NOW() WHERE pref_id = ?";
        return $this->db->query($sql, $params);
    }

    function delete_preference($params) {
        $sql = "DELETE FROM com_preferences WHERE pref_id = ?";
        return $this->db->query($sql, $params);
    }

}
