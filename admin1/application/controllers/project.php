<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project extends CI_Controller {

	public $data=array();
	public $loggedout_method_arr = array('index');

	function __construct() {
		parent::__construct();
		
		$this->data=$this->defaultdata->getFrontendDefaultData();
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
	public function block(){
		$sql = "SELECT ca.*, concat(cu.firstName,' ',cu.lastName) as uname, cnt.short_name, cat1.title as cat1 FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id 
		LEFT JOIN com_countries cnt ON ca.countryID = cnt.id LEFT JOIN com_category1 cat1 ON ca.categoryID = cat1.id WHERE ca.status = 'N' ORDER BY ca.id DESC";
		
		$result = $this->db->query($sql)->result_array();
		$datag = array();
		foreach ($result as $index => $data) {			
			foreach($data as $k=>$a){
				if($k == 'id'){
					$datag[$index][0] = $a;
				}else if($k == 'uname'){
					$datag[$index][1] = $a;
				}else if($k == 'title'){
					$datag[$index][2] = $a;
				}else if($k == 'short_name'){
                    $datag[$index][3] = $a;
				}else if($k == 'cat1'){
                    $datag[$index][4] = $a;
				}else if($k == 'postedTime'){
					$datag[$index][5] =  date('m/d/Y', $a);
				} else {
					unset($data[$k]);
				}
			}
		}


		// $this->db->where('status', 'N');
		// $this->db->order_by("postedTime","desc");
		// $this->db->from("com_main_post_article");
		// $query = $this->db->get();  
		// $result = $query->result_array();
		// $datag = array();
		// //print_r($result);die;
		// foreach ($result as $index => $data) {			
		// 	foreach($data as $k=>$a){
		// 		if($k == 'id'){
		// 			$datag[$index][] = $a;
		// 		}else if($k == 'user_id'){
		// 			$this->db->where('id', $a);
		// 			$this->db->from("com_user");
		// 			$query = $this->db->get(); 
		// 			$result = $query->row(); 
		// 			$user = $result->firstName." ".$result->lastName;
		// 			$datag[$index][] = $user;
		// 		}else if($k == 'title'){
		// 			$datag[$index][] = $a;
		// 		}else if($k == 'projectDescription'){
		// 			$datag[$index][] = $a;
		// 		}else if($k == 'postedTime'){
		// 			$datag[$index][] =  date('m/d/Y', $a);
		// 		}else if($k == 'rating'){
		// 			$datag[$index][] = $a;
		// 		}else if($k == 'total_likes'){
		// 			$datag[$index][] = $a ;
		// 		}			
		// 	}
		//  }
		$this->data['data'] = $datag;
		$this->load->view('project/block',$this->data);
	}

	public function awaitingProject(){
		$sql = "SELECT ca.*, concat(cu.firstName,' ',cu.lastName) as uname, cnt.short_name, cat1.title as cat1 FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id 
		LEFT JOIN com_countries cnt ON ca.countryID = cnt.id LEFT JOIN com_category1 cat1 ON ca.categoryID = cat1.id WHERE ca.status = 'p' ORDER BY ca.id DESC";
		
		$result = $this->db->query($sql)->result_array();
		$datag = array();
		foreach ($result as $index => $data) {
			$data['video_pro_stat'] = 1;
			$id = 0;
			foreach($data as $k=>$a){
				if($k == 'id'){
					$id = $a;
					$datag[$index][0] = $a;
				}else if($k == 'uname'){
					$datag[$index][1] = $a;
				}else if($k == 'title'){
					$datag[$index][2] = $a;
				}else if($k == 'short_name'){
                    $datag[$index][3] = $a;
				}else if($k == 'cat1'){
                    $datag[$index][4] = $a;
				}else if($k == 'postedTime'){
					$datag[$index][5] =  date('m/d/Y', $a);
				} else if($k == 'video_pro_stat'){
					$check_video_procc_cond = array('article_id' => $id, 'status' => 'N');
					$have_video_in_proccess = $this->db->get_where('video_processing', $check_video_procc_cond)->row();
					if(!empty($have_video_in_proccess)){
						$datag[$index][6] = '<span class="btn btn-round btn-warning">In proccess</span>';
					} else 
					$datag[$index][6] = '<span class="btn btn-round btn-success">Completed</span>';
					
				} else {
					unset($data[$k]);
				}
			}
		}
		$this->data['data'] = $datag;
		$this->load->view('project/awaiting',$this->data);
	}

	public function approveReject(){
		$input_data = $this->input->post();//die;
		if(!empty($input_data) && $input_data['id'] != '' && $input_data['id'] != 0){
			$check_video_procc_cond = array('article_id' => $input_data['id'], 'status' => 'N');
			$have_video_in_proccess = $this->db->get_where('video_processing', $check_video_procc_cond)->row();
			if(!empty($have_video_in_proccess)){
				echo json_encode(array('status'=>false, 'message'=> "Sorry the video compresing proccess is in work, so you can't approve this article."));exit;
			}
			$set = array('status'=>$input_data['value']);
			$this->db->set($set);
			$this->db->where('id', $input_data['id']);
			$this->db->update('com_main_post_article');
			
			$this->db->where('id', $input_data['id']);
			$this->db->from("com_main_post_article");
			$query = $this->db->get();
			$result = $query->row();

			$article_title = $this->defaultdata->getArticleTitle(array('id' => $input_data['id']));
			
			$condi = array('ID' => $result->user_id);
			$this->db->select('id, firstName, lastName, emailAddress');
			$query = $this->db->get_where('com_user', $condi);
			$userData = $query->row();
			
			// print_r($userData);die;
			$slug_name = 'article-active';
			if($input_data['value'] == 'N') $slug_name = 'article-deactive';
			$this->db->where('slug',$slug_name);
			$query = $this->db->get(TABLE_EMAIL_TEMPLATE);
			$mail_data = $query->row();

			$mailcontent=htmlspecialchars_decode($mail_data->description);
			$mailcontent=str_replace('{USER_NAME}',$userData->firstName." ".$userData->lastName,$mailcontent);
			$mailcontent=str_replace('{ARTICLE_NAME}',$article_title,$mailcontent);
			$mailcontent=str_replace('{SITE_URL}',base_url(),$mailcontent);
			$mailcontent=str_replace('{SITE_TITLE}',$this->data['general_settings']->SiteTitle,$mailcontent);
			
			$to=$userData->emailAddress;
			$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email.">\n"; 
			$subject = $mail_data->emailTitle;
			$message ="<html><head></head><body>"."<style type=\"text/css\">
			<!--
			.style4 {font-size: x-small}
			-->
			</style>
			".$mailcontent."
			</body><html>";

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
			echo json_encode(array('status'=>true));
		}
	}

	// public function editProject($id=0){
	// 	if($id != 0 && $id != ""){
	// 		$this->db->where('id', $id);
	// 		$this->db->from("com_main_post_article");
	// 		$query = $this->db->get();
	// 		$result = $query->row();

	// 		$prev = 'com_';
	// 		$this->db->from('com_category1');
	// 		$this->db->where('parentID',0);
	// 		$query = $this->db->get();
	// 		$cateArray = $query->result_array();
	// 		$sql = "SELECT * FROM com_category1 WHERE parentID IN(SELECT id FROM com_category1 Where parentID = 0)";
	// 		$query1 = $this->db->query($sql);
	// 		$cateSubArray = $query1->result_array();
	// 		$sql1 = "SELECT * FROM ".$prev."category1 WHERE parentID IN(SELECT id FROM ".$prev."category1 WHERE parentID IN(SELECT id FROM ".$prev."category1 Where parentID = 0))";
	// 		$query2 = $this->db->query($sql1);
	// 		$cateSubOfSubArray = $query2->result_array();
	// 		$attr = "SELECT * FROM com_postmeta WHERE postID = ".$id;
	// 		$attrA = $this->db->query($attr);
	// 		$attrArray = $attrA->result_array();

	// 		// print_r($result);
	// 		// print_r($cateArray);
	// 		// print_r($cateSubArray);
	// 		// print_r($cateSubOfSubArray);
	// 		// print_r($attrArray);die;

	// 		$this->data['projectData'] = $result;
	// 		$this->data['cateArray'] = $cateArray;
	// 		$this->data['cateSubArray'] = $cateSubArray;
	// 		$this->data['cateSubOfSubArray'] = $cateSubOfSubArray;
	// 		$this->data['attrArray'] = $attrArray;
			
			
	// 		$this->load->view('project/editproject',$this->data);
	// 	}else{
	// 		redirect(base_url('index'));
	// 	}
	// }

	public function viewProject($id=0){
		if($id != 0 && $id != ""){
			$this->db->where('id', $id);
			$this->db->from("com_main_post_article");
			$query = $this->db->get();
			$result = $query->row();
			$this->data['projectData'] = [];
			if(!empty($result)){

				$cat_array = array($result->categoryID);
				if($result->subCategoryID != '' && $result->subCategoryID != 0){
					array_push($cat_array,$result->subCategoryID);
				}
				if($result->subSubCategoryID != '' && $result->subSubCategoryID != 0){
					array_push($cat_array,$result->subSubCategoryID);
				}	
				$this->db->select('id, title');
				$this->db->from('com_category1');
				$this->db->or_where_in('id' , $cat_array);
				$query = $this->db->get();
				$cateArray = $query->result_array();
				if(!empty($cateArray)){
					foreach($cateArray as $cat){
						if( $cat['id'] == $result->categoryID)
							$result->category = $cat['title'];
						else if( $cat['id'] == $result->subCategoryID)
							$result->subcategory = $cat['title'];
						else if( $cat['id'] == $result->subSubCategoryID)
						$result->subsubcategory = $cat['title'];
					}
				}
				
				if($result->subCategoryID != 116){

					$this->data['is_series'] = 0;
					$this->db->where('postID' , $id);
					$query = $this->db->get('com_postmeta');
					$attrArray = $query->result_array();
					if($result->categoryID == 113){
						$lyric = $this->getArticleLyrics($id, 'Y');
						if(!empty($lyric)){
							$attrArray[] = array('slugname' => 'Lyrics','slugvalue' => $lyric->lyrics_content,
							'fieldType' => 'Lyrics');
						}
					}
				} else {
					$this->data['is_series'] = 1;
					$season_cond = array('postID' => $id, 'fieldType' => 'EpisodeNumber');
					$series_list = $this->getSeriesList($season_cond, true);
					$episode_list_id = array();
					
					if(!empty($series_list)){
						foreach($series_list as $k=>$_data){
							$check_episode_cond = array('postID'=>$id,'season_id'=>$_data['season_id']);
							$all_episode_count = $this->db->select('episode_id')->from('com_postmeta_series')->where($check_episode_cond)->group_by('episode_id')->get()->result_array();
							
							$episode_list_id[$_data['season_id']] = $all_episode_count;

							foreach($all_episode_count as $k=>$_data_ser){
								$series_cond = array('postID' => $id, 'season_id' => $_data['season_id'],'episode_id'=>$_data_ser['episode_id']);
								$this->db->where($series_cond);
								$query = $this->db->get('com_postmeta_series');
								$attrArray = $query->result_array();
								$episode_list_id[$_data['season_id']][$k]['episode_array'] = $attrArray;
							}
						}
					}
					// print_r($episode_list_id);die;
					$this->data['episode_list_id'] = $episode_list_id;
				}
				$this->data['projectData'] = $result;

				$this->data['attrArray'] = $attrArray;
			}
			
			$this->load->view('project/viewproject',$this->data);
		}else{
			redirect(base_url('index'));
		}
	}

	function getArticleLyrics($article_id, $status){
		$this->db->where(array('article_id'=>$article_id, 'status'=>$status));
		$resultdata = $this->db->get('com_post_lyrics')->row();
		return $resultdata;
	}

	public function getSeriesList($cond = array(), $only_list = false){
		$series_data = $this->db->select()->from('com_postmeta_series')->where($cond)->group_by('season_id')->get()->result_array();
		if($only_list) return $series_data;
		else {
			if(!empty($series_data)){
				foreach($series_data as $k=>$_data){
					$cond = array('postID'=>$_data['postID'], 'season_id'=>$_data['season_id'], 'fieldType' => 'Photo');
					$img = $this->db->select('slugvalue')->from('com_postmeta_series')->where($cond)->limit(1)->get()->row()->slugvalue;
					$series_data[$k]['image'] = $img;
					unset($cond['fieldType']);
					$all_episode_count = $this->db->select('episode_id')->from('com_postmeta_series')->where($cond)->group_by('episode_id')->get()->num_rows();
					$series_data[$k]['total_episode'] = $all_episode_count;
				}
			}
			return $series_data;
		}
	}

	public function getAllData(){
		$this->load->model('projectmodel');
		$list = $this->projectmodel->getData();
		$datag = array();
		// echo json_encode(array('data'=>$list,"recordsTotal" => $this->projectmodel->count_all(), "recordsFiltered" => $this->projectmodel->count_filtered()));die;
		echo json_encode(array('data'=>$list,"recordsTotal" => $this->projectmodel->projec_count_all(), "recordsFiltered" => $this->projectmodel->projec_count_filtered()));die;
	}

	public function index($u = '',$uid = 0){
		$this->data['uid']=$uid;
		$this->data['categories']=$this->getCategory();
		$this->load->view('project/index',$this->data);
	}

	public function projectDelete($id=0){
		if($id != 0 && $id != ""){
			// $this->db->where('id', $id);
			// $this->db->delete("com_main_post_article");
			// echo json_encode(array('status'=>true));
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
			echo json_encode(array('status'=>true));
		}else{
			echo json_encode(array('status'=>false,'message'=>'Something went wrong.'));
		}	
	}

	public function saveData($id=0){
		///print_r($_POST);die;

		if($id != 0 && $id != ""){
			$postdata = $this->input->post();
			$fileData = $_FILES;
			if(!empty($postdata)){
				$input_data1 = array(
					'title' => $postdata['title'],
					'categoryID' =>  $postdata['categoryID'],
					'subCategoryID' =>  $postdata['subCategoryID'],
					'subSubCategoryID' =>$postdata['subSubCategoryID'],
					'projectDescription'=>$postdata['projectDescription'],
					'status'=>$postdata['status'],
				);
				$this->db->set($input_data1);
				$this->db->where('id', $id);
				$this->db->update('com_main_post_article');
			}					
			//print_r($fileData['Metadata']);die;
			if(isset($postdata['Metadata']) && !empty($postdata['Metadata'])){
				foreach($postdata['Metadata'] as $k=>$mtdata){
					$input_data2['slugvalue']=$mtdata;
					$this->db->set($input_data2);
					$this->db->where('slugname', $k);
					$this->db->where('postID', $id );
					$this->db->update('com_postmeta');
				}				
			}
			$this->db->from("com_postmeta");
			$this->db->where('postID', $id);
			$query = $this->db->get();  
			$result = $query->result_array();
			// /print_r($fileData );die;
			if(isset($fileData) && !empty($fileData)){
				foreach($fileData as $k=>$mtdata){
					$name = $this->uploadImageVideo($mtdata);
					if($name != ""){
						$input_data2['slugvalue']=$name;
						$this->db->set($input_data2);
						$this->db->where('slugname', $k);
						$this->db->where('postID', $id );
						$this->db->update('com_postmeta');	
					}							
				}
			}
			redirect(base_url('project/index'));	
		}
	}

	public function uploadImageVideo($mtdata){
		if(isset($mtdata) && !empty($mtdata)){
			$path = '../'.META_ARTICLE_UPLOAD_PATH;
			//echo $path;die;
			$file = time().$mtdata['name'];
			$img_path = $path.$file;
			if($mtdata['name'] != ''){
				if (move_uploaded_file($mtdata['tmp_name'], $img_path)) {
					$image = $mtdata['name'];
				}else{
					redirect(base_url('project/index'));
				}
				return $file;
			}
			return;
		}else{
			return;
		}	
	}

	public function getCategory(){
		$this->db->from('com_category1');
		$this->db->where('parentID',0);
		$query = $this->db->get();
		$cateArray = $query->result_array();
		return $cateArray;
	}

	public function getCatRecords(){
		$this->load-> model('projectmodel'); 
		$list = $this->projectmodel->getCatDataHF();
		$datag = array();
		echo json_encode(array('data'=>$list,"recordsTotal" => $this->projectmodel->count_all(),
		"recordsFiltered" => $this->projectmodel->count_filtered()));die;
	}

	public function getCatRecordsWithCond(){
		if(!isset($_POST['type']) || (isset($_POST['type']) && $_POST['type'] == 'year' && (isset($_POST['year']) && ($_POST['year'] == '' || $_POST['year'] == 0))) || (isset($_POST['type']) && $_POST['type'] == 'month' && (isset($_POST['year']) && ($_POST['year'] == '' || $_POST['year'] == 0)) || (isset($_POST['month']) && ($_POST['month'] == '' || $_POST['month'] == 0)))){
			echo json_encode(array('status'=>false,'message'=>'Something went wrong'));die;
		}  			
		$sql = "SELECT ca.id, ca.is_in_top, ca.user_id, cat.title as sub_cat_type, concat(cu.firstName,' ',cu.lastName) as uname, ca.title, ca.postedTime FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id";

		if(isset($_POST['Category']) && $_POST['Category'] != ''){
			$sql = $sql." LEFT JOIN com_category1 cat ON ca.subCategoryID=cat.id ";
		}
		if(isset($_POST['subCat']) && $_POST['subCat'] != ''){
			$sql = $sql." LEFT JOIN com_category1 cat ON ca.subSubCategoryID=cat.id ";
		}
		$sql = $sql." WHERE ca.status ='Y' AND ca.is_delete = 'N' ";
		
		if(isset($_POST['Category']) && $_POST['Category'] != ''){
			$cond['articles.categoryID'] = $_POST['Category'];
			$sql = $sql." AND ca.categoryID = '".$_POST['Category']."' ";
		}
		if(isset($_POST['subCat']) && $_POST['subCat'] != ''){
			$cond['articles.subCategoryID'] = $_POST['subCat'];
			$sql = $sql." AND ca.subCategoryID = '".$_POST['subCat']."' ";
		}
		$sql= $sql." GROUP BY ca.id ORDER BY ca.postedTime DESC";
		$result = $this->db->query($sql)->result_array();

		if(!empty($result)){
			$check_cond = '';
			if($_POST['type'] == 'year' && isset($_POST['year']) && $_POST['year'] != '' && $_POST['year'] != 0){
				$check_cond = ' AND  YEAR(dates) = '.$_POST['year'];
			} else if($_POST['type'] == 'month' && isset($_POST['year']) &&  ($_POST['year'] != '' && $_POST['year'] != 0 && isset($_POST['month']) &&  $_POST['month'] != '' && $_POST['month'] != 0)){
				$check_cond = ' AND  YEAR(dates) = '.$_POST['year'].' AND MONTH(dates) = '.$_POST['month'];
			}
			foreach ($result as $index => $data1) {
				$like_sql = "SELECT * FROM com_likes WHERE article_id = ".$data1['id'].$check_cond;
				$total_likes = $this->db->query($like_sql)->num_rows();
				$result[$index]['total_likes'] = $total_likes;

				$rate_sql = "SELECT ROUND(SUM(rate)/COUNT(*),2) as rating FROM com_article_rating WHERE article_id = ".$data1['id'].$check_cond;
				
				$total_rates = $this->db->query($rate_sql)->row()->rating;
				$result[$index]['rating'] = ($total_rates != '' ? $total_rates : 0);
				
				$view_sql = "SELECT * FROM com_article_views WHERE article_id = ".$data1['id'].$check_cond;
				$total_views = $this->db->query($view_sql)->num_rows();
				$result[$index]['total_views'] = $total_views;
			}
		} else {
			echo json_encode(array('status'=>true,'data'=>[]));die;
		}
        $datag = array();
        foreach ($result as $index => $data1) {
            $data1['select'] = 1;
            foreach($data1 as $k=>$a){
                if($k == 'id'){
                    $id = $a;
                    unset($data[$k]);
                }else if($k == 'user_id'){
                    $this->db->where('id', $a);
                    $this->db->from("com_user");
                    $query = $this->db->get(); 
                    $result = $query->row(); 
                    $user = $result->firstName." ".$result->lastName;
                    $datag[$index][0] = $user;
                }else if($k == 'title'){
                    $datag[$index][1] = $a;
                }else if($k == 'sub_cat_type'){
                    $datag[$index][2] =  $a;
                }else if($k == 'postedTime'){
                    $datag[$index][3] =  date('m/d/Y', $a);
                }else if($k == 'rating'){
                    $datag[$index][4] = $a;
                }else if($k == 'total_likes'){
                    $datag[$index][5] = $a ;
                }else if($k == 'total_views'){
                    $datag[$index][6] = $a ;
                }else if($k == 'select'){
					$chacked = '';
                    if(isset($_POST['type']) && $_POST['type'] != ''){
						if($_POST['type'] == 'top'){
							$isIn = 'is_in_top';
							if($data1[$isIn] == 1){
								$chacked = 'checked';
							}
                        }else{
							if($_POST['type'] == 'year' && isset($_POST['year']) &&  $_POST['year'] != '' && $_POST['year'] != 0){
								$cond_check = array('post_id'=>$id, 'value' => $_POST['year'], 'type' => 'Y');
							} else if($_POST['type'] == 'month' && isset($_POST['year']) &&  ($_POST['year'] != '' && $_POST['year'] != 0 && isset($_POST['month']) &&  $_POST['month'] != '' && $_POST['month'] != 0)){
								$val = $_POST['month'].'-'.$_POST['year'];
								$cond_check = array('post_id'=>$id, 'value' => $val, 'type' => 'M');
							}
							$this->db->where($cond_check);
							$resultdata = $this->db->get('com_hall_of_fame')->num_rows();
							if($resultdata >= 1){ $chacked = 'checked'; }
						}
                    }
                    $datag[$index][7] = '<input type="radio" data-id="'.$id.'" class="catRadio" name="catRadio" value="'.$id.'" '.$chacked.' >';
                }
            }
        }
        echo json_encode(array('status'=>true,'data'=>$datag));die;
	}

	public function lyricsList(){
		$this->load->view('project/lyrics_list',$this->data);
	}

	public function getAllLyricsData(){
		echo json_encode(array('data'=> $this->getLyricsList(),"recordsTotal" => $this->count_all_lyrics(), "recordsFiltered" => $this->count_lyrics_filtered()));die;
	}

	public function getLyricsList(){
		// print_r($_POST);die;
        $cond = [];
		$column_search = array('user_id','article_id','lyrics_content','date');
		$column_order1 = array('user_id','article_id','lyrics_content','date');
        $this->db->where($cond);
        $this->db->from('com_post_lyrics');
        $i = 0;
        foreach ($column_search as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $i++;
        }
       
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
         
        if(isset($_POST['order'])){
            $this->db->order_by($column_order1[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else { 
            $this->db->order_by('id', 'DESC');
        }
        $query = $this->db->get();
        $result = $query->result_array();
		$datag = array();
        foreach ($result as $index => $data) {
			// print_r($data);
            foreach($data as $k=>$a){
                if($k == 'id'){
                    $id = $a;
                    unset($data[$k]);
                }else if($k == 'user_id'){
					$user = 'User is deleted';
					if($a != 0 && $a != ''){
						$this->db->where('id', $a);
						$this->db->from("com_user");
						$query = $this->db->get();
						$result = $query->row();
						if(!empty($result)){
							$user = $result->firstName." ".$result->lastName;
						}
					}
					$datag[$index][0] = $user;                
                }else if($k == 'article_id'){
					$datag[$index][1] = $this->defaultdata->getArticleTitle(array('id' => $a));
                }else if($k == 'lyrics_content'){
                    $datag[$index][2] = $this->decode($a);;
                }else if($k == 'date'){
					$date = new DateTime($a);
                    $datag[$index][3] =  $date->format('d/m/Y');
                }else if($k == 'status'){
					if($a == 'Y'){
						$btn_text = '<button id="active-lyrics" data-id="'.$id.'" class="btn btn-success btn-xs"><i class="fa fa-check" aria-hidden="true"></i> Active</button>';
					} else {
						$btn_text = '<button id="deactive-lyrics" data-id="'.$id.'" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i> Deactive</button>';
					}
					$btn_text .= '<a href="'.base_url('project/lyrics-detail/'.$id).'" class="btn btn-default btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</a> <a href="'.base_url('project/remove-lyrics/'.$id).'" class="btn btn-default btn-xs" id="remove-lyric"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
					$datag[$index][4] = $btn_text;
                } 
            }
		}
        return $datag;
	}
	public function decode($str){
		$str = mb_convert_encoding($str, "UTF-8");
		$str = iconv(mb_detect_encoding($str), "UTF-8", $str);
		if( strlen( $str ) > 50 ) {
			$str =mb_substr($str,0,142, "utf-8") . '...';
		 }
		return $str ;
	}

	public function count_all_lyrics(){
		$this->db->from('com_post_lyrics');
		$query = $this->db->get();
        return $query->num_rows();
	}
	
	function count_lyrics_filtered(){
		$this->db->from('com_post_lyrics');
		$cond = [];
		$i = 0;  
		$column_search = array('user_id','article_id','lyrics_content','date');
		$column_order1 = array('user_id','article_id','lyrics_content','date');
        $this->db->where($cond);
        $i = 0;
        foreach ($column_search as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $i++;
        }
       
        $query = $this->db->get();
        return $query->num_rows();
	}

	function lyricsDetail($id=0){
        if($id != 0 && $id != ""){
			$this->db->where(array('id' => $id));
			$this->db->from('com_post_lyrics');
			$query = $this->db->get();
			$lyrics_data = $query->row_array();
			if(!empty($lyrics_data)){

				$this->db->where('id', $lyrics_data['user_id']);
				$this->db->from("com_user");
				$query = $this->db->get();
				$result = $query->row(); 
				$user = $result->firstName." ".$result->lastName;
				$lyrics_data['user_name'] = $user;
                
				$lyrics_data['title'] = $this->defaultdata->getArticleTitle(array('id' => $lyrics_data['article_id']));

				$date = new DateTime($lyrics_data['date']);
                $lyrics_data['date'] =  $date->format('d/m/Y');
			}
			$this->data['lyrics_data'] = $lyrics_data;
			$this->load->view('project/lyrics_detail',$this->data);
		} else {
			redirect(base_url('project/projects-lyrics'));
		}
	}
	
	public function changeLyricsStatus($status = '', $id=0){
		$rtn = [];
		if($status != "" && ($status == 'active' || $status == 'deactive') && $id != 0 && $id != ""){
			if($status == 'active') $status = 'N';
			else  $status = 'Y';
			$lyric_data = $this->defaultdata->getArticleLyrics(array('id'=>$id));
			if(!empty($lyric_data)){
				$article_id = $lyric_data['article_id'];
				$upd_cond = array('article_id' => $article_id);
				$upd_data = array('status' => 'N');
				$this->defaultdata->updateArticleLyrics($upd_cond, $upd_data);
				$upd_cond = array('id' => $id);
				$upd_data = array('status' => $status);
				$this->defaultdata->updateArticleLyrics($upd_cond, $upd_data);
				$rtn['status'] = true;
			} else {
				$rtn['status'] = false;
				$rtn['message'] = 'No data found.';
			}
		} else {
			$rtn['status'] = false;
			$rtn['message'] = 'Parameter missing.';
		}
		echo json_encode($rtn);
	}

	function removeLyrics($id=0){
		$rtn['status'] = false;
        if($id != 0 && $id != ""){
			$this->db->where(array('id' => $id));
			$this->db->delete('com_post_lyrics');
			$rtn['message'] = 'Lyrics deleted successfully';
			$rtn['status'] = true;
		} else {
			$rtn['message'] = 'Something went wrong';
		}
		echo json_encode($rtn);
	}
}