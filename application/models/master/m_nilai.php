<?php

class m_nilai extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_nilai() {
        $sql = "SELECT * FROM nilai ORDER BY nis ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_one_nilai( $nis ){
		$sql = "SELECT * FROM nilai WHERE nis = ?"; 	// perintah sql berbentuk string
		$query = $this->db->query( $sql , $nis); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
		if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
			$result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
			$query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
			return $result;					// setelah itu kita kembalikan hasil var result
		}else{
			return array();					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
		}
	}

    function insert_nilai($params){
      return $this->db->insert('nilai', $params);
    }

    function delete_nilai($params){
      $sql= "DELETE FROM nilai WHERE nis = ?";
      return $this->db->query($sql, $params);
    }

    function update_nilai( $params, $where ){
      return $this->db->update('nilai', $params, $where);
		}
}
