<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>webllywood-backend : Lyric Detail</title>
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Lyric Detail</h2>
                                        <div class="nav navbar-right panel_toolbox">
                                        <?PHP if(!empty($lyrics_data) && $lyrics_data['status'] == 'Y'){?>
                                            <button id="active-lyrics" data-id="<?= $lyrics_data['id'];?>" class="btn btn-success btn-sm"><i class="fa fa-check" aria-hidden="true"></i> Active</button>
                                        <?PHP } else {?>
                                            <button id="deactive-lyrics" data-id="<?= $lyrics_data['id'];?>" class="btn btn-danger btn-sm"><i class="fa fa-times" aria-hidden="true"></i> Deactive</button>
                                        <?PHP }?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content form-horizontal form-label-left">
                                        <?PHP if(!empty($lyrics_data)){?>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project Title:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($lyrics_data) ? $lyrics_data['title']: ''?>">
                                            </div>
                                        </div>
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Added By:</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($lyrics_data) ? $lyrics_data['user_name']: ''?>">
                                            </div>
                                        </div>     
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Date:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($lyrics_data) ? $lyrics_data['date']: ''?>">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Lyrics Content:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea style="height:200px;overflow-y: auto;" class="form-control col-md-7 col-xs-12" readonly><?php echo !empty($lyrics_data) ? htmlentities($lyrics_data['lyrics_content']): ''?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <?PHP } else {  ?>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="x_panel">
                                                <div class="x_content text-center no-data-color">
                                                    <span class="glyphicon glyphicon-warning-sign worning-class-small" aria-hidden="true"></span>
                                                    </br><span class="worning-class-small">No data found !</span>
                                                </div>
                                            </div>
                                        </div>  
                                        <?PHP }?>
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
    </body>
</html>