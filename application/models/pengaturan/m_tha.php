<?php

class m_tha extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // list tahun ajaran
    function get_list_tahun_ajaran($params) {

        $sql = "SELECT * from tahun_ajaran WHERE tha_tahun_ajaran LIKE ?
		ORDER BY tha_tahun_ajaran DESC 
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

    // total tahun ajaran
    function get_total_tahun_ajaran($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM tahun_ajaran WHERE tha_tahun_ajaran LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // insert tha
    function insert_tahun_ajaran($params) {
        $status = false;
        for ($i = 1; $i <= 2; $i++) {
            $sql = "INSERT INTO tahun_ajaran (tha_tahun_ajaran, tha_semester, tha_aktif) 
			VALUES (?,$i,0)";
            if ($this->db->query($sql, $params)) {
                $status = true;
            } else {
                $status = false;
            }
        }
        return $status;
    }

    // non-aktifkan semester lain
    function set_other_tha_inactive($params) {
        $sql = "UPDATE tahun_ajaran SET tha_aktif = 0 WHERE tha_id != ?";
        return $this->db->query($sql, $params);
    }

    // update tahun ajaran
    function update_tahun_ajaran($params) {
        $sql = "UPDATE tahun_ajaran SET tha_tahun_ajaran = ?, tha_semester = ?, tha_aktif = ? WHERE tha_id = ?";
        $this->set_other_tha_inactive($params[3]);
        return $this->db->query($sql, $params);
    }

    // get tha by id
    function get_tahun_ajaran_by_id($params) {
        $sql = "SELECT * FROM tahun_ajaran WHERE tha_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all tha
    function get_all_tahun_ajaran($params) {
        $sql = "SELECT * FROM tahun_ajaran";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get tha group by tha
    function get_tahun_ajaran_group_by_tha($params) {
        $sql = "SELECT * FROM tahun_ajaran GROUP BY tha_tahun_ajaran";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all tha
    function get_tha_by_tha($params) {
        $sql = "SELECT * FROM tahun_ajaran WHERE tha_tahun_ajaran = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get active tha
    function get_active_tha(){
    	$sql = "SELECT LEFT(tha_tahun_ajaran, 4) as 'tahun_ajaran' FROM tahun_ajaran WHERE tha_aktif = '1'";
    	$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['tahun_ajaran'];
        } else {
            return array();
        }
    }

    // delete tahun ajaran
    function delete_tahun_ajaran($params) {
        $sql = "DELETE FROM tahun_ajaran WHERE tha_id = ?";
        return $this->db->query($sql, $params);
    }

}
