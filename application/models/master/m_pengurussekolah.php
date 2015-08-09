<?php

class m_pengurussekolah extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list pengurussekolah
    function get_list_pengurussekolah($params) {
        
        $sql = "SELECT * from pengurus_sekolah WHERE guru_nik LIKE ? AND guru_nama LIKE ? AND COALESCE(jabatan_id,'') LIKE ? AND guru_st LIKE ? ORDER BY guru_nama ASC LIMIT ?, ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total pengurussekolah
    function get_total_pengurussekolah($params) {
        
        $sql = "SELECT COUNT(*) AS 'total' FROM pengurus_sekolah WHERE guru_nik LIKE ? AND guru_nama LIKE ? AND COALESCE(jabatan_id,'') LIKE ? AND guru_st LIKE ?";
        $query = $this->db->query($sql, $params);

        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_pengurussekolah_by_nik($param) {
        $sql = "SELECT * FROM pengurus_sekolah WHERE guru_nik = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_all_pengurussekolah() {
        $sql = "SELECT * FROM pengurus_sekolah";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function insert_pengurussekolah($params) {
        return $this->db->insert('pengurus_sekolah', $params);
    }

    // update pengurussekolah
    function update_pengurussekolah($params, $where) {
        return $this->db->update('pengurus_sekolah', $params, $where);
    }

    // delete pengurussekolah
    function delete_pengurussekolah($params) {
        $sql = "DELETE FROM pengurus_sekolah WHERE guru_nik = ?";
        return $this->db->query($sql, $params);
    }

}
