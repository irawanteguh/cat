<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_bank_soal extends CI_Model {

	/*
	| ----------------------SERVER SIDE DATA PERTANYAAN----------------------------------
	*/

	private $column_order = array(null, 'id', 'pertanyaan','pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e'); //set column field database for datatable orderable
	private $column_search = array('id', 'pertanyaan','pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e'); //set column field database for datatable searchable 
	private $order = array('id' => 'desc'); // default order 

	private function tbl_bank_soal($idKategoriSoal)
	{
		$this->db->select('*');
		$this->db->from('tbl_bank_soal');
		$this->db->where('kategori_id', $idKategoriSoal);	
	}

	private function _get_query($idKategoriSoal) {
		$this->tbl_bank_soal($idKategoriSoal);

		$i = 0;
		foreach ($this->column_search as $item) { // loop column 
			if($_POST['search']['value']) { // if datatable send POST for search
				
				if($i===0){ // first loop
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])){ // here order processing
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order)){
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function getBankSoal($idKategoriSoal) {
		$this->_get_query($idKategoriSoal);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		
		return $query->result();
	}

	public function count_filtered($idKategoriSoal) {
		$this->_get_query($idKategoriSoal);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($idKategoriSoal) {
		$this->tbl_bank_soal($idKategoriSoal);
		return $this->db->count_all_results();
	}
	
	/*
	| -------------------------------------------------------------------
	*/

	public function addBankSoal($data)
	{
		$this->db->insert('tbl_bank_soal', $data);
	}

	public function updateBankSoal($data, $idBankSoal)
	{
		$this->db->where('id', $idBankSoal);
		$this->db->update('tbl_bank_soal', $data);
		
	}

	public function deleteBankSoalById($idBankSoal)
	{
		$this->db->where('id', $idBankSoal);
		$this->db->delete('tbl_bank_soal');
		
	}

	public function getBankSoalById($idBankSoal)
	{
		return $this->db->get_where('tbl_bank_soal', array('id' => $idBankSoal));
	}

	public function acakSoal($idKategoriSoal, $limit)
	{ 
		$this->db->select('id, kategori_id');
		$this->db->from('tbl_bank_soal');
		$this->db->where('kategori_id', $idKategoriSoal);
		$this->db->order_by('rand()');
		$this->db->limit($limit); 
		return $this->db->get();
	}

}

/* End of file M_bank_soal.php */
/* Location: ./application/models/M_bank_soal.php */