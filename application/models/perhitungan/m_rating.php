<?php

class m_rating extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_rating($params) {
        $sql = "SELECT * FROM rating r INNER JOIN periode p ON r.`periode` = p.`id` WHERE nama_rating LIKE ? AND periode LIKE ? ORDER BY id_rating ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_one_rating( $id ){
		$sql = "SELECT * FROM rating WHERE id_rating = ?"; 	// perintah sql berbentuk string
		$query = $this->db->query( $sql , $id); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
		if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
			$result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
			$query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
			return $result;					// setelah itu kita kembalikan hasil var result
		}else{
			return array();					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
		}
	}

    function insert_rating($params){
      return $this->db->insert('rating', $params);
    }

    function delete_rating ($params){
      $sql= "DELETE FROM rating WHERE id_rating = ?";
      return $this->db->query($sql, $params);
    }

    function update_rating( $params, $where ){
      return $this->db->update('rating', $params, $where);
		}
}
