<?php

class m_mapel_group extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_list_mapel_group($params) {
        $sql = "SELECT * FROM matapelajaran_group WHERE mapelgroup_nm LIKE ? LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_mapel_group($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM matapelajaran_group WHERE mapelgroup_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_all_mapel_group($params) {
        $sql = "SELECT mapelgroup_id, mapelgroup_nm FROM matapelajaran_group";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_mapel_group_by_id($params) {
        $sql = "SELECT * FROM matapelajaran_group WHERE mapelgroup_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function is_mapel_group_exist($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM matapelajaran_group WHERE mapelgroup_nm = ?";
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

    function insert_mapel_group($params) {
        return $this->db->insert('matapelajaran_group', $params);
    }

    function update_mapel_group($params, $where) {
        return $this->db->update('matapelajaran_group', $params, $where);
    }

    function delete_mapel_group($params) {
        $sql = "DELETE FROM matapelajaran_group WHERE mapelgroup_id = ?";
        return $this->db->query($sql, $params);
    }

}
