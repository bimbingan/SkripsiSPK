<?php

class m_agama extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get agama by id
    function get_agama_by_id($param) {
        $sql = "SELECT * FROM agama WHERE agama_cd = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get all agama
    function get_all_agama($param) {
        $sql = "SELECT * FROM agama";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

}
