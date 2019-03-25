<?php
class Defaultdata extends CI_Model {

	private $data=array();
	private $mydata=array();
	private $footerdata=array();
	private $headerdata=array();
	public $signin_data = array();
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('languageID'))
		{
			$this->session->set_userdata('languageID',1);
		}
	}
	public function getFrontendDefaultData()
	{
		$all_segment= $this->getUrlSegments();
		$this->mydata["tot_segments"]=$all_segment;
		$this->mydata['general_settings'] = $general_settings = $this->grabSettingData();
		$this->mydata['site_logo'] = $general_settings->site_logo;
		$this->mydata['copyright_text'] = $general_settings->copyright_text;
		$this->mydata['meta_data'] = $general_settings->meta_data;
		$this->mydata['meta_keywords'] = $general_settings->meta_keywords;
		$this->mydata['meta_title'] = $general_settings->title;
		$this->mydata['suscribe_no_days'] = $general_settings->suscribe_no_days;
		
		$this->data=$this->mydata;
		$this->headerdata=$this->mydata;
		$this->footerdata=$this->mydata;
		$this->signin_data = $this->mydata;
		
		$this->data["header_scripts"]=$this->load->view('includes/header_scripts',$this->mydata,true);
		$this->headerdata['all_langueges'] = $this->geAllLanguages();
		
		$this->data["header"]=$this->load->view('includes/header',$this->headerdata,true);
		
		$this->data["header_menu"]=$this->load->view('includes/header_menu',$this->headerdata,true);
		// $this->data["footer_subscribe_section"]=$this->load->view('includes/footer_subscribe_section',$this->footerdata,true);	
		$this->data["footer"]=$this->load->view('includes/footer',$this->footerdata,true);
		$this->data["footer_scripts"]=$this->load->view('includes/footer_scripts',$this->mydata,true);
		$this->data["sidebar"]=$this->load->view('includes/sidebar',$this->mydata,true);
		$this->data["topmenu"]=$this->load->view('includes/topmenu',$this->mydata,true);
		//$this->data["login_lightbox"]=$this->load->view('includes/login_lightbox',$this->signin_data,true);
	
		return $this->data;
	}
	public function latest_article(){
		 return $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where(array("categoryID"=>115))->get()->result();
		//return $this->db->last_query();
	}
	public function geAllLanguages()
	{
		$this->db->where('status','Y');
		$this->db->order_by('weight','ASC');
		return $this->db->get(TABLE_ALLLANGUAGE)->result();
	}
	public function grabLanguage($languageID)
	{
		$this->db->where('id',$languageID);
		return $this->db->get(TABLE_ALLLANGUAGE)->row();
	}
	public function getMaxTypeId($table)
	{
		$this->db->select_max('typeID');
		$arr = $this->db->get($table)->row();
		$new_typeID = 100;
		if($arr->typeID != '')
		{
			$new_typeID = $arr->typeID + 1;
		}
		return $new_typeID;
	}
	public function is_session_active()
	{
		//session_start();
		$sess_id = $this->session->userdata('usrid');
		//$sess_usr_type=$this->session->userdata('usrtype');
		if (isset($sess_id)==true && $sess_id!="")
			return 1;
		else
			return 0;
	}
	public function CheckFilename($page_filename)
	{
		$page_filename=str_replace(" ","-",$page_filename); //blank space is converted into blank
		$special_char=array("/",".htm",".","!","@","#","$","^","&","*","(",")","=","+","|","\\","{","}",":",";","'","<",">",",",".","?","\"","%");
		$page_filename=str_replace($special_char,"",$page_filename); // dot is converted into blank
		return strtolower($page_filename);      
	}
	public function getUrlSegments()
	{
		$all_segment=$this->uri->segment_array();
		if(sizeof($all_segment)==0)
		{
			$all_segment[1]=$this->router->class;
		}
		if(sizeof($all_segment)==1)
		{
			$all_segment[2]=$this->router->method;
		}
		return $all_segment;
	}
	
	public function returnPartString($string,$length)
	{
		$string = strip_tags($string);
		$s_length=strlen($string);
		if($s_length > $length)
		{
			if(strpos($string," ",$length) !== false)
			{
				$string=substr($string,0,strpos($string," ",$length));
			}
			else
			{
				$string=substr($string,0,$length);
			}
		} 
		else
		{
			$string=$string;
		}
		return stripslashes($string);
	}
	public function grabSettingData(){
		$query = $this->db->get(TABLE_GENERAL_SETTINGS);
		return $query->row();
	}
	public function getAllCountry() // DO NOT USE see  userdata->countryList()
	{
		$this->db->order_by('countryName','asc');
		$query = $this->db->get(TABLE_COUNTRIES);
		return $query->result();
	}
	public function getAllAds() // DO NOT USE see  userdata->countryList()
	{
		$this->db->order_by('id','DESC');
		$query = $this->db->get_where(TABLE_AD_WITH_US,array('user_id' => $this->session->userdata('usrid')));
		return $query->result();
	}
	public function grabCountry($c_cond = array())
	{
		if(count($c_cond) > 0)
		{
			$this->db->where($c_cond);
			$query = $this->db->get(TABLE_COUNTRIES);
			return $query->row();
		}
		else
		{
			return array();
		}
	}
	public function secureInput($data)
	{
		$return_data = array();
		foreach($data as $field => $inp_data)
		{
			//$return_data[$field]=$this->db->escape_str($inp_data);
			if(!is_array($inp_data)){
				$val = $this->security->xss_clean(trim($inp_data));
				$return_data[$field] = strip_tags($val);
			}
				
		}
		return $return_data;
	}
	public function setLoginSession($admin_data = array())
	{
		//print_r($admin_data);die;
		if(count($admin_data) > 0)
		{
			$this->session->set_userdata('admusrid',$admin_data->id);
			$this->session->set_userdata('admuname',$admin_data->admin_userName);
			$this->session->set_userdata('adm_name',$admin_data->name);
		}
	}
	public function unsetLoginSession()
	{
		$condarr['login_status']=0;
		$this->userdata->updateLoginUser($condarr, $this->session->userdata('usrid'));
		$this->session->unset_userdata('admusrid');
		$this->session->unset_userdata('admuname');
      
      	$sess_array = $this->session->all_userdata();
		foreach($sess_array as $key =>$val){		   
		   	$this->session->unset_userdata($key); // removing all session keys at a time.
		}
	}	
	
	public function getGeneratedPassword( $length = 6 ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr( str_shuffle( $chars ), 0, $length );	
		return $password;
	}
	
	public function grabStaticPost($post_cond = array())
	{
		$this->db->where($post_cond);
		return $this->db->get(TABLE_ALLPOST)->row();
	}
	public function grabStaticPage($page_cond = array())
	{
		$this->db->where($page_cond);
		return $this->db->get(TABLE_PAGES)->row();
	}


	public function getCategories($cond = array())
	{
		 return $this->db->select()->from(TABLE_CATEGORY)->where($cond)->get()->result();
		 //return $this->db->select()->from(TABLE_CATEGORY)->where($cond)->get()->result();
		 //return $this->db->last_query();
	}
	public function getCategoriesByParentId($id = ''){
		 $category = $this->db->select()->from(TABLE_CATEGORY)->where(array("id"=>$id))->get()->result();
		 return $this->db->select()->from(TABLE_CATEGORY)->where(array("parentID"=>$category[0]->parentID))->get()->result();
		 
		 //return $this->db->last_query();
	}
    public function setLimit()
    {
        	$row = $this->db->select()->from(TABLE_GENERAL_SETTINGS)->get()->row();
		
        return $row->projects_per_page;
    }

	public function getPosts($cond = array(),$limit = 0)
	{
		//return $this->setLimit();
		$limitData = $this->setLimit();
		if($limit != 0){
			$limit_exp = explode(',',$limit);
			$query = $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->limit($limit_exp[1],$limit_exp[0])->get();
		}else{
			$query = $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->limit($limitData)->get();
		}
		

		$query = $query->result();
		//echo $this->db->last_query(); exit;
		/*echo $limit;
		echo count($query);

		exit;*/
		return $query;
		// 
	}

	public function getCatIdOnArticleId($cond = array()){
		return $this->db->select('categoryID')->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->get()->row()->categoryID;
	}

	public function getCategoryAttr($cond = array()){
		return $this->db->select()->from(TABLE_CATEGORYATTR)->where($cond)->order_by('weight')->group_by('typeID')->get()->result();
	}

	public function grabMainArticle($cond = array()){
		return $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->get()->row();
	}

	function getArticleLyrics($cond = []){
		if(!empty($cond)){
			$this->db->where($cond);
			$resultdata = $this->db->get('com_post_lyrics')->row_array();
			return $resultdata;
		} else return [];
	}

	function updateArticleLyrics($cond = [], $data =[]){
		if(!empty($cond) && !empty($data)){
			$this->db->set($data);
			$this->db->where($cond);
			$this->db->update('com_post_lyrics');
		} else return 0;
	}

	public function getArticleTitle($cond = array()){
		return $this->db->select('title')->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->get()->row()->title;
	}

	public function grabPosts($cond = array()){
		return $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->get()->row();
	}

	public function grabMetaPosts($cond = array()){
		return $this->db->select()->from(TABLE_META_ARTICLE)->where($cond)->get()->row();
		//return $this->db->last_query();
	}

	public function grabMetaPostsSeries($cond = array()){
		return $this->db->select()->from(TABLE_POSTMETA_SERIES)->where($cond)->get()->row();
		//return $this->db->last_query();
	}

	public function getMetaPosts($cond = array())
	{
		return $this->db->select()->from(TABLE_META_ARTICLE)->where($cond)->get()->result();
	}

	public function getPostArticles($cond = array())
	{
		return $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->order_by('postedTime desc')->get()->result();
	}

	public function grabPostArticles($cond = array())
	{
		return $this->db->select()->from(TABLE_MAIN_POST_ARTICLE)->where($cond)->order_by('postedTime desc')->get()->row();
	}

	public function deletePostArticles($cond = array())
	{
		return $this->db->delete(TABLE_MAIN_POST_ARTICLE,$cond);
	}

	public function deletePrePostArticles($cond = array(), $catattributes_data = array()){
		if(!empty($catattributes_data)){
			foreach($catattributes_data as $cat_data){
				if($cat_data->type == 'Photo' || $cat_data->type == 'Audio' || $cat_data->type == 'Video' || $cat_data->type == 'File'){
					
					$cand_data = array('postID'=>$cond['id'],'fieldType'=>$cat_data->type);
					$article_cat_info = $this->grabPostMeta($cand_data);
					foreach($article_cat_info as $files_data){
						if($files_data['slugvalue'] != '' && (file_exists(DEFAULT_MAIN_ASSETS_URL.$files_data['slugvalue']) || file_exists(getcwd().'/assets/upload/all_post/'.$files_data['slugvalue']))){
							if($cat_data->type == 'Photo'){
								$this->remove_image_process(DEFAULT_MAIN_ASSETS_URL.$files_data['slugvalue'], $files_data['slugvalue']);
							} else {
								unlink(DEFAULT_MAIN_ASSETS_URL.$files_data['slugvalue']);
							}
						};
					}
				}
			}
		}
		$this->db->delete(TABLE_MAIN_POST_ARTICLE,$cond);
		$this->deleteArticleLike(array('article_id'=>$cond['id']));
		$this->deleteArticlePlay(array('post_id'=>$cond['id']));
		$this->deleteArticlePlaylist(array('post_id'=>$cond['id']));
		$this->deleteArticleLyriks(array('article_id'=>$cond['id']));
		$this->deleteArticleFav(array('post_id'=>$cond['id']));
		$this->deleteArticleRating(array('article_id'=>$cond['id']));
		$this->deleteArticleViews(array('article_id'=>$cond['id']));
		$this->deleteArticleComment(array('articleID'=>$cond['id']));
		$this->deleteArticleHallOfFame(array('post_id'=>$cond['id']));
		//series data
		$this->deleteArticleSeriesData(array('postID'=>$cond['id']));
		$this->deleteArticleSearchHestory(array('article_id'=>$cond['id']));
		return 1;
	}

	public function deleteArticleLike($cond = array()){
		return $this->db->delete('com_likes',$cond);
	}

	public function deleteArticlePlay($cond = array()){
		return $this->db->delete('com_play',$cond);
	}

	public function deleteArticlePlaylist($cond = array()){
		return $this->db->delete('com_playlist_item',$cond);
	}

	public function deleteArticleLyriks($cond = array()){
		return $this->db->delete('com_post_lyrics',$cond);
	}

	public function deleteArticleFav($cond = array()){
		return $this->db->delete('com_user_fav',$cond);
	}

	public function deleteArticleRating($cond = array()){
		return $this->db->delete('com_article_rating',$cond);
	}

	public function deleteArticleViews($cond = array()){
		return $this->db->delete('com_article_views',$cond);
	}

	public function deleteArticleComment($cond = array()){
		return $this->db->delete('com_article_comment',$cond);
	}

	public function deleteArticleHallOfFame($cond = array()){
		return $this->db->delete('com_hall_of_fame',$cond);
	}

	public function deleteArticleSeriesData($cond = array()){
		return $this->db->delete('com_series_data',$cond);
	}

	public function deleteArticleSearchHestory($cond = array()){
		return $this->db->delete('com_user_search_history',$cond);
	}

	public function checkLogin(){
		if($this->session->userdata('admuname') == ''){
			redirect(base_url('login'));
		} else return 1;
	}

	public function getallAdv(){
		$this->db->select("id");
		$query = $this->db->get('com_advertisement_with_us');
		$u_sdv = $query->num_rows();
		$query1 = $this->db->get('com_site_add');
		return ($u_sdv + $query1->num_rows());
	}

	function remove_image_process($file, $file_name) {
		if(file_exists($file)){
			chmod($file, 0777);
			unlink($file);
		}
		$ara = [100, 250, 400, 600];
		foreach($ara as $val){
			$resize_path = DEFAULT_RESIZE_ASSETS_URL.$val.'/'.$file_name;
			if(file_exists($resize_path)){
				chmod($resize_path, 0777);
				unlink($resize_path);
			}
		}
	}

	public function grabPostMeta($cond = array()) {
		return $this->db->select()->from(TABLE_META_ARTICLE)->where($cond)->get()->result_array();
	}

	public function grabCategories($cond = array())
	{
		return $this->db->select()->from(TABLE_CATEGORY)->where($cond)->get()->row();
	}

	//¶30102015 S
	function getComments($cond = array())
    {
        return $this->db->select()->from(TABLE_ARTICLE_COMMENT)->where($cond)->order_by('postedTime', 'desc')->get()->result();
    }

    function grabComments($cond = array())
    {
        return $this->db->select()->from(TABLE_ARTICLE_COMMENT)->where($cond)->get()->row();
    }

    function insertComment($input_data = array())
    {
		/*echo "<pre>";
		print_r($input_data);
		exit;*/
        if(empty($input_data)) return 0;
        $this->db->insert(TABLE_ARTICLE_COMMENT,$input_data);
		//echo $this->db->last_query();
        return $this->db->insert_id();
    }
	function getFrontImages()
	{
		return $this->db->select()->from(TABLE_FRONTEND_IMAGES)->get()->result();
	}
	
	function searchQueryProcessor($data,$limitDta = '')
	{
		if(isset($data) && isset($data['token']) && $data['token'] != ''){
			$limit = 10 ;
			if( isset($data['Page'])) {
				$page = $data['Page'];
				$offset = $limit * ($page-1) ;
			}else {
				$page = 0;
				$offset = 0;
			}
		}else{
			$limit = $this->setLimit(); 
		}		
		$category=$data['ct'];
		$country=$data['country'];
		$language=$data['language'];
		$year=$data['year'];
		$q=$data['q'];
		$date=$data['date'];
		$rate=$data['rate'];
		//echo $country;
		//echo $category;echo $country; echo $language; echo $year;echo $q;exit;
		
		$sql = "SELECT  ca.* , cat.type as cat_type, cat.title as cat_title ,cp.slugname as slugname, cp.slugvalue as slugvalue   FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id LEFT JOIN com_category1 cat ON ca.categoryID=cat.parentID LEFT JOIN com_postmeta cp ON ca.id=cp.postID WHERE ca.status ='Y' ";
		
		if($category!='' && $category!='0')
		{
			$sql=$sql." and (ca.categoryID ='".$category."' OR ca.subCategoryID ='".$category."') ";
		}
		if($country!='' && $country!='0')
		{
			$sql=$sql." and ca.countryID='".$country."' ";
		}
		/*if($language!='')
		{
			$sql=$sql." and com_postmeta.slugname='cat_language' and com_postmeta.slugvalue like '%$language%' ";
		}*/
		if($year!='')
		{
			$sql=$sql." and (cp.slugname='cat_year' and cp.slugvalue= '$year') ";
		}
		if($q!='')
		{
			$sql=$sql." and (ca.projectDescription LIKE '%$q%' OR cu.firstName LIKE '%$q%' OR cu.lastName LIKE '%$q%' OR ca.title LIKE '%$q%' OR cat.title LIKE '%$q%')";
		}
		
        $sql=$sql." group by id ORDER BY cp.postedTime ".$date.",ca.rating ".$rate;
		
		if(isset($data) && isset($data['token']) && $data['token'] != ''){
			$sql = $sql." LIMIT 0,".$limit;
		}else{
			if($limitDta != 0){
				$limit_exp = explode(',',$limitDta);
				$sql = $sql." LIMIT ".$limit_exp[0].",".$limit_exp[1];
			}else{
				$sql = $sql." LIMIT 0,".$limit;
			}
		}	
		
		$resultdata = $this->db->query($sql)->result();
	   // echo "<pre>";print_r($resultdata);exit;
		return $resultdata;
	}
	function innerSearchQueryProcessor($data,$limitDta = 0)
	{
		$limit = $this->setLimit();
		
		$category=$data['ct'];
		$country=$data['country'];
		$language=$data['language'];
		$year=$data['year'];
		$q=$data['q'];
		$cat_type=$data['cat_type'];
		$date=$data['date'];
		$rate=$data['rate'];
		//echo $country;
		//echo $category;echo $country; echo $language; echo $year;echo $q;exit;
		
		$sql = "SELECT  ca.* , cat.type as cat_type, cat.title as cat_title ,cp.slugname as slugname, cp.slugvalue as slugvalue   FROM com_main_post_article ca LEFT JOIN com_user cu ON ca.user_id=cu.id LEFT JOIN com_category1 cat ON ca.categoryID=cat.parentID LEFT JOIN com_postmeta cp ON ca.id=cp.postID WHERE ca.status ='Y' ";
		
		if($category!='' && $category!='0')
		{
			/*if($cat_type!='VID')
			{
				$sql=$sql." and ca.subCategoryID='".$category."'";
			}
			else if($cat_type=='VID')
			{*/
				$sql=$sql." and (ca.subSubCategoryID ='".$category."' OR ca.subCategoryID ='".$category."' OR categoryID = '".$category."') ";
			//}
		}
		if($country!='' && $country!='0')
		{
			$sql=$sql." and ca.countryID='".$country."'";
		}
		/*if($language!='')
		{
			$sql=$sql." and com_postmeta.slugname='cat_language' and com_postmeta.slugvalue like '%$language%' ";
		}*/
		if($year!='')
		{
			$sql=$sql." and (cp.slugname='cat_year' and cp.slugvalue= '$year') ";
		}
		if($q!='')
		{
			$sql=$sql." and (ca.projectDescription LIKE '%$q%' OR cu.firstName LIKE '%$q%' OR cu.lastName LIKE '%$q%' OR ca.title LIKE '%$q%' OR ca.title LIKE '%$q%') ";
		}
		
        $sql=$sql." group by id ORDER BY ";
		if($date != ''){
			$sql=$sql.' cp.postedTime '.$date;
		}
		if($rate != ''){
			if($date != ''){
				$sql = $sql." ,ca.rating ".$rate;	
			} else {
				$sql = $sql." ca.rating ".$rate;
			}
		}
		if($date =='' && $rate ==''){
			$sql = $sql." cp.postedTime ";	
		}
		if($limitDta != 0){
			$limit_exp = explode(',',$limitDta);
			$sql = $sql." LIMIT ".$limit_exp[0].",".$limit_exp[1];
		}else{
			$sql = $sql." LIMIT 0,".$limit;
		}
			
		/*echo $sql;
		exit;*/
		$resultdata = $this->db->query($sql)->result();
	   // echo "<pre>";print_r($resultdata);exit;
		return $resultdata;
	}
	function grabCategoryDetails($cond)
	{
		 $result= $this->db->select()->from(TABLE_CATEGORY)->where($cond)->get()->row();
		 
		 return $result;
	}
	function arbitaryArticle()
	{
		$sql="select * from ".TABLE_MAIN_POST_ARTICLE." where status='Y' ORDER BY id DESC LIMIT 4 ";
		$resultdata = $this->db->query($sql)->result();
		return $resultdata;
	}
	function getMyArticleHistoryId($current_user_id)
	{
		$sql="select DISTINCT article_id from ".TABLE_USER_SEARCH_HISTORY." where user_id='".$current_user_id."'  ORDER BY search_time DESC  limit 4";
		
		$resultdata = $this->db->query($sql)->result();
		return $resultdata;
	}
	function deleteHistory($cond)
	{
	
		return $this->db->delete(TABLE_USER_SEARCH_HISTORY,$cond);
		
	}
	
	public function wmText($imgpath,$text="TEST",$valign="middle",$halign="center",$padding='10',$fontsize=22,$color="ff0000",$opacity=50,$shadow_color='FFF5F5')
   {
		$this->load->library('image_lib');
       $config['source_image']     = $imgpath; //The image path,which you would like to watermarking
       $config['wm_text']          = $text;
       $config['wm_type']          = 'text';
       //$config['wm_font_path']     = DEFAULT_ASSETS_URL.'fonts/fontawesome-webfont.ttf';
       $config['wm_font_size']     = $fontsize;
       $config['wm_font_color']    = $color;
		$config['wm_opacity']       = $opacity;
       $config['wm_vrt_alignment'] = $valign;
       $config['wm_hor_alignment'] = $halign;
       $config['wm_padding']       = $padding;
       //$config['wm_shadow_color']  = $shadow_color;
       // $config['wm_vrt_offset']    = 150;
       // $config['wm_hor_offset']    = 100;
	   //print_r($config);exit;
       $this->image_lib->initialize($config);
       
       if (!$this->image_lib->watermark()) {
           echo $this->image_lib->display_errors();
       }
       
   }
   public function wmOverlay($imgpath,$overlaypath,$opacity=50,$valign="middle",$halign="center")
   {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
       $config['source_image']     = './assets/'.$imgpath;
       $config['wm_type']          = 'overlay';
       $config['wm_overlay_path']  = './assets/'.$overlaypath; //the overlay image
       $config['wm_opacity']       = $opacity;
       $config['wm_vrt_alignment'] = $valign;
       $config['wm_hor_alignment'] = $halign;
       
       $this->image_lib->initialize($config);
       
       if (!$this->image_lib->watermark()) {
           echo $this->image_lib->display_errors();
       }
       
   }

}
?>