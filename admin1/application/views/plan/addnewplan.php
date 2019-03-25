
<?PHP
$form_action = $label = "";
if(isset($planData) && !empty($planData)){
  $form_action = base_url('save-edit-plan/id/'.$planData->id);
  $label = 'Edit Plan';
}else{
  $form_action = base_url('save-plan');
  $label = 'Add New Plan';
}
//print_r($adData);die;
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
<?php //print_r($planData);die; ?>
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
              <h3><?PHP echo $title?></h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">

                  <form novalidate action='<?php echo $form_action;?>' method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data">

                    <span class="section"><?= $label;?></span>
                    <?php if($this->session->userdata('language_error')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> 
                        <?php echo $this->session->userdata('language_error');?>
                        <?php $this->session->unset_userdata('language_error');?>
                      </div>
                    </div>
                    <?PHP } ?>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Plan Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="planName" placeholder="e.g Basic" required="required" type="text" value="<?php echo !empty($planData) ? $planData->plan_name : ''   ?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Monthly Price  <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="monthPrice" placeholder="e.g $150" required="required" type="text" value="<?php echo !empty($planData) ? $planData->plan_price_month : ''   ?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Annual Price<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="yearPrice" placeholder="e.g $1500" required="required" type="text" value="<?php echo !empty($planData) ? $planData->plan_price_year : ''   ?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Apple Monthly ID<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="appleMId" placeholder="e.g monthly.art"  type="text" value="<?php echo !empty($planData) ? $planData->apple_monthly_id : ''   ?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Apple Yearly ID<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="appleYId" placeholder="e.g yearly.art"  type="text" value="<?php echo !empty($planData) ? $planData->apple_yearly_id  : ''   ?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Plan Icon<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input class="form-control col-md-7 col-xs-12"  name="iconImage"  required="required" type="file" id="iconImage">
                        <p>For getting you own icon <a href="https://www.flaticon.com/" target="_blank">click here</a>.</p>
                        <?php
                        $no_img = "assets/images/no_image.png";
                        if(!empty($planData)){
                          $folder = "../upload/planIcon/";
                          $pic =$planData->plan_icon;
                        ?>
                         <img style="height:150px;background: #eee;width:150px;margin-bottom: 15px;margin-top:10px;" src="<?php echo base_url($folder. $pic); ?>" onerror="this.src='<?php echo base_url($no_img); ?>'"/>
                         <?php }?>
                      </div>
                    </div>
                    <?php $featureArray = isset($planData) && !empty($planData) ? explode(',',$planData->plan_features) : [];
                    $featureArray = array_values($featureArray);
                    //echo in_array(3,array_values($featureArray));
                    
                    ?>
                      <?php // echo $adData->adtype;die; ?>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Plan Features: <span class="required">*</span>
                      </label>
                    <div class="col-md-6 col-sm-6 col-xs-12" style="display: -webkit-inline-box;">
                        <div class="checkbox p-r-10">
                            <label>
                              <input type="checkbox"  <?php if(!empty($featureArray) && in_array(0,($featureArray))) echo 'checked'; ?> name="features[]" value=0> Art
                            </label>
                        </div>
                        <div class="checkbox p-r-10">
                            <label>
                              <input type="checkbox" <?php if(!empty($featureArray) && in_array(1,($featureArray))) echo 'checked'; ?>   name="features[]" value=1> Writting
                            </label>
                        </div>
                        <div class="checkbox p-r-10">
                            <label>
                              <input type="checkbox" <?php if(!empty($featureArray) && in_array(2,($featureArray))) echo 'checked'; ?>   name="features[]" value=2> Audio
                            </label>
                        </div>
                        <div class="checkbox p-r-10">
                            <label>
                              <input type="checkbox" <?php if(!empty($featureArray) && in_array(3,($featureArray))) echo 'checked'; ?> name="features[]" value=3> Video
                            </label>
                        </div>       
                    </div>
                    </div>                    
                
                    <div class="item form-group">
                         <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Status: *</label>
                         <?php //echo  $adData->siteadd_status;die;  ?>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                          <input type="radio" class="add_extra_field set-checkbox"  <?php if($planData->plan_status=='Y'){ echo "checked=checked";}  ?> name="planStatus" value='Y' > <span class="check-text">Active</span>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                          <input type="radio" <?php if($planData->plan_status=='N'){ echo "checked=checked";}  ?> class="add_extra_field set-checkbox" name="planStatus" value='N'> <span class="check-text">Blocked</span>
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