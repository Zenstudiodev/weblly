<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public $data=array();
	private $table = TABLE_FRONTUSER;
	// public $loggedin_method_arr = array();
	
	function __construct() {
		parent::__construct();
		$this->load->model('userdata');
		$this->data=$this->defaultdata->getFrontendDefaultData();
		// if(array_search($this->data['tot_segments'][2],$this->loggedin_method_arr) !== false) {
		// 	if($this->defaultdata->is_session_active() == 0) {
		// 		redirect(base_url());
		// 	}
		// }
		if($this->defaultdata->is_session_active() == 1) {
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}
		if($this->session->userdata('admuname') == ''){
			redirect(base_url('login'));
		}
	}

	public function listUser($type = ''){
		if($type != "" && ($type == 'awaiting' || $type == 'active' || $type == 'block')){
			$this->db->select('id, userName, emailAddress, userType, postedtime');
			$this->db->order_by('postedtime','DESC');
			if ($type == 'awaiting') {
				$connpage = "E";
				$user_cond = array('status' => 'E');
			} else if ($type == 'active') {
				$connpage = "Y";
				$user_cond = array('status' => 'Y');
			} else if ($type == 'live_tasker') {
				$connpage = "Y";
				$user_cond = array('status' => 'Y');
			} else if ($type == 'block') {
				$connpage = "N";
				$user_cond = array('status' => 'N');
			} else if ($type == 'verify') {
				$connpage = "V";
				$user_cond = array('verify_identity' => 'N');
			}
			$query = $this->db->get_where($this->table,$user_cond);
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $j=>$r){
					foreach($r as $k=>$a){
						if($k == 'postedtime'){
							$a = date('Y/m/d H:i', $a);
						} else if($k == 'userType'){
							if($a == 1){
								$a = '<span style="font-size:20px;"><i class="fa fa-user"></i></span>';
							} else if($a == 2) {
								$a = '<span style="font-size:20px;"><i class="fa fa-facebook-square"></i></span>';
							} elseif($a == 3) {
								$a = '<span style="font-size:20px;"><i class="fa fa-google-plus"></i></span>';
							} elseif($a == 4) {
								$a = '<span style="font-size:20px;"><i class="fa fa-linkedin-square"></i></span>';
							}
						}
						$data[$j][] = $a;
					}
				}
				usort($data, function($a1, $a2) {
					$v1 = strtotime($a1[4]);
					$v2 = strtotime($a2[4]);
					return $v1 - $v2; // $v2 - $v1 to reverse direction
				});	
			}
			$this->data['data'] = $data;
			$this->load->view('user/user_list',$this->data);
		}else{
			redirect(base_url('/'));
		}
	}

	public function edit($id = 0){
		if($id != 0 && $id != ""){
			$condi = array('ID' => $id);
			$this->db->select('id, userName, firstName, lastName,  emailAddress, address1, phone, userType, status');
			$query = $this->db->get_where($this->table, $condi);
			$this->data['data'] = $query->row();
			$this->load->view('user/useredit',$this->data);
		} else {
			redirect(base_url('user/active'));
		}
	}

	public function changePriceResp(){
		if(isset($_POST['value']) && $_POST['value'] != ''){		
			$this->db->select("*");
			$this->db->from("com_pricing");
			$this->db->where('plan_status', 'Y');
			$query = $this->db->get();        
			$Plans = $query->result_array();
			$html = $a = $b=  "";
			$htmlArray = array();
			$price = "";
			if(!empty($Plans)){
				foreach($Plans as $k=>$p){				
					$Data = explode(',',$p['plan_features']);	
					if($_POST['value'] == "true"){
						$price= "$ ".$p['plan_price_year'];
						$a = '/YR';
						$b = 'yearly';
					}else{
						$price= "$ ".$p['plan_price_month'];
						$a = '/MO';
						$b = 'monthly';
					}
					$html ='<div class="price-top"><span>'.$p['plan_name'].'</span><span>'.$price.'<span class="mon-yer">'.$a.'</span></span><span>Billed '.$b.'</span></div>';
					$htmlArray[$k] = $html;
				}	
				$resp['html'] = $htmlArray;
				$resp['status'] = true;	
				echo json_encode($resp);die;
			}				
		}
		//print_r($_POST);die;
	}

	public function userDetail($id = 0){
		if($id != 0 && $id != ""){
			$condi = array('ID' => $id);
			$query = $this->db->get_where($this->table, $condi);
			$this->data['user_data'] = $user_data = $query->row_array();
			if(!empty($this->data['user_data'])){
				$article_cond = array('user_id' => $id);
				$this->data['user_data']['tot_article'] = $this->db->get_where(TABLE_MAIN_POST_ARTICLE, $article_cond)->num_rows();
			}
			unset($this->data['user_data']['userPassword']);
			unset($this->data['user_data']['stripe_resp']);
			$this->data['plan_data'] = (array) $this->userdata->getUserPlansFullDetails($id);
			
			$this->load->view('user/user_detail',$this->data);
		} else {
			redirect(base_url('user/active'));
		}
	}

	public function editProcess($id = 0){
		$input_data = $this->input->post();
		if(!isset($id) || $id == 0 || $id == ""){
			redirect(base_url('/user/active'));
		} else if(empty($input_data) || !isset($input_data['emailAddress']) || $input_data['emailAddress'] == ''){
			$this->session->set_userdata('user_error','Please Enter required filds.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('user-edit/id/'.$id));
		} else {
			$condi = array('id' => $id);
			$this->db->select('id, userName,hastoken,stripe_id, firstName, lastName,  emailAddress, userType, status');
			$query = $this->db->get_where($this->table, $condi);
			$data = $query->row();
			if(!empty($data) && $data->stripe_id == ''){
				if($data->status="E"){
					$this->userdata->createStripeUser($data);
				}
			}
			//if($input_data['status'] == 'Y'){}
			$input_data = array(
				'userName' => (isset($input_data['userName']) ? $input_data['userName'] : ''),
				'firstName' => (isset($input_data['firstName']) ? $input_data['firstName']  : ''),
				'lastName' => (isset($input_data['lastName']) ? $input_data['lastName'] : ''),
				'emailAddress' => $input_data['emailAddress'],
				'address1' => (isset($input_data['address1']) ? $input_data['address1'] : ''),
				'phone' => (isset($input_data['phone']) ? $input_data['phone'] : ''),
				'status' => (isset($input_data['status']) ? $input_data['status'] : ''),
			);
			$this->db->set($input_data);
			$this->db->where('id', $id);
			$this->db->update($this->table);
			if(isset($input_data['status']) && ( $input_data['status'] == 'E' || $input_data['status'] == 'Y')){
				$this->userdata->deleteUserAllToken(array('user_id' => $id));
			}
			$this->session->set_userdata('user_sucess','User updated sucessfully.');
			redirect(base_url('user-edit/id/'.$id));
		}
	}

	public function delete($id = 0){
		if($id != 0 && $id != ""){
			$user_data = $this->db->where('id', $id)->get('com_user')->row_array();
			$this->db->where('id', $id);
			$this->db->delete($this->table);
			if($this->db->affected_rows() > 0){
				$this->clearUserAllData($id, $user_data);
				echo json_encode(array('status'=>true));die;
			} else {
				echo json_encode(array('status'=>false,'message'=> 'Something went wrong. No user Found.'));die;
			}
		}else{
			echo json_encode(array('status'=>false,'message'=>'Parameter not found.'));die;
		}
	}

	public function clearUserAllData($uid = 0, $user_data = []){
		if($uid != 0 && $uid != ''){
			$cond = array('user_id' => $uid);

			// com_advertisement_with_us - user_id
			$this->db->where($cond);
			$this->db->delete('com_advertisement_with_us');

			// com_article_comment - user_id
			$this->db->where($cond);
			$this->db->delete('com_article_comment');

			// com_article_rating - user_id
			$this->db->where($cond);
			$this->db->delete('com_article_rating');

			// com_article_views - user_id
			$this->db->where($cond);
			$this->db->delete('com_article_views');

			// com_likes - user_id
			$this->db->where($cond);
			$this->db->delete('com_likes');

			// com_play - user_id
			$this->db->where($cond);
			$this->db->delete('com_play');

			// com_post_lyrics - user_id
			$this->db->where($cond);
			$this->db->delete('com_post_lyrics');

			// com_userlogin - user_id
			$this->db->where('uid', $uid);
			$this->db->delete('com_userlogin');

			// com_user_fav - user_id
			$this->db->where($cond);
			$this->db->delete('com_user_fav');

			// com_user_profession - user_id
			$this->db->where($cond);
			$this->db->delete('com_user_profession');

			// com_user_search_history - user_id
			$this->db->where($cond);
			$this->db->delete('com_user_search_history');

			// token - user_id
			$this->db->where($cond);
			$this->db->delete('token');

			// com_user_card_details - user_id
			$this->db->where($cond);
			$this->db->delete('com_user_card_details');

			// com_playlist - user_id
			$this->db->where($cond);
			$user_playlist = $this->db->get('com_playlist')->result_array();
			if(!empty($user_playlist)){
				foreach($user_playlist as $play_data){
					//com_playlist_item - (join with com_playlist)
					$this->db->where('playlist_id' , $play_data['id']);
					$this->db->delete('com_playlist_item');
				}
				$this->db->where($cond);
				$this->db->delete('com_playlist');
			}

			// com_user_plan (just deactive) - user_id
			$current_plan = $this->db->where(array('user_id' => $uid,'status'=>'Y', 'current_status' => 'A'))->get('com_user_plan')->result_array();
			if(!empty($current_plan)){
				foreach($current_plan as $pdata){
					if(!empty($pdata)){
						if($pdata['payment_type'] == 'P'){
							$user_paypal_profile_id = $pdata['stripe_subscription_id'];
							require_once ('../'.APPPATH .'libraries/paypal-digital/functions.php');
							$paypal = create_example_subscription();
							$paypal->manage_subscription_status( $user_paypal_profile_id, 'Cancel', 'Cancelled subscription via webllywood ADMIN.');
						}
						$subscription_id = $pdata['stripe_subscription_id'];
						$up_data = array('status'=>'N','current_status'=>'C');
						$this->db->where(array('user_id'=>$uid,'status'=>'Y','current_status'=>'A'));
						$this->db->update('com_user_plan', $up_data);
						$this->sendMailHook('subscription-cancel',$user_data,$subscription_id);
					}
				}
			}
			if($user_data['stripe_id'] != ''){
				try{
					$stripe_api_key = $this->db->select('stripe_api_key')->from(TABLE_GENERAL_SETTINGS)->get()->row()->stripe_api_key;
					require ('../'.APPPATH .'libraries/stripe/init.php');
					\Stripe\Stripe::setApiKey($stripe_api_key);
					$cu = \Stripe\Customer::retrieve($user_data['stripe_id']);
					$cu->delete();
				}catch(Exception $e){}
			}

			$this->db->where($cond);
			$data = array('status'=>'N', 'current_status' => 'C');
			$this->db->update('com_user_plan', $data);

			// user posted article
			$this->db->select('id');
			$this->db->where($cond);
			$user_article_list = $this->db->get('com_main_post_article')->result_array();
			if(!empty($user_article_list)){
				foreach($user_article_list as $article_list){
					$id = $article_list['id'];
					$cond = array('id'=>$id);
					$cate_id = $this->defaultdata->getCatIdOnArticleId($cond);
					
					$condition_data = array('pid'=> $cate_id);
					$catattributes_data = $this->defaultdata->getCategoryAttr($condition_data);
					
					$dat = $this->defaultdata->grabMainArticle($cond);
					
					$post_meta = $this->userdata->getPostMeta(array('postID' => $dat->id));
					
					if(empty($post_meta)){
						$post_meta_series = $this->userdata->getPostMetaSeries(array('postID' => $dat->id));
						foreach($post_meta_series as $singleSeries){
							if(file_exists(DEFAULT_MAIN_ASSETS_URL.$singleSeries->slugvalue) || file_exists(getcwd().'/assets/upload/all_post/'.$singleSeries->slugvalue)){
								unlink(DEFAULT_MAIN_ASSETS_URL.$singleSeries->slugvalue);
								unlink(SECURED_POST_FILES.$singleSeries->slugvalue);
							}
							if($singleSeries->fieldType == 'SubTitle'){
								$sub_title_series = $this->userdata->grabPostMetaVideoSeriesSubtitles(array('postmetaSeriesID' => $singleSeries->id));
								foreach ($sub_title_series as $item) {
									if(file_exists(DEFAULT_MAIN_ASSETS_URL.$item->subtitleFile) || file_exists(getcwd().'/assets/upload/all_post/'.$item->subtitleFile)){
										unlink(DEFAULT_MAIN_ASSETS_URL.$item->subtitleFile);
									}
								}
								$this->userdata->deletePostMetaVideoSeriesSubtitles(array('postmetaSeriesID' => $singleSeries->id));
							}
						}
						$this->userdata->deletePostmetaSeries(array('postID' => $dat->id));
					} else {
						if(!empty($post_meta)){
							foreach($post_meta as $singleSeries) {
								if(file_exists(DEFAULT_MAIN_ASSETS_URL.$singleSeries->slugvalue) || file_exists(getcwd().'/assets/upload/all_post/'.$singleSeries->slugvalue)){
									if($singleSeries->slugvalue != ''){
										unlink(DEFAULT_MAIN_ASSETS_URL.$singleSeries->slugvalue);
										unlink(SECURED_POST_FILES.$singleSeries->slugvalue);
										unlink(DEFAULT_MAIN_ASSETS_URL.$singleSeries->slugvalue);
										unlink(SECURED_POST_FILES.$singleSeries->slugvalue);
									}						
								}
								if($singleSeries->fieldType == 'SubTitle'){
									$sub_title_series = $this->userdata->grabPostMetaVideoSubtitles(array('postID' => $dat->id));
									foreach ($sub_title_series as $item) {
										if(file_exists(DEFAULT_MAIN_ASSETS_URL.$item->subtitleFile) || file_exists(getcwd().'/assets/upload/all_post/'.$item->subtitleFile)){
											if($item->subtitleFile != ''){
												unlink(DEFAULT_MAIN_ASSETS_URL.$item->subtitleFile);
											}								
										}
									}
									$this->userdata->deletePostMetaVideoSubtitles(array('postID' => $dat->id));
								}
							}
							$this->userdata->deletePostMeta(array('postID' => $dat->id));
						}            
					}
					$this->defaultdata->deletePrePostArticles($cond, $catattributes_data);
				}
				$this->db->where($cond);
				$this->db->delete('com_main_post_article');
			}
		}
		return 1;
	}

	public function sendMailHook($key,$userData,$subscribeID){
		if(!empty($userData)){
			$userData = (object) $userData;
			$mail_data = $this->userdata->getEmailTemplate($key);
			$mailcontent = htmlspecialchars_decode($mail_data->description);
			$mailcontent = str_replace('{USER_NAME}',$userData->firstName." ".$userData->lastName,$mailcontent);
			$mailcontent = str_replace('{SITE_URL}',base_url(),$mailcontent);
			$mailcontent = str_replace('{SUB_ID}',$subscribeID,$mailcontent);
			$mailcontent = str_replace('{SITE_TITLE}',$this->data['general_settings']->SiteTitle,$mailcontent);
	  
			$to = $userData->emailAddress;
			$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email.">\n"; 
	  
			$subject = $mail_data->emailTitle;
			$message = "<html><head></head><body>".$mailcontent."</body><html>";
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

	public function adminUser(){
		if($this->session->userdata('admuname') != ''){
			$getAllAdminUser = $this->userdata->getAdmins();
			foreach($getAllAdminUser as $j=>$r){
				foreach($r as $k=>$a){
					$datag[$j][] = $a;
				}
			}
			$this->data['data'] = $datag;
			$this->load->view('user/admins_list',$this->data);
		}else{
			redirect(base_url('login'));
		}
	}
	
	public function adminUserAddPage(){
		if($this->session->userdata('admuname') != ''){
			$this->load->view('user/newadmin',$this->data);
		}else{
			redirect(base_url('login'));
		}
	}

	public function adminUserAdd(){
		if($this->session->userdata('admuname') != ''){
			$input_data = $this->input->post();
			if( (empty($input_data) || !isset($input_data['admin_userName']) || $input_data['admin_userName'] == '') || (!isset($input_data['admin_password']) || $input_data['admin_password'] == '') || (!isset($input_data['name']) || $input_data['name'] == '') ){
				$this->session->set_userdata('user_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('addadmin'));
			} else {
				$input_data = array(
					'name' => (isset($input_data['name']) ? $input_data['name'] : ''),
					'admin_userName' => (isset($input_data['admin_userName']) ? $input_data['admin_userName']  : ''),
					'admin_password' => (isset($input_data['admin_password']) ? $input_data['admin_password'] : ''),
				);
				$this->db->insert('com_adminuser',$input_data);
				$this->session->set_userdata('user_sucess','User updated sucessfully.');
				redirect(base_url('admins'));
			}
		}else{
			redirect(base_url('login'));
		}
	}

	public function adminUserEdit($id = 0){
		if($this->session->userdata('admuname') != ''){
			if($id != 0 && $id != ""){
				$condi = array('id' => $id);
				$this->db->select('id, name, admin_userName, admin_password');
				$query = $this->db->get_where('com_adminuser', $condi);
				$this->data['data'] = $query->row();
				$this->load->view('user/adminuseredit',$this->data);
			} else {
				redirect(base_url('user/adminUser'));
			}
		}else{
			redirect(base_url('login'));
		}
	}
	public function adminEditProcess($id = 0){
		if($this->session->userdata('admuname') != ''){
			$input_data = $this->input->post();
			if(!isset($id) || $id == 0 || $id == ""){
				redirect(base_url('/user/adminUser'));
			} else if( (empty($input_data) || !isset($input_data['admin_userName']) || $input_data['admin_userName'] == '') || (!isset($input_data['admin_password']) || $input_data['admin_password'] == '') ){
				$this->session->set_userdata('user_error','Please Enter required filds.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('adminUser-edit/id/'.$id));
			} else {
				$input_data = array(
					'name' => (isset($input_data['name']) ? $input_data['name'] : ''),
					'admin_userName' => (isset($input_data['admin_userName']) ? $input_data['admin_userName']  : ''),
					'admin_password' => (isset($input_data['admin_password']) ? $input_data['admin_password'] : ''),
				);
				$this->db->set($input_data);
				$this->db->where('id', $id);
				$this->db->update('com_adminuser');
				$this->session->set_userdata('user_sucess','User updated sucessfully.');
				redirect(base_url('adminUser-edit/id/'.$id));
			}
		}else{
			redirect(base_url('login'));
		}
	}

	public function admindelete($id = 0){
		if($this->session->userdata('admuname') != ''){
			if($id != 0 && $id != ""){
				$this->db->where('id', $id);
				$this->db->delete('com_adminuser');
				if($this->db->affected_rows() > 0){
					echo json_encode(array('status'=>true));die;
				} else {
					echo json_encode(array('status'=>false,'message'=> 'Something went wrong.'));die;
				}
			}else{
				echo json_encode(array('status'=>false,'message'=>'Parameter not found.'));die;
			}
		}else{
			redirect(base_url('login'));
		}
	}

	
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */