<?php
class Userdata extends CI_Model {

	private $data=array();
	private $table = TABLE_USER;
	function __construct(){
		parent::__construct();
	}

	public function insertUser($user_data = array()){
		if(count($user_data) > 0){
			$this->db->insert($this->table, $user_data);
			return $this->db->insert_id();
		}else
			return 0;
	}

	public function getEmailTemplate($type = ''){
		$this->db->where('slug',$type);
		$query = $this->db->get(TABLE_EMAIL_TEMPLATE);
		$mail_data = $query->row();
		return $mail_data;
	}

	public function UpdateUserPlan($data,$cond){
		if(count($data) > 0){
			$this->db->where($cond);
			$this->db->update('com_user_plan', $data);
		}
		return 1;
	}

	public function getTotalCount($type = 0){
		if($type > 4 || $type < 1) $type = 0;
		$this->db->select('id');
		if($type != 0){
			$this->db->where('userType', $type);
		}
		$query = $this->db->get('com_user');
		return $query->num_rows();
	}

	public function getMaleFemaleCount($type = 'M'){
		if($type != 'M' && $type != 'F') $type = 'M';
		$this->db->select('id');
		$this->db->where('gender', $type);
		$query = $this->db->get('com_user');
		return $query->num_rows();
	}

	public function getCategoryArticleData(){
		$cat_data = [];
		$category = $this->getCategory(array('parentID' => 0));
		if(!empty($category)){
			foreach($category as $cat){
				$tot_active = $tot_deactive = $tot_pending = 0;
				if($cat->type == 'VID'){
					$subcategory = $this->getCategory(array('parentID' => $cat->id));
					if(!empty($subcategory)){
						foreach($subcategory as $subcat){
							$tot_active = $this->getCountFromCategory(array('categoryID'=>$cat->id,'subCategoryID'=>$subcat->id, 'status' => 'Y', 'is_delete' => 'N'));
							$tot_deactive = $this->getCountFromCategory(array('categoryID'=>$cat->id,'subCategoryID'=>$subcat->id, 'status' => 'N', 'is_delete' => 'N'));
							$tot_pending = $this->getCountFromCategory(array('categoryID'=>$cat->id,'subCategoryID'=>$subcat->id, 'status' => 'P', 'is_delete' => 'N'));
							$cat_data[$subcat->title] = array($tot_active, $tot_deactive, $tot_pending);
						}
					} else 
					$cat_data[$cat->title] = array($tot_active, $tot_deactive, $tot_pending);
				} else {
					$tot_active = $this->getCountFromCategory(array('categoryID'=>$cat->id, 'status' => 'Y', 'is_delete' => 'N'));
					$tot_deactive = $this->getCountFromCategory(array('categoryID'=>$cat->id, 'status' => 'N', 'is_delete' => 'N'));
					$tot_pending = $this->getCountFromCategory(array('categoryID'=>$cat->id, 'status' => 'P', 'is_delete' => 'N'));

					$cat_data[$cat->title] = array($tot_active, $tot_deactive, $tot_pending);
				}
			}
		}
		return $cat_data;
	}

	public function getCountFromCategory($cond = []){
		if(!empty($cond)){
			$this->db->select('id');
			$this->db->from('com_main_post_article');
			$this->db->where($cond);
			$query = $this->db->get();
			return $query->num_rows();
		} else return 0;

	}

	public function getMaleFemaleSkillCountData(){
		$skill_data = [];
		$pofession = (array) $this->getProfessions();
		if(!empty($pofession)){
			foreach($pofession as $data){
				$this->db->select('user.gender');
				$this->db->from('com_user'.' as user');
				$this->db->join('com_user_profession'.' as pro','pro.user_id = user.id');

				$this->db->where(array('pro.profession_id'=>$data->id, 'user.gender' => 'M'));
				$query = $this->db->get();
				$tot_male = $query->num_rows();
				
				$this->db->select('user.gender');
				$this->db->from('com_user'.' as user');
				$this->db->join('com_user_profession'.' as pro','pro.user_id = user.id');
				$this->db->where(array('pro.profession_id'=>$data->id, 'user.gender' => 'F'));
				$query = $this->db->get();
				$tot_female = $query->num_rows();

				$skill_data[] = array($data->title, $tot_male, $tot_female);
				
			}
		}
		return $skill_data;
	}

	public function getProfessions($cond = array()){
		return $this->db->select()->from(TABLE_PROFESSION)->where($cond)->order_by('weight')->get()->result();
	}

	public function getLoginCount(){
		$this->db->where('login_status', 1);
		$this->db->group_by('uid');
		$query = $this->db->get('com_userlogin');
		return $query->num_rows();
	}

	public function grabUserData($user_cond = array()){
		$return_data = array();
		if(count($user_cond) > 0){
			$query = $this->db->get_where($this->table,$user_cond);
			$return_data = $query->row();
		}
		return $return_data;
	}

	public function getUserPlansFullDetails($uid){
		$return_data = array();
		if(isset($uid) && $uid != 0 && $uid != ''){
			$this->db->from('com_user_plan'.' as uplan');
			$this->db->join('com_pricing'.' as pricing','uplan.plan_id = pricing.id');
			$this->db->where(array('uplan.user_id'=>$uid,'uplan.status'=>'Y'));
			$query = $this->db->get();
			$return_data = $query->row();
			if(!empty($return_data)){
				unset($return_data->stripe_resp_month);
				unset($return_data->stripe_resp_year);
				
				$return_data->pay_price = ($return_data->plan_type == 'M'? $return_data->plan_price_month : $return_data->plan_price_year);
				$return_data->plan_name = $return_data->plan_name . ' (1 '.($return_data->plan_type == 'M'? 'MONTH' : 'YEAR').')';
			}
		}
		return $return_data;		
	}

	public function getAllStripePlans(){
		$cond = array('plan_status'=>'Y');
		$planArray = $this->db->select('stripe_resp_month,stripe_resp_year')->from('com_pricing')->where($cond)->get()->result_array();
		$id = array();
		if(!empty($planArray)){
			foreach($planArray as $array){
				$month_id = json_decode($array['stripe_resp_month'])->id;
				$year_id = json_decode($array['stripe_resp_year'])->id;
				$id[] = $month_id;
				$id[] = $year_id;
			}
		}
		return $id;
	}
	public function getAllUsers($cond){
		$this->db->where($cond);
		$this->db->order_by('id','desc');
		$query = $this->db->get('com_user');
		return $query->result_array();
	}
	public function getProjects($cond){
		$this->db->where($cond);
		$this->db->order_by('id','desc');
		$query = $this->db->get(TABLE_MAIN_POST_ARTICLE);	
		return $query->result_array();
	}
	public function getCountry($cond){
		$this->db->where($cond);
		$this->db->group_by('typeID');
		$query = $this->db->get('com_countries');
		return $query->result_array();
	}
	public function updateStripePlanTrialPeriod($data){	
		$stripe_api_key = $this->db->select('stripe_api_key')->from(TABLE_GENERAL_SETTINGS)->get()->row()->stripe_api_key;	
		require APPPATH .'libraries/stripe/init.php';
		\Stripe\Stripe::setApiKey($stripe_api_key);
		if(!empty($data) && $data != ''){
			$getPlansId = $this->getAllStripePlans();
			if(!empty($getPlansId)){
				foreach($getPlansId as $id){
					try{
						$plan = \Stripe\Plan::retrieve($id);
						$plan->trial_period_days = $data;
						$plan->save();
					}catch(Exception $e){
						return false;
					}					
				}
			}
		}
		return true;
	}

	public function getAdmins(){
		$this->db->select('id, name, admin_userName, admin_password');
		//$query = $this->db->get_where($this->table, $condi);
		$query = $this->db->get('com_adminuser');
		return $query->result_array();
	}

	public function grabLoginUserData($where_arr = array()){
		$return_data = array();
		if(count($where_arr) > 0){
			$this->db->where($where_arr);
			$query = $this->db->get($this->table);
			$return_data = $query->row();
		}
		return $return_data;
	}

	public function getActivationEmailTemplate(){
		$query = $this->db->get(TABLE_EMAIL_ACTIVATION);
		$mail_data = $query->row();
		return $mail_data;
	}

	public function updateUser($data,$condition){
		$this->db->update('com_user', $data, $condition);
	}
	public function createStripeUser($user_data){
		$stripe_api_key = $this->db->select('stripe_api_key')->from(TABLE_GENERAL_SETTINGS)->get()->row()->stripe_api_key;
		require ('../'.APPPATH .'libraries/stripe/init.php');
		
		\Stripe\Stripe::setApiKey($stripe_api_key);
		$this->setCustomerOnStripe($user_data);
		$update_data = array('status' => 'Y','hastoken' => '','otp'=>'');
		$condition = array('id' => $user_data->id);
		$this->updateUser($update_data,$condition);
	}

	public function setCustomerOnStripe($user_data){
		try{
			$resp = \Stripe\Customer::create(array(
				"email" => $user_data->emailAddress,
				"description" => $user_data->firstName." ".$user_data->lastName ." Customer for Webblywood."
			));
			$dataResp  = $resp->__toJSON();
			$update_data = array('stripe_id'=> $resp->id, 'stripe_resp' => $dataResp);
			$condition = array('id' => $user_data->id);
			$this->updateUser($update_data,$condition);
			return true;
		}catch(Exception $e){
			return false;
		}
		
	}
	

	// public function updateUserProvider($data,$condition){
	// 	$this->db->update(TABLE_USER_SERVICE_PROVIDER, $data, $condition);
	// }

	// public function saveLoginLog($id){
	// 	$cond = array('uid' => $id);
	// 	$usr_data = $this->db->get_where(TABLE_USERLOGIN,$cond)->row();
	// 	if(count($usr_data) > 0){
	// 		$up_data = array('lastlogintime' => time(),'ipaddress' => $_SERVER["REMOTE_ADDR"],'login_status'=>1);
	// 		$cond = array('uid' => $id);
	// 		$this->db->where($cond);
	// 		$this->db->update(TABLE_USERLOGIN, $up_data);
	// 	}else{
	// 		$in_data = array('uid' => $id,'lastlogintime' => time(),'ipaddress' => $_SERVER["REMOTE_ADDR"],'login_status'=>1);
	// 		$this->db->insert(TABLE_USERLOGIN,$in_data);
	// 	}
	// }

	// public function getWorkCategories($limit = array()){
	// 	$this->db->order_by('weight','ASC');
	// 	if(count($limit) > 0){
	// 		if($limit['count'] != 0){
	// 			$this->db->limit($limit['count'], $limit['start']);
	// 		}
	// 	}
	// 	$query = $this->db->get(TABLE_WORK_CATEGORIES);
	// 	return $query->result();
	// }

	// public function getWorkHomeCategories($limit = array()){
	// 	$this->db->where('show_home','Y');
	// 	$this->db->order_by('weight','ASC');
	// 	if(count($limit) > 0){
	// 		if($limit['count'] != 0){
	// 			$this->db->limit($limit['count'], $limit['start']);
	// 		}
	// 	}
	// 	$query = $this->db->get(TABLE_WORK_CATEGORIES);
	// 	return $query->result();
	// }

	// public function getWorkDistances(){
	// 	$this->db->order_by('distance','ASC');
	// 	$query = $this->db->get(TABLE_WORK_DISTANCES);
	// 	return $query->result();
	// }

	// public function insertWorkerCat($user_data = array()){
	// 	if(count($user_data) > 0){
	// 		$this->db->insert(TABLE_USER_TO_CAT, $user_data);
	// 		return $this->db->insert_id();
	// 	}else
	// 		return 0;
	// }

	// public function deleteUserCat($del_cond){
	// 	$this->db->where($del_cond);
	// 	$this->db->delete(TABLE_USER_TO_CAT);
	// }

	// public function getTaskFixedPrice(){
	// 	$this->db->order_by('fixed_price_id','ASC');
	// 	$query = $this->db->get(TABLE_TASK_FIXED_PRICE);
	// 	return $query->result();
	// }

	// public function grabTaskFixedPrice($price_id){
	// 	$this->db->where('fixed_price_id',$price_id);
	// 	$query = $this->db->get(TABLE_TASK_FIXED_PRICE);
	// 	return $query->row();
	// }

	// public function getTaskHourPrice(){
	// 	$this->db->order_by('hour_price_id','ASC');
	// 	$query = $this->db->get(TABLE_TASK_HOUR_PRICE);
	// 	return $query->result();
	// }

	// public function grabTaskHourPrice($price_id){
	// 	$this->db->where('hour_price_id',$price_id);
	// 	$query = $this->db->get(TABLE_TASK_HOUR_PRICE);
	// 	return $query->row();
	// }

	// public function getTaskHourDuration(){
	// 	$this->db->order_by('duration_id','ASC');
	// 	$query = $this->db->get(TABLE_TASK_DURATION);
	// 	return $query->result();
	// }

	public function insertTask($user_data = array()){
		if(count($user_data) > 0){
			$this->db->insert(TABLE_TASKS, $user_data);
			return $this->db->insert_id();
		}else
			return 0;
	}

	// public function insertTaskFiles($user_data = array()){
	// 	if(count($user_data) > 0){
	// 		$this->db->insert(TABLE_TASK_FILES, $user_data);
	// 		return $this->db->insert_id();
	// 	}else
	// 		return 0;
	// }
	
	// public function getUserWorkCategories($uid = 0){
	// 	$return_data = array();
	// 	if($uid != 0){
	// 		$this->db->from(TABLE_USER_TO_CAT.' as utocat');
	// 		$this->db->join(TABLE_WORK_CATEGORIES.' as wc','utocat.cat_id = wc.cat_id');
	// 		$this->db->where('utocat.user_id',$uid);
	// 		$this->db->order_by('wc.weight','ASC');
	// 		$query = $this->db->get();
	// 		$return_data = $query->result();
	// 	}
	// 	return $return_data;
	// }

	// public function insertPortfolio($user_data = array()){
	// 	if(count($user_data) > 0){
	// 		$this->db->insert(TABLE_USER_PORTFOLIO, $user_data);
	// 		return $this->db->insert_id();
	// 	}else
	// 		return 0;
	// }

	// public function getAllPortfolio($user_id = ''){
	// 	if($user_id != ''){
	// 		$this->db->where('user_id',$user_id);
	// 		$this->db->order_by('portfolio_id','ASC');
	// 		$query = $this->db->get(TABLE_USER_PORTFOLIO);
	// 		return $query->result();
	// 	}else
	// 		return array();
	// }

	// public function grabPortfolio($portfolio_id = ''){
	// 	if($portfolio_id != ''){
	// 		$this->db->where('portfolio_id',$portfolio_id);
	// 		$query = $this->db->get(TABLE_USER_PORTFOLIO);
	// 		return $query->row();
	// 	}else
	// 		return array();
	// }

	// public function updatePortfolio($data,$condition){
	// 	$this->db->update(TABLE_USER_PORTFOLIO, $data, $condition);
	// }

	// public function deletePortfolio($del_cond){
	// 	$this->db->where($del_cond);
	// 	$this->db->delete(TABLE_USER_PORTFOLIO);
	// }

	// public function insertUserSkills($user_data = array()){
	// 	if(count($user_data) > 0){
	// 		$this->db->insert(TABLE_USER_SKILLS, $user_data);
	// 		return $this->db->insert_id();
	// 	}else
	// 		return 0;
	// }

	// public function getAllSkills($user_id = ''){
	// 	if($user_id != ''){
	// 		$this->db->where('user_id',$user_id);
	// 		$this->db->order_by('skill_id','ASC');
	// 		$query = $this->db->get(TABLE_USER_SKILLS);
	// 		return $query->result();
	// 	}else
	// 		return array();
	// }

	// public function deleteSkills($del_cond){
	// 	$this->db->where($del_cond);
	// 	$this->db->delete(TABLE_USER_SKILLS);
	// }

	// public function insertUserTransaction($user_data = array()){
	// 	if(count($user_data) > 0){
	// 		$this->db->insert(TABLE_USER_TRANSACTIONS, $user_data);
	// 		return $this->db->insert_id();
	// 	}else
	// 		return 0;
	// }

	// public function updateUserTransaction($data,$condition){
	// 	$this->db->update(TABLE_USER_TRANSACTIONS, $data, $condition);
	// }

	// public function grabUserTransaction($cond = array()){
	// 	if(count($cond) > 0){
	// 		$this->db->where($cond);
	// 		$query = $this->db->get(TABLE_USER_TRANSACTIONS);
	// 		return $query->row();
	// 	}else
	// 		return array();
	// }

	// public function getUserAllTransaction($cond = array(),$limit = array()){
	// 	$tr_history = array();
	// 	if(count($cond) > 0){
	// 		$this->db->where($cond);
	// 		$this->db->order_by('postedtime','desc');
	// 		if(count($limit) > 0){
	// 			$this->db->limit($limit['count'], $limit['start']);
	// 		}
	// 		$query = $this->db->get(TABLE_USER_TRANSACTIONS);
	// 		$tr_history = $query->result();
	// 		if(count($tr_history) > 0){
	// 			$this->load->model('taskdata');
	// 			for($i=0;$i<count($tr_history);$i++){
	// 				if($tr_history[$i]->transaction_type == 1 || $tr_history[$i]->transaction_type == 4){
	// 					$task_cond = array('task_id' => $tr_history[$i]->task_id);
	// 					$tr_history[$i]->task_det = $this->taskdata->grabTaskDet($task_cond);
	// 					if($tr_history[$i]->transaction_type == 1){
	// 						$bid_ms_cond = array('bid_milestone_id' => $tr_history[$i]->bid_ms_id);
	// 						$tr_history[$i]->bid_ms_det = $this->taskdata->grabBidMilestone($bid_ms_cond);
	// 						$bid_cond = array('bid_id' => $tr_history[$i]->bid_ms_det->bid_id);
	// 						$tr_history[$i]->bid_det = $this->taskdata->grabBidDet($bid_cond);
	// 						$tasker_cond = array('id' => $tr_history[$i]->bid_det->tasker_id);
	// 						$tr_history[$i]->bid_det->tasker_det = $this->grabUserData($tasker_cond);
	// 					} elseif($tr_history[$i]->transaction_type == 4){
	// 						$bid_cond = array('task_id' => $tr_history[$i]->task_id,'bid_status' => 'C');
	// 						$tr_history[$i]->bid_det = $this->taskdata->grabBidDet($bid_cond);
	// 						$tasker_cond = array('id' => $tr_history[$i]->bid_det->tasker_id);
	// 						$tr_history[$i]->bid_det->tasker_det = $this->grabUserData($tasker_cond);
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return $tr_history;
	// }

	// public function getCountUserAllTransaction($cond = array()){
	// 	$count_tr_hs = 0;
	// 	if(count($cond) > 0){
	// 		$this->db->where($cond);
	// 		$this->db->order_by('postedtime','desc');
	// 		$query = $this->db->get(TABLE_USER_TRANSACTIONS);
	// 		$count_tr_hs = $query->num_rows();
	// 	}
	// 	return $count_tr_hs;
	// }

	// public function insertUserIdentityDocs($user_data = array()){
	// 	if(count($user_data) > 0){
	// 		$this->db->insert(TABLE_USER_IDENTITY_DOCS, $user_data);
	// 		return $this->db->insert_id();
	// 	}else
	// 		return 0;
	// }

	// public function insertWithdrawAccount($user_data = array()){
	// 	if(count($user_data) > 0){
	// 		$this->db->insert(TABLE_USER_WITHDRAW_ACCOUNT, $user_data);
	// 		return $this->db->insert_id();
	// 	}else
	// 		return 0;
	// }
	/********************       ****************************/
	// public function getLoginUsers($condarr=array()){
	// 	if($condarr['lastlogintime']!=""){
	// 		$order_by=$this->db->order_by('lastlogintime',$condarr['lastlogintime']);
	// 	}
	// 	unset($condarr['lastlogintime']);
	// 	$query = $order_by;
	// 	$query = $this->db->get_where(TABLE_USERLOGIN,$condarr);
		
	// 	$return_data = $query->result();
	// 	//$return_data = $this->db->last_query();
	// 	//echo $return_data;exit;
	// 	return $return_data;
	// }

	// public function grabLoginUsers($condarr=array()){
	// 	$query = $this->db->get_where(TABLE_USERLOGIN,$condarr);
	// 	$return_data = $query->row();
	// 	//echo $this->db->last_query();
	// 	return $return_data;
	// }

	// public function updateLoginUser($up_data=array()){
	// 	$this->db->where('uid', $this->session->userdata('usrid'));
	// 	$this->db->update(TABLE_USERLOGIN, $up_data);
	// }

	public function updateLoginUser($up_data=array(), $uid = 0){
		$this->db->where('uid',$uid);
		$this->db->update(TABLE_USERLOGIN, $up_data);
	}

	// public function insertProfession($input_data = array()){
	// 	if(empty($input_data)) die("Some data missing to insert.");
	// 	$this->db->insert(TABLE_USER_PROFESSION,$input_data);
	// 	return $this->db->insert_id();
	// }

	// public function deleteUserProfession($cond = array()){
	// 	if(empty($cond)) die("Some data missing to insert.");
	// 	$this->db->delete(TABLE_USER_PROFESSION,$cond);
	// }
	//¶09092015 S

	// public function grabUserProfession($cond = array()){
	// 	return $this->db->select('profession_id, title')->from(TABLE_PROFESSION)->join(TABLE_USER_PROFESSION,TABLE_USER_PROFESSION.".profession_id = ".TABLE_PROFESSION.".id")->where($cond)->get()->result();
	// }

	public function getCategory($cond = array()){
		return $this->db->select()->from(TABLE_CATEGORY)->where($cond)->get()->result();
	}

	public function deleteUserAllToken($cond){
		if(!empty($cond)){
			$this->db->delete('token',$cond);
		}
	}

	// public function garbCategory($cond = array()){
	// 	return $this->db->select()->from(TABLE_CATEGORY)->where($cond)->get()->row();
	// }
	
	// public function getCategoryAttr($cond = array()){
	// 	return $this->db->select()->from(TABLE_CATEGORYATTR)->where($cond)->order_by('weight')->get()->result();
	// }

	//¶28092015 S
	// public function insertMainArticles($input_data = array()){
	// 	$this->db->insert(TABLE_MAIN_POST_ARTICLE,$input_data);
	// 	return $this->db->insert_id();
	// }

	// public function deleteMainArticle($cond = array()){
	// 	if(count($cond) <= 0)return;
	// 	$this->db->delete(TABLE_MAIN_POST_ARTICLE,$cond);
	// }

	// public function getMainArticle($cond = array()){
	// 	return $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->get()->result();
	// }
	
	// public function grabMainArticle($cond = array()){
	// 	return $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->get()->row();
	// }

	// public function updateMainArticle($input_data = array(),$cond = array()){
	// 	$this->db->update(TABLE_MAIN_POST_ARTICLE,$input_data,$cond);
	// }

	//////////////////////////////////////////////////////////
	public function insertPostMeta($input_data = array()){
		$this->db->insert(TABLE_META_ARTICLE,$input_data);
		return $this->db->insert_id();
	}

	public function deletePostMeta($cond = array()){
		if(count($cond) <= 0)return;
		$this->db->delete(TABLE_META_ARTICLE,$cond);
	}

	public function getPostMeta($cond = array()){
		return $this->db->select()->from(TABLE_META_ARTICLE)->where($cond)->get()->result();
	}

	public function grabPostMeta($cond = array()){
		return $this->db->select()->from(TABLE_META_ARTICLE)->where($cond)->get()->row();
	}

	public function updatePostMeta($input_data = array(),$cond = array()){
		$this->db->update(TABLE_META_ARTICLE,$input_data,$cond);
	}

	public function grabCategoryAttribute($cond = array()){
		return $this->db->select()->from(TABLE_CATEGORYATTR)->where($cond)->get()->row();
	}
	//¶14102015 S
	public function articleDataProvider($post_id,$fieldSlug,$type){
		$cond = array();
		$cond['postID'] = $post_id;
		$cond['slugname'] = $fieldSlug;
		if($cond['slugname'] != 'cat_duration'){
			$cond['fieldType'] = $type;
		}
		return $this->db->select()->from(TABLE_META_ARTICLE)->where($cond)->get()->row();
	}

	//¶15102015 S
	public function insertPostMetaVideoSubtitles($input_data = array()){
		if(count($input_data) > 0){
			$this->db->insert(TABLE_POSTMETA_VIDEO_SUBTITLE,$input_data);
			return $this->db->insert_id();
		}else{
			return 0;
		}
	}

	public function getPostMetaVideoSubtitles($cond = array()){
		return $this->db->select()->from(TABLE_POSTMETA_VIDEO_SUBTITLE)->where($cond)->get()->result();
	}

	public function grabPostMetaVideoSubtitles($cond = array()){
		return $this->db->select()->from(TABLE_POSTMETA_VIDEO_SUBTITLE)->where($cond)->get()->row();
	}

	public function deletePostMetaVideoSubtitles($cond = array()){
		$this->db->delete(TABLE_POSTMETA_VIDEO_SUBTITLE,$cond);
	}

	//¶26102015 S
	public function getCountries($cond = array()){
		return $this->db->select()->from(TABLE_COUNTRIES)->where($cond)->get()->result();
	}

	public function grabCountries($cond =array()){
		return $this->db->select()->from(TABLE_COUNTRIES)->where($cond)->get()->row();
	}

	//¶19112015 S
	public function getpostmetaSeries($cond = array()){
		return $this->db->select()->from(TABLE_POSTMETA_SERIES)->where($cond)->group_by('episode_id')->order_by('id desc')->get()->result();
	}
	public function grabPostmetaSeries($post_id,$episode_id,$fieldSlug,$type){
		$cond = array();
		$cond['postID'] = $post_id;
		$cond['slugname'] = $fieldSlug;
		$cond['episode_id'] = $episode_id;
		$cond['fieldType'] = $type;
		return $this->db->select()->from(TABLE_POSTMETA_SERIES)->where($cond)->get()->row();
	}

	public function insertPostmetaSeries($input_data = array()){
		if(empty($input_data)) return 0;
		$this->db->insert(TABLE_POSTMETA_SERIES,$input_data);
		return $this->db->insert_id();
	}

	public function deletePostmetaSeries($cond = array()){
		$this->db->delete(TABLE_POSTMETA_SERIES , $cond);
	}

	public function insertPostMetaSeriesVideoSubtitles($input_data = array()){
		if(count($input_data) > 0){
			$this->db->insert(TABLE_POSTMETA_VIDEO_SERIES_SUBTITLE,$input_data);
			return $this->db->insert_id();
		}else{
			return 0;
		}
	}

	public function getPostMetaVideoSeriesSubtitles($cond = array()){
		return  $this->db->select()->from(TABLE_POSTMETA_VIDEO_SERIES_SUBTITLE)->where($cond)->get()->result();
	}

	public function grabPostMetaVideoSeriesSubtitles($cond = array()){
		return $this->db->select()->from(TABLE_POSTMETA_VIDEO_SERIES_SUBTITLE)->where($cond)->get()->row();
	}

	public function deletePostMetaVideoSeriesSubtitles($cond = array()){
		$this->db->delete(TABLE_POSTMETA_VIDEO_SERIES_SUBTITLE,$cond);
	}

	public function insertHistory($user_data = array()){
		if(count($user_data) > 0){
			$this->db->insert(TABLE_USER_SEARCH_HISTORY, $user_data);
			return $this->db->insert_id();
		}else
			return 0;
	}
}
?>