<?php

class m_nilai extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_nilai() {
        $sql = "SELECT n.*, s.nama FROM siswa s INNER JOIN nilai n USING (nis) ORDER BY nis ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_all_nilai_by_periode($params) {
        $sql = "SELECT n.*, s.nama FROM siswa s INNER JOIN nilai n USING (nis) WHERE s.`periode` = ? ORDER BY nis ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }



    function get_list_nilai($params) {
        $sql = "SELECT n.*, s.* FROM siswa s INNER JOIN nilai n USING (nis) INNER JOIN periode p ON p.`id` = s.`periode` WHERE s.`nama` LIKE ? and s.`periode` LIKE ? ORDER BY s.`nis` ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_cleaned_nilai() {
        $sql = "SELECT n.`mtk_un`, n.`ipa_un`, n.`bindo_un`, n.`bing_un`, n.`mtk_tes`, n.`ipa_tes`, n.`bing_tes`, n.`minat` FROM siswa s INNER JOIN nilai n USING (nis) ORDER BY nis ASC";
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

    function get_max_nilai(){
        $sql = "SELECT (SELECT MAX(nilai_akhir) FROM nilai n INNER JOIN siswa s USING(nis) WHERE s.`periode` = p.`id`) as 'nilai' FROM periode p ORDER BY tahun";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_min_nilai(){
        $sql = "SELECT (SELECT MIN(nilai_akhir) FROM nilai n INNER JOIN siswa s USING(nis) WHERE s.`periode` = p.`id`) as 'nilai' FROM periode p ORDER BY tahun";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
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
}
