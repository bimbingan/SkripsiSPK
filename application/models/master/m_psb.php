<?php

class m_psb extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list psb
    function get_list_psb($params) {
        $sql = "SELECT * FROM psb p INNER JOIN psb_jalur jp ON p.jalurpsb_id = jp.jalurpsb_id 
		WHERE psb_tha LIKE ? AND p.jalurpsb_id LIKE ? AND psb_st LIKE ?
		ORDER BY psb_tha ASC
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

    // get total psb
    function get_total_psb($params) {
        $sql = "SELECT COUNT(*) AS 'total' FROM psb p INNER JOIN psb_jalur jp ON p.jalurpsb_id = jp.jalurpsb_id
		WHERE psb_tha LIKE ? AND p.jalurpsb_id LIKE ? AND psb_st LIKE ?
		ORDER BY psb_tha ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    // get  psb by id
    function get_psb_by_id($param) {
        $sql = "SELECT * FROM psb p INNER JOIN psb_jalur jp ON p.jalurpsb_id = jp.jalurpsb_id WHERE psb_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get psb group by tha
    function get_psb_group_by_tha() {
        $sql = "SELECT * FROM psb p INNER JOIN psb_jalur jp ON p.jalurpsb_id = jp.jalurpsb_id GROUP BY psb_tha";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get psb by tha
    function get_psb_by_tha($params) {
        $sql = "SELECT * FROM psb p INNER JOIN psb_jalur jp ON p.jalurpsb_id = jp.jalurpsb_id WHERE psb_tha = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    // get year psb
    function get_year_psb() {
        $sql = "SELECT MAX(GREATEST(SUBSTRING_INDEX(psb_tha, '-', 1),SUBSTRING_INDEX(psb_tha, '-', -1)))  as `max`,
				MIN(LEAST(SUBSTRING_INDEX(psb_tha, '-', 1),SUBSTRING_INDEX(psb_tha, '-', -1)))  as `min` FROM psb";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            $tahun = array();
            for ($i = 0; $i + $result['min'] <= $result['max']; $i++) {
                $tahun[] = $result['min'] + $i;
            }
            return $tahun;
        } else {
            return 0;
        }
    }

    public function is_psb_exist($params, $psb_id = NULL) {
        $sql = "SELECT COUNT(*) as 'total' FROM psb WHERE psb_tha = ? AND jalurpsb_id = ?";
        if ($psb_id != NULL) {
            $sql .= " AND psb_id != ?";
        }
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if ($result['total'] == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return array();
        }
    }

    // insert psb
    function insert_psb($params) {
        return $this->db->insert('psb', $params);
    }

    function update_psb($params, $where) {
        return $this->db->update('psb', $params, $where);
    }

    // delete psb
    function delete_psb($params) {
        $sql = "DELETE FROM psb WHERE psb_id = ?";
        return $this->db->query($sql, $params);
    }

}
