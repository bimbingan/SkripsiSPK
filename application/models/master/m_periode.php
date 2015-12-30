<?php

class m_periode extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_periode() {
        $sql = "SELECT * FROM periode ORDER BY tahun";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_all_periode_order_status(){
        $sql = "SELECT * FROM periode ORDER BY status DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_list_periode($params) {
        $sql = "SELECT * FROM periode WHERE tahun LIKE ? ORDER BY id ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }


    function get_one_periode( $id ){
        $sql = "SELECT * FROM periode WHERE id = ?"; 	// perintah sql berbentuk string
        $query = $this->db->query( $sql , $id); 	// perintah sql dieksekusi kemudian disimpan di dalam var query
        if($query->num_rows() > 0){			// query dicek apakah ada isinya atau tidak
            $result = $query->row_array();	// hasil dari query dipindahkan ke var result dengan menggunakan fungsi result_array (mempunyai baris banyak)
            $query->free_result();				// karena hasil sudah diperoleh maka query kita hapus
            return $result;					// setelah itu kita kembalikan hasil var result
        }else{
            return array();					// jika query tidak berhasil maka akan mengembalikan nilai array kosong
        }
    }

    function get_active_periode(){
        $sql = "SELECT * FROM periode WHERE status = '1'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_kuota_ipa($params){
        $sql = "SELECT kuota_ipa FROM periode WHERE id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['kuota_ipa'];
        } else {
            return array();
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

    function import($params){
        return $this->db->insert('nilai', $params);
    }

    function update_periode( $params, $where ){
        if($params['status'] == 1){
            $this->set_other_periode_inactive();
        }
        return $this->db->update('periode', $params, $where);
    }

    function insert_periode($params){
        if($params['status'] == 1){
            $this->set_other_periode_inactive();
        }
        return $this->db->insert('periode', $params);
    }
    // 
    // function get_kuota_ipa(){
    //     $sql = "SELECT pref_value FROM com_preferences WHERE pref_group = 'kuota' AND pref_nm = 'ipa'";
    //     $query = $this->db->query($sql);
    //     if ($query->num_rows() > 0) {
    //         $result = $query->row_array();
    //         $query->free_result();
    //         return $result['pref_value'];
    //     } else {
    //         return 0;
    //     }
    // }

    // non-aktifkan periode lain
    function set_other_periode_inactive($params = null) {
        $sql = "UPDATE periode SET status = '0'";
        if(!empty($params)){
            $sql .= " WHERE id != ?";
        }
        return $this->db->query($sql, $params);
    }

}
