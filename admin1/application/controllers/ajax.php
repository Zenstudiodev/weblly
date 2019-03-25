<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public $data=array();
	public $controller_arr = array('user','frontend','fbcontroller','gpluscontroller','routemanager','ajax','admin','language');
	function __construct()
	{
		parent::__construct();
		$this->load->model('userdata');
	}
	
	public function getVideo(){
		//echo $this->input->post('id');
		//die;
		$details_sql = "SELECT slugvalue FROM ".TABLE_META_ARTICLE."
                        WHERE id = ".$this->input->post('id')." AND fieldType = 'video'";
		$result = $this->db->query($details_sql)->result();
		echo $html = '<video autoplay controlslist="nodownload" class="video-js sub-video" controls preload="auto" width="640" height="264" poster="MY_VIDEO_POSTER.jpg" data-setup="{}">
					<source src="'.base_url(META_ARTICLE_UPLOAD_PATH.$result[0]->slugvalue).'" type="video/mp4">
					<source src="'.base_url(META_ARTICLE_UPLOAD_PATH.$result[0]->slugvalue).'" type="video/ogg">
						Your browser does not support the video tag.
				</video>';
	}
	
	public function giveRating(){
		$insert_arr['user_id'] = $this->session->userdata('usrid');
		$insert_arr['article_id'] = $this->input->post('article_id');
		$insert_arr['rate'] = $this->input->post('rate');
		if($this->db->insert('com_article_rating',$insert_arr)){
			$select = get_total_rating($this->input->post('article_id'));
			//$this->db->update(TABLE_MAIN_POST_ARTICLE, array("rating"=>$select))->where('id',$this->input->post('article_id'));
			$this->db->set("rating",$select)
			 ->where('id',$this->input->post('article_id'))
			->update(TABLE_MAIN_POST_ARTICLE);
		}
		echo "sucess";
	}
	
	public function getTotalRating(){
		$article_id = $this->input->post('article_id');
		echo get_total_rating($article_id);
	}
	
	public function getAds(){
		//$location_id = $this->input->post('id');
		$location_id = $this->input->post('id');
		$limit = 0;
		$project_per_page = $this->db->select('projects_per_page')->from(TABLE_GENERAL_SETTINGS)->get()->row()->projects_per_page;
		if($project_per_page >= 50){
			$limit = 3;
		}else{
			$limit = 1;
		}
		$admin_ads = $this->db->select("id ad_id,ad_name,ad_url,image,adsense_script")->FROM(TABLE_SITE_AD)->where('location_id',$location_id)->where('siteadd_status','Y')->order_by('RAND()')->limit($limit)->get()->result_array();
		//echo $this->db->last_query();
		for($i = 0; $i < count($admin_ads); $i++){
			$admin_ads[$i]['table'] = 'com_site_add';
		}
		/*foreach($admin_ads as $ads){
			$ads['table'] .= 'com_site_add';
			/*array_merge($ads, array('table' => 'com_site_add'));
			print_r($ads);
		}*/
		/*echo $this->db->last_query();
		echo "<br>";*/
		/*echo $admin_ads;
		print_r($admin_ads);
		echo count($admin_ads);*/
		if($project_per_page >= 50){
			$limit = (6-count($admin_ads));
		}else{
			$limit = (3-count($admin_ads));
		}
		
		$country_code = $this->ip_info()['country_code'];
		
		/*$user_ads = $this->db->query("SELECT id ad_id,title ad_name ,ad_url,image,adsense_script
			FROM `com_advertisement_with_us` 
			WHERE `location_id` = '$location_id'
			AND (`endDate` > '".date('Y-m-d')."'
			AND `endTime` <= '".strtotime(date('23:59:59'))."')
			AND (`startDate` <= '".date('Y-m-d')."'
			AND `startTime` >= '".strtotime(date('12:00:00'))."')
			AND `paymentStatus` = 'Y'
			AND `status` = 'Y'
			AND (`countryID` = '".$country_code."' OR localinternational = 2)
			ORDER BY RAND()
			LIMIT $limit")->result_array();*/

			$user_ads = $this->db->query("SELECT id ad_id,title ad_name ,ad_url,image,adsense_script
			FROM `com_advertisement_with_us` 
			WHERE `location_id` = '$location_id'
			AND (`endDate` > '".date('Y-m-d')."' )
			AND (`startDate` <= '".date('Y-m-d')."' )
			AND `paymentStatus` = 'Y'
			AND `status` = 'Y'
			AND (`countryID` = '".$country_code."' OR localinternational = 2)
			ORDER BY RAND()
			LIMIT $limit")->result_array();
		
		
		for($i = 0; $i < count($user_ads); $i++){
			$user_ads[$i]['table'] = 'com_advertisement_with_us';
		}
		//echo strtotime(date('12:00:00'))."<br>";
		//echo strtotime(date('23:59:59'));
		//echo $this->db->last_query();
		/*exit;*/
			$query = array_merge($admin_ads,$user_ads);
			//echo count($query);
			
			/*echo "<pre>";
			print_r($query);*/
			
			 if(!empty($query)){
				 $html = '';
				for($i = 0; $i <= count($query); $i++){
					if($query[$i]['table'] == 'com_advertisement_with_us'){
					/*$viewCount = $this->db->select('visit_count')
										  ->from('com_advertisement_with_us')
										  ->where('id', $query[$i]['ad_id'])
										  ->get()
										  ->row()
										  ->visit_count;*/
					$visit_array['ad_id'] = $query[$i]['ad_id'];
					$visit_array['ip'] = $_SERVER['REMOTE_ADDR'];
					$visit_array['visit_time'] = date('y-m-d h:i:s');
					//print_r($visit_array);
					$insert_visit_table = $this->db->insert(TABLE_AD_VISITS,$visit_array);

					if($insert_visit_table){
						$visit_count = $this->db->query("SELECT * FROM `com_site_ad_visit` WHERE ad_id = ".$query[$i]['ad_id'])->result();
					}
					//echo $this->db->last_query();

					//echo count($visit_count);
					//echo "<br>";
					$update_to_ad = $this->db->where('id', $query[$i]['ad_id'])->update('com_advertisement_with_us', array('visit_count'=>count($visit_count)));
					//echo $this->db->last_query();
					}
					if(!empty($query[$i]['image'])){
						$html .= '<div class="pnlAds"><a class="ad_link" href="javascript:void(0)" data-href="'.$query[$i]['ad_url'].'" data-id = "'.$query[$i]['ad_id'].'" data-table="'.$query[$i]['table'].'"><img src="'.base_url("upload/site_adds/".$query[$i]['image']).'" alt=""></a></div>';
					}else{
						$html .='<div class="pnlAds descripion"><p>'.$query[$i]['adsense_script'].'</p></div>';
					}
					//$i++;
				}
			}else{
				
				echo "false";
			}
			//exit;
		//echo $this->db->last_query();
		echo $html;
	}
	
	public function doLike(){
		$insert_arr['user_id'] = $this->session->userdata('usrid');
		$insert_arr['article_id'] = $this->input->post('id');
		
		if($this->db->insert(TABLE_LIKE,$insert_arr)){
			$select_likes = $this->db->select("total_likes")->from(TABLE_MAIN_POST_ARTICLE)->where(array("id"=>$insert_arr['article_id']))->get()->row()->total_likes;
			$this->db->where('id', $insert_arr['article_id'])->update(TABLE_MAIN_POST_ARTICLE, array('total_likes'=>((int)$select_likes+1)));
			/*echo $this->db->last_query();
			exit;*/
			echo $select_likes = $this->db->select("total_likes")->from(TABLE_MAIN_POST_ARTICLE)->where(array("id"=>$insert_arr['article_id']))->get()->row()->total_likes;
		}
	}
	public function do_hit_on_ad(){
		$id = $this->input->post('id');
		$table = $this->input->post('table');
		
		$clickCount = $this->db->select('clickCount')->from($table)->where('id', $id)->get()->row()->clickCount;
		//print_r($clickCount);
		
		$update = $this->db->where('id', $id)->update($table, array('clickCount'=>($clickCount+1)));
		/*echo $this->db->last_query();
		exit;*/
		if($update){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	
	
	/*****First method*****/
	function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
		{
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

   /*****Second method*****/
    function ip_info2($ip = NULL)
    {
        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
        return $query['countryCode'];
    }

    public function setPagination(){
		$data_limit = $this->input->post('data_limit');
		$page_no = $this->input->post('page_no');
		
		
			$this->session->set_userdata('limitData',$data_limit);
			$this->session->set_userdata('page_no',$page_no);
		
		print_r($this->session->userdata);
	}
}
/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */