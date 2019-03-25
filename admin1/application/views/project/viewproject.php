<?PHP
$path = '../'.META_ARTICLE_UPLOAD_PATH;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>webllywood-backend : Article Detail</title>
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
                                    <div class="x_content form-horizontal form-label-left">
                                        <span class="section">Article Detail</span>
                                            
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project Title:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($projectData) ? $projectData->title: ''?>">
                                            </div>
                                        </div>
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Category: 
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($projectData) ? $projectData->category: ''?>">
                                            </div>
                                        </div>     
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sub-Category:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($projectData) ? $projectData->subcategory: ''?>">
                                            </div>
                                        </div>
                                        <?php if(isset($is_series) && $is_series == 0 && isset($attrArray) && !empty($attrArray)){?>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total Likes:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($projectData) ? $projectData->total_likes: 0?>">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total Views:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($projectData) ? $projectData->total_views: 0?>">
                                            </div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Rating:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($projectData) ? $projectData->rating: 0?>">
                                            </div>
                                        </div>
                                        <?PHP }
                                        if(!empty($projectData) && $projectData->subsubcategory != ''){?>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sub-sub-Category:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo !empty($projectData) ? $projectData->subsubcategory: ''?>">
                                            </div>
                                        </div>
                                        <?PHP }?>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project Description:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea style="height:200px;overflow-y: auto;" class="form-control col-md-7 col-xs-12" readonly><?php echo !empty($projectData) ? htmlentities($projectData->projectDescription): ''?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <?php if(isset($is_series) && $is_series == 0 && isset($attrArray) && !empty($attrArray)){
                                            foreach($attrArray as $atar){
                                                if($atar['fieldType'] != 'GmapCoordinates'){?>
                                                    <div class="item form-group">
                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo ucfirst(str_replace('-',' ',str_replace('cat_','',$atar['slugname'])));?>:</label>
                                                        <?PHP if($atar['fieldType'] == 'File'){?>
                                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                                        <?PHP } else {?>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <?PHP }?>
                                                            <?php if($atar['fieldType'] == 'String' || $atar['fieldType'] == 'time' || $atar['fieldType'] == 'SubTitle'){?>
                                                                <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo $atar['slugvalue'];?>">
                                                            <?PHP } else if($atar['fieldType'] == 'Photo'){?>
                                                                <img class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width='100px' onerror="this.src='<?php echo base_url("../assets/images/no_image.png"); ?>'">
                                                            <?PHP } else if($atar['fieldType'] == 'Video'){?>
                                                                <video controls="" width="100%" height="300">
                                                                    <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/mp4">
                                                                    <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/ogg">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            <?PHP } else if($atar['fieldType'] == 'Text' || $atar['fieldType'] == 'Lyrics'){?>
                                                                <textarea style="height:200px;overflow-y: auto;" class="form-control col-md-7 col-xs-12" readonly><?php echo htmlentities($atar['slugvalue']);?>
                                                                </textarea>
                                                            <?PHP } else if($atar['fieldType'] == 'File'){?>
                                                                <iframe class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width="100%" height="880px"></iframe>
                                                            <?PHP } else if($atar['slugname'] == 'cat_audio-file'){?>
                                                                <audio controls>
                                                                    <source src="<?php echo base_url($path.$atar['slugvalue']);?>" type="audio/ogg">
                                                                    <source src="<?php echo base_url($path.$atar['slugvalue']);?>" type="audio/mpeg">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            <?PHP }?>
                                                        </div>
                                                    </div>
                                                <?php }
                                            }
                                        } ?>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Status: </label>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <input type="radio" class="add_extra_field set-checkbox" checked> <span class="check-text">
                                                <?php if($projectData->status=='Y'){?>Active
                                                <?php } else {?>Blocked<?PHP }?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(isset($is_series) && $is_series == 1 && isset($episode_list_id) && !empty($episode_list_id)){
                                        $i = 0;
                                        foreach($episode_list_id as $k=>$episode_data){?>
                                        <div class="x_panel" <?PHP echo ($i > 0 ? 'style="height: auto;"' : '');?>>
                                            <div class="x_title">
                                                <h2><i class="fa fa-film"></i> Season <?= $k;?></h2>
                                                <ul class="nav navbar-right panel_toolbox" style='min-width: 0;'>
                                                    <li>
                                                        <a class="collapse-link">
                                                            <?PHP if($i > 0){?>
                                                                <i class="fa fa-chevron-down"></i>
                                                            <?PHP } else {?>
                                                                <i class="fa fa-chevron-up"></i>
                                                            <?PHP }?>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content" <?PHP echo ($i > 0 ? 'style="display: none;"' : 'style="display: block;"');?>>

                                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                                        <?PHP $j = 0;
                                                        foreach($episode_data as $episode){?>
                                                        <li role="presentation" class="<?PHP echo($j == 0 ? 'active' : '');?>"><a href="#tab_contentseason-<?=$k;?>-episode-<?=$episode['episode_id'];?>" id="season-<?=$k;?>-episode-<?=$episode['episode_id'];?>" role="tab" data-toggle="tab" aria-expanded="<?PHP echo($j == 0 ? 'true' : 'false');?>">Episode <?= $episode['episode_id'];?></a>
                                                        </li>
                                                        <?PHP $j++;}?>
                                                    </ul>
                                                    <div id="myTabContent" class="tab-content">
                                                        <?PHP $j = 0;
                                                        foreach($episode_data as $episode){?>
                                                        <div role="tabpanel" class="tab-pane fade <?PHP echo($j ==0 ? 'active in':'');?>" id="tab_contentseason-<?=$k;?>-episode-<?=$episode['episode_id'];?>" aria-labelledby="season-<?=$k;?>-episode-<?=$episode['episode_id'];?>">
                                                            <div class="form-horizontal form-label-left">
                                                            <?php $episode_total_views = $episode_total_rating = $episode_total_likes = 0;
                                                            $episode_total_views =  $this->db->select("total_views")->from('com_series_data')->where(array("postID"=>$projectData->id, 'season_id'=>$k, 'episode_id'=>$episode['episode_id']))->get()->row()->total_views;
                                                            if($episode_total_views == '') $episode_total_views = 0;
                                                            $episode_total_likes = $this->db->select("total_likes")->from('com_series_data')->where(array("postID"=>$projectData->id, 'season_id'=>$k, 'episode_id'=>$episode['episode_id']))->get()->row()->total_likes;
                                                            if($episode_total_likes == '') $episode_total_likes = 0;
                                                            $episode_total_rating = $this->db->select("rating")->from('com_series_data')->where(array("postID"=>$projectData->id, 'season_id'=>$k, 'episode_id'=>$episode['episode_id']))->get()->row()->rating;
                                                            if($episode_total_rating == '') $episode_total_rating = 0;?>
                                                            <div class="item form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total Likes:
                                                                </label>
                                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                                    <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo $episode_total_likes;?>">
                                                                </div>
                                                            </div>
                                                            <div class="item form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Total Views:
                                                                </label>
                                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                                    <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo $episode_total_views;?>">
                                                                </div>
                                                            </div>
                                                            <div class="item form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Rating:
                                                                </label>
                                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                                    <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo $episode_total_rating;?>">
                                                                </div>
                                                            </div>
                                                            <?php if(isset($episode['episode_array']) && !empty($episode['episode_array'])){?>
                                                                
                                                                <?PHP foreach($episode['episode_array'] as $atar){
                                                                    if($atar['fieldType'] != 'GmapCoordinates'){?>
                                                                        <div class="item form-group col-md-6 col-sm-6 col-xs-12">
                                                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?php echo ucfirst(str_replace('-',' ',str_replace('cat_','',$atar['slugname'])));?>:</label>
                                                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                                                <?php if($atar['fieldType'] == 'String' || $atar['fieldType'] == 'time' || $atar['fieldType'] == 'SubTitle' || $atar['fieldType'] == 'SeriesNumber' || $atar['fieldType'] == 'EpisodeNumber' || $atar['fieldType'] == 'EpisodeTitle' || $atar['fieldType'] == ''){?>
                                                                                    <input class="form-control col-md-7 col-xs-12" readonly type="text" value="<?php echo $atar['slugvalue'];?>">
                                                                                <?PHP } else if($atar['fieldType'] == 'Photo'){?>
                                                                                    <img class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width='100px' onerror="this.src='<?php echo base_url("../assets/images/no_image.png"); ?>'">
                                                                                <?PHP } else if($atar['fieldType'] == 'Video'){?>
                                                                                    <video controls="" width="100%" height="300">
                                                                                        <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/mp4">
                                                                                        <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/ogg">
                                                                                        Your browser does not support the video tag.
                                                                                    </video>
                                                                                <?PHP } else if($atar['fieldType'] == 'Text' || $atar['fieldType'] == 'Lyrics'){?>
                                                                                    <textarea style="height:200px;overflow-y: auto;" class="form-control col-md-7 col-xs-12" readonly><?php echo htmlentities($atar['slugvalue']);?>
                                                                                    </textarea>
                                                                                <?PHP } else if($atar['fieldType'] == 'File'){?>
                                                                                    <iframe class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width="100%" height="880px"></iframe>
                                                                                <?PHP } else if($atar['slugname'] == 'cat_audio-file'){?>
                                                                                    <audio controls>
                                                                                        <source src="<?php echo base_url($path.$atar['slugvalue']);?>" type="audio/ogg">
                                                                                        <source src="<?php echo base_url($path.$atar['slugvalue']);?>" type="audio/mpeg">
                                                                                        Your browser does not support the audio element.
                                                                                    </audio>
                                                                                <?PHP }?>
                                                                            </div>
                                                                        </div>
                                                                    <?php }
                                                                }
                                                            } ?>
                                                            </div>
                                                        </div>
                                                        <?PHP $j++;}?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?PHP $i++; }
                                    } ?>
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