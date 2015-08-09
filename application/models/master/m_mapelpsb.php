<?php

class m_mapelpsb extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get last inserted id
    function get_last_inserted_id() {
        return $this->db->insert_id();
    }

    // get list mapelpsb
    function get_list_mapelpsb($params) {
        $sql = "SELECT * from psb_mapel WHERE mapel_nama LIKE ?
		ORDER BY mapel_cd ASC 
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

    // get total mapelpsb
    function get_total_mapelpsb($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM psb_mapel WHERE mapel_nama LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function generate_id() {
        $sql = "SELECT SUBSTRING(MAX(`mapel_cd`), 2, 4) as 'id' FROM psb_mapel";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if (((int) $result['id']) < 9) {
                return 'S000' . ((int) $result['id'] + 1);
            } elseif (((int) $result['id']) > 9 && ((int) $result['id']) < 99) {
                return 'S00' . ((int) $result['id'] + 1);
            } elseif (((int) $result['id']) > 99 && ((int) $result['id']) < 999) {
                return 'S0' . ((int) $result['id'] + 1);
            } else {
                return 'S' . ((int) $result['id'] + 1);
            }
        } else {
            return 0;
        }
    }

    // get mapel psb by id
    function get_mapelpsb_by_id($param) {
        $sql = "SELECT * FROM psb_mapel WHERE mapel_cd = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert mapelpsb
    function insert_mapelpsb($params) {
        $sql = "INSERT INTO psb_mapel (mapel_cd, mapel_nama, mapel_keterangan, mdb, mdd) 
		VALUES (?,?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update mapelpsb
    function update_mapelpsb($params) {
        $sql = "UPDATE psb_mapel SET mapel_nama = ?, mapel_keterangan = ?, mdb=?, mdd=NOW() WHERE mapel_cd = ?";
        return $this->db->query($sql, $params);
    }

    // delete mapelpsb
    function delete_mapelpsb($params) {
        $sql = "DELETE FROM psb_mapel WHERE mapel_cd = ?";
        return $this->db->query($sql, $params);
    }

}
