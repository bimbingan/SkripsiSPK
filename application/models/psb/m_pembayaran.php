<?php

class m_pembayaran extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_list_kas($params) {
        $sql = "SELECT kp.*, ps.`cs_nama` FROM kas_pendaftaran kp INNER JOIN psb_siswa ps USING(cs_id) 
		WHERE ps.`cs_nama` LIKE ? AND trx_date LIKE ? AND trx_st LIKE ? LIMIT ?,?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_total_kas($params) {
        $sql = "SELECT COUNT(*) as 'total' FROM kas_pendaftaran kp INNER JOIN psb_siswa ps USING(cs_id) 
		WHERE ps.`cs_nama` LIKE ? AND trx_date LIKE ? AND trx_st LIKE ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_kas_by_id($params) {
        $sql = "SELECT kp.*, ps.`cs_nama` FROM kas_pendaftaran kp INNER JOIN psb_siswa ps USING(cs_id) WHERE kas_id = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function insert_pembayaran($params) {
        return $this->db->insert('kas_pendaftaran', $params);
    }

    function update_pembayaran($params, $where) {
        return $this->db->update('kas_pendaftaran', $params, $where);
    }

    function delete_pembayaran($params) {
        $sql = "DELETE FROM kas_pendaftaran WHERE kas_id = ?";
        return $this->db->query($sql, $params);
    }

    function get_new_kas_no() {
        $sql = "SELECT CAST(RIGHT(MAX(kas_no), 6) AS SIGNED) as 'id' FROM kas_pendaftaran";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();

            if ($result['id'] < 10) {
                return "00000" . ($result['id'] + 1);
            } else if ($result['id'] >= 10 && $result['id'] < 100) {
                return "0000" . ($result['id'] + 1);
            } else if ($result['id'] >= 100 && $result['id'] < 1000) {
                return "000" . ($result['id'] + 1);
            } else if ($result['id'] >= 1000 && $result['id'] < 10000) {
                return "00" . ($result['id'] + 1);
            } else if ($result['id'] >= 10000 && $result['id'] < 100000) {
                return "0" . ($result['id'] + 1);
            } else {
                return $result['id'] + 1;
            }
        } else {
            return 0;
        }
    }

}
