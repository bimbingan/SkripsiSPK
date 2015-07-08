<?php

class M_siswa extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	function getAll(){
		$sql =  "SELECT * FROM siswa";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			$result = $query->result_array();
			$query->free_result();
			return $result;
		} else {
			return array();
		}
	}

	function hapus($nis)
	{
		$sql = "DELETE FROM siswa WHERE nis = ?";
		return $this->db->query($sql, $nis);
	}
}