<?php

class m_siswa extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_siswa() {
        $sql = "SELECT * FROM siswa";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_siswa($params){
      return $this->db->insert('siswa', $params); 
    }
}
