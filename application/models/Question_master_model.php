<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_master_model extends CI_Model {

	public function add_record($table,$data)
	{
		$this->db->insert($table,$data);

	}

	public function show_data($table)
	{
		$query=$this->db->get($table);
		return $query->result();

	}

	public function edit_data($table,$id)
	{
		$query=$this->db->get_where($table,$id);
		return $query->row();
	}

	public function update_record($table,$id,$data)
	{
		$this->db->update($table,$id,$data);
	}

	public function delete_data($table,$id)
	{
		$this->db->delete($table,$id);
	}

	var $table="questions_master";
	var $select_column=array("question_id","question","opt_1","opt_2","opt_3","opt_4","true_ans");
	var $order_column=array("question_id","question",null,null,null,null,null);

	function make_query(){
		$this->db->select($this->select_column);
		$this->db->from($this->table);
		if (isset($_POST["search"]["value"])) {
		
			$this->db->like("question",$_POST["search"]["value"]);
			$this->db->or_like("opt_1",$_POST["search"]["value"]);
			$this->db->or_like("opt_2",$_POST["search"]["value"]);
			$this->db->or_like("opt_3",$_POST["search"]["value"]);
			$this->db->or_like("opt_4",$_POST["search"]["value"]);
		}
		if (isset($_POST["order"])) {
			$this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}
		else {
			$this->db->order_by("question_id","DESC");
		}
	}

	function make_datatables(){
		$this->make_query();
		if($_POST["length"] != -1)
		{
			$this->db->limit($_POST["length"],$_POST["start"]);
		}
		$query=$this->db->get();
		return $query->result();

	} 

	function get_filtered_data(){
		$this->make_query();
		$query=$this->db->get(); 
		return $query->num_rows();
	}

	function get_all_data()
	{
		$this->db->select("*");
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

}

/* End of file Question_master_model.php */
/* Location: ./application/models/Question_master_model.php */