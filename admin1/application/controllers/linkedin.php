<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Linkedin extends CI_Controller {

	public $data=array();
	function __construct()
	{
		parent::__construct();
		$this->load->model('userdata');
	}
	
	public function linkedinlogin()
	{
		$user_profile = $this->input->post();
		$return_data = array();
		$return_data['has_error'] = 1;
		if(isset($user_profile['emailAddress']))
		{
			$name = $user_profile['firstName'].' '.$user_profile['lastName'];
			$email = $user_profile['emailAddress'];
			$user_cond = array();
			$user_cond['emailAddress'] = $email;
			$user_det = $this->userdata->grabUserData($user_cond);
			if(count($user_det) > 0)
			{
				$usr_data = array();
				$usr_data['status'] = 'Y';
				$this->userdata->updateUser($usr_data,$user_cond);
				if($user_det->userName == '')
				{
					$email_arr = explode('@',$email);
					$userName = url_title($email_arr[0],'-',true);
					$usr_data = array();
					$usr_data['userName'] = getRandomUserName($userName);
					$this->userdata->updateUser($usr_data,$user_cond);
				}
			}
			else
			{
				$usr_data = array();
				$email_arr = explode('@',$email);
				$userName = url_title($email_arr[0],'-',true);
				$usr_data['userName'] = getRandomUserName($userName);
				$usr_data['name'] = $name;
				$usr_data['firstName'] = $user_profile['firstName'];
				$usr_data['lastName'] = $user_profile['lastName'];
				$usr_data['emailAddress'] = $email;
				$usr_data['userType'] = 2;
				$usr_data['status'] = 'Y';
				$usr_data['postedtime'] = time();
				$usr_data['ipaddress'] = $_SERVER["REMOTE_ADDR"];
				$user_id = $this->userdata->insertUser($usr_data);
				$user_cond['id'] =$user_id;
			}
			$user_det = $this->userdata->grabUserData($user_cond);
			$this->userdata->saveLoginLog($user_det->id);
			$this->defaultdata->setLoginSession($user_det);
			
			$return_data['has_error'] = 0;
			$return_data['redirect_url'] = base_url('my-account');
		}
		else
		{
			$return_data['has_error'] = 1;
		}
		echo json_encode($return_data);
	}
}

/* End of file linkedincontroller.php */
/* Location: ./application/controllers/linkedincontroller.php */