<?PHP
$this->db->select('site_logo');
$logo = $this->db->get(TABLE_GENERAL_SETTINGS)->row()->site_logo;
?>
<div class="navbar nav_title" style="border: 0;">
	<a href="<?php echo base_url();?>" class="site_title">
		<img id='small-logo-pre' class="preview hide" src="<?php echo base_url('../assets/images/title_logo.png');?>" height="80%" alt="W">
		<img id='main-logo-pre' class="preview" src="<?php echo base_url('../assets/images/'. ($logo != '' ? $logo : ''));?>" height="80%" alt="Logo Not Add">
	</a>
</div>
<div class="clearfix"></div>
<!-- menu profile quick info -->
<div class="profile clearfix">
<div class="profile_pic">
<img src="<?php echo DEFAULT_ASSETS_URL;?>images/profile.png" alt="..." class="img-circle profile_img">
</div>
<div class="profile_info">
	<span>Welcome,</span>
	<h2><?php echo $this->session->userdata('adm_name') != '' ? $this->session->userdata('adm_name').' ' : '';?></h2>
	<a href="<?php echo base_url("general-settings");?>">
		<i class="fa fa-cogs fa-2x" style="float:right;margin-top:-30px;"></i>
	</a>
</div>
</div>
<!-- /menu profile quick info -->
<br>
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	<div class="menu_section active">
		<h3>General</h3>
		<ul class="nav side-menu" style="">
			<li class=""><a href="<?php echo base_url();?>"><i class="fa fa-home"></i> Dashboard </a>
			</li>
			<li class=""><a><i class="fa fa-user"></i>Admin User List <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="display: none;">
					<li><a href="<?php echo base_url("admins");?>">All Admin User</a></li>
					<li><a href="<?php echo base_url("addadmin");?>">Add New Admin User</a></li>
				</ul>
			</li>
			<li class=""><a><i class="fa fa-users"></i> User List <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="display: none;">
					<li><a href="<?php echo base_url("user/awaiting");?>">Awaiting For Email Approval</a></li>
					<li><a href="<?php echo base_url("user/active");?>">All Active User</a></li>
					<li><a href="<?php echo base_url("user/block");?>">All Block User</a></li>
				</ul>
			</li>

			<li class=""><a><i class="fa fa-envelope-o"></i> Email Templates <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="display: none;">
					<li><a href="<?php echo base_url("email/new-email");?>">New Template</a></li>
					<li><a href="<?php echo base_url("email/list-email");?>">List Template</a></li>
				</ul>
			</li>
			<li class=""><a href="<?php echo base_url('hall-of-fame');?>"><i class="fa fa-desktop"></i> Hall of Fame </a>
			</li>
			<li><a><i class="fa fa-table"></i> Category <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="">
					<li><a href="<?php echo base_url("category/new-category");?>">Add New</a></li>
					<li><a href="<?php echo base_url("category/list-category");?>">List Category</a></li>
				</ul>
			</li>
			<li><a><i class="fa fa fa-picture-o"></i> Image Management <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="">
					<li><a href="<?php echo base_url("front-images");?>">Image</a></li>
				</ul>
			</li>
			<li><a><i class="fa fa-clone"></i>Project Management <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="">
					<li><a href="<?php echo base_url("project/index");?>">Projects</a></li>
					<li><a href="<?php echo base_url("project/block-projects");?>">Blocked Project</a></li>
					<li><a href="<?php echo base_url("project/awaiting-projects");?>">Awaiting Project</a></li>
					<li><a href="<?php echo base_url("project/projects-lyrics");?>">Projects Lyrics</a></li>
				</ul>
			</li>
			<li class=""><a><i class="fa fa-globe"></i>Site Management<span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="display: none;">
					<li><a href="<?php echo base_url("site/new-page");?>">New Page</a></li>
					<li><a href="<?php echo base_url("site/list-pages");?>">List Pages</a></li>
				</ul>
			</li>
			<li class=""><a><i class="fa fa-hourglass-half"></i>Adverstiment Management<span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="display: none;">
					<li><a href="<?php echo base_url("advertise/ad-location");?>">Ad Location</a></li>
					<li><a href="<?php echo base_url("advertise/ad-country");?>">Ad Country</a></li>
					<li><a href="<?php echo base_url("advertise/ad-new-adv");?>">Add New Advertisement</a></li>
					<li><a href="<?php echo base_url("advertise/ad-show-list");?>">Admin Ad List</a></li>
					<li><a href="<?php echo base_url("advertise/ad-user-list");?>">User Ad List</a></li>
					<li><a href="<?php echo base_url("advertise/list-block");?>">Blocked Ad List</a></li>					
				</ul>
			</li>
			<li><a><i class="fa fa-server"></i>Plan Management <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="">
					<li><a href="<?php echo base_url('new-plan');?>">Add Plans</a></li>
					<li><a href="<?php echo base_url('list-plans');?>">List Of Plans</a></li>
				</ul>
			</li>
			<li><a><i class="fa fa-language"></i>Language <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="">
					<li><a href="<?php echo base_url('new-language');?>">Add Language</a></li>
					<li><a href="<?php echo base_url('list-language');?>">List Language</a></li>
					<li><a href="<?php echo base_url('custom-translation');?>">Custom Translation</a></li>
				</ul>
			</li>
			<li><a><i class="fa fa-map"></i>Country <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="">
					<li><a href="<?php echo base_url('new-country');?>">Add Country</a></li>
					<li><a href="<?php echo base_url('list-country');?>">List Country</a></li>
				</ul>
			</li>
			<li class=""><a href="<?php echo base_url('subscribe-newsletter-list');?>"><i class="fa fa-paper-plane"></i> Subscribe Newsletter </a>
			</li>
			<li class=""><a href="<?php echo base_url('error-log');?>"><i class="fa fa-exclamation-triangle"></i> Error Logs </a>
			</li>
		</ul>
	</div>
</div>
<!-- /sidebar menu -->
