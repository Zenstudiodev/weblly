<?PHP
$title = 'Add New Language';
$form_action = base_url('new-language-process');
//print_r($data);die;
if(isset($data) && !empty($data)){
  $title = 'Edit Language';
  $form_action = base_url('edit-language-process/id/'.$data->id);
}
if($this->session->userdata('language_error')){
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
                <div class="x_title">
                  <h2>Language Info</h2>
                  <div class="nav navbar-right panel_toolbox">                      
                    <a href='https://sites.google.com/site/tomihasa/google-language-codes' class="btn btn-success btn-sm" target="new"><i class="fa fa-search"></i> Check Country Code</a>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content" style='float:none;'>
                  <form novalidate action='<?php echo $form_action;?>' method="POST" class="form-horizontal form-label-left">
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
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="e.g English" required="required" type="text" value="<?php echo !empty($data) ? $data->title : ''   ?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Code <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="code" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="code" placeholder="e.g en" required="required" type="text" value="<?php echo !empty($data) ? $data->shortName : ''   ?>">
                      </div>
                    </div>
                    
                    <!-- <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="description" required="required" name="desc" class="form-control col-md-7 col-xs-12" ><?php //echo !empty($data) ? $data->DES : ''?></textarea>
                      </div> -->
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