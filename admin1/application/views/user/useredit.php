<?PHP
$title = 'Update User';
if($this->session->userdata('user_error')){
  $data = (object) array(
      'userName' => $this->session->userdata('input_data')['userName'],
      'firstName' => $this->session->userdata('input_data')['firstName'],
      'lastName' => $this->session->userdata('input_data')['lastName'],
      'emailAddress' => $this->session->userdata('input_data')['emailAddress'],
      'address1' => $this->session->userdata('input_data')['address1'],
      'phone' => $this->session->userdata('input_data')['phone'],
      'status' => $this->session->userdata('input_data')['status'],
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

                  <form novalidate action='<?php echo base_url('edit-user-process/id/'.$data->id)?>' method="POST" class="form-horizontal form-label-left" novalidate="">

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
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="uname">User Name</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="uname" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="userName" placeholder="e.g English" required="required" type="text" value="<?php echo !empty($data) ? $data->userName : ''   ?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">First Name</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="fname" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="firstName" placeholder="e.g en" required="required" type="text" value="<?php echo !empty($data) ? $data->firstName : ''   ?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Last Name</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="lname" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="lastName" placeholder="e.g English" required="required" type="text" value="<?php echo !empty($data) ? $data->lastName : ''   ?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Address <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="email" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="emailAddress" placeholder="e.g en" required="required" type="text" value="<?php echo !empty($data) ? $data->emailAddress : ''   ?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Address">Address</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="Address" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="address1" placeholder="e.g English" required="required" type="text" value="<?php echo !empty($data) ? $data->address1 : ''   ?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Phone Number</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="phone" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="phone" placeholder="e.g en" required="required" type="text" value="<?php echo !empty($data) ? $data->phone : ''   ?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="utype">User Type</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <?PHP
                          if($data->userType == 1){
                            $a = '<span style="font-size:20px;"><i class="fa fa-user"></i></span>';
                          } else if($data->userType == 2) {
                            $a = '<span style="font-size:20px;"><i class="fa fa-facebook-square"></i></span>';
                          } elseif($data->userType == 3) {
                            $a = '<span style="font-size:20px;"><i class="fa fa-google-plus"></i></span>';
                          } elseif($data->userType == 4) {
                            $a = '<span style="font-size:20px;"><i class="fa fa-linkedin-square"></i></span>';
                          } 
                          echo $a;?>
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">User Status</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="status" class="form-control">
                              <option value="E" <?PHP echo ($data->status == "E" ? 'selected="selected"' : ''); ?>>
                                  Awaiting Email Conformation
                              </option>
                              <option value="Y" <?PHP echo ($data->status == "Y" ? 'selected="selected"' : ''); ?>>
                                  Activate
                              </option>
                              <option value="N" <?PHP echo ($data->status == "N" ? 'selected="selected"' : ''); ?>>
                                  Block
                              </option>
                          </select>
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