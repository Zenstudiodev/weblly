<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>webllywood-backend : Category </title>
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
              <?php if($this->session->userdata('category_sucess')) { ?>
              <div class="x_content bs-example-popovers">
                <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                  <strong>Sucess!</strong>
                  <?php echo $this->session->userdata('category_sucess');?>
                  <?php $this->session->unset_userdata('category_sucess');?>
                </div>
              </div>
              <?PHP } ?>
              <div class="x_panel">
                <div class="x_title">
                  <h2>Category List</h2>
                  <div class="nav navbar-right panel_toolbox">
                    <a href='<?php echo base_url('category/new-category');?>' class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New</a>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="cmsPage" class="set-table">
                          <thead>
                            <tr>
                              <th class='set-th' width="153" align="left">Category</th>
                              <th class='set-th' width="142" align="left">Sub Category </th>
                              <th class='set-th' width="141" align="left"></th>
                              <th class='set-th' width="145" align="left"></th>
                              <th class='set-th' width="182" align="left">Action</th>  
                            </tr>
                          </thead>
                          <tbody class='set-tbody'>
                            <tr>
                              <?PHP if(isset($data) && !empty($data)){ ?>
                              <td class='set-td' align="left">
                                <select name="category" id="category-data" size="17" style="width:100%; height:200px">
                                  <?PHP
                                  if(isset($data) && !empty($data)){
                                    foreach($data as $dt){?>
                                      <option value="<?PHP echo $dt[0]; ?>"><?PHP echo $dt[1]; ?></option>
                                  <?PHP }
                                  }
                                  ?>
                                </select>
                              </td>
                              <td class='set-td' align="left" id="subCat">
                              </td>
                              <td class='set-td' align="left" id="subsubCat">
                              </td>
                              <td class='set-td' align="left" id="subsubsubCat">
                              </td>
                              <td class='set-td' align="left" id="deleteID">
                              </td>
                              <?PHP } else {?>
                              <td class='set-td' colspan='5' align='center' >No data found.</td>
                              <?PHP } ?>
                            </tr>
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
    <script language=Javascript>
      
      function dochange100(src,val,count) {
        var req = Inint_AJAX();
        req.onreadystatechange = function () {
        if (req.readyState==4) {
            if (req.status==200) {
                document.getElementById(src).innerHTML=req.responseText; //retuen value
            }
          }
        };
        req.open("GET", "ajax_postCat.php?data="+src+"&val="+val+"&c="+count); //make connection
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1"); // set Header
        req.send(null); //send value
      }
      function dochange200(src,val,count) {
        var req = Inint_AJAX();
        req.onreadystatechange = function () {
        if (req.readyState==4) {
            if (req.status==200) {
                document.getElementById(src).innerHTML=req.responseText; //retuen value
            }
          }
        };
        req.open("GET", "ajax_catdelete1.php?data="+src+"&val="+val+"&c="+count); //make connection
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=iso-8859-1"); // set Header
        req.send(null); //send value
      }
    </script>
  </body>
</html>