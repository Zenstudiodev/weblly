<?PHP
$title = 'Add Attiributes Form.';
$form_action = base_url('category/newAttrProccess/id/'.$cat_id);
if(isset($data)){
  $title = 'Edit Attiributes Form.';
  $form_action = base_url('category/editAttrProccess/id/'.$cat_id.'/'.$attr_id);
}
if($this->session->userdata('attr_error')){
  $data = $this->session->userdata('input_data');
  $data['edit'] = true;
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

                    <span class="section">Attribute Info</span>
                    <?php if($this->session->userdata('attr_sucess')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Sucess!</strong>
                        <?php echo $this->session->userdata('attr_sucess');?>
                        <?php $this->session->unset_userdata('attr_sucess');?>
                      </div>
                    </div>
                    <?PHP } else if($this->session->userdata('attr_error')) { ?>
                    <div class="x_content bs-example-popovers">
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> 
                        <?php echo $this->session->userdata('attr_error');?>
                        <?php $this->session->unset_userdata('attr_error');?>
                      </div>
                    </div>
                    <?PHP } ?>

                    <div class="item form-group">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="all_language">
                      <?PHP if(isset($language_list) && !empty($language_list)){?>
                        <tr>
                        <?PHP foreach($language_list as $d_alllanguage){?>
                          <th> Field Name (<?=$d_alllanguage['title']?>) <span class="required">*</span></th>
                        <?PHP }?>
                        </tr>
                        <tr>
                        <?PHP $i= 1;
                        foreach($language_list as $d_alllanguage){?>
                          <td align="left">
                            <input type="text" name="fieldName<?=$d_alllanguage['id']?>" <?php if($i==1){?>onblur="generate_url(this.value , 'fieldSlug');" <?php } $i++;?> value="<?php echo (!empty($data) ? $data['fieldName'.$d_alllanguage['id']] : '');?>" class="text_box_percen" style="width:100%;" required/>
                          </td>
                        <?PHP }?>
                          <input type="hidden" name="fieldSlug" id="fieldSlug" value="" class="text_box_percen" onblur="space_change(this.value)">
                        </tr>
                      <?PHP }?>
                      </table>
                    </div>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">Type</label>
                      <div class="col-md-4 col-sm-4 col-xs-12">
                        <select name="type" id="type" class="form-control col-md-7 col-xs-12" onchange="select_box('show_here','<?=$attr_id?>')">
                          <option value="Audio" <?php if($data['type'] =='Audio'){?> selected="selected" <?php }?>>Audio</option>
                          <option value="Video" <?php if($data['type'] =='Video'){?> selected="selected" <?php }?>>Video</option>
                          <option value="SubTitle" <?php if($data['type'] =='SubTitle'){?> selected="selected" <?php }?>>Sub Title</option>
                          <option value="File" <?php if($data['type'] =='File'){?> selected="selected" <?php }?>>File</option>
                          <option value="List" <?php if($data['type'] =='List'){?> selected="selected" <?php }?>>List</option>
                          <option value="String" <?php if($data['type'] =='String'){?> selected="selected" <?php }?>>String</option>
                          <option value="Text" <?php if($data['type'] =='Text'){?> selected="selected" <?php }?>>Text</option>
                          <option value="Float" <?php if($data['type'] =='Float'){?> selected="selected" <?php }?>>Float</option>
                          <option value="Year" <?php if($data['type'] =='Year'){?> selected="selected" <?php }?>>Year</option>
                          <option value="Boolean" <?php if($data['type'] =='Boolean'){?> selected="selected" <?php }?>>Boolean</option>
                          <option value="Photo" <?php if($data['type'] =='Photo'){?> selected="selected" <?php }?>>Photo</option>
                          <option value="GmapCoordinates" <?php if($data['type'] =='GmapCoordinates'){?> selected="selected" <?php }?>>Gmap Coordinates</option>
                          <option value="Url" <?php if($data['type'] =='Url'){?> selected="selected" <?php }?>>Url</option>
                          <option value="time" <?php if($data['type'] =='time'){?> selected="selected" <?php }?>>Time</option>
                          <optgroup label="Series">
                            <option value="SeriesNumber" <?php if($data['type'] =='SeriesNumber'){?> selected="selected" <?php }?>>Series Number</option>
                            <option value="EpisodeNumber" <?php if($data['type'] =='EpisodeNumber'){?> selected="selected" <?php }?>>Episode Number</option>
                            <option value="EpisodeTitle" <?php if($data['type'] =='EpisodeTitle'){?> selected="selected" <?php }?>>Episode Title</option>
                          </optgroup>
                        </select>
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12">
                        <a href="javascript:void(0)" onclick="clickbutton()" id="list_add_link" <?PHP if(!isset($List_html)){?>style="display: none;" <?PHP }?> class="btn btn-success btn-xs"><i class="fa fa-plus" aria-hidden="true"></i>Add New</a>
                      </div>
                    </div>

                    <div id="show_here" class="item form-group">
                      <?PHP if($data['type'] == 'List' && $List_html){ echo $List_html; }?>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">Order</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select name="weight" class="form-control col-md-3 col-xs-12">
                          <?php for($i=1;$i<=100;$i++){ ?>
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

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_required">Required</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="set-checkbox-area col-md-2">
                          <input type="radio" class="add_extra_field set-checkbox" name="is_required" value='Y' <?PHP echo($data['is_required'] == 'Y' ? 'checked' : '');?>> <span class="check-text">Yes</span>
                        </div>
                        <div class="set-checkbox-area col-md-2">
                          <input type="radio" class="add_extra_field set-checkbox" name="is_required" value='N' <?PHP echo($data['is_required'] == 'N' ? 'checked' : '');?>> <span class="check-text">No</span>
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
    <script type="text/javascript">
			function generate_url(value_control, set_control) {
				var str = value_control;
				str = str.toLowerCase();
				str = str.replace(/[^a-zA-Z0-9]+/g, "-");
				$('#' + set_control).val(str);
			}
      
			function select_box(src, id) {
        var href = $(location).attr("href");
        var len = href.indexOf("category") + 9;
        var url = href.substring(0, len) + 'getLen';
				var field_val = $("#type").val();

				if (field_val == 'List') {
					$("#list_add_link").show();
					$.ajax({
						type: "POST",
						url: url,
						data: "id=" + id,
						success: function(data) {
							document.getElementById(src).innerHTML = data;
						}
					});
				} else {
					document.getElementById(src).innerHTML = '';
					$("#list_add_link").hide();
				}
      }
      
			function space_change(value) {
				var new_string = value.replace(/ /g, "-");
				document.getElementById("fieldSlug").value = new_string;
      }
      
			$(function() {
				select_box('show_here', '<?=$id?>');
      })
      
      function clickbutton() {
        var instertable = $('#replicateme tr:first').html();
        
        $('#replicateme').append('<tr>'+instertable+'</tr>');
        var index=0;
        var index = $('#replicateme  tr').length;
        index=index-1;
      }

      function clickbutton_general(DivID) {
        var instertable = $('#'+DivID+' tr:first').html();
        $('#'+DivID).append('<tr>'+instertable+'</tr>');
        var index=0;
        var index = $('#'+DivID+'  tr').length;
        index=index-1;
      }
		</script>
		<script type="text/javascript" src="js/add_more.js"></script>
  </body>
</html>