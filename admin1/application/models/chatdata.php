<?php
class Chatdata extends CI_Model {

	private $data=array();
	
	function __construct()
	{
		parent::__construct();
	}
		
	public function insertMessage($data = array()){
		
		$this->db->insert(TABLE_CHAT, $data);
		return $this->db->insert_id();
		
	}
	
	public function userMessages($to_user_id,$task_id){
		$user_id = $this->session->userdata['usrid'];
		$sql_query = "SELECT * FROM ".TABLE_CHAT." WHERE (`from_user_id`='$user_id' AND `task_id`='$task_id' AND `to_user_id`='$to_user_id') OR (`to_user_id`='$user_id' AND `task_id`='$task_id' AND `from_user_id`='$to_user_id')";
		$query = $this->db->query($sql_query);
		$resut_data = $query->result();
		//echo $this->db->last_query();
		return $resut_data;
	}
	
	public function getMessageById($message_id){
		$sql_query = "SELECT * FROM ".TABLE_CHAT." WHERE `message_id`='$message_id' ";
		$query = $this->db->query($sql_query);
		$resut_data = $query->result();
		return $resut_data;
	}
		
	public function getMessageCount($user_id = 0){
		$sql_query = "SELECT COUNT(*) as total FROM ".TABLE_CHAT." WHERE to_user_id='".$user_id."' AND to_user_view_status='N' ";
		$query = $this->db->query($sql_query);
		$resut_data = $query->row();
		return $resut_data->total;
	}
	
	public function messageRead($user_id = 0,$task_id){
		$update = "UPDATE ".TABLE_CHAT." SET to_user_view_status='S'  WHERE from_user_id='".$user_id."' AND task_id='".$task_id."' AND to_user_view_status='N' ";
		$this->db->query($update);
	}
	
	public function getUnreadMessege($from_userID,$to_userID,$task_id)
	{
		$sql_query="SELECT * FROM ".TABLE_CHAT." WHERE `to_user_id`='$from_userID' AND `from_user_id`='$to_userID' AND task_id='".$task_id."' AND to_user_view_status='N' ORDER BY postedTime ASC";
		$query= $this->db->query($sql_query);
		$result= $query->result();
		return $result;
	}
	
	public function getAllUnreadMessege($to_user_id)
	{
		$sql_query="SELECT * FROM ".TABLE_CHAT." WHERE `to_user_id`='$to_user_id' AND to_user_view_status='N' ORDER BY postedTime ASC"; 
		$query= $this->db->query($sql_query);
		$result= $query->result();
		return $result;
	}
	
	public function unreadMessegeUpdate($value_array,$cond_array){
		$this->db->where($cond_array);
		$this->db->update(TABLE_CHAT,$value_array);
	}
		
	public function showChatList(){
		$sessuser=$this->session->userdata('usrid');
		$user_type=$this->session->userdata('usrtype');
		$return_array = array();
		//if($user_type==1){
			$sql=$this->db->select('T.task_id')
							->from(TABLE_TASKS.' T')
							//->join(TABLE_USERLOGIN.' UL','UL.uid=U.id')
							->join(TABLE_USER.' U','U.id=T.user_id')
							->where(array('U.status'=>'Y','T.task_status !='=>'N','U.id'=>$sessuser))
							->get()
							->result();
			if(count($sql)>0){
				foreach($sql as $task){
					$return_array[]=$this->db->select('U.*,T.user_id TASKUSER,T.task_name,B.*')
							->from(TABLE_BID_TASK.' B')
							->join(TABLE_TASKS.' T','T.task_id=B.task_id')
							->join(TABLE_USER.' U','B.tasker_id=U.id')
							->where(array('U.status'=>'Y','B.task_id'=>$task->task_id))
							->get()
							->result();
				}
				
			}
			
		//}
		$last=count($return_array)+1;
		if($user_type==2){
			$return_array[$last]=$this->db->select('U.*,T.task_id,T.task_name,C.user_id TASKUSER,C.tasker_id')
							->from(TABLE_CHAT.' C')
							->join(TABLE_TASKS.' T','C.task_id=T.task_id')
							//->join(TABLE_BID_TASK.' B','C.tasker_id=B.tasker_id')
							->join(TABLE_USER.' U','U.id=C.user_id')
							->where(array('U.status'=>'Y','T.task_status !='=>'N','C.tasker_id'=>$sessuser))
							->group_by("C.task_id")
							->get()
							->result();
		}
		$return_array=array_reverse($return_array);
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($return_array);echo "</pre>";exit;
		return $return_array;
	}
	
	public function getChatUser($task_id)
	{
		$sql_query="SELECT * FROM ".TABLE_TASKS." T, ".TABLE_USER." U WHERE T.user_id=U.id AND T.task_id='".$task_id."' ";
		$query= $this->db->query($sql_query);
		$result= $query->row();
		return $result;
	}
}
?>