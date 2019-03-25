<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>webllywood-backend : AdLocation</title>
<?php echo $header_scripts;?>
</head>
<?php //echo  (json_encode($data));die; ?>
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
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <?php if($this->session->userdata('adv_contry_success')) { ?>
              <div class="x_content bs-example-popovers">
                <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>Sucess!</strong>
                  <?php echo $this->session->userdata('adv_contry_success');?>
                  <?php $this->session->unset_userdata('adv_contry_success');?>
                </div>
              </div>
              <?PHP } ?>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Location List</h2>
                    <!-- <div class="nav navbar-right panel_toolbox">
                      
                      <a href='<?php echo base_url('new-language');?>' class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New</a>
                      
                    </div> -->
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">                   
                    <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">                      
                      <div class="row"><div class="col-sm-12">
                          <table id="country"></table>
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
    $(document).ready(function() {
        $('#country').DataTable( {
            data: <?php echo  (json_encode($data)); ?>,
            columns: [
                { title: "Location Id" },
                { title: "Title" },
                { title: "Price" },
                { title: "Status" },
                {
                  //data: <?PHP //echo (json_encode($data)); ?>,
                  title:'Action',
                  className: "center",
                  "render": function(data, type, row, meta){
                    return '<a href="edit-country/id/'+row[0]+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a>';  
                  }
              }
            ],            
        } );    
    } );
  </script>
  </body>
</html>
