<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>webllywood-backend : Error Log.</title>
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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Error Log</h2>
                                <div class="nav navbar-right panel_toolbox">
                                    <a href='<?php echo base_url('clear-error-log');?>' class="btn btn-info btn-sm cnf-error-clear"><i class="fa fa-trash"></i> Clear</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">                   
                                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">                      
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="cmsPage"></table>
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
            $('#cmsPage').DataTable( {
                data: <?php echo  (json_encode($data)); ?>,
                "order": [[ 0, "desc" ]],
                columns: [
                { title: "ID" },
                { title: "SEVERITY" },
                { title: "HEADING" },
                { title: "MESSAGE" },
                { title: "FILE PATH" },
                { title: "LINE" },
                { title: "STATUS CODE" },
                { title: "DATE" }
                ],
            } );
        } );
    </script>
  </body>
</html>
