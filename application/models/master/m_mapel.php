<?php

class m_mapel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list mapel
    function get_list_mapel($params) {
        $sql = "SELECT mp.*, g.`grade_nm`, mg.`mapelgroup_nm` FROM mata_pelajaran mp 
        INNER JOIN grade g USING(grade_id) 
        INNER JOIN matapelajaran_group mg USING(mapelgroup_id) 
        WHERE mapel_nama LIKE ? AND grade_id LIKE ? AND mapel_sem LIKE ? AND mapel_st LIKE ? AND mapelgroup_id LIKE ?
		ORDER BY mapel_nama ASC 
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

    // get total mapel
    function get_total_mapel($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM mata_pelajaran mp 
        INNER JOIN grade g USING(grade_id) 
        INNER JOIN matapelajaran_group mg USING(mapelgroup_id) 
        WHERE mapel_nama LIKE ? AND grade_id LIKE ? AND mapel_sem LIKE ? AND mapel_st LIKE ? AND mapelgroup_id LIKE ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_mapel_by_id($param) {
        $sql = "SELECT mp.*, g.`grade_nm`, mg.`mapelgroup_nm` FROM mata_pelajaran mp 
        INNER JOIN grade g USING(grade_id) 
        INNER JOIN matapelajaran_group mg USING(mapelgroup_id) WHERE mapel_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert mapel
    function insert_mapel($params) {
        return $this->db->insert('mata_pelajaran', $params);
    }

    // update mapel
    function update_mapel($params, $where) {
        return $this->db->update('mata_pelajaran', $params, $where);
    }

    // delete mapel
    function delete_mapel($params) {
        $sql = "DELETE FROM mata_pelajaran WHERE mapel_id = ?";
        return $this->db->query($sql, $params);
    }

}
