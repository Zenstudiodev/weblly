<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Plans extends CI_Controller {

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
	public function addNewPlan(){
		$this->load->view('plan/addnewplan',$this->data);
	}
	public function saveNewPlan(){
		$stripe_api_key = $this->db->select('stripe_api_key')->from(TABLE_GENERAL_SETTINGS)->get()->row()->stripe_api_key;
		require APPPATH .'libraries/stripe/init.php';
		\Stripe\Stripe::setApiKey($stripe_api_key);
		$postdata = $this->input->post();
		//print_r($postdata);die;
		if(!empty($postdata) && !empty($_FILES['iconImage'])){
			$fea = implode(',', $postdata['features']);	
			$folder = "../upload/planIcon/";	
			$path = "";
			if(isset($_FILES) && $_FILES['iconImage']['name'] != ''){
				$pic = $_FILES["iconImage"]["name"];
				$path = $folder.$pic;
				if (move_uploaded_file($_FILES['iconImage']['tmp_name'], $path)) {
					$image = $_FILES['iconImage']['name'];
				}else{
					redirect(base_url('list-plans'));
				}
			}	
			$input_data = array(
				'plan_name' => $postdata['planName'],
				'plan_price_month' =>  $postdata['monthPrice'],
				'plan_price_year' =>  $postdata['yearPrice'],
				'plan_status' =>$postdata['planStatus'],
				'plan_features'=>$fea,
				'plan_icon'=>$image,
				'plan_date'=>date("Y-m-d H:i:s"),
				'apple_monthly_id'=>isset($postdata['appleMId']) && $postdata['appleMId'] != '' ? $postdata['appleMId'] : '',
				'apple_yearly_id'=>isset($postdata['appleYId']) && $postdata['appleYId'] != '' ? $postdata['appleYId'] : '',
			);	
			
			$this->db->insert('com_pricing',$input_data);
			$insert_id = $this->db->insert_id();
			unset($input_data['apple_monthly_id']);
			unset($input_data['apple_yearly_id']);
			$this->crateStripePlan($input_data,$insert_id );			
			redirect(base_url('list-plans'));
			// echo json_encode(array('status'=>true));
		}else{
			redirect(base_url('list-plans'));
			// echo json_encode(array('status'=>false));
		}
	}
	public function crateStripePlan($data,$id){
		$days = $this->data['general_settings']->suscribe_no_days;
		$resp = \Stripe\Plan::create(array(
			"amount" => intval ($data['plan_price_month']*100),
			"interval" => "month",
			"name" => $data['plan_name'],
			"currency" => "usd",
			"id" => "weblly-month-".$id,
			'trial_period_days'=>$days
		));		
		$resp2 = \Stripe\Plan::create(array(
			"amount" => intval ($data['plan_price_year']*100),
			"interval" => "year",
			"name" => $data['plan_name'],
			"currency" => "usd",
			"id" => "weblly-year-".$id,
			'trial_period_days'=>$days
		));
		$dataResp  = $resp->__toJSON();
		$dataResp1  = $resp2->__toJSON();
		$setData = array('stripe_resp_month'=>$dataResp,'stripe_resp_year'=>$dataResp1);
		$this->db->set($setData);
		$this->db->where('id', $id);
		$this->db->update('com_pricing');
		return true;
	}
	public function index(){
		$this->db->select("*");
		$this->db->from("com_pricing");
		$query = $this->db->get();  
		$result = $query->result_array();
		$datag = array();
		if(!empty($result)){
			foreach($result as $j=>$r){
				foreach($r as $k=>$a){
					if($k=="plan_status"){
						$datag[$j][]  = $a == 'Y'?'<span class="btn btn-success btn-round btn-sm">Active</span>':'<span class="btn btn-sm btn-danger btn-round">Deactive</span>';
					}else if($k=="plan_features"){
						if($a != ''){
							$fe = explode(',',$a);
							$html = '';
							if(!empty($fe)){
								foreach($fe as $f){
									if($f==0){
										$html .= "<span class='tag'>Art</span>";
									}else if($f==1){
										$html .= "<span class='tag'>Writting</span>";
									}else if($f==2){
										$html .= "<span class='tag'>Audio</span>";
									}else if($f==3){
										$html .= "<span class='tag'>Video</span>";
									}
								}
							}
							$datag[$j][]  = $html;
						}else{
							$datag[$j][]  = '-';
						}
						
					}else{
						$datag[$j][] = $a;
					}					
				}
			}
		}	
		$this->data['data'] = $datag;
		$this->load->view('plan/index',$this->data);
	}
	public function editPlan($id=0){
		if($id != 0 && $id != ""){
			$this->db->where('id', $id);
			$this->db->from("com_pricing");
			$query = $this->db->get();
			$result = $query->row();
			$this->data['planData'] = $result;			 
			$this->load->view('plan/addnewplan',$this->data);
		}else{
			redirect(base_url('index'));
		}	
	}
	public function saveEditData($id=0){
		$stripe_api_key = $this->db->select('stripe_api_key')->from(TABLE_GENERAL_SETTINGS)->get()->row()->stripe_api_key;
		require APPPATH .'libraries/stripe/init.php';
		\Stripe\Stripe::setApiKey($stripe_api_key);
		
		$days = $this->data['general_settings']->suscribe_no_days;
		if($id != 0 && $id != ""){
			$postdata = $this->input->post();
			
			$this->db->from("com_pricing");
			$this->db->where('id', $id);
			$query = $this->db->get();  
			$result = $query->row();
			$image = "";
			$folder = "../upload/planIcon/";
			if(isset($_FILES) && $_FILES['iconImage']['name'] != ''){
				
				$pic = $_FILES["iconImage"]["name"];
				$path = $folder.$pic;
				if (move_uploaded_file($_FILES['iconImage']['tmp_name'], $path)) {
					$image = $_FILES['iconImage']['name'];
				}else{
					redirect(base_url('list-plans'));
				}
			}else{
				$image = $result->plan_icon;
			}
			$fea = implode(',', $postdata['features']);
			$input_data1 = array(
				'plan_name' => $postdata['planName'],
				'plan_price_month' =>  $postdata['monthPrice'],
				'plan_price_year' =>  $postdata['yearPrice'],
				'plan_status' =>$postdata['planStatus'],
				'plan_features'=>$fea,
				'plan_icon'=>$image,
				'apple_monthly_id'=>isset($postdata['appleMId']) && $postdata['appleMId'] != '' ? $postdata['appleMId'] : '',
				'apple_yearly_id'=>isset($postdata['appleYId']) && $postdata['appleYId'] != '' ? $postdata['appleYId'] : '',
			);
			//unset($input_data1['apple_monthly_id']);
			//print_r($input_data1);die;	
			$this->db->set($input_data1);
			$this->db->where('id', $id);
			$this->db->update('com_pricing');
			unset($input_data1['apple_monthly_id']);
			unset($input_data1['apple_yearly_id']);
			//print_r($input_data1);die;	
			$SID = $this->getPlanId($id);
			if($SID != ''){
				$sidArray = explode('=',$SID);				
				if(!empty($sidArray)){
					foreach($sidArray as $ids){
						$plan = \Stripe\Plan::retrieve($ids);
						$plan->delete();					
					}					
				}				
			}
			$this->crateStripePlan($input_data1,$id);
			redirect(base_url('list-plans')); 
		}else{
			redirect(base_url('list-plans'));
		}
	}
	public function planDelete($id=0){
		$stripe_api_key = $this->db->select('stripe_api_key')->from(TABLE_GENERAL_SETTINGS)->get()->row()->stripe_api_key;
		require APPPATH .'libraries/stripe/init.php';
		\Stripe\Stripe::setApiKey($stripe_api_key);
		if($id != 0 && $id != ""){
			$SID = $this->getPlanId($id);
			if($SID != ''){
				$sidArray = explode('=',$SID);				
				if(!empty($sidArray)){
					foreach($sidArray as $ids){
						$plan = \Stripe\Plan::retrieve($ids);
						$plan->delete();
					}
				}
			}
			$this->db->where('id', $id);
			$this->db->delete("com_pricing");
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong.'));
		}		
	}
	public function getPlanId($id){
		$query = $this->db->get_where("com_pricing",array('id' => $id));
		$return_data = $query->row();		
		$plan_stripe_data = json_decode($return_data->stripe_resp_month);
		$plan_stripe_data1 = json_decode($return_data->stripe_resp_year);
		return $plan_stripe_data->id.'='.$plan_stripe_data1->id;
	}
}