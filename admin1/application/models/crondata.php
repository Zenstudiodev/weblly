<?php
class Crondata extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}
	public function getNewTask()
	{
		$general_settings = $this->defaultdata->grabSettingData();
		$task_show_duration = $general_settings->task_show_duration;
		$time_limit = time() - ($task_show_duration * 24 * 60 * 60);
		$this->db->from(TABLE_TASKS.' as tsk');
		$this->db->join(TABLE_WORK_CATEGORIES.' wc','tsk.category_id = wc.cat_id');
		$this->db->join(TABLE_USER.' usr','usr.id = tsk.user_id');
		$this->db->where('tsk.task_status','Y');
		$this->db->where('tsk.posted_time >=',$time_limit);
		$query = $this->db->get();
		$task_arr = $query->result();
		return $task_arr;
	}
	public function getTaskMatchingExpUser($cat_id = 0,$not_user_id = 0)
	{
		$tasker_arr = array();
		if($cat_id != 0 && $not_user_id != 0)
		{
			$this->db->from(TABLE_USER.' as usr');
			$this->db->join(TABLE_USER_TO_CAT.' usrtocat','usrtocat.user_id = usr.id');
			$this->db->where('usr.id !=',$not_user_id);
			$this->db->where('usrtocat.cat_id',$cat_id);
			$query = $this->db->get();
			$tasker_arr = $query->result();
			return $tasker_arr;
		}
		return $tasker_arr;
	}
	public function insertTaskEmailSendNotification($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->db->insert(TABLE_TASK_EMAIL_SEND_NOTIFICATION, $user_data);
			 return $this->db->insert_id();
		}
		else
			return 0;
	}
	public function grabTaskEmailSendNotification($email_send_cond = array())
	{
		if(count($email_send_cond) > 0)
		{
			$query = $this->db->get_where(TABLE_TASK_EMAIL_SEND_NOTIFICATION,$email_send_cond);
			return $query->num_rows();
		}
		else
		{
			return 0;
		}
	}
	public function getTaskNotificationEmailTemplate()
	{
		$query = $this->db->get(TABLE_EMAIL_TASK_MATCHING_EXPERTISE);
		$mail_data = $query->row();
		return $mail_data;
	}
}
?>