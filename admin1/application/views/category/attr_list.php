<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>webllywood-backend : Category Attribute </title>
    <?php echo $header_scripts;?>
    <link rel="stylesheet" href="<?php echo DEFAULT_ASSETS_URL;?>css/tablednd.css" type="text/css"/>
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
              <?PHP } ?>
              <div class="x_panel">
                <div class="x_title">
                  <h2>Attribute List</h2>
                  <div class="nav navbar-right panel_toolbox">
                    <a href='<?php echo base_url('category/new-attribute/id/'.$cat_id);?>' class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add New</a>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="set-table showlist" id="table-3">
                          <thead>
                            <tr>
                              <th class='set-th'></th>
                              <th class='set-th'>Title</th>
                              <th class='set-th'>Type</th>
                              <th class='set-th'>Status</th>
                              <th class='set-th'>Action</th>  
                            </tr>
                          </thead>
                          <tbody class='set-tbody'>
                          <?PHP if(isset($data) && !empty($data)){
                            foreach($data as $d){?>
                            <tr class='set-tr' id="<?=$d['typeID']?>" style="cursor: move;">
                              <td align="center">
                                  <input type="checkbox" name="list<?=$d['typeID']?>" />
                              </td>
                        
                              <td align="left">
                                <?=$d['fieldName']?>&nbsp;</td>
                        
                              <td align="left">
                                <?=$d['type']?>
                              </td>
                              <td align="left">
                                <?php if($d['status']=='Y') {
                                  echo "<span class='btn btn-success btn-round'>Online</span>";
                                } else {
                                  echo "<span class='btn btn-danger btn-round'>Offline</span>";
                                } ?>
                              </td>
                              <td class=" center">
                                <a href="<?PHP echo base_url('category/attr-edit/id/'.$cat_id.'/'.$d['typeID'])?>">
                                  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit
                                </a> |
                                <a href="<?PHP echo base_url('category/attr-delete/id/'.$d['typeID'])?>" class="delete-item-data">
                                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
                                </a>
                              </td>
                            </tr>
                            <?PHP } } else {?>
                              <tr >
                                <td colspan='5' align='center' class='set-td'>No data found.</td>
                                </tr>
                            <?PHP }?>
                          </tbody>
                        </table>
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
    <script type="text/javascript" src="<?php echo DEFAULT_ASSETS_URL;?>js/general.js"></script>
    <script type="text/javascript" src="<?php echo DEFAULT_ASSETS_URL;?>js/jquery.tablednd.0.7.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        // Initialise the first table (as before)
        $('#table-3').tableDnD({
          onDrop: function(table, row) {
            var val=$.tableDnD.serialize();
            save_order_attr(val,'<?=$pid?>','categoryattr');
          }
        });
      });
    </script>
  </body>
</html>