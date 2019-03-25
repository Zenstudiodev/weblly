<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Language extends CI_Controller {

	public $data=array();
	public $loggedout_method_arr = array('index');
	function __construct() {
		parent::__construct();
		
		$this->data=$this->defaultdata->getFrontendDefaultData();
		$this->load-> model('languageModel');
		$this->load->model('userdata');
		if($this->defaultdata->is_session_active() == 1)
		{
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}
		if($this->session->userdata('admuname') == ''){
			redirect(base_url('login'));
		}
	}

	public function addnew() {
		$this->load->view('language/addnew',$this->data);
	}

	public function getAllData(){
		$list = $this->languageModel->getData();
		$datag = array();
		foreach ($list as $index => $data) {
			foreach($data as $k=>$a){
				if($k=='ID'){					
					$datag[$index][] = $a;
				}else if($k=='TEXT'){
					$id = $a;
					$datag[$index][] = $a;
				}else{
					$datag[$index][] = '<a href="trans-edit/word/'.$id.'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Edit</a> | <a href="'.base_url("trans-delete/word/").$id.'" class="delete-item-data"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
				}			
			}
		}
		echo json_encode(array('data'=>$datag,"recordsTotal" => $this->languageModel->count_all(),
		"recordsFiltered" => $this->languageModel->count_filtered()));die;
	}

	public function translation(){
		$this->load->view('language/translist',$this->data);
	}

	public function transWord(){
		$apiKey = '';
		if($apiKey == ''){
			define("CONFIG_TRANS_GOOGLE_KEY", "AIzaSyDch35zuS-Uph_N4dO-XgJAhgl0Kq5X_-U");
		}
		$apiKey = CONFIG_TRANS_GOOGLE_KEY;
		$text = $_POST['word'];
		$this->db->select("*");
		$this->db->from("com_alllanguage");
		$this->db->where('status','Y');
		$query = $this->db->get();  
		$result = $query->result_array(); 
		//print_r($result);die;
		$autoTransArray = array();
		foreach($result as $lang){
			$translatedWord= '';			
			if($lang['shortName'] != 'en'){
				$url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target='.$lang['shortName'];
				//$url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl='.$lang['shortName'].'&dt=t&q="'.$text.'"';
				//echo $url;die;		
			}			
			$handle = curl_init($url);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);//We want the result to be saved into variable, not printed out
			$response = curl_exec($handle);                         
			curl_close($handle);		
			$result = json_decode($response, true);		
					//print_r($result['data']['translations'][0]);die;
			$translatedWord = $result['data']['translations'][0];
		
					//echo $translatedWord['translatedText'];die;
			if($lang['shortName'] == 'en'){
				$translatedWord['translatedText'] = $text;
			}
			$autoTransArray[] = array('lang'=>$lang['shortName'],'translatedText'=>$translatedWord['translatedText']);		
		}
		//print_r($autoTransArray);die;
		echo json_encode(array('autoTransArray'=>$autoTransArray));
		
	}

	public function addnewWord(){
		$result = $this->languageModel->getLangs();
		$this->data['data'] = $result;
		$this->load->view('language/addnewWord',$this->data);
	}

	public function editWord($word= ""){
		if($word != ""){
			$word = urldecode($word);
			$this->db->where('TEXT', $word);
			$this->db->order_by("ID","asc");
			$this->db->from("trans");
			$query = $this->db->get();  
			$result = $query->result_array();
			$results = $this->languageModel->getLangs();
			$newArray = array();
			if(!empty($result)){
				foreach($result as $rr){
					$newArray[$rr['CODE']] = $rr['TRWORD'];
				}
			}
			$this->data['langs'] = $results;
			$this->data['editData'] = $newArray;
			$this->data['mainWord'] =$word;
			$this->db->select("shortName,title");
			$this->db->from("com_alllanguage");
			
			$this->db->where('status','Y');
			$query = $this->db->get();  
			$result1 = $query->result_array();
			if(!empty($result1)){
				foreach($result1 as $k=>$rr){
					$listArray[$rr['shortName']] = $rr['title'];
				}
			}
			$this->data['list'] = $listArray;
			$this->load->view('language/addnewWord',$this->data);
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong'));
			//redirect(base_url('custom-translation'));
		}
	}

	public function createWord(){
		$postdata = $this->input->post();
		//print_r($postdata);die;
		if(!empty($postdata)){
			$mainWord = $postdata['main_word'];
			$words = $postdata['Trans'];
			if(!empty($words)){
				$I = 0;
				foreach($words as $k=>$word){
					$input_data = array(
						'TEXT' => $mainWord,
						'LANGID' =>  $I+1,
						'TRWORD' =>  $word,
						'CODE' => $k
					);
					$this->db->insert('trans',$input_data);
					$I++;
				}
			}
			redirect(base_url('custom-translation'));
			// echo json_encode(array('status'=>true));
		}else{
			redirect(base_url('custom-translation'));
			// echo json_encode(array('status'=>false));
		}
		
		//redirect(base_url('custom-translation'));
	}

	public function updateWord(){
		$postdata = $this->input->post();
		$mainWord = $postdata['main_word'];
		$i = 0;
		if(!empty($postdata['Trans'])){
			foreach($postdata['Trans'] as $k=>$trans){
				$this->db->where('TEXT',$mainWord);
				$this->db->where('CODE',$k);
				$this->db->from("trans");
				$Word = $this->db->get();
				$row = $Word->row();
				if(!empty($row)){
					$data=array('TRWORD'=>$trans);
					$this->db->where('TEXT',$mainWord);
					$this->db->where('CODE',$k);				
					$this->db->update('trans',$data);
				}else{
					$input_data = array(
						'TEXT' => $mainWord,
						'LANGID' =>  $i+1,
						'TRWORD' =>  $trans,
						'CODE' => $k
					);
					$this->db->insert('trans',$input_data);
				}
				$i++;		
			}
			redirect(base_url('custom-translation'));
		}else{
			redirect(base_url('custom-translation'));
		}
	}

	public function addnewProcess() {
		$input_data = $this->input->post();
		if(empty($input_data) || !isset($input_data['name'])  || !isset($input_data['code']) || $input_data['name'] == '' || $input_data['code'] == ''){
			$this->session->set_userdata('language_error','Please Enter required filds.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('new-language'));
		} else {
			$query = $this->db->query('SELECT * FROM com_alllanguage');
			$count = $query->num_rows();
			$input_data = array(
				'title' => $input_data['name'],
				'shortName' => $input_data['code'],
				'status' => 'Y',
				'weight'=>$count+1,
				'domainUrl'=>'',
				'picture'=>''
				);
			$this->db->insert('com_alllanguage',$input_data);
			if($this->db->insert_id() != 0){
				$this->session->set_userdata('language_sucess','Language inserted sucessfully.');
				redirect(base_url('list-language'));
			} else {
				$this->session->set_userdata('language_error','Something went wrong.');
				$this->session->set_userdata('input_data',$input_data);
				redirect(base_url('new-language'));
			}
		}
	}

	public function transDelete($word = ""){
		if($word != ""){
			$this->db->where('TEXT', $word);
			$this->db->delete('trans');
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong'));
			//redirect(base_url('custom-translation'));
		}
	}

	public function index() {
		$this->db->select("*");
		$this->db->from("com_alllanguage");
		$this->db->where('status','Y');
		$query = $this->db->get();  
		$result = $query->result_array();
		if(!empty($result)){
			foreach($result as $j=>$r){
				foreach($r as $k=>$a){
					if($k=="status"){
						$data[$j][] = $a == "Y"?'<span class="btn btn-warning btn-round">Active</span>':'<span class="btn btn-danger btn-round">Deactive</span>';
					}elseif($k=="postingTime"){
						$data[$j][] = date("d M, Y",$a);
					}else{
						$data[$j][] = $a;
					}					
				}
			}
		}	
		$this->data['data'] = $data;
		$this->load->view('language/language',$this->data);
	}

	public function edit($id = 0){
		if($id != 0 && $id != ""){
			$this->db->select("*");
			$this->db->from("com_alllanguage");
			$this->db->where('id',$id);
			$query = $this->db->get();
			$this->data['data'] = $query->row();
			$this->load->view('language/addnew',$this->data);
		}else{
			redirect(base_url('list-language'));
		}
	}

	public function editProcess($id = 0){
		$input_data = $this->input->post();
		if(!isset($id) || $id == 0 || $id == ""){
			$this->session->set_userdata('language_error','Something went wrong.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('language-edit/id/'.$id));
		} else if(empty($input_data) || !isset($input_data['name'])  || !isset($input_data['code']) || $input_data['name'] == '' || $input_data['code'] == ''){
			$this->session->set_userdata('language_error','Please Enter required filds.');
			$this->session->set_userdata('input_data',$input_data);
			redirect(base_url('language-edit/id/'.$id));
		} else {

			$input_data = array(
			'title' => $input_data['name'],
			'shortName' => $input_data['code'],
			);
			$this->db->set($input_data);
			$this->db->where('id', $id);
			$this->db->update('com_alllanguage');

			// if($this->db->affected_rows() > 0){
				$this->session->set_userdata('language_sucess','Language updated sucessfully.');
				redirect(base_url('list-language'));
			// } else {
			// 	$this->session->set_userdata('language_error','Something went wrong.');
			// 	$this->session->set_userdata('input_data',$input_data);
			// 	redirect(base_url('new-language'));
			// }
		}
	}

	public function delete($id = 0){
		if($id != 0 && $id != ""){
			$this->db->where('id', $id);
			$this->db->delete('com_alllanguage');
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong.'));
		}
	}
}