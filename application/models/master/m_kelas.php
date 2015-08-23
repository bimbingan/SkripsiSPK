<?php

class m_kelas extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_list_kelas($params) {
        $sql = "SELECT k.*, g.`grade_nm`, j.`jurusan_nm`, ps.`guru_nama` FROM kelas k
		INNER JOIN grade g USING(grade_id)
		INNER JOIN jurusan j USING(jurusan_id)
		INNER JOIN pengurus_sekolah ps ON k.`wali_kelas`= ps.`guru_id`
		WHERE k.`kelas_nm` LIKE ? AND g.`grade_id` LIKE ? AND j.`jurusan_id` LIKE ? AND k.`kelas_st` LIKE ? LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_kelas($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM kelas WHERE kelas_nm LIKE ? AND grade_id LIKE ? AND jurusan_id LIKE ? AND kelas_st LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_all_kelas($params) {
        $sql = "SELECT kelas_id, kelas_nm FROM kelas k
		INNER JOIN grade g USING(grade_id)
		INNER JOIN jurusan j USING(jurusan_id)
		INNER JOIN pengurus_sekolah ps ON k.`wali_kelas`= ps.`guru_id`";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_kelas_teori_active_with_grade_and_jurusan($params){
    	$sql = "SELECT kelas_id, kelas_nm FROM kelas k
		INNER JOIN grade g USING(grade_id)
		INNER JOIN jurusan j USING(jurusan_id)
		INNER JOIN pengurus_sekolah ps ON k.`wali_kelas`= ps.`guru_id`
		WHERE k.`kelas_st` = 'AKTIF' AND k.`kelas_jenis` = 'TEORI' AND k.`grade_id` = ? AND k.`jurusan_id` = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_kelas_by_id($params) {
        $sql = "SELECT * FROM kelas WHERE kelas_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_kelas($params) {
        return $this->db->insert('kelas', $params);
    }

    function update_kelas($params, $where) {
        return $this->db->update('kelas', $params, $where);
    }

    function delete_kelas($params) {
        $sql = "DELETE FROM kelas WHERE kelas_id = ?";
        return $this->db->query($sql, $params);
    }

}
