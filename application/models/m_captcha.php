<?php 

class m_captcha extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	function delete_expired($params){
		$sql = "DELETE FROM captcha WHERE captcha_time < ?";
		return $this->db->query($params);
	}

	function is_captcha_exist($params){
		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if($result['count'] > 0){
            	return TRUE;
            }else{
            	return FALSE;
            }
        } else {
            return array();
        }
	}
}