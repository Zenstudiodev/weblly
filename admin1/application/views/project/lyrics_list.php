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
                <h2>Project Lyrics List</h2>                
                <div class="clearfix"></div>
              </div>
              <!-- <div class="category">
                <span>Filter By:</span>
                <select class="catId form-control">
                  <option value="">--Select Projects--</option>
                  <?php // foreach($categories as $cat){ ?>
                    <option value=<?PHP //$cat['id']?>><?PHP //$cat['title'] ?></option>
                  <?php //} ?>
                </select>
              </div> -->

              <div class="x_content">
                <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">                      
                  <div class="row">
                    <div class="col-sm-12">
                      <table id="projects-List">
                        <thead>
                          <tr>
                            <th width="15%">Lyrics from</th>
                            <th width="15%">Projet name</th>
                            <th width="40%">Lyrics</th>
                            <th width="5%">Posted date</th>
                            <th width="10%">Action</th>
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
      // $('.catId').on('change', function(e){
      //    var abc = $(this).val();
      //    dataTable(abc)
      // });
    
      $(document).on('click','#active-lyrics, #deactive-lyrics',function(e){
        e.preventDefault();
        _this = $(this);
        id = _this.attr('data-id');
        name = _this.attr('id');
        if(name == 'active-lyrics'){
          msg = 'Deactive';
          var url = base_url+"project/lyrics-status/active/"+id;
        } else if(name == 'deactive-lyrics'){
          msg = 'Active';
          var url = base_url+"project/lyrics-status/deactive/"+id;
        } else {
          return 0;
        }
        swal({
          title: 'Are you sure?',
          text: "You want to "+msg+" this article?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, '+msg+' it!'
        }).then(function () {
          $.ajax({
            url: url,
            dataType:'json',
            data : 'id='+id,
            type: 'post',
            contentType: false,
            processData: false,
            success:function(data){
              if(data.status){
                dataTable()
              } else {
                swal( 'Opss.!', 'Something went wrong.'+data.message, 'error');
              }
            }
          });
        });
      });
    } );
    function dataTable(val=''){
      $('#projects-List').dataTable().fnClearTable();
      $('#projects-List').dataTable().fnDestroy();
      $('#projects-List').DataTable( {
        "order": [],
          "processing": true,
          "serverSide": true,            
          'aoColumns': [ 
              { bSearchable: true, bSortable: true }, 
              { bSearchable: true, bSortable: true }, 
              {  bSearchable: true, bSortable: true } ,
              {  bSearchable: true, bSortable: true } ,
              {  bSearchable: false, bSortable: false } ,
          ],
          "ajax": {
              "url": "<?php echo base_url('project/getAllLyricsData')?>",
              "type": "POST",
              cache: false,
              data:{
                'Category':val
              }
          }
      });
    }
  </script>
  </body>
</html>
