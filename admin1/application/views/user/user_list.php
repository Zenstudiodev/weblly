<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>webllywood-backend : Users.</title>
    <?php echo $header_scripts;?>
  </head>
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
            <div class="col-md-12 col-sm-12 col-xs-12">
              <?php if($this->session->userdata('language_sucess')) { ?>
              <div class="x_content bs-example-popovers">
                <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>Sucess!</strong>
                  <?php echo $this->session->userdata('language_sucess');?>
                  <?php $this->session->unset_userdata('language_sucess');?>
                </div>
              </div>
              <?PHP } ?>
              <div class="x_panel">
                <div class="x_title">
                  <h2>User List</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">                   
                  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">                      
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="user"></table>
                      </div>
                    </div>
                  </div>
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
    <script>
      $(document).ready(function() {
          $('#user').DataTable( {
              data: <?php echo  (json_encode($data)); ?>,
              columns: [
                  { title: "ID" },
                  { title: "USER NAME" },
                  { title: "EMAIL" },
                  { title: "USER TYPE" },
                  { title: "JOIN DATE" },
                  {
                    //data: <?PHP //echo (json_encode($data)); ?>,
                    title:'Action',
                    className: "center",
                    "render": function(data, type, row, meta){
                      return '<a href="<?php echo base_url("user-edit/id/");?>'+row[0]+'" title="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> | <a href="<?php echo base_url("user-delete/id/");?>'+row[0]+'" class="delete-item-data" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a> | <a href="<?php echo base_url("show-user/id/");?>'+row[0]+'" title="Show"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';  
                    }
                }
              ],
          } );
      } );
    </script>
  </body>
</html>
