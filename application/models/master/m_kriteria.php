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

    function get_one_kriteria( $id ){
    $sql = "SELECT * FROM kriteria WHERE id = ?"; 	// perintah sql berbentuk string
    $query = $this->db->query( $sql , $id); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
    if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
      $result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
      $query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
      return $result;					// setelah itu kita kembalikan hasil var result
    }else{
      return array();					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
    }
  }

    function insert_kriteria($params){
      return $this->db->insert('kriteria', $params);
    }

    function delete_kriteria ($params){
      $sql= "DELETE FROM kriteria WHERE id = ?";
      return $this->db->query($sql, $params);
    }

    function update_kriteria( $params, $where ){
      return $this->db->update('kriteria', $params, $where);
		}
}
