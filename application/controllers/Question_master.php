<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_master extends CI_Controller {

	public function index()
	{
		$this->load->view('questions_view');
	}

	//This Method is used for Add Record
	public function add_record()
	{
		$posted_data=$this->input->post();
			/*This Condition used for Update*/
		if (!empty($posted_data['question_id'])) {
				$id['question_id']=$posted_data['question_id'];
			$this->question_master_model->update_record('questions_master',$posted_data,$id);
			echo json_encode(true);
		}
		else {
			
			$this->question_master_model->add_record('questions_master',$posted_data);
			echo json_encode(true);
		}
	}

	//This function Used For Show Record
	public function show_data()
	{
		$data=$this->question_master_model->show_data('questions_master');
		echo json_encode($data);
	}

	public function edit_record()
	{
		$id=$this->input->post();
		$data=$this->question_master_model->edit_data('questions_master',$id);
		echo json_encode($data);
	}

	public function delete_record()
	{
		$id=$this->input->post();
		$data=$this->question_master_model->delete_data('questions_master',$id);
		echo json_encode(true);

	}



	public function fetch_user()
	{
		$fetch_data=$this->question_master_model->make_datatables();
		$data=array(); 

			$i=1;
		foreach ($fetch_data as $row) {
			$sub_array=array();
			$sub_array[]=$i;
			$sub_array[]=$row->question;
			$sub_array[]=$row->opt_1;
			$sub_array[]=$row->opt_2;
			$sub_array[]=$row->opt_3;
			$sub_array[]=$row->opt_4;
			$sub_array[]=$row->true_ans;
			$sub_array[]="<button id='edit' class='btn btn-warning' data-id=".$row->question_id.">Edit</button><button id='delete' class='btn btn-danger ml-2' data-id=".$row->question_id.">Delete</button>";
			$data[]=$sub_array;
			$i+=1;
		}

		$output=array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $this->question_master_model->get_all_data(),
			"recordsFiltered" => $this->question_master_model->get_filtered_data(),
			"data"  =>$data
		); 
		echo json_encode($output);
	}


	
}

	


/* End of file Question_master.php */
/* Location: ./application/controllers/Question_master.php */