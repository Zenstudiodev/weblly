<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>webllywood-backend : UserAdList</title>
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
                            <?php if($this->session->userdata('adv_user_success')) { ?>
                            <div class="x_content bs-example-popovers">
                                <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Sucess!</strong>
                                    <?php echo $this->session->userdata('adv_user_success');?>
                                    <?php $this->session->unset_userdata('adv_user_success');?>
                                </div>
                            </div>
                            <?PHP } ?>
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Ad User Show List</h2>
                                    <!-- <div class="nav navbar-right panel_toolbox">
                                    <a href='<?php echo base_url('new-language');?>' class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New</a>
                                    </div> -->
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">                   
                                    <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">                      
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="adShowList"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /top tiles -->
                </div>
                </div>
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
                $('#adShowList').DataTable( {
                    "order": [],
                    data: <?php echo  (json_encode($data)); ?>,
                    columns: [
                        { title: "User Name",
                            "render": function(data, type, row, meta){
                                return row[2];
                            }
                        },
                        { 
                            title: "Ad name",   
                            "render": function(data, type, row, meta){
                                return row[3];
                            }
                        },
                        { title: "Location",
                            "render": function(data, type, row, meta){
                                return row[1];
                            }
                        },
                        { title: "Image",
                            "render": function(data, type, row, meta){
                                return row[6];
                            } 
                        },      
                        { title: "Payment Status" ,
                            "render": function(data, type, row, meta){
                                return row[9];
                            }
                        },
                        { title: "Start Date",
                            "render": function(data, type, row, meta){
                                return row[7];
                            }
                        },
                        { title: "End Date",
                            "render": function(data, type, row, meta){
                                return row[8];
                            }
                        }, 
                        { title: "No of Click",
                            "render": function(data, type, row, meta){
                                return row[5];
                            }
                        },
                        { title: "No of View",
                            "render": function(data, type, row, meta){
                                return row[4];
                            }
                        },
                        { title: "Status",
                            "render": function(data, type, row, meta){
                                return row[10];
                            }
                        },
                        {
                        //data: <?PHP //echo (json_encode($data)); ?>,
                        title:'Action',
                        className: "center",
                        "render": function(data, type, row, meta){
                            return '<a href="edit-user-ad/id/'+row[0]+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a> | <a href="<?php echo base_url("advertise/adUserList-delete/id/");?>'+row[0]+'" class="delete-item-data"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';  
                        }
                    }
                    ],            
                } );    
            } );
        </script>
    </body>
</html>
