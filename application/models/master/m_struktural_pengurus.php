<?php

class m_struktural_pengurus extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list struktural_pengurus
    function get_list_struktural_pengurus($params) {
        $sql = "SELECT sp.*, js.struktural_nm, ps.guru_nama, jp.jabatan_nama
		FROM struktural_pengurus sp INNER JOIN jabatan_struktural js USING(struktural_id)
		INNER JOIN pengurus_sekolah ps USING(guru_id)
		LEFT JOIN jabatan_pengurus jp ON ps.jabatan_id = jp.jabatan_id
		WHERE sp.struktural_id LIKE ? AND ps.guru_nama LIKE ? AND COALESCE(ps.jabatan_id,'') LIKE ? AND sp.sp_st LIKE ?
		LIMIT ?,?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total struktural_pengurus
    function get_total_struktural_pengurus($params) {
        $sql = "SELECT COUNT(*) AS 'total'
		FROM struktural_pengurus sp INNER JOIN jabatan_struktural js USING(struktural_id)
		INNER JOIN pengurus_sekolah ps USING(guru_id)
		LEFT JOIN jabatan_pengurus jp ON ps.jabatan_id = jp.jabatan_id
		WHERE sp.struktural_id LIKE ? AND ps.guru_nama LIKE ? AND COALESCE(ps.jabatan_id,'') LIKE ? AND sp.sp_st LIKE ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get struktural_pengurus by id
    function get_struktural_pengurus_by_id($param) {
        $sql = "SELECT sp.*, js.struktural_nm, ps.guru_nama, jp.jabatan_nama
		FROM struktural_pengurus sp INNER JOIN jabatan_struktural js USING(struktural_id)
		INNER JOIN pengurus_sekolah ps USING(guru_id)
		INNER JOIN jabatan_pengurus jp USING(jabatan_id)
		WHERE sp.sp_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // insert struktural_pengurus
    function insert_struktural_pengurus($params) {
        $sql = "INSERT INTO struktural_pengurus (struktural_id, guru_id, tanggal_mulai, tanggal_selesai, sp_st, mdb, mdd) 
		VALUES (?,?,?,?,?,?,NOW())";

        if ($this->db->query($sql, $params)) {
            if ($params[5] == 1) {
                $update_params = array(
                    $params[2],
                    $params[0],
                    $params[1]
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

    // update struktural_pengurus
    function update_struktural_pengurus($params) {
    	$jabatan_id = $params[2];
    	array_splice($params, 2,1);
        $sql = "UPDATE struktural_pengurus SET struktural_id = ?, guru_id = ?, tanggal_mulai = ?, tanggal_selesai =?, sp_st = ?, mdb=?, mdd=NOW()
		WHERE sp_id = ?";
        if ($this->db->query($sql, $params)) {
            if ($params[5] == 1) {
                $update_params = array(
                    $jabatan_id,
                    $params[0],
                    $params[1]
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

    // delete struktural_pengurus
    function delete_struktural_pengurus($params) {
        $sql = "DELETE FROM struktural_pengurus WHERE sp_id = ?";
        return $this->db->query($sql, $params);
    }

    function _update_jabatan($params) {
        $sql = "UPDATE pengurus_sekolah SET jabatan_id = ?, struktural_id = ? WHERE guru_id = ?";
        return $this->db->query($sql, $params);
    }

}
