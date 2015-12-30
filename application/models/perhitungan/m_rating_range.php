<?php

class m_rating_range extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_rating_range() {
        $sql = "SELECT r.`nama_rating`, r.`group_rating`, rr.`nilai_range`, rn.`batas_atas`, rn.`batas_bawah`, rr.`id`, periode.`tahun`
        FROM rating_range rr INNER JOIN rating r USING(id_rating) INNER JOIN periode ON periode.`id` = r.`periode`
        INNER JOIN range_nilai rn USING(id_range)";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_all_rating() {
        $sql = "SELECT * FROM rating INNER JOIN periode ON rating.`periode` = periode.`id` ORDER BY id_rating ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
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

    function get_one_rating_range( $id ){
        $sql = "SELECT * FROM rating_range WHERE id = ?"; 	// perintah sql berbentuk string
        $query = $this->db->query( $sql , $id); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
        if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
            $result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
            $query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
            return $result;					// setelah itu kita kembalikan hasil var result
        }else{
            return array();					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
        }
    }


    function get_rating_by_nilai($params){
        $sql_params = array($params[0], $params[0], $params[1]);
        $sql = "SELECT nilai_range FROM rating_range rr INNER JOIN range_nilai rn USING(id_range)
        WHERE rn.`batas_atas` >= ? AND rn.`batas_bawah` <= ? AND rr.`id_rating` = ?";
        $query = $this->db->query( $sql , $sql_params); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
        if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
            $result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
            $query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
            return $result['nilai_range'];					// setelah itu kita kembalikan hasil var result
        }else{
            return 0;					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
        }
    }

    function insert_rating_range($params){
        return $this->db->insert('rating_range', $params);
    }

    function delete_rating_range ($params){
        $sql= "DELETE FROM rating_range WHERE id = ?";
        return $this->db->query($sql, $params);
    }

    function update_rating_range( $params, $where ){
        return $this->db->update('rating_range', $params, $where);
    }
}
