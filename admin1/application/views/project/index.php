<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>webllywood-backend : Project.</title>
<?php echo $header_scripts;?>
</head>
<?php //print_r  ($categories);die; ?>
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
          <div class="clearfix"></div>
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
                <h2>Project List</h2>                
                <div class="clearfix"></div>
              </div>
              <div class="category">
                <span>Category By:</span>
                <select class="catId form-control">
                  <option value="">--Select category--</option>
                  <?php foreach($categories as $cat){ ?>
                    <option value=<?=  $cat['id']?>><?=  $cat['title'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="x_content">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">                      
                  <div class="row">
                    <div class="col-sm-12">
                      <table id="projects">
                        <thead>
                          <tr>
                              <th width="5%">User Name</th>
                              <th>Projet name</th>
                              <th width="5%">Contry</th>
                              <th width="5%">Posted date</th>
                              <th width="5%">Rating</th>
                              <th width="5%">Total Likes</th>
                              <th width="5%">Total Views</th>
                              <th width="5%">Soft Delete</th>
                              <th>Action</th>
                              <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>                
                      </table>
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
      dataTable('');
      $('.catId').on('change', function(e){
         var abc = $(this).val();
         dataTable(abc)
      });
      
      //alert(abc);
      
     
        // $('#projects').DataTable( {
        //     data: <?php //echo  (json_encode($data)); ?>,            
        //     columns: [
        //         { 
        //           title: "User Name",
        //             "render": function(data, type, row, meta){
        //                 return row[1];
        //             }
        //         },
        //         { title: "Projet name",
        //             "render": function(data, type, row, meta){
        //                 return row[2];
        //             }
        //         },
        //         { title: "Project description" ,
        //             "render": function(data, type, row, meta){
        //                 return row[3];
        //             }
        //         },
        //         { title: "Posted date" ,
        //             "render": function(data, type, row, meta){
        //                 return row[4];
        //             }
        //         },
        //         { title: "Total Likes" ,
        //             "render": function(data, type, row, meta){
        //                 return row[5];
        //             }
        //         },
        //         { title: "Rating" ,
        //             "render": function(data, type, row, meta){
        //                 return row[6];
        //             }
        //         },
        //         {
        //           //data: <?PHP //echo (json_encode($data)); ?>,
        //           title:'Action',
        //           className: "center",
        //           "render": function(data, type, row, meta){
        //             return '<a href="project-edit/id/'+row[0]+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a> | <a href="<?php echo base_url("project-delete/id/");?>'+row[0]+'" class="delete-item-data"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';  
        //           }
        //       }
        //     ], 
        //     initComplete: function () {
        //     this.api().columns([1]).every( function () {
        //         var column = this;
        //         var select = $('<select><option value=""></option></select>')
        //             .appendTo( $('.abc').empty() )
        //             .on( 'change', function () {
        //              // console.log($(column.footer()).empty());
        //                 var val = $.fn.dataTable.util.escapeRegex(
        //                     $(this).val()
        //                 );
 
        //                 column
        //                     .search( val ? '^'+val+'$' : '', true, false )
        //                     .draw();
        //             } );
 
        //         column.data().unique().sort().each( function ( d, j ) {
        //                 console.log(d);
        //                   select.append( '<option value="'+d+'">'+d+'</option>' )
        //               } );
        //           } );
        //       },           
        // } );    
    } );
    function dataTable(val=''){
      $('#projects').dataTable().fnClearTable();
      $('#projects').dataTable().fnDestroy();
      $('#projects').DataTable( {
            "processing": true,
            "serverSide": true,
            'aoColumns': [ 
                { bSearchable: true, bSortable: true }, 
                { bSearchable: true, bSortable: true },
                { bSearchable: true, bSortable: true },
                { bSearchable: true, bSortable: true },
                { bSearchable: true, bSortable: true },
                { bSearchable: true, bSortable: true },
                { bSearchable: true, bSortable: true },
                { bSearchable: true, bSortable: true },
                { bSearchable: false, bSortable: false },
                { bSearchable: false, bSortable: false },
            ],
            "ajax": {
                "url": "<?php echo base_url('project/getAllData')?>",
                "type": "POST",
                cache: false,
                data:{
                  'Category':val,
                  'uid':<?PHP echo $uid;?>
                }            
            },
        });
    }
  </script>
  </body>
</html>
