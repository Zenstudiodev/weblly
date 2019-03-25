<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Advertise extends CI_Controller {

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
	public function adLocation(){
        $this->db->from("com_site_add_location");
		$query = $this->db->get();  
        $result = $query->result_array();
		$datag = array();
		if(!empty($result)){
			foreach ($result as $index => $data) {	
				$dimestion = $result[$index]['dimension'];	
				foreach($data as $k=>$a){
					if($k=="title"){
						$datag[$index][] = $a.'--'.$dimestion;  
					}else if($k=="weight"){
						unset($data[$k]);
					}else if($k=="status"){
						$datag[$index][] = $a =="Y"? '<span class="btn btn-warning btn-round">Online</span>':'<span class="btn btn-danger btn-round">Offline</span>';
					}else if($k == 'postedTime'){
						$datag[$index][] = date("d M, Y",$a);
					}else{
						$datag[$index][] = $a ;  
					}
					
				}
			}
		}
        
        $this->data['data'] = $datag;
		$this->load->view('advertise/adlocation',$this->data);
	}
	public function adCountry(){
        $this->db->from("com_site_ad_country");
		$query = $this->db->get();  
        $result = $query->result_array();
		$datag = array();
		if(!empty($result)){
			foreach ($result as $index => $data) {			
				foreach($data as $k=>$a){
					if($k=="dimension"){
						unset($data[$k]);
					}else if($k=="weight"){
						unset($data[$k]);
					}else if($k=="status"){
						$datag[$index][] = $a =="Y"? '<span class="btn btn-warning btn-round">Online</span>':'<span class="btn btn-danger btn-round">Offline</span>';
					}else if($k == 'postedTime'){
						$datag[$index][] = date("d M, Y",$a);
					}else{
						$datag[$index][] = $a ;  
					}
					
				}
			}
		}
        $this->data['data'] = $datag;
		$this->load->view('advertise/adcountry',$this->data);
	}
	public function adShowList(){
        $this->db->from("com_site_add");
        $this->db->order_by("id","DESC");
		$query = $this->db->get();  
		$result = $query->result_array();
		$datag = array();
		if(!empty($result)){
			foreach ($result as $index => $data) {	
				foreach($data as $k=>$a){
					if($k=="ad_name"){
						$datag[$index][] = $a;
					}else if($k=="location_id"){
						$this->db->from("com_site_add_location");
						$this->db->where("id",$a);
						$query = $this->db->get();						
						$result = $query->row();		
						$datag[$index][] = $result->title;
					}else if($k=="adtype"){
						$datag[$index][] = $a =='A'?'<span class="btn btn-round btn-primary">Ad Sense</span>':'<span class="btn btn-round btn-primary">Image Ad</span>';
					}else if($k=="ad_url"){
						$datag[$index][] = '<a href="'.$a.'" target="_blank">Visit Link</a>';
					}else if($k=="siteadd_status"){
						$datag[$index][] = $a == "Y" ? '<span class="btn btn-success btn-round">Online</span>':'<span class="btn btn-round btn-danger">Blocked</span>';
					}else if($k=="id"){
						$datag[$index][] = $a;
					}else{
						unset($data[$k]);
					}					
				}
			}
		}
        $this->data['data'] = $datag;
		$this->load->view('advertise/adshowlist',$this->data);
	}
	public function userAdList(){
		$this->db->order_by("id","DESC");
		$this->db->from("com_advertisement_with_us");
		$query = $this->db->get();  
		$result = $query->result_array();
		//print_r($result[1]['ad_url']);die;
		if(!empty($result)){
			foreach ($result as $index => $data) {
				$url =  $result[$index]['ad_url'];
				$now = date('Y-m-d h:i:s');				
				if( $result[$index]['startDate'] <= $now && $now <= $result[$index]['endDate']){
					$status =  "<span class='btn btn-sm btn-primary btn-round'>Running</span>";
				}
				elseif($now > $result[$index]['endDate']){
					$status = "<span class='btn btn-sm btn-danger btn-round'>Expire</span>";
				}
				else{
					$status =  "<span class='btn btn-sm btn-success btn-round'>Coming</span>";
				}
				foreach($data as $k=>$a){
					//echo $url;
					if($k=="id"){
						$datag[$index][] = $a;
					}else if($k=="user_id"){
						$this->db->from("com_user");
						$this->db->where("id",$a);
						$query = $this->db->get();						
						$results = $query->row();		
						$datag[$index][] = $results->firstName." ".$results->lastName;
					}else if($k=="title"){
						$datag[$index][] = '<a  href="'.$url.'" target="_blank">'.$a.'</a>';
					}else if($k=="location_id"){
						//echo $a;
						$this->db->from("com_site_add_location");
						$this->db->where("id",$a);
						$query = $this->db->get();	
						$results = $query->result();					
						//$result = $query->row();
						$datag[$index][] = isset($results) && $results[0]->title != '' ? $results[0]->title : '';
					}else if($k=="image"){
						$datag[$index][] = '<a download=""  href="'.base_url('../upload/site_adds/'.$a).'"><img src="'.base_url('../upload/site_adds/'.$a).'" width= "80px"></a>';
					}else if($k=="paymentStatus"){
						$datag[$index][]  = $a == 'Y'?'<span class="btn btn-success btn-round">Paid</span>':'<span class="btn btn-danger btn-round">Pending</span>';
					}else if($k=="startDate"){
						$datag[$index][] = $a;
					}else if($k=="endDate"){
						$datag[$index][] = $a;
					}else if($k=="clickCount"){
						$datag[$index][] = $a;
					}else if($k=="visit_count"){
						$datag[$index][] = $a;
					}else if($k=="status"){
						$datag[$index][] = $status;
					}else{
						unset($data[$k]);
					}
				}
			}
		}
		//print_r($datag);die;
		$this->data['data'] = $datag;
		$this->load->view('advertise/useradlist',$this->data);
	}
	public function adBlockList(){
		$this->db->from("com_advertisement_with_us");
		$this->db->where('status','N');
		$query = $this->db->get();  
		$result = $query->result_array();
		//print_r($result[1]['ad_url']);die;
		if(!empty($result)){
			foreach ($result as $index => $data) {
				$url =  $result[$index]['ad_url'];
				$now = date('Y-m-d h:i:s');				
				if( $result[$index]['startDate'] <= $now && $now <= $result[$index]['endDate']){
					$status =  "<span class='btn btn-sm btn-primary btn-round'>Running</span>";
				}
				elseif($now > $result[$index]['endDate']){
					$status = "<span class='btn btn-sm btn-danger btn-round'>Expire</span>";
				}
				else{
					$status =  "<span class='btn btn-sm btn-success btn-round'>Coming</span>";
				}
				foreach($data as $k=>$a){
					//echo $url;
					if($k=="id"){
						$datag[$index][] =$a;
					}else if($k=="user_id"){
						$this->db->from("com_user");
						$this->db->where("id",$a);
						$query = $this->db->get();						
						$results = $query->row();		
						$datag[$index][] = $results->firstName." ".$results->lastName;
					}else if($k=="title"){
						$datag[$index][] = '<a  href="'.$url.'" target="_blank">'.$a.'</a>';
					}else if($k=="location_id"){
						//echo $a;
						$this->db->from("com_site_add_location");
						$this->db->where("id",$a);
						$query = $this->db->get();	
						$results = $query->result();					
						//$result = $query->row();
						$datag[$index][] = isset($results) && $results[0]->title != '' ? $results[0]->title : '';
					}else if($k=="image"){
						$datag[$index][] = '<a download=""  href="'.base_url('../upload/site_adds/'.$a).'"><img src="'.base_url('../upload/site_adds/'.$a).'" width= "80px"></a>';
					}else if($k=="paymentStatus"){
						$datag[$index][]  = $a == 'Y'?'<span class="btn btn-success btn-round btn-sm">Paid</span>':'<span class="btn btn-sm btn-danger btn-round">Pending</span>';
					}else if($k=="startDate"){
						$datag[$index][] = $a;
					}else if($k=="endDate"){
						$datag[$index][] = $a;
					}else if($k=="clickCount"){
						$datag[$index][] = $a;
					}else if($k=="visit_count"){
						$datag[$index][] = $a;
					}else if($k=="status"){
						$datag[$index][] =$status;
					}else{
						unset($data[$k]);
					}
				}
			}
		}
		//print_r($datag);die;
		$this->data['data'] = $datag;
		$this->load->view('advertise/adblock',$this->data);
	}
	public function adNewAdv(){
		$this->db->from("com_site_add_location");
		$this->db->where('status','Y');
		$query = $this->db->get();  
		$result = $query->result_array();
		$this->data['locationData'] = $result;
		$this->load->view('advertise/adnewadv',$this->data);
	}
	public function adSaveAdv(){
		$folder = "../upload/site_adds/";
		$input_data = $this->input->post();
		$image = '';
		if(isset($_FILES) && $_FILES['adBanner']['name'] != ''){
			$pic = $_FILES["adBanner"]["name"];
			$path = $folder.$pic;
			if (move_uploaded_file($_FILES['adBanner']['tmp_name'], $path)) {
				$image = $_FILES['adBanner']['name'];
			} else {
				$this->session->set_userdata('adv_admin_error','Something went wrong. Image not save.');
				redirect(base_url('advertise/ad-new-adv'));
			}
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Advertise name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('location', 'Location', 'trim|required|xss_clean');
		$this->form_validation->set_rules('adType', 'Advertise type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('adStatus', 'Status', 'trim|required|xss_clean');
		if($input_data['adType'] == 'I'){
			$this->form_validation->set_rules('adUrl', 'Url', 'trim|required|xss_clean');
		} else if($input_data['adType'] == 'A'){
			$this->form_validation->set_rules('adDesc', 'Google adsense script', 'trim|required|xss_clean');
		}
		if($this->form_validation->run() == FALSE) {
			$this->session->set_userdata('adv_admin_error',validation_errors());
			redirect(base_url('advertise/ad-new-adv'));
		} else {
			$table = 'com_site_add';		
			$input_data1 = array(
				'ad_name' => $input_data['name'],
				'location_id' => $input_data['location'],
				'image' =>$image,
				'adtype' => $input_data['adType'],
				'ad_url' => $input_data['adUrl'],
				'adsense_script' => $input_data['adDesc'],
				'siteadd_status' => $input_data['adStatus']
			);
			$this->db->insert($table,$input_data1);
			$this->session->set_userdata('adv_admin_success','Admin Adv Saved successfully.');
			redirect(base_url('advertise/ad-show-list'));
		}
	}
	public function adLocationDelete($id = 0){
		if($id != 0 && $id != ""){
			$this->db->where('id', $id);
			$this->db->delete('com_site_add');
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong.'));
		}
	}
	public function adUserListDelete($id = 0){
		if($id != 0 && $id != ""){
			$this->db->where('id', $id);
			$this->db->delete('com_advertisement_with_us');
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong.'));
		}
	}
	public function editLocation($id = 0){
		if($id!=0 && $id != ""){
			$this->db->from("com_site_add_location");
			$this->db->where('id', $id);
			$query = $this->db->get();  
			$result = $query->row();
			$this->data['locationData'] = $result;
			$this->load->view('advertise/editLocation',$this->data);
		}else{
			redirect(base_url('advertise/ad-location'));
		}
	}
	public function saveLocation($id = 0){
		$input_data = $this->input->post();
		$input_data = $this->defaultdata->secureInput($input_data);
		$this->load->library('form_validation');
		if(!isset($id) || $id == 0 || $id == ""){
			$this->session->set_userdata('adv_location_error','Something went wrong. Parameter not found.');
			redirect(base_url('advertise/edit-location/id/'.$id));
		}else {
			$this->form_validation->set_rules('location', 'Location Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('size', 'Dimention', 'trim|required|xss_clean');
			$this->form_validation->set_rules('price', 'Dimention', 'trim|numeric|required|xss_clean');
			$this->form_validation->set_rules('adStatus', 'Status', 'trim|required|xss_clean');
			
			if($this->form_validation->run() == FALSE) {
				$this->session->set_userdata('adv_location_error',validation_errors());
				redirect(base_url('advertise/edit-country/id/'.$id));
			} else {
				$input_data = array(
					'title' => (isset($input_data['location']) ? $input_data['location'] : ''),
					'dimension' => (isset($input_data['size']) ? $input_data['size']  : ''),
					'price' => (isset($input_data['price']) ? $input_data['price'] : ''),
					'weight' => (isset($input_data['weight']) ? $input_data['weight'] : ''),
					'status' => (isset($input_data['adStatus']) ? $input_data['adStatus'] : ''),
				);
				$this->db->set($input_data);
				$this->db->where('id', $id);
				$this->db->update('com_site_add_location');
				$this->session->set_userdata('adv_location_success','Advertise Location updated.');
				redirect(base_url('advertise/ad-location'));
			}
		}
	}
	public function editCountry($id = 0){
		//echo $id;die;
		if($id!=0 && $id != ""){
			$this->db->from("com_site_ad_country");
			$this->db->where('id', $id);
			$query = $this->db->get();  
			$result = $query->row();
			$this->data['countrydata'] = $result;
			$this->load->view('advertise/editCountry',$this->data);
		}else{
			redirect(base_url('advertise/ad-country'));
		}
	}
	public function saveCountry($id = 0){
		$input_data = $this->input->post();
		$input_data = $this->defaultdata->secureInput($input_data);
		$this->load->library('form_validation');
		if(!isset($id) || $id == 0 || $id == ""){
			$this->session->set_userdata('adv_contry_error','Something went wrong. Parameter not found.');
			redirect(base_url('advertise/edit-country/id/'.$id));
		} else {
			$this->form_validation->set_rules('location', 'Location Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('price', 'Price', 'trim|numeric|required|xss_clean');
			$this->form_validation->set_rules('adStatus', 'Status', 'trim|required|xss_clean');
			
			if($this->form_validation->run() == FALSE) {
				$this->session->set_userdata('adv_contry_error',validation_errors());
				redirect(base_url('advertise/edit-country/id/'.$id));
			} else {
				$input_data = array(
					'title' => (isset($input_data['location']) ? $input_data['location'] : ''),
					'status' => (isset($input_data['adStatus']) ? $input_data['adStatus']  : ''),
					'price' => (isset($input_data['price']) ? $input_data['price'] : ''),
				);
				$this->db->set($input_data);
				$this->db->where('id', $id);
				$this->db->update('com_site_ad_country');
				$this->session->set_userdata('adv_contry_success','Advertise country Location updated.');
				redirect(base_url('advertise/ad-country'));
			}
		}
	}
	public function editAd($id = 0){
		if($id!=0 && $id != ""){
			$this->db->from("com_site_add_location");
			$this->db->where('status','Y');
			$query = $this->db->get();  
			$results = $query->result_array();
			
			$this->db->from("com_site_add");
			$this->db->where('id', $id);
			$query = $this->db->get();  
			$result = $query->row();
			$this->data['adData'] = $result;
			$this->data['locationData'] = $results;
			$this->load->view('advertise/adnewadv',$this->data);
		}else{
			redirect(base_url('advertise/ad-show-list'));
		}
	}
	public function saveAd($id){
		$input_data = $this->input->post();
		$folder = "../upload/site_adds/";
		if(!isset($id) || $id == 0 || $id == ""){
			redirect(base_url('advertise/ad-show-list'));
		}else {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Advertise name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('location', 'Location', 'trim|required|xss_clean');
			$this->form_validation->set_rules('adType', 'Advertise type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('adStatus', 'Status', 'trim|required|xss_clean');
			if($input_data['adType'] == 'I'){
				$this->form_validation->set_rules('adUrl', 'Url', 'trim|required|xss_clean');
			} else if($input_data['adType'] == 'A'){
				$this->form_validation->set_rules('adDesc', 'Google adsense script', 'trim|required|xss_clean');
			}
			if($this->form_validation->run() == FALSE) {
				$this->session->set_userdata('adv_admin_error',validation_errors());
				redirect(base_url('advertise/ad-edit-list/id/'.$id));
			} else {
				$this->db->from("com_site_add");
				$this->db->where('id', $id);
				$query = $this->db->get();  
				$result = $query->row();
				if(!empty($result)){
					if(isset($_FILES) && $_FILES['adBanner']['name'] != ''){
						$pic = $_FILES["adBanner"]["name"];
						$path = $folder.$pic;
						if (move_uploaded_file($_FILES['adBanner']['tmp_name'], $path)) {
							$image = $_FILES['adBanner']['name'];
						} else {
							$this->session->set_userdata('adv_admin_error','Something went wrong. Image not save.');
							redirect(base_url('advertise/ad-edit-list/id/'.$id));
						}
					} else {
						$image = $result->image;
					}
					$input_data1 = array(
						'ad_name' => $input_data['name'],
						'location_id' => $input_data['location'],
						'image' =>$image,
						'adtype' => $input_data['adType'],
						'ad_url' => $input_data['adUrl'],
						'adsense_script' => $input_data['adDesc'],
						'siteadd_status' => $input_data['adStatus']
					);
					$this->db->set($input_data1);
					$this->db->where('id', $id);
					$this->db->update('com_site_add');
					$this->session->set_userdata('adv_admin_success','Admin Adv updated successfully.');
					redirect(base_url('advertise/ad-show-list'));
				} else {
					$this->session->set_userdata('adv_admin_error','Something went wrong. Ivalid parameter.');
					redirect(base_url('advertise/ad-edit-list/id/'.$id));
				}
			}
		}
	}
	public function editUserAd($id = 0){
		if(isset($id) || $id != '' || $id != 0){
			$this->db->from("com_site_add_location");
			$this->db->where('status','Y');
			$query = $this->db->get();  
			$results = $query->result_array();
			$this->db->from("com_advertisement_with_us");
			$this->db->where('id', $id);
			$query = $this->db->get();  
			$result = $query->row();
			$this->data['adData'] = $result;
			$this->data['locationData'] = $results;
			$this->load->view('advertise/editUserAd',$this->data);
		}else{
			redirect(base_url('advertise/ad-show-list'));
		}
		
	}
	public function saveUserAd($id = 0){
		$input_data = $this->input->post();
		$folder = "../upload/site_adds/";
		if(!isset($id) || $id == 0 || $id == ""){
			redirect(base_url('advertise/ad-user-list'));
		}else {
			$error_msg = '';
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Advertise title', 'trim|required|xss_clean');
			$this->form_validation->set_rules('location', 'Location', 'trim|required|xss_clean');
			$this->form_validation->set_rules('ad_url', 'URL', 'trim|required|xss_clean');
			$this->form_validation->set_rules('start_date', 'Start date', 'trim|required|xss_clean');
			$this->form_validation->set_rules('end_date', 'End date', 'trim|required|xss_clean');
			$this->form_validation->set_rules('adStatus', 'Status', 'trim|required|xss_clean');
			if(strtotime($input_data['start_date']) > strtotime($input_data['end_date'])){
				$error_msg = "<p>Starting and anding date not proper.<p>";
			}
			
			if($this->form_validation->run() == FALSE || $error_msg != '') {
				$this->session->set_userdata('adv_user_error',validation_errors().$error_msg);
				redirect(base_url('advertise/edit-user-ad/id/'.$id));
			} else {
				$this->db->from("com_advertisement_with_us");
				$this->db->where('id', $id);
				$query = $this->db->get();  
				$result = $query->row();
				if(!empty($result)){
					if(isset($_FILES) && $_FILES['adBanner']['name'] != ''){
						$pic = $_FILES["adBanner"]["name"];
						$path = $folder.$pic;
						if (move_uploaded_file($_FILES['adBanner']['tmp_name'], $path)) {
							$image = $_FILES['adBanner']['name'];
						}else{
							$this->session->set_userdata('adv_user_error','Something went wrong. Image not save.');
							redirect(base_url('advertise/edit-user-ad/id/'.$id));
						}
					}else{
						$image = $result->image;
					}
					$input_data1 = array(
						'title' => $input_data['title'],
						'location_id' => $input_data['location'],
						'image' =>$image,
						'startDate' => $input_data['start_date'],
						'ad_url' => $input_data['ad_url'],
						'endDate' => $input_data['end_date'],
						'status' => $input_data['adStatus']
					);
					$this->db->set($input_data1);
					$this->db->where('id', $id);
					$this->db->update('com_advertisement_with_us');
					$this->session->set_userdata('adv_user_success','Adv Inserted successfully.');
					redirect(base_url('advertise/ad-user-list'));
				} else {
					$this->session->set_userdata('adv_user_error','Something went wrong. Ivalid parameter.');
					redirect(base_url('advertise/edit-user-ad/id/'.$id));
				}
			}
		}
	}
}