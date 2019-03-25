<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class gpluscontroller extends CI_Controller {

	public $data=array();
	function __construct()
	{
		parent::__construct();
		$this->load->model('userdata');
	}
	public function gpluslogin()
	{
		@session_start();
		require_once APPPATH .'libraries/google-api-php-client-master/src/Google/autoload.php';
		$client_id = $this->config->item('client_id','googleplus');
		$client_secret = $this->config->item('client_secret','googleplus');
		$redirect_uri = $this->config->item('redirect_uri','googleplus');
		$simple_api_key = $this->config->item('api_key','googleplus');
		
		// Create Client Request to access Google API
		$client = new Google_Client();
		$client->setApplicationName("PHP Google OAuth Login Example");
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setDeveloperKey($simple_api_key);
		$client->addScope("https://www.googleapis.com/auth/userinfo.email");
		
		// Send Client Request
		$objOAuthService = new Google_Service_Oauth2($client);
		$code = $this->input->get('code');
		if (isset($code)) 
		{
			$client->authenticate($code);
			if($client->getAccessToken())
			{
				$user_profile = $objOAuthService->userinfo->get();
				$name = $user_profile['name'];
				$email = $user_profile['email'];
				
				// save image from URL
					$profile_image_name = md5(time()).'.jpg';
					$img = UPLOAD_PATH_URL.$profile_image_name;
					file_put_contents($img, file_get_contents($user_profile['picture']));
					// Ends
				
				
				$user_cond = array();
				$user_cond['emailAddress'] = $email;
				$user_det = $this->userdata->grabUserData($user_cond);
				if(count($user_det) > 0)
				{
					if($name != '')
					{
						$usr_data = array();
						$usr_data['name'] = $name;
						$usr_data['firstName'] = $user_profile['givenName'];
						$usr_data['lastName'] = $user_profile['familyName'];
						$usr_data['prifile_picture']= $profile_image_name;
						$usr_data['status'] = 'Y';
						$this->userdata->updateUser($usr_data,$user_cond);
					}
					
					$user_id = $user_det->id;
				}
				else
				{
					$usr_data = array();
					$usr_data['name'] = $name;
					$usr_data['firstName'] = $user_profile['givenName'];
					$usr_data['lastName'] = $user_profile['familyName'];
					$usr_data['emailAddress'] = $email;
					$usr_data['prifile_picture']= $profile_image_name;
					$usr_data['userType'] = 3;
					$usr_data['status'] = 'Y';
					$usr_data['postedtime'] = time();
					$usr_data['ipaddress'] = $_SERVER["REMOTE_ADDR"];
					$user_id = $this->userdata->insertUser($usr_data);
					$user_cond['id'] =$user_id;
				}
				$user_det = $this->userdata->grabUserData($user_cond);
				$this->userdata->saveLoginLog($user_det->id);
				$this->defaultdata->setLoginSession($user_det);
				//$_SESSION['googleplus'] = "1";
				$_SESSION['user_id_session'] = $user_id;
				redirect(base_url('my-account'));
			}
			else
			{
				redirect(base_url('register'));
			}
		}
		else
		{
			redirect(base_url('register'));
		}
	}
}
/* End of file gpluscontroller.php */
/* Location: ./application/controllers/gpluscontroller.php */