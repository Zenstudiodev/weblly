<!DOCTYPE html>
<html lang="en">
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>webllywood-backend : Front End Images</title>
        <?php echo $header_scripts;?>
    </head>
    <?php  $form_action = base_url('update-image'); ?>
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
                            <?php if($this->session->userdata('image_error')) { ?>
                            <div class="x_content bs-example-popovers">
                                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Error!</strong> 
                                    <?php echo $this->session->userdata('image_error');?>
                                    <?php $this->session->unset_userdata('image_error');
                                    $this->session->unset_userdata('image_success');?>
                                </div>
                            </div>
                            <?PHP } else if($this->session->userdata('image_success')) { ?>
                            <div class="x_content bs-example-popovers">
                                <div class="alert alert-sucess alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Sucess!</strong>
                                    <?php echo $this->session->userdata('image_success');?>
                                    <?php $this->session->unset_userdata('image_success');?>
                                </div>
                            </div>
                            <?PHP } ?>
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Frontend Edit Images</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <form novalidate action="<?php echo $form_action; ?>" enctype="multipart/form-data" method="post">
                                            <div class="col-md-12 col-sm-12 col-sx-12 non-pad">
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>Banner Image 1:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file" id="image-upload" name="banner1">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->banner1)?>" onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'" class="img-list-tag">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                            <label>Banner Image 2:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                            <input type="file"id="image-upload2"  name="banner2">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->banner2)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                            <label>Banner Image 3:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                            <input type="file" id="image-upload3" name="banner3">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->banner3)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                            <label>Banner Image 4:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                            <input type="file" id="image-upload4"  class="image-upload" name="banner4">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->banner4)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                            <label>Banner Image 5:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                            <input type="file" id="image-upload5"  name="banner5">
                                                        <!-- </div> -->
                                                    </diV>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->banner5)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">   
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clearfix"></div>
                                            <div class="ln_solid" style="margin:0 0 20px 0;"></div>
                                            
                                            <div class="col-md-12 col-sm-12 col-sx-12 non-pad">
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                            <label>Subscribe Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                            <input type="file" id="image-upload" name="suscribe">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->subscribe)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <label>Hall-of-fame Image:</label>
                                                        <input type="file" id="image-upload" name="hall_of_fame">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->hall_of_fame)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <label>Price List Image:</label>
                                                        <input type="file" id="image-upload" name="price_list">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->price_list)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="x_title">
                                                <h4>CMS Pages Images</h4>
                                                <div class="clearfix"></div>
                                            </div>
                                            
                                            <div class="col-md-12 col-sm-12 col-sx-12 non-pad">
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>CSM Page Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="cms_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->cms_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>Contact Page Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="contact_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->contact_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class="clearfix"></div>
                                            <div class="ln_solid" style="margin:0 0 20px 0;"></div> -->
                                            <div class="x_title">
                                                <h4>Article Images</h4>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-sx-12 non-pad">
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>Vedio list page Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="video_list_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->video_list_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>Audio list page Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="audio_list_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->audio_list_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>Art list page Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="art_list_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->art_list_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>Writing list page Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="writing_list_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->writing_list_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>Article Detail page Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="article_detail_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->article_detail_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="x_title">
                                                <h4>User Dashboard Images</h4>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-sx-12 non-pad">
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>User Dashboard Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="user_dashboard_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->user_dashboard_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>User Playlist Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="user_playlist_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->user_playlist_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>User Play Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="user_play_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->user_play_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="col-md-7 vertical">
                                                        <!-- <div class="col-md-4"> -->
                                                        <label>User Favorite Image:</label>
                                                        <!-- </div>
                                                        <div class="col-md-3"> -->
                                                        <input type="file"  id="image-upload" name="user_favorite_image">
                                                        <!-- </div> -->
                                                    </div>
                                                    <div class="col-md-5">
                                                        <img src="<?php echo base_url('../assets/upload/frontendimage/'.$data->user_favorite_image)?>" class="img-list-tag"  onerror="this.src='<?php echo base_url("../assets/images/no_image.png");?>'">  
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-12 m-b-20 form-group">
                                                <div class="ln_solid"></div>
                                                <div class="col-md-6 col-md-offset-5">
                                                    <button id="send" type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>
                                        </form>                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- /top tiles -->
                    </div>
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
