<?PHP
$title = 'Update User';
if($this->session->userdata('user_error')){
  $data = (object) array(
      'name' => $this->session->userdata('input_data')['name'],
      'admin_userName' => $this->session->userdata('input_data')['admin_userName'],
      'admin_password' => $this->session->userdata('input_data')['admin_password'],
  );
  $this->session->unset_userdata('input_data');
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>webllywood-backend : <?PHP echo $title?></title>
<?php echo $header_scripts;?>
</head>
<!-- onLoad="setheight('fade1');openFancyDiv1('login-popup','fade1');"-->
<body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">            
            <!-- sidebar menu -->
		        	<?php echo $sidebar;?>
            <!-- /sidebar menu -->           
          </div>
        </div>
        <!-- top navigation -->
        <?php echo $topmenu;?>
        <!-- /top navigation -->
       
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3><?PHP echo $title;?></h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">

                  <form novalidate action='<?php echo base_url('edit-adminuser-process/id/'.$data->id)?>' method="POST" class="form-horizontal form-label-left">

                    <span class="section">User Info</span>
                    <?php if($this->session->userdata('user_error')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> 
                        <?php echo $this->session->userdata('user_error');?>
                        <?php $this->session->unset_userdata('user_error');?>
                      </div>
                    </div>
                    <?PHP } else if($this->session->userdata('user_sucess')) {?>
                     
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Sucess!</strong>
                        <?php echo $this->session->userdata('user_sucess');?>
                        <?php $this->session->unset_userdata('user_sucess');?>
                      </div>
                    </div>
                    <?PHP } ?>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="uname">Name</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="uname" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="e.g English" required="required" type="text" value="<?php echo !empty($data) ? $data->name : ''   ?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">User Name</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="fname" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="admin_userName" placeholder="e.g en" required="required" type="text" value="<?php echo !empty($data) ? $data->admin_userName : ''   ?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Password</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="lname" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="admin_password" placeholder="e.g English" required="required" type="text" value="<?php echo !empty($data) ? $data->admin_password : ''   ?>">
                      </div>
                    </div>
                    
                    <div class="ln_solid"></div>

                    <div class="form-group">
                      <div class="col-md-6 col-md-offset-3">
                        <button id="send" type="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
          <!-- /top tiles -->
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
			    <?php echo $footer;?>         
        </footer>
        <!-- /footer content -->
      </div>
    </div>
	<?php echo $footer_scripts;?>	
  </body>
</html>