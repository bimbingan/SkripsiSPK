<?php

class m_calonsiswa extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // get list calonsiswa
    function get_list_calonsiswa($params) {
        $sql = "SELECT ps.*, p.psb_tha, pj.jalurpsb_nama, s.sekolah_nama, pv.provinsi_nm, k.kab_nm, a.agama_nm 
				FROM psb_siswa ps 
				INNER JOIN psb p USING (psb_id)
				INNER JOIN psb_jalur pj USING(jalurpsb_id)
				INNER JOIN sekolah s USING (sekolah_id)
				INNER JOIN provinsi pv USING (provinsi_id)
				INNER JOIN kabupaten k USING (kab_id)
				INNER JOIN agama a USING (agama_cd) WHERE cs_no_pendaftaran LIKE ? AND cs_nama LIKE ? AND p.`jalurpsb_id` LIKE ?
				ORDER BY p.`psb_tha` DESC LIMIT ?, ?";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get total calonsiswa
    function get_total_calonsiswa($params) {

        $sql = "SELECT COUNT(*) AS 'total' 
				FROM psb_siswa ps 
				INNER JOIN psb p USING (psb_id)
				INNER JOIN psb_jalur pj USING(jalurpsb_id)
				INNER JOIN sekolah s USING (sekolah_id)
				INNER JOIN provinsi pv USING (provinsi_id)
				INNER JOIN kabupaten k USING (kab_id)
				INNER JOIN agama a USING (agama_cd) WHERE cs_no_pendaftaran LIKE ? AND cs_nama LIKE ? AND p.`jalurpsb_id` LIKE ? 
				ORDER BY p.psb_tha DESC";

        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return 0;
        }
    }

    function get_calonsiswa_by_id($param) {
        $sql = "SELECT ps.*, p.psb_tha, pj.jalurpsb_nama, s.sekolah_nama, pv.provinsi_nm, k.kab_nm, a.agama_nm 
				FROM psb_siswa ps 
				INNER JOIN psb p USING (psb_id)
				INNER JOIN psb_jalur pj USING(jalurpsb_id)
				INNER JOIN sekolah s USING (sekolah_id)
				INNER JOIN provinsi pv USING (provinsi_id)
				INNER JOIN kabupaten k USING (kab_id)
				INNER JOIN agama a USING (agama_cd) WHERE cs_id = ?";
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_all_calonsiswa() {
        $sql = "SELECT ps.*, p.psb_tha, pj.jalurpsb_nama, s.sekolah_nama, pv.provinsi_nm, k.kab_nm, a.agama_nm 
				FROM psb_siswa ps 
				INNER JOIN psb p USING (psb_id)
				INNER JOIN psb_jalur pj USING(jalurpsb_id)
				INNER JOIN sekolah s USING (sekolah_id)
				INNER JOIN provinsi pv USING (provinsi_id)
				INNER JOIN kabupaten k USING (kab_id)
				INNER JOIN agama a USING (agama_cd)";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_all_calonsiswa_for_select() {
        $sql = "SELECT cs_id, cs_nama FROM psb_siswa ps";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

    function get_new_id() {
        $sql = "SELECT CAST(RIGHT(MAX(cs_no_pendaftaran), 6) AS SIGNED) as 'id' FROM psb_siswa";
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

    // check siswa by sekolah and nis
    function is_registered($params){
    	$sql = "SELECT COUNT(*) as 'total' FROM psb_siswa WHERE cs_nis = ? AND sekolah_id = ?";
    	$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            if($result['total'] > 0){
            	return TRUE;
            }else{
            	return FALSE;
            }
        } else {
            return NULL;
        }
    }

    function insert_calonsiswa($params) {
        $sql = "INSERT INTO psb_siswa (psb_id, cs_no_pendaftaran, cs_nis, cs_nama, sekolah_id, cs_tgl_daftar, cs_alamat, cs_st, 
			cs_nilai_un, cs_jk, cs_kewarganegaraan, cs_negara, jurusan_id, provinsi_id, kab_id, cs_notelp, cs_email, cs_tahun_lulus, agama_cd, 
			ayah_nm, ayah_tempatlahir, ayah_tgllahir, ayah_kewarganegaraan, ayah_pendidikan, ayah_pekerjaan, ayah_alamat, 
			ayah_notelp, ayah_agama, ayah_st, ibu_nm, ibu_tempatlahir, ibu_tgllahir, ibu_kewarganegaraan, ibu_pendidikan, 
			ibu_pekerjaan, ibu_alamat, ibu_notelp, ibu_agama, ibu_st, wali_nm, wali_pekerjaan, wali_alamat, wali_notelp, wali_agama, mdb, mdd) 
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";
        return $this->db->query($sql, $params);
    }

    // update calonsiswa
    function update_calonsiswa($params) {
        $sql = "UPDATE psb_siswa SET 
				psb_id = ?, cs_nis = ?, cs_nama = ?, sekolah_id = ?, cs_tgl_daftar = ?, cs_alamat = ?, cs_st = ?, cs_nilai_un = ?, cs_jk = ?,
				cs_kewarganegaraan = ?, cs_negara = ?, jurusan_id=?, provinsi_id = ?, kab_id = ?, cs_notelp = ?, cs_email = ?, cs_tahun_lulus = ?,
				agama_cd = ?, ayah_nm = ?, ayah_tempatlahir = ?, ayah_tgllahir = ?, ayah_kewarganegaraan = ?, ayah_pendidikan = ?,
				ayah_pekerjaan = ?, ayah_alamat = ?, ayah_notelp = ?, ayah_agama = ?, ayah_st = ?, ibu_nm = ?,  ibu_tempatlahir = ?, 
				ibu_tgllahir = ?, ibu_kewarganegaraan = ?, ibu_pendidikan = ?, ibu_pekerjaan = ?, ibu_alamat = ?, ibu_notelp = ?, 
				ibu_agama = ?, ibu_st = ?, wali_nm=?, wali_pekerjaan = ?, wali_alamat = ?, wali_notelp = ?, wali_agama = ?,
				mdb = ?, mdd = NOW() WHERE cs_id = ?";
        return $this->db->query($sql, $params);
    }

    // update calonsiswa foto
    function update_foto($params) {
        $sql = "UPDATE psb_siswa SET cs_foto = ? WHERE cs_id = ?";
        return $this->db->query($sql, $params);
    }

    // update calonsiswa dokumen
    function update_dokumen($params) {
        $sql = "UPDATE psb_siswa SET cs_upload = ? WHERE cs_id = ?";
        return $this->db->query($sql, $params);
    }

    // delete calonsiswa
    function delete_calonsiswa($params) {
        $sql = "DELETE FROM psb_siswa WHERE cs_id = ?";
        return $this->db->query($sql, $params);
    }

    // get tha by tahun ajaran
    function get_tahun_ajaran_group_by_tha($params) {
        $sql = "SELECT * FROM tahun_ajaran tha INNER JOIN psb_jalur j USING(jalurpsb_id) WHERE tha.tha_tahun_ajaran = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return 0;
        }
    }

}
