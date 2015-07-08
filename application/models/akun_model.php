<?php

class akun_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	function getAll(){
		$sql =  "SELECT * FROM user";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			$result = $query->result_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
	}

	function hapus($id_user)
	{
		$sql = "DELETE FROM user WHERE id_user = ?";
		return $this->db->query($sql, $id_user);
	}
}