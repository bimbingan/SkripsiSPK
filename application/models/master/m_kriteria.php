<?php

class m_kriteria extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_kriteria() {
        $sql = "SELECT * FROM kriteria ORDER BY id ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_kriteria($params){
      return $this->db->insert('kriteria', $params);
    }

    function delete_kriteria ($params){
      $sql= "DELETE FROM kriteria WHERE id = ?";
      return $this->db->query($sql, $params);
    }
}
