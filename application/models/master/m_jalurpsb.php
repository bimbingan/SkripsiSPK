<?php

class m_jalurpsb extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list jalurpsb
    function get_list_jalurpsb($params) {
        $sql = "SELECT * from psb_jalur WHERE jalurpsb_nama LIKE ?
		ORDER BY jalurpsb_nama ASC 
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

    // get total jalurpsb
    function get_total_jalurpsb($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM psb_jalur WHERE jalurpsb_nama LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get jalur psb by id
    function get_jalurpsb_by_id($param) {
        $sql = "SELECT * FROM psb_jalur WHERE jalurpsb_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all jalurpsb
    function get_all_jalurpsb($params) {
        $sql = "SELECT * from psb_jalur";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // insert jalurpsb
    function insert_jalurpsb($params) {
        $sql = "INSERT INTO psb_jalur (jalurpsb_nama, mdb, mdd) 
		VALUES (?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update jalurpsb
    function update_jalurpsb($params) {
        $sql = "UPDATE psb_jalur SET jalurpsb_nama = ?, mdb=?, mdd=NOW() WHERE jalurpsb_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete jalurpsb
    function delete_jalurpsb($params) {
        $sql = "DELETE FROM psb_jalur WHERE jalurpsb_id = ?";
        return $this->db->query($sql, $params);
    }

}
