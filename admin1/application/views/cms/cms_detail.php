<?PHP
$title = 'New CMS Page.';
$form_action = base_url('site/newSiteProcess');
if(isset($data)){
  $title = 'Edit CMS Page.';
  $form_action = base_url('site/editSiteProcess/id/'.$data->id);
}
if($this->session->userdata('site_error')){
  $data = (object) array(
      'edit' => true,
      'title' => $this->session->userdata('input_data')['title'],
      'sub_title' => $this->session->userdata('input_data')['sub_title'],
      'meta_title' => $this->session->userdata('input_data')['meta_title'],
      'description' => $this->session->userdata('input_data')['description'],
      'meta_description' => $this->session->userdata('input_data')['meta_description'],
      'key' => $this->session->userdata('input_data')['key'],
  );
  $extra_data = $this->session->userdata('extra_data');
  $this->session->unset_userdata('input_data');
  $this->session->unset_userdata('extra_data');
}
if(isset($extra_data) && !empty($extra_data)){
  $extra_data_type = $extra_data[0]->type;
  $add_qus_ans = $add_service = $add_links = '';
  $style_qus_ans = $style_service = $style_links = 'display:none;';
  if($extra_data_type == 'question-answer'){
    $add_qus_ans = 'checked';
    $style_qus_ans = '';
  } else if($extra_data_type == 'services'){
    $add_service = 'checked';
    $style_service = '';
  } else if($extra_data_type == 'links'){
    $add_links = 'checked';
    $style_links = '';
  }
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
    <script type="text/javascript" src="<?PHP echo base_url('assets/js/jquery.min.js');?>"></script>
    <script type="text/javascript" src="<?PHP echo base_url('assets/js/ddaccordion.js');?>">
    </script>
    <script type="text/javascript">
        ddaccordion.init({
            headerclass: "headerbar", //Shared CSS class name of headers group
            contentclass: "submenu", //Shared CSS class name of contents group
            revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
            mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
            collapseprev: true, //Collapse previous content (so only one open at any time)? true/false
            defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc] [] denotes no content
            onemustopen: true, //Specify whether at least one header should be open always (so never all headers closed)
            animatedefault: false, //Should contents open by default be animated into view?
            persiststate: true, //persist state of opened contents within browser session?
            toggleclass: ["", "selected"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
            togglehtml: ["", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
            animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
            oninit: function (headers, expandedindices) { //custom code to run when headers have initalized
                //do nothing
            },
            onopenclose: function (header, index, state, isuseractivated) { //custom code to run whenever a header is opened or closed
                //do nothing
            }
        })
			function generate_url(value_control, set_control) {
				var str = value_control;
				str = str.toLowerCase();
				str = str.replace(/[^a-zA-Z0-9]+/g, "-");
				$('#' + set_control).val(str);
			}
    </script>

    <script src="//cdn.ckeditor.com/4.5.2/standard/ckeditor.js" type="text/javascript"></script>
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

                      <span class="section">CMS Info</span>
                      <?php if($this->session->userdata('site_error')) { ?>
                      <div class="x_content bs-example-popovers">
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <strong>Error!</strong> 
                          <?php echo $this->session->userdata('site_error');?>
                          <?php $this->session->unset_userdata('site_error');?>
                        </div>
                      </div>
                      <?PHP } ?>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="title" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="title" required="required" type="text" value="<?php echo !empty($data) ? $data->title : ''?>" onblur="generate_url(this.value , 'key');">
                        </div>
                      </div>
                      <?PHP if(!isset($data) || isset($data->edit)){ ?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="key">Key <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="key" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="key" required="required" type="text" value="<?php echo !empty($data) ? $data->key : ''?>" readonly>
                        </div>
                      </div>
                      <?PHP }?>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub_title">Sub Title</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="sub_title" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="sub_title" required="required" type="text" value="<?php echo !empty($data) ? $data->sub_title : ''   ?>">
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Page Content</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea name="description" class="ckeditor" style="width:100%; height:400px;" >
                            <?php echo $data->description; ?>
                          </textarea>
                          <script>
                              CKEDITOR.replace('EmailBody', {
                                  height: 400
                              });
                          </script>
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_title">Meta Title</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="meta_title" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="meta_title" required="required" type="text" value="<?php echo !empty($data) ? $data->meta_title : ''   ?>">
                        </div>
                      </div>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_description">Meta Description</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea name="meta_description" class="ckeditor" style="width:100%; height:400px;" >
                            <?php echo $data->meta_description; ?>
                          </textarea>
                          <script>
                              CKEDITOR.replace('EmailBody', {
                                  height: 400
                              });
                          </script>
                        </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="add_more">Add More</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <div class="set-checkbox-area col-md-4">
                            <input type="radio" class="add_extra_field set-checkbox" name="add_qus_ans" value='1' <?PHP echo $add_qus_ans;?>> <span class="check-text">Add Question & Answer</span>
                          </div>
                          <div class="set-checkbox-area col-md-3">
                            <input type="radio" class="add_extra_field set-checkbox" name="add_qus_ans" value='2' <?PHP echo $add_service;?>> <span class="check-text">Add Services</span>
                          </div>
                          <div class="set-checkbox-area col-md-3">
                          <input type="radio" class="add_extra_field set-checkbox" name="add_qus_ans" value='3' <?PHP echo $add_links;?>> <span class="check-text">Add Links</span>
                          </div>
                          <div class="set-checkbox-area col-md-1">
                            <a class="btn btn-info remove_extra_field">Remove All</a>
                          </div>
                        </div>
                      </div>

                      <div class="parent-div">                    
                        <?PHP if($add_qus_ans == ''){?>
                        <div class="item form-group new_qus_ans_form" style="display:none;">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Questions And Answers</label>
                          <div class="col-md-8 col-sm-8 col-xs-12">
                            <input name="question[]" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" required="required" type="text">
                            <textarea name="answer[]" style="width:100%; height:150px; margin-top: 10px; resize: none;"> </textarea>
                          </div>
                          <div class="col-md-1">
                              <a class="btn btn-success add-clone-qusans"><i class="fa fa-plus" aria-hidden="true"></i></a>
                          </div>
                        </div>
                        <?PHP } else {
                          for ($i = 0; $i < count($extra_data); $i++) { ?>
                          <div class="item form-group new_qus_ans_form" style=''>
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Questions And Answers</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                              <input name="question[]" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" required="required" type="text" value="<?php echo $extra_data[$i]->name;?>">
                              <textarea name="answer[]" style="width:100%; height:150px; margin-top: 10px; resize: none;"><?php echo $extra_data[$i]->value;?></textarea>
                            </div>
                            <div class="col-md-1">
                              <?PHP if($i == 0){?>
                                <a class="btn btn-success add-clone-qusans"><i class="fa fa-plus" aria-hidden="true"></i></a>
                              <?PHP } else {?>
                                <a class="btn btn-danger remove-clone-div"><i class="fa fa-minus" aria-hidden="true"></i></a>
                              <?PHP }?>
                            </div>
                        </div>
                        <?PHP } }?>
                      </div>

                      <div class="parent-div">
                        <?PHP if($add_service == ''){?>
                        <div class="item form-group new_ser_form" style="display:none;">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Services</label>
                          <div class="col-md-8 col-sm-8 col-xs-12">
                            <input name="service_image[]" type="file" class="form-control col-md-7 col-xs-12" placeholder="Service Title">
                            <textarea name="service_description[]" style="width:100%; height:150px; margin-top: 10px; resize: none;"> </textarea>
                          </div>
                          <div class="col-md-1">
                              <a class="btn btn-success add-clone-services"><i class="fa fa-plus" aria-hidden="true"></i></a>
                          </div>
                        </div>
                        <?PHP } else {
                          for ($i = 0; $i < count($extra_data); $i++) { ?>
                          <div class="item form-group new_qus_ans_form" style=''>
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Services</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                              <input name="service_image[]" type="file" class="form-control col-md-5 col-xs-12" placeholder="Service Title"/>

                              <?php
                              $folder = "/ads_file/";
                              $pic =  $extra_data[$i]->name;
                            // echo $pic;die;
                              ?>
                              <?php if(!empty( $extra_data)){?>
                                <div class="col-md-5 col-xs-12" style="margin-top:10px;">
                                    <img style="height:150px;width:150px;margin-bottom: 15px;" src="<?php echo base_url($folder. $pic); ?>" onerror="this.src='<?php echo base_url('assets/images/no_image.png'); ?>'"/>
                                    <input type="hidden" name="<?php echo 'image'.$i; ?>" value="<?php echo  $pic; ?>">
                                </div>
                              <?php } ?>


                              <input type='hidden' name="extra_id[]" value="<?php echo $extra_data[$i]->id;?>">
                              <textarea name="service_description[]" style="width:100%; height:150px; margin-top: 10px; resize: none;"><?php echo $extra_data[$i]->value;?></textarea>
                            </div>
                            <div class="col-md-1">
                              <?PHP if($i == 0){?>
                                <a class="btn btn-success add-clone-qusans"><i class="fa fa-plus" aria-hidden="true"></i></a>
                              <?PHP } else {?>
                                <a class="btn btn-danger remove-clone-div"><i class="fa fa-minus" aria-hidden="true"></i></a>
                              <?PHP }?>
                            </div>
                        </div>
                        <?PHP } }?>
                      </div>

                      <div class="parent-div">
                        <?PHP if($add_links == ''){?>
                        <div class="item form-group new_links_form" style="display:none;">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Links for Social</label>
                          <div class="col-md-8 col-sm-8 col-xs-12">
                            <input name="link_name[]" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" required="required" type="text" placeholder="e.g Facebook">
                            <input name="link_value[]" class="form-control col-md-7 col-xs-12" style="margin-top:10px;" placeholder="e.g https://facebook.com/??"/>
                          </div>
                          <div class="col-md-1">
                              <a class="btn btn-success add-clone-links"><i class="fa fa-plus" aria-hidden="true"></i></a>
                          </div>
                        </div>
                        <?PHP } else {
                          for ($i = 0; $i < count($extra_data); $i++) { ?>
                          <div class="item form-group new_links_form" style=''>
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Links for Social</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                              <input name="link_name[]" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" required="required" type="text" value="<?php echo $extra_data[$i]->name;?>">
                              <input name="link_value[]"  class="form-control col-md-7 col-xs-12" style="margin-top:10px;" placeholder="e.g https://facebook.com/??" value="<?php echo $extra_data[$i]->value;?>"/>
                            </div>
                            <div class="col-md-1">
                              <?PHP if($i == 0){?>
                                <a class="btn btn-success add-clone-links"><i class="fa fa-plus" aria-hidden="true"></i></a>
                              <?PHP } else {?>
                                <a class="btn btn-danger remove-clone-div"><i class="fa fa-minus" aria-hidden="true"></i></a>
                              <?PHP }?>
                            </div>
                        </div>
                        <?PHP } }?>
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