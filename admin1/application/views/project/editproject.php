<?PHP
$form_action =  base_url('project/ad-project-save/id/'.$projectData->id);
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
    <title>webllywood-backend : <?PHP echo $title?></title>
<?php echo $header_scripts;?>
</head>
<?php //print_r($projectData);die;?>
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
          <div class="page-title">
            <div class="title_left">
              <h3><?PHP echo $title?></h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">

                    <form novalidate action='<?php echo $form_action;?>' method="POST" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">

                        <span class="section">EDIT</span>
                        <?php if($this->session->userdata('language_error')) { ?>
                        <div class="x_content bs-example-popovers">
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                            </button>
                            <strong>Error!</strong> 
                            <?php echo $this->session->userdata('language_error');?>
                            <?php $this->session->unset_userdata('language_error');?>
                        </div>
                        </div>
                        <?PHP } ?>
                        <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project Title:<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control col-md-7 col-xs-12" name="title"  required="required" type="text" value="<?php echo !empty($projectData) ? $projectData->title: ''   ?>">
                        </div>
                        </div>
                        <?php
                        
                    // print_r($attrArray);die;
                        ?>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Category: <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="categoryID" class="form-control col-md-7 col-xs-12">
                                    <option value="0">-Select-</option>
                                    <?php   
                                        foreach($cateArray as $arr){ ?>
                                        <option value="<?= $arr['id']; ?>" <?php if($projectData->categoryID == $arr['id']){ ?>SELECTED<?php } ?>><?php echo $arr['title']; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>     
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sub-Category:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="subCategoryID" class="form-control col-md-7 col-xs-12">
                                    <option value="0">-Select-</option>
                                    <?php   
                                        foreach($cateSubArray as $arr){ ?>
                                            <option value="<?= $arr['id']; ?>" <?php if($projectData->subCategoryID == $arr['id']){ ?>SELECTED<?php } ?>><?php echo $arr['title']; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sub-sub-Category:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="subSubCategoryID" class="form-control col-md-7 col-xs-12">
                                    <option value="0">-Select-</option>
                                    <?php   
                                        foreach($cateSubOfSubArray as $arr){ ?>
                                            <option value="<?= $arr['id']; ?>" <?php if($projectData->subSubCategoryID == $arr['id']){ ?>SELECTED<?php } ?>><?php echo $arr['title']; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project Description:<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="name" class="form-control col-md-7 col-xs-12" name="projectDescription"  required="required" type="text" value="<?php echo !empty($projectData) ? $projectData->projectDescription: ''   ?>">
                            </div>
                        </div>  
                        <?php 
                        //print_r($attrArray);die;
                        if(isset($attrArray) && !empty($attrArray)){
                            foreach($attrArray as $atar){
                                if($atar['slugname'] == 'cat_author'){?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project Author:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="name" class="form-control col-md-7 col-xs-12" name="Metadata[cat_author]"  required="required" type="text" value="<?php echo $atar['slugvalue'];   ?>">
                                    </div>
                                </div> 
                                <?php 
                                }
                                if($atar['slugname'] == 'cat_year'){?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Year:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="name" class="form-control col-md-7 col-xs-12" name="Metadata[cat_year]"  required="required" type="text" value="<?php echo $atar['slugvalue'];   ?>">
                                    </div>
                                </div> 
                                <?php 
                                }
                                if($atar['slugname'] == 'cat_photography-by'){?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Photography By:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="name" class="form-control col-md-7 col-xs-12" name="Metadata[cat_photography-by]"  required="required" type="text" value="<?php echo $atar['slugvalue'];   ?>">
                                    </div>
                                </div> 
                                <?php 
                                }
                                if($atar['slugname'] == 'cat_description-by-author'){?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Description By Author:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="name" class="form-control col-md-7 col-xs-12" name="Metadata[cat_description-by-author]"  required="required" type="text" value="<?php echo $atar['slugvalue'];   ?>">
                                    </div>
                                </div> 
                                <?php 
                                }
                                if($atar['slugname'] == 'cat_project-image'){?>
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project Image:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_project-image" value="" class="form-control col-md-7 col-xs-12" />
                                        <img class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width='200px'>
                                    </div>
                                </div> 
                                <?php 
                                }
                                if($atar['slugname'] == 'cat_project-short-video'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Video of Project:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_project-short-video" value="" class="form-control col-md-7 col-xs-12" />
                                        <video controls="" width="100%" height="300">
                                            <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/mp4">
                                            <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                                <?php }
                                if($atar['slugname'] == 'cat_duration'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Duration:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[cat_duration]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_video-file'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Video of Project:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_video-file" value="" class="form-control col-md-7 col-xs-12" />
                                        <video controls="" width="100%" height="300">
                                            <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/mp4">
                                            <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_directed-by'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Directed By:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[at_directed-by]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_written-by'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Written By:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[cat_written-by]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_edited-by'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Edited By:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[cat_edited-by]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_produced-by'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Produced By:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[cat_produced-by]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_music-by'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Music By:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[cat_music-by]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                            
                                if($atar['slugname'] == 'cat_synopsis'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Synopsis:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[cat_synopsis]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_popular-in'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Popular in:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="Metadata[cat_popular-in]" value="<?php echo $atar['slugvalue'];?>" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_video-cover-image-'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Cover Image:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_video-cover-image-" value="" class="form-control col-md-7 col-xs-12" />
                                        <img class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width='200px'>
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_cover-image'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Cover Image:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_cover-image" value="" class="form-control col-md-7 col-xs-12" />
                                        <img class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width='200px'>
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_song-or-album-cover-image'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Album Image:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_song-or-album-cover-image" value="" class="form-control col-md-7 col-xs-12" />
                                        <img class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width='200px'>
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_audio-file'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Audio file:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_audio-file" value="" class="form-control col-md-7 col-xs-12" />
                                        <audio controls>
                                    <source src="<?php echo base_url($path.$atar['slugvalue']);?>" type="audio/ogg">
                                    <source src="<?php echo base_url($path.$atar['slugvalue']);?>" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_videoclip-file'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Video of Project:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_videoclip-file" value="" class="form-control col-md-7 col-xs-12" />
                                        <video controls="" width="100%" height="300">
                                            <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/mp4">
                                            <source src='<?php echo base_url($path.$atar['slugvalue']);?>' type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div> 
                                <?php }
                                if($atar['slugname'] == 'cat_document'){?>
                                    
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">document:<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="file" name="cat_document" value="" class="form-control col-md-7 col-xs-12" />
                                        <iframe class='image_preview' src='<?php echo base_url($path.$atar['slugvalue']);?>' width="500px" height="500px"></iframe>
                                    </div>
                                </div> 
                                <?php }
                            }
                        }
                        ?>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Status: *</label>
                            <?php //echo  $adData->siteadd_status;die;  ?>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <input type="radio" class="add_extra_field set-checkbox"  <?php if($projectData->status=='Y'){ echo "checked=checked";}  ?> name="status" value='Y' > <span class="check-text">Active</span>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <input type="radio" <?php if($projectData->status=='N'){ echo "checked=checked";}  ?> class="add_extra_field set-checkbox" name="status" value='N'> <span class="check-text">Blocked</span>
                            </div>
                        </div>
                        <div class="item form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="send" type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
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