<?PHP
$title = 'General Settings';
$form_action = base_url('general-settings-proccess');

if($this->session->userdata('setting_error')){
  $data = (object) array(
      'NAME' => $this->session->userdata('input_data')['name'],
      'CODE' => $this->session->userdata('input_data')['code'],
      'DES' => $this->session->userdata('input_data')['desc']
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
              <h3><?PHP echo $title?></h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">

                  <form  novalidate action='<?php echo $form_action;?>' method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data">

                    <span class="section">General Info</span>
                    <?php if($this->session->userdata('setting_sucess')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Success!</strong> 
                        <?php echo $this->session->userdata('setting_sucess');?>
                        <?php $this->session->unset_userdata('setting_sucess');?>
                      </div>
                    </div>
                    <?PHP } else if($this->session->userdata('setting_error')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> 
                        <?php echo $this->session->userdata('setting_error');?>
                        <?php $this->session->unset_userdata('setting_error');?>
                      </div>
                    </div>
                    <?PHP } ?>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adminName">Name <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="adminName" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="name" required="required" type="text" value="<?php echo !empty($admin_result) ? $admin_result->name : ''?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="userName">User Name <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="userName" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="userName" required="required" type="text" value="<?php echo !empty($admin_result) ? $admin_result->admin_userName : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adminEmailAddress">Email <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="adminEmailAddress" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="adminEmailAddress" required="required" type="email" value="<?php echo !empty($gen_result) ? $gen_result->adminEmailAddress : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adminnewPassword">New Password</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="adminnewPassword" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="adminnewPassword" type="password">
                        <p>(Leave the password fields blank to retain old password)</p>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ReadminnewPassword">New Password (again)</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="ReadminnewPassword" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="ReadminnewPassword" type="password">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="googleanalytic">Google Analytic <spanclass="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="googleanalytic" name='googleanalytic' class="form-control post_article_textarea col-md-7 col-xs-12"><?php echo !empty($gen_result) ? $gen_result->googleanalytic : ''?>
                        </textarea>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Contact_Email">Contact Email <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="Contact_Email" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="Contact_Email" required="required" type="email" value="<?php echo !empty($gen_result) ? $gen_result->Contact_Email : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contactEmailName">Contact Email Name <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="contactEmailName" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="contactEmailName" required="required" type="text" value="<?php echo !empty($gen_result) ? $gen_result->contactEmailName : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="super_admin_email">Super Admin Email <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="super_admin_email" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="super_admin_email" type="email" value="<?php echo !empty($gen_result) ? $gen_result->super_admin_email : ''?>">
                        <p>(All mails receive on this email)</p>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="SiteTitle">Site Title</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="SiteTitle" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="SiteTitle" type="text" value="<?php echo !empty($gen_result) ? $gen_result->SiteTitle : '' ?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="siteurl">Site Url</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="siteurl" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="siteurl" type="text" value="<?php echo !empty($gen_result) ? $gen_result->siteurl : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="projects_per_page">Projects per page <spanclass="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="projects_per_page" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="projects_per_page" required="required" type="text" value="<?php echo !empty($gen_result) ? $gen_result->projects_per_page : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fb_link">Facebook Link</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="fb_link" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="fb_link" type="text" value="<?php echo !empty($gen_result) ? $gen_result->fb_link : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="flickr_link">Flickr Link</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="flickr_link" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="flickr_link" type="text" value="<?php echo !empty($gen_result) ? $gen_result->flickr_link : ''?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="insta_link">Insta Link</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="insta_link" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="insta_link" type="text" value="<?php echo !empty($gen_result) ? $gen_result->insta_link : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="twitter_link">Twitter Url</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="twitter_link" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="twitter_link" type="text" value="<?php echo !empty($gen_result) ? $gen_result->twitter_link : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gplus_link">Google+ Url</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="gplus_link" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="gplus_link" type="text" value="<?php echo !empty($gen_result) ? $gen_result->gplus_link : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="linkedin_link">Linkedin Url</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="linkedin_link" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="linkedin_link" type="text" value="<?php echo !empty($gen_result) ? $gen_result->linkedin_link : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_data">Meta Description</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="meta_data" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="meta_data" type="text" value="<?php echo !empty($gen_result) ? $gen_result->meta_data : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_keywords">Meta keywords</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="meta_keywords" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="meta_keywords" type="text" value="<?php echo !empty($gen_result) ? $gen_result->meta_keywords : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Meta title</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="title" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="title" type="text" value="<?php echo !empty($gen_result) ? $gen_result->title : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="userName">Site Logo</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file" name="site_logo" class="site_image" style="width:80%">
                      </div>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <?PHP $img_path = '';
                        ?>
                        <img class="preview" src="<?php echo base_url('../assets/images/'. (!empty($gen_result) ? $gen_result->site_logo : ''))?>" height='50px' alt="No image">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="copyright_text">Copyright text <spanclass="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="copyright_text" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="copyright_text" required="required" type="text" value="<?php echo !empty($gen_result) ? $gen_result->copyright_text : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_watermark">Image watermark</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="image_watermark" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="image_watermark" type="text" value="<?php echo !empty($gen_result) ? $gen_result->image_watermark : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="suscribe_no_days">Free days of Subscription <spanclass="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="suscribe_no_days" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="suscribe_no_days" required="required" type="text" value="<?php echo !empty($gen_result) ? $gen_result->suscribe_no_days : ''?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="post_article_text">Post Article Text <spanclass="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="post_article_text" name='post_article_text' class="form-control post_article_textarea col-md-7 col-xs-12"><?php echo !empty($gen_result) ? $gen_result->post_article_text : ''?>
                        </textarea>
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="itunes_text">Itunes Text<spanclass="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="itunes_text" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="itunes_text" required="required" type="text" value="<?php echo !empty($gen_result) ? $gen_result->itunes_text : ''?>">
                      </div>
                    </div>

                    <span class="section">Payment Info</span>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paypalemailaddress">Paypal Email <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="paypalemailaddress" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="paypalemailaddress" required="required" type="email" value="<?php echo !empty($gen_result) ? $gen_result->paypalemailaddress : ''?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paypal_payment_type">Paypal Type <spanclass="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name='paypal_payment_type' id="paypal_payment_type" class="form-control col-md-7 col-xs-12" required="required">
                          <option value=''>Select Paypal Type</option>
                          <option value='S'<?PHP echo ($gen_result->paypal_payment_type == 'S') ? ' Selected' : '' ?>>Sandbox (For Testing)</option>
                          <option value='L'<?PHP echo ($gen_result->paypal_payment_type == 'L') ? ' Selected' : '' ?>>Live Mode</option>
                        </select>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paypalproapipassword">Paypal Password <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="paypalproapipassword" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="paypalproapipassword" required="required" type="password" value="<?php echo !empty($gen_result) ? $gen_result->paypalproapipassword : ''?>">
                        <a class="show-pass fa fa-eye form-control-show-pass"></a>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="paypalproapisignature">Paypal Signature <span class="required">*</span></label>
                      <div class="col-md-7 col-sm-7 col-xs-12">
                        <input id="paypalproapisignature" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="paypalproapisignature" required="required" type="password" value="<?php echo !empty($gen_result) ? $gen_result->paypalproapisignature : ''?>">
                        <a class="show-pass fa fa-eye form-control-show-pass"></a>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="stripe_api_key">Stripe Api Key <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="stripe_api_key" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="stripe_api_key" required="required" type="password" value="<?php echo !empty($gen_result) ? $gen_result->stripe_api_key : ''?>">
                        <a class="show-pass fa fa-eye form-control-show-pass"></a>
                      </div>
                    </div>
                    <span class="section">Contact Page Info</span>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="address" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="address" required="required" type="text" value="<?php echo !empty($gen_result) ? $gen_result->address : ''?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cemail">Contact Email <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="cemail" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="cemail" required="required" type="text" value="<?php echo !empty($gen_result) ? $gen_result->cemail : ''?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tele">Telephone<span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="telephone" class="form-control col-md-7 col-xs-12" name="tele" required="required" type="number" value="<?php echo !empty($gen_result) ? $gen_result->telephone : ''?>">
                      </div>
                    </div>
                    <span class="section">Facebook Info</span>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fb_app_id">Facebook App ID <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="fb_app_id" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="fb_app_id" required="required" type="password" value="<?php echo !empty($gen_result) ? $gen_result->fb_app_id : ''?>">
                        <a class="show-pass fa fa-eye form-control-show-pass"></a>
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