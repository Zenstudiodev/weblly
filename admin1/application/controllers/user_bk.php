<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public $data=array();
	public $loggedin_method_arr = array();
	public $controller_arr = array('user','frontend','fbcontroller','gpluscontroller','routemanager','ajax','admin');
	function __construct()
	{
		parent::__construct();
	
		$this->load->model('userdata');
		$this->data=$this->defaultdata->getFrontendDefaultData();
		
		if(array_search($this->data['tot_segments'][2],$this->loggedin_method_arr) !== false)
		{
			if($this->defaultdata->is_session_active() == 0)
			{
				
				redirect(base_url());
			}
		}
		if($this->defaultdata->is_session_active() == 1)
		{
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}
	}
	public function register()
	{
		
		if($this->session->userdata('usrid') != '')
		{
			redirect(base_url('my-account'));
		}
		else
		{
			$this->data['hello'] = "hello";
			$this->data['profession'] = $this->userdata->getProfessions();
			$this->load->view('register',$this->data);
		}
	}
	public function registerProcess()
	{
		$input_data = $this->input->post();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('firstName', 'First Name', 'trim|required|alpha|xss_clean');
		$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|alpha|xss_clean');
		$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|is_unique['.TABLE_USER.'.emailAddress]|xss_clean');
		$this->form_validation->set_message('valid_email', 'Please enter valid email.');
		$this->form_validation->set_message('is_unique', 'This email is already registered.');
		
		$this->form_validation->set_rules('userPassword', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('re_password', 'Repeat Password', 'trim|required|matches[userPassword]|xss_clean');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
		$this->form_validation->set_rules('address1', 'Address1 ', 'trim|required|xss_clean');
		$this->form_validation->set_rules('address2', 'Address', 'trim|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
		$this->form_validation->set_rules('likeTallentHunter', '', 'trim|xss_clean');
		
		// if (empty($input_data['g-recaptcha-response']))
		// {
		// 	$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|xss_clean');
		// }
		
		$this->session->unset_userdata($input_data);

		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('register_error',validation_errors());
			$this->session->set_userdata('reg_data',$input_data);
		}
		else
		{
			unset($input_data['re_password']);
			//unset($input_data['g-recaptcha-response']);
			//$user_skills = $input_data['user_skills'];
			//unset($input_data['user_skills']);
			$profession = array();
			$profession = $input_data['profession'];
			$input_data = $this->defaultdata->secureInput($input_data);
			unset($input_data['profession']);
			$input_data['userPassword'] = md5($input_data['userPassword']);
			$input_data['dob'] = strtotime($input_data['dob']);
			$input_data['status'] = 'E';
			$input_data['postedtime'] = time();
			$input_data['userType'] = 1;
			$input_data['ipaddress'] = $_SERVER["REMOTE_ADDR"];
			

			$user_id = $this->userdata->insertUser($input_data);
			

			
			if($user_id != 0)
			{
				if(count($profession) > 0)
				foreach($profession as $single_profession)
				{
					$profession_input = array();
					$profession_input['profession_id'] = $single_profession;
					$profession_input['user_id'] = $user_id;	
					$this->userdata->insertProfession($profession_input);
				}
				
				$this->session->set_userdata('register_success','Registration successfully complete, Please check your email to activate.');
				
				$encydata=encrypt($user_id,SALT);
				
				$mail_data = $this->userdata->getActivationEmailTemplate();
				$activation_link=base_url()."user/activation/b674b2f8e615753f1fd54406349d37".$encydata;
				
				$mailcontent=htmlspecialchars_decode($mail_data->description);
				$mailcontent=str_replace('{USER_NAME}',$input_data['firstName']." ".$input_data['lastName'],$mailcontent);
				$mailcontent=str_replace('{SITE_URL}',base_url(),$mailcontent);
				$mailcontent=str_replace('{REG_LINK}',"<a href='".$activation_link."'>Active</a>",$mailcontent);
				$mailcontent=str_replace('{SITE_TITLE}',$this->data['general_settings']->SiteTitle,$mailcontent);
				
				$to=$input_data['emailAddress'];
				$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email.">\n"; 
				$headers .= "MIME-Version: 1.0\n"; 
				$headers .= "Content-type: text/html; charset=UTF-8\n"; 
				$subject = $mail_data->emailTitle;
				$message ="<html><head></head><body>"."<style type=\"text/css\">
				<!--
				.style4 {font-size: x-small}
				-->
				</style>
				".$mailcontent."
				</body><html>"; 
				@mail($to,$subject, $message,$headers);
			}
		}
		redirect(base_url('register'));
	}
	public function activation($str)
	{
		
		$data=$str;
		$encydata=substr($data,30);
		$uid=decrypt($encydata,SALT);
		
		$cond = array('id' => $uid);
		$user_data = $this->userdata->grabUserData($cond);

		if($user_data->status == 'E')
		{
			$this->userdata->saveLoginLog($user_data->id);
			$this->defaultdata->setLoginSession($user_data);
			
			$update_data = array('status' => 'Y');
			$condition = array('id' => $uid);
			$this->userdata->updateUser($update_data,$condition);
			redirect(base_url('my-account'));
		}
		else
		{
			redirect(base_url());
		}
	}
	public function login()
	{
		if($this->session->userdata('usrid') != '')
		{
			redirect(base_url('user/my-account'));
		}
		else
		{
	        $this->data['gplus_url'] = $this->defaultdata->getGplusLoginUrl();
	        $this->load->view('login',$this->data);
		}
	}
	
	public function loginProcess()
	{
		$login_data=array();
		$input_data = $this->input->post();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('emailAddress', 'Email Address', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('userPassword', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('rememberPassword', 'Password', 'trim|xss_clean');
		if($this->form_validation->run() == FALSE)
		{
			echo json_encode(array('has_error'=>'1','login_error'=>'Wrong username or password'));
		}
		else
		{
			$where_arr = array();
			$where_arr['emailAddress'] = $input_data['emailAddress'];
			$where_arr['userPassword'] = md5($input_data['userPassword']);
			$user_data = $this->userdata->grabLoginUserData($where_arr);
			if(count($user_data) > 0)
			{
				if($user_data->status == 'Y')
				{
              
              $this->userdata->saveLoginLog($user_data->id);
              $this->defaultdata->setLoginSession($user_data);
               if($input_data['rememberPassword'] == 'Y')
					{
					
						$emailAddress = array(
							'name'   => 'emailAddress',
							'value'  => $input_data['emailAddress'],
							'expire' => time()+86500
							);
						set_cookie($emailAddress);
						
						$userPassword = array(
							'name'   => 'userPassword',
							'value'  => $input_data['userPassword'],
							'expire' => time()+86500
							);
						set_cookie($userPassword);
					}
					else
					{
						$emailAddress = array(
							'name'   => 'emailAddress',
							'value'  => '',
							'expire' => time()-86500
							);
						set_cookie($emailAddress);
						
						$userPassword = array(
							'name'   => 'userPassword',
							'value'  => '',
							'expire' => time()-86500
							);
						set_cookie($userPassword);
					}
              echo json_encode(array('has_error'=>'0','redirect_url'=>base_url('my-account')));
            }
				else
				{
					echo json_encode(array('has_error'=>'1','login_error'=>'Please active your account.'));
				}
			}
			else
			{
				echo json_encode(array('has_error'=>'1','login_error'=>'Wrong username or password.'));
			}
		}
	}
	
	public function profile($username = '')
	{	
		
	}
	public function editProfile()
	{
		
	}
	public function updateProfileProcess($arg = 1)
	{
		
	}
	public function updatePasswordProcess($arg = 1)
	{
		$input_data = $this->input->post();
		$uid = $this->session->userdata('usrid');
		$user_cond = array('id' => $uid);
		$user_det = $this->userdata->grabUserData($user_cond);
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('newpassword', 'New password', 'trim|required|matches[renewpassword]|xss_clean');
		$this->form_validation->set_rules('renewpassword', 'Re-type new password', 'trim|required|xss_clean');
		
		$return_data = array();
		$return_data['has_error'] = 0;
		
		$this->session->unset_userdata($input_data);
		
		if($this->form_validation->run() == FALSE)
		{
			if($arg == 1)
			{
				$this->session->set_userdata('password_update_error',validation_errors());
				$this->session->set_userdata($input_data);
			}
			else
			{
				$return_data['has_error'] = 1;
				$return_data['password_update_error'] = validation_errors();
			}
		}
		else
		{
			$input_data = $this->defaultdata->secureInput($input_data);
			//print_r($input_data);exit;
			$update_data = array();
			$update_data['userPassword'] = md5($input_data['newpassword']);
			$this->userdata->updateUser($update_data,$user_cond);
			if($arg == 1)
			{
				$this->session->set_userdata('password_update_success','Your password updated successfully.');
			}
			else
			{
				$return_data['has_error'] = 0;
				$return_data['password_update_success'] = 'Your password updated successfully.';
			}
		}
		if($arg == 1)
		{
			redirect(base_url('user/edit-profile'));
		}
		else
		{
			echo json_encode($return_data);
		}
	}
	/************Validation callback functions**************/
	public function checkEmail($email)
	{
		$user_cond = array();
		$user_cond['id !='] = $this->session->userdata('usrid');
		$user_cond['emailAddress'] = $email;
		$exit_user = $this->userdata->grabUserData($user_cond);
		if(count($exit_user) > 0)
		{
			$this->form_validation->set_message('checkEmail', 'This Email is all ready exist.');
			return false;
		}
		else
		{
			return true;
		}
	}
	public function currPassCheck($pass)
	{
		$cond = array();
		$this->load->model('userdata');
		$user_id = $this->session->userdata['usrid'];
		$cond['userPassword'] = md5($pass);
		$cond['id'] = $user_id;
		$user_det=$this->userdata->grabUserData($cond);
		if (count($user_det) > 0)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('currPassCheck', 'Please enter correct password.');
			return false;
		}
	}
	public function checkUserName($str)
	{
		$usr_name = url_title($str,'-',true);
		if(in_array($usr_name, $this->controller_arr))
		{
			$this->form_validation->set_message('checkUserName', 'Please choose different username.');
			return false;
		}
		else
		{
			$post_cond = array('URL_SEOTOOL' => $usr_name);
			$post_data = $this->defaultdata->grabStaticPost($post_cond);
			$page_data = $this->defaultdata->grabStaticPage($post_cond);
			if(count($post_data) > 0 || count($page_data))
			{
				$this->form_validation->set_message('checkUserName', 'Please choose different username.');
				return false;
			}
			else
			{
				$user_cond = array();
				if($this->session->userdata('usrid'))
				{
					$user_cond['id !='] = $this->session->userdata('usrid');
				}
				$user_cond['userName'] = $usr_name;
				$exit_user = $this->userdata->grabUserData($user_cond);
				if(count($exit_user) > 0)
				{
					$this->form_validation->set_message('checkUserName', 'Please choose different username.');
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	}
	public function addressRequired()
	{
		$addrs1 = $this->input->post('addrs1');
		$addrs2 = $this->input->post('addrs2');
		$addrs3 = $this->input->post('addrs3');
		if($addrs1 == '' && $addrs2 == '' && $addrs3 == '')
		{
			$this->form_validation->set_message('addressRequired', 'The Address field is required.');
			return false;
		}
		else
		{
			return true;
		}
	}
	/************Validation callback functions end**************/
	public function forgetPassword()
	{
		$this->load->view('forgot-password',$this->data);
	}
	public function forgotPasswordProcess($args = 1)
	{    
		$input_data = $this->input->post();
        $this->session->unset_userdata($input_data);        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_message('valid_email', 'Please enter valid Email address.');
		
		$return_data = array();
		$return_data['has_error'] = 0;
		
        if($this->form_validation->run() == FALSE)
        {
			if($args == 1)
			{
				$this->session->set_userdata('forgotpassword_error',validation_errors());
				$this->session->set_userdata($input_data);
			}
			else
			{
				$return_data['forgotpassword_error'] = validation_errors();
				$return_data['has_error'] = 1;
			}
        }
		else
		{
            $input_data = $this->defaultdata->secureInput($input_data);
            $user_cond = array();
            $user_cond['emailAddress'] = $input_data['emailAddress'];
            $user_details = $this->userdata->grabUserData($user_cond);
            if(!empty($user_details))
			{   //print_r($user_details); exit;      
                // send mail to user
                $query = $this->db->get(TABLE_EMAIL_FORGET_PASSWORD);
                $result = $query->row();
                $admin_settings = $this->defaultdata->grabSettingData();
				$enc_user = base64_encode($user_details->id.'####'.$user_details->emailAddress); 
				$reset_pass_link = base_url('reset-password/'.$enc_user);
                $mailcontent = htmlspecialchars_decode($result->description);
                $mailcontent = str_replace('{USER_NAME}',$user_details->name,$mailcontent);
				$mailcontent = str_replace('{RESET_PASS_LINK}',$reset_pass_link,$mailcontent);
                $mailcontent = str_replace('{SITE_TITLE}',$admin_settings->SiteTitle,$mailcontent);
                $mailcontent = str_replace('{SITE_URL}',base_url(),$mailcontent);				
				$to=$input_data['emailAddress'];
				
				$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email.">\n"; 
				$headers .= "MIME-Version: 1.0\n"; 
				$headers .= "Content-type: text/html; charset=UTF-8\n"; 
				$subject = $result->emailTitle;
				$message ="<html><head></head><body>"."<style type=\"text/css\">
				<!--
				.style4 {font-size: x-small}
				-->
				</style>
				".$mailcontent."
				</body></html>"; 
				@mail($to,$subject, $message,$headers);
				
				if($args == 1)
				{
					$this->session->set_userdata('fogetpass_success','An email has been sent to '.$user_details->emailAddress.' with a link to change your password.');
				}
				else
				{
					$return_data['has_error'] = 0;
					$return_data['fogetpass_success'] = 'An email has been sent to '.$user_details->emailAddress.' with a link to change your password.';
				}
            }
			else
			{
				if($args == 1)
				{
					$this->session->set_userdata('forgotpassword_error','<p>Email Address does not exists.</p>');
					$this->session->set_userdata($input_data);
				}
				else
				{
					$return_data['has_error'] = 1;
					$return_data['forgotpassword_error'] = '<p>Email Address does not exists.</p>';
				}
            }
        }
		if($args == 1)
		{
			redirect(base_url('forgot-password'));
		}
		else
		{
			echo json_encode($return_data);
		}
	}
	public function resetPassword($user_info = '')
	{
		if($user_info == '')
		{
			redirect(base_url());
		}
		else
		{
			$this->data['user_info'] = $user_info;
			$this->load->view('reset_password',$this->data);
		}
	}
	public function resetPassProcess()
	{
		$input_data = $this->input->post();
		$user_info = $input_data['user_info'];
		unset($input_data['user_info']);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('userPassword', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('re_password', 'Repeat Password', 'trim|required|matches[userPassword]|xss_clean');
		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('reset_pass_error',validation_errors());
			$this->session->set_userdata($input_data);
			redirect(base_url('reset-password/'.$user_info));
		}
		else
		{
			$info_user = base64_decode($user_info);
			$info_user_arr = explode('####',$info_user);
			$user_id = $info_user_arr[0];
			unset($input_data['re_password']);
			$input_data = $this->defaultdata->secureInput($input_data);
			$input_data['userPassword'] = md5($input_data['userPassword']);
			$user_cond = array('id' => $user_id);
			$this->userdata->updateUser($input_data,$user_cond);
			$user_det = $this->userdata->grabUserData($user_cond);
			
			if($user_det->userType == 1)
			{
				$this->session->set_userdata('reset_pass_success','Your password has been reset.you can now login (with email and password fields).');
				redirect(base_url('login'));
			}
			else
			{
				$this->session->set_userdata('reset_pass_sp_success','Your password has been reset.you can now login (with email and password fields).');
				redirect(base_url('service-provider-login'));
			}
		}
	}
	public function uploadProfilePicture()
	{
		$user_id = $this->session->userdata['usrid'];
		$user_details = $this->userdata->grabUserData(array('id' => $user_id));
		$file_name = $file_url = '';
		$has_image = 0;
		$image_type = $image_file = '';
		if($user_details->prifile_picture != '')
		{
			$file_name = $user_details->prifile_picture_org;
			$file_url = profile_picture($user_details->prifile_picture_org);
			$this->data['file_url'] = $file_url;
			$upload_path = UPLOAD_PATH_URL.'profile_pictures/';
			$image_details = getimagesize($upload_path.$file_name);
			$image_type = $image_details['mime'];
			$has_image = 1;
			/*$imgData = base64_encode(file_get_contents($upload_path.$file_name));
			$image_file = 'data: '.$image_type.';base64,'.$imgData;*/
			$image_file = $file_url;
		}
		$this->data['imagetype'] = $image_type;
		$this->data['file_name'] = $file_name;
		$this->data['image_file'] = $image_file;
		
		$html = $this->load->view('lightbox_subview/upload_profile_picture_lightbox',$this->data, TRUE);
		$json_array['html'] = $html;
		$json_array['has_image'] = $has_image;
		echo json_encode($json_array);
	}
	public function logout()
	{
		$condarr['login_status']=0;
		$this->userdata->updateLoginUser($condarr);
		$this->defaultdata->unsetLoginSession();
		redirect(base_url());
	}
   
   //¶ 10092015 S
   /////////////////////////////////////////////////////
   /**
    * My Account
    * 
    *     */
   
   
   public function myAccount()
   {
	  
     $this->checkLogin();
     $uid=$this->session->userdata('usrid');
	 
     $cond = array('id' => $uid);
     $this->data['my_account'] = $this->userdata->grabUserData($cond);
     $this->data['user_profession'] = $this->userdata->grabUserProfession(array('user_id' => $uid));
     $this->data['id'] = encrypt($uid,SALT);
     $this->load->view('my-account',$this->data);
   }
   //¶ 10092015 E


   public function editAccount()
   {
   		$this->checkLogin();
   		$uid=$this->session->userdata('usrid');
	    $cond = array('id' => $uid);
	    $this->data['profession'] = $this->userdata->getProfessions();	    
		if($this->session->userdata('update_error')){
			$this->load->view('edit-account',$this->data);
	    }
	    else
	    {
		    $this->session->set_userdata('my_account', $this->userdata->grabUserData($cond));
		    $this->data['user_profession'] = $this->userdata->grabUserProfession(array('user_id' => $uid));
			$this->load->view('edit-account',$this->data);
		}
   }

   public function editAccountProcess()
   {
   	
		$input_data = $this->input->post();
		$enc_id = $input_data['id'];
		$user_id = decrypt($enc_id,SALT);
		unset($input_data['id']);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('firstName', 'First Name', 'trim|required|alpha|xss_clean');
		$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|alpha|xss_clean');
		// $this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|callback_checkEmail|xss_clean');
		// $this->form_validation->set_message('valid_email', 'Please enter valid email.');
		// $this->form_validation->set_message('is_unique', 'This email is already registered.');
		// $this->form_validation->set_rules('userName', 'Username', 'trim|required|alpha_numeric|callback_checkUserName|xss_clean');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean');
		$this->form_validation->set_rules('address1', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('address2', 'Address', 'trim|xss_clean');
		$this->form_validation->set_rules('phone', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('likeTallentHunter', '', 'trim|xss_clean');
		
		$this->session->unset_userdata($input_data);

		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('update_error',validation_errors());
			$this->session->set_userdata('my_account',$input_data);
		}
		else
		{
			$profession = array();
			$profession = $input_data['profession'];
			unset($input_data['profession']);

			$input_data = $this->defaultdata->secureInput($input_data);
			
			$input_data['dob'] = strtotime($input_data['dob']);
			$input_data['postedtime'] = time();

			$this->userdata->updateUser($input_data,array('id' => $user_id));

			$this->userdata->deleteUserProfession(array('user_id' => $user_id));
			
			if(count($profession) > 0)
			foreach($profession as $single_profession)
			{
				$profession_input = array();
				$profession_input['profession_id'] = $single_profession;
				$profession_input['user_id'] = $user_id;	
				$this->userdata->insertProfession($profession_input);
				$this->db->last_query();
			}				
			$this->session->set_userdata('update_success','Account updated successfully');			
		}
		redirect(base_url('edit-account'));
   }

   public function changePassword()
   {
   		$this->checkLogin();
   		$uid=$this->session->userdata('usrid');
	    $cond = array('id' => $uid);
	    $this->session->set_userdata('my_account', $this->userdata->grabUserData($cond));
	    $this->data['user_profession'] = $this->userdata->grabUserProfession(array('user_id' => $uid));
		$this->data['profession'] = $this->userdata->getProfessions();	    
	    $this->load->view('change-password',$this->data);
   }


    public function changePasswordProcess()
   {
   		$input_data = $this->input->post();
   		$this->load->library('form_validation');
   		$this->form_validation->set_rules('userPassword', 'Password', 'trim|required|xss_clean|callback_password_exists');
		$this->form_validation->set_rules('re_userPassword', 'Repeat Current Password', 'trim|required|matches[userPassword]|xss_clean');
		
		$this->form_validation->set_rules('newPassword', 'New Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('re_newPassword', 'Repeat New Password', 'trim|required|matches[newPassword]|xss_clean');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('update_error',validation_errors());			
		}
		else
		{
			$this->userdata->updateUser(array('userPassword' => md5($input_data['newPassword'])),array('id' => $this->session->userdata('usrid')));
			$this->session->set_userdata('update_success',"Password Succefully updated.");
		}
		redirect(base_url('change-password'));
   }

   public function password_exists($password)
	{
		$cur_pass = $this->userdata->grabUserdata(array('id' => $this->session->userdata('usrid')))->userPassword;
		if ($cur_pass == md5($password))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('password_exists', 'Does not match with your current password.');
			return FALSE;
		}
	}
   public function checkLogin()
   {
	  
   		if($this->session->userdata('usrid')) return true;
   		redirect(base_url());
   }

   public function postArticle($post_id = 0)
	{		
		$this->checkLogin();

		$this->data['post_id'] = $post_id;
   		$uid=$this->session->userdata('usrid');
	    $cond = array('id' => $uid);
	    $this->data['my_account']= $this->userdata->grabUserData($cond);
	    $this->data['countries']= $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
	    $this->data['category'] = $this->userdata->getCategory(array('parentID' => 0));
	    $cond1 = array('user_id' => $uid,'id' => $post_id);
	    $this->data['form_data'] = $this->userdata->grabMainArticle($cond1);
   		$this->load->view('post-article',$this->data);
   }

   public function loadSubCategories()
   {
   		$input_data = $this->input->post();
   		$subCategories = $this->userdata->getCategory(array('parentID' => $input_data['parentID']));
   		$articleDetails = $this->defaultdata->grabPostArticles(array('id' => $input_data['postID']));
   		$return = "";
   		if(count($subCategories) > 0) $return = "<option value='-1'>-- Select --</option>";
   		foreach ($subCategories as $value) {
   			$return .= "<option value='".$value->id."'";
   			if($articleDetails->subCategoryID == $value->id)
   			{
				$return .= " selected ";
   			}
   			$return .= ">".$value->title."</option>";
   		}
   		echo ($return);
   }

   public function loadSubSubCategories()
   {
   		$input_data = $this->input->post();
   		$subCategories = $this->userdata->getCategory(array('parentID' => $input_data['parentID']));
   		$articleDetails = $this->defaultdata->grabPostArticles(array('id' => $input_data['postID']));
   		$return = "";
   		if(count($subCategories) > 0) $return = "<option value='-1'>-- Select --</option>";
   		foreach ($subCategories as $value) {
   			$return .= "<option value='".$value->id."'";
   			if($articleDetails->subSubCategoryID == $value->id)
   			{
				$return .= " selected ";
   			}
   			$return .= ">".$value->title."</option>";
   		}
   		echo ($return);
   }

	public function loadForm()
   	{   		
   		$input_data = $this->input->post();
   		$this->data = array();
		$this->data['credentials'] = $input_data;
		$cat_data =$this->userdata->garbCategory(array('id' => $input_data['subCatId']));
		$this->data['series_or_not'] = $cat_data->series;

		$postID= $input_data['postID'];
		$subCatId= $input_data['subCatId'];
		unset($input_data['postID']);
		unset($input_data['subCatId']);

		$this->data['empty_form'] = 'N';

		$this->data['catattributes'] = $this->userdata->getCategoryAttr($input_data);
		$input_data = array();
		if($cat_data->series == 'Y')
		{
			$cond = array();
			$cond['postID'] = $postID;
			//($this->userdata->getPostMetaSeries(array('postID' => $input_data['postID'])));
			$maxID = $this->db->select_max('episode_id')->from(TABLE_POSTMETA_SERIES)->where($cond)->get()->row()->episode_id;
			$this->data['credentials']['max_episode_id'] = $maxID;

			//for($i = $maxID; $i >= 1 ; $i--)
			for($i = 1; $i <= $maxID ; $i++)
			{
				$this->data['episode_id'] = $i;
				$return_data['form_html'][] = $this->load->view('set-field',$this->data,true);
			}
			$return_data['max_episode_id'] = $maxID;
			$this->data['empty_form'] = 'Y';
			$return_data['form_html_series'] = $this->load->view('set-field',$this->data,true);
		}
		else
		{
			$return_data['form_html'][] = $this->load->view('set-field',$this->data,true);
		}

		$return_data['series_or_not'] = $cat_data->series;
		echo json_encode($return_data);
   	}


	public function loadFormOnDemand()
	{
		$input_data = $this->input->post();
		$this->data = array();
		$this->data['credentials'] = $input_data;
		$cat_data =$this->userdata->garbCategory(array('id' => $input_data['subCatId']));
		$this->data['series_or_not'] = $cat_data->series;

		$postID= $input_data['postID'];
		$subCatId= $input_data['subCatId'];
		$episode_id = $input_data['episode_id'];
		$series_or_not = $input_data['series_or_not'] ;
		unset($input_data['postID']);
		unset($input_data['subCatId']);
		unset($input_data['episode_id']);
		unset($input_data['series_or_not']);

		$this->data['empty_form'] = 'N';

		$this->data['catattributes'] = $this->userdata->getCategoryAttr($input_data);
		$input_data = array();

		///////////////////////
		$cond = array();
		$cond['postID'] = $postID;
		//($this->userdata->getPostMetaSeries(array('postID' => $input_data['postID'])));
		$maxID = $this->db->select_max('episode_id')->from(TABLE_POSTMETA_SERIES)->where($cond)->get()->row()->episode_id;
		$this->data['credentials']['max_episode_id'] = $maxID;

		$this->data['episode_id'] = $episode_id;
		$return_data['form_html'] = $this->load->view('set-field',$this->data,true);

		$return_data['max_episode_id'] = $maxID;
		$return_data['episode_id'] = $episode_id;
		echo json_encode($return_data);
	}
   
   /**
    * My Account End
    * 
    **/
/////////////////////////////////////////////////////

   public function ajaxFileupload()
	{		
		
		if(isset($_FILES) && isset($_REQUEST['k']))
		{
		    $key = $_REQUEST['k'];
		    $previousValue = $_REQUEST['v'];
		    $output_dir = $_REQUEST['path'];
		    if($_FILES[$key]["name"])
		    {		            
				// if($_FILES[$key]["type"])
				$uploadFile = time().".".$key.".".$_FILES[$key]['name'];

				$path_license_file=$output_dir.$uploadFile;
		            
				move_uploaded_file($_FILES[$key]['tmp_name'],$path_license_file);

				$this->userdata->updateUser(array('prifile_picture'=>$uploadFile),array('id' => $this->session->userdata('usrid')));
				if($previousValue)
					@unlink($output_dir.$_POST[$key.'_path']); 
					echo json_encode(array('value'=> $uploadFile ));
		    }
		 
		}
	}

	public function postArticleProcess()
	{
		$input_data = $this->input->post();
		
		$subTitleLanguageTitle = $input_data['subtitleLanguageTitle'];
		unset($input_data['subtitleLanguageTitle']);

        $id = 0;
		$this->load->library('form_validation');

		if ($input_data['series_add_new'] !== 'Y')
        {
			$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
			$this->form_validation->set_rules('categoryID', 'Category', 'trim|required|xss_clean');

			$this->form_validation->set_rules('projectDescription', 'Project Description', 'trim|required|xss_clean');
		}

		if($this->form_validation->run() == FALSE && $input_data['series_add_new'] !== 'Y')
		{
			$this->session->set_userdata('validation_error',validation_errors());
			$this->session->set_userdata('form_data',$input_data);
		}
		else
		{
			if($input_data['series_add_new'] !== 'Y')
			{
				$data =array();
				$data['user_id'] = $this->session->userdata('usrid');
				$data['title'] = $input_data['title'];
				$data['categoryID'] = $input_data['categoryID'];
				$data['countryID'] = $input_data['countryID'];
				$data['postedTime'] = time();
				if(trim($input_data['subCategoryID']) != '')
				{
					$data['subCategoryID'] = $input_data['subCategoryID'];
					unset($input_data['subCategoryID']);
				}
				$data['projectDescription'] = $input_data['projectDescription'];

				unset($input_data['series_add_new']);
				unset($input_data['postID']);

				$id = $this->userdata->insertMainArticles($data);
			}
            else{
				$id = $input_data['postID'];
			}

			unset($input_data['series_add_new']);
			unset($input_data['categoryID']);
			unset($input_data['subCategoryID']);
			unset($input_data['subSubCategoryID']);
			unset($input_data['title']);
			unset($input_data['countryID']);
			unset($input_data['projectDescription']);


			if(!isset($input_data['series_or_not']))
			{
				unset($input_data['series_or_not']);
				foreach ($input_data as $key => $value)
				{
					$metaData = array();
					if(substr($key, -4) === 'path')continue;
					$metaData['postID'] = $id;
					$metaData['slugname'] = $key;
					$metaData['slugvalue'] = $value;
					$fieldSlug = str_replace('cat_', '', $key);
					$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
					$metaData['fieldType'] = $type;
					$metaData['type'] = 'cat';
					$metaDataFiles['postedTime'] = time();
					$postmetaid = $this->userdata->insertPostMeta($metaData);

					// Sub title part Started
					if($key == 'cat_video-subtitle')
					{
						$i = -1;
						foreach ($subTitleLanguageTitle as $single) {
							$i++;

							$subtitle_data = array();
							$subtitle_data['postID'] = $id;
							if(trim($single) == '') continue;
							$subtitle_data['subtitleLanguageTitle'] = $single;
							if($_FILES['subtitleFile']['size'][$i] > 0)
							{
								$fileName = str_replace(" ", "-",time().rand().$_FILES['subtitleFile']['name'][$i]);
								$uploadPath = META_ARTICLE_UPLOAD_PATH.$fileName;
								@move_uploaded_file($_FILES['subtitleFile']['tmp_name'][$i], $uploadPath);
							}
							else continue;
							$subtitle_data['subtitleFile'] = $fileName;
							$subtitle_data['weight'] = rand();
							$subtitle_data['postedTime'] = time();

							$this->userdata->insertPostMetaVideoSubtitles($subtitle_data);
						}

					}
				}

				unset($_FILES['subtitleFile']);
				//print_r($_FILES);
				foreach ($_FILES as $key => $value)
				{
					$file_name = time().$_FILES[$key]['name'];
					$upload_path = META_ARTICLE_UPLOAD_PATH.$file_name;
					@move_uploaded_file($_FILES[$key]['tmp_name'], $upload_path);
					//echo META_ARTICLE_UPLOAD_PATH,$file_name;
                    /** Making A secure copy **/
                    $this->secureUploadedFile(META_ARTICLE_UPLOAD_PATH,$file_name);

					$metaDataFiles = array();
					if(substr($key, -4) === 'path')continue;
					$metaDataFiles['postID'] = $id;
					$metaDataFiles['slugname'] = $key;
					$metaDataFiles['slugvalue'] = $file_name;
					$fieldSlug = str_replace('cat_', '', $key);
					$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
					$metaDataFiles['fieldType'] = $type;
					$metaDataFiles['type'] = 'cat';
					$metaDataFiles['postedTime'] = time();

					$this->userdata->insertPostMeta($metaDataFiles);
				}

			}
			else
			{
				unset($input_data['series_or_not']);
				$episodeID = $input_data['cat_episode-number'];
				foreach ($input_data as $key => $value)
				{
					$metaData = array();
					if(substr($key, -4) === 'path')continue;
					$metaData['postID'] = $id;
					$metaData['slugname'] = $key;
					$metaData['slugvalue'] = $value;
					$fieldSlug = str_replace('cat_', '', $key);
					$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
					$metaData['fieldType'] = $type;
					$metaData['type'] = 'cat';
					$metaData['postedTime'] = time();
					$metaData['episode_id'] = $episodeID;
					$metaDataFiles['postedTime'] = time();
					$PostMetaSeriesId = $this->userdata->insertPostmetaSeries($metaData);

					//Subtitle Section Start
					if($key == 'cat_video-subtitle')
					{
						$i = -1;
						foreach ($subTitleLanguageTitle as $single)
						{
							$i++;

							$subtitle_data = array();
							$subtitle_data['postmetaSeriesID'] = $PostMetaSeriesId;
							if(trim($single) == '') continue;
							$subtitle_data['subtitleLanguageTitle'] = $single;
							if($_FILES['subtitleFile']['size'][$i] > 0)
							{
								$fileName = str_replace(" ", "-",time().rand().$_FILES['subtitleFile']['name'][$i]);
								$uploadPath = META_ARTICLE_UPLOAD_PATH.$fileName;
								@move_uploaded_file($_FILES['subtitleFile']['tmp_name'][$i], $uploadPath);
							}
							else continue;
							$subtitle_data['subtitleFile'] = $fileName;
							$subtitle_data['weight'] = rand();
							$subtitle_data['postedTime'] = time();

							$this->userdata->insertPostMetaSeriesVideoSubtitles($subtitle_data);
						}
					}
				}
				unset($_FILES['subtitleFile']);

				foreach ($_FILES as $key => $value)
				{
					if($_FILES[$key]['error'] == 0)
					{
						$file_name = time().$_FILES[$key]['name'];
						$upload_path = META_ARTICLE_UPLOAD_PATH.$file_name;
						@move_uploaded_file($_FILES[$key]['tmp_name'], $upload_path);
                        /** Making A secure copy **/
						/*echo $fileName;
						exit;*/
                        $this->secureUploadedFile(META_ARTICLE_UPLOAD_PATH,$fileName);

						$metaDataFiles = array();
						if(substr($key, -4) === 'path') continue;
						$metaDataFiles['postID'] = $id;
						$metaDataFiles['slugname'] = $key;
						$metaDataFiles['slugvalue'] = $file_name;
						$fieldSlug = str_replace('cat_', '', $key);
						$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
						$metaDataFiles['fieldType'] = $type;
						$metaDataFiles['type'] = 'cat';
						$metaDataFiles['postedTime'] = time();
						$metaDataFiles['episode_id'] = $episodeID;
						$this->userdata->insertPostmetaSeries($metaDataFiles);
					}

				}
			}
		}
		redirect(base_url('post-article/'.$id));
	}

private function secureUploadedFile($uploadPath = "",$fileName = "")
{
    $ext = strtoupper(pathinfo($uploadPath.$fileName, PATHINFO_EXTENSION));
	$watermark_text = $this->db->select('image_watermark')->from(TABLE_GENERAL_SETTINGS)->get()->row()->image_watermark;
	
    if($ext == "PNG" || $ext == "JPG"|| $ext == "JPEG" || $ext == "GIF")
    {
		$this->defaultdata->wmText($uploadPath.$fileName,$watermark_text);
       // image_watermark($uploadPath,$fileName);
    }

}

//¶07102015 S
	public function articleListing()
	{
		/*$this->data['page_ads'] = get_ad_dynamically_for_differant_page();
		echo "<pre>";
		print_r($this->data['page_ads']);
		echo "</pre>";*/
		//echo $this->session->userdata['page_no'];
		//exit;
		if(!isset($this->session->userdata['limitData'])){
			$this->data['limitdata_afterPaginating'] = $this->session->userdata['limitData'];
		}if(isset($this->session->userdata['page_no'])){
			 $this->data['page_no'] = $this->session->userdata['page_no'];
		}
			$this->data['limitData'] = $this->defaultdata->setLimit();
		
		$input_data = $this->input->get();
		//print_r($input_data);
		$cat_id=$input_data['id'];
		$cat_details=$this->defaultdata->getCategories(array('id' => $cat_id));
		//print_r($cat_details);exit;
		if($cat_details[0]->type=='VID')
		{
			//print($input_data);
			$this->data['parent_id']=$input_data['id'];
		}
		else
		{
			//print_r($cat_details);
			$this->data['parent_id']=$cat_details[0]->id;
		}
			$current_user_id=$this->session->userdata('usrid');
		if($current_user_id!='')
		{
			
			$getMyArticleHistoryId=$this->defaultdata->getMyArticleHistoryId($current_user_id);

			foreach($getMyArticleHistoryId as $val)
			{
				$add_articale_details[]=$this->defaultdata->grabPosts(array('id'=>$val->article_id,'status'=>'Y'));
				//echo $this->db->last_query();
				/*echo "<pre>";
				print_r($add_articale_details);
				exit;*/
			}
			/*echo "<pre>";
			print_r($add_articale_details);
			exit;*/
			if(!empty($add_articale_details)){
				foreach($add_articale_details as $val)
				{
					if(!empty($val)){
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
			}
		}
		else
		{
			
			$get_arbitary_article=$this->defaultdata->arbitaryArticle();
			//print_r($get_arbitary_article);
			$add_articale_details=$get_arbitary_article;
			//echo "<pre>";
				//print_r($add_articale_details);
			foreach($add_articale_details as $val)
			{
				//echo $val->id;
				 $add_details_sql = "SELECT ".TABLE_CATEGORYATTR.".fieldName, ".TABLE_META_ARTICLE.". *
                        FROM ".TABLE_CATEGORYATTR."
                        LEFT JOIN ".TABLE_META_ARTICLE." ON ".TABLE_CATEGORYATTR.".fieldSlug = REPLACE( ".TABLE_META_ARTICLE.".slugname,  'cat_',  '' )
                        WHERE ".TABLE_META_ARTICLE.".postID = ".$val->id."
                        AND ".TABLE_META_ARTICLE.".fieldType !=  '".$fieldTypeChooser[$category->type]."'
                        AND ".TABLE_CATEGORYATTR.".type = ".TABLE_META_ARTICLE.".fieldType GROUP BY ".TABLE_CATEGORYATTR.".fieldSlug order by ".TABLE_META_ARTICLE.".id"; 
				//echo $add_details_sql;
				$val->extra_details = $this->db->query($add_details_sql)->result();
				
			}
			//echo "<pre>";print_r($add_articale_details);exit;
			
		}
		
		//echo "<pre>";print_r($add_articale_details);exit;
		$this->data['adds'] = $add_articale_details;
		//echo $this->data['parent_id'];exit;
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		//echo $this->db->last_query();
		$this->session->set_userdata('page_no',1);
		$this->load->view('article-listing',$this->data);
		
	}


	public function manageArticle()
	{
		$this->checkLogin();
   		$uid=$this->session->userdata('usrid');
	    $cond = array('id' => $uid);
	    $this->data['my_account']= $this->userdata->grabUserData($cond);
	    $this->data['category'] = $this->userdata->getCategory(array('parentID' => 0));
	    $cond1 = array('user_id' => $uid);
	    $this->data['articles_list'] = $this->defaultdata->getPostArticles($cond1);
		$this->load->view('manage-article',$this->data);
	}
	
	public function my_ads($post_id = 0){
		$this->checkLogin();
		$this->data['post_id'] = $post_id;
   		$uid=$this->session->userdata('usrid');
		
		
		$this->data['ad_list'] = $this->defaultdata->getAllAds();
		//echo "<pre>";
		//print_r($this->data['ad_list']);
		
		
		$this->load->view('my-ads',$this->data);
		
	}
	public function create_ad($post_id = 0){
		$this->checkLogin();
		if($this->input->get('id') != ''){
			$this->data['post_id'] = $this->input->get('id');
			$this->data['ad_details'] = getAdById($this->input->get('id'));
		}
   		$uid=$this->session->userdata('usrid');
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('location_id', 'Location List', 'trim|required|xss_clean');
		$this->form_validation->set_rules('localinternational', 'localinternational', 'trim|required|xss_clean');
		
		$this->form_validation->set_rules('ad_url', 'Ad URL', 'trim|required|xss_clean');
		$this->form_validation->set_rules('startDate', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('endDate', 'End Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('image', 'Ad image', 'callback_image_upload');
		

		if($this->form_validation->run() == FALSE && $input_data['series_add_new'] !== 'Y')
		{
			$this->session->set_userdata('validation_error',validation_errors());
			$this->session->set_userdata('form_data',$input_data);
		}
		else
		{
			$insert_data = $this->input->post();
			/*echo "<pre>";
			print_r($insert_data);
			echo "<br>";*/
			$insert_data['user_id'] = $this->session->userdata('usrid');
			$insert_data['startDate'] = $this->input->post('startDate');
			$parsed = parse_url($this->input->post('ad_url'));
			if (empty($parsed['scheme'])) {
				$insert_data['ad_url'] = 'http://' . ltrim($this->input->post('ad_url'), '/');
			}
			
			$insert_data['endDate'] = $this->input->post('endDate');
			if($this->input->post('localinternational') == '1'){
				$insert_data['countryID'] = countryCode()['country_code'];
			}
			
			if(!empty($_FILES)){
				$ext = end(explode('.',$_FILES['image']['name']));
				$insert_data['image'] = $file_name = time().'.'.$ext;
			}
			$paypalData['cmd'] = $insert_data['cmd'];
			$paypalData['no_note'] =$insert_data['no_note'];
			$paypalData['lc'] = $insert_data['lc'];
			$paypalData['currency_code'] = $insert_data['currency_code'];
			$paypalData['bn'] = $insert_data['bn'];
			$paypalData['payer_email'] = $insert_data['payer_email'];
			
			unset($insert_data['ad_id']);
			unset($insert_data['cmd']);
			unset($insert_data['no_note']);
			unset($insert_data['lc']);
			unset($insert_data['currency_code']);
			unset($insert_data['bn']);
			unset($insert_data['payer_email']);
			unset($insert_data['x']);
			unset($insert_data['y']);
			/*print_r($insert_data);
			exit;*/
			$insert = $this->db->insert(TABLE_AD_WITH_US, $insert_data);
			if($insert){
				$paypalemailaddress = $this->db->select('paypalemailaddress')->from(TABLE_GENERAL_SETTINGS)->get()->row()->paypalemailaddress;
					
				$paypal_email = $paypalemailaddress;
			$return_url = 'http://dev4.technoexponent.net/webllywood/my-ads';
			$cancel_url = 'http://dev4.technoexponent.net/webllywood/create-ad?success=0';
			$notify_url = 'http://dev4.technoexponent.net/webllywood/user/updateAfterPaymentPaypal';
			
			$querystring = '';
	
			// Firstly Append paypal account to querystring
			$querystring .= "?business=".urlencode($paypal_email)."&";
			
			// Append amount& currency (£) to quersytring so it cannot be edited in html
			
			//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
			$querystring .= "item_name=".urlencode($this->input->post('title'))."&";
			$querystring .= "amount=".urlencode($this->input->post('price'))."&";
			
			//loop for posted values and append to querystring
			foreach($paypalData as $key => $value){
				$value = urlencode(stripslashes($value));
				$querystring .= "$key=$value&";
			}
			
			$querystring .= "item_number=".urlencode($this->db->insert_id())."&";
			// Append paypal return addresses
			$querystring .= "return=".urlencode(stripslashes($return_url))."&";
			$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
			$querystring .= "notify_url=".urlencode($notify_url);
			
			//echo $querystring; die();
			
			// Append querystring with custom field
			//$querystring .= "&custom=".USERID;
			//echo 'https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring;
			//exit;
			// Redirect to paypal IPN
			redirect('https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
			
			exit();
			}
						

			}
		}
		
		$this->load->view('create-ad',$this->data);
	}
	function image_upload(){
		  if($_FILES['image']['size'] != 0){
			$upload_dir = 'upload/site_adds/';
			if (!is_dir($upload_dir)) {
				 mkdir($upload_dir);
			}   
			$config['upload_path']   = $upload_dir;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$ext = end(explode('.',$_FILES['image']['name']));
			$config['file_name'] = time().'.'.$ext;
			$config['overwrite'] = false;
			$config['max_size']  = '5120';
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('image')){
				$this->form_validation->set_message('image_upload', $this->upload->display_errors());
				return false;
			}   
			else{
				$this->upload_data['file'] =  $this->upload->data();
				return true;
			}   
		}   
		else{
			$this->form_validation->set_message('image_upload', "No file selected");
			return false;
		}
	}	
	public function re_post_ad($post_id = 0){
		$this->checkLogin();
		
		if($post_id != ''){
			$this->data['post_id'] = $post_id;
			$this->data['ad_details'] = getAdById($post_id);
		}
   		$uid=$this->session->userdata('usrid');
		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$this->load->library('form_validation');

			$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
			$this->form_validation->set_rules('location_id', 'Location List', 'trim|required|xss_clean');
			$this->form_validation->set_rules('localinternational', 'localinternational', 'trim|required|xss_clean');
			
			$this->form_validation->set_rules('ad_url', 'Ad URL', 'trim|required|xss_clean');
			$this->form_validation->set_rules('startDate', 'Start Date', 'trim|required|xss_clean');
			$this->form_validation->set_rules('endDate', 'End Date', 'trim|required|xss_clean');
			
			
			if($this->form_validation->run() == FALSE && $input_data['series_add_new'] !== 'Y')
			{
				$this->session->set_userdata('validation_error',validation_errors());
				$this->session->set_userdata('form_data',$input_data);
			}
			else
			{
				
				$insert_data = $this->input->post();
				
				
				$old_ad = getAdById($insert_data['ad_id']);
				$insert_data['user_id'] = $this->session->userdata('usrid');
				$insert_data['startDate'] = $this->input->post('startDate')." ".date("h:i:s");
				$parsed = parse_url($this->input->post('ad_url'));
				if (empty($parsed['scheme'])) {
					$insert_data['ad_url'] = 'http://' . ltrim($this->input->post('ad_url'), '/');
				}
				$insert_data['endDate'] = $this->input->post('endDate')." ".date("h:i:s");
				//exit;
				//print_r($_FILES['image']);
				if($_FILES['image']['tmp_name'] != ''){
					$ext = end(explode('.',$_FILES['image']['name']));
					$insert_data['image'] = $file_name = time().'.'.$ext;
					$upload_path = 'upload/site_adds/'.$insert_data['image'];
					@move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
				}else{
					$insert_data['image'] = $old_ad->image;
				}
				$paypalData['cmd'] = $insert_data['cmd'];
				$paypalData['no_note'] =$insert_data['no_note'];
				$paypalData['lc'] = $insert_data['lc'];
				$paypalData['currency_code'] = $insert_data['currency_code'];
				$paypalData['bn'] = $insert_data['bn'];
				$paypalData['payer_email'] = $insert_data['payer_email'];
				
				unset($insert_data['ad_id']);
				unset($insert_data['cmd']);
				unset($insert_data['no_note']);
				unset($insert_data['lc']);
				unset($insert_data['currency_code']);
				unset($insert_data['bn']);
				unset($insert_data['payer_email']);
				unset($insert_data['x']);
				unset($insert_data['y']);
				
				/*echo "<pre>";
				print_r($insert_data);
				exit;*/
				$this->db->where('id',$this->input->post('ad_id'));
				$this->db->delete(TABLE_AD_WITH_US);
				
				$insert = $this->db->insert(TABLE_AD_WITH_US, $insert_data);
				if($insert){
					$paypalemailaddress = $this->db->select('paypalemailaddress')->from(TABLE_GENERAL_SETTINGS)->get()->row()->paypalemailaddress;
					
				$paypal_email = $paypalemailaddress;
				$return_url = 'http://dev4.technoexponent.net/webllywood/my-ads';
				$cancel_url = 'http://dev4.technoexponent.net/webllywood/create-ad?success=0';
				$notify_url = 'http://dev4.technoexponent.net/webllywood/user/updateAfterPaymentPaypal';
				
				$querystring = '';
		
				// Firstly Append paypal account to querystring
				$querystring .= "?business=".urlencode($paypal_email)."&";
				
				// Append amount& currency (£) to quersytring so it cannot be edited in html
				
				//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
				$querystring .= "item_name=".urlencode($this->input->post('title'))."&";
				$querystring .= "amount=".urlencode($this->input->post('price'))."&";
				
				//loop for posted values and append to querystring
				foreach($paypalData as $key => $value){
					$value = urlencode(stripslashes($value));
					$querystring .= "$key=$value&";
				}
				
				$querystring .= "item_number=".urlencode($this->db->insert_id())."&";
				// Append paypal return addresses
				$querystring .= "return=".urlencode(stripslashes($return_url))."&";
				$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
				$querystring .= "notify_url=".urlencode($notify_url);
				
				//echo $querystring; die();
				
				// Append querystring with custom field
				//$querystring .= "&custom=".USERID;
				//echo 'https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring;
				//exit;
				// Redirect to paypal IPN
				redirect('https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
				
				exit();
				}
							

			}
		}
		
		$this->load->view('re-post-ad',$this->data);
	}

	
	
	public function updateAfterPaymentPaypal(){
		
		$res = $this->db->select('id')->order_by('id','desc')->limit(1)->get(TABLE_AD_WITH_US)->row('id');
		$this->db->where('id', $res);
		$this->db->update(TABLE_AD_WITH_US, array('paymentStatus'=>'Y'));
	
	}
	
	public function deleteUserAd(){
		$this->checkLogin();
		$id = $this->input->get('id');
		$this->db->where('id', $id);
		$this->db->delete(TABLE_AD_WITH_US);
		$this->db->last_query();
		redirect(base_url('my-ads'));
	}
	public function deletePostArticle()
	{
		$input_data = $this->input->get();
        $dat = $this->userdata->grabMainArticle($input_data);
        $post_meta = $this->userdata->getPostMeta(array('postID' => $dat->id));
        if(empty($post_meta))
        {
            $post_meta_series = $this->userdata->getPostMetaSeries(array('postID' => $dat->id));
            foreach($post_meta_series as $singleSeries)
            {
                @unlink(META_ARTICLE_UPLOAD_PATH.$singleSeries->slugvalue);
                @unlink(SECURED_POST_FILES.$singleSeries->slugvalue);
                if($singleSeries->fieldType == 'SubTitle'){
                    $sub_title_series = $this->userdata->grabPostMetaVideoSeriesSubtitles(array('postmetaSeriesID' => $singleSeries->id));
                    foreach ($sub_title_series as $item) {
                        @unlink(META_ARTICLE_UPLOAD_PATH.$item->subtitleFile);
                    }
                    $this->userdata->deletePostMetaVideoSeriesSubtitles(array('postmetaSeriesID' => $singleSeries->id));
                }
            }
            $this->userdata->deletePostmetaSeries(array('postID' => $dat->id));
        }
        else
        {
            foreach($post_meta as $singleSeries)
            {
                @unlink(META_ARTICLE_UPLOAD_PATH.$singleSeries->slugvalue);
                @unlink(SECURED_POST_FILES.$singleSeries->slugvalue);
                if($singleSeries->fieldType == 'SubTitle'){
                    $sub_title_series = $this->userdata->grabPostMetaVideoSubtitles(array('postID' => $dat->id));
                    foreach ($sub_title_series as $item) {
                        @unlink(META_ARTICLE_UPLOAD_PATH.$item->subtitleFile);
                    }
                    $this->userdata->deletePostMetaVideoSubtitles(array('postID' => $dat->id));
                }
            }
            $this->userdata->deletePostMeta(array('postID' => $dat->id));
        }
		$this->defaultdata->deletePostArticles($input_data);
		redirect(base_url('manage-article'));
	}


	public function articleDetails($id = 0,$title = '')
	{
		$current_user_id=$this->session->userdata('usrid');
		if($current_user_id == ''){
			redirect(base_url());
		}
		$this->data['isVideoExist'] = false;
        $input_data = array();
        $input_data['id'] = $id;
		
		$this->data['total_likes'] = $this->db->select("total_likes")->from(TABLE_MAIN_POST_ARTICLE)->where(array("id"=>$id))->get()->row()->total_likes;
		
		$if_like = $this->data['if_like'] = $this->db->select('count(like_id) cnt')->from(TABLE_LIKE)->where(array('article_id'=>$id,"user_id"=>$current_user_id))->get()->row()->cnt;
		
		$next_four_video = $this->data['next_four_video'] = $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where(array('categoryID'=>112))->where("id !=",$id)->order_by('id', 'RANDOM')->limit(4,0)->get()->result();$next_four_video = 
		
		$this->data['next_four_audio'] = $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where(array('categoryID'=>113))->where("id !=",$id)->order_by('id', 'RANDOM')->limit(4,0)->get()->result();
		
		
		$article_data= $this->defaultdata->grabPosts($input_data);
		/*echo "<pre>";
		print_r($article_data);
		exit;*/
		$this->data['comments'] = $this->defaultdata->getComments(array('articleID' => $input_data['id']));
		/*echo "<pre>";
		print_r($this->data['comments']);
		exit;*/

		$this->data['post_details'] = $article_data;

		$this->data['user_details'] = $this->userdata->grabUserData(array('id' => $article_data->user_id));
		
		$category = $this->defaultdata->grabCategories(array('id' => $article_data->categoryID));
		//print_r($category);
		$fieldTypeChooser = array('VID' => 'Video' , 'MUS' => 'Audio', 'ART' => 'Photo', 'WRI'=> 'File');
		//echo $fieldTypeChooser[$category->type];
		$this->data['post_meta'] = $this->defaultdata->grabMetaPosts(array('postID' => $article_data->id,'fieldType' => $fieldTypeChooser[$category->type]));

        $details_sql = "SELECT ".TABLE_CATEGORYATTR.".fieldName, ".TABLE_META_ARTICLE.". *
                        FROM ".TABLE_CATEGORYATTR."
                        LEFT JOIN ".TABLE_META_ARTICLE." ON ".TABLE_CATEGORYATTR.".fieldSlug = REPLACE( ".TABLE_META_ARTICLE.".slugname,  'cat_',  '' )
                        WHERE ".TABLE_META_ARTICLE.".postID = ".$article_data->id."
                        AND ".TABLE_META_ARTICLE.".fieldType !=  '".$fieldTypeChooser[$category->type]."'
                        AND ".TABLE_CATEGORYATTR.".type = ".TABLE_META_ARTICLE.".fieldType GROUP BY ".TABLE_CATEGORYATTR.".fieldSlug order by ".TABLE_META_ARTICLE.".id";
		//echo $details_sql;
        $article_details["post_meta_other_details"] = $this->db->query($details_sql)->result();
		
		$article_details['article_id'] = $id;
		
        $this->data['post_meta_other_details'] = $article_details["post_meta_other_details"];

        $this->data['article_other_details'] = $this->load->view('includes/article_other_details.php',$article_details,true);

       //$this->data['post_meta_other_details'] = $this->defaultdata->getMetaPosts(array('postID' => $article_data->id,'fieldType !=' => $fieldTypeChooser[$category->type]));

		$this->data['article_type'] = $category->type;
		
		$cat_id=$article_data->categoryID;
		$category_details = $this->defaultdata->grabCategoryDetails(array('id'=>$cat_id));
		
		if($current_user_id!='')
		{
			$this->defaultdata->deleteHistory(array('article_id'=>$id,'user_id'=>$current_user_id));
			$insert_history['user_id']=$current_user_id;
			$insert_history['article_id']=$id;
			$insert_history['article_type']=$category_details->type;
			$insert_history['article_owner_id']=$article_data->user_id;
			$insert_history['search_time']=time();
			$id=$this->userdata->insertHistory($insert_history);
		}
		if($current_user_id!='')
		{
			
			$getMyArticleHistoryId=$this->defaultdata->getMyArticleHistoryId($current_user_id);
			//echo "<pre>";print_r($getMyArticleHistoryId);exit;
			foreach($getMyArticleHistoryId as $val)
			{
				$add_articale_details[]=$this->defaultdata->grabPosts(array('id'=>$val->article_id));
			}
			//print_r($add_articale_details);exit;
			foreach($add_articale_details as $val)
			{
				if(!empty($val)){
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
			//echo "<pre>";print_r($add_articale_details);exit;
			
		}
		
		$this->data['adds'] = $add_articale_details;
		
		$this->load->view('article-details',$this->data);
	}

	public function moreProjects($user_id = '',$subCategoryID = ''){
		$this->data['input_data'] = $input_data = $this->input->get();
		
		
		$this->data['page_name'] = 'more-projects';
		$this->data['ct'] = $subCategoryID;
		$this->data['user_id'] = $user_id;
		$this->data['categoryId'] = $this->db->select('categoryID')->from('com_main_post_article')->where('subCategoryID',$subCategoryID)->limit(1)->get()->row()->categoryID;
		if($current_user_id!='')
		{
			
			$getMyArticleHistoryId=$this->defaultdata->getMyArticleHistoryId($current_user_id);
			//print_r($getMyArticleHistoryId);
			foreach($getMyArticleHistoryId as $val)
			{
				$add_articale_details[]=$this->defaultdata->getPosts(array('user_id'=>$user_id,'subCategoryID'=>$subCategoryID));
				echo "<pre>";
				print_r($add_articale_details);
			}
			//print_r($add_articale_details);exit;
			foreach($add_articale_details as $val)
			{
				//print_r($val);
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
			
			$get_arbitary_article=$this->defaultdata->arbitaryArticle();
			//print_r($get_arbitary_article);
			$add_articale_details=$get_arbitary_article;
			//echo "<pre>";
				//print_r($add_articale_details);
			foreach($add_articale_details as $val)
			{
				//echo $val->id;
				 $add_details_sql = "SELECT ".TABLE_CATEGORYATTR.".fieldName, ".TABLE_META_ARTICLE.". *
                        FROM ".TABLE_CATEGORYATTR."
                        LEFT JOIN ".TABLE_META_ARTICLE." ON ".TABLE_CATEGORYATTR.".fieldSlug = REPLACE( ".TABLE_META_ARTICLE.".slugname,  'cat_',  '' )
                        WHERE ".TABLE_META_ARTICLE.".postID = ".$val->id."
                        AND ".TABLE_META_ARTICLE.".fieldType !=  '".$fieldTypeChooser[$category->type]."'
                        AND ".TABLE_CATEGORYATTR.".type = ".TABLE_META_ARTICLE.".fieldType GROUP BY ".TABLE_CATEGORYATTR.".fieldSlug order by ".TABLE_META_ARTICLE.".id"; 
				//echo $add_details_sql;
				$val->extra_details = $this->db->query($add_details_sql)->result();
				
			}
			//echo "<pre>";print_r($add_articale_details);exit;
			
		}
		
		$Postdata =$this->defaultdata->innerSearchQueryProcessor($input_data);
		//echo $this->db->last_query();
		$this->data['searchResult'] = $Postdata;
		$this->data['adds'] = $add_articale_details;
		//echo $this->data['parent_id'];exit;
		$this->data['allCountries'] = $this->userdata->getCountries(array('status' => 'Y','languageID' => '1'));
		//echo $this->db->last_query();
		$limitData_limit = $this->session->userdata['limitData'];
			if(isset($this->session->userdata['page_no'])){
			 $this->data['page_no'] = $this->session->userdata['page_no'];
		}
		$this->session->set_userdata('page_no',1);
		$this->load->view('more_projects',$this->data);
		$this->session->unset_userdata['limitData'];
	}
	public function postArticleUpdateProcess1()
	{
		$input_data = $this->input->post();

		$post_id = $input_data['post_id'];

		unset($input_data['post_id']);

		$subTitleLanguageTitle = $input_data['subtitleLanguageTitle'];
		unset($input_data['subtitleLanguageTitle']);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('categoryID', 'Category', 'trim|required|xss_clean');

		$this->form_validation->set_rules('projectDescription', 'Project Description', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('validation_error',validation_errors());
			$this->session->set_userdata('form_data',$input_data);
		}
		else
		{
			$data =array();
			$data['user_id'] = $this->session->userdata('usrid');
			$data['title'] = $input_data['title'];
			$data['categoryID'] = $input_data['categoryID'];
			$data['countryID'] = $input_data['countryID'];
			$data['postedTime'] = mktime();
			if(trim($input_data['subCategoryID']) != '')
			{
				$data['subCategoryID'] = $input_data['subCategoryID'];
				unset($input_data['subCategoryID']);
			}

			if(trim($input_data['subSubCategoryID']) != '')
			{
				$data['subSubCategoryID'] = $input_data['subSubCategoryID'];
				unset($input_data['subSubCategoryID']);
			}


			$data['projectDescription'] = $input_data['projectDescription'];

			$this->userdata->updateMainArticle($data,array('id' => $post_id));

			unset($input_data['subCategoryID']);
			unset($input_data['subSubCategoryID']);
			unset($input_data['countryID']);
			unset($input_data['title']);
			unset($input_data['categoryID']);
			unset($input_data['projectDescription']);
			$id = $post_id;
			$this->userdata->deleteMetaArticle(array('postID' => $id));
			foreach ($input_data as $key => $value)
			{
				$metaData = array();
				$metaData['postID'] = $id;
				if(substr($key, -4) === 'path') continue;
				$fieldSlug = str_replace('cat_', '', $key);
				$metaData['slugvalue'] = $value;
				$metaData['slugname'] = $key;
				$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
				$metaData['fieldType'] = $type;
				$metaData['type'] = 'cat';
				$metaData['postedTime'] = time();

				$postmetaid = $this->userdata->insertMetaArticles($metaData);
				if($key == 'cat_sub-titles')
				{
					$i = -1;
					var_dump($subTitleLanguageTitle);
					foreach ($subTitleLanguageTitle as $single) {
						$i++;
						$subtitle_data = array();
						$subtitle_data['postID'] = $id;
						if(trim($single) == '') continue;
						$subtitle_data['subtitleLanguageTitle'] = $single;

						var_dump($_FILES['subtitleFile']['size'][$i]);
						if($_FILES['subtitleFile']['size'][$i] > 0)
						{
							$fileName = str_replace(" ", "-",time().rand().$_FILES['subtitleFile']['name'][$i]);
							$uploadPath = META_ARTICLE_UPLOAD_PATH.$fileName;
							@move_uploaded_file($_FILES['subtitleFile']['tmp_name'][$i], $uploadPath);
						}
						else continue;
						$subtitle_data['subtitleFile'] = $fileName;
						$subtitle_data['weight'] = rand();
						$subtitle_data['postedTime'] = time();

						$this->userdata->insertPostMetaVideoSubtitles($subtitle_data);
						echo $this->db->last_query();
					}

				}
			}
			unset($_FILES['subtitleFile']);

			foreach ($_FILES as $key => $value)
			{
				$metaDataFiles = array();
				if($_FILES[$key]['size'] > 0)
				{
					$file_name = time().$_FILES[$key]['name'];
					$file_name = str_replace(" ", "-", $file_name);
					$upload_path = META_ARTICLE_UPLOAD_PATH.$file_name;
					@move_uploaded_file($_FILES[$key]['tmp_name'], $upload_path);
					$metaDataFiles['postID'] = $id;
					$metaDataFiles['slugname'] = $key;
					$fieldSlug = str_replace('cat_', '', $key);
					$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
					$metaDataFiles['fieldType'] = $type;
					$metaDataFiles['type'] = 'cat';
					$metaDataFiles['postedTime'] = time();
					$metaDataFiles['slugvalue'] = $file_name;
					$this->userdata->insertMetaArticles($metaDataFiles);
				}
				else
				{
					$metaDataFiles = array();
					$metaDataFiles['slugvalue'] = $input_data[$key.'path'];
					$metaDataFiles['postID'] = $id;
					$metaDataFiles['slugname'] = $key;
					$fieldSlug = str_replace('cat_', '', $key);
					$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
					$metaDataFiles['fieldType'] = $type;
					$metaDataFiles['type'] = 'cat';
					$metaDataFiles['postedTime'] = time();

					$this->userdata->insertMetaArticles($metaDataFiles);

				}
			}
		}
		redirect(base_url('post-article/'.$post_id));
	}
	public function postArticleUpdateProcess()
	{
		$input_data = $this->input->post();

		$post_id = $input_data['post_id'];

		unset($input_data['post_id']);


		$subTitleLanguageTitle = $input_data['subtitleLanguageTitle'];
		unset($input_data['subtitleLanguageTitle']);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('categoryID', 'Category', 'trim|required|xss_clean');

		$this->form_validation->set_rules('projectDescription', 'Project Description', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('validation_error',validation_errors());
			$this->session->set_userdata('form_data',$input_data);
		}
		else
		{
			if($input_data['series_or_not'] !== 'Y' || !isset($input_data['series_or_not']))
			{
				$data =array();
				$data['user_id'] = $this->session->userdata('usrid');
				$data['title'] = $input_data['title'];
				$data['categoryID'] = $input_data['categoryID'];
				$data['countryID'] = $input_data['countryID'];
				$data['postedTime'] = time();

				if(trim($input_data['subCategoryID']) != '')
				{
					$data['subCategoryID'] = $input_data['subCategoryID'];
					unset($input_data['subCategoryID']);
				}

				if(trim($input_data['subSubCategoryID']) != '')
				{
					$data['subSubCategoryID'] = $input_data['subSubCategoryID'];
					unset($input_data['subSubCategoryID']);
				}


				$data['projectDescription'] = $input_data['projectDescription'];

				$this->userdata->updateMainArticle($data,array('id' => $post_id));

				unset($input_data['subCategoryID']);
				unset($input_data['subSubCategoryID']);
				unset($input_data['countryID']);
				unset($input_data['title']);
				unset($input_data['categoryID']);
				unset($input_data['projectDescription']);
				$id = $post_id;
				$this->userdata->deletePostMeta(array('postID' => $id));
				/*echo "<pre>";
				print_r($input_data);
				exit;*/
				foreach ($input_data as $key => $value)
				{
					$metaData = array();
					$metaData['postID'] = $id;
					if(substr($key, -4) === 'path') continue;
					$fieldSlug = str_replace('cat_', '', $key);
					$metaData['slugvalue'] = $value;
					$metaData['slugname'] = $key;
					$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
					$metaData['fieldType'] = $type;
					$metaData['type'] = 'cat';
					$metaData['postedTime'] = time();

					$postmetaid = $this->userdata->insertPostMeta($metaData);
					/*echo "<pre>";
					print_r($key);*/
					
					if($key == 'cat_video-subtitle')
					{
						$i = -1;
						foreach ($subTitleLanguageTitle as $single) {
							$i++;
							
							$subtitle_data = array();
							$subtitle_data['postID'] = $id;
							if(trim($single) == '') continue;
							$subtitle_data['subtitleLanguageTitle'] = $single;
							
							if($_FILES['subtitleFile']['size'][$i] > 0)
							{
								
								$fileName = str_replace(" ", "-",time().rand().$_FILES['subtitleFile']['name'][$i]);
								$uploadPath = META_ARTICLE_UPLOAD_PATH.$fileName;
								@move_uploaded_file($_FILES['subtitleFile']['tmp_name'][$i], $uploadPath);
								/*echo $uploadPath.$fileName;
								exit;*/
							}
							else continue;
							$subtitle_data['subtitleFile'] = $fileName;
							$this->userdata->insertPostMetaVideoSubtitles($subtitle_data);
						}

					}
				}
				//exit;

				unset($_FILES['subtitleFile']);

				foreach ($_FILES as $key => $value)
				{
					$metaDataFiles = array();
					if($_FILES[$key]['size'] > 0)
					{
						
						$file_name = time().$_FILES[$key]['name'];
						$file_name = str_replace(" ", "-", $file_name);
						$upload_path = META_ARTICLE_UPLOAD_PATH.$file_name;
						@move_uploaded_file($_FILES[$key]['tmp_name'], $upload_path);
                        /** Making A secure copy **/
                        $this->secureUploadedFile(META_ARTICLE_UPLOAD_PATH,$file_name);
						$metaDataFiles['postID'] = $id;
						$metaDataFiles['slugname'] = $key;
						$fieldSlug = str_replace('cat_', '', $key);
						$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
						$metaDataFiles['fieldType'] = $type;
						$metaDataFiles['type'] = 'cat';
						$metaDataFiles['postedTime'] = time();
						$metaDataFiles['slugvalue'] = $file_name;
						$this->userdata->insertPostMeta($metaDataFiles);
					}
					else
					{
						$metaDataFiles = array();
						$metaDataFiles['slugvalue'] = $input_data[$key.'path'];
						$metaDataFiles['postID'] = $id;
						$metaDataFiles['slugname'] = $key;
						$fieldSlug = str_replace('cat_', '', $key);
						$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
						$metaDataFiles['fieldType'] = $type;
						$metaDataFiles['type'] = 'cat';
						$metaDataFiles['postedTime'] = time();

						$this->userdata->insertPostMeta($metaDataFiles);

					}
				}
			}
			else
			{
				unset($input_data['series_or_not']);
				unset($input_data['add_another_series']);
				$data =array();
				$data['user_id'] = $this->session->userdata('usrid');
				$data['title'] = $input_data['title'];
				$data['categoryID'] = $input_data['categoryID'];
				$data['countryID'] = $input_data['countryID'];
				$data['postedTime'] = time();

				if(trim($input_data['subCategoryID']) != '')
				{
					$data['subCategoryID'] = $input_data['subCategoryID'];
					unset($input_data['subCategoryID']);
				}

				if(trim($input_data['subSubCategoryID']) != '')
				{
					$data['subSubCategoryID'] = $input_data['subSubCategoryID'];
					unset($input_data['subSubCategoryID']);
				}


				$data['projectDescription'] = $input_data['projectDescription'];

				$this->userdata->updateMainArticle($data,array('id' => $post_id));

				unset($input_data['subCategoryID']);
				unset($input_data['subSubCategoryID']);
				unset($input_data['countryID']);
				unset($input_data['title']);
				unset($input_data['categoryID']);
				unset($input_data['projectDescription']);
				$id = $post_id;
				$episode_id = $input_data['episode_id'];
				$this->userdata->deletePostmetaSeries(array('postID' => $id,'episode_id' => $episode_id));

				foreach ($input_data as $key => $value)
				{
					$metaData = array();
					$metaData['postID'] = $id;
					if(substr($key, -4) === 'path') continue;
					$fieldSlug = str_replace('cat_', '', $key);
					$metaData['slugvalue'] = $value;
					$metaData['slugname'] = $key;
					$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
					$metaData['fieldType'] = $type;
					$metaData['type'] = 'cat';
					$metaData['postedTime'] = time();
					$metaData['episode_id'] = $episode_id;


					$postmetaid = $this->userdata->insertPostmetaSeries($metaData);

					if($key == 'cat_video-subtitle')
					{
						$i = -1;

						foreach ($subTitleLanguageTitle as $single) {
							$i++;
							$subtitle_data = array();
							$subtitle_data['postmetaSeriesID'] = $postmetaid;
							if(trim($single) == '') continue;
							$subtitle_data['subtitleLanguageTitle'] = $single;

							if($_FILES['subtitleFile']['size'][$i] > 0)
							{
								$fileName = str_replace(" ", "-",time().rand().$_FILES['subtitleFile']['name'][$i]);
								$uploadPath = META_ARTICLE_UPLOAD_PATH.$fileName;
								@move_uploaded_file($_FILES['subtitleFile']['tmp_name'][$i], $uploadPath);
							}
							else continue;
							$subtitle_data['subtitleFile'] = $fileName;
							$subtitle_data['weight'] = rand();
							$subtitle_data['postedTime'] = time();
							$this->userdata->insertPostMetaSeriesVideoSubtitles($subtitle_data);

						}

					}
				}

				unset($_FILES['subtitleFile']);

				foreach ($_FILES as $key => $value)
				{
					$metaDataFiles = array();
					if($_FILES[$key]['size'] > 0)
					{
						$file_name = time().$_FILES[$key]['name'];
						$file_name = str_replace(" ", "-", $file_name);
						$upload_path = META_ARTICLE_UPLOAD_PATH.$file_name;
						@move_uploaded_file($_FILES[$key]['tmp_name'], $upload_path);
                        /** Making A secure copy **/
                        $this->secureUploadedFile(META_ARTICLE_UPLOAD_PATH,$file_name);
						@unlink(META_ARTICLE_UPLOAD_PATH.$input_data[$key.'path']);
						@unlink(SECURED_POST_FILES.$input_data[$key.'path']);
						$metaDataFiles['postID'] = $id;
						$metaDataFiles['slugname'] = $key;
						$fieldSlug = str_replace('cat_', '', $key);
						$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
						$metaDataFiles['fieldType'] = $type;
						$metaDataFiles['type'] = 'cat';
						$metaDataFiles['postedTime'] = time();
						$metaDataFiles['slugvalue'] = $file_name;
						$metaDataFiles['episode_id'] = $episode_id;


						$this->userdata->insertPostmetaSeries($metaDataFiles);

					}
					else
					{
						$metaDataFiles = array();
						$metaDataFiles['slugvalue'] = $input_data[$key.'path'];
						$metaDataFiles['postID'] = $id;
						$metaDataFiles['slugname'] = $key;
						$fieldSlug = str_replace('cat_', '', $key);
						$type = $this->userdata->grabCategoryAttribute(array('fieldSlug' => $fieldSlug))->type;
						$metaDataFiles['fieldType'] = $type;
						$metaDataFiles['type'] = 'cat';
						$metaDataFiles['postedTime'] = time();
						$metaDataFiles['episode_id'] = $episode_id;

						$this->userdata->insertPostmetaSeries($metaDataFiles);
					}
				}
			}
		}
		redirect(base_url('post-article/'.$post_id));
	}

	public function deleteSubtitleFile()
	{
		$input_data = $this->input->get();
		$subtitle_data = $this->userdata->grabPostMetaVideoSubtitles(array('id' => $input_data['id']));
		@unlink(META_ARTICLE_UPLOAD_PATH.$subtitle_data->subtitleFile);
		$this->userdata->deletePostMetaVideoSubtitles(array('id' => $input_data['id']));
		
		redirect(base_url('post-article/'.$input_data['postID']));
	}

	public function deleteSubtitleFileSeries()
	{
		$input_data = $this->input->get();
		$subtitle_data = $this->userdata->grabPostMetaVideoSeriesSubtitles(array('id' => $input_data['id']));
		@unlink(META_ARTICLE_UPLOAD_PATH.$subtitle_data->subtitleFile);
		$this->userdata->deletePostMetaVideoSeriesSubtitles(array('id' => $input_data['id']));

		redirect(base_url('post-article/'.$input_data['postID']));
	}

    public function doRender()
    {
        header("Content-Type: image/png");
        return "assets/images/ads1.png";
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */