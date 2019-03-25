<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>webllywood.dev-backend : Ratings.</title>
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
                                        $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isTopPostId,"fieldType" => 'Photo'));
                                        
                                        $path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
                                        if(trim($Thumb->slugvalue) != ''){
                                            $path = '../'.META_ARTICLE_UPLOAD_PATH;
                                            $path .= $Thumb->slugvalue;
                                        }
                                        $btnText = 'Change '.$cat['title'];
                                    }else{
                                        $btnText = 'Select '.$cat['title'];
                                    }
                                    //print_r($userDetails);die;
                                    echo '<div class="col-md-'.$col.' col-sm-'.$col.' col-xs-12" >
                                    <div class="x_panel" style="background-image:url('.$path.');" id="top'.$cat['id'].'">
                                        <h4 class="no-underline">'.$cat['title'].'</h4>
                                        <button type="button" class="btn btn-primary catId" id="btn'.$cat['id'].'" data-boxid="top'.$cat['id'].'" '.$data_id.'="'.$cat['id'].'" data-selectedId = "'.$isTopPostId.'" data-type="top" data-modalName = "'.$cat['title'].'">'.$btnText.'</button>
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
                                //echo setHtml('year',$isTopYear,$catsArray);
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
                                        $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        $path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
                                        if(trim($Thumb->slugvalue) != ''){
                                            $path = '../'.META_ARTICLE_UPLOAD_PATH;
                                            $path .= $Thumb->slugvalue;
                                        }
                                        $btnText = 'Change '.$cat['title'];
                                    }else{
                                        $btnText = 'Select '.$cat['title'];
                                    }
                                    echo '<div class="BEST-PROJECTS-BOX">
                                        <div class="bes-project-x_panel" id="'.$type.$cat['id'].'" style="background-image:url('.$path.');">
                                            <h4 class="no-underline">'.$cat['title'].'</h4>
                                            <button type="button" class="btn btn-primary catId" data-boxid="'.$type.$cat['id'].'" '.$data_id.'="'.$cat['id'].'" data-selectedId = "'.$isPostId.'" data-type="'.$type.'" data-modalName = "'.$cat['title'].'"> '.$btnText.'</button>
                                            
                                        </div>
                                    </div>';
                                    $i++;
                                } 
                            ?>
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
                                        $Thumb = $this->defaultdata->grabMetaPosts(array('postID' => $isPostId,"fieldType" => 'Photo'));
                                        $path = DEFAULT_ASSETS_URL_WEB.'images/noimg.png';
                                        if(trim($Thumb->slugvalue) != ''){
                                            $path = '../'.META_ARTICLE_UPLOAD_PATH;
                                            $path .= $Thumb->slugvalue;
                                        }
                                        $btnText = 'Change '.$cat['title'];
                                    }else{
                                        $btnText = 'Select '.$cat['title'];
                                    }
                                    echo '<div class="BEST-PROJECTS-BOX">
                                        <div class="bes-project-x_panel" id="'.$type.$cat['id'].'" style="background-image:url('.$path.');">
                                            <h4 class="no-underline">'.$cat['title'].'</h4>
                                            <button type="button" class="btn btn-primary catId" data-boxid="'.$type.$cat['id'].'" '.$data_id.'="'.$cat['id'].'" data-selectedId = "'.$isPostId.'" data-type="'.$type.'" data-modalName = "'.$cat['title'].'"> '.$btnText.'</button>
                                        </div>
                                    </div>';
                                    $i++;
                                } 
                            ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="x_title">
                            <h2> BEST PROJECTS SELECTION <small></small></h2>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Selection for sort by </h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <br>
                                        <form id="shorting-form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post">
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

                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false"> 
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="projects">
                                            <thead>
                                                <tr>
                                                    <th>User Name</th>
                                                    <th>Projet name</th>
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary saveTopHallFame" data-selectedId="0">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $footer_scripts;?>	
            </div>
        </div>
        <script>
            $(document).ready(function() {
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
                    var project = $('#projects').dataTable();
                    project.fnClearTable();
                    project.fnDestroy();
                    dataTable(cat,subCat,type);
                    $('.bs-example-modal-lg').modal('toggle');
                });
            });
            function dataTable(cat='',subCat='',type=''){
                var project = $('#projects').dataTable();
                project.fnClearTable();
                project.fnDestroy();
                $('#projects').DataTable({
                    "processing": true,
                    "serverSide": true,            
                    'aoColumns': [ 
                        { bSearchable: true, bSortable: true }, 
                        { bSearchable: true, bSortable: true }, 
                        { bSearchable: true, bSortable: true } ,
                        { bSearchable: false, bSortable: true } ,
                        { bSearchable: false, bSortable: true } ,
                        { bSearchable: false, bSortable: true } ,
                        { bSearchable: false, bSortable: false } ,
                    ],
                    "ajax": {
                        "url": "<?php echo base_url('project/getCatRecords')?>",
                        "type": "POST",
                        cache: false,
                        data:{
                            'Category':cat,
                            'subCat': subCat,
                            'type': type
                        }            
                    },
                });
            }
        </script>
    </body>
</html>

