<?PHP
$title = 'New Email Template.';
$form_action = base_url('email/newEmailProcess');
if(isset($data)){
  $title = 'Email Body For '.$data->emailTitle;
  $form_action = base_url('email/editEmailProcess/id/'.$data->id);
}
if($this->session->userdata('email_error')){
  $data = (object) array(
      'emailTitle' => $this->session->userdata('input_data')['emailTitle'],
      'description' => $this->session->userdata('input_data')['description']
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
    </script>

    <script src="//cdn.ckeditor.com/4.5.2/standard/ckeditor.js"></script>
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
              <div class="col-md-3">
                <span class="section">Mail Title</span>
                <select size="13" style="font-size:11px;">
                  <option>{SITE_URL} Site URL</option>
                  <option>{SITE_LOGO} Site Logo</option>
                  <option>{SITE_EMAIL} Site Email</option>
                  <option>{REG_LINK} Registration Link</option>
                  <option>{{RESET_PASS_LINK}} Reset Password Link</option>
                  <option>{USER_NAME} User Name</option>
                  <option>{EXTRA_TEXT} Extra Message Text</option>
                  <option>{ARTICLE_NAME} Article Name</option>
                  <option>{ARTICLE_POST_DATE} Article Post Date</option>
                  <option>{AWAITING_LIST_LINK} Article Awaiting List Link</option>
                  <option>{LYRIC_DETAIL_LINK} Lyric Detail Link</option>
                  <option>{SUB_ID} Subscription ID</option>
                </select>
              </div>
              <div class="col-md-9">
                <div class="x_panel">
                  <div class="x_content">

                    <form novalidate action='<?php echo $form_action;?>' method="POST" class="form-horizontal form-label-left">

                      <span class="section">Email Info</span>
                      <?php if($this->session->userdata('email_sucess')) { ?>
                      <div class="x_content bs-example-popovers">
                        <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <strong>Sucess!</strong>
                          <?php echo $this->session->userdata('email_sucess');?>
                          <?php $this->session->unset_userdata('email_sucess');?>
                        </div>
                      </div>
                      <?PHP } else if($this->session->userdata('email_error')) { ?>
                      <div class="x_content bs-example-popovers">
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <strong>Error!</strong> 
                          <?php echo $this->session->userdata('email_error');?>
                          <?php $this->session->unset_userdata('email_error');?>
                        </div>
                      </div>
                      <?PHP } ?>
                      <label for="emailTitle">Title * :</label>
                      <input type="text" id="emailTitle" class="form-control" name="emailTitle" required="required" value='<?php echo $data->emailTitle; ?>'>

                      <label for="slug">Key * :</label>
                      <input type="text" id="slug" class="form-control" name="slug" required="required" value='<?php echo $data->slug; ?>'>
                      
                      <label for="description">Description * :</label>
                      <textarea name="EmailBody" class="ckeditor" required style="width:100%; height:400px;" >
                        <?php echo $data->description; ?>
                      </textarea>
                      <script>
                          CKEDITOR.replace('EmailBody', {
                              height: 400
                          });
                      </script>
                    
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