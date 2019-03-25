<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Country extends CI_Controller {

	public $data=array();
	public $loggedout_method_arr = array('index');
	function __construct() {
		parent::__construct();
		
		$this->data=$this->defaultdata->getFrontendDefaultData();
		$this->load->model('userdata');
		if($this->defaultdata->is_session_active() == 1)
		{
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}
		if($this->session->userdata('admuname') == ''){
			redirect(base_url('login'));
		}
	}

	public function getAllLanugage(){
		$table = TABLE_ALLLANGUAGE;
		$this->db->order_by('weight','ASC');
		$query = $this->db->get($table);
		$result = $query->result_array();
		$optiondata = array();
		if(!empty($result)){
			foreach($result as $j=>$r){
				foreach($r as $k=>$a){
					$optiondata[$j][$k] = $a;
				}
			}
		}
		return $optiondata;
	}

	public function addNewCountry(){
		$this->data['language_list'] = $this->getAllLanugage();
		// $this->data['waith_len'] = ($this->db->select()->from('com_countries')->order_by('weight')->count_all_results() + 2);
		$this->data['waith_len'] = ($this->db->select('weight')->from('com_countries')->order_by('weight DESC')->limit(1)->get()->row()->weight + 1);
		$this->load->view('country/addnewcountry',$this->data);
	}

	public function saveNewCountry(){
		$language_list = $this->getAllLanugage();
        $input_data = $this->input->post();
		if(!empty($input_data)){
			foreach($language_list as $j=>$r){
				if(!isset($input_data['short_name'.$r['id']]) ||$input_data['short_name'.$r['id']] == ''){
					$this->session->set_userdata('country_error','please fill required fields.');
					$this->session->set_userdata('input_data',$input_data);
					redirect(base_url('new-country'));
					die;
				}
			}
			$this->db->order_by('typeID','DESC');
			$query = $this->db->get('com_countries',1,0);
			if($query->row('typeID')){
				$input_data1['typeID'] = $query->row('typeID')+5;
			} else {
				$input_data1['typeID'] = 5;
			}
			$input_data1['status'] = $input_data['status'];
			$input_data1['weight'] = $input_data['weight'];
			$input_data1['country_code'] = $input_data['country_code'];

			foreach($language_list as $j=>$r){
				$input_data1['short_name'] = $input_data['short_name'.$r['id']];
				$input_data1['languageID'] = $r['id'];
				$this->db->set($input_data1);
				$this->db->insert('com_countries');
			}
			$this->session->set_userdata('country_success','Country Add successfully.');
			// $this->db->insert('com_pricing',$input_data);
			redirect(base_url('list-country'));
			// echo json_encode(array('status'=>true));
		}else{
			redirect(base_url('list-country'));
		}
	}

	public function listCountry(){
		$this->db->select("*");
		$this->db->from("com_countries");
		// $this->db->where('status','Y');
		$this->db->group_by('typeID');
		$query = $this->db->get();
		$result = $query->result_array();
		if(!empty($result)){
			foreach($result as $j=>$r){
				foreach($r as $k=>$a){
					if($k=="status"){
						$data[$j][] = $a == "Y"?'<span class="btn btn-warning btn-round">Active</span>':'<span class="btn btn-danger btn-round">Deactive</span>';
					}else{
						$data[$j][] = $a;
					}					
				}
			}
			$this->data['data'] = $data;
			$this->load->view('country/index',$this->data);
		}
	}

	public function edit($id = 0){
		if($id != 0 && $id != ""){
			$this->data['id'] = $id;
			$condi = array('typeID' => $id);
			$query = $this->db->get_where('com_countries', $condi);
			$result = $query->result_array();
			$result_row = $query->row();
			$data = array(
				'country_code' => $result_row->country_code,
				'weight' => $result_row->weight,
				'status' => $result_row->status
			);
			foreach($result as $a){
				$data['short_name'.$a['languageID']] = $a['short_name'];
			}
			$this->data['language_list'] = $this->getAllLanugage();
			$this->data['waith_len'] = ($this->db->select('weight')->from('com_countries')->order_by('weight DESC')->limit(1)->get()->row()->weight + 1);
			$this->data['data'] = $data;
			$this->load->view('country/addnewcountry',$this->data);
		} else {
			redirect(base_url('list-country'));
		}
	}

	public function editProccess($id = 0){
		$language_list = $this->getAllLanugage();
		$input_data = $this->input->post();
		if(!empty($input_data)){
			foreach($language_list as $j=>$r){
				if(!isset($input_data['short_name'.$r['id']]) ||$input_data['short_name'.$r['id']] == ''){
					$this->session->set_userdata('country_error','please fill required fields.');
					$this->session->set_userdata('input_data',$input_data);
					redirect(base_url('country-edit/id/'.$id));
					die;
				}
			}
			$input_data1['status'] = $input_data['status'];
			$input_data1['weight'] = $input_data['weight'];
			$input_data1['country_code'] = $input_data['country_code'];

			foreach($language_list as $j=>$r){
				$input_data1['short_name'] = $input_data['short_name'.$r['id']];
				$input_data1['languageID'] = $r['id'];

				$old_data = $this->db->select()->from('com_countries')->where(array('typeID' => $id, 'languageID'=>$r['id']))->get()->num_rows();
				if($old_data > 0){
					$this->db->set($input_data1);
					$this->db->where(array('typeID' => $id, 'languageID'=>$r['id']));
					$this->db->update('com_countries');
				} else {
					$input_data1['typeID'] = $id;
					$this->db->set($input_data1);
					$this->db->insert('com_countries');
				}
			}
			$this->session->set_userdata('country_success','Country updated successfully.');
			redirect(base_url('country-edit/id/'.$id));
		}else{
			$this->session->set_userdata('country_error','Data not found.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('country-edit/id/'.$id));
		}
	}

	public function delete($id = 0){
		if($id != 0 && $id != ""){
			$this->db->where('typeID', $id);
			$this->db->delete('com_countries');
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong.'));
		}
	}
}