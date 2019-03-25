
<?PHP
$form_action = base_url('save-country');
if(isset($data) && !empty($data)){
  $form_action = base_url('country-edit-proccess/id/'.$id);
}
if($this->session->userdata('country_error')){
  $data = $this->session->userdata('input_data');
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

                  <form novalidate action='<?php echo $form_action;?>' method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data">

                    <span class="section">Add/Edit Country</span>
                    <?php if($this->session->userdata('country_error')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> 
                        <?php echo $this->session->userdata('country_error');?>
                        <?php $this->session->unset_userdata('country_error');?>
                      </div>
                    </div>
                    <?PHP } else if($this->session->userdata('country_success')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Success!</strong> 
                        <?php echo $this->session->userdata('country_success');?>
                        <?php $this->session->unset_userdata('country_success');?>
                      </div>
                    </div>
                    <?PHP } ?>

                    <div class="item form-group">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="all_language">
                      <?PHP if(isset($language_list) && !empty($language_list)){?>
                        <tr>
                        <?PHP foreach($language_list as $d_alllanguage){?>
                          <th> Field Name ( <?=$d_alllanguage['title']?> ) <span class="required">*</span></th>
                        <?PHP }?>
                        </tr>
                        <tr>
                        <?PHP
                        $i= 1; foreach($language_list as $d_alllanguage){?>
                          <td align="left">
                            <input type="text" name="short_name<?=$d_alllanguage['id']?>" <?php if($i==1){?>onblur="generate_url(this.value , 'fieldSlug');"
                            <?php } $i++;?> required="required" value="<?php echo !empty($data) ? $data['short_name'.$d_alllanguage['id']] : ''?>" class="text_box_percen" style="width:100%;" /></td>
                        <?PHP }?>
                        <input type="hidden" name="fieldSlug" id="fieldSlug" value="" class="text_box_percen" onblur="space_change(this.value)">
                        </tr>
                      <?PHP }?>
                      </table>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="country_code">Country Code </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="country_code" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="country_code" placeholder="e.g IN" type="text" value="<?php echo !empty($data) ? $data['country_code'] : ''   ?>">
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">Order</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select name="weight" class="form-control col-md-3 col-xs-12">
                          <?php for($i=0;$i<=$waith_len;$i++){ ?>
                            <option value="<?php echo $i;?>" <?php echo ($data['weight'] == $i ? 'selected' : '');?>><?php echo $i;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="set-checkbox-area col-md-2">
                          <input type="radio" class="add_extra_field set-checkbox" name="status" value='Y' <?PHP echo($data['status'] == 'Y' ? 'checked' : '');?>> <span class="check-text">Yes</span>
                        </div>
                        <div class="set-checkbox-area col-md-2">
                          <input type="radio" class="add_extra_field set-checkbox" name="status" value='N' <?PHP echo($data['status'] == 'N' ? 'checked' : '');?>> <span class="check-text">No</span>
                        </div>
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