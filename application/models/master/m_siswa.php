<?php

class m_siswa extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_list_siswa($params) {
        $sql = "SELECT * FROM siswa s INNER JOIN periode p ON s.`periode` = p.`id` WHERE nama LIKE ? AND periode LIKE ? ORDER BY nis ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_all_siswa() {
        $sql = "SELECT * FROM siswa ORDER BY nis ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_one_siswa( $id ){
        $sql = "SELECT * FROM siswa WHERE nis = ?"; 	// perintah sql berbentuk string
        $query = $this->db->query( $sql , $id); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
        if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
            $result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
            $query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
            return $result;					// setelah itu kita kembalikan hasil var result
        }else{
            return array();					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
        }
    }

    function get_nama_by_nis($nis){
        $sql = "SELECT nama FROM siswa WHERE nis = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['nama'];
        } else {
            return array();
        }
    }

    function get_jumlah_siswa_by_jurusan_per_year($params){
        $sql = "SELECT (SELECT COUNT(nis) FROM siswa s INNER JOIN nilai n USING(nis)
        WHERE s.`periode` = p.`id` AND n.`hasil` = ? ) as 'jumlah' FROM periode p ORDER BY p.`tahun`";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_siswa($params){
        $sql = "SET @disable_trigger = 0";
        $this->db->query($sql);
        return $this->db->insert('siswa', $params);
    }

    function delete_siswa ($params){
        $sql= "DELETE FROM siswa WHERE nis = ?";
        return $this->db->query($sql, $params);
    }

    function update_siswa( $params, $where ){
        return $this->db->update('siswa', $params, $where);
    }

    function is_nis_exist($params){
        $sql = "SELECT count(*) as 'total' from siswa WHERE nis =?";
        $query =$this->db->query($sql, $params);
        if ($query->num_rows() > 0){
            $result = $query->row_array();
            $query->free_result();
            return ($result['total'] > 0) ? TRUE : FALSE;
        }else {
            return NULL;
        }
    }
}
