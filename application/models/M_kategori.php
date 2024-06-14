<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kategori extends CI_Model {

	public function cekKategoriById($id)
	{
		return $this->db->get_where('tbl_kategori_soal', array('id' => $id));
	}

}

/* End of file M_kategori.php */
/* Location: ./application/models/M_kategori.php */