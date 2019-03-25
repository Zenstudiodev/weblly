<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>webllywood-backend : Ratings.</title>
        <?php echo $header_scripts;?>
        <style>
        
        .modal-backdrop{
            position: fixed;
        }
        #projects_wrapper{
            overflow-x: auto;
        }
        .modal_full_movie .modal-body .row > .col-sm-12 {
            width: 100% !important;
        }
        .full_movietb_main{
            max-height: 400px;
            overflow-y: auto;
        }
        </style>
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
                <div class="right_col hall-of-fame" role="main">
                    <div class="">
                        <div class="page-title">
                            <div class="title_left">
                                <h3>BEST PROJECTS ALL THE TIME </h3>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <?php 
                                $catsArray = array_merge($subCat,$categories); $i = 1;
                                foreach($catsArray as $cat){
                                    //print_r($isTop);die;
                                    $data_id = 'data-id';
                                    if($cat['type'] == 'VID' && $cat['parentID'] == 0) continue;
                                    if($cat['type'] == 'VID' && $i == 1){
                                        $col = 5;
                                        $data_id = 'data-subid';
                                    }
                                    else if ($cat['type'] == 'VID' && $i == 2){
                                        $col = 7;
                                        $data_id = 'data-subid';
                                    }else{$col = 4;}
                                    $isTopPostId = $user_id = '';
                                    foreach($isTop as $isTopPost){
                                        if($cat['id'] == $isTopPost['subCategoryID']){
                                            $isTopPostId = $isTopPost['id'];
                                            $user_id = $isTopPost['user_id'];
                                            continue;
                                        }
                                        if($cat['id'] == $isTopPost['categoryID']){
                                            $isTopPostId = $isTopPost['id'];
                                            $user_id = $isTopPost['user_id'];
                                            continue;
                                        }
                                    }
                                    $path = $userDetails = '';
                                    if($isTopPostId != ''){
                                        // $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isTopPostId,"fieldType" => 'Photo'));
                                        if($cat['series'] == 'N'){
                                            $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isTopPostId,"fieldType" => 'Photo'));
                                        }else{
                                            $Thumb = $this->defaultdata->grabMetaPostsSeries(array('postID' => $isTopPostId,"fieldType" => 'Photo'));
                                        }
                                        $path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
                                        if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/resize/400/'.$Thumb->slugvalue)){
                                            $path = base_url('/../assets/upload/resize/400/');
                                            $path .= str_replace(" ","%20",$Thumb->slugvalue);
                                        } else if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/all_post/'.$Thumb->slugvalue)){
                                            $path = '../'.META_ARTICLE_UPLOAD_PATH;
                                            $path .= str_replace(" ","%20",$Thumb->slugvalue);
                                        }
                                        $btnText = 'Change '.$cat['title'];
                                    }else{
                                        $btnText = 'Select '.$cat['title'];
                                    }
                                    //print_r($userDetails);die;
                                    echo '<div class="col-md-'.$col.' col-sm-'.$col.' col-xs-12" >
                                    <div class="x_panel holl_top_list" style="background-image:url('.$path.');" id="top'.$cat['id'].'">
                                        <h4 class="no-underline hall-of-heading rtp">'.$cat['title'].'</h4>
                                        <button type="button" class="btn btn-primary catIdAll" id="btn'.$cat['id'].'" data-boxid="top'.$cat['id'].'" '.$data_id.'="'.$cat['id'].'" data-selectedId = "'.$isTopPostId.'" data-type="top" data-modalName = "'.$cat['title'].'">'.$btnText.'</button>
                                    </div>
                                    </div>';
                                    $i++;
                                }
                            ?>

                            <div class="col-md-12">
                                <div class="x_title">
                                    <h2> BEST PROJECTS FOR <small></small></h2>
                                    <input type="text" class="form-control x_title_year" id="yearDatepicker" data-type="Y">
                                    <div class="clearfix" ></div>
                                </div>
                            </div>
                            <div class="yearProjects">
                                <?php 
                                $i = 1;
                                $type = 'year';
                                foreach($catsArray as $cat){ 
                                    $data_id = 'data-id';
                                    if($cat['type'] == 'VID' && $cat['parentID'] == 0) continue;
                                    if($cat['type'] == 'VID' && $i == 1){
                                        $data_id = 'data-subid';
                                    }
                                    else if ($cat['type'] == 'VID' && $i == 2){
                                        $data_id = 'data-subid';
                                    }
                                    $isPostId = '';
                                    foreach($isTopYear as $isPost){
                                        if($cat['id'] == $isPost['category_id']){
                                            $isPostId = $isPost['post_id'];
                                            continue;
                                        }
                                        // if($cat['id'] == $isPost['categoryID']){
                                        //     $isPostId = $isPost['id'];
                                        //     continue;
                                        // }
                                    }
                                    $path = '';
                                    
                                    if($isPostId != ''){
                                        // $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        if($cat['series'] == 'N'){
                                            $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        }else{
                                            $Thumb = $this->defaultdata->grabMetaPostsSeries(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        }
                                        $path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
                                        if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/resize/250/'.$Thumb->slugvalue)){
                                            $path = base_url('/../assets/upload/resize/250/');
                                            $path .= str_replace(" ","%20",$Thumb->slugvalue);
                                        } else if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/all_post/'.$Thumb->slugvalue)){
                                            $path = '../'.META_ARTICLE_UPLOAD_PATH;
                                            $path .= str_replace(" ","%20",$Thumb->slugvalue);
                                        }
                                        $btnText = 'Change '.$cat['title'];
                                    }else{
                                        $btnText = 'Select '.$cat['title'];
                                    }
                                    echo '<div class="BEST-PROJECTS-BOX">
                                        <div class="bes-project-x_panel" id="'.$type.$cat['id'].'" style="background-image:url('.$path.');">
                                            <h4 class="no-underline hall-of-heading">'.$cat['title'].'</h4>
                                            <button type="button" class="btn btn-primary catId" data-boxid="'.$type.$cat['id'].'" '.$data_id.'="'.$cat['id'].'" data-selectedId = "'.$isPostId.'" data-type="'.$type.'" data-modalName = "'.$cat['title'].'"> '.$btnText.'</button>
                                            
                                        </div>
                                    </div>';
                                    $i++;
                                }?>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="x_title">
                                    <h2> BEST PROJECTS FOR <small></small></h2>
                                    <input type="text" class="form-control x_title_year" id="monthDatepicker" data-type="M">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="monthProjects">
                                <?php 
                                //echo setHtml('month',$isTopMonth,$catsArray);
                                $i = 1;
                                $type = 'month';
                                foreach($catsArray as $cat){ 
                                    $data_id = 'data-id';
                                    if($cat['type'] == 'VID' && $cat['parentID'] == 0) continue;
                                    if($cat['type'] == 'VID' && $i == 1){
                                        $data_id = 'data-subid';
                                    }
                                    else if ($cat['type'] == 'VID' && $i == 2){
                                        $data_id = 'data-subid';
                                    }
                                    $isPostId = '';
                                    foreach($isTopMonth as $isPost){
                                        if($cat['id'] == $isPost['category_id']){
                                            $isPostId = $isPost['post_id'];
                                            continue;
                                        }
                                        // if($cat['id'] == $isPost['categoryID']){
                                        //     $isPostId = $isPost['id'];
                                        //     continue;
                                        // }
                                    }
                                    $path = '';
                                    if($isPostId != ''){
                                        if($cat['series'] == 'N'){
                                            $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        }else{
                                            $Thumb = $this->defaultdata->grabMetaPostsSeries(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        }
                                        // $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        $path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
                                        if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/resize/250/'.$Thumb->slugvalue)){
                                            $path = base_url('/../assets/upload/resize/250/');
                                            $path .= str_replace(" ","%20",$Thumb->slugvalue);
                                        } else if(trim($Thumb->slugvalue) != '' && file_exists(getcwd().'/../assets/upload/all_post/'.$Thumb->slugvalue)){
                                            $path = '../'.META_ARTICLE_UPLOAD_PATH;
                                            $path .= str_replace(" ","%20",$Thumb->slugvalue);
                                        }
                                        $btnText = 'Change '.$cat['title'];
                                    }else{
                                        $btnText = 'Select '.$cat['title'];
                                    }
                                    echo '<div class="BEST-PROJECTS-BOX">
                                        <div class="bes-project-x_panel" id="'.$type.$cat['id'].'" style="background-image:url('.$path.');">
                                            <h4 class="no-underline hall-of-heading">'.$cat['title'].'</h4>
                                            <button type="button" class="btn btn-primary catId" data-boxid="'.$type.$cat['id'].'" '.$data_id.'="'.$cat['id'].'" data-selectedId = "'.$isPostId.'" data-type="'.$type.'" data-modalName = "'.$cat['title'].'"> '.$btnText.'</button>
                                        </div>
                                    </div>';
                                    $i++;
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="x_title">
                            <h2> BEST PROJECTS SELECTION <small></small></h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Selection for sort by </h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <br>
                                        <form novalidate id="shorting-form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post">
                                            <!-- <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select any Year</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" class="form-control x_title_year" id="shortYearDatepicker" data-type="Y">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select any one</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" class="form-control x_title_year" id="shortMonthDatepicker" data-type="Y">
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Select any one</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select id="HallOfShort" class="form-control col-md-7 col-xs-12">
                                                        <?php 
                                                            $selected1 = $selected2 = $selected3 = '';
                                                            if($shortBy == 1){$selected1 = 'selected';}
                                                            if($shortBy == 2){$selected2 = 'selected';}
                                                            if($shortBy == 3){$selected3 = 'selected';}
                                                        ?>
                                                        <option value="1" <?php echo $selected1; ?>>Short By Views</option>
                                                        <option value="2" <?php echo $selected2; ?>>Short By Likes</option>
                                                        <option value="3" <?php echo $selected3; ?>>Short By Rating</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade bs-example-modal-lg modal_full_movie" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false"> 
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content custom-hall">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12 ">
                                        <div class="full_movietb_main">
                                            <table id="projects">
                                                <thead>
                                                    <tr>
                                                        <th>User Name</th>
                                                        <th>Projet name</th>
                                                        <th>Sub Category</th>
                                                        <th>Posted date</th>
                                                        <th>Rating</th>
                                                        <th>Total Likes</th>
                                                        <th>Total Views</th>
                                                        <th>Select</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary saveTopHallFame" data-selectedId="0">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <footer>
			        <?php echo $footer;?>         
                </footer>
                <?php echo $footer_scripts;?>	
            </div>
        </div>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/dataTables.buttons.min.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/buttons.flash.min.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/jszip.min.js"></script>
        <script src="<?php echo DEFAULT_ASSETS_URL;?>js/buttons.html5.min.js"></script>
        <script>
            $(document).ready(function() {
                $(document).on('click','.catIdAll', function(e){
                    var type = $(this).attr('data-type');
                    var cat = $(this).attr('data-id');
                    var subCat = $(this).attr('data-subid');
                    var selectedId = $(this).attr('data-selectedId');
                    var boxID = $(this).attr('data-boxid');
                    var btnID = $(this).attr('id');
                    var modalName = $(this).attr('data-modalName');
                    $('.saveTopHallFame').attr('data-selectedId',selectedId);
                    $('.saveTopHallFame').attr('data-boxID',boxID);
                    $('.saveTopHallFame').attr('data-btnID',btnID);
                    $('.saveTopHallFame').attr('data-type',type);
                    $('.saveTopHallFame').attr('data-modalName',modalName);
                    if(subCat != '' && typeof(subCat)  != "undefined"){
                        $('.saveTopHallFame').attr('data-catId',subCat);
                    }else if(cat != '' && typeof(cat)  != "undefined"){
                        $('.saveTopHallFame').attr('data-catId',cat);
                    }
                    $('#myModalLabel').html('Select any one '+modalName);
                    var project = $('#projects').dataTable();
                    project.fnClearTable();
                    project.fnDestroy();
                    dataTable(cat,subCat,type);
                    $('.bs-example-modal-lg').modal('toggle');
                });

                $(document).on('click','.catId', function(e){
                    var type = $(this).attr('data-type');
                    var cat = $(this).attr('data-id');
                    var subCat = $(this).attr('data-subid');
                    var selectedId = $(this).attr('data-selectedId');
                    var boxID = $(this).attr('data-boxid');
                    var btnID = $(this).attr('id');
                    var modalName = $(this).attr('data-modalName');
                    $('.saveTopHallFame').attr('data-selectedId',selectedId);
                    $('.saveTopHallFame').attr('data-boxID',boxID);
                    $('.saveTopHallFame').attr('data-btnID',btnID);
                    $('.saveTopHallFame').attr('data-type',type);
                    $('.saveTopHallFame').attr('data-modalName',modalName);
                    if(subCat != '' && typeof(subCat)  != "undefined"){
                        $('.saveTopHallFame').attr('data-catId',subCat);
                    }else if(cat != '' && typeof(cat)  != "undefined"){
                        $('.saveTopHallFame').attr('data-catId',cat);
                    }
                    $('#myModalLabel').html('Select any one '+modalName);
                    // var project = $('#projects').dataTable();
                    // project.fnClearTable();
                    // project.fnDestroy();
                    var month = year = 0;
                    if(type == 'year'){
                        year = $('#yearDatepicker').val();
                    } else if(type == 'month'){
                        month = $('#monthDatepicker').val().split('-')[0];
                        year = $('#monthDatepicker').val().split('-')[1];
                    }
                    setDataTable(cat,subCat,type,year,month);
                    $('.bs-example-modal-lg').modal('toggle');
                });
            });
            // function dataTable(cat='',subCat='',type=''){
            //     var project = $('#projects').dataTable();
            //     project.fnClearTable();
            //     project.fnDestroy();
            //     $('#projects').DataTable({
            //         "processing": true,
            //         "serverSide": true,            
            //         'aoColumns': [ 
            //             { bSearchable: true, bSortable: true }, 
            //             { bSearchable: true, bSortable: true }, 
            //             { bSearchable: true, bSortable: true },
            //             // { bSearchable: true, bSortable: true },
            //             { bSearchable: false, bSortable: true },
            //             { bSearchable: false, bSortable: true },
            //             { bSearchable: false, bSortable: true },
            //             { bSearchable: false, bSortable: false },
            //         ],
            //         "ajax": {
            //             "url": "<?php //echo base_url('project/getCatRecords')?>",
            //             "type": "POST",
            //             cache: false,
            //             data:{
            //                 'Category':cat,
            //                 'subCat': subCat,
            //                 'type': type,
            //             }
            //         },
            //     });
            // }
            function dataTable(cat='',subCat='',type=''){
                var project = $('#projects').dataTable();
                project.fnClearTable();
                project.fnDestroy();
                
                _data = {'Category':cat,'subCat': subCat,'type': type}
                
                $.ajax({
                    url: "<?php echo base_url('project/getCatRecordsWithCond')?>",
                    dataType:'json',
                    data:_data,
                    type: 'post',
                    cache: false,
                    success:function(rtn){
                        if(rtn.status == true){
                            setData(rtn.data);
                        } else {
                            swal( 'Opss.!', data.message, 'error');
                        }
				    }
			    });
            }

            function setDataTable(cat='',subCat='',type='', year = 0, month = 0){
                var project = $('#projects').dataTable();
                project.fnClearTable();
                project.fnDestroy();
                
                if(type == 'year'){
                    _data = {'Category':cat,'subCat': subCat,'type': type,'year':year}
                } else if(type == 'month'){
                    _data = {'Category':cat,'subCat': subCat,'type': type,'year':year,'month':month}
                }
                
                $.ajax({
                    url: "<?php echo base_url('project/getCatRecordsWithCond')?>",
                    dataType:'json',
                    data:_data,
                    type: 'post',
                    cache: false,
                    success:function(rtn){
                        if(rtn.status == true){
                            setData(rtn.data);
                        } else {
                            swal( 'Opss.!', rtn.message, 'error');
                        }
                    }
                });
            }

            function setData(_data = []){
                if(_data.length != 0){
                    $('#projects').DataTable( {
                        data: _data,
                        buttons: [
                            {
                                extend: 'excel',
                                text: 'Export',
                            }
                        ],
                        dom: 'Bfrtip',
                        columns: [
                            { 
                            title: "User Name",
                                "render": function(data, type, row, meta){
                                    return row[0];
                                }
                            },
                            { title: "Projet name",
                                "render": function(data, type, row, meta){
                                    return row[1];
                                }
                            },
                            {  title: "Sub Category",
                                "render": function(data, type, row, meta){
                                    return row[2];
                                }
                            },
                            {  title: "Posted date",
                                "render": function(data, type, row, meta){
                                    return row[3];
                                }
                            },
                            { title: "Rating" ,
                                "render": function(data, type, row, meta){
                                    return row[4];
                                }
                            },
                            { title: "Total Likes" ,
                                "render": function(data, type, row, meta){
                                    return row[5];
                                }
                            },
                            { title: "Total Views" ,
                                "render": function(data, type, row, meta){
                                    return row[6];
                                }
                            },
                            { title: "Select" ,
                                "render": function(data, type, row, meta){
                                    return row[7];
                                }
                            },
                        ],
                    } );
                }
            }
        </script>
    </body>
</html>

