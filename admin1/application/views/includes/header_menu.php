
<?php //var_dump($menus);die;?>
<!-- <a class="toggle-menu" href="javascript:void(0);">
	<samp></samp>
	<samp></samp>
	<samp></samp>
</a> -->
<!-- <div class="blackOverlay"></div> -->
<!-- <ul class="menu">
	<li class="selected"><a href="<?php// echo base_url(); ?>">Home</a></li>
	<?php // echo $menus =  populate_header_menu();
	//var_dump($menus);die;
	?>
	<li><a href="<?php //echo base_url('about-us/'); ?>">About Us</a></li>
	<li><a href="<?php// echo base_url('contact-us/'); ?>">Contact</a></li>
</ul> -->

<div class="container">
	<div class="navbar-header">
		<a href="<?php echo base_url();?>" class="logo"  title="Webllywood"><img src="<?php echo DEFAULT_ASSETS_URL;?>images/<?php echo $site_logo; ?>" alt="Webllywood" ></a>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar top-bar"></span>
			<span class="icon-bar middle-bar"></span>
			<span class="icon-bar bottom-bar"></span>
		</button>
	</div>  
	<div class="navbar-collapse collapse">
		<ul id="menu-primary" class="nav navbar-nav">
		<li class="active"><a href="<?php echo base_url(); ?>">Home</a></li>
		<?php echo $menus =  populate_header_menu();
		//var_dump($menus);die;
		?>
		<li><a href="<?php echo base_url('about-us/'); ?>">About Us</a></li>
		<li><a href="<?php echo base_url('contact-us/'); ?>">Contact</a></li>
		</ul>
	</div>
</div>
