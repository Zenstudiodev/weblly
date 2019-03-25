
<?PHP
$form_action =  base_url('advertise/ad-location-save/id/'.$locationData->id);
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
<?php //print_r($locationData);die; 

$titles = $locationData->title;
$position = explode('--',$titles)[0];
$size = str_replace('(','',explode('--',$titles)[1]);
$size = str_replace(')','',$size);

?>
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

                    <span class="section">EDIT</span>
                    <?php if($this->session->userdata('adv_location_error')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> 
                        <?php echo $this->session->userdata('adv_location_error');?>
                        <?php $this->session->unset_userdata('adv_location_error');?>
                      </div>
                    </div>
                    <?PHP } ?>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Location: <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="location"  required="required" type="text" value="<?php echo !empty($position) ? $position : '' ?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Dimention: <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="size"  required="required" type="text" value="<?php echo !empty($locationData) ? $locationData->dimension : '' ?>">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Local Ad Price: <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="price"  required="required" type="text" value="<?php echo !empty($locationData) ? $locationData->price : '' ?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Weight:</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="weight" class="form-control col-md-7 col-xs-12">
                          <option value="0">-Select-</option>
                          <?php for($i=0;$i<=50;$i++){ ?>
                          <option <?php if($locationData->weight == $i) echo "selected";?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Status: *</label>
                      <div class="col-md-2 col-sm-2 col-xs-2">
                        <input type="radio" class="add_extra_field set-checkbox" name="adStatus" value='Y' <?php if('Y' == $locationData->status) echo "checked";?>> <span class="check-text">Online</span>
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-2">
                        <input type="radio" class="add_extra_field set-checkbox" name="adStatus" value='N' <?php if('N' == $locationData->status) echo "checked";?>> <span class="check-text">Offline</span>
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