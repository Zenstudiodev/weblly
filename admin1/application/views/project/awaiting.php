<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>webllywood-backend : Awaiting Project.</title>
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
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Awaiting Project List</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">                      
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="projects">
                                                    <tfoot>
                                                    </tfoot>
                                                </table>
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
                $('#projects').DataTable( {
                    "order": [],
                    data: <?php echo  (json_encode($data)); ?>,
                    initComplete: function () {
                    this.api().columns(1).every( function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
                        column.data().unique().sort().each( function ( d, j ) {
                            //console.log( d);
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                },
                    columns: [
                        { 
                        title: "User Name",
                            "render": function(data, type, row, meta){
                                return row[1];
                            }
                        },
                        { title: "Projet name",
                            "render": function(data, type, row, meta){
                                return row[2];
                            }
                        },
                        { title: "Country" ,
                            "render": function(data, type, row, meta){
                                return row[3];
                            }
                        },
                        { title: "Category" ,
                            "render": function(data, type, row, meta){
                                return row[4];
                            }
                        },
                        { title: "Posted date" ,
                            "render": function(data, type, row, meta){
                                return row[5];
                            }
                        },
                        { title: "Video Status" ,
                            "render": function(data, type, row, meta){
                                return row[6];
                            }
                        },
                        {
                        title:'Action',
                        className: "center",
                        "render": function(data, type, row, meta){
                            return '<a href="<?php echo base_url("project/project-view/id/");?>'+row[0]+'" title="View"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> | <a class="apPost awaiting" data-toggle="tooltip" title="Approve" data-id="'+row[0]+'" value="Y"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a> | <a href="<?php echo base_url("project-delete/id/");?>'+row[0]+'" class="delete-item-data" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
                        }
                    }
                    ]
                } );    
            } );
        </script>
    </body>
</html>