<?php

class m_range_nilai extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_range_nilai() {
        $sql = "SELECT * FROM range_nilai ORDER BY id_range ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_one_range_nilai( $id ){
		$sql = "SELECT * FROM range_nilai WHERE id_range = ?"; 	// perintah sql berbentuk string
		$query = $this->db->query( $sql , $id); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
		if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
			$result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
			$query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
			return $result;					// setelah itu kita kembalikan hasil var result
		}else{
			return array();					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
		}
	}

    function insert_range_nilai($params){
      return $this->db->insert('range_nilai', $params);
    }

    function delete_range_nilai ($params){
      $sql= "DELETE FROM range_nilai WHERE id_range = ?";
      return $this->db->query($sql, $params);
    }

    function update_range_nilai( $params, $where ){
      return $this->db->update('range_nilai', $params, $where);
		}
}
