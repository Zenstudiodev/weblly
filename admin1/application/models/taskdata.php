<?php
class Taskdata extends CI_Model {

	private $data=array();
	private $table = TABLE_TASKS;
	function __construct()
	{
		parent::__construct();
	}
	public function getAllTask($categories = array(),$not_usrid = 0,$limit = array(),$distance_arr = array(),$search_key = '')
	{	//$distance_arr = array();
		if(count($distance_arr) > 0)
		{
			$lat = $distance_arr['lat'];
			$lng = $distance_arr['lng'];
			$distance = $distance_arr['distance'];
			
			$subsql1 = "";
			$subsql2 = "";
			$subsql3 = "";
			$subsql4 = "";
			if(count($categories))
			{
				$cat_str = implode(',',$categories);
				$subsql1 = " AND tsk.category_id IN(".$cat_str.")";
			}
			if($not_usrid != 0)
			{
				$subsql2 = " AND tsk.user_id !=".$not_usrid;
			}
			if(count($limit) == 0)
			{
				$limit['count'] = 10;
				$limit['start'] = 0;
			}
			if($search_key != '')
			{
				if(is_numeric($search_key))
				{
					$subsql3 = " AND tsk.task_id=".$search_key;
				}
				else
				{
					$subsql3 = " AND tsk.task_name LIKE '%".$search_key."%'";
				}
			}
			
			$subsql4 = " AND tsk.is_online_phone!='Y'";
			$subsql5 = " AND tsk.is_online_phone='Y'";
				
			$admin_settings = $this->defaultdata->grabSettingData();
			$task_dur = $admin_settings->task_show_duration;
			$last_tsk_dur_time = time() -($task_dur * 24 * 60 * 60);
			
			$sql = "SELECT * FROM((SELECT *,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc WHERE tsk.category_id = wc.cat_id".$subsql1.$subsql2.$subsql3.$subsql4." AND tsk.posted_time>=".$last_tsk_dur_time." AND tsk.task_status='Y' having distance<='".$distance."') UNION (SELECT *,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc WHERE tsk.category_id = wc.cat_id".$subsql1.$subsql2.$subsql3.$subsql5." AND tsk.posted_time>=".$last_tsk_dur_time." AND tsk.task_status='Y')) AS ALLTSK ORDER BY ALLTSK.posted_time DESC LIMIT ".$limit['start'].",".$limit['count'];
			$query = $this->db->query($sql);
			$task_arr = $query->result();
			if(count($task_arr) > 0)
			{
				for($i = 0;$i < count($task_arr); $i++)
				{
					$task_arr[$i]->is_fav = 0;
					$task_arr[$i]->is_fav = $this->checkFav($task_arr[$i]->task_id);
				}
			}
			return $task_arr;
		}
		else
		{
			if(count($limit) == 0)
			{
				$limit['count'] = 10;
				$limit['start'] = 0;
			}
			$this->db->from($this->table.' as tsk');
			$this->db->join(TABLE_WORK_CATEGORIES.' wc','tsk.category_id = wc.cat_id');
			if(count($categories) > 0)
			{
				$this->db->where_in('tsk.category_id',$categories);
			}
			if($not_usrid != 0)
			{
				$this->db->where('tsk.user_id !=',$not_usrid);
			}
			$this->db->limit($limit['count'], $limit['start']);
			$this->db->order_by('tsk.posted_time','desc');
			$query = $this->db->get();
			$task_arr = $query->result();
			if(count($task_arr) > 0)
			{
				for($i = 0;$i < count($task_arr); $i++)
				{
					$task_arr[$i]->is_fav = 0;
					$task_arr[$i]->is_fav = $this->checkFav($task_arr[$i]->task_id);
				}
			}
			return $task_arr;
		}
	}
	public function getFavTask($categories = array(),$not_usrid = 0,$limit = array(),$distance_arr = array(),$search_key = '')
	{	//$distance_arr = array();
		if(count($distance_arr) > 0)
		{
			$lat = $distance_arr['lat'];
			$lng = $distance_arr['lng'];
			$distance = $distance_arr['distance'];
			
			$subsql1 = "";
			$subsql2 = "";
			$subsql3 = "";
			if(count($categories))
			{
				$cat_str = implode(',',$categories);
				$subsql1 = " AND tsk.category_id IN(".$cat_str.")";
			}
			if($not_usrid != 0)
			{
				$subsql2 = " AND tsk.user_id !=".$not_usrid;
			}
			if(count($limit) == 0)
			{
				$limit['count'] = 10;
				$limit['start'] = 0;
			}
			if($search_key != '')
			{
				if(is_numeric($search_key))
				{
					$subsql3 = " AND tsk.task_id=".$search_key;
				}
				else
				{
					$subsql3 = " AND tsk.task_name LIKE '%".$search_key."%'";
				}
			}
			$subsql4 = " AND tsk.is_online_phone!='Y'";
			$subsql5 = " AND tsk.is_online_phone='Y'";
			$admin_settings = $this->defaultdata->grabSettingData();
			$task_dur = $admin_settings->task_show_duration;
			$last_tsk_dur_time = time() -($task_dur * 24 * 60 * 60);
			
			$sql = "SELECT * FROM((SELECT tsk.*,wc.*,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc,".TABLE_FAVOURITE_TASKS." AS fav_tsk WHERE tsk.category_id = wc.cat_id AND tsk.task_id = fav_tsk.task_id AND fav_tsk.tasker_id=".$not_usrid." ".$subsql1.$subsql2.$subsql3.$subsql4." AND tsk.posted_time>=".$last_tsk_dur_time." having distance<='".$distance."') UNION (SELECT tsk.*,wc.*,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc,".TABLE_FAVOURITE_TASKS." AS fav_tsk WHERE tsk.category_id = wc.cat_id AND tsk.task_id = fav_tsk.task_id AND fav_tsk.tasker_id=".$not_usrid." ".$subsql1.$subsql2.$subsql3.$subsql5." AND tsk.posted_time>=".$last_tsk_dur_time.")) AS ALLTSK ORDER BY ALLTSK.posted_time DESC LIMIT ".$limit['start'].",".$limit['count'];
			
			$query = $this->db->query($sql);
			$task_arr = $query->result();
			if(count($task_arr) > 0)
			{
				for($i = 0;$i < count($task_arr); $i++)
				{
					$task_arr[$i]->is_fav = 0;
					$task_arr[$i]->is_fav = $this->checkFav($task_arr[$i]->task_id);
				}
			}
			return $task_arr;
		}
		else
		{
			return array();
		}
	}
	public function getAllTaskCount($categories = array(),$not_usrid = 0,$distance_arr = array(),$search_key = '')
	{
		if(count($distance_arr) > 0)
		{
			$lat = $distance_arr['lat'];
			$lng = $distance_arr['lng'];
			$distance = $distance_arr['distance'];
			
			$subsql1 = "";
			$subsql2 = "";
			$subsql3 = "";
			if(count($categories))
			{
				$cat_str = implode(',',$categories);
				$subsql1 = " AND tsk.category_id IN(".$cat_str.")";
			}
			if($not_usrid != 0)
			{
				$subsql2 = " AND tsk.user_id !=".$not_usrid;
			}
			if($search_key != '')
			{
				if(is_numeric($search_key))
				{
					$subsql3 = " AND tsk.task_id=".$search_key;
				}
				else
				{
					$subsql3 = " AND tsk.task_name LIKE '%".$search_key."%'";
				}
			}
			
			$subsql4 = " AND tsk.is_online_phone!='Y'";
			$subsql5 = " AND tsk.is_online_phone='Y'";
			
			$admin_settings = $this->defaultdata->grabSettingData();
			$task_dur = $admin_settings->task_show_duration;
			$last_tsk_dur_time = time() - ($task_dur * 24 * 60 * 60);
						
			$sql = "SELECT * FROM((SELECT *,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc WHERE tsk.category_id = wc.cat_id".$subsql1.$subsql2.$subsql3.$subsql4." AND tsk.posted_time>=".$last_tsk_dur_time." AND tsk.task_status='Y' having distance<='".$distance."') UNION (SELECT *,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc WHERE tsk.category_id = wc.cat_id".$subsql1.$subsql2.$subsql3.$subsql5." AND tsk.posted_time>=".$last_tsk_dur_time." AND tsk.task_status='Y')) AS ALLTSK ORDER BY ALLTSK.posted_time DESC";
			$query = $this->db->query($sql);
			return $query->num_rows();
		}
		else
		{
			$this->db->from($this->table.' as tsk');
			$this->db->join(TABLE_WORK_CATEGORIES.' wc','tsk.category_id = wc.cat_id');
			if(count($categories) > 0)
			{
				$this->db->where_in('tsk.category_id',$categories);
			}
			if($not_usrid != 0)
			{
				$this->db->where('tsk.user_id !=',$not_usrid);
			}
			$this->db->order_by('tsk.posted_time','desc');
			$query = $this->db->get();
			return $query->num_rows();
		}
	}
	public function getFavTaskCount($categories = array(),$not_usrid = 0,$distance_arr = array(),$search_key = '')
	{
		if(count($distance_arr) > 0)
		{
			$lat = $distance_arr['lat'];
			$lng = $distance_arr['lng'];
			$distance = $distance_arr['distance'];
			
			$subsql1 = "";
			$subsql2 = "";
			$subsql3 = "";
			if(count($categories))
			{
				$cat_str = implode(',',$categories);
				$subsql1 = " AND tsk.category_id IN(".$cat_str.")";
			}
			if($not_usrid != 0)
			{
				$subsql2 = " AND tsk.user_id !=".$not_usrid;
			}
			if($search_key != '')
			{
				if(is_numeric($search_key))
				{
					$subsql3 = " AND tsk.task_id=".$search_key;
				}
				else
				{
					$subsql3 = " AND tsk.task_name LIKE '%".$search_key."%'";
				}
			}
			
			$subsql4 = " AND tsk.is_online_phone!='Y'";
			$subsql5 = " AND tsk.is_online_phone='Y'";
			$admin_settings = $this->defaultdata->grabSettingData();
			$task_dur = $admin_settings->task_show_duration;
			$last_tsk_dur_time = time() -($task_dur * 24 * 60 * 60);
			
			$sql = "SELECT * FROM((SELECT tsk.*,wc.*,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc,".TABLE_FAVOURITE_TASKS." AS fav_tsk WHERE tsk.category_id = wc.cat_id AND tsk.task_id = fav_tsk.task_id AND fav_tsk.tasker_id=".$not_usrid." ".$subsql1.$subsql2.$subsql3.$subsql4." AND tsk.posted_time>=".$last_tsk_dur_time." having distance<='".$distance."') UNION (SELECT tsk.*,wc.*,SQRT( POW(69.1 * (tsk.task_location_lat - ".$lat."), 2) + POW(69.1 * (".$lng."- tsk.task_location_lng) * COS(tsk.task_location_lat / 57.3), 2)) AS distance FROM ".$this->table." AS tsk,".TABLE_WORK_CATEGORIES." AS wc,".TABLE_FAVOURITE_TASKS." AS fav_tsk WHERE tsk.category_id = wc.cat_id AND tsk.task_id = fav_tsk.task_id AND fav_tsk.tasker_id=".$not_usrid." ".$subsql1.$subsql2.$subsql3.$subsql5." AND tsk.posted_time>=".$last_tsk_dur_time.")) AS ALLTSK ORDER BY ALLTSK.posted_time DESC";
			$query = $this->db->query($sql);
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
	public function grabTaskDet($task_cond = array())
	{
		$task_det = array();
		if(count($task_cond) > 0)
		{
			$this->db->where($task_cond);
			$task_det = $this->db->get($this->table)->row();
			if(count($task_det) > 0)
			{
				$this->load->model('userdata');
				$usr_cond = array();
				$usr_cond['id'] = $task_det->user_id;
				$task_det->created_usr_det = array();
				$task_det->created_usr_det = $this->userdata->grabUserData($usr_cond);
				$cat_cond = array();
				$cat_cond['cat_id'] = $task_det->category_id;
				$task_det->task_cat_det = array();
				$task_det->task_cat_det = $this->grabWorkCategory($cat_cond);
				$task_det->is_fav = 0;
				$task_det->is_fav = $this->checkFav($task_det->task_id);
				$task_det->all_task_files = array();
				$task_det->all_task_files = $this->getTaskFiles($task_det->task_id);
			}
		}
		return $task_det;
	}
	public function getTaskFiles($task_id = 0)
	{
		$file_data = array();
		if($task_id != 0)
		{
			$this->db->where('task_id',$task_id);
			$file_data = $this->db->get(TABLE_TASK_FILES)->result();
		}
		return $file_data;
	}
	public function grabTaskFiles($tsk_file_cond = array())
	{
		$tsk_file_det = array();
		if(count($tsk_file_cond) > 0)
		{
			$this->db->where($tsk_file_cond);
			$tsk_file_det = $this->db->get(TABLE_TASK_FILES)->row();
		}
		return $tsk_file_det;
	}
	public function grabWorkCategory($cat_cond = array())
	{
		$task_cat_det = array();
		if(count($cat_cond) > 0)
		{
			$this->db->where($cat_cond);
			$task_cat_det = $this->db->get(TABLE_WORK_CATEGORIES)->row();
		}
		return $task_cat_det;
	}
	public function insertFavouriteTask($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_FAVOURITE_TASKS, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function checkFav($task_id = 0)
	{
		if($task_id != 0)
		{
			$fav_cond = array();
			$fav_cond['task_id'] = $task_id;
			$fav_cond['tasker_id'] = $this->session->userdata('usrid');
			$query = $this->db->get_where(TABLE_FAVOURITE_TASKS,$fav_cond);
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
	public function delFavTask($del_cond)
	{
		$this->db->where($del_cond);
		$this->db->delete(TABLE_FAVOURITE_TASKS);
	}
	public function checkBidDet($bid_cond)
	{
		$query = $this->db->get_where(TABLE_BID_TASK,$bid_cond);
		return $query->num_rows();
	}
	public function insertBidTask($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_BID_TASK, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function updateBidTask($data,$condition)
	{
		$this->db->update(TABLE_BID_TASK, $data, $condition);
	}
	public function insertBidTaskMilestone($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_BID_TASK_MILESTONE, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function updateBidTaskMilestone($data,$condition)
	{
		$this->db->update(TABLE_BID_TASK_MILESTONE, $data, $condition);
	}
	public function getAllBid($task_id = 0)
	{
		$return_all_bid = array();
		if($task_id > 0)
		{
			$this->db->from(TABLE_BID_TASK.' as BT');
			$this->db->join(TABLE_USER.' as U','BT.tasker_id = U.id');
			$this->db->where('BT.task_id',$task_id);
			$this->db->order_by('BT.bid_postedtime', 'desc');
			$query = $this->db->get();
			$return_all_bid = $query->result();
			if(count($return_all_bid) > 0)
			{
				for($i=0;$i<count($return_all_bid);$i++)
				{
					$return_all_bid[$i]->bid_milestones = array();
					$milestone_cond = array('bid_id' => $return_all_bid[$i]->bid_id);
					$return_all_bid[$i]->bid_milestones = $this->getAllBidMilestones($milestone_cond);
					$return_all_bid[$i]->is_fav_tasker = 0;
					$return_all_bid[$i]->is_fav_tasker = $this->checkFavTaskers($return_all_bid[$i]->tasker_id,$this->session->userdata('usrid'));
					
					$taker_rating_cond['tasker_id'] = $return_all_bid[$i]->tasker_id;
					$tasker_review_ratings = $this->getTaskerReviewRatings($taker_rating_cond);
					$return_all_bid[$i]->review_ratings =  $this->getTaskerReviewRatingsDetails($tasker_review_ratings);
				}
			}
			return $return_all_bid;
		}
	}
	public function grabBidDet($bid_cond = array())
	{
		$bid_det = array();
		if($bid_cond > 0)
		{
			$query = $this->db->get_where(TABLE_BID_TASK,$bid_cond);
			$bid_det = $query->row();
			if(count($bid_det) > 0)
			{
				$bid_det->bid_milestones = array();
				$milestone_cond = array('bid_id' => $bid_det->bid_id);
				$bid_det->bid_milestones = $this->getAllBidMilestones($milestone_cond);
			}
		}
		return $bid_det;
	}
	public function getAllBidMilestones($cond_arr = array())
	{
		$return_bid_milestones = array();
		if(count($cond_arr))
		{
			$this->db->where($cond_arr);
			$this->db->order_by('bid_milestone_id', 'asc');
			$query = $this->db->get(TABLE_BID_TASK_MILESTONE);
			$return_bid_milestones = $query->result();
		}
		return $return_bid_milestones;
	}
	public function deleteBidMilestones($del_cond = array())
	{
		if(count($del_cond) > 0)
		{
			$this->db->where($del_cond);
			$this->db->delete(TABLE_BID_TASK_MILESTONE);
		}
	}
	public function getSumTotalMilestone($bid_id = 0)
	{
		$sum_tot_ms = 0;
		if($bid_id != 0)
		{
			$this->db->select('SUM(milestone_amount) as tot_ms');
			$this->db->where('bid_id',$bid_id);
			$query = $this->db->get(TABLE_BID_TASK_MILESTONE);
			$sum_tot_ms = $query->row()->tot_ms;
		}
		return $sum_tot_ms;
	}
	public function grabBidMilestone($cond = array())
	{
		$bid_det = array();
		if(count($cond) > 0)
		{
			$this->db->where($cond);
			$query = $this->db->get(TABLE_BID_TASK_MILESTONE);
			$bid_det = $query->row();
		}
		return $bid_det;
	}
	public function getMyBidTasks($uid = 0)
	{
		$my_all_bid = array();
		if($uid > 0)
		{
			$this->db->from(TABLE_BID_TASK.' as BT');
			$this->db->join($this->table.' as TSK','TSK.task_id = BT.task_id');
			$this->db->where('BT.tasker_id',$uid);
			$not_status_arr = array('A','AC','C');
			$this->db->where_not_in('TSK.task_status',$not_status_arr);
			$this->db->order_by('BT.bid_postedtime', 'desc');
			$query = $this->db->get();
			$my_all_bid = $query->result();
		}
		return $my_all_bid;
	}
	public function getMyAssignedTasks($uid = 0)
	{
		$my_all_bid = array();
		if($uid > 0)
		{
			$this->db->from(TABLE_BID_TASK.' as BT');
			$this->db->join($this->table.' as TSK','TSK.task_id = BT.task_id');
			$this->db->where('BT.tasker_id',$uid);
			$status_arr = array('A','AC');
			$this->db->where_in('BT.bid_status',$status_arr);
			$this->db->order_by('BT.bid_postedtime', 'desc');
			$query = $this->db->get();
			$my_all_bid = $query->result();
		}
		return $my_all_bid;
	}
	public function getMyTasks($uid = 0)
	{
		$my_all_tasks = array();
		if($uid > 0)
		{
			$this->db->where('user_id',$uid);
			$this->db->where_in('task_status',array('Y','A','AC'));
			$this->db->order_by('posted_time', 'desc');
			$query = $this->db->get($this->table);
			$my_all_tasks = $query->result();
		}
		return $my_all_tasks;
	}
	public function grabAvgBid($task_id = 0)
	{
		$avg_bid = 0;
		if($task_id > 0)
		{
			$this->db->select('avg(bid_total_amount) as avg_bid');
			$this->db->where('task_id',$task_id);
			$query = $this->db->get(TABLE_BID_TASK);
			$avg_bid = $query->row()->avg_bid;
		}
		return $avg_bid;
	}
	public function checkAuthTasker($usr_det = array())
	{
		if(count($usr_det) > 0)
		{
			if($usr_det->profile_small_desc == '' || $usr_det->profile_detail_desc == '' || $usr_det->prifile_picture == '' || $usr_det->hourly_charges == 0 || $this->hasSkills($usr_det->id) == 0)
			{
				return 0;
			}
			else
			{
				return 1;
			}
		}
		else
		{
			return 0;
		}
	}
	public function hasSkills($user_id)
	{
		$this->db->where('user_id',$user_id);
		$query = $this->db->get(TABLE_USER_SKILLS);
		return $query->num_rows();
	}
	public function checkFavTaskers($tasker_id = 0, $user_id = 0)
	{
		if($tasker_id > 0 && $user_id > 0)
		{
			$cond = array('user_id' => $user_id,'tasker_id' => $tasker_id);
			$query = $this->db->get_where(TABLE_FAVOURITE_TASKERS,$cond);
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
	public function insertFavouriteTaskers($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_FAVOURITE_TASKERS, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function delFavTaskers($del_cond)
	{
		$this->db->where($del_cond);
		$this->db->delete(TABLE_FAVOURITE_TASKERS);
	}
	public function updateTaskDet($data,$condition)
	{
		$this->db->update($this->table, $data, $condition);
	}
	public function insertMsPaymentTrack($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_MILESTONE_PAYMENT_TRACK, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function grabMsPaymentTrack($cond = array())
	{
		$pay_track_det = array();
		if(count($cond) > 0)
		{
			$this->db->where($cond);
			$query = $this->db->get(TABLE_MILESTONE_PAYMENT_TRACK);
			$pay_track_det = $query->row();
		}
		return $pay_track_det;
	}
	public function updateMsPaymentTrack($data,$condition)
	{
		$this->db->update(TABLE_MILESTONE_PAYMENT_TRACK, $data, $condition);
	}
	public function insertTaskNotifications($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_TASK_NOTIFICATIONS, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function grabTaskNotifications($cond = array())
	{
		$not_det = array();
		if(count($cond) > 0)
		{
			$this->db->where($cond);
			$query = $this->db->get(TABLE_TASK_NOTIFICATIONS);
			$not_det = $query->row();
		}
		return $not_det;
	}
	public function updateTaskNotifications($data,$condition)
	{
		$this->db->update(TABLE_TASK_NOTIFICATIONS, $data, $condition);
	}
	public function delTaskNotifications($del_cond)
	{
		$this->db->where($del_cond);
		$this->db->delete(TABLE_TASK_NOTIFICATIONS);
	}
	public function getMsReleaseRequestEmailTemplate()
	{
		$query = $this->db->get(TABLE_EMAIL_REQUEST_MS);
		$mail_data = $query->row();
		return $mail_data;
	}
	public function getMsReleaseEmailTemplate()
	{
		$query = $this->db->get(TABLE_EMAIL_RELEASE_MS);
		$mail_data = $query->row();
		return $mail_data;
	}
	public function getMyTaskUser($user_id = 0)
	{
		$my_task_arr = array();
		if($user_id != 0)
		{
			$this->db->select('TSK.task_id as tsk_id,TSK.*,BID.*');
			$this->db->from($this->table.' as TSK');
			$this->db->join(TABLE_BID_TASK.' as BID','TSK.task_id=BID.task_id','left');
			$this->db->where('TSK.user_id',$user_id);
			$this->db->where_in('TSK.task_status',array('Y','A','AC'));
			$this->db->group_by('TSK.task_id');
			$this->db->order_by('TSK.posted_time','DESC');
			$query = $this->db->get();
			$my_task_arr = $query->result();
			if(count($my_task_arr) > 0)
			{
				$this->load->model('userdata');
				for($i=0;$i<count($my_task_arr);$i++)
				{
					if($my_task_arr[$i]->bid_id != NULL)
					{
						$usr_cond = array();
						$usr_cond['id'] = $my_task_arr[$i]->tasker_id;
						$my_task_arr[$i]->tasker_det = array();
						$my_task_arr[$i]->tasker_det = $this->userdata->grabUserData($usr_cond);
					}
				}
			}
		}
		return $my_task_arr;
	}
	public function getAssignedTaskTasker($tasker_id = 0)
	{
		$my_task_arr = array();
		if($tasker_id != 0)
		{
			$this->db->from($this->table.' as TSK');
			$this->db->join(TABLE_BID_TASK.' as BID','TSK.task_id=BID.task_id');
			$this->db->where('BID.tasker_id',$tasker_id);
			$this->db->where_in('BID.bid_status',array('A','AC'));
			$this->db->order_by('TSK.posted_time','DESC');
			$query = $this->db->get();
			$my_task_arr = $query->result();
			if(count($my_task_arr) > 0)
			{
				$this->load->model('userdata');
				for($i=0;$i<count($my_task_arr);$i++)
				{
					if($my_task_arr[$i]->bid_id != NULL)
					{
						$usr_cond = array();
						$usr_cond['id'] = $my_task_arr[$i]->user_id;
						$my_task_arr[$i]->user_det = array();
						$my_task_arr[$i]->user_det = $this->userdata->grabUserData($usr_cond);
					}
				}
			}
		}
		return $my_task_arr;
	}
	public function getCompletedTaskTasker($tasker_id = 0)
	{
		$my_task_arr = array();
		if($tasker_id != 0)
		{
			$this->db->from($this->table.' as TSK');
			$this->db->join(TABLE_BID_TASK.' as BID','TSK.task_id=BID.task_id');
			$this->db->where('BID.tasker_id',$tasker_id);
			$this->db->where('TSK.task_status','C');
			$this->db->order_by('TSK.posted_time','DESC');
			$query = $this->db->get();
			$my_task_arr = $query->result();
			if(count($my_task_arr) > 0)
			{
				$this->load->model('userdata');
				for($i=0;$i<count($my_task_arr);$i++)
				{
					if($my_task_arr[$i]->bid_id != NULL)
					{
						$usr_cond = array();
						$usr_cond['id'] = $my_task_arr[$i]->user_id;
						$my_task_arr[$i]->user_det = array();
						$my_task_arr[$i]->user_det = $this->userdata->grabUserData($usr_cond);
					}
				}
			}
		}
		return $my_task_arr;
	}
	public function getCompletedTaskUser($user_id = 0)
	{
		$my_task_arr = array();
		if($user_id != 0)
		{
			$this->db->from($this->table.' as TSK');
			$this->db->join(TABLE_BID_TASK.' as BID','TSK.task_id=BID.task_id');
			$this->db->where('TSK.user_id',$user_id);
			$this->db->where('TSK.task_status','C');
			$this->db->where('BID.bid_status','C');
			$this->db->order_by('TSK.posted_time','DESC');
			$query = $this->db->get();
			$my_task_arr = $query->result();
			if(count($my_task_arr) > 0)
			{
				$this->load->model('userdata');
				for($i=0;$i<count($my_task_arr);$i++)
				{
					if($my_task_arr[$i]->bid_id != NULL)
					{
						$usr_cond = array();
						$usr_cond['id'] = $my_task_arr[$i]->tasker_id;
						$my_task_arr[$i]->tasker_det = array();
						$my_task_arr[$i]->tasker_det = $this->userdata->grabUserData($usr_cond);
					}
				}
			}
		}
		return $my_task_arr;
	}
	public function getAllNewsfeeds($user_id = 0)
	{
		$news_feeds_arr = array();
		if($user_id != 0)
		{
			$this->db->where('user_id',$user_id);
			$this->db->or_where('tasker_id',$user_id);
			$this->db->order_by('notification_postedtime','desc');
			$this->db->order_by('notification_id','desc');
			$query = $this->db->get(TABLE_TASK_NOTIFICATIONS);
			$news_feeds_arr = $query->result();
			if(count($news_feeds_arr) > 0)
			{
				$this->load->model('userdata');
				for($i=0;$i<count($news_feeds_arr);$i++)
				{
					$user_cond = array('id' => $news_feeds_arr[$i]->user_id);
					$news_feeds_arr[$i]->user_det = $this->userdata->grabUserData($user_cond);
					$tasker_cond = array('id' => $news_feeds_arr[$i]->tasker_id);
					$news_feeds_arr[$i]->tasker_det = $this->userdata->grabUserData($tasker_cond);
					$task_cond = array('task_id' => $news_feeds_arr[$i]->task_id);
					$news_feeds_arr[$i]->task_det = $this->grabTaskDet($task_cond);
					
					$bid_cond = array('task_id' => $news_feeds_arr[$i]->task_id,'tasker_id' => $news_feeds_arr[$i]->tasker_id);
					$news_feeds_arr[$i]->task_bid_det = $this->grabBidDet($bid_cond);
					if($news_feeds_arr[$i]->bid_ms_id != 0)
					{
						$ms_cond = array('bid_milestone_id' => $news_feeds_arr[$i]->bid_ms_id);
						$news_feeds_arr[$i]->bid_ms_det = $this->grabBidMilestone($ms_cond);
					}
					if($news_feeds_arr[$i]->notification_type == 'C')
					{
						$rating_cond['user_id'] = $news_feeds_arr[$i]->user_id;
						$rating_cond['tasker_id'] = $news_feeds_arr[$i]->tasker_id;
						$rating_cond['task_id'] = $news_feeds_arr[$i]->task_id;
						$news_feeds_arr[$i]->is_tasker_rating = count($this->grabTaskerReviewRating($rating_cond));
						
						$rating_cond_user['user_id'] = $news_feeds_arr[$i]->user_id;
						$rating_cond_user['tasker_id'] = $news_feeds_arr[$i]->tasker_id;
						$rating_cond_user['task_id'] = $news_feeds_arr[$i]->task_id;
						$news_feeds_arr[$i]->is_user_rating = count($this->grabUserReviewRating($rating_cond_user));
					}
				}
			}
		}
		return $news_feeds_arr;
	}
	public function insertTaskerReviewRating($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_TASKER_REVIEW_RATINGS, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function grabTaskerReviewRating($cond = array())
	{
		$review_rating_det = array();
		if(count($cond) > 0)
		{
			$this->db->where($cond);
			$query = $this->db->get(TABLE_TASKER_REVIEW_RATINGS);
			$review_rating_det = $query->row();
		}
		return $review_rating_det;
	}
	public function getTaskerReviewRatings($cond = array())
	{
		$tasker_review_ratings = array();
		if(count($cond) > 0)
		{
			$this->db->where($cond);
			$this->db->order_by('rating_postedtime','desc');
			$query = $this->db->get(TABLE_TASKER_REVIEW_RATINGS);
			$tasker_review_ratings = $query->result();
			if(count($tasker_review_ratings) > 0)
			{
				$this->load->model('userdata');
				for($i=0;$i<count($tasker_review_ratings);$i++)
				{
					$user_cond = array('id' => $tasker_review_ratings[$i]->user_id);
					$tasker_review_ratings[$i]->giving_user_det = $this->userdata->grabUserData($user_cond);
					$task_cond = array('task_id' => $tasker_review_ratings[$i]->task_id);
					$tasker_review_ratings[$i]->task_det = $this->grabTaskDet($task_cond);
				}
			}
		}
		return $tasker_review_ratings;
	}
	public function getTaskerReviewRatingsDetails($tasker_review_ratings = array())
	{
		$all_review_ratings = array();
		$all_review_ratings['tasker_review_ratings'] = array();
		$all_review_ratings['tasker_ratings'] = 0;
		$all_review_ratings['tasker_on_budget'] = '0%';
		$all_review_ratings['tasker_on_time'] = '0%';
		$all_review_ratings['tasker_hire_again'] = '0%';
		$all_review_ratings['no_of_rating'] = count($tasker_review_ratings);
		if(count($tasker_review_ratings) > 0)
		{
			$all_review_ratings['tasker_review_ratings'] = $tasker_review_ratings;
			$total_rating_count = 0;
			$total_on_budget = 0;
			$total_on_time = 0;
			$total_hire_again = 0;
			foreach($tasker_review_ratings as $rv_rtng)
			{
				$total_rating_count += $rv_rtng->rating;
				if($rv_rtng->task_on_budget == 'Y')
				{
					$total_on_budget++;
				}
				if($rv_rtng->task_on_time == 'Y')
				{
					$total_on_time++;
				}
				if($rv_rtng->hire_again == 'Y')
				{
					$total_hire_again++;
				}
			}
			$all_review_ratings['tasker_ratings'] = number_format(round($total_rating_count/$all_review_ratings['no_of_rating'],1),1);
			
			$all_review_ratings['tasker_on_budget'] = round((($total_on_budget/$all_review_ratings['no_of_rating'])*100),2);
			$all_review_ratings['tasker_on_budget'] = $all_review_ratings['tasker_on_budget'].'%';
			
			$all_review_ratings['tasker_on_time'] = round((($total_on_time/$all_review_ratings['no_of_rating'])*100),2);
			$all_review_ratings['tasker_on_time'] = $all_review_ratings['tasker_on_time'].'%';
			
			$all_review_ratings['tasker_hire_again'] = round((($total_hire_again/$all_review_ratings['no_of_rating'])*100),2);
			$all_review_ratings['tasker_hire_again'] = $all_review_ratings['tasker_hire_again'].'%';
		}
		return $all_review_ratings;
	}
	public function insertUserReviewRating($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_USER_REVIEW_RATINGS, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function grabUserReviewRating($cond = array())
	{
		$review_rating_det = array();
		if(count($cond) > 0)
		{
			$this->db->where($cond);
			$query = $this->db->get(TABLE_USER_REVIEW_RATINGS);
			$review_rating_det = $query->row();
		}
		return $review_rating_det;
	}
	
	public function getUserReviewRatings($cond = array())
	{
		$user_review_ratings = array();
		if(count($cond) > 0)
		{
			$this->db->where($cond);
			$this->db->order_by('usr_rating_postedtime','desc');
			$query = $this->db->get(TABLE_USER_REVIEW_RATINGS);
			$user_review_ratings = $query->result();
			if(count($user_review_ratings) > 0)
			{
				$this->load->model('userdata');
				for($i=0;$i<count($user_review_ratings);$i++)
				{
					$tasker_cond = array('id' => $user_review_ratings[$i]->tasker_id);
					$user_review_ratings[$i]->giving_user_det = $this->userdata->grabUserData($tasker_cond);
					$task_cond = array('task_id' => $user_review_ratings[$i]->task_id);
					$user_review_ratings[$i]->task_det = $this->grabTaskDet($task_cond);
				}
			}
		}
		return $user_review_ratings;
	}
	public function getUserReviewRatingsDetails($user_review_ratings = array())
	{
		$all_review_ratings = array();
		$all_review_ratings['user_review_ratings'] = array();
		$all_review_ratings['user_ratings'] = 0;
		$all_review_ratings['no_of_rating'] = count($user_review_ratings);
		if(count($user_review_ratings) > 0)
		{
			$all_review_ratings['user_review_ratings'] = $user_review_ratings;
			$total_rating_count = 0;
			foreach($user_review_ratings as $rv_rtng)
			{
				$total_rating_count += $rv_rtng->usr_rating;
			}
			$all_review_ratings['user_ratings'] = number_format(round($total_rating_count/$all_review_ratings['no_of_rating'],1),1);
		}
		return $all_review_ratings;
	}
	public function getUserTaskCount($uid = 0,$status_arr = array())
	{
		$no_of_task = 0;
		if($uid != 0)
		{
			$this->db->where('user_id',$uid);
			if(count($status_arr) > 0)
			{
				$this->db->where_in('task_status',$status_arr);
			}
			$no_of_task = $this->db->get($this->table)->num_rows();
		}
		return $no_of_task;
	}
}
?>