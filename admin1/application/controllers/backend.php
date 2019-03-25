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
			$cond = array('status'=>'Y');
			$getAllActiveUser = $this->userdata->getAllUsers($cond);;
			$this->data['activeUsers'] = count($getAllActiveUser);
			$allActiveProjects = $this->userdata->getProjects(array('status'=>'Y','is_delete'=>'N'));
			$this->data['activeProjects'] = count($allActiveProjects);
			$this->data['projects'] = ($allActiveProjects);
			$this->data['users'] = ($getAllActiveUser);
			$countries = $this->userdata->getCountry($cond);
			$this->data['countries'] = count($countries);
			$cond = array('status'=>'N');
			$getAllDeActiveUser = $this->userdata->getAllUsers($cond);
			$this->data['deActiveUsers'] = count($getAllDeActiveUser);
			$allDeActiveProjects = $this->userdata->getProjects($cond);
			$this->data['deActiveProjects'] = count($allDeActiveProjects);
			$cond = array('status'=>'P');
			$allPendingProjects = $this->userdata->getProjects($cond);
			$this->data['pendingProjects'] = count($allPendingProjects);
			
			$this->data['total_user_count'] = $this->userdata->getTotalCount(0);
			$this->data['web_user_count'] = $this->userdata->getTotalCount(1);
			$this->data['fb_user_count'] = $this->userdata->getTotalCount(2);
			$this->data['gp_user_count'] = $this->userdata->getTotalCount(3);
			$this->data['li_user_count'] = $this->userdata->getTotalCount(4);

			$this->data['login_user'] = $this->userdata->getLoginCount();
			$this->data['count_adv'] = $this->defaultdata->getallAdv();
			$this->data['male_user'] = $this->userdata->getMaleFemaleCount('M');
			$this->data['female_user'] = $this->userdata->getMaleFemaleCount('F');

			$this->data['skill_data'] = $this->userdata->getMaleFemaleSkillCountData();

			$this->data['user_article_category_data'] = $this->userdata->getCategoryArticleData();

			$this->load->view('index',$this->data);
		}else{
			redirect(base_url('login'));
		}
	}
	//for getting front imaegs  
	public function frontImages(){
		$this->defaultdata->checkLogin();
		$this->db->select('*');
		$query = $this->db->get('com_frontend_images');
		$result = $query->row();
		$this->data['data'] = $result;
		$this->load->view('image/index',$this->data);	
	}

	public function updateImages(){
		$folder = "../assets/upload/frontendimage/";
		$this->defaultdata->checkLogin();
		if(!empty($_FILES)){
			$data = array();
			$error = array();
			foreach ($_FILES as $k=>$value){
				if($value['name'] != ''){
					
					$ext = pathinfo($value['name'], PATHINFO_EXTENSION);
					$pic = uniqid().time().'.'.$ext;
					$path = $folder.$pic;
					$old_img = [];
					$old_data = $this->db->select()->from('com_frontend_images')->where('id',1)->get()->row_array();
					if (move_uploaded_file($value['tmp_name'], $path)) {
						chmod($path, 0777);
						if($k == 'banner1'){
							array_push($old_img,$old_data['banner1']);
							$data['banner1']=$pic;
						}else if($k == 'banner2'){
							array_push($old_img,$old_data['banner2']);
							$data['banner2']=$pic;
						}else if($k == 'banner3'){
							array_push($old_img,$old_data['banner3']);
							$data['banner3']=$pic;
						}else if($k == 'banner4'){
							array_push($old_img,$old_data['banner4']);
							$data['banner4']=$pic;
						}else if($k == 'banner5'){
							array_push($old_img,$old_data['banner5']);
							$data['banner5']=$pic;
						}else if($k == 'suscribe'){
							array_push($old_img,$old_data['suscribe']);
							$data['suscribe']=$pic;
						}else if($k == 'hall_of_fame'){
							array_push($old_img,$old_data['hall_of_fame']);
							$data['hall_of_fame']=$pic;
						}else if($k == 'price_list'){
							array_push($old_img,$old_data['price_list']);
							$data['price_list']=$pic;
						}else if($k == 'cms_image'){
							array_push($old_img,$old_data['cms_image']);
							$data['cms_image']=$pic;
						}else if($k == 'contact_image'){
							array_push($old_img,$old_data['contact_image']);
							$data['contact_image']=$pic;
						}else if($k == 'video_list_image'){
							array_push($old_img,$old_data['video_list_image']);
							$data['video_list_image']=$pic;
						}else if($k == 'audio_list_image'){
							array_push($old_img,$old_data['audio_list_image']);
							$data['audio_list_image']=$pic;
						}else if($k == 'art_list_image'){
							array_push($old_img,$old_data['art_list_image']);
							$data['art_list_image']=$pic;
						}else if($k == 'writing_list_image'){
							array_push($old_img,$old_data['writing_list_image']);
							$data['writing_list_image']=$pic;
						}else if($k == 'article_detail_image'){
							array_push($old_img,$old_data['article_detail_image']);
							$data['article_detail_image']=$pic;
						}else if($k == 'user_dashboard_image'){
							array_push($old_img,$old_data['user_dashboard_image']);
							$data['user_dashboard_image']=$pic;
						}else if($k == 'user_playlist_image'){
							array_push($old_img,$old_data['user_playlist_image']);
							$data['user_playlist_image']=$pic;
						}else if($k == 'user_play_image'){
							array_push($old_img,$old_data['user_play_image']);
							$data['user_play_image']=$pic;
						}else if($k == 'user_favorite_image'){
							array_push($old_img,$old_data['user_favorite_image']);
							$data['user_favorite_image']=$pic;
						}
					} else {
						// echo '$k';
						$error[] = 'The '.$k.' Not uploaded.';
					}
				}
			}			
			if(!empty($data)){
				$this->db->where('id',1);
				$this->db->update('com_frontend_images',$data);
				$this->session->set_userdata('image_success','Images Updated Successfully.');
			}
			if(!empty($error)){
				$error_msg = '<p>'.implode("</p><p>",$error).'</p>';
				$error_msg = ltrim($error_msg,"<p></p>");
				$this->session->set_userdata('image_error',$error_msg);
			}

			if(!empty($old_img)){
				foreach ($old_img as $old_value){
					$old_path = getcwd().'/'.$folder.$old_value;
					if($old_value != '' && file_exists($old_path)){
						chmod($old_path, 0777);
						unlink($old_path);
					}
				}
			}
			redirect(base_url('front-images'));

			// if(isset($_FILES['banner1']) && $_FILES['banner1']['name'] != '' ){
			// 	$pic = $_FILES["banner1"]["name"];
			// 	$path = $folder.$pic;
			// 	//echo $path;die;
			// 	if (move_uploaded_file($_FILES['banner1']['tmp_name'], $path)) {
			// 		$data=array('banner1'=>$_FILES['banner1']['name']);
			// 		$this->db->where('id',1);
			// 		$this->db->update('com_frontend_images',$data);
			// 		redirect(base_url('front-images'));
			// 	}else{
			// 		redirect(base_url('front-images'));
			// 	}
			// }else if(isset($_FILES['banner2']) && $_FILES['banner2']['name'] != '' ){
			// 	$pic = $_FILES["banner2"]["name"];
			// 	$path = $folder.$pic;
			// 	if (move_uploaded_file($_FILES['banner2']['tmp_name'], $path)) {
			// 		$data=array('banner2'=>$_FILES['banner2']['name']);
			// 		$this->db->where('id',1);
			// 		$this->db->update('com_frontend_images',$data);
			// 		redirect(base_url('front-images'));
			// 	}else{
			// 		redirect(base_url('front-images'));
			// 	}
			// }else if(isset($_FILES['banner3']) && $_FILES['banner3']['name'] != '' ){
			// 	$pic = $_FILES["banner3"]["name"];
			// 	$path = $folder.$pic;
			// 	if (move_uploaded_file($_FILES['banner3']['tmp_name'], $path)) {
			// 		$data=array('banner3'=>$_FILES['banner3']['name']);
			// 		$this->db->where('id',1);
			// 		$this->db->update('com_frontend_images',$data);
			// 		redirect(base_url('front-images'));
			// 	}else{
			// 		redirect(base_url('front-images'));
			// 	}
			// }else if(isset($_FILES['banner4']) && $_FILES['banner4']['name'] != '' ){
			// 	$pic = $_FILES["banner4"]["name"];
			// 	$path = $folder.$pic;
			// 	if (move_uploaded_file($_FILES['banner4']['tmp_name'], $path)) {
			// 		$data=array('banner4'=>$_FILES['banner4']['name']);
			// 		$this->db->where('id',1);
			// 		$this->db->update('com_frontend_images',$data);
			// 		redirect(base_url('front-images'));
			// 	}else{
			// 		redirect(base_url('front-images'));
			// 	}
			// }else if(isset($_FILES['banner5']) && $_FILES['banner5']['name'] != '' ){
			// 	$pic = $_FILES["banner5"]["name"];
			// 	$path = $folder.$pic;
			// 	if (move_uploaded_file($_FILES['banner5']['tmp_name'], $path)) {
			// 		$data=array('banner5'=>$_FILES['banner5']['name']);
			// 		$this->db->where('id',1);
			// 		$this->db->update('com_frontend_images',$data);
			// 		redirect(base_url('front-images'));
			// 	}else{
			// 		redirect(base_url('front-images'));
			// 	};
			// }else if(isset($_FILES['suscribe']) && $_FILES['suscribe']['name'] != '' ){
			// 	$pic = $_FILES["suscribe"]["name"];
			// 	$path = $folder.$pic;
			// 	if (move_uploaded_file($_FILES['suscribe']['tmp_name'], $path)) {
			// 		$data=array('suscribe'=>$_FILES['suscribe']['name']);
			// 		$this->db->where('id',1);
			// 		$this->db->update('com_frontend_images',$data);
			// 		redirect(base_url('front-images'));
			// 	}else{
			// 		redirect(base_url('front-images'));
			// 	}
			// }else{
			// 	redirect(base_url('front-images'));
			// }
		}else{
			$this->session->set_userdata('image_error','Parameter not found.');
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
			if(empty($input_data) || !isset($input_data['emailTitle']) || !isset($input_data['slug']) || !isset($input_data['EmailBody']) || $input_data['emailTitle'] == '' || $input_data['EmailBody'] == '' || $input_data['slug'] == ''){
				$this->session->set_userdata('email_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('email/new-email'));
			} else {
				$input_data = array(
					'emailTitle' => $input_data['emailTitle'],
					'slug' => $input_data['slug'],
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
			$this->db->select('id, title, sub_title, slug, status');
			$query = $this->db->get($table);
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $j=>$r){
					$id = $r['id'];
					foreach($r as $k=>$a){
						if($k == 'status'){
							$set_status = 0;
							if($a == 0) $set_status = 1;
							if($a == 1){
								$data[$j][] = '<a href="'.base_url("site-set-status").'" title="Set deactive" data-id="'.$id.'" data-status="'.$set_status.'"  class="set-page-status"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Active</a>';
							} else {
								$data[$j][] = '<a href="'.base_url("site-set-status").'" title="Set active" data-id="'.$id.'" data-status="'.$set_status.'" class="set-page-status"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Deactive</a>';
							}
						} else {
							$data[$j][] = $a;
						}
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
						'type' => 'links'
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
						$this->db->insert_batch($sub_table, $data);
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

	public function setSitePageStatus(){
		if($this->session->userdata('admuname') != ''){
			if(isset($_REQUEST) && $_REQUEST['id'] != '' && $_REQUEST['id'] != 0 && $_REQUEST['status'] != '' && ($_REQUEST['status'] == 0 || $_REQUEST['status'] == 1)){
				$input_data = array(
					'status' => $_REQUEST['status']
				);
				$this->db->set($input_data);
				$this->db->where('id', $_REQUEST['id']);
				$this->db->update(TABLE_CMS);

				echo json_encode(array('status'=>true,'message'=>'Status changed.'));die;
			} else {
				echo json_encode(array('status'=>false,'message'=>'Parameter not found.'));die;
			}
		} else {
			echo json_encode(array('status'=>false,'message'=>'Your session expired.'));die;
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
					//print_r($_POST);die;
					$img_name = "";
					$oldData  = array();
					for ($i = 0;$i < count($input_data['service_description']) ; $i++) {
						//echo 'image name = '.$_FILES['service_image']['name'][$i].'<br/>';
						if($_FILES['service_image']['name'][$i] == ""){
							//echo 'in if'.'<br/>';
							if(isset($id) &&  $id != '' && $_FILES['service_image']['name'][$i] == ""){
								//echo 'in ifff'.'<br/>';
								$img_name = $_POST['image'.$i];
							}else{
								//echo 'in else if'.'<br/>';
								$img_array = array(
									'name' => $_FILES['service_image']['name'][$i],
									'size' => $_FILES['service_image']['size'][$i],
									'type' => $_FILES['service_image']['type'][$i],
									'tmp_name' => $_FILES['service_image']['tmp_name'][$i]
								);
								print_r($img_array);
								$img_name = upload_image($img_array, 'cms_service');
								echo $img_name;
							}							
						}else{
							//echo 'in else'.'<br/>';
							$img_array = array(
								'name' => $_FILES['service_image']['name'][$i],
								'size' => $_FILES['service_image']['size'][$i],
								'type' => $_FILES['service_image']['type'][$i],
								'tmp_name' => $_FILES['service_image']['tmp_name'][$i]
							);
							$oldData[] =  $_POST['image'.$i];
							//print_r($img_array);
							$img_name = upload_image($img_array, 'cms_service');
							//echo $img_name;
						}
						
						
						$data[] = array(
							'cms_id' => $id,
							'name' => $img_name,
							'value' => $input_data['service_description'][$i],
							'type' => 'services'
						);
						//print_r($img_name);
					}
					//print_r($_FILES);die;
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
				//print_r($old_result);die;
				if(!empty($old_result)){
					foreach($old_result as $j=>$r){
						if(in_array($r['name'],$oldData)){
							$path =  getcwd().'/ads_file/'.$r['name'];
							if(file_exists($path)){
								chmod($path, 0777);
								unlink($path);
							}
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
						$path =  getcwd().'/ads_file/'.$r['name'];
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
			$this->db->where('id', $this->session->userdata('admusrid'));
			$query = $this->db->get_where(TABLE_ADMINUSER);
			$this->data['admin_result'] = $query->row();
			$query1 = $this->db->get(TABLE_GENERAL_SETTINGS);
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
			$googleanalytic = ((isset($input_data['googleanalytic']) && $input_data['googleanalytic'] != '') ? $input_data['googleanalytic'] : '');
			$input_data = $this->defaultdata->secureInput($input_data);
			$this->load->library('form_validation');
			

			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('userName', 'User Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('adminEmailAddress', 'Admin Email', 'trim|required|valid_email|callback_checkEmail|xss_clean');
			$this->form_validation->set_rules('Contact_Email', 'Contact Email', 'trim|required|valid_email|callback_checkEmail|xss_clean');
			$this->form_validation->set_rules('contactEmailName', 'Contact Email Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('super_admin_email', 'Super Admin Email', 'trim|required|valid_email|callback_checkEmail|xss_clean');
			$this->form_validation->set_rules('projects_per_page', 'Projects Per Page', 'trim|required|numeric|xss_clean');
			$this->form_validation->set_rules('copyright_text', 'Copyright Text', 'trim|required|xss_clean');
			$this->form_validation->set_rules('suscribe_no_days', 'Suscribe No Days', 'trim|required|numeric|xss_clean');
			$this->form_validation->set_rules('paypalemailaddress', 'Paypal Email', 'trim|required|valid_email|callback_checkEmail|xss_clean');
			$this->form_validation->set_rules('paypal_payment_type', 'Paypal Type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('paypalproapipassword', 'Paypal Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('paypalproapisignature', 'Paypal Signature', 'trim|required|xss_clean');
			$this->form_validation->set_rules('stripe_api_key', 'Stripe Api Key', 'trim|required|xss_clean');
			$this->form_validation->set_rules('cemail', 'Contact Email', 'trim|required|valid_email|callback_checkEmail|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
			$this->form_validation->set_rules('tele', 'Telephone', 'trim|required|numeric');
			$this->form_validation->set_rules('fb_app_id', 'FAcebook app ID', 'trim|required|numeric|xss_clean');
			$this->form_validation->set_rules('itunes_text', 'Itunes Text', 'trim|required|xss_clean');

			$address = str_replace(' ','+',$input_data['address']);
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'https://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&key=AIzaSyBTaaN1xqtlRUwQz1oeq3vT2yYakjcMZN0'
			));
		
			$result = curl_exec($curl);
		
			$output= json_decode($result);
			$lat = $output->results[0]->geometry->location->lat;
			$long = $output->results[0]->geometry->location->lng;
			if($this->form_validation->run() == FALSE && $input_data['series_add_new'] !== 'Y') {
				$this->session->set_userdata('setting_error',validation_errors());
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('general-settings'));
			} else {
				if(isset($input_data['adminnewPassword']) && $input_data['adminnewPassword'] != '' && ( !isset($input_data['ReadminnewPassword']) || $input_data['adminnewPassword'] != $input_data['ReadminnewPassword'] || $input_data['ReadminnewPassword'] == '')){
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
					if(isset($input_data['suscribe_no_days']) && $input_data['suscribe_no_days'] != '' && $this->data['general_settings']->suscribe_no_days != $input_data['suscribe_no_days']){
						$this->userdata->updateStripePlanTrialPeriod($input_data['suscribe_no_days']);
					}
					
					$input_data1 = array(
						'adminName' => $input_data['name'],
						'adminEmailAddress' => $input_data['adminEmailAddress'],
						'paypalemailaddress' => ((isset($input_data['paypalemailaddress']) && $input_data['paypalemailaddress'] != '') ? $input_data['paypalemailaddress'] : ''),
						'googleanalytic' => $googleanalytic,
						'Contact_Email' => ((isset($input_data['Contact_Email']) && $input_data['Contact_Email'] != '') ? $input_data['Contact_Email'] : ''),
						'super_admin_email' => ((isset($input_data['super_admin_email']) && $input_data['super_admin_email'] != '') ? $input_data['super_admin_email'] : ''),
						'paypal_payment_type' => $input_data['paypal_payment_type'],
						'paypalproapipassword' => ((isset($input_data['paypalproapipassword']) && $input_data['paypalproapipassword'] != '') ? $input_data['paypalproapipassword'] : ''),
						'paypalproapisignature' => ((isset($input_data['paypalproapisignature']) && $input_data['paypalproapisignature'] != '') ? $input_data['paypalproapisignature'] : ''),
						'stripe_api_key' => ((isset($input_data['stripe_api_key']) && $input_data['stripe_api_key'] != '') ? $input_data['stripe_api_key'] : ''),
						'contactEmailName' => ((isset($input_data['contactEmailName']) && $input_data['contactEmailName'] != '') ? $input_data['contactEmailName'] : ''),
						'SiteTitle' => ((isset($input_data['SiteTitle']) && $input_data['SiteTitle'] != '') ? $input_data['SiteTitle'] : ''),
						'siteurl' => ((isset($input_data['siteurl']) && $input_data['siteurl'] != '') ? $input_data['siteurl'] : ''),
						'projects_per_page' => ((isset($input_data['projects_per_page']) && $input_data['projects_per_page'] != '') ? $input_data['projects_per_page'] : 10),
						'fb_link' => ((isset($input_data['fb_link']) && $input_data['fb_link'] != '') ? $input_data['fb_link'] : ''),
						'flickr_link' => ((isset($input_data['flickr_link']) && $input_data['flickr_link'] != '') ? $input_data['flickr_link'] : ''),
						'insta_link' => ((isset($input_data['insta_link']) && $input_data['insta_link'] != '') ? $input_data['insta_link'] : ''),
						'twitter_link' =>((isset($input_data['twitter_link']) && $input_data['twitter_link'] != '') ? $input_data['twitter_link'] : ''),
						'gplus_link' => ((isset($input_data['gplus_link']) && $input_data['gplus_link'] != '') ? $input_data['gplus_link'] : ''),
						'linkedin_link' =>((isset($input_data['linkedin_link']) && $input_data['linkedin_link'] != '') ? $input_data['linkedin_link'] : ''),
						'meta_data' => ((isset($input_data['meta_data']) && $input_data['meta_data'] != '') ? $input_data['meta_data'] : ''),
						'meta_keywords' => ((isset($input_data['meta_keywords']) && $input_data['meta_keywords'] != '') ? $input_data['meta_keywords'] : ''),
						'title' => ((isset($input_data['title']) && $input_data['title'] != '') ? $input_data['title'] : ''),
						'copyright_text' => ((isset($input_data['copyright_text']) && $input_data['copyright_text'] != '') ? $input_data['copyright_text'] : ''),
						'image_watermark' => ((isset($input_data['image_watermark']) && $input_data['image_watermark'] != '') ? $input_data['image_watermark'] : ''),
						'post_article_text' => ((isset($input_data['post_article_text']) && $input_data['post_article_text'] != '') ? $input_data['post_article_text'] : ''),
						'suscribe_no_days' => ((isset($input_data['suscribe_no_days']) && $input_data['suscribe_no_days'] != '') ? $input_data['suscribe_no_days'] : ''),
						'telephone' => ((isset($input_data['tele']) && $input_data['tele'] != '') ? $input_data['tele'] : ''),
						'cemail' => ((isset($input_data['cemail']) && $input_data['cemail'] != '') ? $input_data['cemail'] : ''),
						'address' => ((isset($input_data['address']) && $input_data['address'] != '') ? $input_data['address'] : ''),
						'latLong' => $lat.','.$long,
						'fb_app_id' => ((isset($input_data['fb_app_id']) && $input_data['fb_app_id'] != '') ? $input_data['fb_app_id'] : ''),
						'itunes_text' => ((isset($input_data['itunes_text']) && $input_data['itunes_text'] != '') ? $input_data['itunes_text'] : '')
					);
					
					if($img_name != ''){
						$input_data1['site_logo'] = $img_name;
					}
					try{
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
					}catch(Exception $e){
						echo 'Message: ' .$e->getMessage();
					}
					
					$this->session->set_userdata('setting_sucess','General settings updated sucessfully.');
					redirect(base_url('general-settings'));
				}
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

	public function ratingList(){
		if($this->session->userdata('admuname') != ''){
			$this->db->from('com_category1');
			$this->db->where('parentID',0);
			$query = $this->db->get();
			$catArray = $query->result_array();
			$this->data['categories'] = $catArray;
			if(!empty($catArray)){
				$this->data['categories'] = $catArray;
				foreach($catArray as $cat){
					if($cat['type'] == 'VID'){
						$this->db->from('com_category1');
						$this->db->where('parentID',$cat['id']);
						$query = $this->db->get();
						$subCatArray = $query->result_array();
						$this->data['subCat'] = $subCatArray;
					}
				}
			}
			
			$this->load->model('projectmodel');
			$this->data['isTop'] = $this->projectmodel->isInTopData();
			$this->data['isTopYear'] = $this->projectmodel->isInTopYearData();
			$this->data['isTopMonth'] = $this->projectmodel->isInTopMonthData();

			$this->db->select('hall_of_fame_short');
			$this->db->from('com_general_settings');
			$query = $this->db->get();
			$this->data['shortBy'] = $query->row()->hall_of_fame_short;
			//print_r($this->data['shortBy']);die;
			$this->load->view('rating/rating',$this->data);
		}else{
			redirect(base_url('login'));
		}
		
	}
	
	public function hallFame(){
		if($this->session->userdata('admuname') != ''){
			// $input_data = $this->input->post();
			// print_r($input_data);die;
			$this->load-> model('projectmodel'); 
			$list = $this->projectmodel->setCatDataHF();
			$this->db->from('com_main_post_article');
			$this->db->where('id',$list);
			$query = $this->db->get();
			$CatArray = $query->result_array();
			foreach($CatArray as $data){
				if($data['subCategoryID'] != 116){
					$Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $data['id'],"fieldType" => 'Photo'));
				}else{
					$Thumb = $this->defaultdata->grabMetaPostsSeries(array('postID' => $data['id'],"fieldType" => 'Photo'));
				}
				$path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
				if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/resize/400/'.$Thumb->slugvalue)){
					$path = base_url('/../assets/upload/resize/400/');
					$path .= str_replace(" ","%20",$Thumb->slugvalue);
				} else if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/all_post/'.$Thumb->slugvalue)){
					$path = '../'.META_ARTICLE_UPLOAD_PATH;
					$path .= $Thumb->slugvalue;
				}
				$selectedid = $data['id'];
			}
			echo json_encode(array('status'=>$list,'path'=>$path,'selectedid'=>$selectedid));die;
		}else{
			redirect(base_url('login'));
		}
	}
	public function saveRating(){
		if($this->session->userdata('admuname') != ''){
			$input_data = $this->input->post();
			$table = 'com_general_settings';
			if(!empty($input_data)){
				$input_data = array(
					'hall_of_fame_short' => $input_data['shortBy'],
					// 'hall_of_fame_year' => $input_data['shortByYear'],
					// 'hall_of_fame_month' => $input_data['shortByMonth'],
				);
				$this->db->set($input_data);
				if($this->db->update($table)){
					echo json_encode(array('status'=>1));die;
				}else{
					echo json_encode(array('status'=>0));die;
				}
			}else{
				echo json_encode(array('status'=>0));die;
			}
		}else{
			echo json_encode(array('status'=>0));die;
		}
	}

	public function getChangeData(){
		if($this->session->userdata('admuname') != ''){
			$this->db->from('com_category1');
			$this->db->where('parentID',0);
			$query = $this->db->get();
			$catArray = $query->result_array();
			//$this->data['categories'] = $catArray;
			if(!empty($catArray)){
				$this->data['categories'] = $catArray;
				foreach($catArray as $cat){
					if($cat['type'] == 'VID'){
						$this->db->from('com_category1');
						$this->db->where('parentID',$cat['id']);
						$query = $this->db->get();
						$subCatArray = $query->result_array();
						//$this->data['subCat'] = $subCatArray;
					}
				}
			}
			$input_data = $this->input->post();
			//print_r($input_data);die;
			$this->load-> model('projectmodel'); 
			if($input_data['type'] == 'Y'){
				$isTopYear = $this->projectmodel->isInTopYearData();
				$type = 'year';
			}else if($input_data['type'] == 'M'){
				$isTopYear = $this->projectmodel->isInTopMonthData();
				$type = 'month';
			}
			$html = '';
			$i = 1;
			$catsArray = array_merge($subCatArray,$catArray);
			foreach($catsArray as $cat){ 
				$data_id = 'data-id';
				if($cat['type'] == 'VID' && $cat['parentID'] == 0) continue;
				if($cat['type'] == 'VID' && $i == 1){
					$data_id = 'data-subid';
				}
				else if ($cat['type'] == 'VID' && $i == 2){
					$data_id = 'data-subid';
				}
				$isPostId = '';
				foreach($isTopYear as $isPost){
					if($cat['id'] == $isPost['category_id']){
						$isPostId = $isPost['post_id'];
						continue;
					}
					// if($cat['id'] == $isPost['categoryID']){
					//     $isPostId = $isPost['id'];
					//     continue;
					// }
				}
				$path = '';
				
				if($isPostId != ''){
					//$Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
					if($cat['series'] == 'N'){
						$Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
					}else{
						$Thumb = $this->defaultdata->grabMetaPostsSeries(array('postID' => $isPostId,"fieldType" => 'Photo'));
					}
					$path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
					if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/all_post/'.$Thumb->slugvalue)){
						$path = '../'.META_ARTICLE_UPLOAD_PATH;
						$path .= str_replace(" ","%20",$Thumb->slugvalue);
					}
					// $path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
					// if(file_exists(META_ARTICLE_UPLOAD_PATH_WEB.'/'.$Thumb->slugvalue)  && trim($Thumb->slugvalue) != ''){
					// 	$path = '../'.META_ARTICLE_FETCH_PATH_WEB.$Thumb->slugvalue;
					// }
					$btnText = 'Change '.$cat['title'];
				}else{
					$btnText = 'Select '.$cat['title'];
				}
				$html .= '<div class="BEST-PROJECTS-BOX">
					<div class="bes-project-x_panel" id="'.$type.$cat['id'].'" style="background-image:url('.$path.');">
						<h4 class="no-underline hall-of-heading">'.$cat['title'].'</h4>
						<button type="button" class="btn btn-primary catId" id="btn'.$cat['id'].'" data-boxid="'.$type.$cat['id'].'" '.$data_id.'="'.$cat['id'].'" data-selectedId = "'.$isPostId.'" data-type="'.$type.'" data-modalName = "'.$cat['title'].'"> '.$btnText.'</button>
					</div>
				</div>';
				$i++;
			} 
			if($html != ''){
				echo json_encode(array('status'=>1,'html'=>$html,'type'=>$input_data['type']));die;
			}else{
				echo json_encode(array('status'=>0,'msg'=>'Something went wrong.'));die;
			}
		}else{
			redirect(base_url('login'));
		}
		//$this->load->view('rating/rating',$this->data);
	}

	// error log
	public function errorLog(){
		if($this->session->userdata('admuname') != ''){
			$query = $this->db->get('com_error_log');
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $j=>$r){
					foreach($r as $k=>$a){
						if(trim($a) == '') $data[$j][] = '---';
						else $data[$j][] = $a;
					}
				}
			}
			$this->data['data'] = $data;
			$this->load->view('cms/error_log',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function clearErrorLog(){
		if($this->session->userdata('admuname') != ''){
			$this->db->empty_table('com_error_log');
			echo json_encode(array('status'=>1));die;
		} else {
			echo json_encode(array('status'=>0));die;
		}
	}

	public function subscribe_newsletter_list(){
		if($this->session->userdata('admuname') != ''){
			$this->db->from("com_user_subscribe");
			$this->db->order_by("id",'desc');
			$query = $this->db->get();
			$result = $query->result_array();
			$datag = array();
			if(!empty($result)){
				foreach($result as $j=>$r){
					$r['action'] = 1;
					$id = $r['id'];
					foreach($r as $k=>$a){
						if($k=="id"){
							$datag[$j][] = $a ;
						}else if($k=="user_id"){
							$this->db->from("com_user");
							$this->db->where("id",$a);
							$query = $this->db->get();
							$results = $query->row();
							if(!empty($results)){
								$datag[$j][] = $results->firstName." ".$results->lastName;
							}else{
								$datag[$j][]  = '-';
							}
						}else if($k=="user_email"){
							$datag[$j][] = $a;
						}else if($k=="date"){
							$date = new DateTime($a);
							$datag[$j][] = $date->format('d/m/Y');
						}else if($k=="action"){
							$datag[$j][] = '<a href="'.base_url("subscribe-newsletter-delete/id/").$id.'" class="delete-item-data" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
						}
					}
				}
			}
			$this->data['data'] = $datag;
			$this->load->view('subscribe/subscribe_list',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function subscribe_newsletter_new_mail(){
		if($this->session->userdata('admuname') != ''){
			$this->load->view('subscribe/new_email',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}

	public function subscribe_newsletter_delete($id = 0){
		if($this->session->userdata('admuname') != ''){
			if($id != 0 && $id != ""){
				$this->db->where("id",$id);
				$this->db->delete("com_user_subscribe");
				echo json_encode(array('status'=>true,'message'=>'Subscribe user deleted successfully'));die;
			} else {
				echo json_encode(array('status'=>false,'message'=>'Parameter not found..'));die;	
			}
		} else {
			echo json_encode(array('status'=>false,'message'=>'Your session expired.'));die;
		}
	}

	public function subscribe_newsletter_send_mail(){
		if($this->session->userdata('admuname') != ''){
			$input_data = $this->input->post();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('emailSubject', 'Subject', 'trim|required|xss_clean');
			$this->form_validation->set_rules('EmailBody', 'Email Body', 'trim|required|xss_clean');
			if($this->form_validation->run() == FALSE) {
				$this->session->set_userdata('sub_new_email_error',validation_errors());
				$this->session->set_userdata('input_data',$input_data);
			} else {
				$this->db->from("com_user_subscribe");
				$query = $this->db->get();
				$result = $query->result_array();

				if(!empty($result)){
					foreach($result as $j=>$r){
						$bytes = openssl_random_pseudo_bytes(30, $cstrong);
						$encydata = $upd_data['key'] = bin2hex($bytes);
						$this->db->where('id',$r['id']);
						$this->db->update('com_user_subscribe',$upd_data);
						$this->subscribeSendMail($r, $encydata, $input_data);
					}
					$this->session->set_userdata('sub_new_email_success',"Email send.");die;
				} else {
					$this->session->set_userdata('sub_new_email_error',"No Sunscribe user found.");
				}
			}
			$this->load->view('subscribe/new_email',$this->data);
		} else {
			redirect(base_url('/'));
		}
	}


	public function subscribeSendMail($user_data, $encydata, $mail_data){
		$this->db->select("firstName, lastName");
		$this->db->from("com_user");
		$this->db->where("id",$user_data['user_id']);
		$query = $this->db->get();
		$userData = $query->row();
		$u_name = 'Webllywood user';
		if(!empty($userData)){
			$u_name = $userData->firstName." ".$userData->lastName;
		}
		$unsub_link = ROOT_URL_WEB."unsubscribe-newsletter/".$encydata;
		$mailcontent=htmlspecialchars_decode($mail_data['EmailBody']);
		$mailcontent=str_replace('{USER_NAME}',$u_name,$mailcontent);
		$mailcontent=str_replace('{SITE_URL}',ROOT_URL_WEB,$mailcontent);
		$mailcontent=str_replace('{UNSUBSCRIBE_LINK}',$unsub_link,$mailcontent);
		$mailcontent=str_replace('{SITE_TITLE}',$this->data['general_settings']->SiteTitle,$mailcontent);
		$mailcontent=str_replace('{SITE_LOGO}',DEFAULT_ASSETS_URL.'images/'.$this->data['site_logo'],$mailcontent);

		$to=$user_data['user_email'];		
		$subject = $mail_data['emailSubject'];
		$message ="<html><head></head><body>".$mailcontent."</body><html>";

		$this->load->library('email');
		$config = array();
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail -t -i';
		$config['charset'] = 'iso-8859-1';
		$config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;
		$config['charset'] = 'utf-8';
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$this->email->from($this->data['general_settings']->adminEmailAddress, 'Webllywood');
		$this->email->to($to); 
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}
}

/* End of file frontend.php */
/* Location: ./application/controllers/frontend.php */