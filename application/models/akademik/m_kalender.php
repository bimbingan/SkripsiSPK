<?php

class m_kalender extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list kalender
    function get_list_kalender($params) {
        $sql = "SELECT * FROM kalender_akademik 
		WHERE kalender_type LIKE ?";
        if ($params[1] != '%') {
            $sql .= " AND kalender_tgl BETWEEN ? AND ?";
        } else {
            unset($params[1]);
            unset($params[2]);
            $params = array_values($params);
        }
        $sql .= "LIMIT ?, ?";

        // die();
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total kalender
    function get_total_kalender($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM kalender_akademik WHERE kalender_type LIKE ?";
        if ($params[1] != '%') {
            $sql .= " AND kalender_tgl BETWEEN ? AND ?";
        }
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get kalender by id
    function get_kalender_by_id($param) {
        $sql = "SELECT *, MIN(kalender_tgl) AS 'tgl_start', MAX(kalender_tgl) AS 'tgl_end' 
		FROM kalender_akademik WHERE kalender_keterangan IN 
		(SELECT kalender_keterangan FROM kalender_akademik WHERE kalender_id = ?)";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all kalender
    function get_all_kalender($param) {
        $sql = "SELECT * FROM kalender_akademik";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all cuti
    function get_all_for_kalender($params) {
        $sql = "SELECT kalender_akademik.*, MIN(kalender_tgl) as `tgl_start`, MAX(DATE_ADD(kalender_tgl, INTERVAL 1 DAY)) as `tgl_end`  FROM kalender_akademik GROUP BY kalender_keterangan";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert kalender
    function insert_kalender($params, $count) {
        $this->db->trans_begin();
        for ($i = 0; $i <= $count; $i++) {
            $sql = "INSERT INTO kalender_akademik (kalender_tgl, kalender_keterangan, kalender_type, mdb, mdd) 
			VALUES(DATE_ADD(?, INTERVAL " . $i . " DAY),?,?,?,NOW())";
            $this->db->query($sql, $params);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // update kalender
    function update_kalender($params, $old_keterangan, $count) {
        $this->delete_group_kalender($old_keterangan);
        return $this->insert_kalender($params, $count);
    }

    // delete kalender
    function delete_kalender($params) {
        $sql = "DELETE FROM kalender_akademik WHERE kalender_id = ?";
        return $this->db->query($sql, $params);
    }

    function delete_group_kalender($params) {
        $sql = "DELETE FROM kalender_akademik WHERE kalender_keterangan = ?";
        return $this->db->query($sql, $params);
    }

}
