<?PHP
$title = 'Add New Word';
$form_action = base_url('create-word');
if(isset($editData) && !empty($editData)){
  $title = 'Update Word';
  $form_action = base_url('update-word');
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
                  <h2>Word Info</h2>
                  <div class="nav navbar-right panel_toolbox">                      
                    <a href='<?php echo base_url('translation-words');?>' class="btn btn-success btn-sm translate"><i class="fa fa-check"></i> Auto Translate</a>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
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
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Main Word <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="name" class="form-control col-md-7 col-xs-12 oriWord" data-validate-length-range="6" data-validate-words="2" name="main_word"  required="required" <?php echo isset($mainWord) && $mainWord != '' ? readonly :''; ?> type="text" value="<?php echo isset($mainWord) && $mainWord != '' ? $mainWord:''; ?>">
                      </div>
                    </div>
                    <?php
                   //print_r($langs);die;
                   if(isset($editData) && isset($langs) && !empty($langs) && !empty($editData)){
                      foreach($langs as $lang){
                          ?>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $lang['title']; ?><span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input id="code" class="form-control col-md-7 col-xs-12"  data-validate-words="2" value="<?php echo $editData[$lang['shortName']]; ?>" name="Trans[<?php echo $lang['shortName']; ?>]" data-tranlang='<?php echo $lang['shortName'] ?>' required="required" type="text">
                            </div>
                        </div>
                        <?php                   
                      }
                    }else{             
                      if(!empty($data)){
                            foreach($data as $d){?>
                              <div class="item form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo $d['title']; ?><span class="required">*</span>
                                  </label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="code" class="form-control col-md-7 col-xs-12"  data-validate-words="2"  name="Trans[<?php echo $d['shortName'] ?>]" data-tranlang='<?php echo $d['shortName'] ?>' required="required" type="text">
                                  </div>
                                </div> 
                           <?php  }
                          } 
                        }
                    ?>                    
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
  <script>
$(document).ready(function(e) {
  $(document).on('click','.translate',function(e){
		e.preventDefault();
		var _this = $(this);
		var _url = '<?php echo base_url('language/transWord');?>';
		//var _isAuto = 1;
		//var _checkEdit = $('.yEdit').val();
		var _oWord = $('.oriWord').val();
		
		
		if(_oWord != ""){
			_this.text('Translating...'); 
			$.ajax({
				url: _url,
				data: {	word: _oWord,},
				type: "POST",
				dataType : "json",
			}).done(function( resp ) {
				console.log(resp.autoTransArray);
				var JSONString = resp.autoTransArray;
				var JSONString = JSON.stringify(JSONString);
				var JSONObject = JSON.parse(JSONString);
				_this.text('Auto Translate');
				$.each(JSONObject, function (index, value) {
          $('#trntest').html(value['translatedText']);
				  $('input[data-tranlang="'+(value['lang'])+'"]').val($('#trntest').html());
				});
			}).fail(function( xhr, status, errorThrown ) {
			}).always(function( xhr, status ) {
			});
		}else{
		}
	});
});
</script>
  <div class="hide" id="trntest"></div>
  </body>
</html>