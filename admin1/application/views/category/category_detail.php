<?PHP
$title = 'Category Add Form.';
$form_action = base_url('category/newProcess');
if(isset($data)){
  $title = 'Category Edit Form.';
  $form_action = base_url('category/editProcess/id/'.$data->id);
}
if($this->session->userdata('category_error')){
  $data = (object) array(
    'edit' => true,
    'category' => $this->session->userdata('input_data')['category'],
    'title' => $this->session->userdata('input_data')['title'],
    'weight' => $this->session->userdata('input_data')['weight'],
    'status' => $this->session->userdata('input_data')['status']
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

                  <form novalidate action='<?php echo $form_action;?>' method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data">

                    <span class="section">Category Info</span>
                    <?php if($this->session->userdata('category_sucess')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Sucess!</strong>
                        <?php echo $this->session->userdata('category_sucess');?>
                        <?php $this->session->unset_userdata('category_sucess');?>
                      </div>
                    </div>
                    <?PHP } else if($this->session->userdata('category_error')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> 
                        <?php echo $this->session->userdata('category_error');?>
                        <?php $this->session->unset_userdata('category_error');?>
                      </div>
                    </div>
                    <?PHP } ?>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Category</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="category" class="form-control col-md-7 col-xs-12">
                            <option value="0">----Please Select a category----</option>
                            <?php echo $cate_list; ?>
                        </select>
                        <p>Select Main category If you want to add as Main category </p>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Category Name <span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="title" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="title" required="required" type="text" value="<?php echo !empty($data) ? $data->title : ''   ?>">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">Order</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select name="weight" class="form-control col-md-3 col-xs-12">
                          <?php for($i=1;$i<=50;$i++){ ?>
                            <option value="<?php echo $i;?>" <?php echo ($data->weight == $i ? 'selected' : '');?>><?php echo $i;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="set-checkbox-area col-md-2">
                          <input type="radio" class="add_extra_field set-checkbox" name="status" value='Y' <?PHP echo($data->status == 'Y' ? 'checked' : '');?>> <span class="check-text">Yes</span>
                        </div>
                        <div class="set-checkbox-area col-md-2">
                          <input type="radio" class="add_extra_field set-checkbox" name="status" value='N' <?PHP echo($data->status == 'N' ? 'checked' : '');?>> <span class="check-text">No</span>
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