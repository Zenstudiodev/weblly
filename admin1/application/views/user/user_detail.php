<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>webllywood-backend : User Detail</title>
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
            <?PHP if(!empty($user_data)){?>
              <h3>User : <?PHP echo $user_data['firstName']. ' '. $user_data['lastName'];?></h3>
            <?PHP } else {?>
              <h3>User : Not Found</h3>
            <?PHP }?>
            </div>
          </div>

          <div class="row">
            <?PHP if(!empty($user_data)){?>
            <div class="col-md-6 col-sm-6 col-xs-6">
              <div class="x_panel">
                <div class="x_content form-horizontal form-label-left">
                    <span class="section">User Info</span>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                      <div class="form-group col-md-9 col-sm-9 col-xs-12">
                        <div class="item form-group">
                          <label class="control-label col-md-4 col-sm-4 col-xs-12">User Name</label>
                          <div class="label-data col-md-8 col-sm-8 col-xs-12">
                            <span> <?php echo !empty($user_data['userName']) ? $user_data['userName'] : '--' ?></span>
                          </div>
                        </div>
                        
                        <div class="item form-group">
                          <label class="control-label col-md-4 col-sm-4 col-xs-12">First Name</label>
                          <div class="label-data col-md-8 col-sm-8 col-xs-12">
                            <span> <?php echo !empty($user_data['firstName']) ? $user_data['firstName'] : '--' ?></span>
                          </div>
                        </div>

                        <div class="item form-group">
                          <label class="control-label col-md-4 col-sm-4 col-xs-12">Last Name</label>
                          <div class="label-data col-md-8 col-sm-8 col-xs-12">
                            <span> <?php echo !empty($user_data['lastName']) ? $user_data['lastName'] : '--' ?></span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group">
                          <div class="label-data col-md-3 col-sm-3 col-xs-12">
                            <img src="<?PHP echo base_url('../assets/upload/resize/250/'.$user_data['prifile_picture']);?>" onerror="this.src='<?PHP echo base_url("assets/images/profile.png");?>'" height="80px" width="100px">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lname">Genger</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                      <?php if($user_data['gender'] != '' && $user_data['gender'] == 'M'){ ?>
                        <span>Male</span>
                      <?php } else if($user_data['gender'] != '' && $user_data['gender'] == 'F'){?>
                        <span>Female</span>
                      <?php } else {?>
                        <span>---</span>
                      <?php }?>
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email Address </label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo !empty($user_data['emailAddress']) ? $user_data['emailAddress'] : '--' ?></span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Address">Address 1</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo !empty($user_data['address1']) ? $user_data['address1'] : '--' ?></span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Address">Address 2</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo !empty($user_data['address2']) ? $user_data['address2'] : '--' ?></span>
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Phone Number</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo !empty($user_data['phone']) ? $user_data['phone'] : '--' ?></span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">User ip</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo !empty($user_data['ipaddress']) ? $user_data['ipaddress'] : '--' ?></span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="utype">User Type</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <?PHP if($user_data['userType'] != ''){
                          if($user_data['userType'] == 1){
                            $a = '<span style="font-size:20px;"><i class="fa fa-user"></i></span>  Normal user';
                          } else if($user_data['userType'] == 2) {
                            $a = '<span style="font-size:20px;"><i class="fa fa-facebook-square"></i></span>  Facebook user';
                          } elseif($user_data['userType'] == 3) {
                            $a = '<span style="font-size:20px;"><i class="fa fa-google-plus"></i></span>  Google+ user';
                          } elseif($user_data['userType'] == 4) {
                            $a = '<span style="font-size:20px;"><i class="fa fa-linkedin-square"></i></span>  Linkedin user';
                          }
                        } 
                          echo $a;?>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">User Status</label>
                        <div class="label-data col-md-9 col-sm-9 col-xs-12">
                          <?php if($user_data['status'] != '' && $user_data['status'] == 'E'){ ?>
                            <span>Awaiting Email Conformation</span>
                          <?php } else if($user_data['status'] != '' && $user_data['status'] == 'Y'){?>
                            <span>Activate</span>
                          <?php } else if($user_data['status'] != '' && $user_data['status'] == 'N'){?>
                            <span>Block</span>
                          <?php } else {?>
                            <span>---</span>
                          <?php }?>
                        </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Total Article</label>
                        <div class="label-data col-md-9 col-sm-9 col-xs-12">
                          <span><?php echo !empty($user_data['tot_article']) ? $user_data['tot_article'] : '0' ?></span>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
              <div class="x_panel">
                <div class="x_content form-horizontal form-label-left">
                    <span class="section">User Subscription</span>

                    <?PHP if(!empty($plan_data)){?>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" >Plan Name</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo !empty($plan_data['plan_name']) ? $plan_data['plan_name'] : '--' ?></span>
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Start date</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo date_format(date_create($plan_data['starting_from']),' d F Y ')?></span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">End date</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> <?php echo date_format(date_create($plan_data['ending_to']),' d F Y ')?></span>
                      </div>
                    </div>

                    <?PHP if($plan_data['payment_type'] == 'S'){?>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Payment type</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> STRIPE</span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Custome ID</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span><?PHP echo $user_data['stripe_id']?></span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Stripe ID</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span><?PHP echo $plan_data['stripe_subscription_id']?></span>
                      </div>
                    </div>
                    <?PHP } else {?>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Payment type</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span> PAYPAL</span>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fname">Paypal ID</label>
                      <div class="label-data col-md-9 col-sm-9 col-xs-12">
                        <span><?PHP echo $plan_data['stripe_subscription_id']?></span>
                      </div>
                    </div>
                    <?PHP } ?>
                                        
                    <?PHP } else {?>
                      <div class="x_content text-center no-data-color">
                        <span class="glyphicon glyphicon-warning-sign worning-class" aria-hidden="true"></span>
                        </br><span class="worning-class-small">No Subscribtion!</span>
                      </div>
                  <?PHP }?>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
            <a href='<?PHP echo base_url('project/index/user/'.$user_data['id']);?>' class="btn btn-dark">
             Show <?PHP echo $user_data['firstName']."'s";?> article</a>
            </div>
            <?PHP } else {?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content text-center no-data-color">
                  <span class="glyphicon glyphicon-warning-sign worning-class" aria-hidden="true"></span>
                  </br><span class="worning-class">No data found !</span>
                  </div>
                </div>
              </div>
            <?PHP }?>
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