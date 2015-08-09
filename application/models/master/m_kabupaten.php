<?php

class m_kabupaten extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list kabupaten
    function get_list_kabupaten($params) {
        $sql = "SELECT k.*, p.provinsi_nm FROM kabupaten k INNER JOIN provinsi p USING(provinsi_id) 
		WHERE kab_nm LIKE ? AND k.provinsi_id LIKE ?
		ORDER BY k.provinsi_id ASC 
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

    // get total kabupaten
    function get_total_kabupaten($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM kabupaten k INNER JOIN provinsi p USING(provinsi_id) 
		WHERE kab_nm LIKE ? AND k.provinsi_id LIKE ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get kabupaten by id
    function get_kabupaten_by_id($param) {
        $sql = "SELECT k.*, p.provinsi_nm FROM kabupaten k INNER JOIN provinsi p USING(provinsi_id) WHERE kab_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all kabupaten
    function get_all_kabupaten($param) {
        $sql = "SELECT k.*, p.provinsi_nm FROM kabupaten k INNER JOIN provinsi p USING(provinsi_id)";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get kabupaten by provinsi
    function get_kabupaten_by_prov($param) {
        $sql = "SELECT k.*, p.provinsi_nm FROM kabupaten k INNER JOIN provinsi p USING(provinsi_id) WHERE p.provinsi_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert kabupaten
    function insert_kabupaten($params) {
        $sql = "INSERT INTO kabupaten (kab_nm, provinsi_id, mdb, mdd) 
		VALUES (?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update kabupaten
    function update_kabupaten($params) {
        $sql = "UPDATE kabupaten SET kab_nm = ?, provinsi_id = ?, mdb=?, mdd=NOW()
		WHERE kab_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete kabupaten
    function delete_kabupaten($params) {
        $sql = "DELETE FROM kabupaten WHERE kab_id = ?";
        return $this->db->query($sql, $params);
    }

}
