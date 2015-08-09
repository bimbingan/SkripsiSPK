<?php

class m_instansi extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list instansi
    function get_list_instansi($params) {
        $sql = "SELECT * from instansi WHERE instansi_nm LIKE ?
		ORDER BY instansi_nm ASC 
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

    // get total instansi
    function get_total_instansi($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM instansi WHERE instansi_nm LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get instansi by id
    function get_instansi_by_id($param) {
        $sql = "SELECT * FROM instansi WHERE instansi_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert instansi
    function insert_instansi($params) {
        $sql = "INSERT INTO instansi (instansi_nm, instansi_alamat, instansi_keterangan, mdb, mdd) 
		VALUES (?,?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update instansi
    function update_instansi($params) {
        $sql = "UPDATE instansi SET instansi_nm = ?, instansi_alamat = ?, instansi_keterangan = ?, mdb=?, mdd=NOW()
		WHERE instansi_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete instansi
    function delete_instansi($params) {
        $sql = "DELETE FROM instansi WHERE instansi_id = ?";
        return $this->db->query($sql, $params);
    }

}
