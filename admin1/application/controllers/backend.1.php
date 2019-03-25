<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend extends CI_Controller {

	public $data=array();
	public $loggedout_method_arr = array('index');
	function __construct() {
		parent::__construct();
		$this->data=$this->defaultdata->getFrontendDefaultData();
		$this->load->model('userdata');
		if($this->defaultdata->is_session_active() == 1) {
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}		
	}
	// admin Login
	public function loginPage(){
		if($this->session->userdata('admuname') == ''){
			$this->load->view('login',$this->data);
		}else{
			redirect(base_url('/'));
		}
	}

	public function login(){
		$login_data=array();
		$input_data = $this->input->post();
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('admin_userName', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('admin_password', 'Password', 'trim|required|xss_clean');
		if($this->form_validation->run() == FALSE) {
			$this->session->set_userdata('login_error','Wrong username or password.');
			redirect(base_url('login'));
		} else {
        	@session_start();
			$where_arr = array();
			$where_arr['admin_userName'] = $input_data['admin_userName'];
			$where_arr['admin_password'] = ($input_data['admin_password']);
			$user_data = $this->userdata->grabLoginUserData($where_arr);

			if(count($user_data) > 0) {
				$this->defaultdata->setLoginSession($user_data);
				$_SESSION['admuser_id_session'] = $user_data->id;
				// print_r($this->session->userdata);die;
				if($this->session->userdata('admuname') != ''){
					redirect(base_url('/'));
				}else{
					redirect(base_url('login'));
				}
			} else {
				$this->session->set_userdata('login_error','Wrong username or password.');
				redirect(base_url('login'));
			}
		}
	}
	
	public function index() {
		if($this->session->userdata('admuname') != ''){
			$this->load->view('index',$this->data);	
		}else{
			redirect(base_url('login'));
		}
	}
	//for getting front imaegs  
	public function frontImages(){
		$this->db->select('*');
		$query = $this->db->get('com_frontend_images');
		$result = $query->row();
		$this->data['data'] = $result;
		$this->load->view('image/index',$this->data);	
	}
	public function updateImages(){
		$folder = "../assets/upload/frontendimage/";		
		if(!empty($_FILES)){		
			if(isset($_FILES['banner1']) && $_FILES['banner1']['name'] != '' ){
				$pic = $_FILES["banner1"]["name"];
				$path = $folder.$pic;
				//echo $path;die;
				if (move_uploaded_file($_FILES['banner1']['tmp_name'], $path)) {
					$data=array('banner1'=>$_FILES['banner1']['name']);
					$this->db->where('id',1);
					$this->db->update('com_frontend_images',$data);
					redirect(base_url('front-images'));
				}else{
					redirect(base_url('front-images'));
				}
			}else if(isset($_FILES['banner2']) && $_FILES['banner2']['name'] != '' ){
				$pic = $_FILES["banner2"]["name"];
				$path = $folder.$pic;
				if (move_uploaded_file($_FILES['banner2']['tmp_name'], $path)) {
					$data=array('banner2'=>$_FILES['banner2']['name']);
					$this->db->where('id',1);
					$this->db->update('com_frontend_images',$data);
					redirect(base_url('front-images'));
				}else{
					redirect(base_url('front-images'));
				}
			}else if(isset($_FILES['banner3']) && $_FILES['banner3']['name'] != '' ){
				$pic = $_FILES["banner3"]["name"];
				$path = $folder.$pic;
				if (move_uploaded_file($_FILES['banner3']['tmp_name'], $path)) {
					$data=array('banner3'=>$_FILES['banner3']['name']);
					$this->db->where('id',1);
					$this->db->update('com_frontend_images',$data);
					redirect(base_url('front-images'));
				}else{
					redirect(base_url('front-images'));
				}
			}else if(isset($_FILES['banner4']) && $_FILES['banner4']['name'] != '' ){
				$path = $folder.$pic;
				if (move_uploaded_file($_FILES['banner4']['tmp_name'], $path)) {
					$data=array('banner4'=>$_FILES['banner4']['name']);
					$this->db->where('id',1);
					$this->db->update('com_frontend_images',$data);
					redirect(base_url('front-images'));
				}else{
					redirect(base_url('front-images'));
				}
			}else if(isset($_FILES['banner5']) && $_FILES['banner5']['name'] != '' ){
				$path = $folder.$pic;
				if (move_uploaded_file($_FILES['banner5']['tmp_name'], $path)) {
					$data=array('banner5'=>$_FILES['banner5']['name']);
					$this->db->where('id',1);
					$this->db->update('com_frontend_images',$data);
					redirect(base_url('front-images'));
				}else{
					redirect(base_url('front-images'));
				};
			}else if(isset($_FILES['suscribe']) && $_FILES['suscribe']['name'] != '' ){
				$path = $folder.$pic;
				if (move_uploaded_file($_FILES['suscribe']['tmp_name'], $path)) {
					$data=array('suscribe'=>$_FILES['suscribe']['name']);
					$this->db->where('id',1);
					$this->db->update('com_frontend_images',$data);
					redirect(base_url('front-images'));
				}else{
					redirect(base_url('front-images'));
				}
			}else{
				redirect(base_url('front-images'));
			}
		}else{
			redirect(base_url('front-images'));
		}
	}

	public function logout() {
		$this->defaultdata->unsetLoginSession();
		@session_start();
		@session_destroy();
		redirect(base_url());
	}
	// admin Login

	// Email Template
	public function newEmailTemplate(){
		if($this->session->userdata('admuname') != ''){
			$this->load->view('email/emailDetail',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function newEmailProcess(){
		if($this->session->userdata('admuname') != ''){
			$table = TABLE_EMAIL_TEMPLATE;
			$input_data = $this->input->post();
			if(empty($input_data) || !isset($input_data['emailTitle']) || !isset($input_data['EmailBody']) || $input_data['emailTitle'] == '' || $input_data['EmailBody'] == ''){
				$this->session->set_userdata('email_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('email/new-email'));
			} else {
				$input_data = array(
					'emailTitle' => $input_data['emailTitle'],
					'description' => $input_data['EmailBody']
					);
				$this->db->insert($table,$input_data);
				if($this->db->insert_id() != 0){
					$this->session->set_userdata('email_sucess','Email teplate inserted sucessfully.');
					redirect(base_url('email/list-email'));
				} else {
					$this->session->set_userdata('email_error','Something went wrong.');
					$this->session->set_userdata('input_data',$input_data);
					redirect(base_url('email/new-email'));
				}
			}
		} else {
			redirect(base_url('/'));
		}
	}

	public function listEmailTemplate(){
		if($this->session->userdata('admuname') != ''){
			$table = TABLE_EMAIL_TEMPLATE;
			$query = $this->db->get($table);
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $j=>$r){
					foreach($r as $k=>$a){
						$data[$j][] = $a;
					}
				}
			}
			$this->data['data'] = $data;
			$this->load->view('email/email_list',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function emailTemplate($id = 0){
		if($this->session->userdata('admuname') != ''){
			if($id != '' && $id != 0){
				$cond = array('id' => $id);
				$table_name = TABLE_EMAIL_TEMPLATE;
				$query = $this->db->get_where($table_name, $cond);
				$this->data['data'] = $query->row();
				$this->load->view('email/emailDetail',$this->data);
			} else {
				redirect(base_url('email/list-email'));
			}
		} else {
			redirect(base_url('/'));
		}
	}

	public function editEmailProcess($id = 0){
		if($this->session->userdata('admuname') != ''){
			$table = TABLE_EMAIL_TEMPLATE;
			$input_data = $this->input->post();
			if(empty($input_data) || !isset($input_data['emailTitle']) || !isset($input_data['EmailBody']) || $input_data['emailTitle'] == '' || $input_data['EmailBody'] == ''){
				$this->session->set_userdata('email_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('/email-edit/id/'.$id));
			} else {
				$input_data = array(
					'emailTitle' => $input_data['emailTitle'],
					'description' => $input_data['EmailBody']
				);
				$this->db->set($input_data);
				$this->db->where('id', $id);
				$this->db->update($table);
				$this->session->set_userdata('email_sucess','Email teplate Updated sucessfully.');
				redirect(base_url('/email-edit/id/'.$id));
			}
		} else {
			redirect(base_url('/'));
		}
	}

	public function deleteEmailProcess($id = 0){
		if($id != 0 && $id != ""){
			$table = TABLE_EMAIL_TEMPLATE;
			$this->db->where('ID', $id);
			$this->db->delete($table);
			if($this->db->affected_rows() > 0){
				echo json_encode(array('status'=>true));die;
			} else {
				echo json_encode(array('status'=>false,'message'=> 'Something went wrong.'));die;
			}
		}else{
			echo json_encode(array('status'=>false,'message'=>'Parameter not found.'));die;
		}
	}
	// Email Template

	// Site Managment
	public function listSitePages(){
		if($this->session->userdata('admuname') != ''){
			$table = TABLE_CMS;
			$this->db->select('id, title, sub_title, slug');
			$query = $this->db->get($table);
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $j=>$r){
					foreach($r as $k=>$a){
						$data[$j][] = $a;
					}
				}
			}
			$this->data['data'] = $data;
			$this->load->view('cms/cms_list',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function newSitePage(){
		if($this->session->userdata('admuname') != ''){
			$this->load->view('cms/cms_detail',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function newSiteProcess(){
		if($this->session->userdata('admuname') != ''){
			$table = TABLE_CMS;
			$sub_table = TABLE_CMS_DATA;
			$input_data = $this->input->post();
			if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 1){
				for ($i = 0;$i < count($input_data['question']) ; $i++) {
					$extra_data[] = (object) array(
						'name' => $input_data['question'][$i],
						'value' => $input_data['answer'][$i],
						'type' => 'question-answer'
					);
				}
			} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 2){
				for ($i = 0;$i < count($input_data['service_image']) ; $i++) {
					$img_name = upload_image($input_data['service_image'][$i], 'cms_service');
					$extra_data[] = (object) array(
						'name' => $img_name,
						'value' => $input_data['service_description'][$i],
						'type' => 'services'
					);
				}
			} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 3){
				for ($i = 0;$i < count($input_data['link_name']) ; $i++) {
					$extra_data[] = (object) array(
						'name' => $input_data['link_name'][$i],
						'value' => $input_data['link_value'][$i],
						'type' => 'liks'
					);
				}
			}
			if(empty($input_data) || !isset($input_data['title']) || !isset($input_data['key']) || $input_data['title'] == '' || $input_data['key'] == ''){
				$this->session->set_userdata('site_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				$this->session->set_userdata('extra_data',$extra_data);
				redirect(base_url('site/new-page'));
			} else {
				$input_data1 = array(
					'title' => $input_data['title'],
					'slug' => $input_data['key'],
					'sub_title' => $input_data['sub_title'],
					'description' => $input_data['description'],
					'meta_title' => $input_data['meta_title'],
					'meta_description' => $input_data['meta_description']
				);
				$this->db->insert($table,$input_data1);
				if($this->db->insert_id() != 0){
					$rec_id = $this->db->insert_id();
					if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 1){
						for ($i = 0;$i < count($input_data['question']) ; $i++) {
							$data[] = array(
								'cms_id' => $rec_id,
								'name' => $input_data['question'][$i],
								'value' => $input_data['answer'][$i],
								'type' => 'question-answer'
							);
						}
						$this->db->insert_batch($sub_table, $data);
					} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 2){
						for ($i = 0;$i < count($input_data['service_description']) ; $i++) {
							$img_array = array(
								'name' => $_FILES['service_image']['name'][$i],
								'size' => $_FILES['service_image']['size'][$i],
								'type' => $_FILES['service_image']['type'][$i],
								'tmp_name' => $_FILES['service_image']['tmp_name'][$i]
							);
							$img_name = upload_image($img_array, 'cms_service');
							if($img_name != false){
								$data[] = array(
									'cms_id' => $rec_id,
									'name' => $img_name,
									'value' => $input_data['service_description'][$i],
									'type' => 'services'
								);
							}
						}
						if(!empty($data)){
							$this->db->insert_batch($sub_table, $data);
						}
					} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 3){
						for ($i = 0;$i < count($input_data['link_name']) ; $i++) {
							$data[] = array(
								'cms_id' => $rec_id,
								'name' => $input_data['link_name'][$i],
								'value' => $input_data['link_value'][$i],
								'type' => 'links'
							);
						}
						$this->db->insert_batch($sub_table, $data);
					}
					$this->session->set_userdata('site_sucess','CMS page inserted sucessfully.');
					redirect(base_url('site/list-pages'));
				} else {
					$this->session->set_userdata('site_error','Something went wrong.');
					$this->session->set_userdata('input_data',$input_data);
					$this->session->set_userdata('extra_data',$extra_data);
					redirect(base_url('site/new-page'));
				}
			}
		} else {
			redirect(base_url('/'));
		}
	}

	public function editSitePage($id = 0){
		if($this->session->userdata('admuname') != ''){
			if($id != '' && $id != 0){
				$cond = array('id' => $id);
				$table_name = TABLE_CMS;
				$sub_table = TABLE_CMS_DATA;
				$query = $this->db->get_where($table_name, $cond);
				$this->data['data'] = $query->row();
				$cond = array('cms_id' => $id);
				$query = $this->db->get_where($sub_table, $cond);
				$this->data['extra_data'] = $query->result();

				$this->load->view('cms/cms_detail',$this->data);
			} else {
				redirect(base_url('site/list-pages'));
			}
		} else {
			redirect(base_url('/'));
		}
	}

	public function editSiteProcess($id = 0){
		// print_r($_FILES['service_image']);die;
		if($this->session->userdata('admuname') != ''){
			$table = TABLE_CMS;
			$sub_table = TABLE_CMS_DATA;
			$input_data = $this->input->post();
			if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 1){
				for ($i = 0;$i < count($input_data['question']) ; $i++) {
					$extra_data[] = (object) array(
						'name' => $input_data['question'][$i],
						'value' => $input_data['answer'][$i],
						'type' => 'question-answer'
					);
				}
			} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 2){
				for ($i = 0;$i < count($input_data['service_image']) ; $i++) {
					$img_name = upload_image($input_data['service_image'][$i], 'cms_service');
					$extra_data[] = (object) array(
						'name' => $img_name,
						'value' => $input_data['service_description'][$i],
						'type' => 'services'
					);
				}
			} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 3){
				for ($i = 0;$i < count($input_data['link_name']) ; $i++) {
					$extra_data[] = (object) array(
						'name' => $input_data['link_name'][$i],
						'value' => $input_data['link_value'][$i],
						'type' => 'links'
					);
				}
			}
			if(empty($input_data) || !isset($input_data['title']) || $input_data['title'] == ''){
				$this->session->set_userdata('site_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				$this->session->set_userdata('extra_data',$extra_data);
				redirect(base_url('/site-edit/id/'.$id));
			} else {
				$input_data1 = array(
					'title' => $input_data['title'],
					'sub_title' => $input_data['sub_title'],
					'description' => $input_data['description'],
					'meta_title' => $input_data['meta_title'],
					'meta_description' => $input_data['meta_description']
				);
				$this->db->set($input_data1);
				$this->db->where('id', $id);
				$this->db->update($table);

				$get_img_cond = array('cms_id'=> $id, 'type' => 'services');
				$this->db->select('name');
				$query = $this->db->get_where($sub_table, $get_img_cond);
				$old_result = $query->result_array();
				
				$this->db->where('cms_id', $id);
				$this->db->delete($sub_table);

				if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 1){
					for ($i = 0;$i < count($input_data['question']) ; $i++) {
						$data[] = array(
							'cms_id' => $id,
							'name' => $input_data['question'][$i],
							'value' => $input_data['answer'][$i],
							'type' => 'question-answer'
						);
					}
					$this->db->insert_batch($sub_table, $data);
				} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 2){
					for ($i = 0;$i < count($input_data['service_description']) ; $i++) {
						
						$img_array = array(
							'name' => $_FILES['service_image']['name'][$i],
							'size' => $_FILES['service_image']['size'][$i],
							'type' => $_FILES['service_image']['type'][$i],
							'tmp_name' => $_FILES['service_image']['tmp_name'][$i]
						);
						print_r($img_array);

						$img_name = upload_image($img_array, 'cms_service');
						echo($img_name);
						$data[] = array(
							'cms_id' => $id,
							'name' => $img_name,
							'value' => $input_data['service_description'][$i],
							'type' => 'services'
						);
						print_r($data);
					}
					$this->db->insert_batch($sub_table, $data);
				} else if(isset($input_data['add_qus_ans']) && $input_data['add_qus_ans'] == 3){
					for ($i = 0;$i < count($input_data['link_name']) ; $i++) {
						$data[] = array(
							'cms_id' => $id,
							'name' => $input_data['link_name'][$i],
							'value' => $input_data['link_value'][$i],
							'type' => 'links'
						);
					}
					$this->db->insert_batch($sub_table, $data);
				}
				if(!empty($old_result)){
					foreach($old_result as $j=>$r){
						$path =  getcwd().'/'.$r['name'];
						if(file_exists($path)){
							chmod($path, 0777);
							unlink($path);
						}
					}
				}
				$this->session->set_userdata('site_sucess','CMS page Updated sucessfully.');
				redirect(base_url('site/list-pages'));
			}
		} else {
			redirect(base_url('/'));
		}
	}

	public function deleteSiteProcess($id = 0){
		if($id != 0 && $id != ""){
			$table = TABLE_CMS;
			$sub_table = TABLE_CMS_DATA;
			$this->db->where('ID', $id);
			$this->db->delete($table);
			if($this->db->affected_rows() > 0){
				$get_img_cond = array('cms_id'=> $id, 'type' => 'services');
				$this->db->select('name');
				$query = $this->db->get_where($sub_table, $get_img_cond);
				$result = $query->result_array();
				if(!empty($result)){
					foreach($result as $j=>$r){
						$path =  getcwd().'/'.$r['name'];
						chmod($path, 0777);
						if(file_exists($path)){
							unlink($path);
						}
					}
				}
				$this->db->where('cms_id', $id);
				$this->db->delete($sub_table);
				echo json_encode(array('status'=>true));die;
			} else {
				echo json_encode(array('status'=>false,'message'=> 'Something went wrong.'));die;
			}
		}else{
			echo json_encode(array('status'=>false,'message'=>'Parameter not found.'));die;
		}
	}
	// Site Managment

	public function generalSettings(){
		if($this->session->userdata('admuname') != ''){
			$g_table = TABLE_GENERAL_SETTINGS;
			$admin_table = TABLE_ADMINUSER;
			$this->db->where('id', $this->session->userdata('admusrid'));
			$query = $this->db->get_where($admin_table);
			$this->data['admin_result'] = $query->row();
			$query1 = $this->db->get($g_table);
			$this->data['gen_result'] = $query1->row();
			$this->load->view('user/setting',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function generalSettingsProccess(){
		if($this->session->userdata('admuname') != ''){
			$g_table = TABLE_GENERAL_SETTINGS;
			$admin_table = TABLE_ADMINUSER;
			$input_data = $this->input->post();
			if(empty($input_data) || !isset($input_data['name']) || $input_data['name'] == '' ||
			 !isset($input_data['userName']) || $input_data['userName'] == '' ||
			  !isset($input_data['adminEmailAddress']) || $input_data['adminEmailAddress'] == '' || 
			  !isset($input_data['paypalemailaddress']) || $input_data['paypalemailaddress'] == ''){
				$this->session->set_userdata('setting_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('general-settings'));
			} if(isset($input_data['adminnewPassword']) && $input_data['adminnewPassword'] != '' && ( !isset($input_data['ReadminnewPassword']) || $input_data['adminnewPassword'] != $input_data['ReadminnewPassword'] || $input_data['ReadminnewPassword'] == '')){
					$this->session->set_userdata('setting_error','The password not match.');
					$this->session->set_userdata('input_data',$input_data);
					redirect(base_url('general-settings'));
			} else {
				$input_data1 = array(
					'admin_userName' => $input_data['userName'],
					'name' => $input_data['name'],
				);
				if(isset($input_data['adminnewPassword']) && $input_data['adminnewPassword'] != '' ){
					$input_data1['admin_password'] = $input_data['adminnewPassword'];
				}
				$this->db->set($input_data1);
				$this->db->where('id', $this->session->userdata('admusrid'));
				$this->db->update($admin_table);

				$query1 = $this->db->get($g_table);
				$old_image = $query1->row('site_logo');
				$img_name = '';
				if($_FILES['site_logo']['name'] != '' || $_FILES['site_logo']['size'] != 0 ){
					$img_name = upload_image($_FILES['site_logo'], 'general_setting');
				}
				$input_data1 = array(
					'adminName' => $input_data['name'],
					'adminEmailAddress' => $input_data['adminEmailAddress'],
					'paypalemailaddress' => ( (isset($input_data['paypalemailaddress']) && $input_data['paypalemailaddress'] != '') ? $input_data['paypalemailaddress'] : ''),
					'Contact_Email' => ( (isset($input_data['Contact_Email']) && $input_data['Contact_Email'] != '') ? $input_data['Contact_Email'] : ''),
					'contactEmailName' => ( (isset($input_data['contactEmailName']) && $input_data['contactEmailName'] != '') ? $input_data['contactEmailName'] : ''),
					'SiteTitle' => ( (isset($input_data['SiteTitle']) && $input_data['SiteTitle'] != '') ? $input_data['SiteTitle'] : ''),
					'siteurl' => ( (isset($input_data['siteurl']) && $input_data['siteurl'] != '') ? $input_data['siteurl'] : ''),
					'projects_per_page' => ( (isset($input_data['projects_per_page']) && $input_data['projects_per_page'] != '') ? $input_data['projects_per_page'] : 10),
					'fb_link' => ( (isset($input_data['fb_link']) && $input_data['fb_link'] != '') ? $input_data['fb_link'] : ''),
					'flickr_link' => ( (isset($input_data['flickr_link']) && $input_data['flickr_link'] != '') ? $input_data['flickr_link'] : ''),
					'twitter_link' =>((isset($input_data['twitter_link']) && $input_data['twitter_link'] != '') ? $input_data['twitter_link'] : ''),
					'gplus_link' => ( (isset($input_data['gplus_link']) && $input_data['gplus_link'] != '') ? $input_data['gplus_link'] : ''),
					'linkedin_link' =>((isset($input_data['gplus_link']) && $input_data['gplus_link'] != '') ? $input_data['gplus_link'] : ''),
					'meta_data' => ( (isset($input_data['meta_data']) && $input_data['meta_data'] != '') ? $input_data['meta_data'] : ''),
					'meta_keywords' => ((isset($input_data['meta_keywords']) && $input_data['meta_keywords'] != '') ? $input_data['meta_keywords'] : ''),
					'title' => ( (isset($input_data['title']) && $input_data['title'] != '') ? $input_data['title'] : ''),
					'copyright_text' => ( (isset($input_data['copyright_text']) && $input_data['copyright_text'] != '') ? $input_data['copyright_text'] : ''),
					'image_watermark' => ( (isset($input_data['image_watermark']) && $input_data['image_watermark'] != '') ? $input_data['image_watermark'] : '')
				);
				if($img_name != ''){
					$input_data1['site_logo'] = $img_name;
				}
				$this->db->set($input_data1);
				$this->db->where('id', 0);
				$this->db->update($g_table);
				if($img_name != ''){
					$path =  getcwd().'/../assets/images/'.$old_image;
					chmod($path, 0777);
					if(file_exists($path)){
						unlink($path);
					}
				}
				$this->session->set_userdata('setting_sucess','General settings updated sucessfully.');
				redirect(base_url('general-settings'));
			}
		} else {
			redirect(base_url('/'));
		}
	}



	// old functions ------------------------------------------------------------------------------
	public function allPost()
	{
		
		$post_cond = array();
		$post_cond['languageID'] = $this->session->userdata('languageID');
		$post_cond['URL_SEOTOOL'] = $this->data['tot_segments'][1];
		$this->data['static_post_data'] = $this->defaultdata->grabStaticPost($post_cond);
		$this->data['sitead_full'] = $this->sitead->getSiteAds(3);
		$this->load->view('static_post',$this->data);
	}
	public function contact()
	{
		echo "contact";
	}
	public function blog()
	{
		echo 'blog';
	}
	public function changeLanguage($lang = 1)
	{
		$this->session->set_userdata('languageID',$lang);
		redirect($this->agent->referrer());
	}
	//¶30102015 S
	function commentProcess()
    {
        $input_data = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xxs_clean');
        $this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|xxs_clean');
        $this->form_validation->set_message('valid_email', 'Please enter valid email.');
        $this->form_validation->set_rules('comment', 'Message', 'trim|required|xxs_clean');

        unset($input_data['submit']);
        $this->session->unset_userdata($input_data);
        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_userdata('comment_error',validation_errors());
            $this->session->set_userdata($input_data);
        }
        else
        {
            $insert_data['postedTime'] = time();
			$insert_data['articleID']=$input_data['articleID'];
			$insert_data['name']=$input_data['name'];
			$insert_data['emailAddress']=$input_data['emailAddress'];
			$insert_data['comment']=$input_data['comment'];
		
			/*echo "<pre>";
			print_r($input_data);*/
			//exit;
            $this->defaultdata->insertComment($insert_data);
			//exit;
        }
     redirect(base_url('article/'.$input_data['articleID'].'/'.$input_data['title']));
	}

	


    public function searchProcess()
    {
		$this->data['input_data'] = $input_data = $this->input->get();
		$this->data['cat_id'] = $cat_id =  $this->db->escape_str($input_data['ct']);

    	$cat_type = $this->db->query("SELECT * FROM com_category1 WHERE id = ".$cat_id)->row();
    	$this->data['category'] = $cat_type;
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));

		//$data = $this->db->query("SELECT * FROM com_postmeta WHERE postID IN (SELECT id FROM com_main_post_article WHERE countryID = ".$cat_id." ) group by postID")->result();
		
			/*if(trim($input_data['q']) == '' && $cat_id == 0)
			{
				$Postdata = array();
			}
			else{
				//$sql = $this->defaultdata->searchQueryProcessor($input_data);
				//$Postdata = $this->db->query($sql)->result();
				
			}*/
			$limitData_limit = $this->session->userdata['limitData'];
			if(isset($this->session->userdata['page_no'])){
			 $this->data['page_no'] = $this->session->userdata['page_no'];
		}
			
			$Postdata =$this->defaultdata->searchQueryProcessor($input_data,$limitData_limit);
			//echo $this->db->last_query();
			
    	$this->data['searchResult'] = $Postdata;
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		//echo $this->db->last_query();
		$this->session->set_userdata('page_no',1);
	   	$this->load->view('search-result',$this->data);
    }

    public function searchQueryProcessor($input_data)
    {
    	/*$ArticlesPrepositions = array('about','above','across','aboard','after','against','along','amid','among','anti','around','as','at','before','behind','below','beneath','beside','besides','between','beyond','but','by','concerning','considering','despite','down','during','except','excepting','excluding','following','for','from','in','inside','into','like','minus','near','of','off','on','onto','opposite','outside','over','past','per','plus','regarding','round','save','since','than','through','to','toward','towards','under','underneath','unlike','until','up','upon','versus','via','with','within','without');

    	if($cat_id > 0 && trim($q)== '') //10
    		{
    			$sql = "SELECT  com_main_post_article.* , com_category1.type as cat_type, com_category1.title as cat_title FROM  com_main_post_article JOIN com_category1 ON com_main_post_article.categoryID = com_category1.id  WHERE com_main_post_article.categoryID = ".$cat_id." ORDER BY com_main_post_article.postedTime DESC";
    		}
    	elseif($cat_id == 0 && trim($q) != '') //01
    	{ 
    		$q = mysql_real_escape_string($q);
    		$keys = explode(' ',$q);

    		$i = 0;
    		for (;$i < count($keys) ; $i++) {
    			$k = mysql_real_escape_string($keys[$i]);
    			$common = " com_main_post_article.projectDescription LIKE '%$k%' OR com_user.firstName LIKE '%$k%' OR com_user.lastName LIKE '%$k%' OR com_main_post_article.title LIKE '%$k%' OR com_category1.title LIKE '%$k%' ";
    			if($i == count($keys) - 1)
    				$sql_like .= $common;
    			else
    				$sql_like .= $common." OR";
    		}
    		$sql = "SELECT com_main_post_article.*,com_category1.type as cat_type, com_category1.title as cat_title FROM com_main_post_article JOIN com_user ON com_main_post_article.user_id = com_user.id JOIN com_category1 ON com_main_post_article.categoryID = com_category1.id WHERE (".$sql_like.") ORDER BY com_main_post_article.postedTime DESC";
    	}	
    	elseif($cat_id > 0 && trim($q) != '') //11
		{
    		$q = mysql_real_escape_string($q);
    		$keys = explode(' ',$q);

    		$i = 0;
    		for (;$i < count($keys) ; $i++) {
    			$k = mysql_real_escape_string($keys[$i]);
    			$common = " com_main_post_article.projectDescription LIKE '%$k%' OR com_user.firstName LIKE '%$k%' OR com_user.lastName LIKE '%$k%' OR com_main_post_article.title LIKE '%$k%' OR com_category1.title LIKE '%$k%' ";
    			if($i == count($keys) - 1)
    				$sql_like .= $common;
    			else
    				$sql_like .= $common." OR";
    		}
    		$sql = "SELECT com_main_post_article.*,com_category1.type as cat_type, com_category1.title as cat_title FROM com_main_post_article JOIN com_user ON com_main_post_article.user_id = com_user.id JOIN com_category1 ON com_main_post_article.categoryID = com_category1.id WHERE com_main_post_article.categoryID = ".$cat_id." AND  (".$sql_like.") ORDER BY com_main_post_article.postedTime DESC";
		}
		
		
		
    	return $sql;*/
    }
	public function innerSearchProcess()
	{
		$this->data['page_no'] = 1;
		/*$this->data['page_ads'] = get_ad_dynamically_for_differant_page();
		echo "<pre>";
		print_r($this->data['page_ads']);
		echo "</pre>";*/
		//print_r($this->input->get());
		$this->data['input_data'] = $input_data = $this->input->get();
		$this->data['page_name'] = 'innersearch';
		$cat_id = $this->db->escape_str($input_data['ct']);
		$this->data['categoryId'] = $this->db->select('categoryID')->from('com_main_post_article')->where('subCategoryID',$input_data['ct'])->limit(1)->get()->row()->categoryID;
    	$cat_type = $this->db->query("SELECT * FROM com_category1 WHERE id = ".$cat_id)->row();
		//echo "SELECT * FROM com_category1 WHERE id = ".$cat_id;
    	//$this->data['category'] = $cat_type;
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		/*echo "<pre>";
		print_r($this->data['allCategories']);
		exit;*/
		
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));

		//$data = $this->db->query("SELECT * FROM com_postmeta WHERE postID IN (SELECT id FROM com_main_post_article WHERE countryID = ".$cat_id." ) group by postID")->result();
		
			/*if(trim($input_data['q']) == '' && $cat_id == 0)
			{
				$Postdata = array();
			}
			else{
				//$sql = $this->defaultdata->searchQueryProcessor($input_data);
				//$Postdata = $this->db->query($sql)->result();
				
			}*/
		$input_data['cat_type']=$cat_type->type;
		$limitData_limit = $this->session->userdata['limitData'];
		if(isset($this->session->userdata['page_no'])){
			 $this->data['page_no'] = $this->session->userdata['page_no'];
		}
		/*echo $this->data['page_no'];
		exit;*/
		/*echo $this->session->userdata['page_no'];
		exit;*/
		$Postdata =$this->defaultdata->innerSearchQueryProcessor($input_data,$limitData_limit);
		//echo $this->db->last_query();
		//print_r($Postdata);exit;	
    	$this->data['searchResult'] = $Postdata;
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
	   //echo "<pre>";print_r($cat_type);
		$this->data['parent_id']=$cat_type->id;
		
		
		$current_user_id=$this->session->userdata('usrid');
		/*if($current_user_id!='')
		{
			$this->defaultdata->deleteHistory(array('article_id'=>$id,'user_id'=>$current_user_id));
			$insert_history['user_id']=$current_user_id;
			$insert_history['article_id']=$id;
			$insert_history['article_type']=$category_details->type;
			$insert_history['article_owner_id']=$article_data->user_id;
			$insert_history['search_time']=time();
			$id=$this->userdata->insertHistory($insert_history);
		}*/
		if($current_user_id!='')
		{
			
			$getMyArticleHistoryId=$this->defaultdata->getMyArticleHistoryId($current_user_id,array('rate'=>$input_data['rate'],'date'=>$input_data['date']));
			//echo "<pre>";print_r($getMyArticleHistoryId);exit;
			foreach($getMyArticleHistoryId as $val)
			{
				$add_articale_details[]=$this->defaultdata->grabPosts(array('id'=>$val->article_id));
			}
			//print_r($add_articale_details);exit;
			foreach($add_articale_details as $val)
			{
				
				 $add_details_sql = "SELECT ".TABLE_CATEGORYATTR.".fieldName, ".TABLE_META_ARTICLE.". *
                        FROM ".TABLE_CATEGORYATTR."
                        LEFT JOIN ".TABLE_META_ARTICLE." ON ".TABLE_CATEGORYATTR.".fieldSlug = REPLACE( ".TABLE_META_ARTICLE.".slugname,  'cat_',  '' )
                        WHERE ".TABLE_META_ARTICLE.".postID = ".$val->id."
                        AND ".TABLE_META_ARTICLE.".fieldType !=  '".$fieldTypeChooser[$category->type]."'
                        AND ".TABLE_CATEGORYATTR.".type = ".TABLE_META_ARTICLE.".fieldType GROUP BY ".TABLE_CATEGORYATTR.".fieldSlug order by ".TABLE_META_ARTICLE.".id";
				//echo $add_details_sql;
				$val->extra_details = $this->db->query($add_details_sql)->result();
				
			}
		}
		else
		{
			//$cat_type=$category_details->type;
			$get_arbitary_article=$this->defaultdata->arbitaryArticle();
			//print_r($get_arbitary_article);exit;
			$add_articale_details=$get_arbitary_article;
			foreach($add_articale_details as $val)
			{
				
				 $add_details_sql = "SELECT ".TABLE_CATEGORYATTR.".fieldName, ".TABLE_META_ARTICLE.". *
                        FROM ".TABLE_CATEGORYATTR."
                        LEFT JOIN ".TABLE_META_ARTICLE." ON ".TABLE_CATEGORYATTR.".fieldSlug = REPLACE( ".TABLE_META_ARTICLE.".slugname,  'cat_',  '' )
                        WHERE ".TABLE_META_ARTICLE.".postID = ".$val->id."
                        AND ".TABLE_META_ARTICLE.".fieldType !=  '".$fieldTypeChooser[$category->type]."'
                        AND ".TABLE_CATEGORYATTR.".type = ".TABLE_META_ARTICLE.".fieldType GROUP BY ".TABLE_CATEGORYATTR.".fieldSlug order by ".TABLE_META_ARTICLE.".id";
				//echo $add_details_sql;
				$val->extra_details = $this->db->query($add_details_sql)->result();
				
			}
			
			
		}
		//echo "<pre>".$this->data['categoryId'];exit;
		$this->data['adds'] = $add_articale_details;
		
		$this->session->set_userdata('page_no',1);
		$this->load->view('article-listing',$this->data);
	}
	public function about_us()
	{
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		
		$this->data['page_content'] = $this->db->select()->from('com_aboutus')->get()->row();
		//print_r($this->data['page_content'] );
		$this->load->view('about_up',$this->data);
	}
	public function contact_us()
	{
		if($this->input->post()){
			//echo "hello";
			$input_data = $this->input->post();
			//print_r($input_data);
			
			
			$to=$input_data['email'];
			$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email.">\n"; 
			$headers .= "MIME-Version: 1.0\n"; 
			$headers .= "Content-type: text/html; charset=UTF-8\n"; 
			$subject = 'Contact Us';
			$message ="<html><head></head><body>"."<style type=\"text/css\">
			<!--
			.style4 {font-size: x-small}
			-->
			</style>
			".$input_data['msg']."
			</body><html>"; 
			
			$insert_array['contactName'] = $input_data['name'];
			$insert_array['contactEmail'] = $input_data['email'];
			$insert_array['message'] = $input_data['msg'];
			$insert_array['postedtime'] = date('Y-m-d H:i:s');
			$insert_array['subject'] = 'Conatact Us';
			
			if($this->db->insert(TABLE_CONTACT_US,$insert_array)){
				@mail($to,$subject, $message,$headers);
				$this->data['mail_done'] = 1;
			}
			
		}
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		$this->data['page_content'] = $this->db->select()->from('com_page_contactus')->get()->row();
		$this->load->view('contact_us',$this->data);
	}
	public function privacy_policy()
	{
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		$this->data['page_content'] = $this->db->select()->from('com_page_privay_policy')->get()->row();
		$this->load->view('privacy_policy',$this->data);
	}
	public function terms_and_conditions()
	{
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		$this->data['page_content'] = $this->db->select()->from('com_page_terms')->get()->row();
		$this->load->view('terms_and_conditions',$this->data);
	}
	public function faq()
	{
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		$this->data['page_content'] = $this->db->select()->from('com_page_faq')->get()->row();
		$this->data['questions'] = $this->db->select()->from('com_page_faq_questions')->get()->result();
		$this->load->view('faq',$this->data);
	}
	
	public function our_vision()
	{
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		$this->data['page_content'] = $this->db->select()->from(TABLE_OUR_VISION)->get()->row();
		$this->load->view('our_vision',$this->data);
	}
	public function help()
	{
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		$this->data['page_content'] = $this->db->select()->from(TABLE_HELP)->get()->row();
		$this->load->view('help',$this->data);
	}
	
	public function our_services()
	{
		$this->data['postVideos'] = array();
		$this->data['allCategories'] = $this->defaultdata->getCategories(array('status' => 'Y','parentID' => '0'));
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		$this->data['frontimages'] = $this->defaultdata->getFrontImages();
		$this->data['page_content'] = $this->db->select()->from(TABLE_OUR_SERVICES)->get()->row();
		$this->data['services'] = $this->db->select()->from(TABLE_SERVICES_SERVICE)->get()->result();
		/*print_r($this->data['services']);
		exit;*/
		$this->load->view('our_services',$this->data);
	}
}

/* End of file frontend.php */
/* Location: ./application/controllers/frontend.php */