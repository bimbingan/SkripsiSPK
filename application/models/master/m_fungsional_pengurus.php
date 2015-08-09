<?php

class m_fungsional_pengurus extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list fungsional_pengurus
    function get_list_fungsional_pengurus($params) {
        $sql = "SELECT fp.*, jf.fungsional_nama, jf.fungsional_tingkat, ps.guru_nama
		FROM fungsional_pengurus fp 
		INNER JOIN pengurus_sekolah ps ON fp.guru_id = ps.guru_id 
		INNER JOIN jabatan_fungsional jf ON fp.fungsional_id = jf.fungsional_id 
		WHERE fp.guru_id LIKE ? AND fp.fungsional_id LIKE ? AND fp.fp_st LIKE ?
		ORDER BY ps.guru_nama ASC 
		LIMIT ?, ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total fungsional_pengurus
    function get_total_fungsional_pengurus($params) {
        $sql = "SELECT COUNT(*) AS 'total'
		FROM fungsional_pengurus fp 
		INNER JOIN pengurus_sekolah ps ON fp.guru_id = ps.guru_id 
		INNER JOIN jabatan_fungsional jf ON fp.fungsional_id = jf.fungsional_id 
		WHERE fp.guru_id LIKE ? AND fp.fungsional_id LIKE ? AND fp.fp_st LIKE ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get fungsional_pengurus by id
    function get_fungsional_pengurus_by_id($param) {
        $sql = "SELECT fp.*,  jf.fungsional_nama, jf.fungsional_tingkat, ps.guru_nama
		FROM fungsional_pengurus fp 
		INNER JOIN pengurus_sekolah ps ON fp.guru_id = ps.guru_id 
		INNER JOIN jabatan_fungsional jf ON fp.fungsional_id = jf.fungsional_id
		WHERE fp.fp_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert fungsional_pengurus
    function insert_fungsional_pengurus($params) {
        $sql = "INSERT INTO fungsional_pengurus (guru_id, fungsional_id, tanggal_mulai, tanggal_selesai, fp_st, mdb, mdd) 
		VALUES (?,?,?,?,?,?,NOW())";

        if ($this->db->query($sql, $params)) {
            if ($params[4] == 1) {
                $update_params = array(
                    $params[1],
                    $params[0]
                );
                if ($this->_update_jabatan($update_params)) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    // update fungsional_pengurus
    function update_fungsional_pengurus($params) {
        $sql = "UPDATE fungsional_pengurus SET guru_id = ?, fungsional_id = ?, tanggal_mulai = ?, tanggal_selesai =?, fp_st = ?, mdb=?, mdd=NOW()
		WHERE fp_id = ?";
        if ($this->db->query($sql, $params)) {
            if ($params[4] == 1) {
                $update_params = array(
                    $params[1],
                    $params[0]
                );
                if ($this->_update_jabatan($update_params)) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    // delete fungsional_pengurus
    function delete_fungsional_pengurus($params) {
        $sql = "DELETE FROM fungsional_pengurus WHERE fp_id = ?";
        return $this->db->query($sql, $params);
    }

    function _update_jabatan($params) {
        $sql = "UPDATE pengurus_sekolah SET fungsional_id = ? WHERE guru_id = ?";
        return $this->db->query($sql, $params);
    }

}
