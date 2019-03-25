<?PHP
$this->db->select('ca.id, ca.title, ca.subCategoryID, ca.postedTime, cat1.title as cat1');
$this->db->from('com_main_post_article ca');
$this->db->order_by('ca.postedTime','desc');
$this->db->join('com_category1 cat1', 'cat1.id = ca.categoryID','left');
$this->db->where('ca.status', 'p');
$notify_array = $this->db->get()->result_array();

$sql = "SELECT ca.id, lyr.id as lyric_id, ca.title, ca.subCategoryID, ca.postedTime, concat(cu.firstName,' ',cu.lastName) as uname FROM com_post_lyrics lyr LEFT JOIN com_main_post_article ca ON ca.id = lyr.article_id LEFT JOIN com_user cu ON lyr.user_id=cu.id WHERE ca.status = 'Y' AND ca.is_delete = 'N' AND lyr.date >= now() - interval 3 day ORDER BY ca.postedTime DESC";

$lyrics_notify_array = $this->db->query($sql)->result_array();
$path = '../'.META_ARTICLE_UPLOAD_PATH;
?>
<div class="top_nav">
	<div class="nav_menu">
		<nav>
		<div class="nav toggle">
			<a id="menu_toggle"><i class="fa fa-bars"></i></a>
		</div>

		<ul class="nav navbar-nav navbar-right">
			<li class="">
				<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<img src="<?php echo base_url('assets/images/profile.png');?>" alt=""><?php echo $this->session->userdata('adm_name') != '' ? $this->session->userdata('adm_name').' ' : '';?>
					<span class=" fa fa-angle-down"></span>
				</a>
				<ul class="dropdown-menu dropdown-usermenu pull-right">
					<li>	
						<a href="<?php echo base_url("general-settings");?>">
							<i class="fa fa-cogs pull-right"></i> General Settings
						</a>
					</li>
					<li>
						<a href="<?PHP echo base_url("/logout")?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
					</li>
				</ul>
			</li>

			<!-- <li role="presentation" class="dropdown">
				<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-envelope-o"></i>
					<span class="badge bg-green">6</span>
				</a>
				<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
					<li>
					<a>
						<span class="image"><img src="<?php //echo base_url('assets/images/friend1.jpg');?>" alt="Profile Image" /></span>
						<span>
						<span>John Smith</span>
						<span class="time">3 mins ago</span>
						</span>
						<span class="message">
						Film festivals used to be do-or-die moments for movie makers. They were where...
						</span>
					</a>
					</li>
					<li>
					<a>
						<span class="image"><img src="<?php //echo base_url('assets/images/friend2.jpg');?>" alt="Profile Image" /></span>
						<span>
						<span>John Smith</span>
						<span class="time">3 mins ago</span>
						</span>
						<span class="message">
						Film festivals used to be do-or-die moments for movie makers. They were where...
						</span>
					</a>
					</li>
					<li>
					<a>
						<span class="image"><img src="<?php //echo base_url('assets/images/friend3.jpg');?>" alt="Profile Image" /></span>
						<span>
						<span>John Smith</span>
						<span class="time">3 mins ago</span>
						</span>
						<span class="message">
						Film festivals used to be do-or-die moments for movie makers. They were where...
						</span>
					</a>
					</li>
					<li>
					<a>
						<span class="image"><img src="<?php //echo base_url('assets/images/friend4.jpg');?>" alt="Profile Image" /></span>
						<span>
						<span>John Smith</span>
						<span class="time">3 mins ago</span>
						</span>
						<span class="message">
						Film festivals used to be do-or-die moments for movie makers. They were where...
						</span>
					</a>
					</li>
					<li>
					<div class="text-center">
						<a>
						<strong>See All Alerts</strong>
						<i class="fa fa-angle-right"></i>
						</a>
					</div>
					</li>
				</ul>
			</li> -->

			<li role="presentation" class="dropdown">
				<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" title="Aricles">
					<i class="fa fa-bell-o"></i>
					<?PHP if(!empty($notify_array)){?>
						<span class="badge bg-green"><?PHP echo count($notify_array);?></span>
					<?PHP }?>
				</a>
				<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
				<?PHP if(!empty($notify_array)){
					foreach($notify_array as $notify_data){?>
					<li>
						<a href="<?php echo base_url("project/awaiting-projects");?>">
							<span class="image">
							<?PHP if($notify_data['subCategoryID'] == 116){
								$img = $this->defaultdata->grabMetaPostsSeries(array('postID' => $notify_data['id'],"fieldType" => 'Photo'));
							} else {
								$img = $this->defaultdata->grabMetaPosts(array('postID' => $notify_data['id'],"fieldType" => 'Photo'));
							}
							if(trim($img->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/resize/100/'.$img->slugvalue)){
								$img = base_url('/../assets/upload/resize/100/').str_replace(" ","%20",$img->slugvalue);
							} else if(!empty($img)){
								$img = base_url($path.$img->slugvalue);
							}
							?>
								<img src="<?php echo $img;?>" alt="Profile Image" onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'"/>
							</span>
							<span class="message">"<?php echo $notify_data['title'];?>"</span>
							<span>
								<span><?php echo $notify_data['cat1'];?></span>
								<span class="time"><?php echo date('d-m-Y', $notify_data['postedTime']);?></span>
							</span>
						</a>
					</li>
					<?PHP }?>
					<li>
					<div class="text-center">
						<a href="<?php echo base_url("project/awaiting-projects");?>">
						<strong>See All Alerts</strong>
						<i class="fa fa-angle-right"></i>
						</a>
					</div>
					</li>
					<?PHP } else {?>
						<li>
					<div class="text-center">
						<strong>No Notification</strong>
					</div>
					</li>
					<?PHP }?>
				</ul>
			</li>

			<li role="presentation" class="dropdown">
				<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" title="Lyrics">
					<i class="fa fa-music"></i>
					<?PHP if(!empty($lyrics_notify_array)){?>
						<span class="badge bg-green"><?PHP echo count($lyrics_notify_array);?></span>
					<?PHP }?>
				</a>
				<ul id="menu2" class="dropdown-menu list-unstyled msg_list" role="menu">
				<?PHP if(!empty($lyrics_notify_array)){
					foreach($lyrics_notify_array as $notify_data){?>
					<li>
						<a href="<?php echo base_url("project/lyrics-detail/".$notify_data['lyric_id']);?>">
							<span class="image">
							<?PHP if($notify_data['subCategoryID'] == 116){
								$img = $this->defaultdata->grabMetaPostsSeries(array('postID' => $notify_data['id'],"fieldType" => 'Photo'));
							} else {
								$img = $this->defaultdata->grabMetaPosts(array('postID' => $notify_data['id'],"fieldType" => 'Photo'));
							}
							if(trim($img->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/resize/100/'.$img->slugvalue)){
								$img = base_url('/../assets/upload/resize/100/').str_replace(" ","%20",$img->slugvalue);
							} else if(!empty($img)){
								$img = base_url($path.$img->slugvalue);
							}
							?>
								<img src="<?php echo $img;?>" alt="Profile Image" onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'"/>
							</span>
							<span class="message">"<?php echo $notify_data['title'];?>"</span>
							<span>
								<span><?php echo $notify_data['uname'];?></span>
								<span class="time"><?php echo date('d-m-Y', $notify_data['postedTime']);?></span>
							</span>
						</a>
					</li>
					<?PHP }?>
					<li>
					<div class="text-center">
						<a href="<?php echo base_url("project/projects-lyrics");?>">
						<strong>See All Lyrics</strong>
						<i class="fa fa-angle-right"></i>
						</a>
					</div>
					</li>
					<?PHP } else {?>
						<li>
					<div class="text-center">
						<strong>No Notification</strong>
					</div>
					</li>
					<?PHP }?>
				</ul>
			</li>
		</ul>
		</nav>
	</div>
</div>